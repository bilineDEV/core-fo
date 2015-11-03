<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends Api_Controller {

  //?key=9f48e2d2bfc6e7ba980563dba3e48e53915c90ea
  public function __construct() {
        parent::__construct();
        $this->load->model('officer/officer_model', 'officer');
        $this->load->model('group/group_model', 'group');
        $this->load->model('client/client_model', 'client');
  }

  public function get_byofficer($officer_id='')
  {
      if($officer_id==''||$officer_id==NULL)
      {
        $this->rest->set_error('Please specify an officer id.');
        $this->rest->render();
      }
      else
      {
        $clients = $this->client->get_clients_by_officer($officer_id);
        $this->rest->set_data($clients);
        $this->rest->render();
      }
  }

  public function get_bygroup($group_id='')
  {
      if($group_id==''||$group_id==NULL)
      {
        $this->rest->set_error('Please specify a group id.');
        $this->rest->render();
      }
      else
      {
        $clients = $this->client->get_clients_by_group($group_id);
        $this->rest->set_data($clients);
        $this->rest->render();
      }
  }

   public function get_bygroup_in_detail($group_id='')
   {
      if($group_id==''||$group_id==NULL)
      {
        $this->rest->set_error('Please specify a group id.');
        $this->rest->render();
      }
      else
      {
        $clients = $this->client->get_clients_in_details_by_group($group_id);
        $this->rest->set_data($clients);
        $this->rest->render();
      }
   }

  public function attendance($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_attendance_by_account($client_account);
        $this->rest->set_data($client);
        $this->rest->render();
      }
  }

  public function balance($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_balance_by_account($client_account);
        $this->rest->set_data($client);
        $this->rest->render();
      }
  }

  public function financing($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_financing_by_account($client_account);
        $this->rest->set_data($client);
        $this->rest->render();
      }
  }

  public function detail($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_details_by_account($client_account);
        $this->rest->set_data($client);
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
