<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Field Officer Apps Model
 *
 * @package amartha
 * @author  afahmi
 * @since   26 Oktober 2015
 */

class officer_model extends MY_Model {

  public function get_officers()
  {
      return $this->db
                  ->select('officer_id, officer_name, branch_name')
                  ->from('tbl_officer')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
                  ->where('tbl_officer.deleted', '0')
                  ->order_by('officer_id', 'ASC')
                  ->get()
                  ->result();
  }

  public function get_an_officer($id)
  {
      return $this->db
                  ->select('officer_id, officer_name, branch_name')
                  ->from('tbl_officer')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
                  ->where('tbl_officer.deleted', '0')
                  ->where('officer_id', $id)
                  ->get()
                  ->result();
  }

  public function get_an_officer_via_login($username, $password)
  {
      return $this->db
                  ->select('officer_id, officer_name, branch_name')
                  ->from('tbl_officer')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_officer.officer_branch', 'left')
                  ->where('tbl_officer.deleted', '0')
                  ->where('officer_username', $username)
                  ->where('officer_password', $password)
                  ->get()
                  ->result();
  }

  public function get_groups()
  {
      return $this->db
                  ->select('group_id, group_name, group_tpl, branch_name')
                  ->from('tbl_group')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
                  ->where('tbl_group.deleted', '0')
                  ->order_by('group_id', 'ASC')
                  ->get()
                  ->result();
  }

  public function get_a_group($id)
  {
      return $this->db
                  ->select('group_id, group_name, group_tpl, branch_name')
                  ->from('tbl_group')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
                  ->where('tbl_group.deleted', '0')
                  ->where('group_id', $id)
                  ->get()
                  ->result();
  }

  public function get_groups_by_officer($officer_id)
  {
      return $this->db
                  ->select('group_id, group_name, branch_name')
                  ->from('tbl_group')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_group.group_branch', 'left')
                  ->where('tbl_group.deleted', '0')
                  ->where('group_tpl', $officer_id)
                  ->order_by('group_id', 'ASC')
                  ->get()
                  ->result();
  }

  public function get_clients_by_officer($officer_id)
  {
      return $this->db
                  ->select('client_id, client_account, client_fullname, branch_name')
                  ->from('tbl_clients')
                  ->join('tbl_branch', 'tbl_branch.branch_id = tbl_clients.client_branch', 'left')
                  ->where('tbl_clients.deleted', '0')
                  ->where('tbl_clients.client_officer', $officer_id)
                  ->order_by('client_id', 'ASC')
                  ->get()
                  ->result();
  }

}
