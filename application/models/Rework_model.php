<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Rework_model extends CI_Model
{
	public function __construct()
	{
		$this->load->model('Auth_model');
		parent::__construct();
	}

	public function rules()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];
	}

	public function rules_pakai()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'qty_pemakaian',
				'label' => 'Berat',
				'rules' => 'required'
			],
			[
				'field' => 'kode_batch',
				'label' => 'Kode Batch Sekarang',
				'rules' => 'required'
			],
			[
				'field' => 'plastik',
				'label' => 'Temuan Plastik',
				'rules' => 'required'
			],
			[
				'field' => 'metal',
				'label' => 'Temuan Metal',
				'rules' => 'required'
			]
		];
	}

	public function rules_edit_pakai()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'qty_pemakaian',
				'label' => 'Berat',
				'rules' => 'required'
			],
			[
				'field' => 'kode_batch',
				'label' => 'Kode Batch Sekarang',
				'rules' => 'required'
			],
			[
				'field' => 'plastik',
				'label' => 'Temuan Plastik',
				'rules' => 'required'
			],
			[
				'field' => 'metal',
				'label' => 'Temuan Metal',
				'rules' => 'required'
			]
		];
	}


	public function get_all()
	{
		$this->db->select('k.*, v.varian');
		$this->db->from('rwk_kupas k');
		$this->db->join($this->db->database . '.varian v', 'k.varian_uuid = v.uuid', 'left');
		$this->db->order_by('k.created_at', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}
		return $data;
	}

	public function get_varian()
	{
		$today = date('Y-m-d');
		$this->db->select('t.uuid, t.tanggal, t.varian, t.varian_uuid');
		$this->db->from('t_planning t');
		$this->db->where('t.tanggal', $today);
		$data = $this->db->get()->result();

		return $data;
	}

	public function get_batch_by_varian($varian_uuid)
	{
		$this->db->select('m.kode_batch');
		$this->db->from('mincing m');
		$this->db->join('t_planning t', 'm.planprod_uuid = t.uuid');
		$this->db->where('t.varian_uuid', $varian_uuid);
		$this->db->order_by('m.kode_batch', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_batch_by_planprod($planprod_uuid)
	{
		$this->db->select('m.planprod_uuid, m.MN_BATCH');
		$this->db->from($this->db->database . '.mincing m');
		$this->db->join('t_planning t', 'm.planprod_uuid = t.uuid', 'inner');
		$this->db->where('t.uuid', $planprod_uuid);
		$data = $this->db->get()->result();
		return $data;
	}


	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$varian = $this->input->post('varian');
		$kode_rework = $this->input->post('kode_rework');
		$berat = $this->input->post('berat');

		$data = array(
			'uuid' => $uuid,
			'varian_uuid' => $varian,
			'berat' => $berat,
			'kode_rework' => $kode_rework,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);

		$this->db->insert('rwk_kupas', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_pakai()
	{
		$uuid = Uuid::uuid4()->toString();

		$varian = $this->input->post('varian');
		$plastik = $this->input->post('plastik');
		$metal = $this->input->post('metal');
		$kode_rework = $this->input->post('kode_rework');
		$berat = $this->input->post('qty_pemakaian');
		$produksi = $this->input->post('kode_batch');
		$kupas = $this->db->get_where('rwk_kupas', array('kode_rework' => $kode_rework))->row();

		$data = array(
			'uuid' => $uuid,
			'kode_produksi' => $produksi,
			'rwk_kupas_uuid' => $kupas->uuid,
			'kode_rework' => $kode_rework,
			'dipakai' => $berat,
			'plastik' => $plastik,
			'metal' => $metal,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);

		$this->db->insert('rwk_pakai', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$varian = $this->input->post('varian');
		$berat = $this->input->post('berat');
		$kode_rework = $this->input->post('kode_rework');

		$data = array(

			'varian_uuid' => $varian,
			'kode_rework' => $kode_rework,
			'berat' => $berat,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'  => date('Y-m-d h:i:s')

		);

		$this->db->update('rwk_kupas', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('rwk_kupas', array('uuid' => $uuid))->row();
	}

	public function get_pakai_by_uuid($uuid)
	{
		$this->db->select('p.*, k.uuid AS kupas_uuid, v.varian');
		$this->db->from('rwk_pakai p');
		$this->db->join('rwk_kupas k', 'k.uuid = p.rwk_kupas_uuid', 'left');
		$this->db->join($this->db->database . '.varian v', 'v.uuid = k.varian_uuid', 'left');
		$this->db->where('p.uuid', $uuid);

		$data = $this->db->get()->row();

		return $data;
	}


	public function get_pakai_data_by_tanggal_kode($tanggal_kode)
	{
		$this->db->select(
			'
        p.uuid,
        p.mp_usage_uuid,
        p.rwk_kupas_uuid,

        p.dipakai,
        mp.kode_batch,
		tb.kode_batch as kode_rework,

        p.user_uuid,
		p.acc_qc,
        p.plastik,
        p.metal,

        k.tbatch_uuid,
        k.varian_uuid,
        k.berat AS total_rework,
        k.created_at AS kupas_created_at,

        v.varian,

        DATE_FORMAT(
            k.created_at,
            "%d-%m-%Y"
        ) AS tanggal_masuk
    ',
			false
		);

		/*
     * ==========================================================
     * Nama SPV
     * ==========================================================
     */

		/*
     * ==========================================================
     * Nama Foreman / Leader
     * ==========================================================
     */


		/*
     * ==========================================================
     * Nama Pembuat
     * ==========================================================
     */
		$this->db->select(
			'(SELECT u3.fullname
          FROM users u3
          WHERE u3.uuid = p.user_uuid
        ) AS pembuat',
			false
		);

		/*
     * ==========================================================
     * Sisa stock dari transaksi kupas
     * ==========================================================
     *
     * Sisa =
     * rwk_kupas.berat
     * -
     * seluruh pemakaian pada rwk_kupas tersebut
     */
		$this->db->select(
			'(
            k.berat -
            COALESCE((
                SELECT SUM(rp.dipakai)
                FROM rwk_pakai rp
                WHERE rp.rwk_kupas_uuid = k.uuid
                  AND rp.deleted_at IS NULL
            ), 0)
        ) AS sisa_stock',
			false
		);

		$this->db->from('rwk_pakai p');

		/*
     * ==========================================================
     * Sumber stock rework
     * ==========================================================
     */
		$this->db->join(
			'rwk_kupas k',
			'k.uuid = p.rwk_kupas_uuid',
			'left'
		);

		/*
     * ==========================================================
     * Varian
     * ==========================================================
     */
		$this->db->join(
			'varian v',
			'v.uuid = k.varian_uuid',
			'left'
		);

		$this->db->join('mp_usage mp', 'mp.uuid = p.mp_usage_uuid', 'left');
		$this->db->join('tbatch tb', 'tb.uuid = k.tbatch_uuid', 'left');

		/*
     * ==========================================================
     * User
     * ==========================================================
     */
		$this->db->join(
			'users u',
			'u.uuid = p.user_uuid',
			'left'
		);

		/*
     * ==========================================================
     * Filter kode tanggal produksi
     * ==========================================================
     */
		$this->db->where(
			'SUBSTR(mp.kode_batch, 1, 4) =',
			$tanggal_kode
		);

		/*
     * ==========================================================
     * Urutan
     * ==========================================================
     */
		$this->db->order_by(
			'k.created_at',
			'ASC'
		);

		$this->db->order_by(
			'p.created_at',
			'ASC'
		);

		/*
     * ==========================================================
     * Eksekusi
     * ==========================================================
     */
		$data = $this->db->get()->result();

		/*
     * ==========================================================
     * Format data
     * ==========================================================
     */
		foreach ($data as $val) {

			$val->plastik =
				$val->plastik == 1
				? 'Ya'
				: ($val->plastik == 2
					? 'Tidak'
					: $val->plastik
				);

			$val->metal =
				$val->metal == 1
				? 'Ya'
				: ($val->metal == 2
					? 'Tidak'
					: $val->metal
				);

			$val->kode = substr(
				$val->kode_batch,
				0,
				4
			);

			$val->tanggal =
				$this->convertKodeToTanggal(
					$val->kode
				);

			$val->tanggal_kode =
				$tanggal_kode;

			$val->total_rework =
				(float) $val->total_rework;

			$val->dipakai =
				(float) $val->dipakai;

			$val->sisa_stock =
				(float) $val->sisa_stock;
		}

		return $data;
	}

	public function get_pakai_data()
	{
		// Ambil data dari database
		$this->db->select('*, substr(kode_produksi, 1, 4) as tanggal_kode');
		$this->db->from('rwk_pakai');
		$this->db->order_by('created_at', 'DESC');
		$this->db->group_by('tanggal_kode');
		$data = $this->db->get()->result();

		// Proses konversi tanggal
		foreach ($data as $item) {
			// Ambil substring 'OJ04' dari kode
			$item->tanggal = $this->convertKodeToTanggal($item->tanggal_kode);
		}

		return $data;
	}

	private function convertKodeToTanggal($kode)
	{
		// Huruf pertama sebagai tahun
		$yearBase = 2010;
		$yearChar = $kode[0];
		$yearOffset = ord($yearChar) - ord('A');  // Selisih dari A
		$year = $yearBase + $yearOffset;

		// Huruf kedua sebagai bulan
		$monthChar = $kode[1];
		$monthOffset = ord($monthChar) - ord('A');  // A = Januari
		$months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
		$month = $months[$monthOffset];

		// Dua digit terakhir sebagai hari
		$day = intval(substr($kode, 2, 2));

		// Formatkan ke tanggal d M Y
		return sprintf('%02d %s %04d', $day, $month, $year);
	}


	public function insert_stock($data)
	{
		return $this->db->insert('rwk_kupas', $data);
	}

	// Get all rework stock
	public function get_all_stock()
	{
		return $this->db->get('rwk_kupas')->result();
	}

	// Get rework stock by kode_rework
	public function get_stock_by_kode_rework($kode_rework)
	{
		$this->db->where('kode_rework', $kode_rework);
		$this->db->where('berat >', 0);
		return $this->db->get('rwk_kupas')->row();
	}

	// Insert rework usage into rwk_pakai
	public function insert_usage($data)
	{
		return $this->db->insert('rwk_pakai', $data);
	}

	// Update remaining stock in rwk_kupas
	public function update_stock($id, $new_weight)
	{
		$this->db->set('berat', $new_weight);
		$this->db->where('id', $id);
		return $this->db->update('rwk_kupas');
	}

	public function get_rework_by_varian($varian_uuid)
	{
		$this->db->select('kode_rework, berat');
		$this->db->from('rwk_kupas'); // Assuming 'rwk_kupas' is your table for rework stocks
		$this->db->where('varian_uuid', $varian_uuid);
		$this->db->where('berat >', 0); // Only get codes with remaining stock
		$query = $this->db->get();

		// Prepare the result with remaining stock calculation
		$result = [];
		foreach ($query->result() as $row) {
			$kode_rework = $row->kode_rework;

			// Fetch the total used weight (dipakai) from r_pakai for the selected kode_rework
			$this->db->select_sum('dipakai');
			$this->db->from('rwk_pakai');
			$this->db->where('kode_rework', $kode_rework);
			$pakai = $this->db->get()->row();

			// Calculate remaining stock
			$remaining = $row->berat - ($pakai->dipakai ?? 0);

			// Only include if remaining stock is greater than 0
			if ($remaining > 0) {
				$result[] = $row; // Add the row to the result
			}
		}

		return $result; // Return the filtered result as an array of objects
	}

	public function update_pakai($uuid)
	{
		$plastik = $this->input->post('plastik');
		$qty_pemakaian = $this->input->post('qty_pemakaian');
		$kode_batch = $this->input->post('kode_batch');
		$metal = $this->input->post('metal');

		$data = array(
			'dipakai' => $qty_pemakaian,
			'kode_produksi' => $kode_batch,
			'plastik' => $plastik,
			'metal' => $metal,
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('rwk_pakai', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function approval($tanggal_kode, $role)
	{
		$data = [];
		if ($role == '2') {
			$data['spv_uuid'] = $this->auth_model->current_user()->uuid;
		} elseif ($role == '1') {
			$data['foreman_uuid'] = $this->auth_model->current_user()->uuid;
		}

		if (!empty($data)) {
			$this->db->where("LEFT(kode_produksi, 4) =", $tanggal_kode); // Gunakan LEFT untuk substring
			$this->db->update('rwk_pakai', $data);

			if ($this->db->affected_rows() > 0) {
				// Ambil nama user yang baru approve
				$current_user = $this->auth_model->current_user();
				return $current_user->fullname;
			}
		}

		return false;
	}

	public function get_stock_rework()
	{
		$this->db->select('
        tb.uuid AS tbatch_uuid,
        tb.kode_batch,
        v.varian AS nama_varian,

        SUM(tbp.berat) AS total_rework,

        COALESCE((
            SELECT SUM(rk.berat)
            FROM rwk_kupas rk
            WHERE rk.tbatch_uuid = tb.uuid
              AND rk.deleted_at IS NULL
        ), 0) AS total_kupas
    ');

		$this->db->from('tbatch tb');

		$this->db->join(
			't_badpro tbp',
			'tb.uuid = tbp.tbatch_uuid',
			'left'
		);

		$this->db->join(
			'badpro bp',
			'bp.uuid = tbp.badpro_uuid',
			'left'
		);

		$this->db->join(
			't_planning tp',
			'tp.uuid = tb.t_planning_uuid',
			'left'
		);

		$this->db->join(
			'varian v',
			'v.uuid = tp.varian',
			'left'
		);

		// Hanya bad product kategori REWORK
		$this->db->where('bp.kategori', 1);

		$this->db->where(
			'tbp.deleted_at IS NULL',
			null,
			false
		);

		$this->db->group_by([
			'tb.uuid',
			'tb.kode_batch',
			'v.varian'
		]);

		// Hanya tampilkan batch yang masih mempunyai
		// stock rework yang belum dikupas
		$this->db->having(
			'SUM(tbp.berat) > COALESCE((
            SELECT SUM(rk2.berat)
            FROM rwk_kupas rk2
            WHERE rk2.tbatch_uuid = tb.uuid
              AND rk2.deleted_at IS NULL
        ), 0)',
			null,
			false
		);

		$this->db->order_by('tb.kode_batch', 'ASC');

		$rows = $this->db->get()->result();

		foreach ($rows as &$row) {
			$row->total_rework = (float) $row->total_rework;
			$row->total_kupas  = (float) $row->total_kupas;

			$row->sisa_kupas =
				$row->total_rework - $row->total_kupas;
		}

		return $rows;
	}

	public function get_stock_rework_detail($tbatch_uuid)
	{
		$this->db->select('
        tb.uuid AS tbatch_uuid,
        tb.kode_batch, v.keterangan,
        v.varian AS nama_varian,

        COALESCE((
            SELECT SUM(tbp2.berat)
            FROM t_badpro tbp2
            JOIN badpro bp2
                ON bp2.uuid = tbp2.badpro_uuid
            WHERE tbp2.tbatch_uuid = tb.uuid
              AND bp2.kategori = 1
              AND tbp2.deleted_at IS NULL
        ), 0) AS total_rework,

        COALESCE((
            SELECT SUM(rk.berat)
            FROM rwk_kupas rk
            WHERE rk.tbatch_uuid = tb.uuid
              AND rk.deleted_at IS NULL
        ), 0) AS total_kupas
    ');

		$this->db->from('tbatch tb');

		$this->db->join(
			't_planning tp',
			'tp.uuid = tb.t_planning_uuid',
			'left'
		);

		$this->db->join(
			'varian v',
			'v.uuid = tp.varian',
			'left'
		);

		$this->db->where('tb.uuid', $tbatch_uuid);

		$row = $this->db->get()->row();

		if (!$row) {
			return null;
		}

		$row->total_rework = (float) $row->total_rework;
		$row->total_kupas  = (float) $row->total_kupas;

		$row->sisa_kupas =
			$row->total_rework - $row->total_kupas;

		return $row;
	}

	public function get_total_kupas($tbatch_uuid)
	{
		$this->db->select_sum('berat');

		$this->db->where('tbatch_uuid', $tbatch_uuid);
		$this->db->where('deleted_at IS NULL', null, false);

		$row = $this->db->get('rwk_kupas')->row();

		return $row && $row->berat !== null
			? (float) $row->berat
			: 0;
	}
	/**
	 * =========================================================
	 * HISTORI KUPAS
	 * =========================================================
	 */
	public function get_riwayat_kupas($tbatch_uuid)
	{
		$this->db->select('
            rk.uuid,
            rk.berat,
			u.fullname,
            rk.created_at,
            rk.user_uuid
        ');

		$this->db->from('rwk_kupas rk');
		$this->db->join('users u', 'u.uuid = rk.user_uuid', 'left');
		$this->db->where('rk.tbatch_uuid', $tbatch_uuid);
		$this->db->where('rk.deleted_at IS NULL', null, false);

		$this->db->order_by('rk.created_at', 'DESC');

		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}

		return $data;
	}

	/**
	 * =========================================================
	 * SIMPAN KUPAS
	 *
	 * Validasi dilakukan ulang di server.
	 * =========================================================
	 */
	public function simpan_kupas($tbatch_uuid, $berat)
	{
		$berat = (float) $berat;

		if (!$tbatch_uuid) {
			return [
				'status'  => false,
				'message' => 'Batch tidak ditemukan.'
			];
		}

		if ($berat <= 0) {
			return [
				'status'  => false,
				'message' => 'Berat kupas harus lebih dari 0.'
			];
		}

		$this->db->trans_begin();

		/*
     * ==========================================================
     * 1. Ambil varian dari batch
     * ==========================================================
     *
     * Operator tidak memilih varian.
     * Varian otomatis mengikuti batch yang dipilih.
     *
     * tbatch
     *   -> t_planning
     *      -> varian
     */
		$this->db->select('
        tb.uuid AS tbatch_uuid,
        tp.varian AS varian_uuid
    ');

		$this->db->from('tbatch tb');

		$this->db->join(
			't_planning tp',
			'tp.uuid = tb.t_planning_uuid',
			'inner'
		);

		$this->db->where(
			'tb.uuid',
			$tbatch_uuid
		);

		$batch = $this->db->get()->row();

		if (!$batch) {

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' => 'Batch tidak ditemukan.'
			];
		}

		$varian_uuid = $batch->varian_uuid;

		if (!$varian_uuid) {

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' => 'Varian untuk batch ini tidak ditemukan.'
			];
		}


		/*
     * ==========================================================
     * 2. Ambil sumber rework dari t_badpro
     * ==========================================================
     *
     * Semua bad product kategori REWORK dalam batch
     * dijumlahkan.
     */
		$this->db->select_sum(
			'tbp.berat',
			'total_rework'
		);

		$this->db->from('t_badpro tbp');

		$this->db->join(
			'badpro bp',
			'bp.uuid = tbp.badpro_uuid',
			'inner'
		);

		$this->db->where(
			'tbp.tbatch_uuid',
			$tbatch_uuid
		);

		$this->db->where(
			'bp.kategori',
			1
		);

		$this->db->where(
			'tbp.deleted_at IS NULL',
			null,
			false
		);

		$row = $this->db->get()->row();

		$total_rework = $row
			? (float) $row->total_rework
			: 0;

		if ($total_rework <= 0) {

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' => 'Sumber rework tidak ditemukan.'
			];
		}


		/*
     * ==========================================================
     * 3. Total yang sudah dikupas dari batch ini
     * ==========================================================
     */
		$total_kupas = $this->get_total_kupas(
			$tbatch_uuid
		);

		$total_kupas = (float) $total_kupas;


		/*
     * ==========================================================
     * 4. Hitung sisa rework batch
     * ==========================================================
     */
		$sisa_kupas = $total_rework - $total_kupas;

		if ($sisa_kupas < 0) {
			$sisa_kupas = 0;
		}


		/*
     * ==========================================================
     * 5. Validasi berat kupas
     * ==========================================================
     */
		if ($berat > $sisa_kupas) {

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' => 'Berat kupas melebihi sisa rework.',
				'sisa'    => $sisa_kupas
			];
		}


		/*
     * ==========================================================
     * 6. Generate UUID
     * ==========================================================
     */
		$uuid = Uuid::uuid4()->toString();


		/*
     * ==========================================================
     * 7. Simpan transaksi kupas
     * ==========================================================
     *
     * varian_uuid otomatis berasal dari batch.
     */
		$data = [
			'uuid'        => $uuid,
			'user_uuid'   => $this->Auth_model
				->current_user()
				->uuid,
			'tbatch_uuid' => $tbatch_uuid,
			'varian_uuid' => $varian_uuid,
			'berat'       => $berat,
		];

		$insert = $this->db->insert(
			'rwk_kupas',
			$data
		);


		/*
     * ==========================================================
     * 8. Validasi INSERT
     * ==========================================================
     */
		if (!$insert) {

			$error = $this->db->error();

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' =>
				'Gagal menyimpan transaksi kupas: '
					. ($error['message'] ?? 'Database error.')
			];
		}


		/*
     * ==========================================================
     * 9. Validasi transaksi
     * ==========================================================
     */
		if ($this->db->trans_status() === false) {

			$error = $this->db->error();

			$this->db->trans_rollback();

			return [
				'status'  => false,
				'message' =>
				'Transaksi gagal disimpan: '
					. ($error['message'] ?? 'Database error.')
			];
		}


		/*
     * ==========================================================
     * 10. Commit
     * ==========================================================
     */
		$this->db->trans_commit();


		/*
     * ==========================================================
     * 11. Return
     * ==========================================================
     */
		return [
			'status'       => true,
			'uuid'         => $uuid,
			'varian_uuid'  => $varian_uuid,
			'total_rework' => $total_rework,
			'total_kupas'  => $total_kupas + $berat,
			'sisa_kupas'   => $sisa_kupas - $berat
		];
	}
}
