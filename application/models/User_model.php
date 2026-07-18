<?php

class User_model extends CI_Model
{
	public function get_all_qc()
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('D_ID=',2);
		$this->db->order_by('U_NAMA');
		$query = $this->db->get();

		return $query->result(); 
	}
}