<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Rj_filler_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('Gmp_model');
		// $this->dberetort = $this->load->database('e-retort', TRUE);
        // $this->dbpga = $this->load->database('pga', TRUE);
        $this->config->load('relasi_uuid');
        $this->filler = $this->config->item('filler_uuid');
    }

    public function rules_mesin()
    {
        return [
            [
                'field' => 'mesin[]',
                'label' => 'Mesin',
                'rules' => 'required'
            ],
            [
                'field' => 't_planning',
                'label' => 'Mesin',
                'rules' => 'required'
            ]
        ];
    }

    public function rules_operator()
    {
        return [
            [
                'field' => 't_planning',
                'label' => 'Varian dan Tanggal',
                'rules' => 'required'
            ],
            [
                'field' => 'operator_uuid[]',
                'label' => 'Operator',
                'rules' => 'required'
            ],
            [
                'field' => 'mesin_uuid[]',
                'label' => 'Mesin',
                'rules' => 'required'
            ],
            [
                'field' => 'berat[]',
                'label' => 'Berat',
                'rules' => 'required'
            ]
        ];
    }

    public function get_data()
    {
        $this->db->select('rf.*, tp.tanggal, m.nama_mesin, u.fullname, tp.varian');
        $this->db->from('rj_filler rf');
        $this->db->join('t_planning tp', 'tp.uuid = rf.t_planning_uuid', 'left');
        $this->db->join('mesin m', 'm.device_id = rf.mesin_uuid', 'left');
        $this->db->join('users u', 'u.uuid = rf.operator_uuid', 'left');
        $this->db->where('rf.deleted_at', null);
        $this->db->order_by('rf.created_at', 'DESC');
        $data = $this->db->get()->result();

        foreach ($data as $val) {
            if ($val->varian == 1) {
                $val->varian_name = 'OKEY';
            } elseif ($val->varian == 2) {
                $val->varian_name = 'CHAMP AYAM';
            } elseif ($val->varian == 3) {
                $val->varian_name = 'CHAMP SAPI';
            } elseif ($val->varian == 4) {
                $val->varian_name = 'CHAMP OTAK-OTAK';
            }
            $tanggal = $val->tanggal; // format YYYY-MM-DD
            $tahun  = (int) date('Y', strtotime($tanggal));
            $bulan  = (int) date('n', strtotime($tanggal));
        $hari   = date('d', strtotime($tanggal)); // tetap string dua digit

        // Ambil huruf tahun & bulan
        $yearLetter  = $this->mapYearToLetter($tahun);
        $monthLetter = $this->mapMonthToLetter($bulan);

        // Format nomor batch
        $batch_nomor = str_pad($val->kode_batch, 2, '0', STR_PAD_LEFT); // pastikan batch_nmr ada di rf
        $shift_code  = 'AA0'; // atau default

        // Kode plant misalnya tetap 7
        $kodeplant = '7';

        // Buat kode batch
        $val->kode_batch = $yearLetter . $monthLetter . $hari . $kodeplant . $batch_nomor . $shift_code;
    }

    return $data;
}

public function get_data_mesin()
{
    $this->db->select('rf.*, tp.uuid, rf.uuid as rjfiller_uuid, tp.tanggal, m.nama_mesin, tp.varian');
    $this->db->from('rj_filler rf');
    $this->db->join('t_planning tp', 'tp.uuid = rf.t_planning_uuid', 'left');
    $this->db->join('mesin m', 'm.device_id = rf.mesin_uuid', 'left');
    $this->db->join('users u', 'u.uuid = rf.operator_uuid', 'left');
    $this->db->where('rf.deleted_at', null);
    $this->db->where('rf.mesin_uuid IS NOT NULL', null, false);
    $this->db->where('rf.mesin_uuid !=', '');
    $this->db->order_by('rf.created_at', 'ASC');
    $data = $this->db->get()->result();

    foreach ($data as $val) {
        $val->tgl = date('d M Y', strtotime($val->tanggal));
        if ($val->varian == 1) {
            $val->varian_name = 'OKEY';
        } elseif ($val->varian == 2) {
            $val->varian_name = 'CHAMP AYAM';
        } elseif ($val->varian == 3) {
            $val->varian_name = 'CHAMP SAPI';
        } elseif ($val->varian == 4) {
            $val->varian_name = 'CHAMP OTAK-OTAK';
        }

    }

    return $data;
}

