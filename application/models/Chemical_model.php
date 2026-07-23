<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Chemical_model extends CI_Model
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
				'field' => 'nama_chemical',
				'label' => 'Chemical',
				'rules' => 'required'
			],
			[
				'field' => 'chemical_id',
				'label' => 'Kode Chemical',
				'rules' => 'required'
			],
			[
				'field' => 'stock',
				'label' => 'Jumlah Chemical',
				'rules' => 'required'
			],
			[
				'field' => 'persen',
				'label' => 'Perbandingan',
				'rules' => 'required'
			]
		];
	}

	public function rules1()
	{
		return [
			[
				'field' => 'chemical_stock',
				'label' => 'Ketersediaan Chemical',
				'rules' => 'required'
			]
		];
	}

	public function rules_chemical()
	{
		return [
			[
				'field' => 'stock',
				'label' => 'Jumlah Stock',
				'rules' => 'required'
			]
		];
	}

	public function rules_master()
	{
		return [
			[
				'field' => 'nama_chemical',
				'label' => 'Nama Chemical',
				'rules' => 'required'
			]
		];
	}

	public function rules_persen()
	{
		return [
			[
				'field' => 'persentase',
				'label' => 'Persentase',
				'rules' => 'required'
			],
			[
				'field' => 'satuan',
				'label' => 'Satuan',
				'rules' => 'required'
			]
		];
	}

	public function get_chemical_stock()
	{
		$this->db->select('k.uuid, k.chemical_master_uuid, k.kode_chemical, k.persentase, k.satuan, c.chemical_name');
		$this->db->select('(SELECT SUM(stock_murni) FROM chemical_stock s WHERE s.chemical_master_uuid = c.uuid) as total_stock', false);
		$this->db->select('(SELECT SUM(chemical_used) FROM larutan l WHERE l.kode_chemical_uuid = k.uuid) as murni_used', false);
		$this->db->select('(SELECT SUM(larutan) FROM larutan l WHERE l.kode_chemical_uuid = k.uuid) as stock_encer', false);
		$this->db->select('(SELECT SUM(used) FROM larutan_used lu WHERE lu.kode_chemical_uuid = k.uuid) as encer_used', false);
		$this->db->from('kode_chemical k');
		$this->db->join('chemical_master c', 'c.uuid = k.chemical_master_uuid', 'left');
		$this->db->order_by('k.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			if ($val->satuan == 1) {
				$val->stn = '%';
			} else{
				$val->stn = 'Ppm';
			}
		}
		return $data;
	}

	// public function get_all_chemical()
	// {
	// 	$this->db->select('p.*, m.chemical_name');
	// 	$this->db->select('(SELECT SUM(stock) FROM chemical c WHERE c.chemical_master_uuid = l.chemical_uuid) as total_stock', false);
	// 	$this->db->select('(SELECT SUM(chemical_used) FROM larutan la WHERE la.chemical_uuid = l.chemical_uuid) as total_used', false);
	// 	$this->db->select('(SELECT SUM(stock_encer) FROM larutan la WHERE la.chemical_uuid = l.chemical_uuid) as total_encer', false);
	// 	$this->db->select('(SELECT SUM(used) FROM larutan_used lu WHERE lu.kode_chemical = p.kode_chemical) as encer_used', false);
	// 	$this->db->from('larutan l');
	// 	$this->db->join('persentase p', 'l.chemical_id = p.uuid', 'left');
	// 	$this->db->join('chemical_master m', 'm.uuid = l.chemical_uuid', 'left');
	// 	$this->db->where('m.chemical_name IS NOT NULL');
	// 	$this->db->group_by('chemical_uuid, chemical_id');
	// 	$data = $this->db->get()->result();
	// 	return $data;
	// }

	public function get_chemical_by_uuid($uuid)
	{
		$this->db->select('c.stock, c.uuid, c.created_at');
		$this->db->from('chemical c');
		$this->db->where('c.chemical_master_uuid', $uuid);
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}
		return $data;
	}
	
	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$chemical_id = $this->input->post('chemical_id');
		$chemical_uuid = $this->input->post('nama_chemical');
		$stock = $this->input->post('stock');
		$chemical_name = $this->input->post('chemical_name');
		$banding = $this->input->post('banding');
		$persen = $this->input->post('persen');
		if ($persen == 1) {
			$perbanding = $banding;
		} else {
			$perbanding = $banding / 10000;
		}
		$data = array(
			'uuid'          => $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'     => $this->auth_model->current_user()->username,
			'chemical_id'      => $chemical_id,
			'chemical_master_uuid' => $chemical_uuid,
			'nama_chemical'        => $chemical_name,
			'stock'        => $stock,
			'banding'        => $perbanding
		);
		$this->db->insert('chemical', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_master()
	{
		$uuid = Uuid::uuid4()->toString();
		$nama_chemical = $this->input->post('nama_chemical');
		$data = array(
			'uuid'          => $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'      => $this->auth_model->current_user()->username,
			'chemical_name' => $nama_chemical
		);
		$this->db->insert('chemical_master', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_persen()
	{
		$uuid = Uuid::uuid4()->toString();
		$persentase = $this->input->post('persentase');
		$chemical = $this->input->post('chemical');
		$satuan = $this->input->post('satuan');
		$kode_chemical = $this->input->post('kode_chemical');
		if ($satuan == 1) {
			$banding = $persentase;
		} elseif ($satuan == 2) {
			$banding = $persentase / 10000;
		}
		$data = array(
			'uuid'          => $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'      => $this->auth_model->current_user()->username,
			'persentase' => $persentase,
			'satuan' => $satuan,
			'banding' => $banding,
			'kode_chemical' => $kode_chemical,
			'chemical_master_uuid' => $chemical
		);

		$this->db->insert('kode_chemical', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_persen($uuid)
	{

		$persentase = $this->input->post('persentase');
		$chemical = $this->input->post('chemical');
		$satuan = $this->input->post('satuan');
		$kode_chemical = $this->input->post('kode_chemical');
		if ($satuan == 1) {
			$banding = $persentase;
		} elseif ($satuan == 2) {
			$banding = $persentase / 10000;
		}

		$data = array(
			'persentase' => $persentase,
			'satuan' => $satuan,
			'banding' => $banding,
			'kode_chemical' => $kode_chemical,
			'chemical_master_uuid' => $chemical,
			'modified_at' => date('Y-m-d h:i:s')
		);

		$this->db->update('kode_chemical', $data, array('uuid' => $uuid)); 
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_pengenceran()
	{
		$uuid 		= Uuid::uuid4()->toString();
		$chemical_stock 	= $this->input->post('chemical_stock');
		$nama_chemical 	= $this->input->post('nama_chemical');
		$stock = $this->input->post('larutan');
		$kode = $this->input->post('kode_chemical');
		$data 		= array(
			'uuid' 	=> $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'      => $this->auth_model->current_user()->username,

			'kode_chemical_uuid' => $kode,
			'chemical_used' => $chemical_stock,
			'larutan' => $stock
		);
		$this->db->insert('larutan', $data);		
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all_nama()
	{
		$this->db->select('m.*');
		$this->db->select('(SELECT SUM(c.stock_murni) FROM chemical_stock c WHERE c.chemical_master_uuid = m.uuid) as chemical_stock', false);
		// $this->db->select('(SELECT SUM(l.chemical_used) FROM larutan l WHERE l.chemical_uuid = m.uuid) as chemical_used', false);
		$this->db->from('chemical_master m');
		$this->db->order_by('m.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->chemical_used = 0;
			$val->sisa_chemical = $val->chemical_stock - $val->chemical_used;


		}
		return $data;
	}

	public function get_persen()
	{
		$this->db->select('k.*, c.chemical_name');
		$this->db->from('kode_chemical k');
		$this->db->join('chemical_master c', 'c.uuid = k.chemical_master_uuid', 'left');
		$this->db->order_by('k.kode_chemical', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			if ($val->satuan == 1) {
				$val->satuan = '<span> %</span>';
			} else {
				$val->satuan = '<span> Ppm</span>';
			}
		}

		return $data;
	}

	public function get_chemical_nama($uuid)
	{
		return $this->db->get_where('chemical_master', array('uuid' => $uuid))->row();
	}

	public function insert_chemical($uuid)
	{
		$data = $this->db->get_where('chemical_master', array('uuid' => $uuid))->row();
		$uuid = Uuid::uuid4()->toString();


		$stock = $this->input->post('stock');
		$data = array(
			'uuid'          => $uuid,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'username'     => $this->auth_model->current_user()->username,

			'chemical_master_uuid' => $data->uuid,
			'stock_murni'        => $stock

		);

		$this->db->insert('chemical_stock', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all_chemical_data()
	{
		return $this->db->get('chemical_stock')->result();
	}

	public function get_all_chemical_master_data()
	{
		return $this->db->get('chemical_master')->result();
	}
	
	public function get_kode_chemical()
	{
		$this->db->select('k.*, c.chemical_name');
		$this->db->from('kode_chemical k');
		$this->db->join('chemical_master c', 'c.uuid = k.chemical_master_uuid', 'left');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			if ($val->satuan == 1) {
				$val->stn = '%';
			} else{
				$val->stn = 'Ppm';
			}
		}

		return $data;
	}

	public function get_persen_name($uuid)
	{
		$data = $this->db->get_where('kode_chemical', array('uuid' => $uuid))->row();

		if ($data->satuan == 1) {
			$data->satu = '%';
		} else {
			$data->satu = ' Ppm';
		}

		return $data;
	}

	public function delete_persen($uuid)
	{
		$this->db->where('uuid', $uuid);
		$this->db->delete('kode_chemical');
	}

	public function delete_chemical($uuid)
	{
		$this->db->where('uuid', $uuid);
		$this->db->delete('chemical_master');
	}

	public function get_larutan_data()
	{
		$this->db->select('created_at, DATE_FORMAT(created_at, "%Y-%m-%d") as tanggal');
		$this->db->order_by('created_at', 'DESC');
		$this->db->group_by('tanggal, created_at');
		$data = $this->db->get('larutan')->result();

		return $data;
	}

	public function get_pengenceran_data($tanggal)
{
    // Menyesuaikan tanggal jika waktu < 06:00:00
    $sql_date_adjusted = "CASE 
        WHEN TIME(l.created_at) < '06:00:00' THEN DATE_SUB('$tanggal', INTERVAL 1 DAY)
        ELSE '$tanggal'
    END";

    // Membuat query untuk join tabel dan menampilkan chemical_name
    $this->db->select('l.*, cm.chemical_name');
    $this->db->from('larutan l');
    $this->db->join('kode_chemical kc', 'kc.uuid = l.kode_chemical_uuid', 'left');
    $this->db->join('chemical_master cm', 'cm.uuid = kc.chemical_master_uuid', 'left');
    
    // Kondisi WHERE untuk menyesuaikan tanggal berdasarkan waktu
    $this->db->where("DATE(l.created_at) = ($sql_date_adjusted)", NULL, FALSE);
    
    // Mendapatkan hasil query
    $data = $this->db->get()->result();
    foreach ($data as $val) {
    	$val->tgl = date('d M Y', strtotime($val->created_at));
    }
    return $data;
}


}