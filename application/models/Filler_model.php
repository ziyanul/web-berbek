<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Filler_model extends CI_Model
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
				'field' => 'f_varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'f_start',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'f_end',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'f_clean',
				'label' => 'Cleanning',
				'rules' => 'required'
			]
		];
	}
	public function rulesspeed()
	{
		return [
			[
				'field' => 'okey',
				'label' => 'Speed Okey',
				'rules' => 'required'
			],
			[
				'field' => 'champ',
				'label' => 'Speed Champ',
				'rules' => 'required'
			]
		];
	}
	public function update_plan($uuid)
	{
		$varian = $this->input->post('f_varian');
		$tanggal = $this->input->post('tanggal');
		$start = $this->input->post('f_start');
		$end = $this->input->post('f_end');
		$clean = $this->input->post('f_clean');
		$data = array(
			'varian'      => $varian,
			'tanggal'     => $tanggal,
			'clean'       => $clean,
			'start'       => $start,
			'end'         => $end,
			'modified_at' => date('Y-m-d H:i:s')
		);
		$this->db->where('uuid', $uuid);
		$this->db->update('t_planning', $data);
		return ($this->db->affected_rows() > 0);
	}
	public function save_plan_with_speed()
	{
		$this->db->trans_start();
		$planning_uuid = Uuid::uuid4()->toString();
		$varian_uuid   = $this->input->post('f_varian');
		$start         = $this->input->post('f_start');
		$end           = $this->input->post('f_end');
		$clean         = $this->input->post('f_clean');
		$tanggal       = $this->input->post('tanggal');
		// =========================
		// 1. INSERT T_PLANNING
		// =========================
		$data_planning = [
			'uuid'       => $planning_uuid,
			'user_uuid'  => $this->auth_model->current_user()->uuid,
			'username'   => $this->auth_model->current_user()->username,
			'tanggal'    => $tanggal,
			'varian'     => $varian_uuid,
			'clean'      => $clean,
			'start'      => $start,
			'end'        => $end,
			'created_at' => date('Y-m-d H:i:s')
		];
		$this->db->insert('t_planning', $data_planning);
		// =========================
		// 2. AMBIL MASTER SPEED SESUAI VARIAN
		// =========================
		$this->db->select('ms.*, m.nama_mesin');
		$this->db->from('master_speed ms');
		$this->db->join('mesin m', 'm.uuid = ms.mesin_uuid', 'left');
		$this->db->where('ms.varian_uuid', $varian_uuid);
		$this->db->where('m.nama_area', 'FILLER');
		$this->db->order_by('m.nama_mesin', 'ASC');
		$master_speed = $this->db->get()->result();
		// =========================
		// 3. INSERT SNAPSHOT KE T_SPEED
		// =========================
		$insert_speed = [];
		foreach ($master_speed as $row) {
			$insert_speed[] = [
				'uuid'              => Uuid::uuid4()->toString(),
				't_planning_uuid'   => $planning_uuid,
				'master_speed_uuid' => $row->uuid,
				'mesin_uuid'        => $row->mesin_uuid,
				'varian_uuid'       => $row->varian_uuid,
				'speed'             => $row->speed,
				'quality'           => 0,
				'keterangan'        => null,
				'created_at'        => date('Y-m-d H:i:s')
			];
		}
		if (!empty($insert_speed)) {
			$this->db->insert_batch('t_speed', $insert_speed);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('t_filler', array('uuid' => $uuid))->row();
	}
	public function get_plan_uuid($uuid)
	{
		$this->db->select('p.*, v.varian as nama_varian, v.keterangan');
		$this->db->from('t_planning p');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->where('p.uuid', $uuid);
		$data = $this->db->get()->row();
		if (!$data) {
			return null;
		}
		$tgl = strtotime($data->tanggal);
		$data->date = date("d M Y", $tgl); //tanggal
		$awal = strtotime($data->start);
		$data->start_time = date("H:i", $awal); // jam start
		$akhir = strtotime($data->end);
		$data->end_time = date("H:i", $akhir); // jam akhiri
		$clean = $data->clean / 60; //cleaning schedule
		$diff_seconds = $akhir - $awal;
		$diff_jam = $diff_seconds / 3600;
		$data->total_waktu = $diff_jam - $clean; //total waktu
		$this->db->select_sum('speed');
		$this->db->from('t_speed');
		$this->db->where('t_planning_uuid', $uuid);
		$query = $this->db->get();
		$result = $query->row();
		$data->total = $result->speed;
		$data->total *= $data->total_waktu;
		$data->total *= 50;
		return $data;
	}
	public function get_plan_data()
	{
		$this->db->select('tp.*, v.varian, v.keterangan');
		$this->db->from('t_planning tp');
		$this->db->join('varian v', 'v.uuid = tp.varian', 'left');
		$this->db->order_by('tp.created_at', 'DESC');
		$this->db->where('tp.deleted_at IS NULL', null, false);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));
		}
		return $data;
	}
	public function getPlanningDataByVarian($varian)
	{
		$query = $this->db->where('varian', $varian)
			->get('t_planning');
		return $query->result();
	}
	public function get_mesin_name($mesin_uuid)
	{
		$this->db->select('nama_mesin');
		$this->db->from('mesin');
		$this->db->where('uuid', $mesin_uuid);
		$row = $this->db->get()->row();
		return $row ? $row->nama_mesin : '-';
	}
	public function get_mesin_by_plan($uuid)
	{
		return $this->db
			->select('m.uuid,m.nama_mesin')
			->from('t_speed s')
			->join('mesin m', 'm.uuid=s.mesin_uuid')
			->where('s.t_planning_uuid', $uuid)
			->where('s.speed >', 0)
			->order_by('m.nama_mesin', 'ASC')
			->get()
			->result();
	}
	public function get_batch_by_plan($uuid)
	{
		return $this->db
			->select('uuid,kode_batch,batch_ke')
			->from('tbatch')
			->where('t_planning_uuid', $uuid)
			->order_by('batch_ke', 'ASC')
			->get()
			->result();
	}
	public function get_speed_data($planning_uuid, $mesin_uuid)
	{
		$this->db->select('
			s.t_planning_uuid,
			s.mesin_uuid,
			p.varian,
			p.tanggal,
			s.keterangan,
			s.quality,
			s.uuid,
			v.varian as vrn,
			m.nama_mesin
			');
		$this->db->from('t_speed s');
		$this->db->join('t_planning p', 'p.uuid = s.t_planning_uuid');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->join('mesin m', 'm.uuid = s.mesin_uuid', 'left');
		$this->db->where('s.t_planning_uuid', $planning_uuid);
		$this->db->where('s.mesin_uuid', $mesin_uuid);
		$data = $this->db->get()->row();
		if ($data) {
			$data->tgl = date('d M Y', strtotime($data->tanggal));
			$data->mesin = $data->nama_mesin;
		}
		return $data;
	}
	public function update_quality($planning_uuid, $mesin_uuid)
	{
		$jumlah = $this->input->post('jumlah');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'keterangan' => $keterangan,
			'quality' => $jumlah,
			'modified_at' => date('Y-m-d H:i:s')
		);
		$this->db->where('t_planning_uuid', $planning_uuid);
		$this->db->where('mesin_uuid', $mesin_uuid);
		$this->db->update('t_speed', $data);
		return ($this->db->affected_rows() > 0);
	}
	public function insert_downtime($uuid)
	{
		$speed = $this->db->get_where('t_speed', array('uuid' => $uuid))->row();
		$uuid = Uuid::uuid4()->toString();
		$waktu = $this->input->post('jumlah');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'uuid' => $uuid,
			't_speed_uuid' => $speed->uuid,
			'keterangan' => $keterangan,
			'downtime' => $waktu
		);
		$this->db->insert('t_downtime', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	public function get_last_plan_uuid()
	{
		$this->db->select('uuid, tanggal, varian');
		$this->db->from('t_planning');
		$this->db->order_by('tanggal', 'DESC');
		$this->db->limit(1);
		$result = $this->db->get()->row();
		if ($result) {
			$result->tanggal = date("d M Y", strtotime($result->tanggal));
			$result->varian = ($result->varian == 1) ? 'Okey' : (($result->varian == 2) ? 'Champ Ayam' : (($result->varian == 3) ? 'Champ Sapi' : (($result->varian == 4) ? 'Champ Otak-Otak' : '')));
			return $result->uuid;
		} else {
			return null; // Mengembalikan null jika tidak ada data
		}
	}
	public function get_all_uuids()
	{
		$this->db->select('p.uuid, p.tanggal, v.varian');
		$this->db->from('t_planning p');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->where('p.deleted_at IS NULL', null, false);
		$this->db->order_by('p.tanggal', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tanggal = date("d/m/y", strtotime($val->tanggal));
		}
		return $data;
	}
	public function get_counter_by_t_planning_uuid($uuid, $mesin_uuid = null, $batch_uuid = null)
	{
		$sub_counter = $this->db
			->select('
		tc.mesin_uuid,
		SUM(tc.counter) as total_counter
		')
			->from('tcounter tc')
			->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid')
			->where('tb.t_planning_uuid', $uuid);
		if ($batch_uuid) {
			$this->db->where('tb.uuid', $batch_uuid);
		}
		$sub_counter = $this->db
			->group_by('tc.mesin_uuid')
			->get_compiled_select();
		$sub_downtime = $this->db->select('t_speed_uuid, SUM(downtime) as total_downtime')
			->from('t_downtime')
			->group_by('t_speed_uuid')
			->get_compiled_select();
		$this->db->select('
		s.mesin_uuid,
		m.nama_mesin,
		v.varian,
		p.tanggal,
		p.start,
		p.end,
		p.clean,
		p.plan,
		p.formula,
		p.filkar,
		s.speed,
		p.uuid,
		COALESCE(tc.total_counter, 0) as counters,
		s.uuid as speed_uuid,
		COALESCE(s.quality, 0) as quality,
		COALESCE(td.total_downtime, 0) as total_downtime
		', false);
		$this->db->from('t_speed s');
		$this->db->join('t_planning p', 'p.uuid = s.t_planning_uuid');
		$this->db->join('varian v', 'v.uuid = p.varian', 'left');
		$this->db->join('mesin m', 'm.uuid = s.mesin_uuid', 'left');
		$this->db->join("($sub_counter) tc", 'tc.mesin_uuid = s.mesin_uuid', 'left');
		$this->db->join("($sub_downtime) td", 'td.t_speed_uuid = s.uuid', 'left');
		$this->db->where('p.uuid', $uuid);
		$this->db->where('s.speed >', 0);
		if (!empty($mesin_uuid)) {
			$this->db->where('s.mesin_uuid', $mesin_uuid);
		}
		$this->db->order_by('m.nama_mesin', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			/*
|--------------------------------------------------------------------------
| FILTER BATCH
|--------------------------------------------------------------------------
*/
			if ($batch_uuid) {
				$batch = $this->db
					->where('uuid', $batch_uuid)
					->get('tbatch')
					->row();
				$prev_batch = $this->db
					->select('created_at')
					->from('tbatch')
					->where('t_planning_uuid', $uuid)
					->where('created_at <', $batch->created_at)
					->order_by('created_at', 'DESC')
					->limit(1)
					->get()
					->row();
				if ($prev_batch) {
					$start_batch = strtotime($prev_batch->created_at);
				} else {
					// batch pertama mulai dari start planning
					$start_batch = strtotime($val->start);
				}
				$end_batch = strtotime($batch->created_at);
				$detik = $end_batch - $start_batch;
			} else {
				$detik = strtotime($val->end) - strtotime($val->start);
			}
			$val->total_waktu = ($detik / 3600);
			if ($val->total_waktu < 0) {
				$val->total_waktu = 0;
			}
			$val->target = $val->speed * 50 * $val->total_waktu;
			$val->performa = ($val->target > 0) ? ($val->counters / $val->target * 100) : 0;
			$val->running = ($val->speed > 0) ? ($val->counters / $val->speed / 60) : 0;
			$adjustment = $val->total_waktu * 10 / 60;
			$val->total_losses = (($val->total_waktu - $val->running - $adjustment) * 60) - $val->total_downtime;
			if ($val->total_losses < 0) {
				$val->total_losses = 0;
			}
			$val->quality_persen = ($val->counters > 0) ? ($val->quality / $val->counters * 100) : 0;
			$val->downtime_persen = ($val->total_waktu > 0) ? (($val->total_downtime / 60) / $val->total_waktu * 100) : 0;
			$val->vrn = $val->varian;
		}
		return $data;
	}
	public function get_t_speed($uuid)
	{
		$this->db->select('s.*, m.nama_mesin');
		$this->db->from('t_speed s');
		$this->db->join('mesin m', 'm.uuid = s.mesin_uuid', 'left');
		$this->db->where('s.uuid', $uuid);
		$data = $this->db->get()->row();
		if ($data) {
			$data->mesin = $data->nama_mesin;
		}
		return $data;
	}
	public function delete_plan($uuid)
	{
		$data = array(
			'deleted_at' => date('Y-m-d h:i:s')
		);
		$this->db->where('uuid', $uuid);
		$this->db->update('t_planning', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	public function get_keterangan_downtime($uuid)
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get_where('t_downtime', array('t_speed_uuid' => $uuid))->result();
		return $data;
	}
	public function delete_downtime($uuid)
	{
		$this->db->where('uuid', $uuid);
		$this->db->delete('t_downtime');
	}
	public function get_performa_batch($batch_uuid)
	{
		$batch = $this->db
			->select('
		tb.*,
		tp.start as plan_start,
		tp.end as plan_end,
		tp.clean,
		ts.speed,
		ts.mesin_uuid,
		m.nama_mesin
		')
			->from('tbatch tb')
			->join('t_planning tp', 'tp.uuid=tb.t_planning_uuid')
			->join('t_speed ts', 'ts.t_planning_uuid=tp.uuid')
			->join('mesin m', 'm.uuid=ts.mesin_uuid')
			->where('tb.uuid', $batch_uuid)
			->where('ts.speed >', 0)
			->get()
			->result();
		foreach ($batch as $row) {
			$prev_batch = $this->db
				->select('created_at')
				->from('tbatch')
				->where('t_planning_uuid', $row->t_planning_uuid)
				->where('created_at <', $row->created_at)
				->order_by('created_at', 'DESC')
				->limit(1)
				->get()
				->row();
			/*
        =====================================
        START BATCH
        =====================================
        */
			if ($prev_batch) {
				$start_batch = $prev_batch->created_at;
			} else {
				$start_batch = date(
					'Y-m-d H:i:s',
					strtotime(
						$row->tanggal_produksi . ' ' . $row->plan_start
					)
				);
			}
			/*
        =====================================
        END BATCH
        =====================================
        */
			$end_batch = $row->created_at;
			/*
        =====================================
        DURASI
        =====================================
        */
			$durasi_jam =
				(strtotime($end_batch) - strtotime($start_batch))
				/ 3600;
			if ($durasi_jam < 0) {
				$durasi_jam = 0;
			}
			/*
        =====================================
        COUNTER
        =====================================
        */
			$counter = $this->db
				->select_sum('counter')
				->where('tbatch_uuid', $row->uuid)
				->where('mesin_uuid', $row->mesin_uuid)
				->get('tcounter')
				->row();
			$actual = (int)$counter->counter;
			/*
        =====================================
        TARGET
        =====================================
        */
			$target = $row->speed * 50 * $durasi_jam;
			/*
        =====================================
        PERFORMA
        =====================================
        */
			$performa = 0;
			if ($target > 0) {
				$performa =
					($actual / $target) * 100;
			}
			$row->start_batch = $start_batch;
			$row->end_batch   = $end_batch;
			$row->durasi_jam  = $durasi_jam;
			$row->actual      = $actual;
			$row->target      = round($target);
			$row->performa    = round($performa, 2);
		}
		return $batch;
	}
}
