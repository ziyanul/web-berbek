<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Sortasi_model extends CI_Model
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
				'field' => 'tbatch_uuid',
				'label' => 'Kode Batch',
				'rules' => 'required'
			]
		];
	}
	public function get_all()
	{
		$this->db->select('s.*, v.varian, v.keterangan, tb.kode_batch, v.box_kg');
		$this->db->from('Sortasi s');
		$this->db->join('tbatch tb', 'tb.uuid = s.tbatch_uuid', 'left');
		$this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
		$this->db->join('varian v', 'v.uuid=tp.varian', 'left');
		$this->db->where('s.deleted_at IS NULL', null, false);
		$this->db->order_by('s.created_at', 'DESC');
		$data = $this->db->get()->result();
		return $data;
	}
	public function get_by_uuid($uuid)
	{
		$this->db->select("
			s.*,
			tb.kode_batch,
			tb.filkar_box,
			tb.sortasi_box,
			tb.release_box,
			tb.bad_sortasi_rework_kg,
			tb.bad_sortasi_reject_kg,
			v.uuid as varian_uuid,
			v.varian,
			v.keterangan AS varian_keterangan,
			v.box_kg,
			u.fullname
			");
		$this->db->from('sortasi s');
		$this->db->join('tbatch tb', 'tb.uuid = s.tbatch_uuid', 'left');
		$this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
		$this->db->join('varian v', 'v.uuid = tp.varian', 'left');
		$this->db->join('users u', 'u.uuid = s.user_uuid', 'left');
		$this->db->where('s.uuid', $uuid);
		$this->db->where('s.deleted_at IS NULL', NULL, FALSE);
		return $this->db->get()->row();
	}
	public function get_mesin_batch($tbatch_uuid)
	{
		$this->db->distinct();
		$this->db->select("
        m.uuid,
        m.nama_mesin
    ");
		$this->db->from('tcounter tc');
		$this->db->join(
			'mesin m',
			'm.uuid = tc.mesin_uuid',
			'left'
		);
		$this->db->where('tc.tbatch_uuid', $tbatch_uuid);
		$this->db->where('tc.counter >', 0);
		$this->db->where('tc.deleted_at', NULL);
		$this->db->where('m.deleted_at', NULL);
		$this->db->order_by('m.nama_mesin');
		return $this->db->get()->result();
	}
	public function insert()
	{
		$this->db->trans_begin();
		$uuid = Uuid::uuid4()->toString();
		$tbatch_uuid = $this->input->post('tbatch_uuid');
		$data = [
			'uuid'          => $uuid,
			'tbatch_uuid'   => $tbatch_uuid,
			'proses_uuid'   => $this->Proses_model->get_uuid('SORTASI'),
			'jml_release'   => $this->input->post('release_box'),
			'jumlah_wip'    => $this->input->post('jumlah_sortir'),
			'keterangan'    => $this->input->post('keterangan'),
			'user_uuid'     => $this->Auth_model->current_user()->uuid
		];
		$this->db->insert('sortasi', $data);
		$mesin_uuid = $this->input->post('mesin_uuid');
		$badpro     = $this->input->post('badpro_uuid');
		$jumlah     = $this->input->post('jumlah_badpro');
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
		if (!empty($mesin_uuid)) {
			foreach ($mesin_uuid as $i => $mesin) {
				if (empty($mesin)) {
					continue;
				}
				// jika mesin ini tidak memiliki bad produk
				if (!isset($badpro[$i])) {
					continue;
				}
				foreach ($badpro[$i] as $j => $bp) {
					if (empty($bp)) {
						continue;
					}
					$berat = isset($jumlah[$i][$j])
						? $jumlah[$i][$j]
						: 0;
					if ($berat <= 0) {
						continue;
					}
					$this->db->insert('t_badpro', [
						'uuid'         => Uuid::uuid4()->toString(),
						'tbatch_uuid'  => $tbatch_uuid,
						'proses_uuid'  => $proses_uuid,
						'mesin_uuid'   => $mesin,
						'ref_uuid'     => $uuid,
						'badpro_uuid'  => $bp,
						'berat'        => $berat,
						'keterangan'   => '',
						'created_by'   => $this->Auth_model->current_user()->uuid,
						'created_at'   => date('Y-m-d H:i:s')
					]);
				}
			}
		}
		// ===============================
		// Update total batch
		// ===============================
		$this->update_total_sortasi($tbatch_uuid);
		$this->update_total_bad_sortasi($tbatch_uuid);
		// ===============================
		// Selesai transaksi
		// ===============================
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
		$data = [
			'tbatch_uuid' => $this->input->post('tbatch_uuid'),
			'jumlah_wip'  => $this->input->post('jumlah_sortir'),
			'jml_release' => $this->input->post('release_box'),
			'keterangan'  => $this->input->post('keterangan'),
			'modified_at' => date('Y-m-d H:i:s')
		];
		$this->db->where('uuid', $uuid);
		$this->db->update('sortasi', $data);
		// ======================================
		// Hapus bad produk lama
		// ======================================
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
		$this->db->where('proses_uuid', $proses_uuid);
		$this->db->where('ref_uuid', $uuid);
		$this->db->delete('t_badpro');
		// ======================================
		// Insert ulang
		// ======================================
		$mesin  = $this->input->post('mesin_uuid');
		$badpro = $this->input->post('badpro_uuid');
		$jumlah = $this->input->post('jumlah_badpro');
		if (!empty($mesin)) {
			foreach ($mesin as $i => $mesin_uuid) {
				if (empty($badpro[$i])) {
					continue;
				}
				foreach ($badpro[$i] as $j => $bp) {
					if (empty($bp)) {
						continue;
					}
					$this->db->insert('t_badpro', [
						'uuid'         => Uuid::uuid4()->toString(),
						'tbatch_uuid'  => $data['tbatch_uuid'],
						'proses_uuid'  => $proses_uuid,
						'ref_uuid'     => $uuid,
						'mesin_uuid'   => $mesin_uuid,
						'badpro_uuid'  => $bp,
						'berat'        => $jumlah[$i][$j],
						'keterangan'   => '',
						'created_at'   => date('Y-m-d H:i:s')
					]);
				}
			}
		}
		// update total batch lama
		$this->update_total_sortasi($lama->tbatch_uuid);
		$this->update_total_bad_sortasi($lama->tbatch_uuid);
		// jika batch berubah
		if ($lama->tbatch_uuid != $data['tbatch_uuid']) {
			$this->update_total_sortasi($data['tbatch_uuid']);
			$this->update_total_bad_sortasi($data['tbatch_uuid']);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	private function update_total_sortasi($tbatch_uuid)
	{
		// total box yang sudah disortasi
		$this->db->select_sum('jml_release');
		$this->db->select_sum('jumlah_wip');
		$this->db->where('tbatch_uuid', $tbatch_uuid);
		$this->db->where('deleted_at', NULL);
		$total = $this->db->get('sortasi')->row();
		// ambil box_kg dari batch
		// $batch = $this->Counter_model->get_batch_uuid($tbatch_uuid);
		$jumlah_box = $total->jml_release ?? 0;
		$wip_box = $total->jumlah_wip ?? 0;
		// $jumlah_kg  = $jumlah_box * ($batch->box_kg ?? 0);
		$this->db->where('uuid', $tbatch_uuid);
		$this->db->update('tbatch', [
			'release_box' => $jumlah_box,
			'sortasi_box' => $wip_box
		]);
	}
	public function update_total_bad_sortasi($tbatch_uuid)
	{
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
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
			'bad_sortasi_rework_kg' => $total->rework ?? 0,
			'bad_sortasi_reject_kg' => $total->reject ?? 0,
		]);
	}
	public function get_batch()
	{
		$this->db->select("
			b.uuid,
			b.kode_batch,
			b.adonan,
			b.filkar_box,
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
	public function get_badpro_by_ref($ref_uuid)
	{
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
		$this->db->select("
			t_badpro.*,
			badpro.nama_badpro,
			badpro.kategori, mesin.nama_mesin
			");
		$this->db->from('t_badpro');
		$this->db->join(
			'badpro',
			'badpro.uuid = t_badpro.badpro_uuid',
			'left'
		);
		$this->db->join(
			'mesin',
			'mesin.uuid=t_badpro.mesin_uuid',
			'left'
		);
		$this->db->where('t_badpro.ref_uuid', $ref_uuid);
		$this->db->where('t_badpro.proses_uuid', $proses_uuid);
		$this->db->where('t_badpro.deleted_at', NULL);
		$this->db->order_by('badpro.nama_badpro');
		$this->db->order_by('mesin.nama_mesin');
		$rows = $this->db->get()->result();
		foreach ($rows as $r) {
			$r->kategori_nama = ($r->kategori == 1)
				? 'Rework'
				: 'Reject';
		}
		return $rows;
	}
	public function get_batch_info($uuid)
	{
		$this->db->select("
			tb.uuid,
			tb.filkar_box,
			tb.sortasi_box,
			v.box_kg
			");
		$this->db->from('tbatch tb');
		$this->db->join(
			't_planning tp',
			'tp.uuid=tb.t_planning_uuid'
		);
		$this->db->join(
			'varian v',
			'v.uuid=tp.varian'
		);
		$this->db->where('tb.uuid', $uuid);
		$row = $this->db->get()->row();
		if (!$row) {
			return null;
		}
		$row->sisa_sortasi = $row->filkar_box - $row->sortasi_box;
		return $row;
	}
	public function delete($uuid)
	{
		$data = $this->get_by_uuid($uuid);
		if (!$data) {
			return false;
		}
		$this->db->trans_begin();
		$this->db->where('uuid', $uuid);
		$this->db->update('sortasi', [
			'deleted_at' => date('Y-m-d H:i:s')
		]);
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
		$this->db->where('ref_uuid', $uuid);
		$this->db->where('proses_uuid', $proses_uuid);
		$this->db->update('t_badpro', [
			'deleted_at' => date('Y-m-d H:i:s')
		]);
		$this->update_total_sortasi($data->tbatch_uuid);
		$this->update_total_bad_sortasi($data->tbatch_uuid);
		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			return true;
		}
		$this->db->trans_rollback();
		return false;
	}
}