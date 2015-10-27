<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends Api_Controller {

  //?key=9f48e2d2bfc6e7ba980563dba3e48e53915c90ea
  public function __construct() {
        parent::__construct();
        $this->load->model('officer/officer_model', 'officer');
        $this->load->model('group/group_model', 'group');
        $this->load->model('client/client_model', 'client');
    }

  public function get($officer_id='')
  {
      if($officer_id==''||$officer_id=='all')
      {
        $officers = $this->officer->get_officers();
        $this->rest->set_data($officers);
        $this->rest->render();
      }
      else
      {
        $officer = $this->officer->get_an_officer($officer_id);
        $this->rest->set_data($officer);
        $this->rest->render();
      }
  }

  public function login()
  {
      $username  = $this->input->post('user');
      $password  = sha1($this->input->post('pass'));
      $officer   = $this->officer->get_an_officer_via_login($username, $password);

      if($officer)
      {
        $this->rest->set_data($officer);
        $this->rest->render();
      }
      else
      {
        $this->rest->set_error('Wrong Username/Password.');
        $this->rest->render();
      }
  }

  private function apikey()
  {
      return sha1('amArth4Micr0Financ3');
      //$this->rest->set_data(array('sha' => sha1('amArth4Micr0Financ3')));
      //$this->rest->render();
  }


}
