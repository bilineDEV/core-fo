<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends Api_Controller {

  //?key=9f48e2d2bfc6e7ba980563dba3e48e53915c90ea
  public function __construct(){
        parent::__construct();
        $this->load->model('client_pembiayaan_model');
        $this->load->model('client_model');
        $this->load->model('sector_model');
  }

  /*
  client_reg = 1,
  client_name, client_plafond, client_pengajuan_date, client_pencairan_date, client_tujuan,
  client_pembiayaanke, client_sector
  Notes:
  Bila dari API ada data_ke = 3, maka pengajuan kali ini dicatat sebagai client_pembiayaanke = 4 (Pengajuan)
  */
  public function register(){
        $client_reg   = $this->input->post("client_reg");
        if($client_reg == "1")
        {

          $client_id = $this->input->post("client_name");
          $plafond   = $this->input->post("client_plafond");
          $plafond   = str_replace(".", "", $plafond);

          if($client_id AND $plafond
            AND $this->input->post("client_pengajuan_date")
            AND $this->input->post("client_pencairan_date")
            AND $this->input->post("client_tujuan")
            AND $this->input->post("client_pembiayaanke")
            )
          {
            //INSERT PEMBIAYAAN
            $data_pembiayaan = array(
                  'data_client'       => $this->input->post("client_name"),
                  'data_tgl'          => $this->input->post("client_pengajuan_date"),
                  'data_date_accept'  => $this->input->post("client_pencairan_date"),
                  'data_plafond'      => $plafond,
                  'data_pengajuan'    => $plafond,
                  'data_tujuan'       => $this->input->post("client_tujuan"),
                  'data_sector'       => $this->input->post("client_sector"),
                  'data_ke'           => $this->input->post("client_pembiayaanke"),
                  'data_status'       => '2',
                  'data_jangkawaktu'  => '50'
            );
            $this->client_pembiayaan_model->insert($data_pembiayaan);

            //UPDATE n-th PEMBIAYAAN di table CLIENT
            $timestamp=date("Y-m-d H:i:s");
            $data_client = array(
                  'client_pembiayaan' => $this->input->post("client_pembiayaanke")
            );
            $this->client_model->update_pembiayaan($client_id, $data_client);
          }
        }
  }

  public function sector(){
        $sectors = $this->sector_model->get_all()->result();
        $this->rest->set_data($sectors);
        $this->rest->render();

  }

}
