<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Sub_area_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		//$this->db = $this->load->database('e-retort', TRUE);
	}

	public function rules()
	{
		return [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			]
		];
	}

	public function get_sub_by_area($area_uuid)
	{
		$this->db->select('sub_area.*, a.nama_area');
		$this->db->join('area a', 'a.uuid = sub_area.area_uuid', 'left');
		$data = $this->db->get_where('sub_area', array('area_uuid' => $area_uuid))->result();

		return $data;
	}

	public function get_all()
	{
		$this->db->select('sub_area.*, a.nama_area');
		$this->db->join('area a', 'a.uuid = sub_area.area_uuid', 'left');
		$this->db->where('sub_area.deleted_at', NULL);
		$data =  $this->db->get('sub_area')->result();

		return $data;
	}

	public function lokasi_by_area($uuid)
	{
		$this->db->select('sub_area.*, area.nama_area');
		$this->db->join('area', 'area.uuid = sub_area.area_uuid', 'left');
		return $this->db->get_where('sub_area', array('sub_area.uuid' => $uuid))->row();
	}

	public function get_lokasi_by_area($area_uuid)
	{
		return $this->db->get_where('sub_area', array('area_uuid' => $area_uuid))->result();
	}

	public function get_sub_area($area_uuid)
	{
		return $this->db->get_where('sub_area', array('area_uuid' => $area_uuid))->result();
	}

	public function insert()
	{
		$uuid 			= Uuid::uuid4()->toString();
		$area 			= $this->input->post('area');
		$lokasi 		= $this->input->post('lokasi');

		$data = array(
			'uuid' 			=> $uuid,
			'area_uuid' 	=> $area,
			'lokasi'		=> $lokasi,
			'user_uuid'		=> $this->auth_model->current_user()->user_uuid,
			'username' 		=> $this->auth_model->current_user()->username
		);	

		$this->db->insert('sub_area', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$lokasi = $this->input->post('lokasi'); 
		
		$data = array( // inisiasi data yang di input ke database
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'lokasi' => $lokasi,
			'username' => $this->auth_model->current_user()->username,
			'modified_at' => date('Y-m-d h:i:s')
		);	

		$this->db->update('sub_area', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}

	public function hapus($uuid)
	{
		
		$data = array( // inisiasi data yang di input ke database
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'deleted_at' => date('Y-m-d h:i:s')
		);	

		$this->db->update('sub_area', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}
}