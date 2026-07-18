<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Badpro_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		
	}

	public function rules()
	{
		return [
			[
				'field' => 'badpro',
				'label' => 'Bad Pro',
				'rules' => 'required'
			],
			[
				'field' => 'kategori',
				'label' => 'Kategori',
				'rules' => 'required'
			]
		];
	}

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from('badpro b');
		$this->db->where('b.deleted_at', null);
		$this->db->order_by('b.nama_badpro', 'ASC');
		$badpro_list = $this->db->get()->result();

		foreach ($badpro_list as $val) {
			if ($val->kategori == 1) {
				$val->jenis = 'Rework';
			} elseif ($val->kategori == 2) {
				$val->jenis = 'Reject';
			} else {
				$val->jenis = '-';
			}
		}

		return $badpro_list;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('badpro', array('uuid' => $uuid ))->row();
	}


	public function get_sub_by_uuid($uuid) // ngambil nama data mesin
	{
		$this->db->select('sub_badpro.*, badpro.nama_badpro');
		$this->db->from('sub_badpro');
		$this->db->join('badpro', 'badpro.uuid = sub_badpro.badpro_uuid', 'left');
		$this->db->where('sub_badpro.uuid', $uuid);
		$data =  $this->db->get()->row();
		return $data;
	}

	public function insert_master()
	{
		$uuid = Uuid::uuid4()->toString();
		$badpro = $this->input->post('badpro');
		$kategori = $this->input->post('kategori');
		$kode = $this->input->post('proses');
		
		$data = array(
			'uuid'        => Uuid::uuid4()->toString(),
			'nama_badpro' => $badpro,
			'kategori'    => $kategori,
			'proses_uuid' 	     => $kode,
			'user_uuid'   => $this->Auth_model->current_user()->uuid
		);

		$this->db->insert('badpro', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_master($uuid)
	{
		$badpro = $this->input->post('badpro');
		$kategori = $this->input->post('kategori');

		$data = array(
        'nama_badpro' => $badpro, // Nama Badpro
        'kategori' 		=> $kategori,
        'updated_at'  => date('Y-m-d H:i:s') // Timestamp
    );

		$this->db->update('badpro', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function hapus_master($uuid)
	{
		$data = array(
        'deleted_at'  => date('Y-m-d H:i:s') // Timestamp
    );
		$this->db->update('badpro', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_sub($uuid)
	{
		$data = $this->db->get_where('badpro', array('uuid' => $uuid))->row();
		$uuid 		= Uuid::uuid4()->toString();
		$sub_badpro 	= $this->input->post('sub_badpro');
		$data 		= array(
			'uuid' 	=> $uuid,
			'sub_badpro' => $sub_badpro,
			'badpro_uuid' => $data->uuid,
			'user_uuid' 		=> $this->Auth_model->current_user()->uuid
		);
		$this->db->insert('sub_badpro', $data);		
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_sub($uuid)
	{
		$sub_badpro = $this->input->post('sub_badpro');
		$data = array(
			'user_uuid' => $this->Auth_model->current_user()->uuid,
			'sub_badpro' => $sub_badpro,
			'updated_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('sub_badpro', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_proses()
	{
		return $this->db->get('m_proses')->result();
	}
}