public function get_data_operator()
{
    $this->db->select('rf.*, tp.tanggal, u.fullname, tp.varian');
    $this->db->from('rj_filler rf');
    $this->db->join('t_planning tp', 'tp.uuid = rf.t_planning_uuid', 'left');
        // $this->db->join('mesin m', 'm.uuid = rf.mesin_uuid', 'left');
    $this->db->join('users u', 'u.uuid = rf.operator_uuid', 'left');
    $this->db->where('rf.deleted_at', null);
    $this->db->where('rf.operator_uuid IS NOT NULL', null, false);
        $this->db->where('rf.operator_uuid !=', ''); // ini untuk cek tidak kosong
        $this->db->order_by('rf.created_at', 'ASC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            if ($val->varian == 1) {
                $val->varian_name = 'OKEY';
            } elseif ($val->varian == 2) {
                $val->varian_name = 'CHAMP AYAM';
            } elseif ($val->varian == 3) {
                $val->varian_name = 'CHAMP SAPI';
            } elseif ($val->varian == 4) {
                $val->varian_name = 'CHAMP OTAK-OTAK';
            }
        }
        return $data;
    }

    public function get_kode_batch($varian_uuid)
    {
        $this->db->select('mn.batch_ke');
        $this->db->from('tbatch mn');
        $this->db->join('t_planning tp', 'tp.uuid = mn.t_planning_uuid', 'left');
        $this->db->where('tp.varian', $varian_uuid);
        // $this->db->where('mn.MN_IS_DELETE', 0);
        $this->db->order_by('mn.batch_ke', 'DESC');
        $this->db->limit(5);
        $data = $this->db->get()->result();

        return $data;
    }

    public function get_planning()
    {
        $this->db->select('tp.tanggal, tp.varian, tp.uuid');
        $this->db->from('t_planning tp');
        // $this->db->join('tbatch', 'tp.uuid = tb.t_planning_uuid', 'left');
        $this->db->order_by('tp.tanggal', 'DESC');
    // $this->db->limit(5);
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            if ($val->varian == 1) {
                $val->varian_name = 'OKEY';
            } elseif ($val->varian == 2) {
                $val->varian_name = 'CHAMP AYAM';
            } elseif ($val->varian == 3) {
                $val->varian_name = 'CHAMP SAPI';
            } elseif ($val->varian == 4) {
                $val->varian_name = 'CHAMP OTAK-OTAK';
            }

            $val->format_tanggal = date('d M Y', strtotime($val->tanggal));
        }
        return $data;
    }

    public function get_mesin_filler()
    {
        $filler = $this->filler;
        $this->db->select('m.uuid, m.nama_mesin, m.device_id');
        $this->db->from('mesin m');
        $this->db->where('m.area_uuid', $filler);

    // Kondisi LIKE untuk nama_mesin: zap%, kap%, cap%
    $this->db->group_start(); // buka grup kondisi OR
    $this->db->like('m.nama_mesin', 'zap', 'after');
    $this->db->or_like('m.nama_mesin', 'kap', 'after');
    $this->db->or_like('m.nama_mesin', 'cap', 'after');
    $this->db->group_end(); // tutup grup kondisi OR
    $this->db->order_by('m.nama_mesin', 'ASC');
    $data = $this->db->get()->result();

    return $data;
}


