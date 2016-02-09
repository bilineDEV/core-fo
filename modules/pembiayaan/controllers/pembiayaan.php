<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembiayaan extends Base_Controller {

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
    //set form validation
    //$this->form_validation->set_rules('ts_date', 'Tanggal', 'required');
    //$this->form_validation->set_rules('ts_freq', 'Pertemuan ke', 'required');
    if($this->form_validation->run() === TRUE){

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
                $data_pembiayaan = $this->client_pembiayaan_model->insert($data_pembiayaan);

                //UPDATE n-th PEMBIAYAAN di table CLIENT
                $timestamp=date("Y-m-d H:i:s");
                $data_client = array(
                      'client_pembiayaan' => $this->input->post("client_pembiayaanke")
                );
                $client_pembiayaan = $this->client_model->update_pembiayaan($client_id, $data_client);

                if($data_pembiayaan && $client_pembiayaan)
                {
                    $timestamp = date("Y-m-d H:i:s");
                    $return = array(
                                    'request_param'     => "",
                                    'status'            => "success",
                                    'error_message'     => "success",
                                    'data'              => array('saved' => "$timestamp", 'action' => "insert pembiayaan registration succeeds.")
                    );
                }
                else
                {
                    $timestamp = date("Y-m-d H:i:s");
                    $return = array(
                                    'request_param'     => "",
                                    'status'            => "error",
                                    'error_message'     => "error",
                                    'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "insert pembiayaan registration fail.")
                    );
                }

            }
            else
            {
                    $return = array(
                            'request_param'     => "",
                            'status'            => "error",
                            'error_message'     => "error",
                            'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "validation in data registration: client_id, plafond, etc not found.")
                    );
            }
        }
    }
    else
    {
        $return = array(
                            'request_param'     => "",
                            'status'            => "error",
                            'error_message'     => "error",
                            'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "validation in data registration: validation fails.")
                    );
    }

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
        echo json_encode($return);

  }

  public function entry(){
        //set form validation
        //$this->form_validation->set_rules('ts_date', 'Tanggal', 'required');
        //$this->form_validation->set_rules('ts_freq', 'Pertemuan ke', 'required');
        if( $this->input->post() ) //$this->form_validation->run() === TRUE
        {

                $id = $this->input->post('data_id');
                //CALCULATE POPI INDEX
                $score_popi=0;
                $data_popi_anggotart        = $this->input->post('data_popi_anggotart');
                $data_popi_masihsekolah     = $this->input->post('data_popi_masihsekolah');
                $data_popi_pendidikanistri  = $this->input->post('data_popi_pendidikanistri');
                $data_popi_pekerjaansuami   = $this->input->post('data_popi_pekerjaansuami');
                $data_popi_jenislantai      = $this->input->post('data_popi_jenislantai');
                $data_popi_jeniswc          = $this->input->post('data_popi_jeniswc');
                $data_popi_bahanbakar       = $this->input->post('data_popi_bahanbakar');
                $data_popi_gas              = $this->input->post('data_popi_gas');
                $data_popi_kulkas           = $this->input->post('data_popi_kulkas');
                $data_popi_motor            = $this->input->post('data_popi_motor');

                if($data_popi_anggotart=="A"){ $score_popi += 0 ; }
                elseif($data_popi_anggotart=="B"){ $score_popi += 5 ; }
                elseif($data_popi_anggotart=="C"){ $score_popi += 11 ; }
                elseif($data_popi_anggotart=="D"){ $score_popi += 18 ; }
                elseif($data_popi_anggotart=="E"){ $score_popi += 24 ; }
                elseif($data_popi_anggotart=="F"){ $score_popi += 37 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_masihsekolah=="A"){ $score_popi += 0 ; }
                elseif($data_popi_masihsekolah=="B"){ $score_popi += 0 ; }
                elseif($data_popi_masihsekolah=="C"){ $score_popi += 2 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_pendidikanistri=="A"){ $score_popi += 0 ; }
                elseif($data_popi_pendidikanistri=="B"){ $score_popi += 3 ; }
                elseif($data_popi_pendidikanistri=="C"){ $score_popi += 4 ; }
                elseif($data_popi_pendidikanistri=="D"){ $score_popi += 4 ; }
                elseif($data_popi_pendidikanistri=="E"){ $score_popi += 4 ; }
                elseif($data_popi_pendidikanistri=="F"){ $score_popi += 6 ; }
                elseif($data_popi_pendidikanistri=="G"){ $score_popi += 18 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_pekerjaansuami=="A"){ $score_popi += 0 ; }
                elseif($data_popi_pekerjaansuami=="B"){ $score_popi += 0 ; }
                elseif($data_popi_pekerjaansuami=="C"){ $score_popi += 1 ; }
                elseif($data_popi_pekerjaansuami=="D"){ $score_popi += 3 ; }
                elseif($data_popi_pekerjaansuami=="E"){ $score_popi += 3 ; }
                elseif($data_popi_pekerjaansuami=="F"){ $score_popi += 6 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_jenislantai=="A"){ $score_popi += 0 ; }
                elseif($data_popi_jenislantai=="B"){ $score_popi += 5 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_jeniswc=="A"){ $score_popi += 0 ; }
                elseif($data_popi_jeniswc=="B"){ $score_popi += 1 ; }
                elseif($data_popi_jeniswc=="C"){ $score_popi += 4 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_bahanbakar=="A"){ $score_popi += 0 ; }
                elseif($data_popi_bahanbakar=="B"){ $score_popi += 5 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_gas=="A"){ $score_popi += 0 ; }
                elseif($data_popi_gas=="B"){ $score_popi += 6 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_kulkas=="A"){ $score_popi += 0 ; }
                elseif($data_popi_kulkas=="B"){ $score_popi += 8 ; }
                else{ $score_popi += 0 ; }

                if($data_popi_motor=="A"){ $score_popi += 0 ; }
                elseif($data_popi_motor=="B"){ $score_popi += 9 ; }
                else{ $score_popi += 0 ; }

                if($score_popi <= 25){ $kategori_popi = "D"; }
                elseif($score_popi > 25 AND $score_popi <= 50){ $kategori_popi = "C"; }
                elseif($score_popi > 50 AND $score_popi <= 75){ $kategori_popi = "B"; }
                elseif($score_popi > 75 AND $score_popi <= 100){ $kategori_popi = "A"; }
                //END OF CALCULATE POPI INDEX

                //CALCULATE RMC INDEX
                $score_rmc=0;
                $data_rmc_ukuranrumah     = $this->input->post('data_rmc_ukuranrumah');
                $data_rmc_kondisirumah    = $this->input->post('data_rmc_kondisirumah');
                $data_rmc_jenisatap       = $this->input->post('data_rmc_jenisatap');
                $data_rmc_jenisdinding    = $this->input->post('data_rmc_jenisdinding');
                $data_rmc_jenislantai     = $this->input->post('data_rmc_jenislantai');
                $data_rmc_listrik         = $this->input->post('data_rmc_listrik');
                $data_rmc_sumberair       = $this->input->post('data_rmc_sumberair');
                $data_rmc_kepemilikan     = $this->input->post('data_rmc_kepemilikan');
                $data_rmc_hargaperbulan   = $this->input->post('data_rmc_hargaperbulan');

                if($data_rmc_ukuranrumah=="A"){ $score_rmc += 3 ; }
                elseif($data_rmc_ukuranrumah=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_ukuranrumah=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_kondisirumah=="A"){ $score_rmc += 3 ; }
                elseif($data_rmc_kondisirumah=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_kondisirumah=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_jenisatap=="A"){ $score_rmc += 2 ; }
                elseif($data_rmc_jenisatap=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_jenisatap=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_jenisdinding=="A"){ $score_rmc += 2 ; }
                elseif($data_rmc_jenisdinding=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_jenisdinding=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_jenislantai=="A"){ $score_rmc += 2 ; }
                elseif($data_rmc_jenislantai=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_jenislantai=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_listrik=="A"){ $score_rmc += 2 ; }
                elseif($data_rmc_listrik=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_listrik=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($data_rmc_sumberair=="A"){ $score_rmc += 2 ; }
                elseif($data_rmc_sumberair=="B"){ $score_rmc += 1 ; }
                elseif($data_rmc_sumberair=="C"){ $score_rmc += 0 ; }
                else{ $score_rmc += 0 ; }

                if($score_rmc <= 8){ $kategori_rmc = "D"; }
                elseif($score_rmc > 8 AND $score_rmc <= 10){ $kategori_rmc = "C"; }
                elseif($score_rmc > 10 AND $score_rmc <= 11){ $kategori_rmc = "B"; }
                elseif($score_rmc > 11 AND $score_rmc <= 15){ $kategori_rmc = "A"; }
                //END OF CALCULATE RMC INDEX

                //Process the Form
                $data = array(
                    //'data_client'             => $this->input->post('data_client'),
                    'data_ke'                   => $this->input->post('data_ke'),
                    'data_pengajuan'            => $this->input->post('data_pengajuan'),
                    'data_tgl'                  => $this->input->post('data_tgl'),
                    'data_date_accept'          => $this->input->post('data_date_accept'),
                    'data_date_first'           => $this->input->post('data_date_first'),
                    'data_tgl'                  => $this->input->post('data_tgl'),
                    'data_tujuan'               => $this->input->post('data_tujuan'),
                    'data_plafond'              => $this->input->post('data_plafond'),
                    'data_jangkawaktu'          => $this->input->post('data_jangkawaktu'),
                    'data_akad'                 => $this->input->post('data_akad'),
                    'data_totalangsuran'        => $this->input->post('data_totalangsuran'),
                    'data_angsuranpokok'        => $this->input->post('data_angsuranpokok'),
                    'data_tabunganwajib'        => $this->input->post('data_tabunganwajib'),
                    'data_margin'               => $this->input->post('data_margin'),
                    'data_angsuranke'           => $this->input->post('data_angsuranke'),
                    'data_status'               => $this->input->post('data_status'),
                    'data_sector'               => $this->input->post('data_sector'),

                    'data_pembiayaan1_nama'       => $this->input->post('data_pembiayaan1_nama'),
                    'data_pembiayaan1_lama'       => $this->input->post('data_pembiayaan1_lama'),
                    'data_pembiayaan1_plafond'    => $this->input->post('data_pembiayaan1_plafond'),
                    'data_pembiayaan1_total'      => $this->input->post('data_pembiayaan1_total'),
                    'data_pembiayaan1_status'     => $this->input->post('data_pembiayaan1_status'),
                    'data_pembiayaan2_nama'       => $this->input->post('data_pembiayaan2_nama'),
                    'data_pembiayaan2_lama'       => $this->input->post('data_pembiayaan2_lama'),
                    'data_pembiayaan2_plafond'    => $this->input->post('data_pembiayaan2_plafond'),
                    'data_pembiayaan2_total'      => $this->input->post('data_pembiayaan2_total'),
                    'data_pembiayaan2_status'     => $this->input->post('data_pembiayaan2_status'),
                    'data_pembiayaan3_nama'       => $this->input->post('data_pembiayaan3_nama'),
                    'data_pembiayaan3_lama'       => $this->input->post('data_pembiayaan3_lama'),
                    'data_pembiayaan3_plafond'    => $this->input->post('data_pembiayaan3_plafond'),
                    'data_pembiayaan3_total'      => $this->input->post('data_pembiayaan3_total'),
                    'data_pembiayaan3_status'     => $this->input->post('data_pembiayaan3_status'),
                    'data_pembiayaan4_nama'       => $this->input->post('data_pembiayaan4_nama'),
                    'data_pembiayaan4_lama'       => $this->input->post('data_pembiayaan4_lama'),
                    'data_pembiayaan4_plafond'    => $this->input->post('data_pembiayaan4_plafond'),
                    'data_pembiayaan4_total'      => $this->input->post('data_pembiayaan4_total'),
                    'data_pembiayaan4_status'     => $this->input->post('data_pembiayaan4_status'),

                    'data_suami'                  => $this->input->post('data_suami'),
                    'data_suami_tgllahir'         => $this->input->post('data_suami_tgllahir'),
                    'data_suami_pekerjaan'        => $this->input->post('data_suami_pekerjaan'),
                    'data_suami_komoditas'        => $this->input->post('data_suami_komoditas'),
                    'data_suami_pendidikan'       => $this->input->post('data_suami_pendidikan'),
                    'data_keluarga_anak'          => $this->input->post('data_keluarga_anak'),
                    'data_keluarga_belumsekolah'  => $this->input->post('data_keluarga_belumsekolah'),
                    'data_keluarga_tk'            => $this->input->post('data_keluarga_tk'),
                    'data_keluarga_tidaksekolah'  => $this->input->post('data_keluarga_tidaksekolah'),
                    'data_keluarga_tidaktamatsd'  => $this->input->post('data_keluarga_tidaktamatsd'),
                    'data_keluarga_sd'            => $this->input->post('data_keluarga_sd'),
                    'data_keluarga_smp'           => $this->input->post('data_keluarga_smp'),
                    'data_keluarga_sma'           => $this->input->post('data_keluarga_sma'),
                    'data_keluarga_kuliah'        => $this->input->post('data_keluarga_kuliah'),
                    'data_keluarga_tanggungan'    => $this->input->post('data_keluarga_tanggungan'),

                    'data_popi_anggotart'         => $this->input->post('data_popi_anggotart'),
                    'data_popi_masihsekolah'      => $this->input->post('data_popi_masihsekolah'),
                    'data_popi_pendidikanistri'   => $this->input->post('data_popi_pendidikanistri'),
                    'data_popi_pekerjaansuami'    => $this->input->post('data_popi_pekerjaansuami'),
                    'data_popi_jenislantai'       => $this->input->post('data_popi_jenislantai'),
                    'data_popi_jeniswc'           => $this->input->post('data_popi_jeniswc'),
                    'data_popi_bahanbakar'        => $this->input->post('data_popi_bahanbakar'),
                    'data_popi_gas'               => $this->input->post('data_popi_gas'),
                    'data_popi_kulkas'            => $this->input->post('data_popi_kulkas'),
                    'data_popi_motor'             => $this->input->post('data_popi_motor'),
                    'data_popi_total'             => $score_popi ,
                    'data_popi_kategori'          => $kategori_popi ,

                    'data_rmc_ukuranrumah'        => $this->input->post('data_rmc_ukuranrumah'),
                    'data_rmc_kondisirumah'       => $this->input->post('data_rmc_kondisirumah'),
                    'data_rmc_jenisatap'          => $this->input->post('data_rmc_jenisatap'),
                    'data_rmc_jenisdinding'       => $this->input->post('data_rmc_jenisdinding'),
                    'data_rmc_jenislantai'        => $this->input->post('data_rmc_jenislantai'),
                    'data_rmc_listrik'            => $this->input->post('data_rmc_listrik'),
                    'data_rmc_sumberair'          => $this->input->post('data_rmc_sumberair'),
                    'data_rmc_kepemilikan'        => $this->input->post('data_rmc_kepemilikan'),
                    'data_rmc_hargaperbulan'      => $this->input->post('data_rmc_hargaperbulan'),
                    'data_rmc_total'              => $score_rmc ,
                    'data_rmc_kategori'           => $kategori_rmc ,

                    'data_aset_lahan'             => $this->input->post('data_aset_lahan'),
                    'data_aset_jumlahlahan'       => $this->input->post('data_aset_jumlahlahan'),
                    'data_aset_ternak'            => $this->input->post('data_aset_ternak'),
                    'data_aset_jumlahternak'      => $this->input->post('data_aset_jumlahternak'),
                    'data_aset_tabungan'          => $this->input->post('data_aset_tabungan'),
                    'data_aset_deposito'          => $this->input->post('data_aset_deposito'),
                    'data_aset_lain'              => $this->input->post('data_aset_lain'),
                    'data_aset_total'             => $this->input->post('data_aset_total'),

                    'data_pendapatan_suamijenisusaha' => $this->input->post('data_pendapatan_suamijenisusaha'),
                    'data_pendapatan_suamilama'       => $this->input->post('data_pendapatan_suamilama'),
                    'data_pendapatan_suami'           => $this->input->post('data_pendapatan_suami'),
                    'data_pendapatan_istri'           => $this->input->post('data_pendapatan_istri'),
                    'data_pendapatan_istrijenisusaha' => $this->input->post('data_pendapatan_istrijenisusaha'),
                    'data_pendapatan_istrilama'       => $this->input->post('data_pendapatan_istrilama'),
                    'data_pendapatan_lain'            => $this->input->post('data_pendapatan_lain'),
                    'data_pendapatan_lainjenisusaha'  => $this->input->post('data_pendapatan_lainjenisusaha'),
                    'data_pendapatan_lainlama'        => $this->input->post('data_pendapatan_lainlama'),
                    'data_pendapatan_total'           => $this->input->post('data_pendapatan_total'),

                    'data_pengeluaran_dapur'          => $this->input->post('data_pengeluaran_dapur'),
                    'data_pengeluaran_rekening'       => $this->input->post('data_pengeluaran_rekening'),
                    'data_pengeluaran_pulsa'          => $this->input->post('data_pengeluaran_pulsa'),
                    'data_pengeluaran_kreditan'       => $this->input->post('data_pengeluaran_kreditan'),
                    'data_pengeluaran_arisan'         => $this->input->post('data_pengeluaran_arisan'),
                    'data_pengeluaran_pendidikan'     => $this->input->post('data_pengeluaran_pendidikan'),
                    'data_pengeluaran_umum'           => $this->input->post('data_pengeluaran_umum'),
                    'data_pengeluaran_angsuranlain'   => $this->input->post('data_pengeluaran_angsuranlain'),
                    'data_pengeluaran_total'          => $this->input->post('data_pengeluaran_total'),
                    'data_savingpower'                => $this->input->post('data_savingpower'),
                    'data_sumber_pembiayaan'          => $this->input->post('client_pembiayaan_sumber')

                );

                if(!$id)
                {
                    $client_data = $this->clients_pembiayaan_model->insert($data);
                    if($client_data){
                            $timestamp   = date("Y-m-d H:i:s");
                            $return      = array(
                                'request_param'     => "",
                                'status'            => "success",
                                'error_message'     => "success",
                                'data'              => array('saved' => "$timestamp", 'action' => "insert pembiayaan data.")
                            );
                    }else{
                            $return = array(
                                'request_param'     => "",
                                'status'            => "error",
                                'error_message'     => "error",
                                'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "insert client pembiayaan data: fails.")
                            );
                    }
                }
                else
                {
                    $client_data = $this->clients_pembiayaan_model->update($id, $data);
                    if($client_data){
                            $timestamp   = date("Y-m-d H:i:s");
                            $return      = array(
                                'request_param'     => "",
                                'status'            => "success",
                                'error_message'     => "success",
                                'data'              => array('saved' => "$timestamp", 'action' => "update pembiayaan data.")
                            );
                    }else{
                            $return = array(
                                'request_param'     => "",
                                'status'            => "error",
                                'error_message'     => "error",
                                'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "update client pembiayaan data: fails.")
                            );
                    }
                }
        }
        else
        {
                    $return = array(
                                        'request_param'     => "",
                                        'status'            => "error",
                                        'error_message'     => "error",
                                        'data'              => array('saved' => "0000-00-00 00:00:00", 'action' => "validation in data registration: validation fails.")
                            );
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');
        echo json_encode($return);

  }

}
