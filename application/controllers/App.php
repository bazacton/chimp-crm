<?php

class App extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('email');
    }

    function index() {
        //$this->Projects_model->alter_table();
        if ($this->Users_model->login_user_id()) {
            redirect('dashboard');
        } else {

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('app_name', 'APP Name', 'required');
            $this->form_validation->set_rules('description', lang('description'), 'required');
            //$this->form_validation->set_rules('company_name', lang('company_name'), 'required');
            $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
            $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
            $this->form_validation->set_rules('email', lang('email'), 'required|valid_email');
            $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]');
            $purchase_code_error = true;
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = NULL;
                $this->load->view('app/index', $data);
            } else {
                if ($this->input->post()) {
                    $formData = $this->input->post();
                    $formData['dated']  = date('Y-m-d H:i:s');
                    $settings_data = array(
                        'setting_name'  => 'app_request_form',
                        'setting_value'  => json_encode($formData),
                    );
                    $this->db->insert("settings", $settings_data);
                    
                    
                    $purchase_code = $this->input->post('purchase_code');
                    $responseData = $this->verify_purchase_code($purchase_code);
                    if (!empty($responseData)) {
                        $support_until = isset($responseData->supported_until) ? date('Y-m-d H:i:s', strtotime($responseData->supported_until)) : '';
                        $current_date = date('Y-m-d H:i:s');
                        if ($responseData->item->id != 14221636) {
                            $data['error'] = 'Please Use correct purchase code for Jobcareer theme';
                            $this->load->view('app/index', $data);
                        } else if ($support_until < $current_date) {
                            $data['error'] = 'Please Renew Your Support License';
                            $this->load->view('app/index', $data);
                        } else {
                            $purchase_code_error = false;
                        }
                    }
                }
                $purchase_code_error = false;
                if ($purchase_code_error == false) {
                    if ($this->input->post('company_name') != '') {
                        $company_name = $this->input->post('company_name');
                    } else {
                        $company_name = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
                    }
                    $validate = $this->validate_company_email($company_name, $this->input->post('email'));

                    if ($validate != false) {
                        if ($validate != true) {
                            $company_id = $validate;
                        } else {
                            $company_id = $this->add_company($this->input->post(), $company_name);
                        }

                        $user_id = $this->add_user($this->input->post(), $company_id);
                        $project_id = $this->add_project($this->input->post(), $company_id);
                        
                        $this->add_app_request($this->input->post(), $project_id);

                        $this->save_quotation_member($project_id, $user_id);

                        log_notification("client_signup", array("client_id" => $company_id), $user_id);

                        $this->save_file($project_id, $this->input->post(), $user_id);

                        if ($company_id && $user_id && $project_id) {

                            if (!$this->Users_model->authenticate($this->input->post('email'), $this->input->post('password'))) {
                                $data['error'] = lang("authentication_failed");
                                $this->load->view('app/index', $data);
                            } else {
                                redirect('projects/all_projects');
                                exit;
                            }
                        } else {
                            $data['error'] = lang('something_went_wrong');
                            $this->load->view('app/index', $data);
                        }
                    }
                }
            }
        }
    }

    function add_company($form_data, $company_name) {

        $form_data = (object) $form_data;

        $data = array(
            "company_name" => $company_name,
            "created_date" => get_current_utc_time()
        );

        $save_id = $this->Clients_model->save($data);

        return $save_id;
    }

    function add_user($form_data, $company_id) {

        $form_data = (object) $form_data;

        $user_data = array(
            "first_name" => $form_data->first_name,
            "last_name" => $form_data->last_name,
            "email" => $form_data->email,
            "phone" => $form_data->phone,
            "skype" => $form_data->skype,
            "password" => md5($form_data->password),
            "client_id" => $company_id,
            "created_at" => get_current_utc_time(),
        );

        $save_id = $this->Users_model->save($user_data);

        return $save_id;
    }

    function validate_quotation_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    function upload_quotation_file() {
        upload_file_to_temp();
    }

    function add_project($form_data, $company_id) {

        $form_data = (object) $form_data;
        $theme_name = isset($form_data->theme_name) ? $form_data->theme_name : 'Jobcareer APP';
        $plusTwo = strtotime( '+2 weekday' );
        $project_data = array(
            "title" => $form_data->app_name,
            "description" => $form_data->description,
            "start_date" => date('Y-m-d'),
            "deadline" => date( 'Y-m-d', $plusTwo ),
            "client_id" => $company_id,
            "created_date" => get_current_utc_time(),
            "type" => 'project',
            "theme_name" => $theme_name,
        );

        $save_id = $this->Projects_model->save($project_data);
        return $save_id;
    }
    
    /*
     * Save Request into db
     */
    
    function add_app_request($form_data, $project_id) {

        $form_data = (object) $form_data;
        $request_data = array(
            "project_id"        => $project_id,
            "app_name"          => $form_data->app_name,
            "purchase_code"     => $form_data->purchase_code,
            "description"       => $form_data->description,
            "app_id"            => $form_data->app_id,
            "author_name"       => $form_data->author_name,
            "author_email"      => $form_data->author_email,
            "first_name"        => $form_data->first_name,
            "last_name"         => $form_data->last_name,
            "organization_unit" => $form_data->organization_unit,
            "organization"      => $form_data->organization,
            "city"              => $form_data->city,
            "state"             => $form_data->state,
            "country_code"      => $form_data->country_code,
            "date_added"        => date('Y-m-d H:i:s'),
        );

        $this->db->insert("app_requests", $request_data);
        return $project_id;
    }

    function validate_company_email($company_name, $email) {

        if ($this->Clients_model->is_company_exists($company_name)) {

            if ($this->Users_model->is_email_exists($email)) {

                return $this->Clients_model->is_company_exists($company_name)->id;
            }
        } else {

            if ($this->Users_model->is_email_exists($email)) {
                $data['error'] = lang("already_have_an_account_quotation");
                $this->load->view('quotation/index', $data);
                return false;
            }
        }

        return true;
    }

    function validate_email() {
        $email = $this->input->post('email');
        if ($this->Users_model->is_email_exists($email)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    function validate_company() {
        $company_name = $this->input->post('company_name');
        if ($this->Clients_model->is_company_exists($company_name)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    function save_file($project_id, $files_array, $user_id) {
        $success = false;
        $now = get_current_utc_time();
        $target_path = getcwd() . "/" . get_setting("project_file_path") . $project_id . "/";
        if (isset($files_array['file_names']) && count($files_array['file_names']) > 0) {
            foreach ($files_array['file_names'] as $key => $file_name) {
                $file_size = isset($files_array['file_sizes'][$key]) ? $files_array['file_sizes'][$key] : '';
                $new_file_name = move_temp_file($file_name, $target_path);
                if ($new_file_name) {
                    $data = array(
                        "project_id" => $project_id,
                        "file_name" => $new_file_name,
                        "description" => '',
                        "file_size" => $file_size,
                        "created_at" => $now,
                        "uploaded_by" => $user_id
                    );
                    $success = $this->Project_files_model->save($data);
                }
            }
        }
    }

    function save_quotation_member($project_id, $project_member_id) {
        $data = array(
            "project_id" => $project_id,
            "user_id" => $project_member_id
        );
        $save_id = $this->Project_members_model->save_member($data);
    }

    /*
     * Verify Purchase Code
     */

    public function verify_purchase_code($purchase_code = '') {
        $username = 'ChimpStudio';

        // Set API Key  

        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        $personal_token = "FXkbo6WD6cq0qcmqzxCkpD3rDnjY0nnf";
        $header = array();
        $header[] = 'Authorization: Bearer ' . $personal_token;
        $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
        $header[] = 'timeout: 20';

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $envatoRes = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($envatoRes);
        // Return output
        $output = (object) $output;
        return $output;
    }

}