public function get_operator()
{
    $produksi = $this->config->item('produksi_dept');

    $this->db->select('u.uuid, u.fullname, sr.subrole');
    $this->db->from('users u');
    $this->db->join('sub_role sr', 'sr.users_uuid = u.uuid', 'left');
    $this->db->where('u.departemen', $produksi);
    $this->db->where('u.type', 3);
    $this->db->where('sr.subrole', 1);
    $this->db->order_by('u.fullname', 'ASC');
    $data = $this->db->get()->result();

    return $data;
}

// public function insert()
// {
//   $uuid = Uuid::uuid4()->toString();
//   $planning_uuid = $this->input->post('planning_uuid');
//   $kode_batch = $this->input->post('kode_batch');
//   $mesin_uuid = $this->input->post('mesin_uuid');
//   $operator_uuid = $this->input->post('operator_uuid');
//   $berat = $this->input->post('berat');
//   $keterangan = $this->input->post('keterangan');

//   $data = array(
//     'uuid'          => $uuid,
//     'user_uuid'     => $this->Auth_model->current_user()->uuid,
//     'planning_uuid'      => $planning_uuid,
//     'kode_batch' => $kode_batch,
//     'mesin_uuid'        => $mesin_uuid,
//     'operator_uuid'        => $operator_uuid,
//     'berat'        => $berat,
//     'keterangan'        => $keterangan
// );

//   $this->db->insert('rj_filler', $data);
//   return ($this->db->affected_rows() > 0) ? true : false;
// }

public function insert_mesin()
{
    $data = array();
    $performas = $this->input->post('performa');
    $device_ids = $this->input->post('mesin');
    $plan = $this->input->post('t_planning');

    for ($i = 0; $i < count($performas); $i++) {
        $uid = Uuid::uuid4()->toString();
        $data[] = array(
            'uuid' => $uid,
            't_planning_uuid' => $plan,
            'mesin_uuid' => $device_ids[$i],
            'berat' => $performas[$i]
        );
    }

    $this->db->insert_batch('rj_filler', $data);
    return ($this->db->affected_rows() > 0) ? true : false;
}

public function insert_operator()
{
  $uuid = Uuid::uuid4()->toString();
  $t_planning_uuid = $this->input->post('t_planning');
  $mesin_uuid = $this->input->post('mesin_uuid');
  $operator_uuid = $this->input->post('operator_uuid');
  $berat = $this->input->post('berat');
  $batch = $this->input->post('batch');

  if (!is_array($operator_uuid) || !is_array($berat) || count($operator_uuid) !== count($berat)) {
    return false;
}

$insert_data = [];

for ($i = 0; $i < count($operator_uuid); $i++) {
    if (!empty($operator_uuid[$i]) && is_numeric($berat[$i])) {
        $insert_data[] = [
            'uuid' => Uuid::uuid4()->toString(),
            'operator_uuid' => $operator_uuid[$i],
            'berat' => $berat[$i],
            'mesin_uuid' => $mesin_uuid,
            'kode_batch' => $batch,
            't_planning_uuid' => $t_planning_uuid
        ];
    }
}

if (!empty($insert_data)) {
    return $this->db->insert_batch('rj_filler', $insert_data);
}

return false;
}

public function update($uuid)
{
  $berat = $this->input->post('berat');
  $keterangan = $this->input->post('keterangan');

  $data = array(
    'berat'        => $berat,
    'keterangan'        => $keterangan
);

  $this->db->update('rj_filler', $data, array('uuid' => $uuid)); // query update
  return ($this->db->affected_rows() > 0) ? true : false;
}

public function get_total_berat_mesin()
{
    $this->db->select('rf.mesin_uuid, FORMAT(SUM(berat), 2) as total_berat, m.nama_mesin');
    $this->db->from('rj_filler rf');
    $this->db->join('mesin m', 'm.uuid = rf.mesin_uuid', 'left');
    $this->db->where('rf.deleted_at IS NULL');
    $this->db->group_by('rf.mesin_uuid, m.nama_mesin');
    $data = $this->db->get()->result();
    return $data;
}

