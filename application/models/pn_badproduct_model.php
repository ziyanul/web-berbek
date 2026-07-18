<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class pn_badproduct_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		//$this->dberetort = $this->load->database('e-retort', TRUE);
	}

	public function rules()
	{
		return [
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'	
			],
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'	
			],
			[
				'field' => 'kode',
				'label' => 'Kode Produk',
				'rules' => 'required'	
			],
			[
				'field' => 'qty_kg',
				'label' => 'Qty Produk',
				'rules' => 'required'	
			],
		];
	}

	public function rules1()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'	
			],
			[
				'field' => 'kode',
				'label' => 'Kode Produk',
				'rules' => 'required'	
			],
			[
				'field' => 'qty_kg',
				'label' => 'Qty Produk',
				'rules' => 'required'	
			],
		];
	}


	

	public function get_all()
	{
		$this->db->select('pn.*, DATE(pn.created_at) as tgl');
		$this->db->from('pn_badpro pn');
		$this->db->order_by('pn.created_at', 'DESC');
		$this->db->group_by('tgl');
		$data= $this->db->get()->result();
		foreach ($data as $v) {
			$v->tanggal=date('d M Y',strtotime($v->created_at));
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
		$data = $this->db->get_where('pn_badpro', array('uuid' => $uuid ))->row();
		$data->tanggal=date('Y-m-d',strtotime($data->created_at));
		return $data;
	}

	
	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$shift = $this->input->post('shift');
		$varian_uuid = $this->input->post('varian');
		$varian = $this->input->post('varian_name');	
		$kode = $this->input->post('kode');
		$qty_kg = $this->input->post('qty_kg');
		$data = array(
			'uuid' 			=> $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'shift' 		=> $shift,
			'varian_uuid' 	=> $varian_uuid,
			'varian' 		=> $varian,
			'kode_produksi' => $kode,
			'qty_kg' 		=> $qty_kg,
		);	

		$this->db->insert('pn_badpro', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$varian = $this->input->post('varian');
		$kode = $this->input->post('kode');
		$qty_kg = $this->input->post('qty_kg');
		$data = array(
			'varian_uuid' 			=> $varian,
			'kode_produksi' 		=> $kode,
			'qty_kg' 				=> $qty_kg,	
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('pn_badpro', $data, array('uuid' => $uuid)); 
		return ($this->db->affected_rows() > 0) ? true : false; 
	}

	public function get_all_varian() {
		$query = $this->db->get('varian');
		return $query->result();
	}

	public function get_item_name($uuid)
	{
		return $this->db->get_where('varian', array('uuid' => $uuid))->row();
	}

	public function get_by_tanggal($tanggal,$shift)
	{
		$this->db->select('pn.*,v.varian as varian, us.fullname as username');
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = pn.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u1.fullname FROM users u1 WHERE u1.uuid = pn.kr_uuid) AS kr_name", false);
		$this->db->from('pn_badpro pn');
		$this->db->join('varian v', 'v.uuid=pn.varian_uuid', 'left');
		$this->db->join('users us', 'us.uuid=pn.user_uuid', 'left');
		$this->db->order_by('pn.created_at', 'ASC');
		$this->db->where('DATE(pn.created_at)', $tanggal);
		$this->db->where('pn.shift', $shift);
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tgl = date('d M Y', strtotime($v->created_at));
			$v->tanggal = date('Y-m-d', strtotime($v->created_at));


			if($v->shift==1){
				$v->shift_name='Pagi';
			} else if($v->shift==2){
				$v->shift_name='Sore';
			} else if($v->shift==3){
				$v->shift_name='Malam';
			} else {
				$v->shift_name='-';
			}

			if ($v->qc_id==0) {
				$v->acc_qc='-';
			} else{
				$v->acc_qc= '<i class="fa fa-check fa-lg text-success"></i>';
			}
		}
		return $data;	
	}

	public function approval_kr($tanggal, $shift)
	{
		$data = [
			'kr_uuid' => $this->auth_model->current_user()->uuid, // UUID supervisor
		];
		$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") =', $tanggal); // Update berdasarkan tanggal
		$this->db->where('shift', $shift); // Update berdasarkan shift
		$this->db->update('pn_badpro', $data);

		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}

	public function approval_spv($tanggal, $shift)
	{
		$data = [
			'spv_uuid' => $this->auth_model->current_user()->uuid, // UUID supervisor
		];
		$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") =', $tanggal); // Update berdasarkan tanggal
		$this->db->where('shift', $shift); // Update berdasarkan shift
		$this->db->update('pn_badpro', $data);

		return $this->db->affected_rows() > 0; // Pastikan ada baris yang diperbarui
	}
}