<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Pm_model extends CI_Model 
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
				'field' => 'mesin',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'keluhan',
				'label' => 'Keluhan',
				'rules' => 'required'
			],
			[
				'field' => 'tindakan',
				'label' => 'Tindakan Perbaikan',
				'rules' => 'required'
			],
			[
				'field' => 'pelaksana',
				'label' => 'Pelaksana',
				'rules' => 'required'
			],
			// [
			// 	'field' => 'dokumentasi',
			// 	'label' => 'Foto',
			// 	'rules' => 'callback_file_check'
			// ],

			[
				'field' => 'catatan',
				'label' => 'Catatan',
				'rules' => 'required'
			]	

		];
	}

	public function get_all()
	{
		$this->db->select('a.*');
		$this->db->select('(SELECT b.status FROM status_maintenance b WHERE b.maintenance_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
		$this->db->select("DATE(created_at) as tgl", false);
		$this->db->from('maintenance a');
		$this->db->order_by('a.created_at', 'DESC');
		$this->db->where('a.deleted_at IS NULL', null, false);
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$status = $val->status;
			$tgl = $val->tgl;
			$now = date('Y-m-d');
			$awal = strtotime($tgl);
			$akhir = strtotime($now);
			$total = $akhir - $awal;
			$val->selisih = $total / (60*60*24);
			if ($status == 0) {
				$val->status_mesin = '<span class="text-info">Pengajuan</span>';
			} else if ($status == 1) {
				$val->status_mesin = '<span class="text-success">Sesuai</span>';
			} else if ($status == 2) {
				$val->status_mesin = '<span class="text-danger">Perbaikan Ulang</span>';
			}
			if ($val->selisih >= 8) {
				$val->kondisi = '<span class="text-danger font-weight-bold">Top Urgent</span>';
			} else if ($val->selisih > 3) {
				$val->kondisi = '<span class="text-warning font-weight-bold">Urgent</span>';
			} else {
				$val->kondisi = '<span class="text-info font-weight-bold">Pengajuan</span>';
			}
		}	


		return $data;

	}

	public function get_by_uuid($uuid) // maintenance.index
	{

		// $this->db->select('a.*, b.nama_area');
		$this->db->select('a.*, a.uuid as maintenance_uuid, a.created_at as tgl, b.*, up.fullname as pelaksana, ua.fullname as acc_name');
		$this->db->from('maintenance a');
		$this->db->join('mesin b', 'a.mesin_uuid = b.uuid', 'left');
		$this->db->join('users up', 'up.uuid = a.nama_pelaksana', 'left');
		$this->db->join('users ua', 'ua.uuid = a.nama_acc', 'left');
		$this->db->where('a.uuid', $uuid);
		$data = $this->db->get()->row();		
		


		return $data;
	}


	public function insert($dok_before)
	{
		$uuid 			= Uuid::uuid4()->toString();
		$mesin 			= $this->input->post('mesin');
		$mesin_name 	= $this->input->post('mesin_name');
		$keluhan 		= $this->input->post('keluhan');
		// $tindakan 		= $this->input->post('tindakan');
		// $pelaksana 		= $this->input->post('pelaksana');
		// $catatan 		= $this->input->post('catatan');
		// $harga 		= $this->input->post('harga');
		
		$data = array(
			'uuid' 				=> $uuid,
			'user_uuid'			=> $this->auth_model->current_user()->uuid,
			'username' 			=> $this->auth_model->current_user()->username,
			'mesin_uuid' 		=> $mesin,
			'nama_mesin' 		=> $mesin_name,
			'nama_operator' 	=>  $this->auth_model->current_user()->fullname,
			// 'nama_pelaksana' 	=> $pelaksana,
			'keluhan' 			=> $keluhan,
			// 'tindakan' 			=> $tindakan,
			'dokumentasi' 		=> $dok_before,
			// 'total_pending' 	=> 'total pending',
			// 'dokumentasi_acc' 	=> $dok_after,
			// 'catatan' 			=> $catatan
		);	

		$this->db->insert('maintenance', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid, $dok_before = '')
	{
		$mesin        = $this->input->post('mesin');
		$mesin_name   = $this->input->post('mesin_name');
		$keluhan      = $this->input->post('keluhan');

		$data = array(
			'user_uuid'      => $this->auth_model->current_user()->uuid,
			'username'       => $this->auth_model->current_user()->username,
			'mesin_uuid'     => $mesin,
			'nama_mesin'     => $mesin_name,
			'nama_operator'  => $this->auth_model->current_user()->fullname,
			'keluhan'        => $keluhan,
			'modified_at'    => date('Y-m-d H:i:s')
		);

		if (!empty($dok_before)) {
			$data['dokumentasi'] = $dok_before;
		}

		$this->db->where('uuid', $uuid);

		$update = $this->db->update('maintenance', $data);

		return $update;
	}

	public function tindakan($uuid, $dok_after = '')
	{
		$tindakan = $this->input->post('tindakan');

		$data = array(
			'nama_pelaksana' => $this->auth_model->current_user()->uuid,
			'tindakan'       => $tindakan,
			'tindakan_at'    => date('Y-m-d H:i:s')
		);

		if (!empty($dok_after)) {
			$data['dokumentasi_acc'] = $dok_after;
		}

		$this->db->where('uuid', $uuid);

		$update = $this->db->update('maintenance', $data);

		return $update;
	} 

	public function status($uuid)
	{
	// 	$data = $this->db->get_where('maintenance', array('uuid' => $uuid))->row();
	// 	$uuid 		= Uuid::uuid4()->toString();
	// 	$status 	= $this->input->post('status');
	// 	$keterangan = $this->input->post('keterangan');
	// 	$data 		= array(
	// 		'uuid' 	=> $uuid,
	// 		'status' => $status,
	// 		// 'dokumentasi_acc' 	=> $dok_after,
	// 		'maintenance_uuid' => $data->uuid,
	// 		'catatan' 		=> $keterangan
	// 	);
		$data = array(
			'nama_acc' => $this->auth_model->current_user()->uuid,
			'acc_at' => date('Y-m-d H:i:s')
		);

		$this->db->update('maintenance', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function updatedoc($uuid, $dok_after)
	{
		
		$data = array(
			'dokumentasi_acc' => $dok_after
		);

		$this->db->where('uuid', $uuid);
        $this->db->update('maintenance', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false;

    }

    // public function get_status_by_maintenance_uuid($uuid)
    // {
    // 	$this->db->order_by('created_at','DESC');
    // 	$data = $this->db->get_where('status_maintenance', array('maintenance_uuid' => $uuid))->result();

    // 	foreach ($data as $val) {
    // 		$status = $val->status;
    // 		if ($status == 1) {
    // 			$val->status_mesin = '<span class="text-success">Sesuai</span>';
    // 		} else if ($status == 2) {
    // 			$val->status_mesin = '<span class="text-danger">Perbaikan Ulang</span>';
    // 		}

    // 		$date = DateTime::createFromFormat('Y-m-d H:i:s', $val->created_at);
    // 		$val->tgl = $date->format('d F Y, H:i:s');
    // 	}
    // 	return $data;
    // }

    public function get_catat($uuid)
    {
    	$this->db->select('a.*, IFNULL (b.catatan, "") as catat', false);
    	$this->db->from('maintenance a');
    	$this->db->join('status_maintenance b', 'a.uuid = b.maintenance_uuid', 'left');
    	$this->db->where('a.uuid', $uuid);
    	$data = $this->db->get()->row();

    	return $data;
// print_r($data);
		// $this->db->select('a.*, IFNULL (b.catatan, "") as catat', false);
		// $this->db->from('maintenance a');
		// $this->db->join('status_maintenance b', 'a.uuid=b.maintenance_uuid', 'left');
		// $this->db->where('a.uuid', $uuid)
		// $data = $this->db->get()->row();
		// return $data;
    }

    public function get_pengajuan()
    {
    	$CI =& get_instance();
    	$hak_akses = $CI->session->userdata('hak_akses');

    	$pengajuan = date('Y-m-d H:i:s', strtotime('-3 days'));
    	$this->db->select('a.*');
    	$this->db->select("DATE(a.created_at) as tgl", false);
    	$this->db->from('maintenance a');
    	$this->db->join('users u', 'u.uuid = a.user_uuid', 'left');
    	$this->db->where('a.created_at>=', $pengajuan);
    	if($hak_akses != 'Engineering' && $hak_akses != 'superadmin'){
    		$this->db->where('u.hak_akses', $hak_akses);
    	}
    	$this->db->where("a.acc_at IS NULL", null, false);
    	$this->db->where('a.deleted_at IS NULL', null, false);
    	$this->db->order_by('a.created_at', 'DESC');
    	$data = $this->db->get()->result();
// print_r($data);
    	foreach ($data as $val) {
    		$tgl = $val->tgl;
    		$now = date('Y-m-d');
    		$awal = strtotime($tgl);
    		$akhir = strtotime($now);
    		$total = $akhir - $awal;
    		$val->selisih = $total / (60*60*24);

    		if ($val->tindakan == NULL) {
    			$val->status_mesin = '<span class="text-dark">-</span>';
    		} else {
    			$val->status_mesin = '<span class="text-warning">Menunggu ACC</span>';
    		}

    		if ($val->selisih >= 8) {
    			$val->kondisi = '<span class="text-danger font-weight-bold">Top Urgent</span>';
    		} else if ($val->selisih > 3) {
    			$val->kondisi = '<span class="text-warning font-weight-bold">Urgent</span>';
    		} else {
    			$val->kondisi = '<span class="text-info font-weight-bold">Pengajuan</span>';
    		}

    	}

    	return $data;

    }

    public function get_urgent()
    {
    	$CI =& get_instance();
    	$hak_akses = $CI->session->userdata('hak_akses');

    	$pengajuan = date('Y-m-d H:i:s', strtotime('-3 days'));
    	
    	$this->db->select('a.*, m.nama_area, DATE(a.created_at) as tgl, u.hak_akses');
    	
    	$this->db->from('maintenance a');
    	$this->db->join('mesin m', 'm.uuid = a.mesin_uuid', 'left');
    	$this->db->join('users u', 'u.uuid = a.user_uuid', 'left');
    	$this->db->where('a.created_at<',$pengajuan);
    	$this->db->where('a.acc_at IS NULL', null, false);
    	if($hak_akses != 'Engineering' && $hak_akses != 'superadmin'){
    		$this->db->where('u.hak_akses', $hak_akses);
    	}
    	$this->db->where('a.deleted_at IS NULL', null, false);
    	$this->db->order_by('a.created_at', 'DESC');
    	$data = $this->db->get()->result();

    	foreach ($data as $val) {
    		$tgl = $val->tgl;
    		$now = date('Y-m-d');
    		$awal = strtotime($tgl);
    		$akhir = strtotime($now);
    		$total = $akhir - $awal;
    		$val->selisih = $total / (60*60*24);
    		if ($val->tindakan_at == NULL) {
    			$val->status_mesin = '<span class="text-dark">-</span>';
    		} else {
    			$val->status_mesin = '<span class="text-warning">Menunggu ACC</span>';
    		}

    		if ($val->selisih >= 8) {
    			$val->kondisi = '<span class="text-danger font-weight-bold">Top Urgent</span>';
    		} else if ($val->selisih > 3) {
    			$val->kondisi = '<span class="text-warning font-weight-bold">Urgent</span>';
    		} else {
    			$val->kondisi = '<span class="text-info font-weight-bold">Pengajuan</span>';
    		}
    	}	
    	return $data;
    }

    public function get_history()
    {
    	$CI =& get_instance();
    	$hak_akses = $CI->session->userdata('hak_akses');


    	$this->db->select('a.*, m.nama_area, DATE(a.created_at) as awal');
    	$this->db->select('DATE(a.acc_at) as akhir');
    	$this->db->from('maintenance a');
    	$this->db->join('users u', 'u.uuid = a.user_uuid', 'left');
    	$this->db->join('mesin m', 'a.mesin_uuid = m.uuid', 'left');
    	$this->db->where('a.acc_at IS NOT NULL', null, false);
    	if($hak_akses != 'Engineering' && $hak_akses != 'superadmin'){
    		$this->db->where('u.hak_akses', $hak_akses);
    	}
		// $this->db->order_by('b.created_at', 'DESC');
    	$this->db->order_by('a.acc_at', 'DESC');
    	$this->db->where('a.deleted_at IS NULL', null, false);
		// $this->db->limit(1);
    	$data = $this->db->get()->result();

		// print_r($data);
		// // $sesuai = '1';
		// // $this->db->select('a.*','b.status');
		// // $this->db->select('(SELECT b.status FROM status_maintenance b WHERE b.maintenance_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);

		// // $this->db->from('maintenance a');
		// // $this->db->where('b.status', $sesuai );

		// // $data = $this->db->get()->result();

    	foreach ($data as $val) {
    		$tgl = $val->awal;
    		$now = $val->akhir;
    		$awal = strtotime($tgl);
    		$akhir = strtotime($now);
    		$total = $akhir - $awal;
    		$val->selisih = $total / (60*60*24);
    		if ($val->acc_at != NULL) {
    			$val->status_mesin = '<span class="text-success">SESUAI</span>';
    		} else {
    			$val->status_mesin = '<span class="text-danger">Perbaikan Ulang</span>';
    		}
    		if ($val->selisih >= 8) {
    			$val->kondisi = '<span class="text-danger font-weight-bold">Top Urgent</span>';
    		} else if ($val->selisih > 3) {
    			$val->kondisi = '<span class="text-warning font-weight-bold">Urgent</span>';
    		} else {
    			$val->kondisi = '<span class="text-info font-weight-bold">Pengajuan</span>';
    		}
    	}
    	return $data;

    }

    public function get_total_main()
    {
		// $pengajuan = date('Y-m-d H:i:s', strtotime('-3 days'));
    	$sesuai = $this->db->select('maintenance_uuid')
    	->from('status_maintenance')->where('status', 1)->get_compiled_select();

    	$this->db->select('a.*, DATE(a.created_at) as tgl');
    	$this->db->select('(SELECT b.status FROM status_maintenance b WHERE b.maintenance_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
    	$this->db->from('maintenance a');
		// $this->db->where('a.created_at<',$pengajuan);
    	$this->db->where("uuid NOT IN ($sesuai)", null, false);
    	$this->db->order_by('a.created_at', 'DESC');
    	return $this->db->get()->num_rows();
    }

    public function delete_kegiatan($uuid)
    {
    	$data = [
    		'deleted_at' => date('Y-m-d H:i:s'),
    		'user_uuid' => $this->auth_model->current_user()->uuid
    	];

    	$this->db->where('uuid', $uuid);
    	$this->db->update('maintenance', $data);
    }

    public function get_export($area,$mesin,$status,$start,$end,$pending)
    {
    	$CI =& get_instance();
    	$hak_akses = $CI->session->userdata('hak_akses');

    	$this->db->select('a.*, m.nama_area, DATE(a.created_at) as awal');
    	$this->db->select('DATE(a.acc_at) as akhir');
    	$this->db->from('maintenance a');
    	$this->db->join('mesin m','a.mesin_uuid = m.uuid','left');
    	$this->db->join('users u', 'u.uuid = a.user_uuid', 'left');
    	$this->db->where('a.acc_at IS NOT NULL', null, false);
    	$this->db->where('a.deleted_at IS NULL',null,false);
    	if($hak_akses != 'Engineering' && $hak_akses != 'superadmin'){
    		$this->db->where('u.hak_akses', $hak_akses);
    	}

    	if($area){
    		$this->db->where('m.nama_area',$area);
    	}

    	if($mesin){
    		$this->db->where('a.nama_mesin',$mesin);
    	}

    	if($start){
    		$this->db->where('DATE(a.created_at) >=',$start);
    	}

    	if($end){
    		$this->db->where('DATE(a.created_at) <=',$end);
    	}

    	$data = $this->db->get()->result();

    	foreach ($data as $key => $val){

    		$awal = strtotime($val->awal);
    		$akhir = strtotime($val->akhir);

    		$selisih = ($akhir-$awal)/(60*60*24);

    		$val->selisih = $selisih;

    		if ($selisih >= 8){
    			$val->kondisi="Top Urgent";
    		}
    		else if ($selisih > 3){
    			$val->kondisi="Urgent";
    		}
    		else{
    			$val->kondisi="Pengajuan";
    		}

    		/* FILTER STATUS */

    		if($status && $val->kondisi != $status){
    			unset($data[$key]);
    			continue;
    		}

    		/* FILTER PENDING */

    		if($pending){

    			if($pending=="0-7" && !($selisih>=0 && $selisih<=7)){
    				unset($data[$key]);
    			}

    			if($pending=="8-14" && !($selisih>=8 && $selisih<=14)){
    				unset($data[$key]);
    			}

    			if($pending=="15-30" && !($selisih>=15 && $selisih<=30)){
    				unset($data[$key]);
    			}

    			if($pending=="30+" && !($selisih>30)){
    				unset($data[$key]);
    			}

    		}

    	}

    	return $data;
    }
    public function count_maintenance()
    {
    	$pengajuan = date('Y-m-d H:i:s', strtotime('-3 days'));

    	$this->db->from('maintenance');

    	$this->db->where('created_at <', $pengajuan);
    	$this->db->where('acc_at IS NULL', null, false);
    	$this->db->where('deleted_at IS NULL', null, false);

    	return $this->db->count_all_results();
    }

    public function get_top_pm($limit = 5)
{
    $this->db->select("
        a.mesin_uuid,
        m.nama_mesin,
        COUNT(a.uuid) as total
    ");

    $this->db->from('maintenance a');
    $this->db->join('mesin m', 'm.uuid = a.mesin_uuid', 'left');

    $this->db->where('a.deleted_at IS NULL', null, false);

    $this->db->group_by('a.mesin_uuid');
    $this->db->group_by('m.nama_mesin');

    $this->db->order_by('total', 'DESC');
    $this->db->limit($limit);

    return $this->db->get()->result();
}

}