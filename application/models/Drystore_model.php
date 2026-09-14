<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Drystore_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Proses_model');
        $this->load->model('Counter_model');
        //$this->dberetort = $this->load->database('e-retort', TRUE);
    }
    public function rules()
    {
        return [
            [
                'field' => 'varian_uuid',
                'label' => 'Varian',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi !',
                ]
            ],
            [
                'field' => 'jumlah_badpro[]',
                'label' => 'Jumlah Bad Produk',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => '{field} wajib diisi !',
                    'numeric' => '{field} harus berupa angka !',
                ]
            ]
        ];
    }
    public function rules_type()
    {
        return [
            [
                'field' => 'nama',
                'label' => 'Type',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ],
            [
                'field' => 'std_waste',
                'label' => 'Standar %',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ],
            [
                'field' => 'satuan',
                'label' => 'Satuan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ]
        ];
    }
    public function rules_waste()
    {
        return [
            [
                'field' => 'nama',
                'label' => 'Type',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ]
        ];
    }
    /**
     * Generate UUID
     */
    /* =========================================================
     * DRYSTORE
     * ========================================================= */
    public function get_by_tanggal($tanggal)
    {
        return $this->db
            ->where('tanggal', $tanggal)
            ->get('drystore')
            ->row();
    }
    public function get_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore')
            ->row();
    }
    public function get_all()
    {
        return $this->db
            ->order_by('tanggal', 'DESC')
            ->get('drystore')
            ->result();
    }
    public function get_type()
    {
        $this->db->select('dt.*');
        $this->db->from('drystore_type dt');
        $this->db->order_by('dt.nama', 'ASC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $val->varian = [];
            if (!empty($val->varian_uuid)) {
                $varian_uuid = explode(',', $val->varian_uuid);
                $this->db->select('v.varian');
                $this->db->where_in('uuid', $varian_uuid);
                $val->varian = $this->db->get('varian v')->result();
            }
        }
        return $data;
    }
    public function get_waste()
    {
        return $this->db
            ->order_by('nama', 'DESC')
            ->get('drystore_waste')
            ->result();
    }
    /**
     * Ambil semua Type Packaging aktif
     */
    public function get_all_type()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('drystore_type')
            ->result();
    }
    /**
     * Ambil semua Waste aktif
     */
    public function get_all_waste()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('drystore_waste')
            ->result();
    }
    /**
     * Ambil transaksi berdasarkan drystore
     */
    public function get_transaksi($drystore_uuid)
{
    return $this->db
        ->select('
            t.*,
            dt.nama AS type_nama,
            dt.satuan AS type_satuan,
            dt.std_waste,
            dt.perbox,
            dw.nama AS waste_nama
        ')
        ->from(
            'drystore_waste_transaksi t'
        )
        ->join(
            'drystore_type dt',
            'dt.uuid = t.type_uuid',
            'left'
        )
        ->join(
            'drystore_waste dw',
            'dw.uuid = t.waste_uuid',
            'left'
        )
        ->where(
            't.drystore_uuid',
            $drystore_uuid
        )
        ->order_by(
            'dt.nama',
            'ASC'
        )
        ->order_by(
            'dw.nama',
            'ASC'
        )
        ->get()
        ->result();
}
    /**
     * Bentuk data transaksi menjadi:
     *
     * $data[type_uuid][waste_uuid] = berat
     */
    public function get_transaksi_matrix($drystore_uuid)
{
    $rows = $this->get_transaksi($drystore_uuid);
    $matrix = [];
    foreach ($rows as $row) {
        $matrix[$row->type_uuid][$row->waste_uuid] = [
            'berat'      => (float) $row->berat,
            'onproduk'   => (float) $row->onproduk,
            'penggunaan' => (float) $row->penggunaan
        ];
    }
    return $matrix;
}
    /**
     * SIMPAN DRYSTORE HARIAN
     */
    public function insert_harian(
    $tanggal,
    $post,
    $user_uuid = null
) {
    $this->db->trans_begin();
    try {
        // ==========================================
        // CEK DATA DRYSTORE
        // ==========================================
        $drystore = $this->get_by_tanggal($tanggal);
        if ($drystore) {
            throw new Exception(
                'Data Drystore untuk tanggal tersebut sudah ada.'
            );
        }
        // ==========================================
        // AMBIL RELEASE
        // ==========================================
        $release = $this->get_release($tanggal);
        // Buat UUID Drystore
        $drystore_uuid = Uuid::uuid4()->toString();
        // ==========================================
        // INSERT HEADER
        // ==========================================
        $header = [
            'uuid'       => $drystore_uuid,
            'tanggal'    => $tanggal,
            'user_uuid'  => $user_uuid,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert(
            'drystore',
            $header
        );
        if ($this->db->affected_rows() <= 0) {
            throw new Exception(
                'Gagal membuat transaksi Drystore.'
            );
        }
        // ==========================================
        // INSERT DETAIL
        // ==========================================
        if (!empty($post['waste'])) {
            foreach (
                $post['waste']
                as $type_uuid => $wastes
            ) {
                // --------------------------------------
                // Ambil master type
                // --------------------------------------
                $type = $this->get_type_by_uuid(
                    $type_uuid
                );
                if (!$type) {
                    continue;
                }
                // --------------------------------------
                // HITUNG ON PRODUK
                // --------------------------------------
                $onproduk =
                    $this->get_onproduk_by_type(
                        $type,
                        $release
                    );
                // --------------------------------------
                // Varian yang digunakan type
                // --------------------------------------
                $varian_uuid =
                    !empty($type->varian_uuid)
                    ? $type->varian_uuid
                    : null;
                // --------------------------------------
                // LOOP WASTE
                // --------------------------------------
                foreach (
                    $wastes
                    as $waste_uuid => $berat
                ) {
                    $berat = trim($berat);
                    if ($berat === '') {
                        $berat = 0;
                    }
                    $berat = (float) $berat;
                    // Tidak menyimpan baris
                    // apabila waste dan onproduk
                    // sama-sama 0
                    if (
                        $berat <= 0 &&
                        $onproduk <= 0
                    ) {
                        continue;
                    }
                    // ==================================
                    // PENGGUNAAN
                    // ==================================
                    $penggunaan =
                        $berat + $onproduk;
                    $data = [
                        'uuid'          =>
                            Uuid::uuid4()->toString(),
                        'drystore_uuid' =>
                            $drystore_uuid,
                        'type_uuid'     =>
                            $type_uuid,
                        'varian_uuid'   =>
                            $varian_uuid,
                        'waste_uuid'    =>
                            $waste_uuid,
                        'berat'         =>
                            $berat,
                        'onproduk'      =>
                            $onproduk,
                        'penggunaan'    =>
                            $penggunaan,
                        'tanggal'       =>
                            $tanggal,
                        'user_uuid'     =>
                            $user_uuid,
                        'created_at'    =>
                            date('Y-m-d H:i:s')
                    ];
                    $this->db->insert(
                        'drystore_waste_transaksi',
                        $data
                    );
                    if (
                        $this->db->affected_rows() <= 0
                    ) {
                        throw new Exception(
                            'Gagal menyimpan detail waste.'
                        );
                    }
                }
            }
        }
        // ==========================================
        // CEK TRANSAKSI
        // ==========================================
        if (
            $this->db->trans_status() === false
        ) {
            throw new Exception(
                'Transaksi database gagal.'
            );
        }
        $this->db->trans_commit();
        return $drystore_uuid;
    } catch (Exception $e) {
        $this->db->trans_rollback();
        return [
            'error' => $e->getMessage()
        ];
    }
}
    /**
     * UPDATE DRYSTORE
     */
    public function update_harian(
    $drystore_uuid,
    $post,
    $user_uuid = null
) {
    $this->db->trans_begin();
    try {
        $drystore = $this->get_by_uuid(
            $drystore_uuid
        );
        if (!$drystore) {
            throw new Exception(
                'Data Drystore tidak ditemukan.'
            );
        }
        // ==========================================
        // AMBIL RELEASE BERDASARKAN TANGGAL
        // ==========================================
        $release = $this->get_release(
            $drystore->tanggal
        );
        // ==========================================
        // HAPUS DETAIL LAMA
        // ==========================================
        $this->db
            ->where(
                'drystore_uuid',
                $drystore_uuid
            )
            ->delete(
                'drystore_waste_transaksi'
            );
        // ==========================================
        // INSERT ULANG
        // ==========================================
        if (!empty($post['waste'])) {
            foreach (
                $post['waste']
                as $type_uuid => $wastes
            ) {
                $type =
                    $this->get_type_by_uuid(
                        $type_uuid
                    );
                if (!$type) {
                    continue;
                }
                // ==================================
                // HITUNG ON PRODUK
                // ==================================
                $onproduk =
                    $this->get_onproduk_by_type(
                        $type,
                        $release
                    );
                $varian_uuid =
                    !empty($type->varian_uuid)
                    ? $type->varian_uuid
                    : null;
                foreach (
                    $wastes
                    as $waste_uuid => $berat
                ) {
                    $berat = trim($berat);
                    if ($berat === '') {
                        $berat = 0;
                    }
                    $berat = (float) $berat;
                    if (
                        $berat <= 0 &&
                        $onproduk <= 0
                    ) {
                        continue;
                    }
                    $penggunaan =
                        $berat + $onproduk;
                    $data = [
                        'uuid' =>
                            Uuid::uuid4()->toString(),
                        'drystore_uuid' =>
                            $drystore_uuid,
                        'type_uuid' =>
                            $type_uuid,
                        'varian_uuid' =>
                            $varian_uuid,
                        'waste_uuid' =>
                            $waste_uuid,
                        'tanggal' =>
                            $drystore->tanggal,
                        'berat' =>
                            $berat,
                        'onproduk' =>
                            $onproduk,
                        'penggunaan' =>
                            $penggunaan,
                        'user_uuid' =>
                            $user_uuid,
                        'created_at' =>
                            date('Y-m-d H:i:s')
                    ];
                    $this->db->insert(
                        'drystore_waste_transaksi',
                        $data
                    );
                    if (
                        $this->db->affected_rows() <= 0
                    ) {
                        throw new Exception(
                            'Gagal memperbarui detail waste.'
                        );
                    }
                }
            }
        }
        // ==========================================
        // UPDATE HEADER
        // ==========================================
        $this->db
            ->where(
                'uuid',
                $drystore_uuid
            )
            ->update(
                'drystore',
                [
                    'user_uuid' =>
                        $user_uuid,
                    'updated_at' =>
                        date('Y-m-d H:i:s')
                ]
            );
        if (
            $this->db->trans_status() === false
        ) {
            throw new Exception(
                'Update database gagal.'
            );
        }
        $this->db->trans_commit();
        return true;
    } catch (Exception $e) {
        $this->db->trans_rollback();
        return [
            'error' => $e->getMessage()
        ];
    }
}
    /* =========================================================
     * MASTER TYPE
     * ========================================================= */
    public function insert_type()
    {
        $uuid = Uuid::uuid4()->toString();
        $nama = $this->input->post('nama');
        $std_waste = $this->input->post('std_waste');
        $satuan = $this->input->post('satuan');
        $varian = $this->input->post('varian');
        $varian_uuid = !empty($varian)
            ? implode(',', $varian)
            : null;
        $data = array(
            'uuid'        => $uuid,
            'nama'        => $nama,
            'satuan'      => $satuan,
            'aktif'       => 1,
            'std_waste'   => $std_waste,
            'varian_uuid' => $varian_uuid,
            'user_uuid'   => $this->auth_model->current_user()->uuid
        );
        $this->db->insert('drystore_type', $data);
        return ($this->db->affected_rows() > 0);
    }
    public function insert_waste()
    {
        $uuid = Uuid::uuid4()->toString();
        $nama = $this->input->post('nama');
        $data = array(
            'uuid' => $uuid,
            'nama' => $nama,
            'aktif' => 1,
            'user_uuid'     => $this->auth_model->current_user()->uuid
        );
        $this->db->insert('drystore_waste', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    public function get_type_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore_type')
            ->row();
    }
    public function update_type($uuid)
    {
        $nama = $this->input->post('nama');
        $std_waste = $this->input->post('std_waste');
        $satuan = $this->input->post('satuan');
        $varian = $this->input->post('varian');
        $perbox = $this->input->post('perbox');
        // Select2 multiple menghasilkan array
        $varian_uuid = !empty($varian)
            ? implode(',', $varian)
            : null;
        $data = array(
            'nama'        => $nama,
            'std_waste'   => $std_waste,
            'user_uuid'   => $this->auth_model->current_user()->uuid,
            'updated_at'  => date('Y-m-d H:i:s'),
            'satuan'      => $satuan,
            'perbox'      => $perbox,
            'varian_uuid' => $varian_uuid
        );
        $this->db->update(
            'drystore_type',
            $data,
            array('uuid' => $uuid)
        );
        return ($this->db->affected_rows() > 0);
    }
    /* =========================================================
     * MASTER WASTE
     * ========================================================= */
    public function update_waste($uuid)
    {
        $nama = $this->input->post('nama');
        $data = array(
            'nama' => $nama,
            'user_uuid'     => $this->auth_model->current_user()->uuid,
            'updated_at'  => date('Y-m-d h:i:s')
        );
        $this->db->update('drystore_waste', $data, array('uuid' => $uuid));
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    public function get_waste_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore_waste')
            ->row();
    }
    public function get_release($tanggal)
    {
        $this->db->select('v.uuid AS varian_uuid,v.varian,SUM(s.jml_release) AS total_release');
        $this->db->from('sortasi s');
        $this->db->join('tbatch tb','tb.uuid = s.tbatch_uuid','left');
        $this->db->join('varian v', 'v.uuid = tb.varian_uuid', 'left');
        $this->db->where('DATE(s.created_at)', $tanggal);
        $this->db->where('s.deleted_at IS NULL', null, false);
        $this->db->group_by(['v.uuid','v.varian']);
        $this->db->order_by('v.varian','ASC');
        return $this->db->get()->result();
    }
    public function get_onproduk_by_type($type, $release)
{
    $total_release = 0;
    if (
        empty($type->varian_uuid) ||
        empty($release)
    ) {
        return 0;
    }
    $varian_uuid = array_filter(
        array_map(
            'trim',
            explode(',', $type->varian_uuid)
        )
    );
    foreach ($release as $row) {
        if (
            !empty($row->varian_uuid) &&
            in_array(
                $row->varian_uuid,
                $varian_uuid
            )
        ) {
            $total_release +=
                (float) $row->total_release;
        }
    }
    return $total_release * (float) $type->perbox;
}
public function get_detail($drystore_uuid)
{
    $this->db->select('
        dt.uuid AS type_uuid,
        dt.nama AS type_nama,
        dt.satuan AS type_satuan,
        dw.uuid AS waste_uuid,
        dw.nama AS waste_nama,
        t.berat,
        t.onproduk,
        t.penggunaan
    ');
    $this->db->from('drystore_waste_transaksi t');
    $this->db->join(
        'drystore_type dt',
        'dt.uuid = t.type_uuid',
        'left'
    );
    $this->db->join(
        'drystore_waste dw',
        'dw.uuid = t.waste_uuid',
        'left'
    );
    $this->db->where('t.drystore_uuid', $drystore_uuid);
    $this->db->order_by('dt.nama', 'ASC');
    $this->db->order_by('dw.nama', 'ASC');
    $query = $this->db->get();
    $result = [];
    foreach ($query->result() as $row) {
        if (!isset($result[$row->type_uuid])) {
            $result[$row->type_uuid] = [
                'type_uuid'   => $row->type_uuid,
                'type_nama'   => $row->type_nama,
                'type_satuan' => $row->type_satuan,
                'onproduk'    => (float) $row->onproduk,
                'items'       => []
            ];
        }
        $berat = (float) $row->berat;
        $onproduk = (float) $row->onproduk;
        /*
         * Use = Reject + On Product
         */
        $penggunaan = $berat + $onproduk;
        /*
         * % Waste
         */
        $persen_waste = 0;
        if ($penggunaan > 0) {
            $persen_waste = ($berat / $penggunaan) * 100;
        }
        /*
         * % Yield
         */
        $persen_yield = 100 - $persen_waste;
        $result[$row->type_uuid]['items'][] = [
            'waste_uuid'   => $row->waste_uuid,
            'waste_nama'   => $row->waste_nama,
            'berat'        => $berat,
            'onproduk'     => $onproduk,
            'penggunaan'   => $penggunaan,
            'persen_waste' => $persen_waste,
            'persen_yield' => $persen_yield
        ];
    }
    return $result;
}
}
