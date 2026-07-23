<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Pbtajam_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('area_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
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
				'field' => 'pbtajam',
				'label' => 'Benda Tajam',
				'rules' => 'required'
			],
		];
	}
	
	public function rules1()
	{
		return [
			[
				'field' => 'jenis',
				'label' => 'Jenis Benda',
				'rules' => 'required'
			],
			[
				'field' => 'kode',
				'label' => 'Kode Benda',
				'rules' => 'required'
			]
		];
	}

	public function rules2()
	{
		return [
			
			[
				'field' => 'area',
				'label' => 'Nama Area',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			]
		];
	}

	public function rules3()
	{
		return [
			
			[
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => 'required'
			]
		];
	}

	public function get_all()
	{
		$this->db->select('j.*, us.username, a.nama_area');
		$this->db->from('jenis_btajam j');
		$this->db->join('area a', 'a.uuid = j.area_uuid', 'left');
		$this->db->join('users us', 'us.uuid = j.user_uuid', 'left');
		$this->db->order_by('a.nama_area', 'ASC');
		$this->db->order_by('j.created_at', 'ASC');
		$data = $this->db->get()->result();

		$result = [];
		foreach ($data as $item) {
			$item->tanggal = date('d M Y', strtotime($item->created_at));
			$result[$item->nama_area][$item->tanggal][] = $item;
		}
		return $result;
	}

	public function get_all_kode()
	{
		$this->db->select('k.*, us.username, j.jenis_benda, j.uuid as jenis_btajam_uuid, a.nama_area');
		$this->db->from('kode_btajam k');
		$this->db->join('jenis_btajam j', 'k.jenis_btajam_uuid = j.uuid', 'left');
		$this->db->join('users us', 'k.user_uuid = us.uuid', 'left');
		$this->db->join('area a', 'j.area_uuid = a.uuid', 'left');
		$this->db->order_by('a.nama_area', 'ASC');
		$this->db->order_by('j.jenis_benda', 'ASC');
		$this->db->order_by('k.created_at', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tgl = date('d M Y', strtotime($v->created_at));
		}
		return $data;
	}

	public function get_all_form()
	{
		$this->db->select('f.*,us.username, a.nama_area, j.jenis_benda, k.kode_benda');
		$this->db->from('f_btajam f');
		$this->db->join('area a', 'a.uuid=f.area_uuid', 'left');
		$this->db->join('jenis_btajam j', 'j.uuid=f.jenis_btajam_uuid', 'left');
		$this->db->join('kode_btajam k', 'k.uuid=f.kode_btajam_uuid', 'left');
		$this->db->join('users us', 'us.uuid=f.user_uuid', 'left');
		$this->db->order_by('f.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tanggal=date('d M Y',strtotime($v->created_at));
			$v->tgl=date('Y-m-d',strtotime($v->created_at));
			if($v->shift==1){
				$v->shift_name='Pagi';
			} else if($v->shift==2){
				$v->shift_name='Sore';
			} else if($v->shift==3){
				$v->shift_name='Malam';
			} else {
				$v->shift_name='-';
			}
		}
		return $data;
	}

	public function insert_jenis()
	{
		$uuid = Uuid::uuid4()->toString();
		$area_uuid = $this->input->post('area');
		$pbtajam = $this->input->post('pbtajam');

		$data = array(
			'uuid'          => $uuid,
			'area_uuid'     => $area_uuid,
			'jenis_benda'  	=> $pbtajam,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);	

		$this->db->insert('jenis_btajam', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_kode()
	{
		$uuid = Uuid::uuid4()->toString();
		$kode = $this->input->post('kode');
		$jenis_uuid = $this->input->post('jenis');

		$data = array(
			'uuid'          		=> $uuid,
			'jenis_btajam_uuid'     => $jenis_uuid,
			'kode_benda' 			=> $kode,
			'user_uuid'     		=> $this->auth_model->current_user()->uuid
		);	
		$this->db->insert('kode_btajam', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_form()
	{	
		$shift = $this->input->post('shift');
		$area_uuid = $this->input->post('area');
		$f_kode_uuid = $this->input->post('kode'); 
		$keterangan = $this->input->post('keterangan');

		$this->db->select('a.*,b.uuid as kode_btajam_uuid');
		$this->db->from('jenis_btajam a');
		$this->db->join('kode_btajam b', 'a.uuid=b.jenis_btajam_uuid');
		$this->db->where('a.area_uuid', $area_uuid);
		$data_benda = $this->db->get()->result();

		$data_kode = [];
		foreach ($data_benda as $key => $row) {
			$uuid = Uuid::uuid4()->toString();
			$data_kode_tmp = array(
				'uuid' 				=> $uuid,
				'area_uuid' 		=> $area_uuid,
				'shift' 			=> $shift,
				'user_uuid' 		=> $this->auth_model->current_user()->uuid,
				'jenis_btajam_uuid' => $row->uuid,
				'kode_btajam_uuid' => $row->kode_btajam_uuid,
				'keterangan' => $keterangan[$row->kode_btajam_uuid],
				'kondisi' =>  $f_kode_uuid[$row->kode_btajam_uuid]
			);

			array_push($data_kode, $data_kode_tmp);
		}

		$this->db->trans_begin();
		$this->db->insert_batch('f_btajam', $data_kode);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();;
			return true;
		}
	}

	public function update($uuid)
	{
		$area = $this->input->post('area');
		$pbtajam = $this->input->post('pbtajam');
		$data = array(
			'area_uuid'     => $area,
			'jenis_benda'   => $pbtajam,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'   => date('Y-m-d h:i:s')
		);	

		$this->db->update('jenis_btajam', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_kode($uuid)
	{
		$kode = $this->input->post('kode');
		$data = array(
			
			'kode_benda'   => $kode,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'   => date('Y-m-d h:i:s')
		);	
		$this->db->update('kode_btajam', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_form($uuid)
	{
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'kondisi'       => $kondisi,
			'keterangan'    => $keterangan,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'   => date('Y-m-d h:i:s')
		);

		$this->db->update('f_btajam', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}


	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('jenis_btajam', array('uuid' => $uuid ))->row();
	}

	public function get_kode_by_uuid($uuid)
	{
		return $this->db->get_where('kode_btajam', array('uuid' => $uuid ))->row();
	}
	public function get_form_by_uuid($uuid)
	{
		$this->db->select('fb.*, a.nama_area, j.jenis_benda, k.kode_benda , us.username');
		$this->db->from('f_btajam fb');
		$this->db->join('area a', 'a.uuid=fb.area_uuid', 'left');
		$this->db->join('jenis_btajam j', 'j.uuid=fb.jenis_btajam_uuid', 'left');
		$this->db->join('kode_btajam k', 'k.uuid=fb.kode_btajam_uuid', 'left');
		$this->db->join('users us', 'us.uuid=fb.user_uuid', 'left');
		$this->db->order_by('fb.created_at', 'ASC');
		$this->db->group_by('kode_benda');
		$this->db->where('fb.uuid', $uuid);
		$data = $this->db->get()->row();
		$data->tgl=DATE('Y-m-d',strtotime($data->created_at));

		if($data->shift==1){
			$data->shift_name='Pagi';
		} else if($data->shift==2){
			$data->shift_name='Sore';
		} else if($data->shift==3){
			$data->shift_name='Malam';
		} else {
			$data->shift_name='-';
		}
		return $data;
	}

	public function get_by_tanggal($tanggal, $shift)
	{
		$this->db->select('f.*,j.jenis_benda, k.kode_benda, a.nama_area, us.fullname');
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = f.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = f.frm_uuid) AS leader", false);
		$this->db->from('f_btajam f');
		$this->db->join('jenis_btajam j', 'j.uuid=f.jenis_btajam_uuid', 'left');
		$this->db->join('area a', 'a.uuid=f.area_uuid', 'left');
		$this->db->join('kode_btajam k', 'k.uuid=f.kode_btajam_uuid', 'left');
		$this->db->join('users us', 'us.uuid=f.user_uuid', 'left');
		$this->db->where('DATE(f.created_at)', $tanggal);
		$this->db->where('shift', $shift);
		$this->db->order_by('a.nama_area, j.jenis_benda, k.kode_benda');
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tgl = date('d M Y', strtotime($v->created_at));
			$v->tanggal=date('Y-m-d',strtotime($v->created_at));
			if($v->shift==1){
				$v->shift_name='Pagi';
			} else if($v->shift==2){
				$v->shift_name='Sore';
			} else if($v->shift==3){
				$v->shift_name='Malam';
			} else {
				$v->shift_name='-';
			}
			if ($v->kondisi==1){
				$v->kondisi1='<i class="fa fa-check fa-lg text-success"></i>';
				$v->kondisi2='-';
				$v->kondisi3='-';
			} else if ($v->kondisi==2){
				$v->kondisi1='-';
				$v->kondisi2='<i class="fa fa-check fa-lg text-success"></i>';
				$v->kondisi3='-';
			}else if ($v->kondisi==3){
				$v->kondisi1='-';
				$v->kondisi2='-';
				$v->kondisi3='<i class="fa fa-check fa-lg text-success"></i>';
			}

			if ($v->kondisi==1){
				$v->kondisi_1='&check;';
				$v->kondisi_2='-';
				$v->kondisi_3='-';
			} else if ($v->kondisi==2){
				$v->kondisi_1='-';
				$v->kondisi_2='&check;';
				$v->kondisi_3='-';
			}else if ($v->kondisi==3){
				$v->kondisi_1='-';
				$v->kondisi_2='-';
				$v->kondisi_3='&check;';
			}
		}
		return $data;
	}

	public function get_by_jenis($area_uuid) {
		return $this->db->get_where('jenis_btajam', array('area_uuid' => $area_uuid))->result();
	}

	public function get_by_nav_kode($uuid) 
	{
		$this->db->select('k.*, j.area_uuid');
		$this->db->from('kode_btajam k');
		$this->db->join('jenis_btajam j', 'j.uuid=k.jenis_btajam_uuid', 'left');
		$this->db->where('k.uuid', $uuid);
		$data = $this->db->get()->row();
		return $data;
	}

	public function get_kode_by_area($area_uuid, $shift, $tanggal)
	{
		$this->db->select('a.*, b.*, c.*');
		$this->db->from('f_btajam a');
		$this->db->join('jenis_btajam b', 'a.jenis_btajam_uuid=b.uuid');
		$this->db->join('kode_btajam c', 'a.kode_btajam_uuid=c.uuid');
		$this->db->where(array('a.area_uuid' => $area_uuid, 'a.shift' => $shift, 'DATE(a.created_at)' => $tanggal));
		$existing_data = $this->db->get()->result();

		$data = [];

		if (empty($existing_data)) {
			$this->db->select('a.*, b.*');
			$this->db->from('jenis_btajam a');
			$this->db->join('kode_btajam b', 'a.uuid=b.jenis_btajam_uuid');
			$this->db->where('a.area_uuid', $area_uuid);
			$data = $this->db->get()->result();
		}

		return [
			'existing_data' => $existing_data,
			'data' => $data
		];
	}
	public function approval($tanggal, $shift, $role)
	{
		$data = [];
		if ($role === '1') {
			$data['spv_uuid'] = $this->auth_model->current_user()->uuid;
		} elseif ($role === '2') {
			$data['frm_uuid'] = $this->auth_model->current_user()->uuid;
		}
		$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") =', $tanggal); // Update berdasarkan tanggal
		$this->db->where('shift', $shift); // Update berdasarkan shift
		$this->db->update('f_btajam', $data);

		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}
}