<?php

class Expenses_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'expenses';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expenses_table = $this->db->dbprefix('expenses');
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $projects_table = $this->db->dbprefix('projects');
        $users_table = $this->db->dbprefix('users');


        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expenses_table.id=$id";
        }
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($expenses_table.expense_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $category_id = get_array_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $expenses_table.category_id=$category_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $where .= " AND $expenses_table.project_id=$project_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $expenses_table.user_id=$user_id";
        }

        $sql = "SELECT $expenses_table.*, $expense_categories_table.title as category_title, 
                 CONCAT($users_table.first_name, ' ', $users_table.last_name) AS linked_user_name,
                 $projects_table.title AS project_title
        FROM $expenses_table
        LEFT JOIN $expense_categories_table ON $expense_categories_table.id= $expenses_table.category_id
        LEFT JOIN $projects_table ON $projects_table.id= $expenses_table.project_id
        LEFT JOIN $users_table ON $users_table.id= $expenses_table.user_id
        WHERE $expenses_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_income_expenses_info() {
        $expenses_table = $this->db->dbprefix('expenses');
        $invoice_payments_table = $this->db->dbprefix('invoice_payments');
        $info = new stdClass();

        $sql1 = "SELECT SUM($invoice_payments_table.amount) as total_income
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0";
        $income = $this->db->query($sql1)->row();

        $sql2 = "SELECT SUM($expenses_table.amount) as total_expenses
        FROM $expenses_table
        WHERE $expenses_table.deleted=0";
        $expenses = $this->db->query($sql2)->row();

        $info->income = $income->total_income;
        $info->expneses = $expenses->total_expenses;
        return $info;
    }

}
