<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Coba_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
	}

	// public function insert($f_foto)
	// {
	// 	$uuid 			= Uuid::uuid4()->toString();
		
		
	// 	$data = array(
	// 		'uuid' 				=> $uuid,
			
	// 		'foto' 		=> $f_foto,
			
	// 	);	

	// 	$this->db->insert('t_coba', $data);
	// 	return ($this->db->affected_rows() > 0) ? true : false;
	// }

	public function get_all()
	{
		$query = $this->db->select('(SELECT SUM(t.counter / t.speed / 60) FROM tcounter t WHERE t.device_id = mesin.device_id AND t.created_at >= monitor.created_at) as rh_counter', false)
                  ->get_compiled_select();


	}

}