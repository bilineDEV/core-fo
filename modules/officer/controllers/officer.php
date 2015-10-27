<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Officer extends Base_Controller {
  public function __construct() {
        parent::__construct();
        $this->load->model('officer/officer_model', 'officer');
        $this->load->model('group/group_model', 'group');
        $this->load->model('client/client_model', 'client');
  }

  public function index($officer_id=''){
        $officer = $this->officer->get_an_officer($officer_id);
        var_dump($officer);
  }

}
