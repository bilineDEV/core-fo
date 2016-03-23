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

          $client_id = $this->input->post("client_id");
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
                  'data_client'       => $client_id,
                  'data_tgl'          => $this->input->post("client_pengajuan_date"),
                  'data_date_accept'  => $this->input->post("client_pencairan_date"),
                  'data_plafond'      => $plafond,
                  'data_pengajuan'    => $plafond,
                  'data_tujuan'       => $this->input->post("client_tujuan"),
                  'data_sector'       => $this->input->post("client_sector"),
                  'data_ke'           => $this->input->post("client_pembiayaanke"),
                  'data_status'       => '2',
                  'data_jangkawaktu'  => $this->input->post("client_tenor"),
                  'data_akad'         => $this->input->post("client_akad")
            );
    			  $this->db->trans_start();
    				//INSERT pengajuan di tbl_pembiayaan (PEMBIAYAAN)
    				$this->client_pembiayaan_model->insert($data_pembiayaan);
    				$pembiayaan_id = $this->db->insert_id();
    				$data_id = array( 'data_id' => $pembiayaan_id );		
    				$this->rest->set_data($data_pembiayaan);
    				$this->rest->set_requestparam($data_id);
    				
    				//UPDATE n-th PEMBIAYAAN SEORANG CLIENT di tbl_client (CLIENT)
    				$timestamp=date("Y-m-d H:i:s");
    				$data_client = array(
    					  'client_pembiayaan'    => $this->input->post("client_pembiayaanke"),
    					  'client_pembiayaan_id' => $pembiayaan_id
    				);
    				$this->client_model->update_pembiayaan($client_id, $data_client);

    			  $this->db->trans_complete();
          }
          else
          {
			      $this->rest->set_error('Missing Parameters');
		      }
        }
        else
        {
			   $this->rest->set_error('Missing Flag Registration');
		    }		
		
		$this->rest->render();
  }

  public function sector(){
        $sectors = $this->sector_model->get_all()->result();
        $this->rest->set_data($sectors);
        $this->rest->render();

  }

}
