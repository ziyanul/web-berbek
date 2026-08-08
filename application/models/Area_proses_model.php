<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Area_proses_model extends CI_Model
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

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
        $this->db->where('deleted_at', NULL);
		return $this->db->get('m_proses')->result();
	}

	public function get_by_uuid($uuid) // ngambil nama data mesin
	{
		return $this->db->get_where('m_proses', array('uuid' => $uuid ))->row();
	}


	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$area = $this->input->post('area');

		$data = array(
			'uuid' => $uuid,
			'kode' => $area,
            'nama_proses' => $area,
			'created_by'     => $this->auth_model->current_user()->uuid
		);

		$this->db->insert('m_proses', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$area = $this->input->post('area'); // mendapatkan data dari input area

		$data = array( // inisiasi data yang di input ke database
			'updated_by' => $this->auth_model->current_user()->uuid,
			'nama_proses' => $area,
            'kode' => $area,
			'update_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('m_proses', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}

    public function delete($uuid)
	{
		$data = array(
			'deleted_by' => $this->auth_model->current_user()->username,
			'deleted_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('m_proses', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}
}