<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Rework_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function rules()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];
	}

	public function rules_pakai()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'qty_pemakaian',
				'label' => 'Berat',
				'rules' => 'required'
			],
			[
				'field' => 'kode_batch',
				'label' => 'Kode Batch Sekarang',
				'rules' => 'required'
			],
			[
				'field' => 'plastik',
				'label' => 'Temuan Plastik',
				'rules' => 'required'
			],
			[
				'field' => 'metal',
				'label' => 'Temuan Metal',
				'rules' => 'required'
			]
		];
	}

	public function rules_edit_pakai()
	{
		return [
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode_rework',
				'label' => 'Kode Rework',
				'rules' => 'required'
			],
			[
				'field' => 'qty_pemakaian',
				'label' => 'Berat',
				'rules' => 'required'
			],
			[
				'field' => 'kode_batch',
				'label' => 'Kode Batch Sekarang',
				'rules' => 'required'
			],
			[
				'field' => 'plastik',
				'label' => 'Temuan Plastik',
				'rules' => 'required'
			],
			[
				'field' => 'metal',
				'label' => 'Temuan Metal',
				'rules' => 'required'
			]
		];
	}


	public function get_all()
	{
		$this->db->select('k.*, v.varian');
		$this->db->from('rwk_kupas k');
		$this->db->join($this->db->database.'.varian v', 'k.varian_uuid = v.uuid', 'left');
		$this->db->order_by('k.created_at', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$val->tgl = date('d M Y', strtotime($val->created_at));
		}
		return $data;

	}

	public function get_varian()
	{
		$today = date('Y-m-d');
		$this->db->select('t.uuid, t.tanggal, t.varian, t.varian_uuid');
		$this->db->from('t_planning t');
		$this->db->where('t.tanggal', $today);
		$data = $this->db->get()->result();

		return $data;
	}

	public function get_batch_by_varian($varian_uuid)
{
    $this->db->select('m.kode_batch');
    $this->db->from('mincing m');
    $this->db->join('t_planning t', 'm.planprod_uuid = t.uuid');
    $this->db->where('t.varian_uuid', $varian_uuid);
    $this->db->order_by('m.kode_batch', 'ASC');

    $query = $this->db->get();
    return $query->result();
}
public function get_batch_by_planprod($planprod_uuid)
{
    $this->db->select('m.planprod_uuid, m.MN_BATCH');
    $this->db->from($this->db->database.'.mincing m');
    $this->db->join('t_planning t', 'm.planprod_uuid = t.uuid', 'inner');
    $this->db->where('t.uuid', $planprod_uuid);
    $data = $this->db->get()->result();
    return $data;
}


	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$varian = $this->input->post('varian');
		$kode_rework = $this->input->post('kode_rework');
		$berat = $this->input->post('berat');

		$data = array(
			'uuid' => $uuid,
			'varian_uuid' => $varian,
			'berat' => $berat,
			'kode_rework' => $kode_rework,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);	

		$this->db->insert('rwk_kupas', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_pakai()
	{
		$uuid = Uuid::uuid4()->toString();

		$varian = $this->input->post('varian');
		$plastik = $this->input->post('plastik');
		$metal = $this->input->post('metal');
		$kode_rework = $this->input->post('kode_rework');
		$berat = $this->input->post('qty_pemakaian');
		$produksi = $this->input->post('kode_batch');
		$kupas = $this->db->get_where('rwk_kupas', array('kode_rework' => $kode_rework ))->row();

		$data = array(
			'uuid' => $uuid,
			'kode_produksi' => $produksi,
			'rwk_kupas_uuid' => $kupas->uuid,
			'kode_rework' => $kode_rework,
			'dipakai' => $berat,
			'plastik' => $plastik,
			'metal' => $metal,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);	

		$this->db->insert('rwk_pakai', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$varian = $this->input->post('varian');
		$berat = $this->input->post('berat');
		$kode_rework = $this->input->post('kode_rework');

		$data = array(
			
			'varian_uuid' => $varian,
			'kode_rework' => $kode_rework,
			'berat' => $berat,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'modified_at'  => date('Y-m-d h:i:s')

		);	

		$this->db->update('rwk_kupas', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db->get_where('rwk_kupas', array('uuid' => $uuid ))->row();
	}

	public function get_pakai_by_uuid($uuid)
	{
		$this->db->select('p.*, k.uuid AS kupas_uuid, v.varian');
		$this->db->from('rwk_pakai p');
		$this->db->join('rwk_kupas k', 'k.uuid = p.rwk_kupas_uuid', 'left');
		$this->db->join($this->db->database.'.varian v', 'v.uuid = k.varian_uuid', 'left');
		$this->db->where('p.uuid', $uuid);

		$data = $this->db->get()->row();

		return $data;
	}


	public function get_pakai_data_by_tanggal_kode($tanggal_kode)
	{
		$this->db->query('SET @sisa_rework = 0, @last_kode_rework = NULL');
		$this->db->select("
			p.kode_rework, u.username,
			IF(@last_kode_rework IS NULL OR @last_kode_rework != p.kode_rework,
			@sisa_rework := k.berat, 
			@sisa_rework
			) AS total_rework,
			p.dipakai, p.kode_produksi, p.foreman_uuid, p.spv_uuid, k.varian_uuid, p.plastik, p.metal, p.acc_qc, p.uuid,
			@sisa_rework := @sisa_rework - p.dipakai AS sisa_stock,
			@last_kode_rework := p.kode_rework AS kode_rework_terbaru,
			v.varian,
			DATE_FORMAT(k.created_at, '%d-%m-%Y') AS tanggal_masuk
			", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = p.spv_uuid) AS spv", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = p.foreman_uuid) AS leader", false);
		$this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = p.user_uuid) AS pembuat", false);
		$this->db->from('rwk_pakai p');
		$this->db->join('rwk_kupas k', 'p.rwk_kupas_uuid = k.uuid', 'left');
		$this->db->join($this->db->database.'.varian v', 'v.uuid = k.varian_uuid', 'left');
		$this->db->join('users u', 'u.uuid = p.user_uuid', 'left');
		$this->db->where('SUBSTR(p.kode_produksi, 1, 4) =', $tanggal_kode);
		$this->db->order_by('p.kode_rework, p.created_at', 'ASC');

    // Eksekusi query dan ambil hasilnya
		$data = $this->db->get()->result();

    // Konversi nilai plastik dan metal untuk setiap baris data
		foreach ($data as $val) {
			$val->plastik = $val->plastik == 1 ? 'Ya' : ($val->plastik == 2 ? 'Tidak' : $val->plastik);
			$val->metal = $val->metal == 1 ? 'Ya' : ($val->metal == 2 ? 'Tidak' : $val->metal);
			$val->kode = SUBSTR($val->kode_produksi,0,4);
			$val->tanggal = $this->convertKodeToTanggal($val->kode);
			$val->tanggal_kode = $tanggal_kode;
		}

		return $data;
	}



	public function get_pakai_data()
	{
    // Ambil data dari database
		$this->db->select('*, substr(kode_produksi, 1, 4) as tanggal_kode');
		$this->db->from('rwk_pakai');
		$this->db->order_by('created_at', 'DESC');
		$this->db->group_by('tanggal_kode');
		$data = $this->db->get()->result();

    // Proses konversi tanggal
		foreach ($data as $item) {
        // Ambil substring 'OJ04' dari kode
			$item->tanggal = $this->convertKodeToTanggal($item->tanggal_kode);
		}

		return $data;
	}

	private function convertKodeToTanggal($kode)
	{
    // Huruf pertama sebagai tahun
		$yearBase = 2010;
		$yearChar = $kode[0];
    $yearOffset = ord($yearChar) - ord('A');  // Selisih dari A
    $year = $yearBase + $yearOffset;

    // Huruf kedua sebagai bulan
    $monthChar = $kode[1];
    $monthOffset = ord($monthChar) - ord('A');  // A = Januari
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $month = $months[$monthOffset];

    // Dua digit terakhir sebagai hari
    $day = intval(substr($kode, 2, 2));

    // Formatkan ke tanggal d M Y
    return sprintf('%02d %s %04d', $day, $month, $year);
}


public function insert_stock($data) {
	return $this->db->insert('rwk_kupas', $data);
}

    // Get all rework stock
public function get_all_stock() {
	return $this->db->get('rwk_kupas')->result();
}

    // Get rework stock by kode_rework
public function get_stock_by_kode_rework($kode_rework) {
	$this->db->where('kode_rework', $kode_rework);
	$this->db->where('berat >', 0);
	return $this->db->get('rwk_kupas')->row();
}

    // Insert rework usage into rwk_pakai
public function insert_usage($data) {
	return $this->db->insert('rwk_pakai', $data);
}

    // Update remaining stock in rwk_kupas
public function update_stock($id, $new_weight) {
	$this->db->set('berat', $new_weight);
	$this->db->where('id', $id);
	return $this->db->update('rwk_kupas');
}

public function get_rework_by_varian($varian_uuid) {
	$this->db->select('kode_rework, berat');
    $this->db->from('rwk_kupas'); // Assuming 'rwk_kupas' is your table for rework stocks
    $this->db->where('varian_uuid', $varian_uuid);
    $this->db->where('berat >', 0); // Only get codes with remaining stock
    $query = $this->db->get();

    // Prepare the result with remaining stock calculation
    $result = [];
    foreach ($query->result() as $row) {
    	$kode_rework = $row->kode_rework;

        // Fetch the total used weight (dipakai) from r_pakai for the selected kode_rework
    	$this->db->select_sum('dipakai');
    	$this->db->from('rwk_pakai');
    	$this->db->where('kode_rework', $kode_rework);
    	$pakai = $this->db->get()->row();

        // Calculate remaining stock
    	$remaining = $row->berat - ($pakai->dipakai ?? 0);

        // Only include if remaining stock is greater than 0
    	if ($remaining > 0) {
            $result[] = $row; // Add the row to the result
        }
    }
    
    return $result; // Return the filtered result as an array of objects
}

public function update_pakai($uuid)
{
	$plastik = $this->input->post('plastik');
	$qty_pemakaian = $this->input->post('qty_pemakaian');
	$kode_batch = $this->input->post('kode_batch');
	$metal = $this->input->post('metal');

	$data = array(
		'dipakai' => $qty_pemakaian,
		'kode_produksi' => $kode_batch,
		'plastik' => $plastik,
		'metal' => $metal,
		'modified_at' => date('Y-m-d h:i:s')
	);

		$this->db->update('rwk_pakai', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function approval($tanggal_kode, $role)
{
    $data = [];
    if ($role == '2') {
        $data['spv_uuid'] = $this->auth_model->current_user()->uuid;
    } elseif ($role == '1') {
        $data['foreman_uuid'] = $this->auth_model->current_user()->uuid;
    }

    if (!empty($data)) {
        $this->db->where("LEFT(kode_produksi, 4) =", $tanggal_kode); // Gunakan LEFT untuk substring
        $this->db->update('rwk_pakai', $data);

        if ($this->db->affected_rows() > 0) {
            // Ambil nama user yang baru approve
            $current_user = $this->auth_model->current_user();
            return $current_user->fullname;
        }
    }

    return false;
}

}

// $this->db->where('SUBSTR(p.kode_produksi, 1, 4) =', $tanggal_kode);