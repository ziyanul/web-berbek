<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class pg_varian_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->config->load('relasi_uuid');
		$this->retort = $this->config->item('retort_uuid');
		$this->packing = $this->config->item('packing_uuid');
	}

	public function rules()
	{
		return [
			[
				'field' => 'uuid_varian_1',
				'label' => 'Varian Produksi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'uuid_kode_prod_1',
				'label' => 'Kode Produksi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'uuid_varian_2',
				'label' => 'Varian Produksi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'uuid_kode_prod_2',
				'label' => 'Kode Produksi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'kondisi',
				'label' => 'Kondisi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			]
		];
	}

	public function rules_1()
	{
		return [
			[
				'field' => 'kondisi',
				'label' => 'Kondisi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			]
		];
	}

	public function get_transition_varian_by_date($date)
	{
		// Ambil data pertama berdasarkan created_at dan kode_use = 0
		// Ambil ST_uuidproduk pertama berdasarkan created_at yang memiliki kode_use = 0
		$this->db->select('ST_uuidproduk');
		$this->db->from('stuffer');
		$this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') >=", $date);
		$this->db->where("ST_uuidproduk IS NOT NULL AND ST_uuidproduk != ''");
		$this->db->where("kode_use", 0);
		$this->db->order_by('created_at', 'ASC'); // Ambil produk pertama berdasarkan created_at
		$this->db->limit(1); // Hanya ambil ST_uuidproduk pertama

		$query_first_product = $this->db->get();
		$first_product = $query_first_product->row();

		if ($first_product) {
			// Ambil data terakhir dari ST_uuidproduk pertama
			$this->db->select('ST_ID, uuid, ST_uuidproduk, ST_nmproduk, ST_kodebatch, created_at, kode_use');
			$this->db->from('stuffer');
			$this->db->where("ST_uuidproduk", $first_product->ST_uuidproduk); // Filter ST_uuidproduk pertama
			$this->db->where(["kode_use" => 0, "ST_IS_DELETE" => 0]);
			$this->db->order_by('created_at', 'DESC'); // Ambil data terakhir dari produk pertama
			$this->db->limit(1); // Hanya ambil 1 data terakhir

			$query_previous = $this->db->get();
			$previous_data = $query_previous->row();
		} else {
			$previous_data = null;
		}


		// $latest_data = null;

		if (!empty($previous_data)) {
			// Ambil data berikutnya yang memiliki ST_ID lebih besar dan ST_uuidproduk berbeda
			$this->db->select('ST_ID, uuid, ST_uuidproduk, ST_nmproduk, ST_kodebatch, created_at, kode_use');
			$this->db->from('stuffer');
			$this->db->where("ST_ID >", $previous_data->ST_ID); // Cari ID lebih besar
			$this->db->where("kode_use", 0); // Filter kode_use = 0
			$this->db->where("ST_uuidproduk !=", $previous_data->ST_uuidproduk); // Pastikan uuid berbeda
			$this->db->order_by('ST_ID', 'ASC');
			$this->db->limit(1);

			$query_latest = $this->db->get();
			$latest_data = $query_latest->row();
		}

		return [
			'latest' => $latest_data,
			'previous' => $previous_data
		];
	}

	public function get_all_retort()
	{
		$this->db->select('pg.*, DATE(pg.created_at) as tanggal');
		$this->db->from('pg_varian_rt pg');
		$this->db->group_by('DATE(pg.created_at)');
		$this->db->order_by('pg.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('pg_varian_rt', array('uuid' => $uuid))->row();
		$data->tanggal = date('Y-m-d', strtotime($data->created_at));
		return $data;
	}

	public function insert_retort()
	{
		$uuid = Uuid::uuid4()->toString();
		$varian_1 = $this->input->post('uuid_varian_1');
		$batch_1 = $this->input->post('uuid_kode_prod_1');
		$varian_2 = $this->input->post('uuid_varian_2');
		$batch_2 = $this->input->post('uuid_kode_prod_2');
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');

		// Ambil nama varian langsung dari input form, bukan dari query
		$varian_name_1 = $this->input->post('varian_name_1');
		$varian_name_2 = $this->input->post('varian_name_2');

		$data = array(
			'uuid'              => $uuid,
			'user_uuid'         => $this->auth_model->current_user()->uuid,
			'uuid_varian_1'     => $varian_1,
			'varian_name_1'     => $varian_name_1, // Ambil dari form
			'uuid_kode_prod_1'  => $batch_1,
			'uuid_varian_2'     => $varian_2,
			'varian_name_2'     => $varian_name_2, // Ambil dari form
			'uuid_kode_prod_2'  => $batch_2,
			'kondisi'           => $kondisi,
			'keterangan'        => $keterangan
		);

		$this->db->insert('pg_varian_rt', $data);
		return ($this->db->affected_rows() > 0);
	}

	public function update_stuffer($date)
	{
		// Ambil data transition varian berdasarkan tanggal
		$transition_data = $this->get_transition_varian_by_date($date);

		$previous_data = $transition_data['previous'];
		// Update kode_use menjadi 1 untuk semua data dengan ST_uuidproduk yang sama
		$this->db->where("ST_uuidproduk", $previous_data->ST_uuidproduk);
		$this->db->where("kode_use", 0); // Hanya update yang masih 0
		$this->db->update("stuffer", ["kode_use" => 1]);

		return ($this->db->affected_rows() > 0);
	}

	public function update_retort($uuid)
	{
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'kondisi' 			=> $kondisi,
			'keterangan' 		=> $keterangan,
			'modified_at' 		=> date('Y-m-d h:i:s')
		);

		$this->db->update('pg_varian_rt', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all_varian()
	{
		$query = $this->db->get('varian');
		return $query->result();
	}

	public function get_by_tanggal($tanggal)
	{
		$this->db->select('p.*, us.fullname');
		$this->db->from('pg_varian_rt p');
		$this->db->join('users us', 'us.uuid=p.user_uuid', 'left');
		$this->db->order_by('p.created_at', 'ASC');
		$this->db->where('DATE(p.created_at)', $tanggal);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));

			if ($val->kondisi == 1) {
				$val->kondisi1 = '<i class="fa fa-check fa-lg text-success"></i>';
				$val->kondisi2 = '-';
			} else if ($val->kondisi == 2) {
				$val->kondisi2 = '<i class="fa fa-check fa-lg text-success"></i>';
				$val->kondisi1 = '-';
			}

			if ($val->kondisi == 1) {
				$val->kondisi_1 = '&check;';
				$val->kondisi_2 = '-';
			} else if ($val->kondisi == 2) {
				$val->kondisi_2 = '&check;';
				$val->kondisi_1 = '-';
			}

			if ($val->qc_id == 0) {
				$val->acc_qc = '-';
			} else {
				$val->acc_qc = '✓';
			}
		}
		return $data;
	}

	public function get_nav_by_tanggal($tanggal)
	{
		$this->db->select('pg.id, pg.uuid, pg.kr_uuid, pg.qc_id, pg.spv_uuid, pg.created_at, us.fullname');
		$this->db->select("(SELECT u2.fullname FROM users u2 WHERE u2.uuid = pg.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u1.fullname FROM users u1 WHERE u1.uuid = pg.kr_uuid) AS leader", false);
		$this->db->from('pg_varian_rt pg');
		$this->db->join('users us', 'us.uuid = pg.user_uuid', 'left');
		$this->db->where('DATE(pg.created_at)', $tanggal);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}
		return $data;
	}

	public function get_all_packing()
	{
		$this->db->group_by('DATE(created_at), shift');
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('sortasi')->result();

		foreach ($data as $val) {
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));
			$val->tgl = date('d M Y', strtotime($val->created_at));

			if ($val->shift == 1) {
				$val->shift_name = 'Pagi';
			} elseif ($val->shift == 2) {
				$val->shift_name = 'Sore';
			} elseif ($val->shift == 3) {
				$val->shift_name = 'Malam';
			}
		}
		return $data;
	}

	public function insert_packing()
	{
		$uuid = Uuid::uuid4()->toString();
		$uuid_sortasi = $this->input->post('uuid_sortasi');
		$varian_uuid = $this->input->post('varian_uuid');
		$kode_prod = $this->input->post('kode_prod');
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'uuid'          => $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'uuid_sortasi'     => $uuid_sortasi,
			'varian_uuid'   => $varian_uuid,
			'kode_prod'     => $kode_prod,
			'kondisi'       => $kondisi,
			'keterangan'    => $keterangan

		);

		$this->db->insert('pg_varian', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_packing($uuid)
	{
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'kondisi'       => $kondisi,
			'keterangan'    => $keterangan,
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('pg_varian', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_varian_by_sortasi($tanggal, $shift)
	{
		$eretort_db_name = $this->db->database;
		$this->db->select('s.*, pg.uuid as pg_uuid, pg.kondisi, pg.keterangan as pg_keterangan, u.fullname, v.varian as varian_1');
		$this->db->select('(SELECT s1.kode_prod FROM sortasi s1 WHERE s1.created_at > s.created_at ORDER BY s1.created_at ASC LIMIT 1) as kode_prod_2', false);
		$this->db->select("(SELECT v2.varian FROM `{$eretort_db_name}`.varian v2 WHERE v2.uuid = (SELECT s1.varian_uuid FROM sortasi s1 WHERE s1.created_at > s.created_at ORDER BY s1.created_at ASC LIMIT 1) LIMIT 1) as varian_2", false);
		$this->db->select('(SELECT u.fullname FROM users u WHERE u.uuid = pg.kr_uuid LIMIT 1) AS leader', false);
		$this->db->select('(SELECT u.fullname FROM users u WHERE u.uuid = pg.spv_uuid LIMIT 1) AS spv', false);
		$this->db->from('sortasi s');
		$this->db->join('pg_varian pg', 'pg.uuid_sortasi = s.uuid', 'left');
		$this->db->join($eretort_db_name . '.varian v', 'v.uuid = s.varian_uuid', 'left');
		$this->db->join('users u', 'u.uuid = pg.user_uuid', 'left');
		$this->db->where('DATE(s.created_at)', $tanggal);
		$this->db->where('s.shift', $shift);
		$this->db->order_by('s.created_at', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));

			if ($val->shift == 1) {
				$val->shift_name = 'Pagi';
			} elseif ($val->shift == 2) {
				$val->shift_name = 'Sore';
			} elseif ($val->shift == 3) {
				$val->shift_name = 'Malam';
			}

			if ($val->kondisi == 1) {
				$val->kondisi1 = '<i class="fa fa-check fa-lg text-success"></i>';
				$val->kondisi2 = '-';
			} else if ($val->kondisi == 2) {
				$val->kondisi2 = '<i class="fa fa-check fa-lg text-success"></i>';
				$val->kondisi1 = '-';
			}

			if ($val->kondisi == 1) {
				$val->kondisi_1 = '&check;';
				$val->kondisi_2 = '-';
			} else if ($val->kondisi == 2) {
				$val->kondisi_2 = '&check;';
				$val->kondisi_1 = '-';
			}
		}
		return $data;
	}

	public function get_pg_varian($uuid)
	{
		$this->db->select('s.*, DATE(s.created_at) as tanggal, us.fullname');
		$this->db->from('sortasi s');
		$this->db->join('users us', 'us.uuid=s.user_uuid', 'left');
		$this->db->where('s.uuid', $uuid);
		$data = $this->db->get()->row();
		return $data;
	}

	public function get_by_uuid_area_packing($uuid)
	{
		$this->db->select('pg.*, DATE(s.created_at) as tanggal , s.id, s.user_uuid, s.shift, s.varian_uuid, s.kode_prod, us.fullname');
		$this->db->from('pg_varian pg');
		$this->db->join('sortasi s', 's.uuid=pg.uuid_sortasi', 'left');
		$this->db->join('users us', 'us.uuid=s.user_uuid', 'left');
		$this->db->where('pg.uuid', $uuid);
		$data = $this->db->get()->row();
		return $data;
	}

	public function approval_packing($tanggal, $shift, $role)
	{
		$formatted_tanggal = date('Y-m-d', strtotime($tanggal));

		if ($formatted_tanggal === '1970-01-01') {
			log_message('error', "Error: strtotime() gagal memproses tanggal: $tanggal");
			return false;
		}

		log_message('error', "Tanggal setelah diproses: $formatted_tanggal, Shift: $shift");

		$sql = "UPDATE pg_varian
            SET $column = ?
            WHERE uuid_sortasi IN (
                SELECT uuid FROM sortasi WHERE DATE(created_at) = ? AND shift = ?
            )";

		$this->db->query($sql, [$current_user_uuid, $formatted_tanggal, $shift]);

		return $this->db->affected_rows();
	}

	public function approval_retort($tanggal, $role)
	{
		$data = [];
		if ($role === '2') {
			$data['spv_uuid'] = $this->auth_model->current_user()->uuid;
		} elseif ($role === '1') {
			$data['kr_uuid'] = $this->auth_model->current_user()->uuid;
		}

		// Tambahkan log untuk debugging
		log_message('error', "Approval Update: Role => $role, Tanggal => $tanggal, Data => " . json_encode($data));

		// Update berdasarkan tanggal (gunakan LIKE untuk menghindari kesalahan format)
		$this->db->where('DATE(created_at LIKE)', "$tanggal%");
		$this->db->update('pg_varian_rt', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			log_message('error', "Approval gagal: Role => $role, Tanggal => $tanggal, Data => " . json_encode($data));
			return false;
		}
	}


	public function update_spv($date, $varian_uuid)
	{
		// Dapatkan UUID user saat ini
		$current_uuid = $this->auth_model->current_user()->uuid;

		// Lakukan update pada rj_cooking_rt
		$this->db->query("
			UPDATE rj_cooking_rt rc
			JOIN " . $this->db->database . ".masak_retort mr
			ON rc.masak_retort_uuid = mr.uuid
			SET rc.spv_uuid = ?
			WHERE mr.MR_DATE = ? AND mr.MR_uuid_varian = ?
			", [$current_uuid, $date, $varian_uuid]);

		return $this->db->affected_rows();
	}
}