public function get_total_berat_operator()
{
    $this->db->select('rf.mesin_uuid, SUM(berat) as berat_mesin, tp.formula');
    $this->db->from('rj_filler rf');
    $this->db->join('users u', 'u.uuid = rf.operator_uuid', 'left');
    $this->db->where('rf.deleted_at IS NULL');
    $this->db->where('rf.operator_uuid IS NOT NULL', null, false);
    $this->db->group_by('rf.operator_uuid, u.fullname');
    $data = $this->db->get()->result();
    return $data;
}

public function get_total_berat_per_mesin($varian_uuid)
{
    $today = date('Y-m-d');

    $this->db->select('tp.uuid AS planning_uuid, tp.formula, rf.mesin_uuid, SUM(rf.berat) as total_berat');
    $this->db->from('rj_filler rf');
    $this->db->join('t_planning tp', 'tp.uuid = rf.t_planning_uuid');
    $this->db->where('tp.varian', $varian_uuid);
    $this->db->where('MONTH(tp.tanggal)', date('m'));
    $this->db->where('YEAR(tp.tanggal)', date('Y'));
    $this->db->where('DATE(tp.tanggal) <', $today);
    $this->db->where('rf.deleted_at IS NULL');
    $this->db->group_by(['rf.mesin_uuid', 'rf.t_planning_uuid', 'tp.formula']);
    $data = $this->db->get()->result();

    // Kelompokkan data per mesin
    $mesin_data = [];

    foreach ($data as $row) {
        $persen = ($row->formula > 0) ? ($row->total_berat / $row->formula) * 100 : 0;

        if (!isset($mesin_data[$row->mesin_uuid])) {
            $mesin_data[$row->mesin_uuid] = [
                'mesin_uuid' => $row->mesin_uuid,
                'persentase_list' => [],
                'formula_total' => 0,
                'berat_total' => 0,
                'jumlah_plan' => 0
            ];
        }

        $mesin_data[$row->mesin_uuid]['persentase_list'][] = $persen;
        $mesin_data[$row->mesin_uuid]['formula_total'] += $row->formula;
        $mesin_data[$row->mesin_uuid]['berat_total'] += $row->total_berat;
        $mesin_data[$row->mesin_uuid]['jumlah_plan']++;
    }

    // Hitung rata-rata per mesin
    $result = [];
    foreach ($mesin_data as $mesin) {
        $avg = count($mesin['persentase_list']) > 0
            ? round(array_sum($mesin['persentase_list']) / count($mesin['persentase_list']), 2)
            : 0;

        $result[] = [
            'mesin_uuid' => $mesin['mesin_uuid'],
            'rata_reject' => number_format($avg, 2, '.', '')
            // 'berat_total' => round($mesin['berat_total'], 2),
            // 'formula_total' => round($mesin['formula_total'], 2),
            // 'jumlah_plan' => $mesin['jumlah_plan']
        ];
    }

    return $result;
}



public function get_total_berat_per_operator($varian_uuid)
{
    $today = date('Y-m-d');

    // Step 1: Ambil semua planning di bulan berjalan
    $this->db->select('uuid, formula');
    $this->db->from('t_planning');
    $this->db->where('varian', $varian_uuid);
    $this->db->where('MONTH(tanggal)', date('m'));
    $this->db->where('YEAR(tanggal)', date('Y'));
    $this->db->where('DATE(tanggal) <', $today);
    $plannings = $this->db->get()->result();

    $operator_data = [];

    // Step 2: Loop tiap planning
    foreach ($plannings as $plan) {
        $this->db->select('rf.operator_uuid, u.fullname, SUM(rf.berat) as total_berat');
        $this->db->from('rj_filler rf');
        $this->db->join('users u', 'u.uuid = rf.operator_uuid', 'left');
        $this->db->where('rf.deleted_at IS NULL');
        $this->db->where('rf.t_planning_uuid', $plan->uuid);
        // $this->db->where('rf.operator_uuid IS NOT NULL', null, false);
        $this->db->group_by('rf.operator_uuid, u.fullname');
        $operators = $this->db->get()->result();

        foreach ($operators as $op) {
            $percent = ($plan->formula > 0) ? ($op->total_berat / $plan->formula) * 100 : 0;

            if (!isset($operator_data[$op->operator_uuid])) {
                $operator_data[$op->operator_uuid] = [
                    'fullname' => $op->fullname,
                    'persentase_list' => [],
                ];
            }

            $operator_data[$op->operator_uuid]['persentase_list'][] = $percent;
        }
    }

    // Step 3: Hitung rata-rata kontribusi per operator
    $result = [];
    foreach ($operator_data as $op) {
        $count = count($op['persentase_list']);
        $avg = ($count > 0) ? round(array_sum($op['persentase_list']) / $count, 2) : 0;

        $result[] = [
            'fullname' => $op['fullname'],
            'rata_persen' => $avg
        ];
    }

    return $result;
}


