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

  public function get_pembiayaan_byofficer($officer_id='')
  {
      if($officer_id==''||$officer_id==NULL)
      {
        $this->rest->set_error('Please specify an officer id.');
        $this->rest->render();
      }
      else
      {
        $clients = $this->client->get_clients_pembiayaan_by_officer($officer_id);
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
        $n       = 0;
        foreach ($clients as $client) {
          $attendances[$n]                           = (array) $this->client->get_client_attendance_by_account($client->client_account);
          $clients[$n]                               = (array) $clients[$n];
          $clients[$n]['client_max_angsuranke']      = $attendances[$n]['client_max_angsuranke'];
          $clients[$n]['client_angsuranke']          = $attendances[$n]['client_angsuranke'];
          $clients[$n]['client_hadir']               = $attendances[$n]['client_hadir'];
          $clients[$n]['client_sakit']               = $attendances[$n]['client_sakit'];
          $clients[$n]['client_cuti']                = $attendances[$n]['client_cuti'];
          $clients[$n]['client_izin']                = $attendances[$n]['client_izin'];
          $clients[$n]['client_absen']               = $attendances[$n]['client_absen'];
          $clients[$n]['client_tanggungrenteng']     = $attendances[$n]['client_tanggungrenteng'];
          $n++;
        }
        $this->rest->set_data($clients);
        $this->rest->render();
      }
  }

  public function get_bygroup_proposal($group_id)
  {
      if($group_id==''||$group_id==NULL)
      {
        $this->rest->set_error('Please specify a group id.');
        $this->rest->render();
      }
      else
      {
        $clients = $this->client->get_proposing_clients_by_group($group_id);
        $n       = 0;
        foreach ($clients as $client) {
          $attendances[$n]                           = (array) $this->client->get_client_attendance_by_account($client->client_account);
          $clients[$n]                               = (array) $clients[$n];
          $clients[$n]['client_max_angsuranke']      = $attendances[$n]['client_max_angsuranke'];
          $clients[$n]['client_angsuranke']          = $attendances[$n]['client_angsuranke'];
          $clients[$n]['client_hadir']               = $attendances[$n]['client_hadir'];
          $clients[$n]['client_sakit']               = $attendances[$n]['client_sakit'];
          $clients[$n]['client_cuti']                = $attendances[$n]['client_cuti'];
          $clients[$n]['client_izin']                = $attendances[$n]['client_izin'];
          $clients[$n]['client_absen']               = $attendances[$n]['client_absen'];
          $clients[$n]['client_tanggungrenteng']     = $attendances[$n]['client_tanggungrenteng'];
          $n++;
        }
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
        $n       = 0;
        foreach ($clients as $client) {
          $attendances[$n]                           = (array) $this->client->get_client_attendance_by_account($client->client_account);
          //$att[$n]                                 = $attendances[$n][0];
          //echo '<br/><br/>'.$att[$n]->client_hadir.'<br/>';
          $clients[$n]                               = (array) $clients[$n];
          $clients[$n]['client_max_angsuranke']      = $attendances[$n][0]->client_total_angsuranke;
          $clients[$n]['client_angsuranke']          = $attendances[$n][0]->client_angsuranke;
          $clients[$n]['client_hadir']               = $attendances[$n][0]->client_hadir;
          $clients[$n]['client_sakit']               = $attendances[$n][0]->client_sakit;
          $clients[$n]['client_cuti']                = $attendances[$n][0]->client_cuti;
          $clients[$n]['client_izin']                = $attendances[$n][0]->client_izin;
          $clients[$n]['client_absen']               = $attendances[$n][0]->client_absen;
          $clients[$n]['client_tanggungrenteng']     = $attendances[$n][0]->client_tanggungrenteng;
          //var_dump($clients[0]); die();
          $n++;
        }
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

  public function financing_proposal($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_financing_proposal_by_account($client_account);
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

  public function simpledetail($client_account='')
  {
      if($client_account==''||$client_account==NULL)
      {
        $this->rest->set_error('Please specify an account of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_simpledetails_by_account($client_account);
        $this->rest->set_data($client);
        $this->rest->render();
      }
  }

  public function residence($client_id='')
  {
      if($client_id==''||$client_id==NULL)
      {
        $this->rest->set_error('Please specify an ID of client.');
        $this->rest->render();
      }
      else
      {
        $client = $this->client->get_client_residence_by_id($client_id);
        $this->rest->set_data($client);
        $this->rest->render();
      }
  }

  private function apikey()
  {
      return sha1('amArth4Micr0Financ3');
      //?key=9f48e2d2bfc6e7ba980563dba3e48e53915c90ea
      //$this->rest->set_data(array('sha' => sha1('amArth4Micr0Financ3')));
      //$this->rest->render();
  }


}
