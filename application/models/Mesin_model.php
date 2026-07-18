<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Mesin_model extends CI_Model 
{
	public function __construct()
    {
        parent::__construct();
    }

	public function rules()
	{
		return [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'mesin',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'rhupdate',
				'label' => 'RH Terbaru',
				'rules' => 'required'
			]
		];
	}

	public function get_mesin_name($uuid)
	{
		return $this->db->get_where('mesin', array('uuid' => $uuid))->row();
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('mesin')->result();
	}

	public function get_by_uuid($area_uuid) // ngambil nama data mesin
	{
		return $this->db->get_where('mesin', array('area_uuid' => $area_uuid ))->row();
	}

	public function get_by_area($area_uuid) 
	{
		return $this->db->get_where('mesin', array('area_uuid' => $area_uuid ))->result();
	}

	public function insert()
	{
		$uuid 			= Uuid::uuid4()->toString();
		$area 			= $this->input->post('area');
		$area_name 		= $this->input->post('area_name');
		$mesin 			= $this->input->post('mesin');
		$rhupdate 		= $this->input->post('rhupdate');

		$data = array(
			'uuid' 			=> $uuid,
			'area_uuid' 	=> $area,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'nama_area' 	=> $area_name,
			'nama_mesin' 	=> $mesin,
			'rh_update' 	=> $rhupdate,
			'username' 		=> $this->auth_model->current_user()->username

		);	

		$this->db->insert('mesin', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$mesin = $this->input->post('mesin'); 
		$rhupdate = $this->input->post('rhupdate');
		$mesin_id = $this->input->post('mesin_id'); 

		$data = array( // inisiasi data yang di input ke database
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'nama_mesin' => $mesin,
			'rh_update' => $rhupdate,
			'device_id' => $mesin_id,
			'username' => $this->auth_model->current_user()->username,
			'modified_at' => date('Y-m-d h:i:s')
		);	

		$this->db->update('mesin', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}
}