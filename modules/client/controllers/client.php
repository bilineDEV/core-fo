<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends Base_Controller {
  public function __construct() {
        parent::__construct();
        $this->load->model('officer/officer_model', 'officer');
        $this->load->model('group/group_model', 'group');
        $this->load->model('client/client_model', 'client');
  }

  public function single($client_account=''){
        $client = $this->client->get_client_details_by_account($client_account);
        var_dump($client);
  }

}
