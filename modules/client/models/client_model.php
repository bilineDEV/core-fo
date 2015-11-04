<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Field Officer Apps Model
 *
 * @package amartha
 * @author  afahmi
 * @since   26 Oktober 2015
 */

class client_model extends MY_Model {

  public function get_a_client($id)
  {
      return $this->db
                  ->select('client_id, client_account, client_fullname, client_group, branch_name')
                  ->from('tbl_clients')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
                  ->where('tbl_clients.deleted', '0')
                  ->where('client_id', $id)
                  ->get()
                  ->result();
  }

  public function get_clients_by_group($group_id)
  {
      return $this->db
                  ->select('client_id, client_account, client_fullname, client_group, branch_name')
                  ->from('tbl_clients')
                  ->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id' , 'left')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
                  ->where('tbl_pembiayaan.data_status', '1')
                  ->where('tbl_pembiayaan.deleted', '0')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_clients.client_group', $group_id)
                  ->order_by('client_id', 'ASC')
                  ->get()
                  ->result();
  }

    public function get_clients_in_details_by_group($group_id)
  {
      return $this->db
                  ->select('tbl_pembiayaan.data_id, tbl_pembiayaan.data_ke, tbl_pembiayaan.data_angsuranke, tbl_pembiayaan.data_plafond')
                  ->select('tbl_pembiayaan.data_jangkawaktu, tbl_pembiayaan.data_totalangsuran, tbl_pembiayaan.data_angsuranpokok, tbl_pembiayaan.data_pertemuanke')
                  ->select('tbl_pembiayaan.data_tabunganwajib, tbl_pembiayaan.data_margin, tbl_pembiayaan.data_sisaangsuran, tbl_pembiayaan.data_tr, tbl_pembiayaan.data_par')
                  ->select('tbl_clients.client_id, tbl_clients.client_account, tbl_clients.client_fullname, tbl_clients.client_group, tbl_clients.client_officer, tbl_clients.client_tr')
                  //->select('MAX(tbl_transaction.tr_angsuranke) as client_max_angsuranke')
                  //->select('SUM(tbl_transaction.tr_freq) as client_total_angsuranke')
                  //->select('SUM(tbl_transaction.tr_absen_h) as client_hadir, SUM(tbl_transaction.tr_absen_s) as client_sakit, SUM(tbl_transaction.tr_absen_c) as client_cuti')
                  //->select('SUM(tbl_transaction.tr_absen_i) as client_izin, SUM(tbl_transaction.tr_absen_a) as client_absen, SUM(tbl_transaction.tr_tanggungrenteng) as client_tanggungrenteng')
                  ->select('tbl_tabwajib.tabwajib_credit, tbl_tabwajib.tabwajib_debet, tbl_tabwajib.tabwajib_saldo, tbl_tabsukarela.tabsukarela_credit, tbl_tabsukarela.tabsukarela_debet, tbl_tabsukarela.tabsukarela_saldo')
                  ->select('tbl_tabberjangka.tabberjangka_credit, tbl_tabberjangka.tabberjangka_debet, tbl_tabberjangka.tabberjangka_saldo')
                  ->from('tbl_clients')
                  ->join('tbl_pembiayaan', 'tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id' , 'left')
                  ->join('tbl_group', 'tbl_group.group_id = tbl_clients.client_group', 'left')
                  //->join('tbl_transaction', 'tbl_transaction.tr_pembiayaan = tbl_pembiayaan.data_id', 'left')
                  ->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabberjangka', 'tbl_tabberjangka.tabberjangka_account = tbl_clients.client_account', 'left')
                  ->where('tbl_pembiayaan.data_status', '1')
                  ->where('tbl_pembiayaan.deleted', '0')
                  //->where('tbl_transaction.deleted', '0')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id')
                  ->where('tbl_pembiayaan.data_ke = tbl_clients.client_pembiayaan')
                  //->where('tbl_transaction.tr_angsuranke > 0')
                  ->where('tbl_clients.client_group', $group_id)
                  //->group_by('tbl_clients.client_group')
                  ->order_by('client_id', 'ASC')
                  ->get()
                  ->result();
  }

  public function get_clients_by_officer($officer_id)
  {
      return $this->db
                  ->select('client_id, client_account, client_fullname, client_group, branch_name')
                  ->from('tbl_clients')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_clients.client_officer', $officer_id)
                  ->order_by('client_id', 'ASC')
                  ->get()
                  ->result();
  }

