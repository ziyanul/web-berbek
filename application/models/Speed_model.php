<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Speed_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('Varian_model');
	}

	public function rulesspeed()
	{
		return [	
			[
				'field' => 'speed',
				'label' => 'Speed',
				'rules' => 'required'
			],
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'mesin',
				'label' => 'Mesin',
				'rules' => 'required'
			]
		];
	}

	public function rulesspeededit()
	{
		return [	
			[
				'field' => 'speed[]',
				'label' => 'Speed',
				'rules' => 'required'
			]
		];
	}
	
	public function update_speed_per_mesin($mesin_uuid)
	{
		$speeds = $this->input->post('speed');

		if (empty($speeds) || !is_array($speeds)) {
			return false;
		}

		$this->db->trans_start();

		foreach ($speeds as $varian_uuid => $speed) {
			$data = [
				'speed'       => (int) $speed,
				'user_uuid'   => $this->auth_model->current_user()->uuid,
				'username'    => $this->auth_model->current_user()->username,
				'modified_at' => date('Y-m-d H:i:s')
			];

			$this->db->where('mesin_uuid', $mesin_uuid);
			$this->db->where('varian_uuid', $varian_uuid);
			$this->db->update('master_speed', $data);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function insertspeed()
	{
		$uuid = Uuid::uuid4()->toString();
		$mesin = $this->input->post('mesin');
		$speed = $this->input->post('speed');
		$varian = $this->input->post('varian');
		$mesin_name = $this->input->post('mesin_name');

			// cek duplikat	
		$this->db->where('mesin_uuid', $mesin);
		$this->db->where('varian_uuid', $varian);
		$cek = $this->db->get('master_speed')->row();

		if ($cek) {
			return 'Speed pada Mesin dan Varian tersebut Sudah Ada!';
		}
		$data = array(
			'uuid'  		=> $uuid,
			'user_uuid'		=> $this->auth_model->current_user()->uuid,
			'username' 		=> $this->auth_model->current_user()->username,
			'mesin_uuid'  	=> $mesin,
			'varian_uuid'  => $varian,
			'speed'  		=> $speed,
			'mesin'  		=> $mesin_name
		);

		$this->db->insert('master_speed', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_master_speed_raw()
	{
		$this->db->select('
			ms.uuid,
			ms.mesin_uuid,
			ms.varian_uuid,
			ms.speed,
			m.nama_mesin,
			v.varian as nama_varian
			');
		$this->db->from('master_speed ms');
		$this->db->join('mesin m', 'm.uuid = ms.mesin_uuid', 'left');
		$this->db->join('varian v', 'v.uuid = ms.varian_uuid', 'left');
		$this->db->where('m.nama_area', 'FILLER');
		$this->db->order_by('m.nama_mesin', 'ASC');
		$this->db->order_by('v.varian', 'ASC');

		return $this->db->get()->result();
	}

	public function get_master_speed_pivot_dynamic()
	{
		$varian_list = $this->Varian_model->get_all();
		$raw = $this->get_master_speed_raw();

		$result = [];

		foreach ($raw as $row) {
			$mesin_uuid = $row->mesin_uuid;

			if (!isset($result[$mesin_uuid])) {
				$result[$mesin_uuid] = [
					'mesin_uuid' => $row->mesin_uuid,
					'nama_mesin' => $row->nama_mesin,
					'speeds' => []
				];
			}

			$result[$mesin_uuid]['speeds'][$row->varian_uuid] = $row->speed;
		}

		return [
			'varian_list' => $varian_list,
			'data' => $result
		];
	}

	public function get_master_speed_by_mesin($mesin_uuid)
	{
		$this->db->select('
			ms.uuid,
			ms.mesin_uuid,
			ms.varian_uuid,
			ms.speed,
			v.varian as nama_varian,
			m.nama_mesin,
			v.keterangan
			');
		$this->db->from('master_speed ms');
		$this->db->join('varian v', 'v.uuid = ms.varian_uuid', 'left');
		$this->db->join('mesin m', 'm.uuid = ms.mesin_uuid', 'left');
		$this->db->where('ms.mesin_uuid', $mesin_uuid);
		$this->db->order_by('v.varian', 'ASC');

		return $this->db->get()->result();
	}

	public function get_all_mesin() 
	{
		$this->db->select('uuid, nama_mesin');
		$this->db->from('mesin');
		$this->db->where('nama_area', 'FILLER');
		$this->db->order_by('created_at', 'ASC');

		$data = $this->db->get()->result();
		return $data;
	}

}