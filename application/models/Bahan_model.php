<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;

class Bahan_model extends CI_Model
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
				'field' => 'bahan',
				'label' => 'Bahan',
				'rules' => 'required'
			],
			
		];
	}

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from('m_bahan');
		$this->db->order_by('nama_bahan', 'ASC');
		$this->db->where('deleted_at IS NULL', null, false);
		$data = $this->db->get()->result();
		return $data;
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$kode = $this->input->post('kode');
		$bahan = $this->input->post('bahan');
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'uuid' => $uuid,
			'nama_bahan' 	=> $bahan,
			'keterangan' 	=> $keterangan,
			'kode_bahan' 	=> $kode,
			'created_by'     => $this->auth_model->current_user()->uuid

		);	

		$this->db->insert('m_bahan', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$bahan = $this->input->post('bahan');
		$keterangan = $this->input->post('keterangan');
		$kode = $this->input->post('kode');

		$data = array(
			
			'nama_bahan' => $bahan,
			'keterangan' => $keterangan,
			'kode_bahan' => $kode,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'  => date('Y-m-d h:i:s')

		);	

		$this->db->update('m_bahan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('m_bahan', array('uuid' => $uuid ))->row();
	}
}