<?php

class Api extends Api_Controller {
	
	
	public function __construct() {
        parent::__construct();
		$this->load->model('group_model');
		$this->load->model('clients_model');
		$this->load->model('officer_model');
		$this->load->model('tsdaily_model');
		$this->load->model('transaction_model');
		$this->load->model('saving_model');
		$this->load->model('clients_pembiayaan_model');
		$this->load->model('branch_model');
		$this->load->model('topsheet_model');
		$this->load->model('tabwajib_model');
		$this->load->model('tabsukarela_model');
		$this->load->model('tabberjangka_model');
		$this->load->model('tr_tabwajib_model');
		$this->load->model('tr_tabsukarela_model');
		$this->load->model('tr_tabberjangka_model');
		$this->load->model('jurnal_model');
		$this->load->model('risk_model');
	}
	

  
	public function save_topsheet(){
		$user_branch = $this->session->userdata('user_branch');	
		
		//set form validation
		$this->form_validation->set_rules('ts_date', 'Tanggal', 'required');
		//$this->form_validation->set_rules('ts_freq', 'Pertemuan ke', 'required');	
	
		if($this->form_validation->run() === TRUE){
			$this->db->trans_start();
			
			$no = $this->input->post('no');
			$total_absen_h=0; 
			$total_absen_s=0;
			$total_absen_c=0;
			$total_absen_i=0;
			$total_absen_a=0;
			$timestamp= date("Ymdhis");
			$group_id = $this->input->post('group_id');
			
			$topsheet_code= $timestamp.$group_id;
			$ts_freq_total=0;
			$count_transaction = 0;
			$total_par = 0;
			$total_par_ammount = 0;
			$total_tr = 0;
			for($i=1; $i<=$no; $i++){
			
				
				//process the form
				
				//ABSEN
				$absen=$this->input->post("data_absen_".$i);
				$columnabsen="tr_absen_".$absen;
				if($absen == "h") { $tr_absen_h = 1; $total_absen_h ++; }else{ $tr_absen_h = 0;}
				if($absen == "s") { $tr_absen_s = 1; $total_absen_s ++; }else{ $tr_absen_s = 0;}
				if($absen == "c") { $tr_absen_c = 1; $total_absen_c ++; }else{ $tr_absen_c = 0;}
				if($absen == "i") { $tr_absen_i = 1; $total_absen_i ++; }else{ $tr_absen_i = 0;}
				if($absen == "a") { $tr_absen_a = 1; $total_absen_a ++; }else{ $tr_absen_a = 0;}
				
				// TS FREQ
				$ts_freq = $this->input->post("ts_freq");
				if($ts_freq==""){$ts_freq=0;}
				
				// ANGSURAN FREQ
				$frek = $this->input->post("data_freq_".$i);
				$angsuranke = $this->input->post("data_angsuranke_".$i)-1; //dikurangi 1 karena di view sudah di +1
				if($angsuranke < 0) { $angsuranke = 0;}
				$pertemuanke = $this->input->post("data_pertemuanke_".$i) + 1;
				if($frek>=1){				
					$angsuranke = $angsuranke + $frek;
					$ts_freq_total += $frek;
					
				}
				
				//TANGGUNG RENTENG
				$data_tr = $this->input->post("data_tr_".$i);
				$client_tr = $this->input->post("client_tr_".$i);
				$data_tr_today = $this->input->post("data_tr_today_".$i);
				if($data_tr_today=="1"){
					$data_tr = $data_tr + 1;
					$client_tr = $client_tr + 1;
					$total_tr++;
				}
				$data_id = $this->input->post("data_id_".$i);
				if(empty($data_id)){ $data_id = 0 ;}
				//$tr_tabwajib_debet = $this->input->post("data_freq_".$i) * 1000;
				$tr_tabwajib_debet = $this->input->post("data_tabwajib_debet_".$i)*1000;
				$tr_tabwajib_credit = $this->input->post("data_tabwajib_credit_".$i)*1000;
				$data_transaction = array(
						'tr_date'       		  => $this->input->post("ts_date"),
						'tr_account'       		  => $this->input->post("data_account_".$i),
						'tr_topsheet_code'        => $topsheet_code,
						'tr_client'       		  => $this->input->post("data_client_".$i),
						'tr_pembiayaan'    		  => $data_id ,
						'tr_group'       		  => $group_id,
						"$columnabsen"			  => '1',
						'tr_freq'       		  => $this->input->post("data_freq_".$i),
						'tr_angsuranke'       	  => $angsuranke,
						'tr_pertemuanke'       	  => $pertemuanke,
						'tr_angsuranpokok'        => $this->input->post("data_totalangsuranpokok_".$i)*1000,
						'tr_profit'        		  => $this->input->post("data_totalangsuranprofit_".$i)*1000,
						'tr_tabunganwajib'    	  => $tr_tabwajib_debet,
						'tr_tabsukarela_debet'    => ($this->input->post("data_tabsukarela_debet_".$i)*1000),
						'tr_tabsukarela_credit'   => ($this->input->post("data_tabsukarela_credit_".$i)*1000),
						'tr_tabberjangka_debet'   => ($this->input->post("data_tabberjangka_debet_".$i)*1000),
						'tr_tabberjangka_credit'  => ($this->input->post("data_tabberjangka_credit_".$i)*1000),
						'tr_tabwajib_debet'   	  => $tr_tabwajib_debet,
						'tr_tabwajib_credit'   	  => $tr_tabwajib_credit,
						'tr_absen_h'   	  => $tr_absen_h,
						'tr_absen_s'   	  => $tr_absen_s,
						'tr_absen_c'   	  => $tr_absen_c,
						'tr_absen_i'   	  => $tr_absen_i,
						'tr_absen_a'   	  => $tr_absen_a,
						'tr_tanggungrenteng'   	  => $data_tr_today,
						'tr_adm'          => ($this->input->post("data_adm_".$i)*1000),
						'tr_butab'        => ($this->input->post("data_butab_".$i)*1000),
						'tr_asuransi'     => ($this->input->post("data_asuransi_".$i)*1000),
						'tr_lwk'          => ($this->input->post("data_lwk_".$i)*1000),
				);
				
				$total_adm += ($this->input->post("data_adm_".$i)*1000);
				$total_butab += ($this->input->post("data_butab_".$i)*1000);
				$total_asuransi += ($this->input->post("data_asuransi_".$i)*1000);
				$total_lwk += ($this->input->post("data_lwk_".$i)*1000);
				$total_lainlain += $total_adm+$total_butab+$total_asuransi+$total_lwk;
				
				//$data_id = $this->input->post("data_id_".$i);
				$detail_pembiayaan = $this->clients_pembiayaan_model->get_pembiayaan($data_id)->result();
				$detail_pembiayaan = $detail_pembiayaan[0];
				$akad=$detail_pembiayaan->data_akad;
				
				//ANGSURAN
				//$data_sisaangsuran = $this->input->post("data_sisaangsuran_".$i) - ( $this->input->post("data_freq_".$i) * ($this->input->post("data_totalangsuran_".$i) * 1000) );
				$data_sisaangsuran = $this->input->post("data_sisaangsuran_".$i) - ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );				
				$data_angsuran_pokok = ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );
				$data_angsuran_profit = ( $this->input->post("data_totalangsuranprofit_".$i) * 1000 );
				
				//Total Angsuran
				$total_angsuran_pokok += ( $this->input->post("data_totalangsuranpokok_".$i) * 1000 );
				$total_angsuran_profit += ( $this->input->post("data_totalangsuranprofit_".$i) * 1000 );
				//Total Angsuran per Akad
				if($akad == "MYR"){ 
					$total_angsuran_pokok_Musyarakah += $data_angsuran_pokok ; 
					$total_angsuran_profit_Musyarakah += $data_angsuran_profit ;
				}elseif($akad == "AHA"){ 
					$total_angsuran_pokok_Ijarah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Ijarah += $data_angsuran_profit ;
				}elseif($akad == "MBA"){ 
					$total_angsuran_pokok_Murabahah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Murabahah += $data_angsuran_profit ;
				}elseif($akad == "IJR"){ 
					$total_angsuran_pokok_Ijarah +=  $data_angsuran_pokok ; 
					$total_angsuran_profit_Ijarah += $data_angsuran_profit ;
				}
				//$par =0;
				$par = $this->input->post("data_par_".$i);
				// PAR / RISK
				if($this->input->post("data_freq_".$i) == 0 AND $data_id != 0 AND $angsuranke >0 AND $angsuranke <=50){ 
					$par = $this->input->post("data_par_".$i) + 1;
					$tr_risk = array(					
							'risk_client'  		=>  $this->input->post("data_client_".$i),
							'risk_pembiayaan'   =>  $data_id,
							'risk_ke'   		=>  $angsuranke + 1,
							'risk_date'   		=>  $this->input->post("ts_date")
					);
					$this->risk_model->insert($tr_risk);
					$total_par ++;
					$total_par_ammount += $this->input->post("data_totalangsuranpokok_".$i);
				}
				
				//PEMBAYARAN PAR
				if($this->input->post("data_freq_".$i) > 1 AND $par>0){
					$par = $this->input->post("data_par_".$i) - $this->input->post("data_freq_".$i) + 1;
				}
				if($par < 0){ $par = 0; }
				
				$data_pembiayaan = array(
						'data_angsuranke'       =>  $angsuranke,
						'data_sisaangsuran'     =>  $data_sisaangsuran,
						'data_tr'     			=>  $data_tr,
						'data_par'     			=>  $par
				);
				
				$saving_date= date("Ymdhis");
				$transactioncode= $saving_date.$this->input->post("data_client_".$i);
				
				if($data_id){
					$tabwajib_saldo = $this->input->post("data_tabwajib_saldo_".$i) + $tr_tabwajib_debet - $tr_tabwajib_credit;
					$tabwajib_debet = $tr_tabwajib_debet;
					$tabwajib_totaldebet = $this->input->post("data_tabwajib_totaldebet_".$i) + $tr_tabwajib_debet;
					$tabwajib_totalcredit = $this->input->post("data_tabwajib_totalcredit_".$i) + $tr_tabwajib_credit;
				}else{
					$tabwajib_saldo = $this->input->post("data_tabwajib_saldo_".$i);
					$tabwajib_debet = 0;
					$tabwajib_totaldebet = $this->input->post("data_tabwajib_totaldebet_".$i);
				}
				$tabwajib_totalcredit = $this->input->post("data_tabwajib_totalcredit_".$i) + ($this->input->post("data_tabwajib_credit_".$i)*1000);
				
				$tabsukarela_saldo = $this->input->post("data_tabsukarela_saldo_".$i) + ($this->input->post("data_tabsukarela_debet_".$i)*1000) - ($this->input->post("data_tabsukarela_credit_".$i)*1000);
				$tabsukarela_totaldebet = $this->input->post("data_tabsukarela_totaldebet_".$i) + ($this->input->post("data_tabsukarela_debet_".$i)*1000);
				$tabsukarela_totalcredit = $this->input->post("data_tabsukarela_totalcredit_".$i) + ($this->input->post("data_tabsukarela_credit_".$i)*1000);
				
				if($tabsukarela_saldo < 0){ return false; }
				
				$tabberjangka_saldo = $this->input->post("data_tabberjangka_saldo_".$i) + ($this->input->post("data_tabberjangka_debet_".$i)*1000) - ($this->input->post("data_tabberjangka_credit_".$i)*1000);
				$tabberjangka_totaldebet = $this->input->post("data_tabberjangka_totaldebet_".$i) + ($this->input->post("data_tabberjangka_debet_".$i)*1000);
				$tabberjangka_totalcredit = $this->input->post("data_tabberjangka_totalcredit_".$i) + ($this->input->post("data_tabberjangka_credit_".$i)*1000);
				
				if($tabberjangka_saldo < 0){ return false; }
				
				/*
				if($tabsukarela_saldo < 10000){
					$this->session->set_flashdata('message', "success|Tab. Sukarela ".$this->input->post('data_account_'.$i)." < 10000 ");
					redirect('topsheet/ts_entry/172');
					return false;
				}
				*/
				
				$total_tabwajib += $tabwajib_saldo;
				$total_tabsukarela = $total_tabsukarela + $tabsukarela_saldo;
								
				$tabsukarela_debet = $this->input->post("data_tabsukarela_debet_".$i)*1000;
				$tabsukarela_credit = $this->input->post("data_tabsukarela_credit_".$i)*1000;
				
				
				$tabwajib_credit = $this->input->post("data_tabwajib_credit_".$i)*1000;
				
				$total_tabwajib_debet += $tabwajib_debet;
				$total_tabwajib_credit += $tabwajib_credit;
				
				$total_tabsukarela_debet += $tabsukarela_debet;				
				$total_tabsukarela_credit += $tabsukarela_credit;
				
				//Tab Berjangka
				$tabberjangka_debet = $this->input->post("data_tabberjangka_debet_".$i)*1000;
				$tabberjangka_credit = $this->input->post("data_tabberjangka_credit_".$i)*1000;
				$total_tabberjangka_debet += $tabberjangka_debet;				
				$total_tabberjangka_credit += $tabberjangka_credit;
				
								
				$account_number = $this->input->post("data_account_".$i);
				$data_tabwajib = array(
						'tabwajib_date'      =>  $saving_date,
						'tabwajib_account'   =>  $this->input->post("data_account_".$i),
						'tabwajib_client'    =>  $this->input->post("data_client_".$i),
						'tabwajib_debet'     =>  $tabwajib_totaldebet,
						'tabwajib_credit'    =>  $tabwajib_totalcredit,
						'tabwajib_saldo'     =>  $tabwajib_saldo
				);
				$tr_tabwajib = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  $tabwajib_debet,
						'tr_saldo'    		=>  $tabwajib_saldo,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);
				$data_tabsukarela = array(
						'tabsukarela_date'      =>  $saving_date,
						'tabsukarela_account'   =>  $this->input->post("data_account_".$i),
						'tabsukarela_client'    =>  $this->input->post("data_client_".$i),
						'tabsukarela_debet'     =>  $tabsukarela_totaldebet,
						'tabsukarela_credit'    =>  $tabsukarela_totalcredit,
						'tabsukarela_saldo'     =>  $tabsukarela_saldo
				);
				$tr_tabsukarela = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  ($this->input->post("data_tabsukarela_debet_".$i)*1000),
						'tr_credit'    		=>  ($this->input->post("data_tabsukarela_credit_".$i)*1000),
						'tr_saldo'    		=>  $tabsukarela_saldo,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);
				
				$tab_berjangka_data = $this->tabberjangka_model->get_account($this->input->post("data_account_".$i));
				$tab_berjangka_data = $tab_berjangka_data[0];
				$tabberjangka_tr_angsuranke = $tab_berjangka_data->tabberjangka_angsuranke + 1;
				
				$data_tabberjangka = array(
						'tabberjangka_date'      =>  $saving_date,
						'tabberjangka_account'   =>  $this->input->post("data_account_".$i),
						'tabberjangka_client'    =>  $this->input->post("data_client_".$i),
						'tabberjangka_debet'     =>  $tabberjangka_totaldebet,
						'tabberjangka_credit'    =>  $tabberjangka_totalcredit,
						'tabberjangka_saldo'     =>  $tabberjangka_saldo,
						'tabberjangka_angsuranke'   =>  $tabberjangka_tr_angsuranke
				);
				$tr_tabberjangka = array(					
						'tr_topsheet_code'  =>  $topsheet_code,
						'tr_date'   		=>  $saving_date,
						'tr_account'   		=>  $this->input->post("data_account_".$i),
						'tr_client'   		=>  $this->input->post("data_client_".$i),
						'tr_debet'    		=>  ($this->input->post("data_tabberjangka_debet_".$i)*1000),
						'tr_credit'    		=>  ($this->input->post("data_tabberjangka_credit_".$i)*1000),
						'tr_saldo'    		=>  $tabberjangka_saldo,
						'tr_angsuranke'    	=>  $tabberjangka_tr_angsuranke,
						'tr_remark'    		=>  "TS ".$topsheet_code
				);	
				
				

				
				//INSERT TO DATABASE
				$this->transaction_model->insert($data_transaction);
				$count_transaction++;
				$this->clients_pembiayaan_model->update_pembiayaan($data_id, $data_pembiayaan);
				
				if($data_id){ 
					$this->tr_tabwajib_model->insert($tr_tabwajib); 
					$this->tabwajib_model->update($account_number, $data_tabwajib);
				}
				
				if( ($this->input->post("data_tabsukarela_debet_".$i)  != 0) OR ($this->input->post("data_tabsukarela_credit_".$i) != 0) ){
					$this->tr_tabsukarela_model->insert($tr_tabsukarela);				
					$this->tabsukarela_model->update($account_number, $data_tabsukarela);
				}
				
				if( ($this->input->post("data_tabberjangka_debet_".$i)  != 0) OR ($this->input->post("data_tabberjangka_credit_".$i) != 0) ){
					$this->tr_tabberjangka_model->insert($tr_tabberjangka);				
					$this->tabberjangka_model->update($account_number, $data_tabberjangka);
				}
				 
			}
			
			
			$group_id = $this->input->post("group_id");
			$group_name = $this->input->post("group_name");
			$grand_total = $total_angsuran_pokok+$total_angsuran_profit+$total_tabwajib_debet-$total_tabwajib_credit+$total_tabsukarela_debet-$total_tabsukarela_credit+$total_tabberjangka_debet-$total_tabberjangka_credit+$total_adm+$total_butab+$total_asuransi+$total_lwk;
			$grand_total_tabungan = $total_tabwajib_debet-$total_tabwajib_credit+$total_tabsukarela_debet-$total_tabsukarela_credit+$total_tabberjangka_debet-$total_tabberjangka_credit;
			$grand_total_rf = $total_angsuran_pokok+$total_angsuran_profit+$total_adm+$total_butab+$total_asuransi+$total_lwk;
			
			$data_tsdaily = array(
						'tsdaily_group'      	=>  "$group_name",						
						'tsdaily_topsheet_code' =>  $topsheet_code,
						'tsdaily_groupid'   	=>  $group_id,
						'tsdaily_date'    		=>  $this->input->post("ts_date"),
						'tsdaily_freq'    		=>  $ts_freq_total,
						'tsdaily_angsuranpokok' =>  $total_angsuran_pokok,
						'tsdaily_profit'    	=>  $total_angsuran_profit,
						'tsdaily_tabwajib'    	=>  $total_tabwajib_debet-$total_tabwajib_credit,
						'tsdaily_tabungan_debet'    	=> $total_tabsukarela_debet ,
						'tsdaily_tabungan_credit'    	=> $total_tabsukarela_credit,
						'tsdaily_tabungan_berjangka_debet'    	=> $total_tabberjangka_debet ,
						'tsdaily_tabungan_berjangka_credit'    	=> $total_tabberjangka_credit,
						'tsdaily_total'    		 =>  $grand_total,
						'tsdaily_total_tabungan' =>  $grand_total_tabungan,
						'tsdaily_total_rf'    	 =>  $grand_total_rf,
						'tsdaily_absen_h'    	 =>  $total_absen_h,
						'tsdaily_absen_s'    	 =>  $total_absen_s,
						'tsdaily_absen_c'    	 =>  $total_absen_c,
						'tsdaily_absen_i'    	 =>  $total_absen_i,
						'tsdaily_absen_a'    	 =>  $total_absen_a,
						'tsdaily_adm'    	 	 =>  $total_adm,
						'tsdaily_asuransi'    	 =>  $total_asuransi,
						'tsdaily_bukutabungan'   =>  $total_butab,
						'tsdaily_lwk'    		 =>  $total_lwk,
						'tsdaily_total_transaction' =>  $count_transaction,
						'tsdaily_total_par'  		=>  $total_par,
						'tsdaily_total_tr'  		=>  $total_tr,
						'tsdaily_total_par_ammount' =>  $total_par_ammount
				);
				
			 $this->tsdaily_model->insert($data_tsdaily);
			 
			//------------------------------------------------------------ 
			//ADD JURNAL 
			//------------------------------------------------------------
			
				//Jurnal Tab.Wajib Debet
				if($total_tabwajib_debet != 0){
					$jurnal_account_credit = "2010300"; //Simpanan Wajib Kelompok
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabwajib_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Wajib"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Wajib Credit
				if($total_tabwajib_credit != 0){
					$jurnal_account_credit = "1010001"; //Kas Teller
					$jurnal_account_debet = "2010300";  //Simpanan Wajib Kelompok
					$nominal = $total_tabwajib_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,	
						'jurnal_tscode'  		=> $topsheet_code,
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Wajib"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Sukarela Debet
				if($total_tabsukarela_debet != 0){
					$jurnal_account_credit = "2010100"; //Simpanan Sukarela
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabsukarela_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Sukarela"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Sukarela Kredit
				if($total_tabsukarela_credit != 0){
					$jurnal_account_credit = "1010001"; //Simpanan Sukarela
					$jurnal_account_debet = "2010100";  //Kas Teller
					$nominal = $total_tabsukarela_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Sukarela"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				
				//Jurnal Tab.Berjangka Debet
				if($total_tabberjangka_debet != 0){
					$jurnal_account_credit = "2010400"; //Simpanan berjangka
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_tabberjangka_debet;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Berjangka"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Tab.Berjangka Kredit
				if($total_tabberjangka_credit != 0){
					$jurnal_account_credit = "1010001"; //Simpanan berjangka
					$jurnal_account_debet = "2010400";  //Kas Teller
					$nominal = $total_tabberjangka_credit;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Tab Berjangka"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Adm
				if($total_adm > 0){
					$jurnal_account_credit = "4020003"; //JASA ADMINISTRASI PELAYANAN
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_adm;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Adm"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				//Jurnal Butab
				if($total_butab > 0){
					$jurnal_account_credit = "4020006"; //JASA ADMINISTRASI GANTI BUKU TABUNGAN
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_butab;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Butab"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Asuransi
				if($total_asuransi > 0){
					$jurnal_account_credit = "2050201"; //TITIPAN ASURANSI
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_asuransi;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - Asuransi"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal LWK
				if($total_lwk > 0){
					$jurnal_account_credit = "4020008"; //JASA ADMINISTRASI LATIHAN WAJIB KELOMPOK
					$jurnal_account_debet = "1010001";  //Kas Teller
					$nominal = $total_lwk;				
					$data_jurnal = array(
						'jurnal_branch'    	 	=> $user_branch,
						'jurnal_date'    	 	=> $this->input->post("ts_date"),
						'jurnal_account_debet'  => $jurnal_account_debet,
						'jurnal_debet' 			=> $nominal,
						'jurnal_account_credit' => $jurnal_account_credit,	
						'jurnal_credit'  		=> $nominal,
						'jurnal_tscode'  		=> $topsheet_code,	
						'jurnal_remark' 		=> "TS $topsheet_code $group_name - LWK"
					);				
					$this->jurnal_model->insert($data_jurnal);
				}
				
				//Jurnal Angsuran Pokok
					//Jurnal Angsuran Pokok Musyarakah
					if($total_angsuran_pokok_Musyarakah > 0){
						$jurnal_account_credit = "1030101"; //PIUTANG PEMBIAYAAN ANGGOTA MUSYARAKAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Musyarakah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Musyarakah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Murabahah
					if($total_angsuran_pokok_Murabahah > 0){
						$jurnal_account_credit = "1030102"; //PIUTANG PEMBIAYAAN ANGGOTA MURABAHAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Murabahah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Murabahah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Ijarah
					if($total_angsuran_pokok_Ijarah > 0){
						$jurnal_account_credit = "1030103"; //PIUTANG PEMBIAYAAN ANGGOTA Ijarah
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_pokok_Ijarah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Pokok Ijarah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					
				//Jurnal Angsuran Profit
					//Jurnal Angsuran Pokok Musyarakah
					if($total_angsuran_profit_Musyarakah > 0){
						$jurnal_account_credit = "4010101"; //PIUTANG PEMBIAYAAN ANGGOTA MUSYARAKAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Musyarakah;				
						$data_jurnal = array(							
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Musyarakah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Murabahah
					if($total_angsuran_profit_Murabahah > 0){
						$jurnal_account_credit = "4010102"; //PIUTANG PEMBIAYAAN ANGGOTA MURABAHAH
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Murabahah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,
							'jurnal_tscode'  		=> $topsheet_code,	
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Murabahah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
					//Jurnal Angsuran Pokok Ijarah
					if($total_angsuran_profit_Ijarah > 0){
						$jurnal_account_credit = "4010103"; //PIUTANG PEMBIAYAAN ANGGOTA Ijarah
						$jurnal_account_debet = "1010001";  //Kas Teller
						$nominal = $total_angsuran_profit_Ijarah;				
						$data_jurnal = array(
							'jurnal_branch'    	 	=> $user_branch,
							'jurnal_date'    	 	=> $this->input->post("ts_date"),
							'jurnal_account_debet'  => $jurnal_account_debet,
							'jurnal_debet' 			=> $nominal,
							'jurnal_account_credit' => $jurnal_account_credit,	
							'jurnal_credit'  		=> $nominal,	
							'jurnal_tscode'  		=> $topsheet_code,
							'jurnal_remark' 		=> "TS $topsheet_code $group_name - Angsuran Profit Ijarah"
						);				
						$this->jurnal_model->insert($data_jurnal);
					}
				
				$this->db->trans_complete();

				$timestamp = date("Y-m-d H:i:s");
				$return = array(
							'status'    	 	=> "success",
							'saved'    	 		=> "$timestamp"
						);	
		}else{
			$return = array(
							'status'    	 	=> "failed",
							'saved'    	 		=> "0000-00-00 00:00:00"
						);	
		}
		
		var_dump($return);
	}
	
	
	
}