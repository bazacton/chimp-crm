<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once("Pre_loader.php");

class roles extends Pre_loader {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    //load the role view
    function index() {
        $this->template->rander("roles/index");
    }

    //load the role add/edit modal
    function modal_form() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Roles_model->get_one($this->input->post('id'));
        $view_data['roles_dropdown'] = array("" => "-") + $this->Roles_model->get_dropdown_list(array("title"), "id");
        $this->load->view('roles/modal_form', $view_data);
    }

    //get permisissions of a role
    function permissions($role_id) {
        if ($role_id) {
            $view_data['model_info'] = $this->Roles_model->get_one($role_id);

            $view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
            $ticket_types_dropdown = array();
            $ticket_types = $this->Ticket_types_model->get_all_where(array("deleted" => 0))->result();
            foreach ($ticket_types as $type) {
                $ticket_types_dropdown[] = array("id" => $type->id, "text" => $type->title);
            }
            $view_data['ticket_types_dropdown'] = json_encode($ticket_types_dropdown);

            $permissions = unserialize($view_data['model_info']->permissions);

            if (!$permissions) {
                $permissions = array();
            }
            $view_data['leave'] = get_array_value($permissions, "leave");
            $view_data['leave_specific'] = get_array_value($permissions, "leave_specific");
            $view_data['attendance_specific'] = get_array_value($permissions, "attendance_specific");

            $view_data['attendance'] = get_array_value($permissions, "attendance");
            $view_data['invoice'] = get_array_value($permissions, "invoice");
            $view_data['expense'] = get_array_value($permissions, "expense");
            $view_data['client'] = get_array_value($permissions, "client");
            $view_data['ticket'] = get_array_value($permissions, "ticket");
            $view_data['announcement'] = get_array_value($permissions, "announcement");

            $view_data['can_create_projects'] = get_array_value($permissions, "can_create_projects");
            $view_data['can_edit_projects'] = get_array_value($permissions, "can_edit_projects");
            $view_data['can_delete_projects'] = get_array_value($permissions, "can_delete_projects");

            $view_data['can_add_remove_project_members'] = get_array_value($permissions, "can_add_remove_project_members");

            $view_data['can_create_tasks'] = get_array_value($permissions, "can_create_tasks");
            $view_data['can_edit_tasks'] = get_array_value($permissions, "can_edit_tasks");
            $view_data['can_delete_tasks'] = get_array_value($permissions, "can_delete_tasks");
            $view_data['can_comment_on_tasks'] = get_array_value($permissions, "can_comment_on_tasks");

            $view_data['can_create_milestones'] = get_array_value($permissions, "can_create_milestones");
            $view_data['can_edit_milestones'] = get_array_value($permissions, "can_edit_milestones");
            $view_data['can_delete_milestones'] = get_array_value($permissions, "can_delete_milestones");

            $view_data['can_delete_files'] = get_array_value($permissions, "can_delete_files");


            $view_data['can_view_team_members_contact_info'] = get_array_value($permissions, "can_view_team_members_contact_info");
            $view_data['can_view_team_members_social_links'] = get_array_value($permissions, "can_view_team_members_social_links");

            $this->load->view("roles/permissions", $view_data);
        }
    }

    //save a role
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $copy_settings = $this->input->post('copy_settings');
        $data = array(
            "title" => $this->input->post('title'),
        );

        if ($copy_settings) {
            $role = $this->Roles_model->get_one($copy_settings);
            $data["permissions"] = $role->permissions;
        }

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save permissions of a role
    function save_permissions() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        $leave = $this->input->post('leave_permission');
        $leave_specific = "";
        if ($leave === "specific") {
            $leave_specific = $this->input->post('leave_permission_specific');
        }

        $attendance = $this->input->post('attendance_permission');
        $attendance_specific = "";
        if ($attendance === "specific") {
            $attendance_specific = $this->input->post('attendance_permission_specific');
        }

        $invoice = $this->input->post('invoice_permission');
        $expense = $this->input->post('expense_permission');
        $client = $this->input->post('client_permission');
        $ticket = $this->input->post('ticket_permission');

        $can_create_projects = $this->input->post('can_create_projects');
        $can_edit_projects = $this->input->post('can_edit_projects');
        $can_delete_projects = $this->input->post('can_delete_projects');

        $can_add_remove_project_members = $this->input->post('can_add_remove_project_members');

        $can_create_tasks = $this->input->post('can_create_tasks');
        $can_edit_tasks = $this->input->post('can_edit_tasks');
        $can_delete_tasks = $this->input->post('can_delete_tasks');
        $can_comment_on_tasks = $this->input->post('can_comment_on_tasks');

        $can_create_milestones = $this->input->post('can_create_milestones');
        $can_edit_milestones = $this->input->post('can_edit_milestones');
        $can_delete_milestones = $this->input->post('can_delete_milestones');

        $can_delete_files = $this->input->post('can_delete_files');

        $announcement = $this->input->post('announcement_permission');

        $can_view_team_members_contact_info = $this->input->post('can_view_team_members_contact_info');
        $can_view_team_members_social_links = $this->input->post('can_view_team_members_social_links');


        $permissions = array(
            "leave" => $leave,
            "leave_specific" => $leave_specific,
            "attendance" => $attendance,
            "attendance_specific" => $attendance_specific,
            "invoice" => $invoice,
            "expense" => $expense,
            "client" => $client,
            "ticket" => $ticket,
            "announcement" => $announcement,
            "can_create_projects" => $can_create_projects,
            "can_edit_projects" => $can_edit_projects,
            "can_delete_projects" => $can_delete_projects,
            "can_add_remove_project_members" => $can_add_remove_project_members,
            "can_create_tasks" => $can_create_tasks,
            "can_edit_tasks" => $can_edit_tasks,
            "can_delete_tasks" => $can_delete_tasks,
            "can_comment_on_tasks" => $can_comment_on_tasks,
            "can_create_milestones" => $can_create_milestones,
            "can_edit_milestones" => $can_edit_milestones,
            "can_delete_milestones" => $can_delete_milestones,
            "can_delete_files" => $can_delete_files,
            "can_view_team_members_contact_info" => $can_view_team_members_contact_info,
            "can_view_team_members_social_links" => $can_view_team_members_social_links
        );

        $data = array(
            "permissions" => serialize($permissions),
        );

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete or undo a role
    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Roles_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Roles_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get role list data
    function list_data() {
        $list_data = $this->Roles_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of role list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Roles_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //make a row of role list table
    private function _make_row($data) {
        return array("<span data-id='$data->id' class='role-row'>" . $data->title . "</span>",
            modal_anchor(get_uri("roles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_role'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_role'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("roles/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */