<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Pg_varian_model extends CI_Model 
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
				'label' => 'area',
				'rules' => 'required'	
			],
			[
				'field' => 'shift',
				'label' => 'shift',
				'rules' => 'required'	
			],
			[
				'field' => 'varian_sortasi',
				'label' => 'varian_sortasi',
				'rules' => 'required'	
			],
			[
				'field' => 'kode_batch_sortasi',
				'label' => 'kode_batch_sortasi',
				'rules' => 'required'	
			],
			[
				'field' => 'varian_ke_sortasi',
				'label' => 'Pergantian_Varian',
				'rules' => 'required'	
			],
			[
				'field' => 'kode_batch_ke_sortasi',
				'label' => 'Pergantian_Varian',
				'rules' => 'required'	
			],
			[
				'field' => 'kondisi',
				'label' => 'Kondisi',
				'rules' => 'required'	
			]
		];
	}

	

	public function get_all()
	{
		$this->db->select('pg.uuid, DATE(pg.created_at) as tgl, pg.shift, pg.area');
		$this->db->from('pg_varian pg');
		$this->db->order_by('pg.created_at', 'DESC');
		$this->db->group_by('tgl, area, shift, uuid, created_at');
		$data= $this->db->get()->result();
		foreach ($data as $v) {
			$v->tanggal=date('d M Y',strtotime($v->tgl));
			if($v->area==1){
				$v->area_name='Retort';
			} else{
				$v->area_name='Packing';
			}
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

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('pg_varian', array('uuid' => $uuid ))->row();
		$data->tanggal=date('Y-m-d',strtotime($data->created_at));
		return $data;
	}

	
	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$area = $this->input->post('area');
		$shift = $this->input->post('shift');
		$varian_1 = $this->input->post('varian_sortasi');
		$batch_1 = $this->input->post('kode_batch_sortasi');
		$varian_2 = $this->input->post('varian_ke_sortasi');
		$batch_2 = $this->input->post('kode_batch_ke_sortasi');
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');


		$data = array(
			'uuid' => $uuid,
			'area' => $area,
			'user_uuid'     		=> $this->auth_model->current_user()->uuid,
			'shift' 		 		=> $shift,
			'varian_1_uuid' 		=> $varian_1,
			'batch_1' 				=> $batch_1,
			'varian_2_uuid' 		=> $varian_2,
			'batch_2' 				=> $batch_2,
			'kondisi' 				=> $kondisi,
			'keterangan' 			=> $keterangan
		);	

		$this->db->insert('pg_varian', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$area = $this->input->post('area');
		$shift = $this->input->post('shift');
		$varian_1 = $this->input->post('varian_sortasi');
		$batch_1 = $this->input->post('kode_batch_sortasi');
		$varian_2 = $this->input->post('varian_ke_sortasi');
		$batch_2 = $this->input->post('kode_batch_ke_sortasi');
		$kondisi = $this->input->post('kondisi');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			
			'area' => $area,
			'shift' 		 		=> $shift,
			'varian_1_uuid' 		=> $varian_1,
			'batch_1' 				=> $batch_1,
			'varian_2_uuid' 		=> $varian_2,
			'batch_2' 				=> $batch_2,
			'kondisi' 				=> $kondisi,
			'keterangan' 			=> $keterangan,
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('pg_varian', $data, array('uuid' => $uuid)); 
		return ($this->db->affected_rows() > 0) ? true : false; 
	}

	public function get_all_varian() {
        $query = $this->db->get('varian');
        return $query->result();
    }

	public function get_by_tanggal($tanggal, $shift, $area)
	{
		$this->db->select('p.*,v.varian as varian_1, v.keterangan as keterangan_1, v2.varian as varian_2, v2.keterangan as keterangan_2, us.username');
		$this->db->from('pg_varian p');
		$this->db->join('varian v', 'v.uuid=p.varian_1_uuid', 'left');
		$this->db->join('varian v2', 'v2.uuid=p.varian_2_uuid', 'left');
		$this->db->join('users us', 'us.uuid=p.user_uuid', 'left');
		$this->db->order_by('p.created_at', 'ASC');
		$this->db->where('DATE(p.created_at)', $tanggal);
		$this->db->where('shift', $shift);
		$this->db->where('area', $area);
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tgl = date('d M Y', strtotime($v->created_at));
		if($v->area==1){
			$v->area_name='Area Retort';
		} else{
			$v->area_name='Area Packing';
		} 

		if ($v->kondisi==1){
			$v->kondisi1='&check;';
			$v->kondisi2='-';
		} else if ($v->kondisi==2){
			$v->kondisi2='&check;';
			$v->kondisi1='-';	
		}

		if ($v->qc_id==0) {
			$v->acc_qc='-';
		} else{
			$v->acc_qc='✓';
		}
		

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
}