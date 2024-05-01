<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once("Pre_loader.php");

class Expenses extends Pre_loader {

    function __construct() {
        parent::__construct();

        $this->init_permission_checker("expense");

        $this->access_only_allowed_members();
    }

    //load the expenses list view
    function index() {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0))->result();
        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }
        $view_data['categories_dropdown'] = json_encode($categories_dropdown);
        $this->template->rander("expenses/index", $view_data);
    }

    //load the expenses list yearly view
    function yearly() {
        $this->load->view("expenses/yearly_expenses");
    }

    //load the add/edit expense form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Expenses_model->get_one($this->input->post('id'));
        $view_data['categories_dropdown'] = $this->Expense_categories_model->get_dropdown_list(array("title"));

        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;

        $view_data['projects_dropdown'] = array("0" => "-") + $this->Projects_model->get_dropdown_list(array("title"));

        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $this->input->post('project_id');
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');

        $view_data['model_info'] = $model_info;
        $this->load->view('expenses/modal_form', $view_data);
    }

    //save an expense
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "expense_date" => "required",
            "category_id" => "required",
            "amount" => "required"
        ));

        $id = $this->input->post('id');
        $data = array(
            "expense_date" => $this->input->post('expense_date'),
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category_id'),
            "amount" => unformat_currency($this->input->post('amount')),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('expense_user_id'),
        );

        $save_id = $this->Expenses_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an expense
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Expenses_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Expenses_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get the expnese list data
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_id);
        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of expnese list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Expenses_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of expnese list
    private function _make_row($data) {

        $description = $data->description;
        if ($data->project_title) {
            if ($description) {
                $description.="<br /> ";
            }
            $description .= lang("project") . ": " . $data->project_title;
        }

        if ($data->linked_user_name) {
            if ($description) {
                $description.="<br /> ";
            }
            $description .= lang("team_member") . ": " . $data->linked_user_name;
        }


        return array(
            $data->expense_date,
            format_to_date($data->expense_date),
            $data->category_title,
            $data->title,
            $description,
            to_currency($data->amount),
            modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_expense'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_expense'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("expenses/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file expenses.php */
/* Location: ./application/controllers/expenses.php */