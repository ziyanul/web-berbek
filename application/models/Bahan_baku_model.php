<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Bahan_baku_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
		$this->dbwarehouse = $this->load->database('warehouse', TRUE);
		$this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');
		
	}

	public function rules()
	{
		return [
			[
				'field' => 'item',
				'label' => 'Item',
				'rules' => 'required'
			],
			[
				'field' => 'qty_reservasi',
				'label' => 'Jumlah',
				'rules' => 'required'
			]
		];
	}

	public function get_area_name($uuid)
	{
		return $this->dberetort->get_where('area', array('uuid' => $uuid))->row();
	}

	public function get_all_mesin($uuid)
	{
		return $this->db->get_where('mesin', array('area_uuid' => $uuid))->result();
	}

	public function get_filler()
	{
		$this->db->select('area.nama_area, bahanbaku.*');
		$this->db->from('bahanbaku');
		$this->db->join(area', 'area.uuid = bahanbaku.area_uuid', 'left');
		$this->db->where('area_uuid', $this->filler);
		$this->db->group_by('DATE_FORMAT(bahanbaku.created_at, "%d %M %Y")');
		$this->db->order_by('created_at, no_reservasi', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tanggal = date('d M Y', strtotime($val->created_at));
			$val->tgl = date('Y-m-d', strtotime($val->created_at));
		}

		return $data;
	}

	public function get_all_filler($tanggal)
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select('a.nama_area, bb.*, u.fullname, mp.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->select("(SELECT SUM(wh.qty_dikirim) FROM `{$warehouse_db_name}`.bahanbaku_wh wh WHERE wh.bahanbaku_mp_uuid = bb.uuid AND wh.kondisi_kemasan = 1 AND wh.kondisi_pallet = 1 AND wh.kontaminasi = 1 AND wh.penempatan = 1) AS total_kirim", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = bb.spv_uuid) AS spv", false);
		$this->db->join(area a', 'a.uuid = bb.area_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.master_packaging mp", 'mp.uuid = bb.item_barang_uuid', 'left');
		$this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
		$this->db->where('bb.area_uuid', $this->filler);
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

	public function get_all_sparepart($tanggal)
	{
		$part = 'area_sparepart';
		$this->db->select('a.nama_area, bb.*, u.fullname, p.satuan');
		$this->db->from('bahanbaku bb');

    // Load the warehouse database connection
	$warehouse_db_name = $this->dbwarehouse->database;

    // Add the subquery for total qty_dikirim from bahanbaku_wh in the warehouse database
		$this->db->select("(SELECT SUM(wh.qty_dikirim) FROM `{$warehouse_db_name}`.bahanbaku_wh wh WHERE wh.bahanbaku_mp_uuid = bb.uuid AND wh.penerima_uuid IS NOT NULL AND wh.penerima_uuid <> 0) AS total_kirim", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = bb.spv_uuid) AS spv", false);
		$this->db->join(area a', 'a.uuid = bb.area_uuid', 'left');
		$this->db->join('part p', 'p.uuid = bb.item_barang_uuid', 'left');
		$this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
		$this->db->where('bb.area_uuid', $part);
		$this->db->where('DATE_FORMAT(bb.created_at, "%Y-%m-%d") =', $tanggal);
		$this->db->order_by('bb.created_at', 'DESC');

		$data = $this->db->get()->result();

		$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs', 5 => 'Set'];

		foreach ($data as $val) {
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->jam = date('H:i:s', strtotime($val->created_at));
			$val->satuan = $satuan_map[$val->satuan] ?? '-';
		}

		return $data;
	}

	public function get_form_filler($tanggal)
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select('a.nama_area, bb.*, u.fullname, wb.qty_dikirim, wb.jam_kirim, wb.jam_terima, wb.kode_produk, wb.exp_date, wb.kondisi_kemasan, wb.kontaminasi, wb.kondisi_pallet, wb.penempatan, wb.qc_name as acc_qc, mp.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->select("(SELECT u2.fullname FROM users u2 WHERE u2.uuid = wb.penerima_uuid) AS penerima", false);
		$this->db->select("(SELECT u1.fullname FROM users u1 WHERE u1.uuid = wb.user_uuid) AS pengirim", false);
		$this->db->select("(SELECT u3.fullname FROM users u3 WHERE u3.uuid = bb.spv_uuid) AS spv", false);
		$this->db->join($this->dberetort->database. '.area a', 'a.uuid = bb.area_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.master_packaging mp", 'mp.uuid = bb.item_barang_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.bahanbaku_wh wb", 'bb.uuid = wb.bahanbaku_mp_uuid', 'left');
		$this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
		$this->db->where('bb.area_uuid', $this->filler);
		$this->db->where('DATE_FORMAT(bb.created_at, "%Y-%m-%d") =', $tanggal);
		$this->db->order_by('bb.created_at', 'ASC');
		$data = $this->db->get()->result();
		
		$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs'];
		$status_map = [1 => '&check;', 2 => 'x', null => ''];
		
		foreach ($data as $val) {
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->satuan = $satuan_map[$val->satuan] ?? '-';
			
			foreach (['kondisi_kemasan', 'kontaminasi', 'kondisi_pallet', 'penempatan'] as $field) {
				$val->$field = $status_map[$val->$field] ?? '';
			}
			
			$val->jam_terima = (empty($val->kondisi_kemasan) && empty($val->kontaminasi) && empty($val->kondisi_pallet)) ? '-' :
				(($val->kondisi_kemasan === '&check;' && $val->kontaminasi === '&check;' && $val->kondisi_pallet === '&check;') ? $val->jam_terima : 'x');
	
		}

		return $data;
	}

	public function get_form_part($tanggal)
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$part = 'area_sparepart';
		$this->db->select('a.nama_area, bb.*, u.fullname, wb.qty_dikirim, wb.jam_kirim, wb.jam_terima, wb.kode_produk, wb.exp_date, wb.kondisi_kemasan, wb.kontaminasi, wb.kondisi_pallet, wb.penempatan, wb.qc_name as acc_qc, p.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->select("(SELECT u2.fullname FROM users u2 WHERE u2.uuid = wb.penerima_uuid) AS penerima", false);
		$this->db->select("(SELECT u1.fullname FROM users u1 WHERE u1.uuid = wb.user_uuid) AS pengirim", false);
		$this->db->select("(SELECT u3.fullname FROM users u3 WHERE u3.uuid = bb.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u3.fullname FROM users u3 WHERE u3.uuid = bb.spv_uuid) AS spv", false);
		$this->db->select("(SELECT wb1.jam_kirim FROM  `{$warehouse_db_name}`.bahanbaku_wh wb1 WHERE wb1.bahanbaku_mp_uuid = bb.uuid ORDER BY wb1.created_at ASC LIMIT 1) as jam_kirim1", false);
		$this->db->select("(SELECT wb1.jam_terima FROM `{$warehouse_db_name}`.bahanbaku_wh wb1 WHERE wb1.bahanbaku_mp_uuid = bb.uuid ORDER BY wb1.created_at ASC LIMIT 1) as jam_terima1", false);
		$this->db->join($this->dberetort->database. '.area a', 'a.uuid = bb.area_uuid', 'left');
		$this->db->join("{$warehouse_db_name}.bahanbaku_wh wb", 'bb.uuid = wb.bahanbaku_mp_uuid', 'left');
		$this->db->join('part p', 'p.uuid = bb.item_barang_uuid', 'left');
		$this->db->join('users u', 'u.uuid = bb.user_uuid', 'left');
		$this->db->where('bb.area_uuid', $part);
		$this->db->where('DATE_FORMAT(bb.created_at, "%Y-%m-%d") =', $tanggal);
		$this->db->order_by('bb.created_at', 'ASC');
		$data = $this->db->get()->result();

		$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs', 5 => 'Set'];

		foreach ($data as $val) {
			$val->tanggal = date('Y-m-d', strtotime($val->created_at));
			$val->tgl = date('d M Y', strtotime($val->created_at));
			$val->satuan = $satuan_map[$val->satuan] ?? '-';

			if ($val->jam_kirim1 != NULL && $val->jam_terima1 !=NULL) {
					$val->jam_kirim2 = date('Y-m-d / H:i:s', strtotime($val->jam_kirim1));
					$val->jam_terima2 = date('Y-m-d / H:i:s', strtotime($val->jam_terima1));
			} else {
				$val->jam_kirim2 = '';
				$val->jam_terima2 = '';
			}
		}

		return $data;
	}

	public function get_jenis_filler()
	{
		$this->dbwarehouse->select('nama, uuid');
		$this->dbwarehouse->from('master_packaging');
		$this->dbwarehouse->where('area_uuid', $this->filler);
		$data = $this->dbwarehouse->get()->result();

		return $data;

	}

	public function get_sparepart()
	{
		$part = 'area_sparepart';
		$this->db->select('area.nama_area, bahanbaku.*');
		$this->db->from('bahanbaku');
		$this->db->join(area', 'area.uuid = bahanbaku.area_uuid', 'left');
		$this->db->where('area_uuid', $part);
		$this->db->group_by('DATE_FORMAT(bahanbaku.created_at, "%d %M %Y")');
		$this->db->order_by('created_at, no_reservasi', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tanggal = date('d M Y', strtotime($val->created_at));
			$val->tgl = date('Y-m-d', strtotime($val->created_at));
		}

		return $data;
	}

	public function get_by_uuid($uuid) // ngambil nama data mesin
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select("bb.*, (SELECT SUM(c.qty_dikirim) FROM `{$this->dbwarehouse->database}`.bahanbaku_wh c WHERE c.bahanbaku_mp_uuid = bb.uuid AND c.kondisi_kemasan = 1 AND c.kondisi_pallet = 1 AND c.kontaminasi = 1 AND c.penempatan = 1) as total_kirim", false);
		$this->db->select('mp.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->join("{$warehouse_db_name}.master_packaging mp", 'mp.uuid = bb.item_barang_uuid', 'left');
		$this->db->join('part p', 'p.uuid = bb.item_barang_uuid', 'left');
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

	public function get_by_uuid2($uuid) // ngambil nama data mesin
	{
		$warehouse_db_name = $this->dbwarehouse->database;
		$this->db->select("bb.*, (SELECT SUM(c.qty_dikirim) FROM `{$this->dbwarehouse->database}`.bahanbaku_wh c WHERE c.bahanbaku_mp_uuid = bb.uuid AND c.penerima_uuid IS NOT NULL AND c.penerima_uuid <> 0) as total_kirim", false);
		$this->db->select('p.satuan');
		$this->db->from('bahanbaku bb');
		$this->db->join("{$warehouse_db_name}.master_packaging mp", 'mp.uuid = bb.item_barang_uuid', 'left');
		$this->db->join('part p', 'p.uuid = bb.item_barang_uuid', 'left');
		$this->db->where('bb.uuid', $uuid);
		$data = $this->db->get()->row();

		$satuan_map = [1 => 'Kg', 2 => 'Liter', 3 => 'Roll', 4 => 'Pcs', 5 => 'Set'];

		if ($data) {
			// Format tanggal
			$data->tanggal = date('Y-m-d', strtotime($data->created_at));
			$data->tgl = date('d M Y', strtotime($data->created_at));
			$data->satuan = $satuan_map[$data->satuan] ?? '-';
		}
		return $data;
	}

	public function get_kirim_filler($bahan_uuid)
	{
		$this->db->select("{$this->dbwarehouse->database}.bahanbaku_wh.*, .bahanbaku_wh.qc_name as acc_qc ");
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
	
	public function insert_filler()
	{
		$uuid = Uuid::uuid4()->toString();
		$no_reservasi = $this->input->post('no_reservasi');
		$item_barang_uuid = $this->input->post('item');
		$item_barang = $this->input->post('item_name');
		$keterangan = $this->input->post('keterangan');
		$qty_reservasi = $this->input->post('qty_reservasi');
		$area = $this->filler;
		$data = array(
			'uuid' => $uuid,
			'no_reservasi' => $no_reservasi,
			'item_barang_uuid' => $item_barang_uuid,
			'item_barang' => $item_barang,
			'area_uuid' =>$area,
			'qty_reservasi' => $qty_reservasi,
			'keterangan' => $keterangan,
			'user_uuid'     => $this->auth_model->current_user()->uuid
		);	

		$this->db->insert('bahanbaku', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_item_filler($uuid)
	{
		$data = $this->dbwarehouse->get_where('master_packaging', array('uuid' => $uuid))->row();

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

	public function get_item_part($uuid)
	{
		$data = $this->db->get_where('part', array('uuid' => $uuid))->row();

    // Mapping satuan
    $units = [
        1 => 'Kg',
        2 => 'Liter',
        3 => 'Roll',
		4 => 'Pcs',
		5 => 'Set'
    ];

    if ($data) {
        $data->satuan = isset($units[$data->satuan]) ? $units[$data->satuan] : '-';
    }

    return $data;
	}

	public function insert_part()
	{
		$uuid = Uuid::uuid4()->toString();
		$no_reservasi = $this->input->post('no_reservasi');
		$item_barang_uuid = $this->input->post('item');
		$keterangan = $this->input->post('keterangan');
		$qty_reservasi = $this->input->post('qty_reservasi');
		$item_name = $this->input->post('item_name');
		$area = 'area_sparepart';
		$data = array(
			'uuid' => $uuid,
			'no_reservasi' => $no_reservasi,
			'item_barang_uuid' => $item_barang_uuid,
			'area_uuid' =>$area,
			'qty_reservasi' => $qty_reservasi,
			'item_barang' => $item_name,
			'keterangan' => $keterangan,
			'user_uuid'     => $this->auth_model->current_user()->uuid
		);	

		$this->db->insert('bahanbaku', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function generate_reservasi_number()
	{
		$year = date('Y');
		$today = date('Y-m-d');
    $area_uuid = $this->filler;
    $this->db->select('no_reservasi, created_at');
    $this->db->where('YEAR(created_at)', $year);
    $this->db->where('area_uuid', $area_uuid);
    $this->db->order_by('no_reservasi', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('bahanbaku');

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

public function reservasi_part()
	{
		$year = date('Y');
		$today = date('Y-m-d');
    $area_uuid = 'area_sparepart';
    $this->db->select('no_reservasi, created_at');
    $this->db->where('YEAR(created_at)', $year);
    $this->db->where('area_uuid', $area_uuid);
    $this->db->order_by('no_reservasi', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('bahanbaku');

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


public function update_filler($uuid)
{
	$item = $this->input->post('item');
	$keterangan = $this->input->post('keterangan');
	$qty_reservasi = $this->input->post('qty_reservasi');
	
	$data = array(
		'item_barang' => $item,
		'qty_reservasi' => $qty_reservasi,
		'keterangan' => $keterangan,
		'user_uuid'     => $this->auth_model->current_user()->uuid,
		'modified_at' => date('Y-m-d H:i:s')
	);	

		$this->db->update('bahanbaku', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function approval($tanggal,$area_uuid)
{
    $data = [
        'spv_uuid' => $this->auth_model->current_user()->user_uuid, // UUID supervisor
    ];
	$area_uuid = 'area_sparepart';
	$this->db->where('DATE_FORMAT(bahanbaku.created_at, "%Y-%m-%d") =', $tanggal); // Update berdasarkan UUID
	$this->db->where('area_uuid', $area_uuid);
    $this->db->update('bahanbaku', $data);

    return $this->db->affected_rows() > 0; // Cek apakah ada baris yang terpengaruh
}

public function approval_filler($tanggal,$area_uuid)
{
    $data = [
        'spv_uuid' => $this->auth_model->current_user()->user_uuid, // UUID supervisor
    ];
	$area_uuid = $this->filler;
	$this->db->where('DATE_FORMAT(bahanbaku.created_at, "%Y-%m-%d") =', $tanggal); // Update berdasarkan UUID
	$this->db->where('area_uuid', $area_uuid);
    $this->db->update('bahanbaku', $data);

    return $this->db->affected_rows() > 0; // Cek apakah ada baris yang terpengaruh
}

}