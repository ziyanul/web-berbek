<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Area_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
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

	public function get_area_name($uuid)
	{
		return $this->db->get_where('area', array('uuid' => $uuid))->row();
	}

	public function get_all_mesin($uuid)
	{
		return $this->db->get_where('mesin', array('area_uuid' => $uuid))->result();
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('area')->result();
	}

	public function get_by_uuid($uuid) // ngambil nama data mesin
	{
		return $this->db->get_where('area', array('uuid' => $uuid ))->row();
	}

	
	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$area = $this->input->post('area');

		$data = array(
			'uuid' => $uuid,
			'nama_area' => $area,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'      => $this->auth_model->current_user()->username
		);	

		$this->db->insert('area', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$area = $this->input->post('area'); // mendapatkan data dari input area

		$data = array( // inisiasi data yang di input ke database
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'nama_area' => $area,
			'username' => $this->auth_model->current_user()->username,
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('area', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}
}