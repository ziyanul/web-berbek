<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class View_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Filler_model');
		$this->load->model('Rj_filler_model');
		// $this->dberetort = $this->load->database('e-retort', TRUE);
		$this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');
	}

	public function get_plan_data()
	{
		$this->db->select('*');
		$this->db->from('t_planning');
		$this->db->order_by('tanggal', 'DESC');
		$this->db->where('deleted_at', null);
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));

			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}
		}

		return $data;
	}

	public function get_formula_plan_data()
	{
		$this->db->select('*');
		$this->db->from('t_planning');
		$this->db->order_by('tanggal', 'DESC');
		$this->db->where('deleted_at', null);
		$this->db->where('formula>',0);
		$this->db->where('filkar>', 0);
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));

			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}
		}

		return $data;
	}

	public function get_rata_badpro($varian_uuid)
{
	$this->db->select('uuid, filkar');
	$this->db->from('t_planning t');
	$this->db->where('MONTH(tanggal)', date('m'));
	$this->db->where('YEAR(tanggal)', date('Y'));
	$this->db->where('t.varian', $varian_uuid);
	$plannings = $this->db->get()->result();

	$this->db->select('DISTINCT(sb.badpro_uuid), bp.badpro');
	$this->db->from('v_sr_badpro sb');
	$this->db->join('v_badpro bp', 'bp.uuid = sb.badpro_uuid', 'left');
	$this->db->join('t_planning tp', 'tp.uuid = sb.t_planning_uuid', 'left');
	$this->db->where('sb.deleted_at IS NULL');
	$this->db->where('MONTH(tp.tanggal)', date('m'));
	$this->db->where('YEAR(tp.tanggal)', date('Y'));
	$this->db->where('tp.varian', $varian_uuid); // pastikan filtering varian
	$badpros = $this->db->get()->result();

	$badpro_data = [];
	foreach ($badpros as $b) {
		$badpro_data[$b->badpro_uuid] = [
			'badpro' => $b->badpro,
			'persentase_list' => []
		];
	}

	foreach ($plannings as $plan) {
		$this->db->select('sb.badpro_uuid, SUM(sb.jumlah) as total_jumlah');
		$this->db->from('v_sr_badpro sb');
		$this->db->where('sb.deleted_at IS NULL');
		$this->db->where('sb.t_planning_uuid', $plan->uuid);
		$this->db->group_by('sb.badpro_uuid');
		$result = $this->db->get()->result();

		$jumlah_per_badpro = [];
		foreach ($result as $r) {
			$jumlah_per_badpro[$r->badpro_uuid] = $r->total_jumlah;
		}

		foreach ($badpro_data as $uuid => &$badpro) {
			$jumlah = isset($jumlah_per_badpro[$uuid]) ? $jumlah_per_badpro[$uuid] : 0;
			$persen = ($plan->filkar > 0) ? ($jumlah / $plan->filkar) * 100 : 0;
			$badpro['persentase_list'][] = $persen;
		}
	}

	$result = [];
	foreach ($badpro_data as $uuid => $badpro) {
		$avg = count($badpro['persentase_list']) > 0
			? round(array_sum($badpro['persentase_list']) / count($badpro['persentase_list']), 2)
			: 0;

		$result[] = [
			'badpro' => $badpro['badpro'],
			'persen_bad_sortasi' => $avg
		];
	}

	return $result;
}


	public function get_rata_bp_perplan($t_planning_uuid)
{
	$this->db->select('sb.badpro_uuid, AVG(sb.jumlah) as rata_jumlah, bp.badpro, tp.filkar');
	$this->db->from('v_sr_badpro sb');
	$this->db->join('v_badpro bp', 'bp.uuid = sb.badpro_uuid', 'left');
	$this->db->join('t_planning tp', 'tp.uuid = sb.t_planning_uuid', 'left');
	$this->db->where('sb.deleted_at IS NULL');
	$this->db->where('sb.t_planning_uuid', $t_planning_uuid);
	$this->db->where('MONTH(tp.tanggal)', date('m'));
	$this->db->where('YEAR(tp.tanggal)', date('Y'));
	$this->db->group_by('sb.badpro_uuid, bp.badpro, tp.filkar');
	$this->db->order_by('bp.badpro', 'ASC');

	$data = $this->db->get()->result();

	foreach ($data as $val) {
		$val->persen_bad_sortasi = ($val->filkar > 0) ? round(floatval($val->rata_jumlah) / $val->filkar * 100, 2) : 0;
	}
	return $data;
}


	public function get_sortasi_data()
	{
		$this->db->select('tp.*, 
			(SELECT SUM(sr.jumlah) FROM v_sr_badpro sr WHERE sr.t_planning_uuid = tp.uuid) as total_berat,
			(SELECT SUM(tc.counter) FROM tcounter tc JOIN tbatch ON tbatch.uuid = tc.tbatch_uuid WHERE tbatch.t_planning_uuid = tp.uuid) as total_counter', false);
		$this->db->from('t_planning tp');
		$this->db->where('EXISTS (SELECT 1 FROM v_sr_badpro sr WHERE sr.t_planning_uuid = tp.uuid AND sr.deleted_at IS NULL)');
		$this->db->order_by('tp.tanggal', 'DESC');
		$data = $this->db->get()->result();



		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));

			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}

			$val->sortasi_persen = 0;
			if (!empty($val->total_counter) && $val->total_counter > 0) {
				$val->sortasi_persen = ($val->total_berat ?: 0) / $val->total_counter * 100;
			}
		}
		return $data;
	}

	public function get_sortasi_by_plan()
	{
		$this->db->select('s.*, t.tanggal, t.varian');
		$this->db->from('v_sortasi s');
		$this->db->join('t_planning t', 't.uuid = s.t_planning_uuid', 'left');
		$this->db->order_by('s.created_at', 'DESC');
		$this->db->where('s.deleted_at', NULL);
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));

			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}
		}

		return $data;
	}

	public function get_smfg_data()
	{
		$this->db->select('tp.*, 
			(SELECT SUM(sr.jumlah) FROM v_smfg sr WHERE sr.t_planning_uuid = tp.uuid) as total_berat', false);
		$this->db->from('t_planning tp');
		$this->db->where('EXISTS (SELECT 1 FROM v_smfg sr WHERE sr.t_planning_uuid = tp.uuid AND sr.deleted_at IS NULL)');
		$this->db->order_by('tp.tanggal', 'DESC');
		$data = $this->db->get()->result();



		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));

			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}
		}
		return $data;
	}

	public function get_sortasi_persen_by_plan($varian_uuid)
{
    $today = date('Y-m-d');
    $bulan = date('m');
    $tahun = date('Y');

    // Subquery untuk persen maksimal per t_planning_uuid
    $subquery = $this->db
        ->select('t_planning_uuid, MAX(persen) AS persen')
        ->from('v_sortasi')
        ->group_by('t_planning_uuid')
        ->get_compiled_select();

    $this->db->select('
        t.uuid,
        t.tanggal,
        t.varian,
        t.filkar,
        IFNULL(s.persen, 0) AS persen,
        (IFNULL(s.persen, 0) / t.filkar * 100) AS persen_data
    ');
    $this->db->from('t_planning t');
    $this->db->join("($subquery) s", 's.t_planning_uuid = t.uuid', 'left');
    $this->db->where('t.varian', $varian_uuid);
    $this->db->where('t.deleted_at', null);
    $this->db->where('MONTH(t.tanggal)', $bulan);
    $this->db->where('YEAR(t.tanggal)', $tahun);
    $this->db->where('DATE(t.tanggal) <', $today);
    $this->db->order_by('t.tanggal', 'ASC');

    $data = $this->db->get()->result();

    foreach ($data as $val) {
        $val->tgl = date('d M', strtotime($val->tanggal));
        // jika filkar 0, beri nilai 0 agar aman
        if ($val->filkar == 0) {
            $val->persen_data = 0;
        }
    }

    return $data;
}



	public function get_badpro()
	{
		return $this->db->get('v_badpro')->result();
	}

	public function get_performa_data_by_varian($varian_uuid)
	{
		$today = date('Y-m-d');
		$bulan = date('m');
		$tahun = date('Y'); // tahun sekarang
		$this->db->order_by('tanggal', 'ASC');
		$this->db->where('varian', $varian_uuid);
		$this->db->where('deleted_at', null);
		$this->db->where('DATE(tanggal)<', $today);
		$this->db->where('MONTH(tanggal)', $bulan);
		$this->db->where('YEAR(tanggal)', $tahun);
		$planning_data = $this->db->get('t_planning')->result();

		$chart_data = array();

		foreach ($planning_data as $planning) {
			$performa = $this->filler_model->get_counter_by_t_planning_uuid($planning->uuid);
			$formatted_date = date("d M", strtotime($planning->tanggal));

			$total_target = 0;
			$total_counters = 0;
			$total_losses = 0;
			$total_downtime = 0;
			$total_performa = 0;
			$count = count($performa);

			foreach ($performa as $row) {
				$total_target += $row->target;
				$total_counters += $row->counters;
				$total_losses += $row->total_losses;
				$total_downtime += $row->total_downtime;
				$total_performa += $row->performa;
			}

			$average_performa = $count > 0 ? $total_performa / $count : 0;
			$average_losses = $count > 0 ? $total_losses / $count : 0;
			$average_downtime = $count > 0 ? $total_downtime / $count : 0;

			$chart_data[] = array(
				'date' => $formatted_date,
				'varian' => $planning->varian,
				'rata_performa' => $average_performa
			);
		}

		return $chart_data;
	}

	public function get_cooking_by_varian($varian_uuid)
{
    $today = date('Y-m-d');
    $bulan = date('m');
    $tahun = date('Y');

    // Subquery: ambil rata-rata berat / formula (formula dari t_planning) * 100
    $subquery = $this->db
        ->select('rj.t_planning_uuid, AVG(rj.berat / tp.formula * 100) as total_berat')
        ->from('ch_rj_cooking rj')
        ->join('t_planning tp', 'rj.t_planning_uuid = tp.uuid')
        ->where('rj.deleted_at', null)
        ->group_by('rj.t_planning_uuid')
        ->get_compiled_select();

    // Query utama
    $this->db->select('tp.uuid, tp.varian, tp.tanggal, IFNULL(c.total_berat, 0) as total_berat');
    $this->db->from('t_planning tp');
    $this->db->join("($subquery) c", 'tp.uuid = c.t_planning_uuid', 'left');
    $this->db->where('tp.varian', $varian_uuid);
    $this->db->where('tp.deleted_at', null);
    $this->db->where('MONTH(tp.tanggal)', $bulan);
    $this->db->where('YEAR(tp.tanggal)', $tahun);
    $this->db->where('DATE(tp.tanggal) <', $today);
    $this->db->order_by('tp.tanggal', 'ASC');

    $data = $this->db->get()->result();

    foreach ($data as $val) {
        $val->tgl = date('d M', strtotime($val->tanggal));
    }

    return $data;
}


	public function get_cooking_mesin_by_varian($varian_uuid)
	{
		$subquery = $this->db
		->select('rf.mesin_uuid, SUM(rf.berat) as total_berat, tp.formula')
		->from('ch_rj_mesin rf')
		->join('t_planning tp', 'tp.uuid = rf.t_planning_uuid')
		->where('tp.varian', $varian_uuid)
		->where('MONTH(tp.tanggal)', date('m'))
		->where('YEAR(tp.tanggal)', date('Y'))
		->group_by(['rf.mesin_uuid', 'rf.t_planning_uuid', 'tp.formula'])
		->get_compiled_select();

		$query = $this->db
		->select('sub.mesin_uuid, FORMAT(AVG((sub.total_berat / sub.formula) * 100), 2) as rata_ckmesin')
		->from("($subquery) as sub")
		->group_by('sub.mesin_uuid')
		->order_by('sub.mesin_uuid', 'ASC')
		->get();

		return $query->result();
	}




	public function get_last_plan_uuid()
	{
		$this->db->select('uuid, tanggal, varian');
		$this->db->from('t_planning');
		$this->db->order_by('tanggal', 'DESC');
		$this->db->where('deleted_at', null);
		$this->db->limit(1);
		$data = $this->db->get()->row();

		return $data;
	}

	public function get_counter_data()
	{
		$subquery = $this->db->select('tc.device_id, SUM(tc.counter) as total_counter')
		->from('tcounter tc')
		->join('tbatch t', 't.uuid = tc.tbatch_uuid')
		->join('t_planning tp', 'tp.uuid = t.t_planning_uuid')
		->where('tp.deleted_at', NULL)
		->group_by('tc.device_id')
		->get_compiled_select();

		$this->db->select('s.t_sensor_device_id, p.varian, p.tanggal, p.start, p.end, p.clean, s.speed, p.uuid, COALESCE(tc.total_counter, 0) as counters, s.uuid as speed_uuid');
		$this->db->from('t_speed s');
		$this->db->join('t_planning p', 'p.uuid = s.t_planning_uuid');
		// $this->db->join($this->dberetort->database . '.mincing mn', 'mn.planprod_uuid = p.uuid', 'left');
		// $this->db->join($this->dberetort->database . '.varian v', 'v.uuid = p.varian_uuid', 'left');
		$this->db->join("($subquery) as tc", 'tc.device_id = s.t_sensor_device_id', 'left');
		$this->db->where('p.deleted_at', NULL);
		// $this->db->group_by('s.t_sensor_device_id, p.start, p.end, p.clean, s.speed, p.uuid');
		$this->db->group_by([
			's.t_sensor_device_id', 
			'p.varian', 
			'p.tanggal', 
			'p.start', 
			'p.end', 
			'p.clean', 
			's.speed', 
			'p.uuid', 
			's.uuid'
		]);
		$data = $this->db->get()->result();

		$mesinData = [];

		foreach ($data as $row) {
			$deviceId = $row->t_sensor_device_id;
			$detik = strtotime($row->end) - strtotime($row->start);
			$total_waktu = ($detik / 3600) - ($row->clean / 60);

			$target = ($row->speed > 0) ? ceil($row->speed * 50 * $total_waktu) : 0;
			$performa = ($target > 0) ? ($row->counters / $target * 100) : 0;

			if (!isset($mesinData[$deviceId])) {
				$mesinData[$deviceId] = [
					'total_target' => 0,
					'total_counter' => 0,
					'total_performa' => 0,
					'count' => 0
				];
			}

			$mesinData[$deviceId]['total_target'] += $target;
			$mesinData[$deviceId]['total_counter'] += $row->counters;
			$mesinData[$deviceId]['total_performa'] += $performa;
			$mesinData[$deviceId]['count']++;
		}

// Hitung rata-rata performa
		$rataRataMesin = [];

		foreach ($mesinData as $deviceId => $values) {
			$rataRataMesin[$deviceId] = [
				'total_target' => $values['total_target'],
				'total_counter' => $values['total_counter'],
				'avg_performa' => ($values['count'] > 0) ? ($values['total_performa'] / $values['count']) : 0,
				'count' => $values['count']
			];
		}

// Menampilkan hasil
// foreach ($rataRataMesin as $deviceId => $rata) {
//     echo "Mesin $deviceId - Total Target: {$rata['total_target']}, Total Counter: {$rata['total_counter']}, Rata-rata Performa: {$rata['avg_performa']}%, Count: {$rata['count']} <br>";
// }

		return $data;
	}



	public function get_performa_mesin()
	{
		$this->db->select('p.*, tp.varian, tp.tanggal, m.nama_mesin');
		$this->db->from('performa p');
		$this->db->join('t_planning tp', 'tp.uuid = p.t_planning_uuid', 'left');
		$this->db->join('mesin m', 'm.device_id = p.mesin_uuid', 'left');
		$this->db->where('p.deleted_at IS NULL');
		$this->db->order_by('p.created_at', 'DESC');
		$this->db->order_by('m.nama_mesin', 'ASC');
		$data = $this->db->get()->result();

		return $data;
	}

	public function get_reject_mesin()
	{
		$this->db->select('rm.*, rm.uuid as rc_uuid, tp.varian, tp.tanggal, m.nama_mesin, tp.uuid, m.device_id');
		$this->db->from('ch_rj_mesin rm');
		$this->db->join('t_planning tp', 'tp.uuid = rm.t_planning_uuid', 'left');
		$this->db->join('mesin m', 'm.device_id = rm.mesin_uuid', 'left');
		$this->db->where('rm.deleted_at IS NULL');
		$this->db->order_by('rm.created_at', 'DESC');
		$this->db->order_by('m.nama_mesin', 'ASC');
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));
		}

		return $data;
	}

	public function get_reject_cooking()
	{
		$this->db->select('rc.*, tp.varian, tp.tanggal');
		$this->db->from('ch_rj_cooking rc');
		$this->db->join('t_planning tp', 'tp.uuid = rc.t_planning_uuid', 'left');
		$this->db->where('rc.deleted_at IS NULL');
		$this->db->order_by('rc.created_at', 'DESC');

		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));
			if ($val->varian == 1) {
				$val->varian_name = 'OKEY';
			} else if ($val->varian == 2) {
				$val->varian_name = 'CHAMP AYAM';
			} else if ($val->varian == 3) {
				$val->varian_name = 'CHAMP SAPI';
			} elseif ($val->varian == 4) {
				$val->varian_name = 'CHAMP OTAK-OTAK';
			}
		}

		return $data;
	}

	public function getPlanningDataByVarian($varian_uuid)
	{
		$this->db->select('*');
		$this->db->from('t_planning');
		$this->db->where('varian', $varian_uuid);
		$this->db->where('deleted_at', null);
		$this->db->order_by('tanggal', 'DESC');
		$this->db->limit(20);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tanggal_produksi = date('d M Y', strtotime($val->tanggal));
		}

		return $data;
	}

	public function insert_performamesin() {
		$data = array();
		$performas = $this->input->post('performa');
		$device_ids = $this->input->post('mesin');
		$plan = $this->input->post('t_planning');

		for ($i = 0; $i < count($performas); $i++) {
			$uid = Uuid::uuid4()->toString();
			$data[] = array(
				'uuid' => $uid,
				't_planning_uuid' => $plan,
				'mesin_uuid' => $device_ids[$i],
				'performa' => $performas[$i]
			);
		}

		$this->db->insert_batch('performa', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_rj_mesin() {
		$data = array();
		$performas = $this->input->post('performa');
		$device_ids = $this->input->post('mesin');
		$plan = $this->input->post('t_planning');

		for ($i = 0; $i < count($performas); $i++) {
			$uid = Uuid::uuid4()->toString();
			$data[] = array(
				'uuid' => $uid,
				't_planning_uuid' => $plan,
				'mesin_uuid' => $device_ids[$i],
				'berat' => $performas[$i]
			);
		}

		$this->db->insert_batch('ch_rj_mesin', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_rj_cooking()
	{
		$uid = Uuid::uuid4()->toString();
		$berat = $this->input->post('berat');
		$plan = $this->input->post('t_planning');

		$data[] = array(
			'uuid' => $uid,
			't_planning_uuid' => $plan,
			'berat' => $berat
		);

		$this->db->insert_batch('ch_rj_cooking', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_sortasi()
	{
		$uid = Uuid::uuid4()->toString();
		$persen = $this->input->post('persen');
		$plan = $this->input->post('t_planning');

		$data = array(
			'uuid' => $uid,
			't_planning_uuid' => $plan,
			'persen' => $persen
		);

		$this->db->insert('v_sortasi', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_badpro()
	{
		$uid = Uuid::uuid4()->toString();
		$badpro = $this->input->post('badpro');

		$data = array(
			'uuid' => $uid,
			'badpro' => $badpro
		);

		$this->db->insert('v_badpro', $data);

		if ($this->db->affected_rows() > 0) {
        return $data; // ⬅️ Ini yang penting: return data lengkap
    }

    return false;
}


public function insert_sortasi_badpro()
{
	$badpro = $this->input->post('badpro_uuid');
	$t_planning_uuid = $this->input->post('t_planning');
	$jumlah = $this->input->post('jumlah');		

	if (!is_array($badpro) || !is_array($jumlah) || count($badpro) !== count($jumlah)) {
		return false;
	}

	$insert_data = [];

	for ($i = 0; $i < count($badpro); $i++) {
		if (!empty($badpro[$i]) && is_numeric($jumlah[$i])) {
			$insert_data[] = [
				'uuid' => Uuid::uuid4()->toString(),
				'badpro_uuid' => $badpro[$i],
				'jumlah' => $jumlah[$i],
				't_planning_uuid' => $t_planning_uuid
			];
		}
	}

	if (!empty($insert_data)) {
		return $this->db->insert_batch('v_sr_badpro', $insert_data);
	}

	return false;
}


public function insert_smfg()
{
	$badpro = $this->input->post('badpro_uuid');
	$t_planning_uuid = $this->input->post('t_planning');
	$jumlah = $this->input->post('jumlah');		

	if (!is_array($badpro) || !is_array($jumlah) || count($badpro) !== count($jumlah)) {
		return false;
	}

	$insert_data = [];

	for ($i = 0; $i < count($badpro); $i++) {
		if (!empty($badpro[$i]) && is_numeric($jumlah[$i])) {
			$insert_data[] = [
				'uuid' => Uuid::uuid4()->toString(),
				'badpro_uuid' => $badpro[$i],
				'jumlah' => $jumlah[$i],
				't_planning_uuid' => $t_planning_uuid
			];
		}
	}

	if (!empty($insert_data)) {
		return $this->db->insert_batch('v_smfg', $insert_data);
	}

	return false;
}

public function get_detail_smfg($uuid)
{
	$this->db->select('sb.*, b.badpro');
	$this->db->from('v_smfg sb');
	$this->db->join('v_badpro b', 'sb.badpro_uuid = b.uuid', 'left');
	$this->db->where('sb.t_planning_uuid', $uuid);
	$this->db->where('sb.deleted_at', NULL);
	$this->db->order_by('b.badpro', 'ASC');
	$data = $this->db->get()->result();

	return $data;
}

public function get_performa_by_varian($varian_uuid) {
	$this->db->select('p.mesin_uuid, AVG(p.performa) as rata_performa, t.varian');
	$this->db->from('performa p');
	$this->db->join('t_planning t', 'p.t_planning_uuid = t.uuid', 'left');
	$this->db->where('t.varian', $varian_uuid);
	$this->db->where('MONTH(t.tanggal)', date('m'));
	$this->db->where('YEAR(t.tanggal)', date('Y'));
	$this->db->group_by('p.mesin_uuid, t.varian');
	$data = $this->db->get()->result();

	return $data;
}

public function get_total_mesin_cooking()
{
	$this->db->select('rf.mesin_uuid, FORMAT(SUM(berat), 2) as total_berat, m.nama_mesin');
	$this->db->from('ch_rj_mesin rf');
	$this->db->join('mesin m', 'm.device_id = rf.mesin_uuid', 'left');
	$this->db->where('rf.deleted_at IS NULL');
	$this->db->group_by('m.nama_mesin, rf.mesin_uuid');

	$data = $this->db->get()->result();
	return $data;
}


public function get_mesin_filler()
    {
        $filler = $this->filler;
        $this->db->select('m.uuid, m.nama_mesin, m.device_id');
        $this->db->from('mesin m');
        $this->db->where('m.area_uuid', $filler);

    // Kondisi LIKE untuk nama_mesin: zap%, kap%, cap%
    $this->db->group_start(); // buka grup kondisi OR
    $this->db->like('m.nama_mesin', 'zap', 'after');
    $this->db->or_like('m.nama_mesin', 'kap', 'after');
    $this->db->or_like('m.nama_mesin', 'cap', 'after');
    $this->db->group_end(); // tutup grup kondisi OR
    $this->db->order_by('m.nama_mesin', 'ASC');
    $data = $this->db->get()->result();

    return $data;
}

public function getByUuid($uuid)
{
	return $this->db->get_where('performa', ['uuid' => $uuid])->row();
}

public function get_data_cooking($uuid)
{
	return $this->db->get_where('ch_rj_cooking', ['uuid' => $uuid])->row();
}

public function get_cooking_per_mesin($uuid)
{
	return $this->db->get_where('ch_rj_mesin', ['uuid' => $uuid])->row();
}

public function get_data_sortasi($uuid)
{
	return $this->db->get_where('v_sortasi', ['uuid' => $uuid])->row();
}

public function update_performa($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$performa 		= $this->input->post('performa');
	$mesin          = $this->input->post('mesin');

	$data = array(
		'performa'            => $performa,
		'mesin_uuid' 		=> $mesin,
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('performa', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_formula($uuid)
{
	
	$formula 		= $this->input->post('formula');
	$filkar          = $this->input->post('filkar');

	$data = array(
		'formula'            => $formula,
		'filkar' 		=> $filkar
	);

	$this->db->update('t_planning', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_rjcooking($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 		= $this->input->post('berat');
		// $mesin          = $this->input->post('mesin');

	$data = array(
		'berat'            => $berat,
			// 'mesin_uuid' 		=> $mesin,
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('ch_rj_cooking', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_rcmesin($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 			= $this->input->post('berat');
	

	$data = array(
		'berat'            => $berat,
		'modified_at' => date('Y-m-d H:i:s')
	);	
	$this->db->update('ch_rj_mesin', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_srbadpro($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 		= $this->input->post('jumlah');
	$badpro          = $this->input->post('badpro');

	$data = array(
		'jumlah'            => $berat,
		'badpro_uuid' 		=> $badpro,
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('v_sr_badpro', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_sortasi($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 		= $this->input->post('berat');
	

	$data = array(
		'persen'            => $berat,
		
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('v_sortasi', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function update_smfg($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 		= $this->input->post('jumlah');
	$badpro          = $this->input->post('badpro');

	$data = array(
		'jumlah'            => $berat,
		'badpro_uuid' 		=> $badpro,
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('v_smfg', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_performa($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('performa', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_rjcooking($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('ch_rj_cooking', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_rcmesin($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d H:i:s')
	);

	$this->db->update('ch_rj_mesin', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function get_detail_srbp($uuid)
{
	$this->db->select('sb.*, b.badpro');
	$this->db->from('v_sr_badpro sb');
	$this->db->join('v_badpro b', 'sb.badpro_uuid = b.uuid', 'left');
	$this->db->where('sb.t_planning_uuid', $uuid);
	$this->db->where('sb.deleted_at', NULL);
	$this->db->order_by('b.badpro', 'ASC');
	$data = $this->db->get()->result();

	return $data;
}

public function delete_srbadpro($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('v_sr_badpro', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_smfg($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('v_smfg', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function get_rata_smfg()
{
	$this->db->select('sb.badpro_uuid, FORMAT(AVG(sb.jumlah), 2) as rata_jumlah, bp.badpro');
	$this->db->from('v_smfg sb');
	$this->db->join('v_badpro bp', 'bp.uuid = sb.badpro_uuid', 'left');
	$this->db->join('t_planning tp', 'tp.uuid = sb.t_planning_uuid', 'left');
	$this->db->where('sb.deleted_at IS NULL');
    // $this->db->where('tp.varian', $varian_uuid);
	$this->db->where('MONTH(tp.tanggal)', date('m'));
	$this->db->where('YEAR(tp.tanggal)', date('Y'));
	$this->db->group_by('sb.badpro_uuid, bp.badpro');
	$this->db->order_by('bp.badpro', 'ASC');

	$data = $this->db->get()->result();
	return $data;
}

public function get_rata_smfg_perplan($t_planning_uuid)
{
	$this->db->select('sb.badpro_uuid, FORMAT(AVG(sb.jumlah), 2) as rata_jumlah, bp.badpro');
	$this->db->from('v_smfg sb');
	$this->db->join('v_badpro bp', 'bp.uuid = sb.badpro_uuid', 'left');
	$this->db->join('t_planning tp', 'tp.uuid = sb.t_planning_uuid', 'left');
	$this->db->where('sb.deleted_at IS NULL');
	$this->db->where('sb.t_planning_uuid', $t_planning_uuid);
	$this->db->where('MONTH(tp.tanggal)', date('m'));
	$this->db->where('YEAR(tp.tanggal)', date('Y'));
	$this->db->group_by('sb.badpro_uuid, bp.badpro');
	$this->db->order_by('bp.badpro', 'ASC');

	$data = $this->db->get()->result();
	return $data;
}

public function get_smfgmsn_data()
{
	$this->db->select('tp.*, 
		(SELECT SUM(sr.berat) FROM v_smfgmsn sr WHERE sr.t_planning_uuid = tp.uuid) as total_berat', false);
	$this->db->from('t_planning tp');
	$this->db->where('EXISTS (SELECT 1 FROM v_smfgmsn sr WHERE sr.t_planning_uuid = tp.uuid AND sr.deleted_at IS NULL)');
	$this->db->order_by('tp.tanggal', 'DESC');
	$data = $this->db->get()->result();



	foreach ($data as $val) {
		$val->tgl = date('d M Y', strtotime($val->tanggal));

		if ($val->varian == 1) {
			$val->varian_name = 'OKEY';
		} else if ($val->varian == 2) {
			$val->varian_name = 'CHAMP AYAM';
		} else if ($val->varian == 3) {
			$val->varian_name = 'CHAMP SAPI';
		} elseif ($val->varian == 4) {
			$val->varian_name = 'CHAMP OTAK-OTAK';
		}
	}
	return $data;
}

public function insert_smfgmsn()
{
	$badpro = $this->input->post('badpro_uuid');
	$mesin = $this->input->post('mesin_uuid');
	$t_planning_uuid = $this->input->post('t_planning');
	$jumlah = $this->input->post('jumlah');		

	if (!is_array($badpro) || !is_array($jumlah) || count($badpro) !== count($jumlah)) {
		return false;
	}

	$insert_data = [];

	for ($i = 0; $i < count($badpro); $i++) {
		if (!empty($badpro[$i]) && is_numeric($jumlah[$i])) {
			$insert_data[] = [
				'uuid' => Uuid::uuid4()->toString(),
				'badpro_uuid' => $badpro[$i],
				'berat' => $jumlah[$i],
				'mesin_uuid' => $mesin,
				't_planning_uuid' => $t_planning_uuid
			];
		}
	}

	if (!empty($insert_data)) {
		return $this->db->insert_batch('v_smfgmsn', $insert_data);
	}

	return false;
}

public function get_detail_smfgmsn($uuid)
{
	$this->db->select('sb.*, b.badpro, m.nama_mesin');
	$this->db->from('v_smfgmsn sb');
	$this->db->join('v_badpro b', 'sb.badpro_uuid = b.uuid', 'left');
	$this->db->join('mesin m', 'sb.mesin_uuid = m.device_id', 'left');
	$this->db->where('sb.t_planning_uuid', $uuid);
	$this->db->where('sb.deleted_at', NULL);
	$this->db->order_by('b.badpro', 'ASC');
	$data = $this->db->get()->result();

	return $data;
}

public function update_smfgmsn($uuid)
{
	$uuid 			= $this->input->post('uuid');
	$berat 		= $this->input->post('jumlah');
	$badpro          = $this->input->post('badpro');

	$data = array(
		'berat'            => $berat,
		'badpro_uuid' 		=> $badpro,
		'modified_at' => date('Y-m-d h:i:s')
	);	
	$this->db->update('v_smfgmsn', $data, array('uuid' => $uuid));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_smfgmsn($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('v_smfgmsn', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function delete_sortasi($uuid)
{
	$data = array(
		'deleted_at' => date('Y-m-d h:i:s')
	);

	$this->db->update('v_sortasi', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function getChartConfig()
{
	$this->db->select('sc.*, bp.badpro as title');
	$this->db->from('v_smfgchart sc');
	$this->db->join('v_badpro bp', 'sc.badpro_uuid = bp.uuid', 'left');

	$data = $this->db->get()->result();


	return $data;
}

public function getDistinctUUID()
{
	return $this->db->distinct()
        ->select('v_smfgmsn.badpro_uuid, v_badpro.badpro') // pastikan tabel ditentukan untuk menghindari ambiguitas
        ->from('v_smfgmsn')
        ->join('v_badpro', 'v_badpro.uuid = v_smfgmsn.badpro_uuid')
        ->get()
        ->result();
    }

    public function getChartDataByUUID($badpro_uuid)
    {
    	$this->db->select('m.device_id, AVG(sm.berat) AS rata_jumlah');
    	$this->db->from('mesin m');
    	$this->db->join('v_smfgmsn sm', 'sm.mesin_uuid = m.uuid AND sm.badpro_uuid = '.$this->db->escape($badpro_uuid), 'left');
    	$this->db->join('t_planning tp', 'tp.uuid = sm.t_planning_uuid', 'left');
	// $this->db->where('m.area_uuid', 'filler_uuid'); // ganti dengan UUID filler yang sebenarnya
    	$this->db->where('m.device_id IS NOT NULL');
    	$this->db->where('sm.deleted_at IS NULL');
    	$this->db->where('MONTH(tp.tanggal)', date('m'));
    	$this->db->where('YEAR(tp.tanggal)', date('Y'));
    	$this->db->group_by('m.device_id');
    	$this->db->order_by('m.device_id', 'ASC');
    	$query = $this->db->get()->result();

    	$labels = [];
    	$data = [];

    	foreach ($query as $row) {
    		$labels[] = $row->device_id;
		$data[] = round($row->rata_jumlah ?? 0, 2); // jika null, beri 0
	}

	return ['labels' => $labels, 'data' => $data];
}


public function getChartDataByUUID_perplan($plan_uuid, $badpro_uuid)
{
	$this->db->select('sm.mesin_uuid, m.device_id, AVG(sm.berat) AS rata_jumlah');
	$this->db->from('v_smfgmsn sm');
	$this->db->join('mesin m', 'm.uuid = sm.mesin_uuid', 'left');
	$this->db->where('sm.t_planning_uuid', $plan_uuid);
	$this->db->where('sm.badpro_uuid', $badpro_uuid);
	$this->db->where('sm.deleted_at IS NULL');
	$this->db->group_by('sm.mesin_uuid, m.device_id');
	$this->db->order_by('m.device_id', 'ASC');
	$query = $this->db->get()->result();

	$labels = [];
	$data = [];

	foreach ($query as $row) {
		$labels[] = $row->device_id;
		$data[] = round($row->rata_jumlah, 2);
	}

	return ['labels' => $labels, 'data' => $data];
}


public function updateChartConfig($chart_id, $uuid)
{
	$this->db->where('chart_id', $chart_id)
	->update('v_smfgchart', ['badpro_uuid' => $uuid]);
}

public function get_plan_data_by_uuid($uuid)
{
	$this->db->select('*');
	$this->db->from('t_planning');
	$this->db->where('uuid', $uuid);
	$data = $this->db->get()->row_array();

	return $data;
}

}