public function mapYearToLetter($year)
{
    $offset = ($year - 2010) % 26;
    if ($offset < 0) {
        $offset += 26;
    }
        $letterCode = 65 + $offset; // ASCII A = 65
        return chr($letterCode);
    }

    // Fungsi untuk mengubah bulan ke huruf
    public function mapMonthToLetter($month) {
        $monthMap = [
            '1'  => 'A',
            '2'  => 'B',
            '3'  => 'C',
            '4'  => 'D',
            '5'  => 'E',
            '6'  => 'F',
            '7'  => 'G',
            '8'  => 'H',
            '9'  => 'I',
            '10' => 'J',
            '11' => 'K',
            '12' => 'L',
        ];
        return isset($monthMap[$month]) ? $monthMap[$month] : '';
    }

    // Fungsi utama untuk generate kode batch
    public function generateKodeBatch($batch_nomor_baru, $batch_shift_js = 'AA0', $nextday = false) {
        $currentDate = new DateTime();
        if ($nextday) {
            $currentDate->modify('+1 day');
        }

        $year  = (int) $currentDate->format('Y');
        $month = (int) $currentDate->format('n');
        $day   = $currentDate->format('d');

        $yearLetter  = $this->mapYearToLetter($year);
        $monthLetter = $this->mapMonthToLetter($month);
        $kodeplant   = '7';

        // Format nomor batch 2 digit
        $batch_nomor_baru = str_pad($batch_nomor_baru, 2, '0', STR_PAD_LEFT);

        // Gabungkan kode batch
        $kodeBatch = $yearLetter . $monthLetter . $day . $kodeplant . $batch_nomor_baru . $batch_shift_js;

        return $kodeBatch;
    }

    public function get_by_uuid($uuid)
    {
        return $this->db->get_where('rj_filler', array('uuid' => $uuid))->row();
    }

    public function update_rjmesin($uuid)
    {
        $uuid           = $this->input->post('uuid');
        $berat          = $this->input->post('berat');
        $mesin          = $this->input->post('mesin');

        $data = array(
            'berat'            => $berat,
            'mesin_uuid'        => $mesin,
            'modified_at' => date('Y-m-d h:i:s')
        );  
        $this->db->update('rj_filler', $data, array('uuid' => $uuid));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function update_rjopr($uuid)
    {
        $uuid           = $this->input->post('uuid');
        $berat          = $this->input->post('berat');
        $operator          = $this->input->post('operator');
        $mesin          = $this->input->post('mesin');

        $data = array(
            'berat'                 => $berat,
            'operator_uuid'         => $operator,
            'mesin_uuid'            => $mesin,
            'modified_at'           => date('Y-m-d h:i:s')
        );  
        $this->db->update('rj_filler', $data, array('uuid' => $uuid));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function delete_rjmesin($uuid)
    {
        $data = array(
            'deleted_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('rj_filler', $data, array('uuid' => $uuid ));
        return ($this->db->affected_rows() > 0) ? true : false;
    }


}