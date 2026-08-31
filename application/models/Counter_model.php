<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Counter_model extends CI_Model
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
                'field' => 'date',
                'label' => 'Tanggal',
                'rules' => 'required'
            ],
            [
                'field' => 'varian',
                'label' => 'Varian',
                'rules' => 'required'
            ]
        ];
    }
    private function parse_kode_batch($kode)
    {
        $bulan_map = [
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4,
            'E' => 5,
            'F' => 6,
            'G' => 7,
            'H' => 8,
            'I' => 9,
            'J' => 10,
            'K' => 11,
            'L' => 12
        ];
        $tahun = 2010 + (ord(strtoupper(substr($kode, 0, 1))) - ord('A'));
        $bulan  = $bulan_map[substr($kode, 1, 1)];
        $tanggal = substr($kode, 2, 2);
        $batch  = (int) substr($kode, 5, 2);
        return [
            'tanggal_produksi' => $tahun . '-' . $bulan . '-' . $tanggal,
            'batch_ke' => $batch
        ];
    }
    private function generate_kode_batch($tanggal_produksi, $batch_ke)
    {
        $bulan_map = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
            7 => 'G',
            8 => 'H',
            9 => 'I',
            10 => 'J',
            11 => 'K',
            12 => 'L'
        ];
        $tahun = date('Y', strtotime($tanggal_produksi));
        $bulan = (int) date('n', strtotime($tanggal_produksi));
        $hari  = date('d', strtotime($tanggal_produksi));
        $plant = 7;
        $kode_tahun = chr(($tahun - 2010) + ord('A'));
        return sprintf(
            '%s%s%s%d%02dAA0',
            $kode_tahun,
            $bulan_map[$bulan],
            $hari,
            $plant,
            $batch_ke
        );
    }
    public function get_all()
    {
        $this->db->select('p.*, v.varian as vrn, v.keterangan');
        $this->db->from('t_planning p');
        $this->db->join('varian v', 'v.uuid = p.varian', 'left');
        $this->db->where('p.deleted_at IS NULL', null, false);
        $this->db->order_by('tanggal', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $tgl = strtotime($val->tanggal);
            $val->tanggal = date('d-m-Y', $tgl);
        }
        return $data;
    }
    public function tambah_batch($t_planning_uuid)
    {
        $this->db->select('tp.*');
        $this->db->from('t_planning tp');
        $this->db->where('tp.uuid', $t_planning_uuid);
        $planning = $this->db->get()->row();
        $this->db->trans_start();
        $batch_uuid = Uuid::uuid4()->toString();
        $kode_batch = strtoupper(trim($this->input->post('kode_batch')));
        $total_counter = $this->input->post('total_counter');
        $parse = $this->parse_kode_batch($kode_batch);
        $varian = $planning->varian;
        // ======================
        // 1. INSERT TBATCH
        // ======================
        $data = [
            'uuid'              => $batch_uuid,
            'user_uuid'         => $this->auth_model->current_user()->uuid,
            'username'          => $this->auth_model->current_user()->username,
            't_planning_uuid'   => $t_planning_uuid,
            'varian_uuid'       => $varian,
            'kode_batch'        => $kode_batch,
            'total'             => $total_counter,
            'tanggal_produksi'  => $parse['tanggal_produksi'],
            'batch_ke'          => $parse['batch_ke']
        ];
        $cek = $this->db
            ->where('kode_batch', $kode_batch)
            ->where('t_planning_uuid', $t_planning_uuid)
            ->get('tbatch')
            ->row();
        if ($cek) {
            return false;
        }
        $this->db->insert('tbatch', $data);
        if ($this->db->affected_rows() <= 0) {
            $this->db->trans_rollback();
            return false;
        }
        // ======================
        // 2. INSERT MP_USAGE
        // ======================
        $mp_data = [
            'uuid'            => Uuid::uuid4()->toString(),
            'created_by'      => $this->auth_model->current_user()->uuid,
            't_planning_uuid' => $t_planning_uuid,
            'tbatch_uuid'      => $batch_uuid,
            'varian_uuid'     => $planning->varian,
            'kode_batch'      => $kode_batch,
            'formula_kg'      => 0,
            'rework_kg'       => 0,
            'total_output'    => 0,
            'created_at'      => date('Y-m-d H:i:s')
        ];
        $this->db->insert('mp_usage', $mp_data);
        $this->db->trans_complete();
        return $this->db->trans_status() ? $batch_uuid : false;
    }
    public function get_data_form()
    {
        $this->db->select("tb.tanggal_produksi, tp.varian as varian_uuid, v.varian, v.keterangan, COUNT(DISTINCT tb.uuid) as total_batch");
        $this->db->from('tbatch tb');
        $this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid');
        $this->db->join('varian v', 'v.uuid = tp.varian');
        $this->db->group_by(['tb.tanggal_produksi', 'tp.varian', 'v.varian', 'v.keterangan']);
        $this->db->order_by('tb.tanggal_produksi', 'DESC');
        $this->db->order_by('v.varian', 'ASC');
        return $this->db->get()->result();
    }
    public function insert_counter($batch_uuid)
    {
        $batch = $this->db->get_where('tbatch', ['uuid' => $batch_uuid])->row();
        if (!$batch) {
            return false;
        }
        $counters    = $this->input->post('counter');
        $speeds      = $this->input->post('speed');
        $device_ids  = $this->input->post('device_id');
        $mesin_uuids = $this->input->post('mesin_uuid');
        if (empty($counters) || empty($device_ids) || empty($mesin_uuids)) {
            return false;
        }
        $data = [];
        for ($i = 0; $i < count($device_ids); $i++) {
            $counter    = isset($counters[$i]) ? (int)$counters[$i] : 0;
            $device_id  = isset($device_ids[$i]) ? $device_ids[$i] : null;
            $mesin_uuid = isset($mesin_uuids[$i]) ? $mesin_uuids[$i] : null;
            $speed      = isset($speeds[$i]) ? (int)$speeds[$i] : 0;
            if (!$device_id || !$mesin_uuid) {
                continue;
            }
            $data[] = [
                'uuid'        => Uuid::uuid4()->toString(),
                'tbatch_uuid' => $batch->uuid,
                'mesin_uuid'  => $mesin_uuid,
                'device_id'   => $device_id,
                'counter'     => $counter,
                'speed'       => $speed,
                'created_at'  => $batch->created_at
            ];
        }
        if (empty($data)) {
            return false;
        }
        $this->db->insert_batch('tcounter', $data);
        return ($this->db->affected_rows() > 0);
    }
    public function updatebatch($uuid)
    {
        $kode_batch = strtoupper(
            trim($this->input->post('kode_batch'))
        );
        $total_counter = $this->input->post('total_counter');
        $parse = $this->parse_kode_batch($kode_batch);
        $batch_ke         = $parse['batch_ke'];
        $tanggal_produksi = $parse['tanggal_produksi'];
        $counters    = $this->input->post('counter');
        $device_ids  = $this->input->post('device_id');
        $mesin_uuids = $this->input->post('mesin_uuid');
        $speeds      = $this->input->post('speed');
        $created_at = $this->input->post('waktu_ganti');
        // Ambil batch lama
        $batch = $this->db
            ->where('uuid', $uuid)
            ->get('tbatch')
            ->row();
        if (!$batch) {
            return [
                'status' => false,
                'msg'    => 'Data batch tidak ditemukan.'
            ];
        }
        // Cek duplikat kode batch pada plan yang sama
        $cek = $this->db
            ->where('kode_batch', $kode_batch)
            ->where('t_planning_uuid', $batch->t_planning_uuid)
            ->where('uuid !=', $uuid)
            ->get('tbatch')
            ->row();
        if ($cek) {
            return [
                'status' => false,
                'msg'    => 'Kode batch sudah digunakan.'
            ];
        }
        $this->db->trans_start();
        // Update tbatch
        $data_batch = [
            'kode_batch'       => $kode_batch,
            'batch_ke'         => $batch_ke,
            'total'            => $total_counter,
            'tanggal_produksi' => $tanggal_produksi,
            'created_at'        => $created_at,
            'modified_at'      => date('Y-m-d H:i:s')
        ];
        $this->db->where('uuid', $uuid);
        $this->db->update('tbatch', $data_batch);
        // Update tcounter
        if (!empty($device_ids)) {
            foreach ($device_ids as $i => $device_id) {
                $data_counter = [
                    'counter'    => isset($counters[$i]) ? (int)$counters[$i] : 0,
                    'mesin_uuid' => isset($mesin_uuids[$i]) ? $mesin_uuids[$i] : null,
                    'speed'      => isset($speeds[$i]) ? (int)$speeds[$i] : 0,
                ];
                $this->db->where('tbatch_uuid', $uuid);
                $this->db->where('device_id', $device_id);
                $this->db->update('tcounter', $data_counter);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return [
                'status' => true,
                'msg'    => 'Data batch berhasil diperbarui.'
            ];
        }
        return [
            'status' => false,
            'msg'    => 'Data batch gagal diperbarui.'
        ];
    }
    public function updatecounter($uuid)
    {
        $counter             = $this->input->post('counter');
        $data = array(
            'counter'         => $counter
        );
        $this->db->where('uuid', $uuid);
        $this->db->update('tcounter', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    public function get_by_uuid($uuid)
    {
        $this->db->select('
        p.uuid,
        p.tanggal,
        p.varian,
        v.varian as vrn,
        v.keterangan,
        v.panjang
        ');
        $this->db->from('t_planning p');
        $this->db->join('varian v', 'v.uuid = p.varian', 'left');
        $this->db->where('p.uuid', $uuid);
        $data = $this->db->get()->row();
        if ($data) {
            $data->tgl  = date("d M Y", strtotime($data->tanggal));
            $data->film = !empty($data->panjang) ? ((float)$data->panjang / 100) : 0;
        }
        return $data;
    }
    public function get_batch_counter($uuid)
    {
        $this->db->select('
        tc.tbatch_uuid,
        tc.device_id,
        tc.counter,
        tc.mesin_uuid,
        tc.speed,
        tb.batch_ke,
        tb.kode_batch,
        tb.tanggal_produksi,
        tb.t_planning_uuid,
        m.nama_mesin,
        tb.created_at
        ');
        $this->db->from('tcounter tc');
        $this->db->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid');
        $this->db->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left');
        $this->db->where('tc.tbatch_uuid', $uuid);
        $this->db->order_by('m.nama_mesin', 'ASC');
        return $this->db->get()->result();
    }
    public function get_batch_uuid($uuid)
    {
        $data = $this->db->get_where('tbatch', array('uuid' => $uuid))->row();
        return $data;
    }
    public function get_counter_uuid($uuid)
    {
        $this->db->select('tc.*, m.nama_mesin');
        $this->db->from('tcounter tc');
        $this->db->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left');
        $this->db->where('tc.uuid', $uuid);
        return $this->db->get()->row();
    }
    public function delete_by_uuid($uuid)
    {
        $this->db->trans_start();
        $this->db->where('tbatch_uuid', $uuid);
        $this->db->delete('tcounter');
        $this->db->where('uuid', $uuid);
        $this->db->delete('tbatch');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function get_batch($uuid)
    {
        $this->db->select('b.*');
        $this->db->select('(SELECT SUM(c.counter) FROM tcounter c WHERE b.uuid = c.tbatch_uuid ) as total', false);
        $this->db->from('tbatch b');
        $this->db->where('t_planning_uuid', $uuid);
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $val->time = date('H:i', strtotime($val->created_at));
        }
        return $data;
    }
    public function get_counter($uuid)
    {
        $this->db->select('tc.*, m.nama_mesin, tp.uuid as plan_uuid');
        $this->db->from('tcounter tc');
        $this->db->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left');
        $this->db->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid', 'left');
        $this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
        $this->db->where('tc.tbatch_uuid', $uuid);
        $this->db->order_by('m.nama_mesin', 'ASC');
        return $this->db->get()->result();
    }
    public function get_counter_by_t_planning_uuid($uuid)
    {
        $this->db->select('
        tf.uuid as form_uuid,
        tf.varian,
        v.varian as nama_varian,
        v.panjang,
        tc.mesin_uuid,
        tc.device_id,
        m.nama_mesin,
        GROUP_CONCAT(tc.counter ORDER BY tb.batch_ke ASC) as counters,
        MAX(tb.batch_ke) as max_batch_ke
        ');
        $this->db->from('tcounter tc');
        $this->db->join('tbatch tb', 'tb.uuid = tc.tbatch_uuid', 'inner');
        $this->db->join('t_planning tf', 'tf.uuid = tb.t_planning_uuid', 'inner');
        $this->db->join('varian v', 'v.uuid = tf.varian', 'left');
        $this->db->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left');
        $this->db->where('tf.uuid', $uuid);
        $this->db->group_by([
            'tc.mesin_uuid',
            'tc.device_id',
            'm.nama_mesin',
            'tf.uuid',
            'tf.varian',
            'v.varian',
            'v.panjang'
        ]);
        $this->db->order_by('m.nama_mesin', 'ASC');
        $rows = $this->db->get()->result();
        foreach ($rows as $val) {
            $film = !empty($val->panjang) ? ((float)$val->panjang / 100) : 0;
            $counter_array = array_filter(explode(',', $val->counters), 'strlen');
            $counter_array = array_map('intval', $counter_array);
            $val->counter_array = $counter_array;
            $val->total = array_sum($counter_array);
            $val->film  = $film;
            $val->pvdc  = round($val->total * $film, 2);
            $val->wire  = round($val->total * 0.000302, 3);
        }
        return $rows;
    }
    public function get_speed_by_plan($t_planning_uuid)
    {
        $this->db->select('
        s.mesin_uuid,
        s.speed,
        m.device_id,
        m.nama_mesin
        ');
        $this->db->from('t_speed s');
        $this->db->join('mesin m', 'm.uuid = s.mesin_uuid', 'left');
        $this->db->where('s.t_planning_uuid', $t_planning_uuid);
        $result = $this->db->get()->result();
        $data = [];
        foreach ($result as $row) {
            $data[$row->device_id] = [
                'mesin_uuid' => $row->mesin_uuid,
                'device_id'  => $row->device_id,
                'nama_mesin' => $row->nama_mesin,
                'speed'      => (int)$row->speed
            ];
        }
        return $data;
    }
    public function get_form_header($tanggal_produksi, $varian_uuid)
    {
        $this->db->select('
        v.uuid,
        v.varian,
        v.keterangan,
        v.panjang
        ');
        $this->db->from('varian v');
        $this->db->where('v.uuid', $varian_uuid);
        $data = $this->db->get()->row();
        if ($data) {
            $data->tanggal_produksi = $tanggal_produksi;
            $data->tgl = date(
                'd M Y',
                strtotime($tanggal_produksi)
            );
            $data->film = ((float)$data->panjang / 100);
        }
        return $data;
    }
    public function get_form_counter($tanggal_produksi, $varian_uuid)
{
    $this->db->select("
        x.mesin_uuid,
        x.device_id,
        x.nama_mesin,
        GROUP_CONCAT(
            x.counter
            ORDER BY x.batch_ke ASC
        ) AS counters,
        GROUP_CONCAT(
            x.kode_batch
            ORDER BY x.batch_ke ASC
        ) AS kode_batch,
        MAX(x.batch_ke) AS max_batch_ke,
        x.panjang
    ");
    $this->db->from("
        (
            SELECT
                tc.mesin_uuid,
                tc.device_id,
                m.nama_mesin,
                tb.kode_batch,
                MIN(tb.batch_ke) AS batch_ke,
                v.panjang,
                SUM(tc.counter) AS counter
            FROM tcounter tc
            JOIN tbatch tb
                ON tb.uuid = tc.tbatch_uuid
            JOIN t_planning tp
                ON tp.uuid = tb.t_planning_uuid
            JOIN varian v
                ON v.uuid = tp.varian
            JOIN mesin m
                ON m.uuid = tc.mesin_uuid
            WHERE tb.tanggal_produksi = " .
                $this->db->escape($tanggal_produksi) . "
            AND tp.varian = " .
                $this->db->escape($varian_uuid) . "
            GROUP BY
                tc.mesin_uuid,
                tc.device_id,
                m.nama_mesin,
                tb.kode_batch,
                v.panjang
        ) x
    ");
    $this->db->group_by([
        'x.mesin_uuid',
        'x.device_id',
        'x.nama_mesin',
        'x.panjang'
    ]);
    $this->db->order_by(
        'x.nama_mesin',
        'ASC'
    );
    $rows = $this->db->get()->result();
    foreach ($rows as $val) {
        $film = ((float) $val->panjang / 100);
        $counter_array = array_map(
            'intval',
            explode(',', $val->counters)
        );
        $val->counter_array = $counter_array;
        $val->total = array_sum(
            $counter_array
        );
        $val->pvdc = round(
            $val->total * $film,
            2
        );
        $val->wire = round(
            $val->total * 0.000302,
            3
        );
    }
    return $rows;
}
    public function get_next_batch_data($t_planning_uuid)
    {
        $tanggal_produksi = date('Y-m-d');
        $row = $this->db
            ->select_max('batch_ke')
            ->where('t_planning_uuid', $t_planning_uuid)
            ->where('tanggal_produksi', $tanggal_produksi)
            ->get('tbatch')
            ->row();
        $batch_ke = ($row && $row->batch_ke)
            ? $row->batch_ke + 1
            : 1;
        return [
            'tanggal_produksi' => $tanggal_produksi,
            'batch_ke'         => $batch_ke,
            'kode_batch'       => $this->generate_kode_batch(
                $tanggal_produksi,
                $batch_ke
            )
        ];
    }
}
