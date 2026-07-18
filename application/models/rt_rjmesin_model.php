<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Rt_rjmesin_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
	}

	public function rules()
	{
		return [
			[
				'field' => 'mesin',
				'label' => 'Pilih Mesin',
				'rules' => 'required'	
			]
		];
	}

	public function rules_reject()
	{
		return [
			[
				'field' => 'badpro',
				'label' => 'Pilih Badpro',
				'rules' => 'required'	
			]
		];
	}
	public function get_all()
	{
		$this->db->select('pl.*, v.varian, m.MN_BATCH');
		$this->db->from('t_planning pl');
		$this->db->join('varian v', 'pl.varian_uuid = v.uuid', 'left');
		$this->db->join('mincing m', 'pl.uuid = m.planprod_uuid', 'left');
		$this->db->group_by('pl.uuid, v.varian');
		$this->db->order_by('pl.created_at', 'DESC');
		$data = $this->db->get()->result();

		foreach ($data as &$record) {
			$record->tanggal = date('d M Y', strtotime($record->created_at));
			$record->tgl = date('Y-m-d', strtotime($record->created_at)); 

			$record->batch_ke = isset($record->MN_BATCH) ? substr($record->MN_BATCH, 5, 2) : null;
		}

		return $data;
	}

	public function get_batch($uuid) 
	{
		$this->db->select('tp.uuid, m.created_at, m.MN_BATCH, tp.fr_rt_rjmesin, m.MN_PRODUK, tp.tanggal, u.fullname as foreman_name, u1.fullname as spv_name');
		$this->db->from('e-retort-dev.mincing m');
		$this->db->join('t_planning tp', 'tp.uuid = m.planprod_uuid', 'left');
		$this->db->join('users u', 'u.uuid = tp.fr_rt_rjmesin', 'left');
		$this->db->join('users u1', 'u1.uuid = tp.spv_rt_rjmesin', 'left');
		$this->db->where('planprod_uuid', $uuid);
		$this->db->order_by('m.MN_BATCH', 'ASC');
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->time = date('H:i', strtotime($val->created_at));
			$val->batch_ke = substr($val->MN_BATCH, 5, 2);
			$val->tgl = date('d M Y', strtotime($val->tanggal));
		}
		return $data;
	}

	public function get_by_code($MN_BATCH)
	{   
		$this->db->select('m.*');
		$this->db->from('mincing m');
		$this->db->where('MN_BATCH', $MN_BATCH);
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get()->row();

		$data->tanggal = date('d M Y', strtotime($data->MN_DATE));
		return $data;
	}

	public function get_badpro()
	{
		return $this->db->get('badpro')->result();
	}

	public function update($uuid)
	{
		$item = $this->input->post('item_barang');
		$keterangan = $this->input->post('keterangan');
		$qty_reservasi = $this->input->post('qty_reservasi');

		$data = array(
			'item_barang' 		=> $item,
			'qty_reservasi'		=> $qty_reservasi,
			'keterangan' 		=> $keterangan,
			'user_uuid'     	=> $this->auth_model->current_user()->uuid,
			'modified_at' 		=> date('Y-m-d H:i:s')
		);	

		$this->db->update('bahanbaku', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	
	public function insert_mesin($MN_BATCH)
	{
    // Ambil data mesin, badpro, dan reject dari input
		$mesin_uuid = $this->input->post('mesin');
		$badpro_array = $this->input->post('badpro') ?? [];
		$reject_array = $this->input->post('reject') ?? [];

		if (empty($mesin_uuid)) {
        return false; // Jika mesin tidak dipilih, kembalikan false
    }

    // Generate UUID untuk rt_mesin
    $uuid_mesin = Uuid::uuid4()->toString();
    $data_mesin = [
    	'uuid' => $uuid_mesin,
    	'user_uuid' => $this->auth_model->current_user()->uuid,
    	'mesin_uuid' => $mesin_uuid,
    	'kode_batch' => $MN_BATCH
    ];

    // Insert data mesin ke tabel rt_mesin
    $this->db->insert('rt_mesin', $data_mesin);

    // Cek apakah query berhasil
    if (!$this->db->affected_rows()) {
    	return false;
    }

    // Proses data badpro dan reject
    foreach ($badpro_array as $key => $badpro_uuid) {
    	if (!empty($badpro_uuid) && isset($reject_array[$key])) {
    		$reject_uuid = Uuid::uuid4()->toString();
    		$data_badpro = [
    			'uuid' => $reject_uuid,
    			'rt_mesin_uuid' => $uuid_mesin,
    			'badpro_uuid' => $badpro_uuid,
    			'reject' => $reject_array[$key],
    		];

            // Insert data ke tabel rt_rjmesin
    		$this->db->insert('rt_rjmesin', $data_badpro);

            // Cek apakah query berhasil
    		if (!$this->db->affected_rows()) {
    			return false;
    		}
    	}
    }

    return true;
}

public function update_reject($rm_uuid)
{
	$mesin_uuid = $this->input->post('mesin');
	$badpro_array = $this->input->post('badpro') ?? [];
	$reject_array = $this->input->post('reject') ?? [];

	$badproadd_array = $this->input->post('badproadd') ?? [];
	$rejectadd_array = $this->input->post('rejectadd') ?? [];

	foreach ($badpro_array as $key => $badpro_uuid) {
		if (!empty($badpro_uuid) && isset($reject_array[$key])) {

			$data_badpro = [
				'badpro_uuid' => $badpro_uuid,
				'reject' => $reject_array[$key],
				'modified_at' => date('Y-m-d h:i:s')
			];

            // Insert data ke tabel rt_rjmesin
			$this->db->where('rt_mesin_uuid', $rm_uuid);
			$this->db->where('badpro_uuid', $badpro_uuid);
			$this->db->update('rt_rjmesin', $data_badpro);

            // Cek apakah query berhasil
			if (!$this->db->affected_rows()) {
				return false;
			}
		}
	}

	foreach ($badproadd_array as $key => $badproadd_uuid) {
		if (!empty($badproadd_uuid) && isset($rejectadd_array[$key])) {
			$reject_uuid = Uuid::uuid4()->toString();
			$data_new_badpro = [
				'uuid' => $reject_uuid,
				'rt_mesin_uuid' => $rm_uuid,
				'badpro_uuid' => $badproadd_uuid,
				'reject' => $rejectadd_array[$key],
			];

            // Insert data ke tabel rt_rjmesin
			$this->db->insert('rt_rjmesin', $data_new_badpro);

            // Cek apakah query berhasil
			if (!$this->db->affected_rows()) {
				return false;
			}
		}
	}

    return true; // Return true jika semua operasi berhasil
}


// public function get_mesin_with_usage_status($MN_BATCH)
// 	{
// 		$area_uuid = $this->mp;
// 		$this->db->select('m.uuid, m.nama_mesin, COUNT(c.mesin_uuid) AS is_used');
// 		$this->db->from('mesin m');
// 		$this->db->join('cek_mesin c', 'm.uuid = c.mesin_uuid AND c.t_planning_uuid = '.$this->db->escape($t_planning_uuid), 'left');
// 		$this->db->where('m.area_uuid', $area_uuid);
// 		$this->db->group_by('m.id'); // Gunakan primary key mesin

// 		$data = $this->db->get();
// 		return $data->result();
// 	}

public function get_mesin($MN_BATCH)
{
	$this->db->select('tc.device_id, tc.tbatch_uuid, m.nama_mesin, COUNT(rm.mesin_uuid) AS is_used');
	$this->db->from('tcounter tc');
    $this->db->join('rt_mesin rm', 'tc.device_id = rm.mesin_uuid', 'left'); // Perbaiki join ini
    $this->db->join('mesin m', 'm.device_id = tc.device_id', 'left'); // Perbaiki join ini
    $this->db->where('tc.tbatch_uuid', $MN_BATCH);
    $this->db->group_by('tc.id');
    $data = $this->db->get()->result();

    return $data;
}


public function get_reject_by_batch($MN_BATCH)
{
	$this->db->select('rm.mesin_uuid, m.nama_mesin, bp.nama_badpro, rj.reject, rm.uuid');
	$this->db->from('rt_mesin rm');
	$this->db->join('rt_rjmesin rj', 'rj.rt_mesin_uuid = rm.uuid', 'left');
	$this->db->join('badpro bp', 'bp.uuid = rj.badpro_uuid', 'left');
	$this->db->join('mesin m', 'm.device_id = rm.mesin_uuid', 'left');
	$this->db->where('rm.kode_batch', $MN_BATCH);
	$data = $this->db->get()->result();

    // Proses data menjadi bentuk terstruktur
	$result = [];
	foreach ($data as $row) {
		$mesin = $row->mesin_uuid;
		$badpro = $row->nama_badpro;
		$rm_uuid = $row->uuid;

		if (!isset($result[$mesin])) {
			$result[$mesin] = [
				'rm_uuid' => $rm_uuid,
				'nama_mesin' => $row->nama_mesin,
				'badpro' => []
			];
		}

		$result[$mesin]['badpro'][$badpro] = $row->reject;
	}

	return $result;
}

public function get_badpro_headers($MN_BATCH)
{
	$this->db->select('DISTINCT(bp.nama_badpro), bp.uuid');
	$this->db->from('rt_rjmesin rj');
	$this->db->join('badpro bp', 'bp.uuid = rj.badpro_uuid', 'left');
	$this->db->join('rt_mesin rm', 'rj.rt_mesin_uuid = rm.uuid', 'left');
	$this->db->where('rm.kode_batch', $MN_BATCH);
	$this->db->order_by('bp.nama_badpro', 'asc');
	$data = $this->db->get()->result();
	return $data;
}


public function get_mesin_reject_by_plan($planprod_uuid)
{
	$this->db->select('rm.mesin_uuid, m.nama_mesin, bp.nama_badpro, rj.reject, rm.kode_batch, rm.uuid, mn.planprod_uuid, u.fullname, mn.MN_PRODUK, mn.MN_DATE');
	$this->db->from('rt_mesin rm');
	$this->db->join('rt_rjmesin rj', 'rj.rt_mesin_uuid = rm.uuid', 'left');
	$this->db->join('badpro bp', 'bp.uuid = rj.badpro_uuid', 'left');
	$this->db->join('mesin m', 'm.device_id = rm.mesin_uuid', 'left');
	$this->db->join('users u', 'u.uuid = rm.user_uuid', 'left');
	$this->db->join('mincing mn', 'mn.MN_BATCH = rm.kode_batch', 'left');
	$this->db->join('t_planning tp', 'tp.uuid = mn.planprod_uuid', 'left');
	$this->db->where('mn.planprod_uuid', $planprod_uuid);
	$this->db->order_by('rm.kode_batch, m.nama_mesin, bp.nama_badpro', 'asc');
	$data = $this->db->get()->result();

    // Proses data menjadi terstruktur berdasarkan mesin dan batch
	$result = [];
	foreach ($data as $row) {
		$mesin = $row->mesin_uuid;
		$batch = $row->kode_batch;
		$row->tanggal = date('d M Y', strtotime($row->MN_DATE));
		if (!isset($result[$mesin])) {
			$result[$mesin] = [
				'nama_mesin' => $row->nama_mesin,
				'batches' => []
			];
		}

		if (!isset($result[$mesin]['batches'][$batch])) {
			$result[$mesin]['batches'][$batch] = [];
		}

		$result[$mesin]['batches'][$batch][$row->nama_badpro] = $row->reject;
	}

	return $result;
}


public function get_badpro_headers_by_plan($planprod_uuid)
{
	$this->db->select('DISTINCT(bp.nama_badpro), bp.uuid');
	$this->db->from('rt_rjmesin rj');
	$this->db->join('badpro bp', 'bp.uuid = rj.badpro_uuid', 'left');
	$this->db->join('rt_mesin rm', 'rj.rt_mesin_uuid = rm.uuid', 'left');
	$this->db->join('mincing mn', 'mn.MN_BATCH = rm.kode_batch', 'left');
	$this->db->where('mn.planprod_uuid', $planprod_uuid);
	$this->db->order_by('bp.nama_badpro', 'asc');
	$data = $this->db->get()->result();
	return $data;
}

public function get_batches_by_plan($planprod_uuid)
{
	$this->db->select('DISTINCT(mn.MN_BATCH) AS kode_batch');
	$this->db->from('mincing mn');
	$this->db->where('mn.planprod_uuid', $planprod_uuid);
	$this->db->order_by('mn.MN_BATCH', 'ASC');

	$data = $this->db->get()->result();

	return array_column($data, 'kode_batch');
}




public function get_reject_by_mesin($rm_uuid)
{
	$this->db->select('rj.*, rm.kode_batch, m.planprod_uuid, m.MN_DATE, m.MN_PRODUK, m.MN_BATCH, ms.nama_mesin');
	$this->db->from('rt_rjmesin rj');
	$this->db->join('rt_mesin rm', 'rm.uuid = rj.rt_mesin_uuid', 'left');
	$this->db->join('mincing m', 'm.MN_BATCH = rm.kode_batch','left');
	$this->db->join('mesin ms', 'ms.device_id = rm.mesin_uuid', 'left');
	$this->db->where('rj.rt_mesin_uuid', $rm_uuid);
	$data = $this->db->get()->result();

	foreach ($data as $val)
	{
		$val->tanggal = date('d M Y', strtotime($val->MN_DATE));
	}

	return $data;
}

public function get_plan_by_uuid($planprod_uuid)
{
	$this->db->select('tp.uuid, tp.tanggal, tp.varian, u.fullname as user_name, u1.fullname as foreman_name, u2.fullname as spv_name');
	$this->db->from('t_planning tp');
	$this->db->join('mincing mn', 'tp.uuid = mn.planprod_uuid', 'left');
	$this->db->join('rt_mesin rm', 'rm.kode_batch = mn.MN_BATCH', 'left');
	$this->db->join('users u', 'u.uuid = rm.user_uuid', 'left');
	$this->db->join('users u1', 'u1.uuid = tp.fr_rt_rjmesin', 'left');
	$this->db->join('users u2', 'u2.uuid = tp.spv_rt_rjmesin', 'left');
	$this->db->where('tp.uuid', $planprod_uuid);
	$data = $this->db->get()->row();

	$data->tgl = date('d M Y', strtotime($data->tanggal));

	return $data;
}

public function update_kr($plan_uuid)
{
		$data = array
		(
			'fr_rt_rjmesin' => $this->auth_model->current_user()->uuid
		);

		$this->db->update('t_planning', $data, array('uuid' => $plan_uuid));
		return $this->db->affected_rows();

	}

	public function update_spv($plan_uuid)
	{
		$data = array
		(
			'spv_rt_rjmesin' => $this->auth_model->current_user()->uuid
		);

		$this->db->update('t_planning', $data, array('uuid' => $plan_uuid));
		return $this->db->affected_rows();
	}




}