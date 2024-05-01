<?php

class Project_comments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'project_comments';
        parent::__construct($this->table);
        parent::init_activity_log("project_comment", "description", "project", "project_id");
    }

    function schema() {
        return array(
            "id" => array(
                "label" => lang("id"),
                "type" => "int"
            ),
            "created_by" => array(
                "label" => lang("created_by"),
                "type" => "foreign_key",
                "linked_model" => $this->Users_model,
                "label_fields" => array("first_name", "last_name"),
            ),
            "created_at" => array(
                "label" => lang("created_date"),
                "type" => "date_time"
            ),
            "description" => array(
                "label" => lang("comment"),
                "type" => "text"
            ),
            "project_id" => array(
                "label" => lang("project"),
                "type" => "foreign_key",
                "linked_model" => $this->Projects_model,
                "label_fields" => array("title"),
            ),
            "task_id" => array(
                "label" => lang("task"),
                "type" => "foreign_key",
                "linked_model" => $this->Tasks_model,
                "label_fields" => array("id"),
            ),
            "file_id" => array(
                "label" => lang("project"),
                "type" => "foreign_key",
                "linked_model" => $this->Project_files_model,
                "label_fields" => array("id"),
            ),
            "customer_feedback_id" => array(
                "label" => lang("feedback"),
                "type" => "foreign_key",
                "linked_model" => $this->Project_comments_model,
                "label_fields" => array("description"),
            ),
            "comment_id" => array(
                "label" => lang("comment"),
                "type" => "foreign_key",
                "linked_model" => $this->Project_comments_model,
                "label_fields" => array("description"),
            ),
            "deleted" => array(
                "label" => lang("deleted"),
                "type" => "int"
            )
        );
    }

    function get_details($options = array()) {
        $project_comments_table = $this->db->dbprefix('project_comments');
		$projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $project_comments_table.id=$id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_comments_table.project_id=$project_id AND $project_comments_table.task_id=0 AND $project_comments_table.file_id=0 and $project_comments_table.customer_feedback_id=0";
        }

        $task_id = get_array_value($options, "task_id");
        if ($task_id) {
            $where .= " AND $project_comments_table.task_id=$task_id";
        }

        $file_id = get_array_value($options, "file_id");
        if ($file_id) {
            $where .= " AND $project_comments_table.file_id=$file_id";
        }

        $customer_feedback_id = get_array_value($options, "customer_feedback_id");
        if ($customer_feedback_id) {
            $where .= " AND $project_comments_table.customer_feedback_id=$customer_feedback_id";
        }
        
        $customer_feedback_id_not = get_array_value($options, "customer_feedback_id_not");
        if ( isset( $customer_feedback_id_not ) ) {
            $where .= " AND $project_comments_table.customer_feedback_id!=$customer_feedback_id_not";
        }
		
	$view_status = get_array_value($options, "view_status");
        if ($view_status) {
            $where .= " AND $project_comments_table.view_status=$view_status";
        }
        
        $comment_project_id = get_array_value($options, "comment_project_id");
        if ($comment_project_id) {
            $where .= " AND $project_comments_table.project_id=$comment_project_id";
        }
		
	$created_by = get_array_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $project_comments_table.created_by!=$created_by";
        }
		/*
		$project_type = get_array_value($options, "project_type");
        if ($project_type) {
            $where .= " AND $projects_table.type=$project_type";
        }
		
		$project_user = get_array_value($options, "project_user");
        if ($project_user) {
            $where .= " AND $projects_table.client_id=$project_user";
        }*/
		


        //show the main comments in descending mode
        //but show the replies in ascedning mode
        $sort = " ASC";
        $comment_id = get_array_value($options, "comment_id");
        if ($comment_id) {
            $where .= " AND $project_comments_table.comment_id=$comment_id";
            $sort = "ASC";
        } else {
            $where .= " AND $project_comments_table.comment_id=0";
        }

        $sql = "SELECT $project_comments_table.*, $project_comments_table.id AS parent_commment_id, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar, $users_table.user_type,
            (SELECT COUNT($project_comments_table.id) as total_replies FROM $project_comments_table WHERE $project_comments_table.comment_id=parent_commment_id) AS total_replies
        FROM $project_comments_table
        LEFT JOIN $users_table ON $users_table.id= $project_comments_table.created_by
        WHERE $project_comments_table.deleted=0 $where
        ORDER BY $project_comments_table.created_at $sort";
        return $this->db->query($sql);
    }
    
    
    function get_last_comment($options = array()) {
        $project_comments_table = $this->db->dbprefix('project_comments');
	$projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');
        $where = "";
        
        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $project_comments_table.project_id=$project_id";
        }
        $sort = " DESC";

        $sql = "SELECT *
        FROM $project_comments_table
        WHERE deleted=0 $where
        ORDER BY created_at $sort LIMIT 0,1";
        $result = $this->db->query($sql);
        return $result->row();
    }
	
	function update_comment($data, $comment_id) {
        parent::use_table("project_comments");
        $where = array("id" => $comment_id);
		return parent::update_where($data, $where);
    }

    function save_comment($data) {
        //set extra info
        $comment_id = get_array_value($data, "comment_id");
        $file_id = get_array_value($data, "file_id");
        $task_id = get_array_value($data, "task_id");
        $customer_feedback_id = get_array_value($data, "customer_feedback_id");
        if ($comment_id) {
            $comment_info = parent::get_one($comment_id);
            $reply_type = "project_comment_reply";
            $data["project_id"] = $comment_info->project_id;
            $type = "";
            $type_id = "";
            if ($comment_info->task_id) {
                $data["task_id"] = $comment_info->task_id;
                $type = "task";
                $type_id = "task_id";
                $reply_type = "task_comment_reply";
            } else if ($comment_info->file_id) {
                $data["file_id"] = $comment_info->file_id;
                $type = "file";
                $type_id = "file_id";
                $reply_type = "file_comment_reply";
            } else if ($comment_info->customer_feedback_id) {
                $data["customer_feedback_id"] = $comment_info->customer_feedback_id;
                $type = "customer_feedback";
                $type_id = "customer_feedback_id";
                $reply_type = "customer_feedback_reply";
            }
            parent::init_activity_log($reply_type, "description", "project", "project_id", $type, $type_id);
        } else if ($file_id) {
            $file_info = $this->Project_files_model->get_one($file_id);
            $data["project_id"] = $file_info->project_id;
            parent::init_activity_log("project_comment", "description", "project", "project_id", "file", "file_id");
        } else if ($task_id) {
            $task_info = $this->Tasks_model->get_one($task_id);
            $data["project_id"] = $task_info->project_id;
            parent::init_activity_log("task_comment", "description", "project", "project_id", "task", "task_id");
        } else if ($customer_feedback_id) {
            $data["project_id"] = $customer_feedback_id;
            parent::init_activity_log("customer_feedback", "description", "project", "project_id", "customer_feedback", "customer_feedback_id");
        }
        return parent::save($data);
    }

}
