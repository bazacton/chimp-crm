<?php

class Invoice_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'invoice_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoices_table = $this->db->dbprefix('invoices');
        $clients_table = $this->db->dbprefix('clients');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_items_table.id=$id";
        }
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        WHERE $invoice_items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_item_suggestion($keyword = "") {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        
        $where = "";
        if ($keyword) {
            $where = " AND $invoice_items_table.title LIKE '%$keyword%'";
        }

        $sql = "SELECT $invoice_items_table.title
        FROM $invoice_items_table
    
        WHERE $invoice_items_table.deleted=0 $where
        GROUP BY $invoice_items_table.title LIMIT 10";
        return $this->db->query($sql)->result();
    }

}
