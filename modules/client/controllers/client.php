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

  public function detailed($group_id){
        $clients = $this->client->get_clients_in_details_by_group($group_id);
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

        //echo count($clients)." clients. <br/><br/>";
        echo "<br/><br/>";
        var_dump($clients);
        //echo "<br/><br/>";
        //echo $clients[0]['data_plafond'];
        //echo $attendances[0]['client_max_angsuranke'];
        //echo "<br/><br/>";
        //var_dump($attendances);
        //echo "<br/><br/>";
        //echo $attendances[1]['client_max_angsuranke'];

  }

  public function group($group_id){
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

        //echo count($clients)." clients. <br/><br/>";
        echo "<br/><br/>";
        var_dump($clients);
        //echo "<br/><br/>";
        //echo $clients[0]['data_plafond'];
        //echo $attendances[0]['client_max_angsuranke'];
        //echo "<br/><br/>";
        //var_dump($attendances);
        //echo "<br/><br/>";
        //echo $attendances[1]['client_max_angsuranke'];

  }

}
