<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends Base_Controller {
  public function __construct() {
        parent::__construct();
        $this->load->model('officer/officer_model', 'officer');
        $this->load->model('group/group_model', 'group');
        $this->load->model('client/client_model', 'client');
  }

  public function index($officer_id=''){
        $groups = $this->group->get_groups_by_officer($officer_id);
        var_dump($groups);
  }

}
