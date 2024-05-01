<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe extends CI_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
         $this->template->rander("attendance/index");
    }

    public function checkout() {
     
    }

}

/* End of file team_member.php */
/* Location: ./application/controllers/team_member.php */