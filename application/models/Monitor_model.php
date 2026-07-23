<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Monitor_model extends CI_Model 
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
				'field' => 'part',
				'label' => 'Part',
				'rules' => 'required'
			],
			[
				'field' => 'lifetime_name',
				'label' => 'lifetime_name',
				'rules' => 'required'
			],
			[
				'field' => 'harga_name',
				'label' => 'harga_name',
				'rules' => 'required'
			],
			

		];
	}

	public function rules1()
	{
		return [
			
			[
				'field' => 'part',
				'label' => 'Part',
				'rules' => 'required'
			]
		];
	}

	public function calculate_rh($monitor)
	{
    // Jika sudah history → jangan hitung ulang
		if ($monitor->status == 2) {
			return $monitor->final_rh;
		}

		$installed_at = $monitor->installed_at;


    // JADWAL 0 → berdasarkan hari
		if ($monitor->jadwal == 0) {
			$start = new DateTime($installed_at);
			$now   = new DateTime();
			return $start->diff($now)->days;
		}

    // JADWAL 1 → berdasarkan plan produksi
		if ($monitor->jadwal == 1) {
			$this->db->select('SUM(TIMESTAMPDIFF(HOUR, start, LEAST(end, NOW()))) as total');
			$this->db->from('t_planning');
			$this->db->where('start >=', $installed_at);
			$result = $this->db->get()->row();
			return $result->total ?? 0;
		}

    // JADWAL 2 → berdasarkan counter
		if ($monitor->jadwal == 2) {
			$this->db->select('SUM(tc.counter/tc.speed/60) as total');
			$this->db->from('tcounter tc');
			$this->db->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid');
			$this->db->where('tc.created_at >=', $installed_at);
			$this->db->where('tb.deleted_at IS NULL', null, false);
			$result = $this->db->get()->row();
			return $result->total ?? 0;
		}

		return 0;
	}

	public function get_active()
	{
		$this->db->select('
			a.uuid,
			a.created_at,
			a.installed_at,
			a.mesin_uuid,
			a.nama_mesin,
			a.nama_part,
			a.lifetime,
			a.jadwal,
			a.status,
			a.final_rh,
			m.nama_area
			');
		$this->db->from('monitor a');
		$this->db->join('mesin m', 'm.uuid = a.mesin_uuid', 'left');
		$this->db->where('a.status', 1);
		$this->db->order_by('a.installed_at', 'DESC');

		return $this->db->get()->result();
	}

	public function get_planning_rows()
	{
		$this->db->select('start, end');
		$this->db->from('t_planning');
		$this->db->where('deleted_at IS NULL', null, false);
		$this->db->where('start IS NOT NULL', null, false);
		$this->db->where('end IS NOT NULL', null, false);
		$this->db->order_by('start', 'ASC');

		return $this->db->get()->result();
	}

	public function get_counter_rows()
	{
		$this->db->select('tc.mesin_uuid, tc.created_at, tc.counter, tc.speed');
		$this->db->from('tcounter tc');
		$this->db->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid', 'left');
		$this->db->where('tb.deleted_at IS NULL', null, false);

		return $this->db->get()->result();
	}

	public function get_history()
	{
		$this->db->select('a.*, m.nama_area');
		$this->db->from('monitor a');
		$this->db->join('mesin m', 'm.uuid = a.mesin_uuid', 'left');
		$this->db->where('a.status', 2);
		$this->db->order_by('a.removed_at', 'DESC');
		return $this->db->get()->result();
	}

	public function get_tpm()
	{

		$data = $this->db->where_in('status', [0, 3])->where('deleted_at', NULL)->order_by('created_at', 'DESC')->get('monitor')->result();
		foreach ($data as $val) {
			$val->tanggal = date('d M Y',strtotime($val->created_at));
			$val->rh_end = 0;
			$val->kondisi = '-';
		}

		return $data;
	}


	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$mesin = $this->input->post('mesin');
		$mesin_name = $this->input->post('mesin_name');
		$part = $this->input->post('part');
		$jadwal = $this->input->post('jadwal');
		$part_name = $this->input->post('part_name');
		$lifetime_name = $this->input->post('lifetime_name');
		$harga_name = $this->input->post('harga_name');

		$data = array(
			'uuid' => $uuid,
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'username' => $this->auth_model->current_user()->username,
			'mesin_uuid' => $mesin,
			'nama_mesin' => $mesin_name,
			'part_uuid' => $part,
			'nama_part' => $part_name,
			'lifetime' => $lifetime_name,
			'jadwal' => $jadwal,
			'harga' => $harga_name,
			'installed_at'=> date('Y-m-d H:i:s'),
			'status'   => 0,
			'created_at' 	=> date('Y-m-d H:i:s')
		);



		$this->db->insert('monitor', $data);
		$affected_rows = $this->db->affected_rows();

		return ($affected_rows > 0) ? true : false;
	}

	public function tindakan($uuid)
	{
		
		$pelaksana 	= $this->input->post('pelaksana');
		$catatan 	= $this->input->post('catatan');
		
		$data = array(
			
			'nama_pelaksana' 	=> $pelaksana,
			'catatan' 			=> $catatan,
			'modified_at' => date('Y-m-d h:i:s')
		);	

		$this->db->update('monitor', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	public function ubah_data($uuid)
	{
		
		$jadwal 	= $this->input->post('jadwal');
		
		
		
		$data = array(
			
			
			'jadwal' 			=> $jadwal,
			'modified_at' => date('Y-m-d h:i:s')
		);	

		$this->db->update('monitor', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function status($monitor_uuid)
	{
		$uuid 		= Uuid::uuid4()->toString();
		
		$status = $this->input->post('status');

		$data = array(
			'uuid' => $uuid,
			'user_uuid'         => $this->auth_model->current_user()->uuid,
			'username'          => $this->auth_model->current_user()->username,
			'status' => $status,
			'monitor_uuid' => $monitor_uuid
		);	

		$this->db->insert('status_part', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_status_by_part_uuid($uuid)
	{
		$this->db->order_by('created_at','DESC');


		$data = $this->db->get_where('status_part', array('monitor_uuid' => $uuid))->result();
		foreach ($data as $val) {
			$status = $val->status;
			if ($status == 0) {
				$val->status_part = '<span class="text-info">Pengajuan</span>';
			} else if ($status == 1) {
				$val->status_part = '<span class="text-success">Setuju</span>';
			} else if ($status == 2) {
				$val->status_part = '<span class="text-danger">Tolak</span>';
			} else if ($status == 3) {
				$val->status_part = '<span class="text-primary">Sesuai</span>';
			} else if ($status == 4) {
				$val->status_part = '<span class="text-warning">Tidak Sesuai';
			}
		}
		return $data;
	}

	public function get_monitor_count()
	{
	$data = $this->prepare_active_display(); // sudah hitung rh_end + kondisi

	$warning = 0;
	$over = 0;

	foreach ($data as $row) {
		if ($row->rh_end >= $row->lifetime) {
			$over++;
		} elseif ($row->rh_end >= $row->life_limit) {
			$warning++;
		}
	}

	return [
		'warning' => $warning,
		'over_lifetime' => $over
	];
}

public function delete_kegiatan($uuid)
{
	$data = [
		'deleted_at' => date('Y-m-d H:i:s'),
		'user_uuid' => $this->auth_model->current_user()->uuid
	];

	$this->db->where('uuid', $uuid);
	$this->db->update('monitor', $data);
}




public function approve_replacement($new_monitor_uuid)
{
	$new = $this->db->get_where('monitor', ['uuid' => $new_monitor_uuid])->row();

    // Cari part lama yang aktif
	$old = $this->db->get_where('monitor', [
		'mesin_uuid' => $new->mesin_uuid,
		'part_uuid'  => $new->part_uuid,
		'status'  => 1
	])->row();

	if ($old) {
        // Hitung RH terakhir
		$rh = $this->calculate_rh($old);

        // Tutup part lama
		$this->db->update('monitor', [
			'status' => 2,
			'removed_at'=> date('Y-m-d H:i:s'),
			'final_rh'  => $rh
		], ['uuid' => $old->uuid]);
	}

    // Aktifkan part baru
	$this->db->update('monitor', [
		'status' => 1,
		'nama_foreman' => $this->auth_model->current_user()->username,
		'installed_at' => date('Y-m-d H:i:s')
	], ['uuid' => $new_monitor_uuid]);

	return true;
}

public function prepare_active_display()
{
	$data = $this->get_active();

	// Ambil data pendukung SEKALI SAJA
	$planning_rows = $this->get_planning_rows();
	$counter_rows  = $this->get_counter_rows();

	// mapping counter per mesin
	$counter_map = [];
	foreach ($counter_rows as $c) {
		$counter_map[$c->mesin_uuid][] = $c;
	}

	foreach ($data as $row) {
		$row->tanggal = date('d M Y', strtotime($row->created_at));
		$row->life_limit = $row->lifetime * 0.8;
		$row->rh_end = 0;

		// HISTORY
		if ((int)$row->status === 2) {
			$row->rh_end = (float)$row->final_rh;
		}

		// JADWAL 0 → berdasarkan hari
		elseif ((int)$row->jadwal === 0) {
			$start = new DateTime($row->installed_at);
			$now   = new DateTime();
			$row->rh_end = $start->diff($now)->days;
		}

		// JADWAL 1 → berdasarkan total jam plan global
		elseif ((int)$row->jadwal === 1) {
			$total = 0;
			$installed = strtotime($row->installed_at);
			$now = time();

			foreach ($planning_rows as $p) {
				$start = strtotime($p->start);
				$end   = strtotime($p->end);

				if ($end >= $installed) {
					$effective_start = max($start, $installed);
					$effective_end   = min($end, $now);

					if ($effective_end > $effective_start) {
						$total += ($effective_end - $effective_start) / 3600;
					}
				}
			}

			$row->rh_end = round($total, 2);
		}

		// JADWAL 2 → berdasarkan counter per mesin
		elseif ((int)$row->jadwal === 2) {
			$total = 0;
			$installed = strtotime($row->installed_at);

			if (!empty($counter_map[$row->mesin_uuid])) {
				foreach ($counter_map[$row->mesin_uuid] as $c) {
					if (strtotime($c->created_at) >= $installed && (float)$c->speed > 0) {
						$total += ((float)$c->counter / (float)$c->speed / 60);
					}
				}
			}

			$row->rh_end = round($total, 2);
		}

		if ($row->rh_end >= $row->lifetime) {
			$row->kondisi = 'Over Lifetime';
		} elseif ($row->rh_end >= $row->life_limit) {
			$row->kondisi = 'Warning';
		} else {
			$row->kondisi = 'Baik';
		}
	}

	return $data;
}

public function prepare_history_display()
{
	$data = $this->get_history();

	foreach ($data as $row) {
		$row->rh_end = $row->final_rh;
		$row->tanggal = date('d M Y',strtotime($row->created_at));
		$row->tgl = date('Y-m-d', strtotime($row->created_at));
		$row->life_limit = $row->lifetime * 0.8;
		if ($row->rh_end >= $row->lifetime) {
			$row->kondisi = 'Over Lifetime';
		} elseif ($row->rh_end >= $row->life_limit) {
			$row->kondisi = 'Warning';
		} else {
			$row->kondisi = 'Baik';
		}
	}

	return $data;
}

public function reject_replacement($uuid)
{
	$this->db->update('monitor', [
		'status' => 3
	], ['uuid' => $uuid]);

	return true;
}

public function get_part_lama($uuid)
{
	$this->db->select('a.*, b.nama_mesin, b.nama_area, b.area_uuid, u.fullname');
	$this->db->from('monitor a');
	$this->db->join('mesin b', 'a.mesin_uuid = b.uuid', 'left');
	$this->db->join('users u', 'a.user_uuid = u.uuid', 'left');
	$this->db->where('a.uuid', $uuid);

	$data = $this->db->get()->row();

	if(!$data){
		return null;
	}

	$data->rh_end = $this->calculate_rh($data);

	// nama jadwal
	if ($data->jadwal == 0) {
		$data->jadwal_name = 'Harian';
	} elseif ($data->jadwal == 1) {
		$data->jadwal_name = 'Plan Produksi';
	} elseif ($data->jadwal == 2) {
		$data->jadwal_name = 'Counter Filler';
	}

	// part aktif lain
	$this->db->select('a.*, m.nama_mesin, m.nama_area, u.fullname');
	$this->db->from('monitor a');
	$this->db->join('mesin m','a.mesin_uuid = m.uuid','left');
	$this->db->join('users u', 'a.user_uuid = u.uuid', 'left');
	$this->db->where('a.part_uuid', $data->part_uuid);
	$this->db->where('a.status', 1);
	$this->db->where('a.deleted_at', null);

	$part_aktif = $this->db->get()->result();

	// hitung RH dan kondisi
	foreach ($part_aktif as $row) {

		$row->rh_end = $this->calculate_rh($row);
		$row->life_limit = $row->lifetime * 0.8;

		if ($row->rh_end >= $row->lifetime) {
			$row->kondisi = 'Over Lifetime';
			$row->badge = 'danger';
		} elseif ($row->rh_end >= $row->life_limit) {
			$row->kondisi = 'Warning';
			$row->badge = 'warning';
		} else {
			$row->kondisi = 'Baik';
			$row->badge = 'success';
		}

		if ($row->jadwal == 0) {
			$row->jadwal_name = 'Harian';
		} elseif ($row->jadwal == 1) {
			$row->jadwal_name = 'Plan Produksi';
		} elseif ($row->jadwal == 2) {
			$row->jadwal_name = 'Counter Filler';
		}

	}

	$data->part_aktif = $part_aktif;

	return $data;
}

public function get_by_uuid($uuid)
{
		// $this->db->select('a.*, b.nama_area');
	$this->db->select('a.*, b.nama_mesin, b.nama_area, b.area_uuid, u.fullname');
	$this->db->from('monitor a');
	$this->db->join('mesin b', 'a.mesin_uuid = b.uuid', 'left');
	$this->db->join('users u', 'a.user_uuid = u.uuid', 'left');
	$this->db->where('a.uuid', $uuid);

	$data = $this->db->get()->row();

	$data->rh_end = $this->calculate_rh($data);
	if ($data->jadwal == 0) {
		$data->jadwal_name = 'Harian';
	} elseif ($data->jadwal == 1) {
		$data->jadwal_name = 'Plan Produksi';
	} elseif ($data->jadwal == 2) {
		$data->jadwal_name = 'Counter Filler';
	}

	return $data;
}

public function get_history_export($area,$mesin,$kondisi,$start,$end)
{
	$this->db->select('a.*, m.nama_area');
	$this->db->from('monitor a');
	$this->db->join('mesin m', 'm.uuid = a.mesin_uuid', 'left');
	$this->db->where('a.status',2);

	if($area != ""){
		$this->db->where('m.nama_area',$area);
	}

	if($mesin != ""){
		$this->db->where('a.nama_mesin',$mesin);
	}

	if($start != ""){
		$this->db->where('DATE(a.created_at) >=',$start);
	}

	if($end != ""){
		$this->db->where('DATE(a.created_at) <=',$end);
	}

	$this->db->order_by('a.created_at','DESC');

	$data = $this->db->get()->result();

	foreach ($data as $row) {

		$row->life_limit = $row->lifetime * 0.8;

		if ($row->final_rh >= $row->lifetime) {
			$row->kondisi = 'Over Lifetime';
		} elseif ($row->final_rh >= $row->life_limit) {
			$row->kondisi = 'Warning';
		} else {
			$row->kondisi = 'Baik';
		}
	}

	if($kondisi != ""){
		$data = array_filter($data,function($d) use ($kondisi){
			return $d->kondisi == $kondisi;
		});
	}

	return $data;
}

}