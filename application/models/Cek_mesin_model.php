<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Cek_mesin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('gmp_model');

		$this->config->load('relasi_uuid');
		$this->mp = $this->config->item('mp_uuid');
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
				'field' => 'kegiatan[]',
				'label' => 'Kegiatan',
				'rules' => 'required'
			],
			[
				'field' => 'keterangan[]',
				'label' => 'Keterangan',
				'rules' => 'required'
			]
		];
	}

	public function get_mp()
	{
		$mp = $this->mp;
		$this->db->select('p.uuid, v.varian, v.keterangan, p.tanggal');
		$this->db->select("(SELECT DATE_FORMAT(c.created_at, '%d %b %Y') FROM cek_mesin c WHERE p.uuid = c.t_planning_uuid AND c.area_uuid = '$mp' ORDER BY c.created_at ASC LIMIT 1) as awal_cek", false);
		$this->db->select("(SELECT count(DISTINCT c.mesin_uuid) FROM cek_mesin c WHERE c.t_planning_uuid = p.uuid AND c.area_uuid = '$mp') as jumlah_mesin", false);
		$this->db->from('t_planning p');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->order_by('p.tanggal', 'DESC');
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));
		}

		return $data;
	}

	public function get_plan_uuid($t_planning_uuid)
	{
		return $this->db->get_where('t_planning', ['uuid' => $t_planning_uuid])->row();
	}

	public function cek_area_name($uuid)
	{
		return $this->db->select('nama_area')->from($this->db->database . '.area')->where('uuid', $uuid)->get()->row();
	}

	public function cek_mesin_name($uuid)
	{
		return $this->db->select('nama_mesin')->from('mesin')->where('uuid', $uuid)->get()->row();
	}

	public function cek_kegiatan_by_mesin($mesin_uuid)
	{
		return $this->db->select('uuid, kegiatan')
			->from('item_cekmesin')
			->where('mesin_uuid', $mesin_uuid)
			->get()
			->result();
	}

	public function insert_cek_mesin($t_planning_uuid)
	{
		$area_uuid = $this->input->post('area_uuid');
		$mesin_uuid = $this->input->post('mesin');
		$kegiatan = $this->input->post('kegiatan');
		$keterangan = $this->input->post('keterangan');
		$area = $this->cek_area_name($area_uuid)->nama_area ?? null;
		$mesin = $this->cek_mesin_name($mesin_uuid)->nama_mesin;
		$all_kegiatan = $this->cek_kegiatan_by_mesin($mesin_uuid);

		foreach ($all_kegiatan as $kegiatan_item) {
			$uuid = Uuid::uuid4()->toString();
			$kegiatan_name = $kegiatan_item->kegiatan;
			$kegiatan_uuid = $kegiatan_item->uuid;

			$data = [
				'uuid'             => $uuid,
				'user_uuid'        => $this->auth_model->current_user()->uuid,
				'username'         => $this->auth_model->current_user()->username,
				't_planning_uuid'  => $t_planning_uuid,
				'area_uuid'        => $area_uuid,
				'area'             => $area,
				'mesin_uuid'       => $mesin_uuid,
				'mesin'            => $mesin,
				'item_uuid'        => $kegiatan_uuid,
				'item'             => $kegiatan_name,
				'checklist'        => isset($kegiatan[$kegiatan_item->uuid]) ? $kegiatan[$kegiatan_item->uuid] : 0,
				'paraf_prod'       => null,
				'paraf_qc'         => null,
				'keterangan'       => isset($keterangan[$kegiatan_item->uuid]) ? $keterangan[$kegiatan_item->uuid] : ''
			];

			$this->db->insert('cek_mesin', $data);

			if (empty($area)) {
				log_message('error', 'Area UUID tidak ditemukan: ' . $area_uuid);
				return false;
			}
		}

		return true;
	}

	public function get_mesin_with_usage_status($area_uuid, $t_planning_uuid)
	{
		$area_uuid = $this->mp;
		$this->db->select('m.uuid, m.nama_mesin, COUNT(c.mesin_uuid) AS is_used');
		$this->db->from('mesin m');
		$this->db->join('cek_mesin c', 'm.uuid = c.mesin_uuid AND c.t_planning_uuid = ' . $this->db->escape($t_planning_uuid), 'left');
		$this->db->where('m.area_uuid', $area_uuid);
		$this->db->group_by('m.id'); // Gunakan primary key mesin

		$data = $this->db->get();
		return $data->result();
	}

	public function update_check_akhir($uuid)
	{
		$checklist = $this->input->post('checklist');
		$data = array(
			'checklist2' => $checklist,
			'paraf_prod' => $this->auth_model->current_user()->uuid
		);

		$this->db->update('cek_mesin', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_keterangan($uuid)
	{
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'keterangan2' => $keterangan
		);

		$this->db->update('cek_mesin', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}


	public function get_area_data($t_planning_uuid, $area_uuid)
	{
		$area_uuid = $this->mp;
		$this->db->select(
			'c.*, c.uuid as cek_uuid, t.varian, t.tanggal, u.fullname,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.spv_uuid), "-") AS spv,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.paraf_prod), "-") AS paraf_prod,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.fr_uuid), "-") AS foreman'
		);
		$this->db->from('cek_mesin c');
		$this->db->join('t_planning t', 't.uuid = c.t_planning_uuid', 'left');
		$this->db->join('users u', 'u.uuid = c.user_uuid', 'left');
		$this->db->where(['c.t_planning_uuid' => $t_planning_uuid, 'c.area_uuid' => $area_uuid]);
		$this->db->order_by('c.mesin, c.item');

		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->check_ya = $val->checklist == 2 ? '<i class="fa fa-check fa-lg text-success"></i>' : '-';
			$val->check_tdk = $val->checklist == 0 ? '<i class="fa fa-times fa-lg text-danger"></i>' : '-';
			$val->akhir_ya = $val->checklist2 == 2 ? '<i class="fa fa-check fa-lg text-success"></i>' : '-';
			$val->akhir_tdk = $val->checklist2 == 1 ? '<i class="fa fa-times fa-lg text-danger"></i>' : '-';
		}
		return $data;
	}

	public function get_nav_by_tplanning($t_planning_uuid, $area_uuid)
	{
		$this->db->select(
			't.uuid, t.varian, t.tanggal, c.id, c.area_uuid, c.created_at, c.created_at as tgl_cek,
			c.spv_uuid, c.fr_uuid, c.user_uuid,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.spv_uuid), "-") AS spv,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.fr_uuid), "-") AS foreman,
			COALESCE((SELECT fullname FROM users WHERE uuid = c.user_uuid), "-") AS user'
		);
		$this->db->from('t_planning t');
		$this->db->join('cek_mesin c', 'c.t_planning_uuid = t.uuid AND c.area_uuid = ' . $this->db->escape($area_uuid), 'left');
		$this->db->where('t.uuid', $t_planning_uuid);
		$this->db->order_by('c.created_at', 'DESC');

		$data = $this->db->get()->row();

		if ($data) {
			$data->tgl = $data->tgl_cek ? date('d M Y', strtotime($data->tgl_cek)) : '-';
			$data->tgl_planning = date('d M Y', strtotime($data->tanggal));
		}

		return $data;
	}

	public function get_data_by_uuid($uuid)
	{
		return $this->db->get_where('cek_mesin', array('uuid' => $uuid))->row();
	}

	public function get_item()
	{
		$this->db->select('c.*, a.nama_area, m.nama_mesin');
		$this->db->from('item_cekmesin c');
		$this->db->join($this->db->database . '.area a', 'a.uuid = c.area_uuid', 'left');
		$this->db->join('mesin m', 'm.uuid = c.mesin_uuid', 'left');
		$this->db->order_by('c.created_at, c.id', 'DESC');
		$data = $this->db->get()->result();
		return $data;
	}

	public function insert_item()
	{
		$area_uuid  = $this->input->post('area');
		$mesin_uuid = $this->input->post('mesin');
		$user_uuid  = $this->auth_model->current_user()->uuid;
		$username   = $this->auth_model->current_user()->username;
		$kegiatan   = $this->input->post('kegiatan');

		$data = [];

		foreach ($kegiatan as $item) {
			if (!empty($item)) {
				$uuid = Uuid::uuid4()->toString();
				$data[] = [
					'uuid'       => $uuid,
					'area_uuid'  => $area_uuid,
					'mesin_uuid' => $mesin_uuid,
					'user_uuid'  => $user_uuid,
					'username'   => $username,
					'kegiatan'   => $item,
				];
			}
		}

		if (!empty($data)) {
			$this->db->insert_batch('item_cekmesin', $data);
			return ($this->db->affected_rows() > 0) ? true : false;
		}

		return false;
	}


	public function update_item($uuid)
	{

		$area 		= $this->input->post('area');
		$mesin 		= $this->input->post('mesin');
		$mesin_name 	= $this->input->post('mesin_name');
		$kegiatan 		= $this->input->post('kegiatan');

		$data = array(

			'area_uuid' 	=> $area,
			'mesin_uuid' 	=> $mesin,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'      => $this->auth_model->current_user()->username,
			'kegiatan' 		=> $kegiatan
		);

		$this->db->update('item_cekmesin', $data,  array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('item_cekmesin', array('uuid' => $uuid))->row();
	}


	public function get_kegiatan_by_mesin($mesin_uuid)
	{

		return $this->db->get_where('item_cekmesin', array('mesin_uuid' => $mesin_uuid))->result();
	}

	public function approval_cekmesin($t_planning_uuid, $area_uuid)
	{
		$data = [
			'spv_uuid' => $this->auth_model->current_user()->uuid, // UUID supervisor
		];
		$area_uuid = $this->mp;
		$this->db->where('t_planning_uuid', $t_planning_uuid);
		$this->db->where('area_uuid', $area_uuid); // Filter berdasarkan area_uuid
		$this->db->update('cek_mesin', $data);

		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}

	public function approval_cekmesin2($t_planning_uuid, $area_uuid)
	{
		$data = [
			'fr_uuid' => $this->auth_model->current_user()->uuid, // UUID supervisor
		];
		$area_uuid = $this->mp;
		$this->db->where('t_planning_uuid', $t_planning_uuid);
		$this->db->where('area_uuid', $area_uuid); // Filter berdasarkan area_uuid
		$this->db->update('cek_mesin', $data);

		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}
}
