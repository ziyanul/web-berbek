<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class bahan_sanitasi_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('relasi_uuid');
		$this->load->model('auth_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
		$this->dbwarehouse = $this->load->database('warehouse', TRUE);
		$this->sanitasi = $this->config->item('sanitasi_uuid');
	}

	public function rules()
	{
		return [
			[
				'field' => 'no_reservasi',
				'label' => 'Jenis Barang',
				'rules' => 'required'	
			],
			[
				'field' => 'item_barang',
				'label' => 'Item Barang',
				'rules' => 'required'	
			],
			[
				'field' => 'qty_reservasi',
				'label' => 'Qty Reservasi',
				'rules' => 'required'	
			],
		];
	}

	public function get_all()
	{
		$this->db->select('b.*');
		$this->db->from('bahanbaku b');
		$this->db->where('area_uuid', $this->sanitasi);
		$this->db->order_by('b.created_at, no_reservasi', 'DESC');
		$this->db->group_by('DATE_FORMAT(b.created_at, "%d %M %Y")');
		
		$data = $this->db->get()->result();
		
		foreach ($data as $v) {
			$v->tanggal = date('d M Y', strtotime($v->created_at));
			$v->tgl = date('Y-m-d', strtotime($v->created_at));
		}
		return $data;
	}

	public function get_all_sanitasi($tanggal)
	{
    $this->db->select('a.nama_area, bb.*, u.fullname, bh.satuan');
    $this->db->from('bahanbaku bb');
    
    // Load the warehouse database connection
    $warehouse_db_name = $this->dbwarehouse->database;

    // Add the subquery for total qty_dikirim from bahanbaku_wh in the warehouse database
    $this->db->select("(SELECT SUM(wh.qty_dikirim) FROM `{$warehouse_db_name}`.`bahanbaku_wh` wh WHERE wh.bahanbaku_mp_uuid = bb.uuid AND wh.penerima_uuid IS NOT NULL AND wh.penerima_uuid <> 0) AS total_kirim", false);
    $this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = bb.spv_uuid) AS spv", false);
    $this->db->join($this->dberetort->database. '.area a', 'a.uuid = bb.area_uuid', 'left');
	$this->db->join("{$warehouse_db_name}.master_bahanbaku bh", 'bh.uuid = bb.item_barang_uuid', 'left');
    $this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
    $this->db->where('bb.area_uuid', $this->sanitasi);
    $this->db->where('DATE_FORMAT(bb.created_at, "%Y-%m-%d") =', $tanggal);
	$this->db->order_by('bb.created_at', 'DESC');
    $data = $this->db->get()->result();

	$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs'];
    
    foreach ($data as $val) {
        $val->tanggal = date('Y-m-d', strtotime($val->created_at));
        $val->tgl = date('d M Y', strtotime($val->created_at));
		$val->jam = date('H:i:s', strtotime($val->created_at));
		$val->satuan = $satuan_map[$val->satuan] ?? '-';
    }

    return $data;
	}

	public function get_item()
	{
			$this->dbwarehouse->select('*');
			$this->dbwarehouse->from('master_bahanbaku');
			$this->dbwarehouse->where('area_uuid',$this->sanitasi);
			$data= $this->dbwarehouse->get()->result();
			
			return $data;
	}

	public function get_by_uuid($uuid)
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select("bb.*, (SELECT SUM(c.qty_dikirim) FROM `{$this->dbwarehouse->database}`.bahanbaku_wh c WHERE c.bahanbaku_mp_uuid = bb.uuid AND c.penerima_uuid IS NOT NULL AND c.penerima_uuid <> 0) as total_kirim", false);
		$this->db->select('bh.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->join("{$warehouse_db_name}.master_bahanbaku bh", 'bh.uuid = bb.item_barang_uuid', 'left');
		$this->db->where('bb.uuid', $uuid);
		$data = $this->db->get()->row();

		$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs'];

		if ($data) {
			// Format tanggal
			$data->tanggal = date('Y-m-d', strtotime($data->created_at));
			$data->tgl = date('d M Y', strtotime($data->created_at));
			$data->satuan = $satuan_map[$data->satuan] ?? '-';
		}
		return $data;
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$no_reservasi = $this->input->post('no_reservasi');
		$item_barang_uuid = $this->input->post('item_barang');
		$item_barang = $this->input->post('item_name');
		$qty_reservasi = $this->input->post('qty_reservasi');
        $keterangan = $this->input->post('keterangan');
		$area = $this->sanitasi;

		$data = array(
			'uuid' 					=> $uuid,
			'user_uuid'     		=> $this->auth_model->current_user()->uuid,
			'no_reservasi'			=> $no_reservasi,
			'item_barang_uuid'		=> $item_barang_uuid,
			'item_barang' 			=> $item_barang,
			'area_uuid' 			=> $area,
			'qty_reservasi'   		=> $qty_reservasi,
            'keterangan'            => $keterangan
		);	

		$this->db->insert('bahanbaku', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_item_sanitasi($uuid)
	{
		$data = $this->dbwarehouse->get_where('master_bahanbaku', array('uuid' => $uuid))->row();

    // Mapping satuan
    $units = [
        1 => 'Kg',
        2 => 'Liter',
        3 => 'Roll',
		4 => 'Pcs'
    ];

    if ($data) {
        $data->satuan = isset($units[$data->satuan]) ? $units[$data->satuan] : '-';
    }

    return $data;
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

	public function generate_reservasi_number()
	{
    $year = date('Y');
    $today = date('Y-m-d');
    $area_uuid = $this->sanitasi; // area_uuid yang ingin difilter

    // Query untuk mendapatkan no_reservasi terakhir berdasarkan tahun dan area_uuid
    $this->db->select('no_reservasi, created_at');
    $this->db->where('YEAR(created_at)', $year); // Filter berdasarkan tahun dari created_at
    $this->db->where('area_uuid', $area_uuid); // Filter berdasarkan area_uuid
    $this->db->order_by('no_reservasi', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('bahanbaku');

    // Cek apakah hasil query tidak kosong
    if ($query->num_rows() > 0) {
        $result = $query->row();

        if (date('Y-m-d', strtotime($result->created_at)) == $today) {
            return $result->no_reservasi; 
        } else {
            return $result->no_reservasi + 1;
        }
    }

    return 1;
	}


	public function get_kirim_sanitasi($bahan_uuid)
	{
	$this->db->select("{$this->dbwarehouse->database}.bahanbaku_wh.*");
	$this->db->select("(SELECT u1.fullname FROM users u1 WHERE `{$this->dbwarehouse->database}`.bahanbaku_wh.user_uuid = u1.uuid) AS pengirim",false);
	$this->db->select("(SELECT u2.fullname FROM users u2 WHERE `{$this->dbwarehouse->database}`.bahanbaku_wh.penerima_uuid = u2.uuid) AS penerima", false);
	$this->db->where('bahanbaku_mp_uuid', $bahan_uuid);
	$data = $this->db->get("`{$this->dbwarehouse->database}`.bahanbaku_wh")->result();
	foreach ($data as $val) {
		$val->kode_produk = !empty($val->kode_produk) ? $val->kode_produk : '-';
		$val->exp_date = !empty($val->exp_date) ? $val->exp_date : '-';
	}
	return $data;
	}
	public function update_diterima($uuid)
	{
    $data = array(
        'penerima_uuid' => $this->auth_model->current_user()->user_uuid,
        'jam_terima'    => date('Y-m-d H:i:s')
    );

    $this->dbwarehouse->where('uuid', $uuid);
    $this->dbwarehouse->update('bahanbaku_wh', $data); 

    return ($this->dbwarehouse->affected_rows() > 0) ? true : false;
	}

	public function get_form_sanitasi($tanggal)
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select('a.nama_area, bb.*, u.fullname, wb.qty_dikirim, wb.jam_kirim, wb.jam_terima, wb.kode_produk, wb.exp_date, wb.kondisi_kemasan, wb.kontaminasi, wb.kondisi_pallet, wb.penempatan, wb.qc_name as acc_qc, bh.satuan');
		$this->db->select("(SELECT u2.fullname FROM users u2 WHERE u2.uuid = wb.penerima_uuid) AS penerima", false);
		$this->db->select("(SELECT u1.fullname FROM users u1 WHERE u1.uuid = wb.user_uuid) AS pengirim", false);
		$this->db->select("(SELECT u3.fullname FROM users u3 WHERE u3.uuid = bb.spv_uuid) AS spv", false);
		$this->db->from('bahanbaku bb');
		$this->db->join("{$this->dberetort->database}.area a", 'a.uuid = bb.area_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.master_bahanbaku bh", 'bh.uuid = bb.item_barang_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.bahanbaku_wh wb", 'bb.uuid = wb.bahanbaku_mp_uuid', 'left');
		$this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
		$this->db->where('bb.area_uuid', $this->sanitasi);
		$this->db->where('DATE_FORMAT(bb.created_at, "%Y-%m-%d") =', $tanggal);
		$this->db->order_by('bb.created_at', 'ASC');
		
		$data = $this->db->get()->result();

		foreach ($data as $val) {
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));
			$val->tgl = date('d M Y', strtotime($val->created_at));
			
			$satuanMap = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs'];
			$val->satuan = $satuanMap[$val->satuan] ?? '-';
			
			}
			
		return $data;
	}

}