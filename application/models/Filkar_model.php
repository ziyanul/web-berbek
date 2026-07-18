<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Filkar_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('Proses_model');
		$this->load->model('Counter_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
	}
	public function rules()
	{
		return [
			[
				'field' => 'varian_uuid',
				'label' => 'Varian',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'kode_produk',
				'label' => 'Kode Produk',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jam_mulai',
				'label' => 'Jam Mulai',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jam_selesai',
				'label' => 'Jam Selesai',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jml_mp',
				'label' => 'Jumlah Manpower',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			],
			[
				'field' => 'jml_box',
				'label' => 'Jumlah Box',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			],
			[
				'field' => 'jml_kg',
				'label' => 'Jumlah Kg',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			],
			[
				'field' => 'badpro_uuid[]',
				'label' => 'Bad Produk',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jumlah_badpro[]',
				'label' => 'Jumlah Bad Produk',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			]
		];
	}
	public function rules1()
	{
		return [
			[
				'field' => 'kode_produk',
				'label' => 'Kode Produk',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jam_mulai',
				'label' => 'Jam Mulai',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jam_selesai',
				'label' => 'Jam Selesai',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jml_mp',
				'label' => 'Jumlah Manpower',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			],
			[
				'field' => 'jml_box',
				'label' => 'Jumlah Box',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			],
			[
				'field' => 'jml_kg',
				'label' => 'Jumlah Kg',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			]
		];
	}
	public function rules2()
	{
		return [
			[
				'field' => 'tbatch_uuid',
				'label' => 'Kode Batch',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required|numeric'
			]
		];
	}
	public function get_all()
	{
		$this->db->select('f.*, v.varian, tb.kode_batch, f.jumlah_box, f.jumlah_kg');
		$this->db->from('filkar f');
		$this->db->join('tbatch tb', 'tb.uuid = f.tbatch_uuid', 'left');
		$this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
		$this->db->join('varian v', 'v.uuid=tp.varian', 'left');
		$this->db->where('f.deleted_at IS NULL', null, false);
		$this->db->order_by('f.created_at', 'DESC');
		$data = $this->db->get()->result();
		return $data;
	}
	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('filkar', ['uuid' => $uuid, 'deleted_at' => NULL])->row();
	}
	public function get_by_uuid_join($uuid)
	{
		return $this->db
			->select("
            f.*,
            tb.kode_batch,
            v.varian,
            v.keterangan AS nama_varian
        ")
			->from('filkar f')
			->join('tbatch tb', 'tb.uuid=f.tbatch_uuid', 'left')
			->join('t_planning tp', 'tp.uuid=tb.t_planning_uuid', 'left')
			->join('varian v', 'v.uuid=tp.varian', 'left')
			->where('f.uuid', $uuid)
			->where('f.deleted_at', NULL)
			->get()
			->row();
	}
	public function get_badpro_by_update($uuid)
	{
		$this->db->select('sf.*, SUBSTR(f.kode_prod, 1, 5) as tanggal_kode, b.nama_badpro');
		$this->db->join('filkar f', 'sf.filkar_uuid=f.uuid', 'left');
		$this->db->join('badpro b', 'sf.badpro_uuid=b.uuid', 'left');
		$data = $this->db->get_where('sub_filkar sf', array('sf.filkar_uuid' => $uuid))->result();
		return $data;
	}
	public function get_item_name($uuid)
	{
		return $this->db->get_where('wd', array('WD_UUIDNMPRODUK' => $uuid))->row();
	}
	public function get_badpro($proses = null)
	{
		$this->db->select('*, badpro.uuid as uuid_badpro');
		$this->db->from('badpro');
		if ($proses != null) {
			$this->db->join('m_proses', 'm_proses.uuid = badpro.proses_uuid', 'left');
			$this->db->where('m_proses.kode', $proses);
		}
		$this->db->where('badpro.deleted_at', NULL);
		$this->db->order_by('badpro.nama_badpro');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			if ($val->kategori == 1) {
				$val->kategori_nama = 'Rework';
			} elseif ($val->kategori == 2) {
				$val->kategori_nama = 'Reject';
			}
		}
		return $data;
	}
	public function insert_lama()
	{
		$filkar_uuid = Uuid::uuid4()->toString();
		$varian_uuid = $this->input->post('varian_uuid');
		$varian_name = explode(' - ', $this->input->post('varian_name'))[0];
		$kode_prod = $this->input->post('kode_produk');
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');
		$jml_mp = $this->input->post('jml_mp');
		$jumlah_box = $this->input->post('jml_box');
		$jumlah_kg = $this->input->post('jml_kg');
		$keterangan = $this->input->post('keterangan');
		$badpro_array = $this->input->post('badpro_uuid') ?? [];
		$jumlah_array = $this->input->post('jumlah_badpro') ?? [];
		$data_filkar = [
			'uuid' 			=> $filkar_uuid,
			'tbatch_uuid' => $this->input->post('tbatch_uuid'),
			'jumlah_box' => $this->input->post('qty'),
			'jumlah_kg' => $this->input->post('berat'),
			'keterangan' => $this->input->post('keterangan'),
			'user_uuid' => $this->auth_model->current_user()->uuid
		];
		$this->db->insert('filkar', $data_filkar);
		if (!$this->db->affected_rows()) {
			return false;
		}
		foreach ($badpro_array as $key => $badpro_uuid) {
			if (!empty($badpro_uuid) && isset($jumlah_array[$key])) {
				$sub_filkar_uuid = Uuid::uuid4()->toString();
				$data_badpro = [
					'uuid' 			=> $sub_filkar_uuid,
					'user_uuid'     => $this->auth_model->current_user()->uuid,
					'filkar_uuid' 	=> $filkar_uuid,
					'badpro_uuid' 	=> $badpro_uuid,
					'jumlah'	 	=> $jumlah_array[$key],
				];
				$this->db->insert('sub_filkar', $data_badpro);
				if (!$this->db->affected_rows()) {
					return false;
				}
			}
		}
		return true;
	}
	public function update_lama($uuid)
	{
		$kode_prod          = $this->input->post('kode_produk');
		$jam_mulai          = $this->input->post('jam_mulai');
		$jam_selesai        = $this->input->post('jam_selesai');
		$jml_mp             = $this->input->post('jml_mp');
		$jumlah_box         = $this->input->post('jml_box');
		$jumlah_kg          = $this->input->post('jml_kg');
		$keterangan         = $this->input->post('keterangan');
		$badpro_array       = $this->input->post('badpro_uuid') ?? [];
		$jumlah_array       = $this->input->post('jumlah_badpro') ?? [];
		$badproadd_array    = $this->input->post('badproadd') ?? [];
		$jumlahadd_array    = $this->input->post('jumlahadd') ?? [];
		$sub_filkar_uuid_array = $this->input->post('sub_filkar_uuid') ?? [];
		$data = [
			'kode_batch'     => $kode_prod,
			'jam_mulai'     => $jam_mulai,
			'jam_selesai'   => $jam_selesai,
			'jml_mp'        => $jml_mp,
			'jumlah_box'    => $jumlah_box,
			'jumlah_kg'     => $jumlah_kg,
			'keterangan'    => $keterangan,
			'modified_at'   => date('Y-m-d H:i:s')
		];
		$this->db->where('uuid', $uuid);
		$this->db->update('filkar', $data);
		if (!$this->db->affected_rows()) {
			return false; // Gagal update data utama
		}
		foreach ($sub_filkar_uuid_array as $key => $sub_filkar_uuid) {
			if (!empty($sub_filkar_uuid) && isset($badpro_array[$key], $jumlah_array[$key])) {
				$data_badpro = [
					'badpro_uuid' => $badpro_array[$key],
					'jumlah' => $jumlah_array[$key],
					'modified_at' => date('Y-m-d h:i:s')
				];
				$this->db->where('uuid', $sub_filkar_uuid); // Menggunakan UUID sub_filkar
				$this->db->update('sub_filkar', $data_badpro);
				if (!$this->db->affected_rows()) {
					return false;
				}
			}
		}
		foreach ($badproadd_array as $key => $badproadd_uuid) {
			if (!empty($badproadd_uuid) && isset($jumlahadd_array[$key])) {
				$sub_filkar_uuid = Uuid::uuid4()->toString();
				$data_badpro_new = [
					'uuid' 			=> $sub_filkar_uuid,
					'user_uuid'     => $this->auth_model->current_user()->uuid,
					'filkar_uuid' 	=> $uuid,
					'badpro_uuid' 	=> $badproadd_uuid,
					'jumlah'	 	=> $jumlahadd_array[$key],
				];
				$this->db->insert('sub_filkar', $data_badpro_new);
				if (!$this->db->affected_rows()) {
					return false;
				}
			}
		}
		return true;
	}
	function get_batch_uuid($uuid)
	{
		$this->db->select('tb.*, v.box_kg');
		$this->db->from('tbatch tb');
		$this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
		$this->db->join('varian v', 'v.uuid = tp.varian', 'left');
		$this->db->where('tb.uuid', $uuid);
		return $this->db->get()->row();
	}
	public function insert()
	{
		$this->db->trans_begin();
		$tbatch = $this->get_batch_uuid(
			$this->input->post('tbatch_uuid')
		);
		$jumlah_box = 0;
		if ($tbatch && $tbatch->box_kg > 0) {
			$jumlah_box = $this->input->post('berat') / $tbatch->box_kg;
		}
		$uuid = Uuid::uuid4()->toString();
		$data = [
			'uuid'          => $uuid,
			'tbatch_uuid'   => $this->input->post('tbatch_uuid'),
			'proses_uuid'	=> $this->Proses_model->get_uuid('FILKAR'),
			'jumlah_box'    => $jumlah_box,
			'jumlah_kg'     => $this->input->post('berat'),
			'keterangan'    => $this->input->post('keterangan'),
			'user_uuid'     => $this->Auth_model->current_user()->uuid
		];
		$this->db->insert('filkar', $data);
		// ============================
		// Ambil UUID proses FILKAR
		// ============================
		$proses = $this->db
			->get_where('m_proses', ['kode' => 'FILKAR'])
			->row();
		$badpro = $this->input->post('badpro_uuid');
		$jumlah = $this->input->post('jumlah_badpro');
		if (!empty($badpro)) {
			foreach ($badpro as $i => $bp) {
				if (empty($bp)) {
					continue;
				}
				$this->db->insert('t_badpro', [
					'uuid'          => Uuid::uuid4()->toString(),
					'tbatch_uuid'   => $data['tbatch_uuid'],
					'proses_uuid'   => $this->Proses_model->get_uuid('FILKAR'),
					'ref_uuid'      => $uuid,
					'badpro_uuid'   => $bp,
					'berat'         => $jumlah[$i],
					'keterangan'    => '',
					'created_by'    => $this->Auth_model->current_user()->uuid,
					'created_at'    => date('Y-m-d H:i:s')
				]);
			}
		}
		$this->update_total_filkar($data['tbatch_uuid']);
		$this->update_total_bad_filkar($data['tbatch_uuid']);
		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			return TRUE;
		}
		$this->db->trans_rollback();
		return FALSE;
	}
	public function update($uuid)
	{
		$lama = $this->get_by_uuid($uuid);
		$this->db->trans_start();
		$batch = $this->input->post('tbatch_uuid');
		$tbatch = $this->get_batch_uuid(
			$this->input->post('tbatch_uuid')
		);
		$jumlah_box = 0;
		if ($tbatch && $tbatch->box_kg > 0) {
			$jumlah_box = $this->input->post('berat') / $tbatch->box_kg;
		}
		$data = [
			'tbatch_uuid' => $this->input->post('tbatch_uuid'),
			'jumlah_box'  => $jumlah_box,
			'jumlah_kg'   => $this->input->post('berat'),
			'keterangan'  => $this->input->post('keterangan'),
			'modified_at' => date('Y-m-d H:i:s')
		];
		$this->db->where('uuid', $uuid);
		$this->db->update('filkar', $data);
		// ======================================
		// Hapus bad produk lama
		// ======================================
		$proses_uuid = $this->Proses_model->get_uuid('FILKAR');
		$this->db->where('proses_uuid', $proses_uuid);
		$this->db->where('ref_uuid', $uuid);
		$this->db->delete('t_badpro');
		// ======================================
		// Insert ulang
		// ======================================
		$badpro = $this->input->post('badpro_uuid');
		$jumlah = $this->input->post('jumlah_badpro');
		if (!empty($badpro)) {
			foreach ($badpro as $i => $bp) {
				if (empty($bp)) {
					continue;
				}
				$this->db->insert('t_badpro', [
					'uuid'         => Uuid::uuid4()->toString(),
					'tbatch_uuid'  => $data['tbatch_uuid'],
					'proses_uuid'  => $proses_uuid,
					'ref_uuid'     => $uuid,
					'badpro_uuid'  => $bp,
					'berat'        => $jumlah[$i],
					'keterangan'   => '',
					'created_at'   => date('Y-m-d H:i:s')
				]);
			}
		}
		// update total batch lama
		$this->update_total_filkar($lama->tbatch_uuid);
		$this->update_total_bad_filkar($lama->tbatch_uuid);
		// jika batch berubah
		if ($lama->tbatch_uuid != $data['tbatch_uuid']) {
			$this->update_total_filkar($data['tbatch_uuid']);
			$this->update_total_bad_filkar($data['tbatch_uuid']);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	public function get_varian_wd()
	{
		// Ambil kombinasi kode dan UUID yang sudah digunakan di tabel `filkar`
		$this->db->select('kode_batch');
		$this->db->from('filkar');
		$used_combinations = $this->db->get()->result_array();
		// Ubah ke array untuk pengecekan cepat
		$used_map = array_map(
			function ($item) {
				return $item['kode_batch'];
			},
			$used_combinations
		);
		// Ambil data dari tabel `wd` yang belum digunakan
		$this->db->select('WD_ID, WD_KDPRODUK, WD_NMPRODUK, v.keterangan, WD_UUIDNMPRODUK');
		$this->db->from('wd');
		$this->db->join('varian v', 'v.uuid=wd.WD_UUIDNMPRODUK', 'left');
		$this->db->where('WD_IS_DELETE', 0);
		$this->db->where('WD_UUIDNMPRODUK IS NOT NULL');
		$this->db->order_by('WD_ID', 'ASC');
		$wd_data = $this->db->get()->result_array();
		foreach ($wd_data as $data) {
			$key = $data['WD_KDPRODUK'] . '|' . $data['WD_UUIDNMPRODUK'];
			// Jika kombinasi kode+UUID belum digunakan, kembalikan data ini
			if (!in_array($key, $used_map)) {
				return $data;
			}
		}
		// Jika semua data sudah digunakan, kembalikan null
		return null;
	}
	public function get_kode_by_varian($varian_uuid)
	{
		$this->db->select('ew.WD_KDPRODUK, ew.WD_NMPRODUK');
		$this->db->from('wd ew');
		$this->db->where('ew.WD_UUIDNMPRODUK', $varian_uuid);
		$data = $this->db->get()->result();
		return $data;
	}
	public function get_by_tanggal($varian_uuid, $tanggal_kode)
	{
		$this->db->select('f.*, us.fullname');
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = f.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = f.kr_uuid) AS leader", false);
		$this->db->from('filkar f');
		$this->db->join('users us', 'f.user_uuid=us.uuid', 'left');
		$this->db->order_by('f.created_at', 'ASC');
		$this->db->where('f.varian_uuid', $varian_uuid);
		$this->db->where('SUBSTR(f.kode_prod, 1, 5) =', $tanggal_kode);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->tanggal_kode = SUBSTR($val->kode_prod, 0, 5);
		}
		return $data;
	}
	public function get_nav_form($varian_uuid, $tanggal_kode)
	{
		$this->db->select('*');
		$this->db->from('filkar');
		$this->db->where('varian_uuid', $varian_uuid);
		$this->db->where('SUBSTR(kode_prod, 1, 5) =', $tanggal_kode);
		$data = $this->db->get()->row();
		$data->tgl = date('d M Y', strtotime($data->created_at));
		return $data;
	}
	public function get_badpro_by_sub($varian_uuid, $tanggal_kode)
	{
		$this->db->select('sf.*, b.nama_badpro');
		$this->db->from('sub_filkar sf');
		$this->db->join('filkar f', 'sf.filkar_uuid=f.uuid', 'left');
		$this->db->join('badpro b', 'sf.badpro_uuid=b.uuid', 'left');
		$this->db->order_by('b.nama_badpro', 'ASC');
		$this->db->where('f.varian_uuid', $varian_uuid);
		$this->db->where('SUBSTR(f.kode_prod, 1, 5) =', $tanggal_kode);
		$data = $this->db->get()->result();
		return $data;
	}
	public function get_badpro_by_ref($uuid)
	{
		$proses_uuid = $this->Proses_model->get_uuid('FILKAR');
		$this->db->select("
			t_badpro.*,
			badpro.nama_badpro,
			badpro.kategori
			");
		$this->db->from('t_badpro');
		$this->db->join(
			'badpro',
			'badpro.uuid = t_badpro.badpro_uuid',
			'left'
		);
		$this->db->where('t_badpro.ref_uuid', $uuid);
		$this->db->where('t_badpro.proses_uuid', $proses_uuid);
		$this->db->where('t_badpro.deleted_at', NULL);
		$this->db->order_by('badpro.kategori');
		$data = $this->db->get()->result();
		foreach ($data as $d) {
			$d->kategori_nama = ($d->kategori == 1) ? 'Rework' : 'Reject';
		}
		return $data;
	}
	public function get_total_by_tanggal($varian_uuid, $tanggal_kode)
	{
		$this->db->select('SUM(f.jumlah_kg) as total_kg, SUM(f.jumlah_box) as total_box');
		$this->db->from('filkar f');
		$this->db->where('f.varian_uuid', $varian_uuid);
		$this->db->where('SUBSTR(f.kode_prod, 1, 5) =', $tanggal_kode);
		$totals = $this->db->get()->row();
		return $totals;
	}
	public function get_total_badpro($varian_uuid, $tanggal_kode)
	{
		$this->db->select('b.nama_badpro, SUM(sf.jumlah) as total_badpro');
		$this->db->from('sub_filkar sf');
		$this->db->join('filkar f', 'sf.filkar_uuid = f.uuid', 'left');
		$this->db->join('badpro b', 'sf.badpro_uuid = b.uuid', 'left');
		$this->db->where('f.varian_uuid', $varian_uuid);
		$this->db->where('SUBSTR(f.kode_prod, 1, 5) =', $tanggal_kode);
		$this->db->group_by('b.nama_badpro');
		$totals = $this->db->get()->result();
		return $totals;
	}
	public function approval($varian_uuid, $tanggal_kode, $role)
	{
		$data = [];
		if ($role === '2') {
			$data['spv_uuid'] = $this->auth_model->current_user()->uuid;
		} elseif ($role === '1') {
			$data['kr_uuid'] = $this->auth_model->current_user()->uuid;
		}
		$this->db->where('varian_uuid', $varian_uuid); // Update berdasarkan varian
		$this->db->where('SUBSTR(kode_prod, 1, 5) =', $tanggal_kode); // Update berdasarkan kode produk
		$this->db->update('filkar', $data);
		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}
	public function delete($uuid)
	{
		$this->db->trans_begin();
		try {
			$row = $this->get_by_uuid($uuid);
			if (!$row) {
				throw new Exception('Data tidak ditemukan');
			}
			// soft delete filkar
			$this->db
				->where('uuid', $uuid)
				->update('filkar', [
					'deleted_at' => date('Y-m-d H:i:s')
				]);
			// soft delete bad produk FILKAR
			$proses_uuid = $this->Proses_model->get_uuid('FILKAR');
			$this->db
				->where('ref_uuid', $uuid)
				->where('proses_uuid', $proses_uuid)
				->update('t_badpro', [
					'deleted_at' => date('Y-m-d H:i:s')
				]);
			// update total batch
			$this->update_total_filkar($row->tbatch_uuid);
			$this->update_total_bad_filkar($row->tbatch_uuid);
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Gagal menghapus data');
			}
			$this->db->trans_commit();
			return TRUE;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e->getMessage());
			return FALSE;
		}
	}
	private function update_total_filkar($tbatch_uuid)
	{
		$this->db->select_sum('jumlah_kg');
		$this->db->select_sum('jumlah_box');
		$this->db->from('filkar');
		$this->db->where('tbatch_uuid', $tbatch_uuid);
		$this->db->where('deleted_at', NULL);
		$total = $this->db->get()->row();
		$jumlah_kg = $total->jumlah_kg ?? 0;
		$jumlah_box = $total->jumlah_box ?? 0;
		$this->db
			->where('uuid', $tbatch_uuid)
			->update('tbatch', [
				'filkar_kg' => $jumlah_kg,
				'filkar_box' => $jumlah_box
			]);
	}
	public function get_batch()
	{
		$this->db->select("
			b.uuid,
			b.kode_batch,
			b.adonan,
			b.filkar_kg,
			v.varian, v.keterangan, v.kontainer_kg, v.box_kg
			");
		$this->db->from('tbatch b');
		$this->db->join('t_planning p', 'p.uuid = b.t_planning_uuid');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->where('b.deleted_at', NULL);
		$this->db->order_by('b.created_at', 'DESC');
		$this->db->order_by('b.kode_batch', 'DESC');
		$this->db->limit(10);
		return $this->db->get()->result();
	}
	public function update_total_bad_filkar($tbatch_uuid)
	{
		$proses_uuid = $this->Proses_model->get_uuid('FILKAR');
		$this->db->select("
			SUM(CASE WHEN badpro.kategori = 1 THEN t_badpro.berat ELSE 0 END) AS rework,
			SUM(CASE WHEN badpro.kategori = 2 THEN t_badpro.berat ELSE 0 END) AS reject
			");
		$this->db->from('t_badpro');
		$this->db->join('badpro', 'badpro.uuid=t_badpro.badpro_uuid');
		$this->db->where('t_badpro.tbatch_uuid', $tbatch_uuid);
		$this->db->where('t_badpro.proses_uuid', $proses_uuid);
		$this->db->where('t_badpro.deleted_at', NULL);
		$total = $this->db->get()->row();
		$this->db->where('uuid', $tbatch_uuid);
		$this->db->update('tbatch', [
			'bad_filkar_rework_kg' => $total->rework ?? 0,
			'bad_filkar_reject_kg' => $total->reject ?? 0,
		]);
	}
}
