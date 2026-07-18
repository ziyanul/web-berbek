<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Cek_mesin_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('gmp_model');
		// $this->load->model('area_model');
		// $this->load->model('mesin_model');
	}

	public function rules()
	{
		return [
			
		];
	}

	public function get_plan()
	{
		$this->db->select('p.*');
		$this->db->select('(SELECT count(DISTINCT c.area_uuid) FROM cek_mesin c WHERE c.t_planning_uuid = p.uuid) as jumlah_area', false);
		$this->db->select('(SELECT count(DISTINCT c.group) FROM cek_mesin c WHERE c.t_planning_uuid = p.uuid) as jumlah_group', false);
		$this->db->from('t_planning p');
		$this->db->order_by('p.tanggal', 'DESC');
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tgl_cek = date('d M Y', strtotime($val->start));
			$val->tgl = date('d M Y', strtotime($val->tanggal));
			$val->varian = $val->varian == 1 ? 'Okey' : (
				$val->varian == 2 ? 'Champ Ayam' : (
					$val->varian == 3 ? 'Champ Sapi' : (
						$val->varian == 4 ? 'Champ Otak-Otak' : ''
					)));

		}

		return $data;
	}


	public function get_plan_uuid($t_planning_uuid)
	{
		
		$data = $this->db->get_where('t_planning', array('uuid' => $t_planning_uuid ))->row();

		return $data;
	}

	public function cek_area_name($uuid)
	{
		$this->db->select('nama_area');
		$this->db->from('area');
		$this->db->where('uuid', $uuid);
		$query = $this->db->get();
		return $query->row();
	}

	public function cek_mesin_name($uuid)
	{
		$this->db->select('nama_mesin');
		$this->db->from('mesin');
		$this->db->where('uuid', $uuid);
		$query = $this->db->get();
		return $query->row(); 
	}

	public function cek_kegiatan_by_mesin($mesin_uuid)
	{
		$this->db->select('uuid, kegiatan');
		$this->db->from('item_cekmesin');
		$this->db->where('mesin_uuid', $mesin_uuid);
		$query = $this->db->get();
    return $query->result();  // Mengembalikan array objek kegiatan
  }

  public function insert_cek_mesin($t_planning_uuid)
  {
  	$area_uuid = $this->input->post('area');
  	$mesin_uuid = $this->input->post('mesin');
  	$kegiatan = $this->input->post('kegiatan');
  	$keterangan = $this->input->post('keterangan');
  	$area = $this->cek_area_name($area_uuid)->nama_area;
  	$mesin = $this->cek_mesin_name($mesin_uuid)->nama_mesin;
  	$all_kegiatan = $this->cek_kegiatan_by_mesin($mesin_uuid);

  	foreach ($all_kegiatan as $kegiatan_item) {
  		$uui = Uuid::uuid4()->toString();
  		$kegiatan_name = $kegiatan_item->kegiatan;
  		$kegiatan_uuid = $kegiatan_item->uuid;

  		$data = [
  			'uuid'             => $uui,
  			'user_uuid'        => $this->auth_model->current_user()->uuid,
  			'username'         => $this->auth_model->current_user()->username,
  			't_planning_uuid'  => $t_planning_uuid,
  			'area_uuid'			=> $area_uuid,
  			'area'             => $area,
  			'group'            => $mesin,
  			'item_uuid' => $kegiatan_uuid,
  			'item'             => $kegiatan_name,
  			'checklist'        => isset($kegiatan[$kegiatan_item->uuid]) ? $kegiatan[$kegiatan_item->uuid] : 0,
  			'paraf_prod'       => null,
  			'paraf_qc'         => null,
  			'keterangan'       => isset($keterangan[$kegiatan_item->uuid]) ? $keterangan[$kegiatan_item->uuid] : ''
  		];

  		$this->db->insert('cek_mesin', $data);
  	}

  	return ($this->db->affected_rows() > 0);
  }
  public function get_area_data($t_planning_uuid)
  {
  	$this->db->select('*, uuid as cek_uuid');
  	$this->db->from('cek_mesin');
  	$this->db->where('t_planning_uuid', $t_planning_uuid);
  	$data = $this->db->get()->result();
  	foreach ($data as $val) {
  		if ($val->checklist == 2) {
  			$val->check_ya = '<b>&check;</b>';
  			$val->check_tdk = '-';
  		} else {
  			$val->check_ya = '-';
  			$val->check_tdk = '<b>&#10005;</b>';
  		}
  	}
  	return $data;
  }
  public function get_data_by_uuid($uuid)
  {
  	return $this->db->get_where('cek_mesin', array('uuid' => $uuid))->row();
  }

  public function update_paraf_prod($uuid)
  {

  	$data = array(
  		'paraf_prod' => $this->auth_model->current_user()->username
  	);

  	$this->db->update('cek_mesin', $data, array('uuid' => $uuid ));
  	return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function get_data($t_planning_uuid) {
  	$this->db->select('c.*, p.varian, p.tanggal');
  	$this->db->from('cek_mesin c');
  	$this->db->join('t_planning p', 'p.uuid = c.t_planning_uuid', 'left');
  	$this->db->order_by('c.area, c.group, c.item');
  	$this->db->where('c.t_planning_uuid', $t_planning_uuid);
  	$query = $this->db->get()->result_array();

  	foreach ($query as &$val) {
  		if ($val['tanggal'] !== null) {
  			$val['tgl'] = date('d M Y', strtotime($val['tanggal']));
  		} else {
  			$val['tgl'] = null;
  		}
  		$val['vrn'] = ($val['varian'] == 1) ? 'Okey' : (
  			($val['varian'] == 2) ? 'Champ Ayam' : (
  				($val['varian'] == 3) ? 'Champ Sapi' : (
  					($val['varian'] == 4) ? 'Champ Otak-Otak' : ''
  				)));

  		if ($val['checklist'] == 2) {
  			$val['cek_ya'] = '&#x2713;';
  			$val['cek_tdk'] = '-';
  		} else {
  			$val['cek_ya'] = '-';
  			$val['cek_tdk'] = '&#x2713;';
  		}
  		if ($val['paraf_prod'] !== null) {
  			$val['prf_prod'] = '&#x2713;';
  			
  		} else {
  			$val['prf_prod'] = '-';
  		}
  		if ($val['paraf_qc'] !== null) {
  			$val['prf_qc'] = '&#x2713;';
  			
  		} else {
  			$val['prf_qc'] = '-';
  		}
  	}


  	return $query;
  }

  public function get_item()
  {
  	$this->db->select('c.*, a.nama_area, m.nama_mesin');
  	$this->db->from('item_cekmesin c');
  	$this->db->join('area a', 'a.uuid = c.area_uuid', 'left');
  	$this->db->join('mesin m', 'm.uuid = c.mesin_uuid', 'left');
  	$this->db->order_by('c.created_at', 'DESC');
  	$data = $this->db->get()->result();
  	return $data;
  }

  public function insert_item_batch()
  {
  	$area     = $this->input->post('area');
  	$mesin    = $this->input->post('mesin');
  	$kegiatan = $this->input->post('kegiatan');

  	$batch = [];

  	foreach ($kegiatan as $item) {
  		if (trim($item) == '') continue;

  		$batch[] = [
  			'uuid'       => Uuid::uuid4()->toString(),
  			'area_uuid'  => $area,
  			'mesin_uuid' => $mesin,
  			'user_uuid'  => $this->auth_model->current_user()->uuid,
  			'username'   => $this->auth_model->current_user()->username,
  			'kegiatan'   => trim($item)
  		];
  	}

  	if (empty($batch)) return false;

  	$this->db->trans_start();
  	$this->db->insert_batch('item_cekmesin', $batch);
  	$this->db->trans_complete();

  	return $this->db->trans_status();
  }

  public function update_item($uuid)
  {
  	
  	$area 		= $this->input->post('area');
  	$mesin 		= $this->input->post('mesin');
  	$mesin_name 	= $this->input->post('mesin_name');
  	$kegiatan 		= $this->input->post('kegiatan');

  	$data = array(
  		
  		'area_uuid' 	=> $area,
  		'mesin_uuid' 	=> $mesin,
  		'user_uuid'           => $this->auth_model->current_user()->uuid,
  		'username'          => $this->auth_model->current_user()->username,
  		'kegiatan' 		=> $kegiatan
  	);

  	$this->db->update('item_cekmesin', $data,  array('uuid' => $uuid ));
  	return ($this->db->affected_rows() > 0) ? true : false;

  }

  public function get_by_uuid($uuid)
  {
  	return $this->db->get_where('item_cekmesin', array('uuid' => $uuid ))->row();
  }

  public function delete_item($uuid)
  {
  	$this->db->where('uuid', $uuid);
  	$this->db->delete('item_cekmesin');
  }

  public function get_kegiatan_by_mesin($mesin_uuid)
  {

  	return $this->db->get_where('item_cekmesin', array('mesin_uuid' => $mesin_uuid))->result();

  }

}