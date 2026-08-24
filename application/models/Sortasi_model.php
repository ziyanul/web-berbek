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
			],
			[
				'field' => 'jumlah_sortir',
				'label' => 'Jumlah Sortir',
				'rules' => 'required'
			],
			[
				'field' => 'release_box',
				'label' => 'Jumlah Release',
				'rules' => 'required'
			],
			[
				'field' => 'mulai',
				'label' => 'Mulai',
				'rules' => 'required'
			],
			[
				'field' => 'selesai',
				'label' => 'Selesai',
				'rules' => 'required'
			],
			[
				'field' => 'jml_mp',
				'label' => 'Jumlah MP',
				'rules' => 'required'
			]
		];
	}
	public function get_all()
	{
		$this->db->select('s.*, v.varian, v.keterangan, tb.kode_batch, v.box_kg, s.created_at');
		$this->db->from('Sortasi s');
		$this->db->join('tbatch tb', 'tb.uuid = s.tbatch_uuid', 'left');
		$this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
		$this->db->join('varian v', 'v.uuid=tp.varian', 'left');
		$this->db->where('s.deleted_at IS NULL', null, false);
		$this->db->order_by('s.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tanggal = tanggal_indo($val->created_at);
		}
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
		try {
			/* =====================================================
           UUID SORTASI
        ====================================================== */
			$uuid = Uuid::uuid4()->toString();
			$tbatch_uuid =
				$this->input->post('tbatch_uuid');
			$proses_uuid =
				$this->Proses_model->get_uuid('SORTASI');
			/* =====================================================
           INSERT SORTASI
        ====================================================== */
			$data = [
				'uuid' =>
				$uuid,
				'tbatch_uuid' =>
				$tbatch_uuid,
				'proses_uuid' =>
				$proses_uuid,
				'jml_release' =>
				$this->input->post('release_box'),
				'jumlah_wip' =>
				$this->input->post('jumlah_sortir'),
				'keterangan' =>
				$this->input->post('keterangan'),
				'jam_mulai' =>
				$this->input->post('mulai'),
				'jam_selesai' =>
				$this->input->post('selesai'),
				'jml_mp' =>
				$this->input->post('jml_mp'),
				'user_uuid' =>
				$this->Auth_model
					->current_user()
					->uuid
			];
			$this->db->insert(
				'sortasi',
				$data
			);
			/* =====================================================
           POST BAD PRODUK
           badpro_uuid[]
           badpro_berat[]
           mesin_uuid[index][]
        ====================================================== */
			$badpro_uuid = $this->input->post('badpro_uuid');
			$badpro_berat = $this->input->post('badpro_berat');
			$mesin_uuid = $this->input->post('mesin_uuid');
			if (!empty($badpro_uuid) && is_array($badpro_uuid)) {
				foreach ($badpro_uuid as $index => $bp_uuid) {
					if (empty($bp_uuid)) {
						continue;
					}
					$berat = isset($badpro_berat[$index])
						? (float) $badpro_berat[$index]
						: 0;
					if ($berat <= 0) {
						continue;
					}
					/*
         * ==========================================
         * INSERT 1 RECORD BAD PRODUK
         * ==========================================
         */
					$t_badpro_uuid = Uuid::uuid4()->toString();
					$this->db->insert('t_badpro', [
						'uuid'         => $t_badpro_uuid,
						'tbatch_uuid'  => $tbatch_uuid,
						'proses_uuid'  => $proses_uuid,
						'ref_uuid'     => $uuid,
						'badpro_uuid'  => $bp_uuid,
						'berat'        => $berat,
						'keterangan'   => '',
						'created_by'   => $this->Auth_model->current_user()->uuid,
						'created_at'   => date('Y-m-d H:i:s')
					]);
					/*
         * ==========================================
         * INSERT MESIN DOMINAN
         * ==========================================
         */
					$mesin_list = isset($mesin_uuid[$index])
						? $mesin_uuid[$index]
						: ['Lain-lain'];
					if (!empty($mesin_list) && is_array($mesin_list)) {
						foreach ($mesin_list as $mesin) {
							$this->db->insert('t_badpro_mesin', [
								'uuid'         => Uuid::uuid4()->toString(),
								't_badpro_uuid' => $t_badpro_uuid,
								'mesin_uuid'   => $mesin
							]);
						}
					}
				}
			}
			/* =====================================================
           UPDATE TOTAL SORTASI
        ====================================================== */
			$this->update_total_sortasi(
				$tbatch_uuid
			);
			/* =====================================================
           UPDATE TOTAL BAD PRODUK
        ====================================================== */
			$this->update_total_bad_sortasi(
				$tbatch_uuid
			);
			/* =====================================================
           CEK TRANSAKSI
        ====================================================== */
			if ($this->db->trans_status()) {
				$this->db->trans_commit();
				return TRUE;
			}
			$this->db->trans_rollback();
			return FALSE;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message(
				'error',
				'Insert Sortasi Error: ' .
					$e->getMessage()
			);
			return FALSE;
		}
	}
	public function update($uuid)
	{
		$this->db->trans_begin();
		$tbatch_uuid = $this->input->post('tbatch_uuid');
		$proses_uuid =
			$this->Proses_model->get_uuid('SORTASI');
		/*
     * =====================================================
     * UPDATE SORTASI
     * =====================================================
     */
		$data = [
			'tbatch_uuid' => $tbatch_uuid,
			'jml_release' => $this->input->post('release_box'),
			'jumlah_wip'  => $this->input->post('jumlah_sortir'),
			'keterangan'  => $this->input->post('keterangan'),
			'jam_mulai'  => $this->input->post('mulai'),
			'jam_selesai'  => $this->input->post('selesai'),
			'jml_mp'  => $this->input->post('jml_mp')
		];
		$this->db
			->where('uuid', $uuid)
			->where('deleted_at', NULL)
			->update('sortasi', $data);
		/*
     * =====================================================
     * HAPUS DATA MESIN DOMINAN LAMA
     * =====================================================
     */
		$subquery = $this->db
			->select('uuid')
			->from('t_badpro')
			->where('ref_uuid', $uuid)
			->where('proses_uuid', $proses_uuid)
			->where('deleted_at', NULL)
			->get()
			->result();
		foreach ($subquery as $row) {
			$this->db
				->where(
					't_badpro_uuid',
					$row->uuid
				)
				->delete('t_badpro_mesin');
		}
		/*
     * =====================================================
     * HAPUS BAD PRODUK LAMA
     * =====================================================
     */
		$this->db
			->where('ref_uuid', $uuid)
			->where('proses_uuid', $proses_uuid)
			->delete('t_badpro');
		/*
     * =====================================================
     * POST DATA BARU
     * =====================================================
     */
		$badpro_uuid =
			$this->input->post('badpro_uuid');
		$badpro_berat =
			$this->input->post('badpro_berat');
		$mesin_uuid =
			$this->input->post('mesin_uuid');
		/*
     * =====================================================
     * INSERT BAD PRODUK
     * =====================================================
     */
		if (
			!empty($badpro_uuid)
			&&
			is_array($badpro_uuid)
		) {
			foreach ($badpro_uuid as $index => $bp_uuid) {
				if (empty($bp_uuid)) {
					continue;
				}
				$berat =
					isset($badpro_berat[$index])
					? (float) $badpro_berat[$index]
					: 0;
				if ($berat <= 0) {
					continue;
				}
				/*
             * UUID T_BADPRO
             */
				$t_badpro_uuid =
					Uuid::uuid4()->toString();
				/*
             * INSERT T_BADPRO
             */
				$this->db->insert(
					't_badpro',
					[
						'uuid'         => $t_badpro_uuid,
						'tbatch_uuid'  => $tbatch_uuid,
						'proses_uuid'  => $proses_uuid,
						'ref_uuid'     => $uuid,
						'badpro_uuid'  => $bp_uuid,
						'berat'        => $berat,
						'keterangan'   => '',
						'created_by'   => $this->Auth_model
							->current_user()
							->uuid,
						'created_at'   => date(
							'Y-m-d H:i:s'
						)
					]
				);
				/*
             * =================================================
             * INSERT MESIN DOMINAN
             * =================================================
             */
				$mesin_list =
					isset($mesin_uuid[$index])
					? $mesin_uuid[$index]
					: [];
				if (
					!empty($mesin_list)
					&&
					is_array($mesin_list)
				) {
					foreach ($mesin_list as $mesin) {
						if (empty($mesin)) {
							continue;
						}
						$this->db->insert(
							't_badpro_mesin',
							[
								'uuid' =>
								Uuid::uuid4()
									->toString(),
								't_badpro_uuid' =>
								$t_badpro_uuid,
								'mesin_uuid' =>
								$mesin
							]
						);
					}
				}
			}
		}
		/*
     * =====================================================
     * UPDATE TOTAL BATCH
     * =====================================================
     */
		$this->update_total_sortasi(
			$tbatch_uuid
		);
		$this->update_total_bad_sortasi(
			$tbatch_uuid
		);
		/*
     * =====================================================
     * TRANSACTION
     * =====================================================
     */
		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			return TRUE;
		}
		$this->db->trans_rollback();
		return FALSE;
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
		$this->db->select
		("
        b.uuid,
        b.kode_batch,
        b.adonan,
        b.filkar_box,
        (b.filkar_box - COALESCE(b.sortasi_box, 0)) AS sisa_wip,
        v.varian,
        v.keterangan,
        v.kontainer_kg,
        v.box_kg
		");
    $this->db->from('tbatch b');
    $this->db->join('t_planning p', 'p.uuid = b.t_planning_uuid', 'left');
    $this->db->join('varian v', 'v.uuid = p.varian', 'left');
    $this->db->where('b.deleted_at', NULL);
    $this->db->where('(b.filkar_box - COALESCE(b.sortasi_box, 0)) !=', 0);
    $this->db->order_by('b.created_at', 'DESC');
    $this->db->order_by('b.kode_batch', 'DESC');
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
		/*
     * =====================================================
     * AMBIL DATA BAD PRODUK
     * =====================================================
     */
		$this->db->select("
        t_badpro.uuid,
        t_badpro.tbatch_uuid,
        t_badpro.proses_uuid,
        t_badpro.ref_uuid,
        t_badpro.badpro_uuid,
        t_badpro.berat,
        t_badpro.keterangan,
        t_badpro.created_at,
        badpro.nama_badpro,
        badpro.kategori
    ");
		$this->db->from('t_badpro');
		$this->db->join(
			'badpro',
			'badpro.uuid = t_badpro.badpro_uuid',
			'left'
		);
		$this->db->where(
			't_badpro.ref_uuid',
			$ref_uuid
		);
		$this->db->where(
			't_badpro.proses_uuid',
			$proses_uuid
		);
		$this->db->where(
			't_badpro.deleted_at',
			NULL
		);
		$this->db->order_by(
			'badpro.nama_badpro',
			'ASC'
		);
		$rows = $this->db->get()->result();
		/*
     * =====================================================
     * AMBIL MESIN DOMINAN SETIAP BAD PRODUK
     * =====================================================
     */
		foreach ($rows as $r) {
			$this->db->select("
            mesin.uuid,
            mesin.nama_mesin
        ");
			$this->db->from('t_badpro_mesin');
			$this->db->join(
				'mesin',
				'mesin.uuid = t_badpro_mesin.mesin_uuid',
				'left'
			);
			$this->db->where(
				't_badpro_mesin.t_badpro_uuid',
				$r->uuid
			);
			$this->db->where(
				't_badpro_mesin.deleted_at',
				NULL
			);
			$this->db->where(
				'mesin.deleted_at',
				NULL
			);
			$this->db->order_by(
				'mesin.nama_mesin',
				'ASC'
			);
			$mesin = $this->db->get()->result();
			/*
         * Simpan nama mesin dalam bentuk array
         */
			$r->mesin = $mesin;
			/*
         * Untuk tampilan tabel
         */
			$nama_mesin = [];
			foreach ($mesin as $m) {
				$nama_mesin[] = $m->nama_mesin;
			}
			$r->nama_mesin = implode(', ', $nama_mesin);
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
	public function get_badpro_summary_by_ref($ref_uuid)
	{
		$proses_uuid = $this->Proses_model->get_uuid('SORTASI');
		$this->db->select("
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 1
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS rework_kg,
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 2
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS reject_kg,
        COALESCE(
            SUM(t_badpro.berat),
            0
        ) AS total_bad_kg
    ", FALSE);
		$this->db->from('t_badpro');
		$this->db->join(
			'badpro',
			'badpro.uuid = t_badpro.badpro_uuid',
			'left'
		);
		$this->db->where(
			't_badpro.ref_uuid',
			$ref_uuid
		);
		$this->db->where(
			't_badpro.proses_uuid',
			$proses_uuid
		);
		$this->db->where(
			't_badpro.deleted_at',
			NULL
		);
		return $this->db->get()->row();
	}
}
