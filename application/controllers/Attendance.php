<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once("Pre_loader.php");

class Attendance extends Pre_loader {

    function __construct() {
        parent::__construct();

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("attendance");
    }

    //check ip restriction for none admin users
    private function check_allowed_ip() {
        if (!$this->login_user->is_admin) {
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
                    redirect("forbidden");
                }
            }
        }
    }

    //only admin or assigend members can access/manage other member's attendance
    protected function access_only_allowed_members($user_id = 0) {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                redirect("forbidden");
            }
        }
    }

    //show attendance list view
    function index() {
        $this->template->rander("attendance/index");
    }

    //show add/edit attendance modal
    function modal_form() {
        $user_id = 0;

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));
        if ($view_data['model_info']->id) {
            $user_id = $view_data['model_info']->user_id;

            $this->access_only_allowed_members($user_id, true);
        }

        if ($user_id) {
            //edit mode. show user's info
            $view_data['team_members_info'] = $this->Users_model->get_one($user_id);
        } else {
            //new add mode. show users dropdown
            //don't show none allowed members in dropdown
            if ($this->access_type === "all") {
                $where = array("user_type" => "staff");
            } else {
                if (!count($this->allowed_members)) {
                    redirect("forbidden");
                }
                $where = array("user_type" => "staff", "id !=" => $this->login_user->id, "where_in" => array("id" => $this->allowed_members));
            }

            $view_data['team_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);
        }

        $this->load->view('attendance/modal_form', $view_data);
    }

    //add/edit attendance record
    function save() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric",
            "in_date" => "required",
            "out_date" => "required",
            "in_time" => "required",
            "out_time" => "required"
        ));

        //convert to 24hrs time format
        $in_time = $this->input->post('in_time');
        $out_time = $this->input->post('out_time');

        if (get_setting("time_format") != "24_hours") {
            $in_time = convert_time_to_24hours_format($in_time);
            $out_time = convert_time_to_24hours_format($out_time);
        }

        //join date with time
        $in_date_time = $this->input->post('in_date') . " " . $in_time;
        $out_date_time = $this->input->post('out_date') . " " . $out_time;

        //add time offset
        $in_date_time = convert_date_local_to_utc($in_date_time);
        $out_date_time = convert_date_local_to_utc($out_date_time);

        $data = array(
            "in_time" => $in_date_time,
            "out_time" => $out_date_time,
            "status" => "pending"
        );

        //save user_id only on insert and it will not be editable
        if ($id) {
            $info = $this->Attendance_model->get_one($id);
            $user_id = $info->user_id;
        } else {
            $user_id = $this->input->post('user_id');
            $data["user_id"] = $user_id;
        }

        $this->access_only_allowed_members($user_id);


        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //clock in / clock out
    function log_time() {
        $this->Attendance_model->log_time($this->login_user->id);
        clock_widget();
    }

    //delete/undo attendance record
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->access_type !== "all") {
            $info = $this->Attendance_model->get_one($id);
            $this->access_only_allowed_members($info->user_id);
        }

        if ($this->input->post('undo')) {
            if ($this->Attendance_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Attendance_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* get all attendance of a given duration */

    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load attendance attendance info view
    function attendance_info() {
        $view_data['user_id'] = $this->login_user->id;
        if ($this->input->is_ajax_request()) {
            $this->load->view("team_members/attendance_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("team_members/attendance_info", $view_data);
        }
    }

    //get a row of attendnace list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Attendance_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of attendance list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $out_time = $data->out_time;
        if (!($out_time * 1)) {
            $out_time = "";
        }

        $to_time = strtotime($data->out_time);
        if (!$out_time) {
            $to_time = strtotime($data->in_time);
        }
        $from_time = strtotime($data->in_time);

        $option_links = modal_anchor(get_uri("attendance/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_attendance'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_attendance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("attendance/delete"), "data-action" => "delete"));

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        return array(
            get_team_member_profile_link($data->user_id, $user),
            format_to_date($data->in_time),
            format_to_time($data->in_time),
            $out_time ? format_to_date($out_time) : "-",
            $out_time ? format_to_time($out_time) : "-",
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            $option_links
        );
    }

}

/* End of file attendance.php */
/* Location: ./application/controllers/attendance.php */