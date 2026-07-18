<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Varian_model extends CI_Model
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
				'field' => 'varian',
				'label' => 'varian',
				'rules' => 'required'
			],
			
		];
	}

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from('varian');
		$this->db->order_by('varian', 'ASC');
		$this->db->where('deleted_at IS NULL', null, false);
		$data = $this->db->get()->result();
		return $data;
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$varian = $this->input->post('varian');
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'uuid' => $uuid,
			'varian' => $varian,
			'keterangan' => $keterangan,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);	

		$this->db->insert('varian', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$varian = $this->input->post('varian');
		$keterangan = $this->input->post('keterangan');
		$panjang = $this->input->post('panjang');
		$berat = $this->input->post('berat');
		$kontainer_kg = $this->input->post('kontainer_kg');
		$box_kg = $this->input->post('box_kg');

		$data = array(
			
			'varian' => $varian,
			'keterangan' => $keterangan,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'  => date('Y-m-d h:i:s'),
			'panjang' => $panjang,
			'berat' => $berat,
			'kontainer_kg' => $kontainer_kg,
			'box_kg' => $box_kg

		);	

		$this->db->update('varian', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('varian', array('uuid' => $uuid ))->row();
	}
}