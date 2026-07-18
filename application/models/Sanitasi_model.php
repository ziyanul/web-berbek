<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Sanitasi_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('gmp_model');
	}
	public function rules()
	{
		return [
			
			[
				'field' => 'used',
				'label' => 'Chemical',
				'rules' => 'required'
			]
		];
	}
	public function rules1()
	{
		return [
			
			[
				'field' => 'jam',
				'label' => 'Waktu ditindak',
				'rules' => 'required'
			]
		];
	}

	public function get_cheklist_sanitasi()
	{
		$this->db->select('c.area, c.area_uuid, DATE(c.created_at) as tanggal');
		$this->db->from('cheklist_sanitasi c');
		$this->db->group_by('area, tanggal, area_uuid');
		$this->db->order_by('tanggal', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->tanggal));
		}
		return $data;
	}

	public function insert_kondisi()
	{
		$area_uuid = $this->input->post('area');
		$lokasi_uuid = $this->input->post('lokasi');
		$waktu = $this->input->post('waktu');
    $keterangan = $this->input->post('keterangan'); // Pastikan ini ada
    $area = $this->gmp_model->cek_area_name($area_uuid)->area;
    $all_kegiatan = $this->gmp_model->cek_kegiatan_by_lokasi($lokasi_uuid);
    foreach ($all_kegiatan as $kegiatan_item) {
    	$uui = Uuid::uuid4()->toString();
    	$kegiatan_name = $kegiatan_item->kegiatan;
    	$kegiatan_uuid = $kegiatan_item->uuid;
    	$data = [
    		'uuid'             => $uui,
    		'user_uuid'        => $this->auth_model->current_user()->uuid,
    		'username'         => $this->auth_model->current_user()->username,
    		'area'            => $area,
    		'area_uuid'            => $area_uuid,
    		'kegiatan_uuid' => $kegiatan_uuid,
    		'nama_item'        => $kegiatan_name,
    		'waktu_kondisi'            => $waktu,
            'kondisi_uuid'          => isset($keterangan[$kegiatan_uuid]) ? $keterangan[$kegiatan_uuid] : 0 // Periksa dengan UUID kegiatan
        ];
        $this->db->insert('cheklist_sanitasi', $data);
    }
    return ($this->db->affected_rows() > 0);
}
public function update($uuid)
{
	$kondisi = $this->input->post('kondisi'); 
	$data = array(

		'kondisi' => $kondisi,

		'modified_at' => date('Y-m-d h:i:s')
	);	
		$this->db->update('cheklist_sanitasi', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; 
	}

	public function get_by_area_uuid($area_uuid, $tanggal)
	{
		$kondisi = $this->get_kondisi_nomor();
		$tindakan = $this->get_tindakan_nomor();
		$sql_date_adjusted = "CASE 
		WHEN TIME(waktu_kondisi) < '06:00:00' THEN DATE_SUB('$tanggal', INTERVAL - 1 DAY)
		ELSE '$tanggal'
		END";
		$this->db->select("c.*, DATE_FORMAT(waktu_kondisi, '%H:%i') AS jam_cek, k.kondisi, t.tindakan as tindakan_name");
		$this->db->from('cheklist_sanitasi c');
		$this->db->join('m_kondisi k', 'k.uuid = c.kondisi_uuid', 'left');
		$this->db->join('m_tindakan t', 't.uuid = c.tindakan', 'left');
		$this->db->where('c.area_uuid', $area_uuid);
		$this->db->where("DATE(c.created_at) = ($sql_date_adjusted)", NULL, FALSE);
		$this->db->order_by('c.created_at', 'ASC');
		$data = $this->db->get()->result();

		foreach ($data as $val) {
	// Default value untuk menghindari error
			$val->kondis = '-';
			$val->no_tindak = '-';

			$val->tindak = date('H:i', strtotime($val->waktu_tindakan));
			$val->waktu_tindakan = ($val->tindakan != null) ? $val->tindak : '';
			$val->tgl = date('d M Y', strtotime($val->created_at));

			foreach ($kondisi as $row) {
				if ($val->kondisi_uuid == $row->uuid) {
					$val->kondis = $row->no_kondisi;
				}
			}
			foreach ($tindakan as $row1) {
				if ($val->tindakan == $row1->uuid) {
					$val->no_tindak = $row1->no_tindakan;
				}
			}
			if ($val->kondis == 0) {
				$val->kondis = '&#x2713;';
			}
		}

		return $data;
	}

	public function do_tindakan($uuid)
	{
		$tindakan = $this->input->post('tindakan'); 
		$waktu_tindakan = $this->input->post('jam');
		$is_chemical_used = $this->input->post('flexSwitchCheckChecked');
    $selected_chemicals = $this->input->post('selected_chemicals'); // Get selected chemicals

    $data = array(
    	'petugas'          => $this->auth_model->current_user()->username,
    	'tindakan'         => $tindakan,
    	'waktu_tindakan'   => $waktu_tindakan,
    	'modified_at'      => date('Y-m-d h:i:s')
    );

    $this->db->update('cheklist_sanitasi', $data, array('uuid' => $uuid)); 
    $update_success = ($this->db->affected_rows() > 0) ? true : false; 

    if ($is_chemical_used && $update_success && !empty($selected_chemicals)) {
    	foreach ($selected_chemicals as $chemical_uuid) {
            // Fetch kode_chemical_uuid and target from kondisi_area
    		$this->db->select('ka.kode_chemical_uuid, ka.target, cs.uuid as uuid_checklist');
    		$this->db->from('kondisi_area ka');
    		$this->db->join('cheklist_sanitasi cs', 'cs.kegiatan_uuid = ka.kegiatan_gmp_uuid', 'left');
    		$this->db->where('ka.uuid', $chemical_uuid);
    		$query = $this->db->get();
    		$kondisi_area_data = $query->row();

            // Prepare data for larutan_used table
    		if ($kondisi_area_data) {
    			$larutan_data = array(
    				'uuid' => Uuid::uuid4()->toString(),
                    'cheklist_sanitasi_uuid' => $uuid, // Assuming this is the correct linkage
                    'kode_chemical_uuid' => $kondisi_area_data->kode_chemical_uuid,
                    'used' => $kondisi_area_data->target
                );

                // Insert into larutan_used table
    			$this->db->insert('larutan_used', $larutan_data);
    		}
    	}
    }

    return $update_success;
}


public function get_by_uuid($uuid)
{
	$data = $this->db->get_where('cheklist_sanitasi', array('uuid' => $uuid ))->row();
	$data->tanggal = date('Y-m-d', strtotime($data->created_at));
	$data->waktu_kondisi = date('H:i',strtotime($data->waktu_kondisi));
	if ($data->kondisi == 0) {
		$data->kondis = 'Ok Bersih';
	} elseif ($data->kondisi == 1) {
		$data->kondis = 'Basah';
	} elseif ($data->kondisi == 2) {
		$data->kondis = 'Berdebu';
	} elseif ($data->kondisi == 3) {
		$data->kondis = 'Kerak';
	} elseif ($data->kondisi == 4) {
		$data->kondis = 'Noda';
	} elseif ($data->kondisi == 5) {
		$data->kondis = 'Karat';
	} elseif ($data->kondisi == 6) {
		$data->kondis = 'Sampah';
	} elseif ($data->kondisi == 7) {
		$data->kondis = 'Retak/Pecah';
	} elseif ($data->kondisi == 8) {
		$data->kondis = 'Sisa Produk';
	} elseif ($data->kondisi == 9) {
		$data->kondis = 'Sisa Adonan';
	} elseif ($data->kondisi == 10) {
		$data->kondis = 'Berjamur';
	} elseif ($data->kondisi == 11) {
		$data->kondis = 'Lain-lain';
	}
	return $data;
}
public function get_sanitasi_chemical($kegiatan_uuid)
{
	return $this->db->get_where('sanitasi_chemical', array('kegiatan_gmp_uuid' => $kegiatan_uuid))->row();
}
public function all_chemical_used($uuid)
{
    // Get data from cheklist_sanitasi based on the UUID
	$this->db->select('c.*, DATE(c.created_at) as tanggal, k.kondisi');
	$this->db->from('cheklist_sanitasi c');
	$this->db->join('m_kondisi k', 'k.uuid = c.kondisi_uuid', 'left');
	$this->db->where('c.uuid', $uuid);
	$data = $this->db->get()->row();
	$data->waktu_kondisi = date('H:i',strtotime($data->waktu_kondisi));


    // Get related data from sanitasi_chemical
	// $this->db->select('ka.kode_chemical_uuid, ka.target, p.kode_chemical, p.uuid');
	// $this->db->from('kondisi_area ka'); // s sanitasi_chemical
	// $this->db->join('kode_chemical p', 'p.uuid = ka.kode_chemical_uuid', 'left');
	// $this->db->where('ka.kegiatan_gmp_uuid', $cheklist_sanitasi->kegiatan_uuid);
	// $sanitasi_chemical = $this->db->get()->result();

	// $data = [
	// 	'cheklist_sanitasi' => $cheklist_sanitasi,
	// 	'sanitasi_chemical' => $sanitasi_chemical
	// ];
	return $data;
}

public function get_kondisi()
{
	$this->db->select('*');
	$this->db->from('m_kondisi');

	$data = $this->db->get()->result();
	
    return $data;

}

public function get_kondisi_nomor()
{
	$data = $this->db->get('m_kondisi')->result();
	$no = 0;
	foreach ($data as $val) {
		$val->no_kondisi = $no;
		$no++;
	}
	return $data;
}

public function get_tindakan()
{
	return $this->db->get('m_tindakan')->result();
}

public function get_tindakan_nomor()
{
	$data = $this->db->get('m_tindakan')->result();
	$no = 0;
	foreach ($data as $val) {
		$val->no_tindakan = $no;
		$no++;
	}
	return $data;
}

public function get_tindakan_by_kegiatan($tindakan, $kegiatan_gmp_uuid) 
{
	$this->db->select('ka.*, kc.kode_chemical');
	$this->db->from('kondisi_area ka');
	$this->db->join('kode_chemical kc', 'kc.uuid = ka.kode_chemical_uuid', 'left');
	$this->db->where('ka.tindakan', $tindakan);
	$this->db->where('ka.kegiatan_gmp_uuid', $kegiatan_gmp_uuid);
	$data = $this->db->get()->result();
	return $data;

}


public function insert_master_kondisi()
{
	$uuid = Uuid::uuid4()->toString();
	$kondisi = $this->input->post('kondisi');

	$data = [
		'uuid' => $uuid,
		'kondisi' => $kondisi
	];

	$this->db->insert('m_kondisi', $data);
	return ($this->db->affected_rows() > 0);
}

public function insert_master_tindakan()
{
	$uuid = Uuid::uuid4()->toString();
	$kondisi = $this->input->post('tindakan');

	$data = [
		'uuid' => $uuid,
		'tindakan' => $kondisi
	];

	$this->db->insert('m_tindakan', $data);
	return ($this->db->affected_rows() > 0);
}

public function get_data_form($area_uuid, $tanggal)
{
	$this->db->select("c.*, DATE_FORMAT(waktu_kondisi, '%H:%i') AS jam_cek, k.kondisi, t.tindakan as tindakan_name");
	$this->db->from('cheklist_sanitasi c');
	$this->db->join('m_kondisi k', 'k.uuid = c.kondisi_uuid', 'left');
	$this->db->join('m_tindakan t', 't.uuid = c.tindakan', 'left');
	$this->db->where('c.area_uuid', $area_uuid);
	$this->db->where('DATE(c.created_at)', $tanggal);
	$data = $this->db->get()->result();

	return $data;
}


public function get_chemical_by_area($tanggal)
{
	$sql_date_adjusted = "CASE 
	WHEN TIME(waktu_kondisi) < '06:00:00' THEN DATE_SUB('$tanggal', INTERVAL - 1 DAY)
	ELSE '$tanggal'
	END";

	$this->db->select("c.*, DATE_FORMAT(waktu_kondisi, '%H:%i') AS jam_cek, l.kode_chemical_uuid, l.used, kc.kode_chemical");
	$this->db->from('cheklist_sanitasi c');
	$this->db->join('larutan_used l', 'l.cheklist_sanitasi_uuid = c.uuid', 'left');
	$this->db->join('kode_chemical kc', 'kc.uuid = l.kode_chemical_uuid', 'left');
	$this->db->where("DATE(c.created_at) = ($sql_date_adjusted)", NULL, FALSE);
	$this->db->order_by('c.area, c.nama_item, kc.kode_chemical', 'ASC');
	$data = $this->db->get()->result();
	$item_map = [];
	$kegiatan_with_chemical = [];

	foreach ($data as $val) {
		$val->tindak = date('H:i', strtotime($val->waktu_tindakan));
		$val->waktu_tindakan = ($val->tindakan != null) ? $val->tindak : '';
		$val->tgl = date('d M Y', strtotime($val->created_at));

        // Track which kegiatan_uuid already has a kode_chemical
		if ($val->kode_chemical) {
			$kegiatan_with_chemical[$val->kegiatan_uuid] = true;
		}
	}

	foreach ($data as $val) {
		if (isset($kegiatan_with_chemical[$val->kegiatan_uuid]) && !$val->kode_chemical) {
			continue;
		}

		$key = $val->nama_item . ($val->kode_chemical ? '_' . $val->kode_chemical : '_no_chemical');

		if (!isset($item_map[$key])) {
			$item_map[$key] = (object) [
				'area' => $val->area,
				'tgl' =>$val->tgl,
				'kegiatan_uuid' => $val->kegiatan_uuid,
				'nama_item' => $val->nama_item,
				'kode_chemical' => $val->kode_chemical ?? '-',
				'jam8' => '-',
				'jam10' => '-',
				'jam12' => '-',
				'jam14' => '-',
				'jam16' => '-',
				'jam18' => '-',
				'jam20' => '-',
				'jam22' => '-',
				'jam0' => '-',
				'jam2' => '-',
				'jam4' => '-',
				'jam6' => '-',
				'total_used' => 0,
			];
		}

        // Update the time slots based on the tindak time
		if ($val->tindak >= "08:00" && $val->tindak < "10:00") {
			$item_map[$key]->jam8 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "10:00" && $val->tindak < "12:00") {
			$item_map[$key]->jam10 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "12:00" && $val->tindak < "14:00") {
			$item_map[$key]->jam12 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "14:00" && $val->tindak < "16:00") {
			$item_map[$key]->jam14 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "16:00" && $val->tindak < "18:00") {
			$item_map[$key]->jam16 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "18:00" && $val->tindak < "20:00") {
			$item_map[$key]->jam18 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "20:00" && $val->tindak < "22:00") {
			$item_map[$key]->jam20 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "22:00" && $val->tindak < "00:00") {
			$item_map[$key]->jam22 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "00:00" && $val->tindak < "02:00") {
			$item_map[$key]->jam0 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "02:00" && $val->tindak < "04:00") {
			$item_map[$key]->jam2 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "04:00" && $val->tindak < "06:00") {
			$item_map[$key]->jam4 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
		if ($val->tindak >= "06:00" && $val->tindak < "08:00") {
			$item_map[$key]->jam6 = $val->used;
			$item_map[$key]->total_used += (float)$val->used;
		}
	}

	return array_values($item_map);
}


}