  public function get_client_attendance_by_account($client_account)
  {
      return $this->db
                  ->select('MAX(tbl_transaction.tr_angsuranke) as client_max_angsuranke')
                  ->select('SUM(tbl_transaction.tr_freq) as client_angsuranke')
                  ->select('SUM(tbl_transaction.tr_absen_h) as client_hadir, SUM(tbl_transaction.tr_absen_s) as client_sakit')
                  ->select('SUM(tbl_transaction.tr_absen_c) as client_cuti, SUM(tbl_transaction.tr_absen_i) as client_izin, SUM(tbl_transaction.tr_absen_a) as client_absen, SUM(tbl_transaction.tr_tanggungrenteng) as client_tanggungrenteng')
                  ->from('tbl_pembiayaan')
                  ->join('tbl_clients', 'tbl_clients.client_pembiayaan_id = tbl_pembiayaan.data_id', 'left')
                  ->join('tbl_transaction', 'tbl_transaction.tr_pembiayaan = tbl_pembiayaan.data_id', 'left')
                  ->where('tbl_transaction.tr_angsuranke > 0')
                  ->where('tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id')
                  ->where('tbl_pembiayaan.data_ke = tbl_clients.client_pembiayaan')
                  ->where('tbl_pembiayaan.data_status', '1')
                  ->where('tbl_pembiayaan.deleted', '0')
                  ->where('tbl_transaction.deleted', '0')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_clients.client_account', $client_account)
                  //->group_by('tbl_clients.client_group')
                  //->order_by('tbl_clients.client_officer', 'ASC')
                  ->limit('1,0')
                  ->get()
                  ->result();
  }

    public function get_client_balance_by_account($client_account)
  {
      return $this->db
                  ->select('tbl_tabwajib.tabwajib_credit, tbl_tabwajib.tabwajib_debet, tbl_tabwajib.tabwajib_saldo')
                  ->select('tbl_tabsukarela.tabsukarela_credit, tbl_tabsukarela.tabsukarela_debet, tbl_tabsukarela.tabsukarela_saldo')
                  ->select('tbl_tabberjangka.tabberjangka_credit, tbl_tabberjangka.tabberjangka_debet, tbl_tabberjangka.tabberjangka_saldo')
                  ->from('tbl_clients')
                  ->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabberjangka', 'tbl_tabberjangka.tabberjangka_account = tbl_clients.client_account', 'left')
                  ->where('tbl_clients.client_account', $client_account)
                  ->where('tbl_clients.deleted', '0')
                  //->group_by('tbl_clients.client_group')
                  //->order_by('tbl_clients.client_officer', 'ASC')
                  ->limit('1,0')
                  ->get()
                  ->result();
  }

  public function get_client_financing_by_account($client_account)
  {
      return $this->db
                  ->select('tbl_clients.client_id, tbl_clients.client_account, tbl_clients.client_fullname, tbl_clients.client_group, tbl_clients.client_officer')
                  ->select('tbl_pembiayaan.data_id, tbl_pembiayaan.data_ke, tbl_pembiayaan.data_angsuranke, tbl_pembiayaan.data_plafond, tbl_pembiayaan.data_jangkawaktu')
                  ->select('tbl_pembiayaan.data_totalangsuran, tbl_pembiayaan.data_angsuranpokok, tbl_pembiayaan.data_tabunganwajib, tbl_pembiayaan.data_margin, tbl_pembiayaan.data_sisaangsuran')
                  ->from('tbl_pembiayaan')
                  ->join('tbl_clients', 'tbl_clients.client_pembiayaan_id = tbl_pembiayaan.data_id', 'left')
                  ->where('tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id')
                  ->where('tbl_pembiayaan.data_ke = tbl_clients.client_pembiayaan')
                  ->where('tbl_pembiayaan.data_status', '1')
                  ->where('tbl_pembiayaan.deleted', '0')
                  ->where('tbl_clients.client_account', $client_account)
                  ->where('tbl_clients.deleted', '0')
                  //->group_by('tbl_clients.client_group')
                  //->order_by('tbl_clients.client_officer', 'ASC')
                  ->limit('1,0')
                  ->get()
                  ->result();
}

  public function get_client_details_by_account($client_account)
  {
      return $this->db
                  ->select('tbl_pembiayaan.data_id, tbl_pembiayaan.data_ke, tbl_pembiayaan.data_angsuranke, tbl_pembiayaan.data_plafond, tbl_pembiayaan.data_jangkawaktu, tbl_pembiayaan.data_totalangsuran, tbl_pembiayaan.data_angsuranpokok, tbl_pembiayaan.data_tabunganwajib, tbl_pembiayaan.data_margin, tbl_pembiayaan.data_sisaangsuran')
                  ->select('tbl_clients.client_id, tbl_clients.client_account, tbl_clients.client_fullname, tbl_clients.client_group, tbl_clients.client_officer')

                  ->select('SUM(tbl_transaction.tr_freq) as client_total_angsuranke')
                  ->select('SUM(tbl_transaction.tr_absen_h) as client_hadir, SUM(tbl_transaction.tr_absen_s) as client_sakit, SUM(tbl_transaction.tr_absen_c) as client_cuti, SUM(tbl_transaction.tr_absen_i) as client_izin, SUM(tbl_transaction.tr_absen_a) as client_absen, SUM(tbl_transaction.tr_tanggungrenteng) as client_tanggungrenteng')
                  ->select('tbl_tabwajib.tabwajib_saldo, tbl_tabsukarela.tabsukarela_saldo, tbl_tabberjangka.tabberjangka_saldo')
                  ->from('tbl_pembiayaan')
                  ->join('tbl_clients', 'tbl_clients.client_pembiayaan_id = tbl_pembiayaan.data_id', 'left')
                  ->join('tbl_transaction', 'tbl_transaction.tr_pembiayaan = tbl_pembiayaan.data_id', 'left')
                  ->join('tbl_tabwajib', 'tbl_tabwajib.tabwajib_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabsukarela', 'tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account', 'left')
                  ->join('tbl_tabberjangka', 'tbl_tabberjangka.tabberjangka_account = tbl_clients.client_account', 'left')
                  ->where('tbl_pembiayaan.data_status', '1')
                  ->where('tbl_pembiayaan.deleted', '0')
                  ->where('tbl_transaction.deleted', '0')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id')
                  ->where('tbl_pembiayaan.data_ke = tbl_clients.client_pembiayaan')
                  ->where('tbl_transaction.tr_angsuranke > 0')
                  ->where('tbl_clients.client_account', $client_account)
                  //->group_by('tbl_clients.client_group')
                  //->order_by('tbl_clients.client_officer', 'ASC')
                  ->limit('1,0')
                  ->get()
                  ->result();

      /* ORIGINAL QUERY */
      /*
      $query = "
                  SELECT tbl_pembiayaan.data_id, tbl_pembiayaan.data_ke, tbl_pembiayaan.data_angsuranke, tbl_pembiayaan.data_plafond,
                  tbl_pembiayaan.data_jangkawaktu, tbl_pembiayaan.data_totalangsuran, tbl_pembiayaan.data_angsuranpokok,
                  tbl_pembiayaan.data_tabunganwajib, tbl_pembiayaan.data_margin, tbl_pembiayaan.data_sisaangsuran
                  FROM tbl_pembiayaan
                  LEFT JOIN tbl_clients ON tbl_clients.client_pembiayaan_id = tbl_pembiayaan.data_id
                  LEFT JOIN tbl_transaction ON tbl_transaction.tr_pembiayaan = tbl_pembiayaan.data_id
                  LEFT JOIN tbl_tabwajib ON tbl_tabwajib.tabwajib_account = tbl_clients.client_account
                  LEFT JOIN tbl_tabsukarela ON tbl_tabsukarela.tabsukarela_account = tbl_clients.client_account
                  LEFT JOIN tbl_tabberjangka ON tbl_tabberjangka.tabberjangka_account = tbl_clients.client_account
                  WHERE
                  tbl_pembiayaan.data_status = '1' AND tbl_pembiayaan.deleted = '0' AND tbl_transaction.deleted = '0' AND tbl_clients.deleted = '0'
                  AND tbl_pembiayaan.data_id = tbl_clients.client_pembiayaan_id AND tbl_pembiayaan.data_ke = tbl_clients.client_pembiayaan
                  AND tbl_transaction.tr_angsuranke > 0 AND tbl_clients.client_account = '".$client_account."'
                  LIMIT 0,1
              ";
      return $this->db->query($query)->result();
      */

  }

}
