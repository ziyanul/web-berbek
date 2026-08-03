<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class TLmesin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        //$this->dberetort = $this->load->database('e-retort', TRUE);
    }

    public function rules()
    {
        return [
            [
                'field' => 'area',
                'label' => 'Area',
                'rules' => 'required'
            ],
            [
                'field' => 'tl_mesin',
                'label' => 'Tools Mesin',
                'rules' => 'required'
            ],

        ];
    }

    public function get_all()
    {
        $this->db->select('t.*,us.username, a.nama_area');
        $this->db->from('tools_mesin t');
        $this->db->join('area a', 'a.uuid=t.area_uuid', 'left');
        $this->db->join('users us', 'us.uuid=t.user_uuid', 'left');
        $this->db->group_by('nama_area');
        $this->db->order_by('t.created_at', 'ASC');
        $data = $this->db->get()->result();
        return $data;
    }

    public function insert()
    {
        $uuid = Uuid::uuid4()->toString();

        $area = $this->input->post('area');
        $tl_mesin = $this->input->post('tl_mesin');

        $data = array(
            'uuid'          => $uuid,
            'area_uuid'     => $area,
            'nama_tools'    => $tl_mesin,
            'user_uuid'     => $this->auth_model->current_user()->uuid

        );

        $this->db->insert('tools_mesin', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function update($uuid)
    {
        $area = $this->input->post('area');
        $tl_mesin = $this->input->post('tl_mesin');

        $data = array(

            'area_uuid'     => $area,
            'nama_tools'    => $tl_mesin,
            'user_uuid'     => $this->auth_model->current_user()->uuid,
            'modified_at'   => date('Y-m-d h:i:s')

        );

        $this->db->update('tools_mesin', $data, array('uuid' => $uuid));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function get_by_uuid($uuid)
    {
        return $this->db->get_where('tools_mesin', array('uuid' => $uuid))->row();
    }

    public function get_pengecekan_by_uuid($uuid)
    {
        return $this->db->get_where('pengecekan_tools', array('uuid' => $uuid))->row();
    }

    public function get_all_area()
    {
        $query = $this->db->get('area');
        return $query->result();
    }

    public function get_by_area($area_uuid)
    {
        $this->db->select('t.*,a.nama_area, us.username');
        $this->db->from('tools_mesin t');
        $this->db->join('area a', 'a.uuid=t.area_uuid', 'left');
        $this->db->join('users us', 'us.uuid=t.user_uuid', 'left');
        $this->db->order_by('t.created_at', 'ASC');
        $this->db->where('t.area_uuid', $area_uuid);
        $data = $this->db->get()->result();
        foreach ($data as $v) {
            $v->tgl = date('d M Y', strtotime($v->created_at));
        }
        return $data;
    }

    public function get_group_area_bulan()
    {
        $this->db->select('
        DATE_FORMAT(t.created_at, "%Y-%m") AS bln,
        t.area_uuid,
        a.nama_area
    ');
        $this->db->from('pengecekan_tools t');
        $this->db->join('area a', 'a.uuid = t.area_uuid', 'left');

        $this->db->group_by([
            'DATE_FORMAT(t.created_at, "%Y-%m")',
            't.area_uuid',
            'a.nama_area'
        ]);

        $this->db->order_by('bln', 'DESC');

        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $val->blnth = date('M Y', strtotime($val->bln));
        }

        return $data;
    }


    public function get_by_area_bulan($area_uuid, $bulan)
    {
        $this->db->select('
        t.created_at,
        t.tools_mesin_uuid,
        t.kondisi,
        t.kelengkapan,
        t.keterangan,
        t.fr_uuid,
        t.spv_uuid,
        u1.fullname as ch_name,
        u2.fullname as fr_name,
        u3.fullname as spv_name,
        a.nama_area,
        m.nama_tools
    ');
        $this->db->from('pengecekan_tools t');
        $this->db->join('area a', 'a.uuid = t.area_uuid', 'left');
        $this->db->join('tools_mesin m', 'm.uuid = t.tools_mesin_uuid', 'left');
        $this->db->join('users u1', 'u1.uuid = t.user_uuid', 'left');
        $this->db->join('users u2', 'u2.uuid = t.fr_uuid', 'left');
        $this->db->join('users u3', 'u3.uuid = t.spv_uuid', 'left');
        $this->db->where('t.area_uuid', $area_uuid);
        $this->db->where('DATE_FORMAT(t.created_at, "%Y-%m") =', $bulan);
        $this->db->order_by('t.created_at', 'ASC');

        $data = $this->db->get()->result();

        $result = [];
        $uniqueTools = [];

        $fr_uuid = null;
        $spv_uuid = null;
        $area = null;
        $fr_name = null;
        $spv_name = null;
        $ch_name = null;
        foreach ($data as $row) {

            // Ambil status approval
            if (!empty($row->fr_uuid)) {
                $fr_uuid = $row->fr_uuid;
            }

            if (!empty($row->spv_uuid)) {
                $spv_uuid = $row->spv_uuid;
            }

            $tanggal = date('d M Y', strtotime($row->created_at));
            $uuid = $row->nama_tools;
            $area = $row->nama_area;

            if ($row->kondisi == 1) {
                $row->kondisi = '<i class="fa fa-check fa-lg text-success"></i>';
            } elseif ($row->kondisi == 2) {
                $row->kondisi = '<i class="fa fa-times fa-lg text-danger"></i>';
            }

            if ($row->kelengkapan == 1) {
                $row->kelengkapan = '<i class="fa fa-check fa-lg text-success"></i>';
            } elseif ($row->kelengkapan == 2) {
                $row->kelengkapan = '<i class="fa fa-times fa-lg text-danger"></i>';
            }

            if (!in_array($uuid, $uniqueTools)) {
                $uniqueTools[] = $uuid;
            }

            if (!isset($result[$tanggal])) {
                $result[$tanggal] = [];
            }

            $result[$tanggal][$uuid] = [
                'kondisi' => $row->kondisi,
                'kelengkapan' => $row->kelengkapan,
                'keterangan' => $row->keterangan ?? '-',
            ];
        }

        foreach ($result as $tanggal => &$toolsData) {
            foreach ($uniqueTools as $uuid) {
                if (!isset($toolsData[$uuid])) {
                    $toolsData[$uuid] = [
                        'kondisi' => '-',
                        'kelengkapan' => '-',
                        'keterangan' => '-'
                    ];
                }
            }
        }

        return [
            'data' => $result,
            'tools' => $uniqueTools,
            'area_uuid' => $area_uuid,
            'area' => $area,
            'bulan' => $bulan,

            // Status approval
            'fr_uuid' => $fr_uuid,
            'spv_uuid' => $spv_uuid,
            'fr_name' => $fr_name,
            'ch_name' => $ch_name,
            'spv_name' => $spv_name
        ];
    }

    public function insert_form()
    {

        $area_uuid = $this->input->post('area');
        $tools_mesin_uuid = $this->input->post('tools'); // Ambil data dari checkbox
        $lengkap = $this->input->post('lengkap');
        $all_tools = $this->get_tools_by_area($area_uuid); // Ambil semua kode terkait
        $keterangan = $this->input->post('keterangan');


        // Loop untuk setiap kode barang
        foreach ($all_tools as $tools_item) {
            $uui = Uuid::uuid4()->toString();
            $tools_uuid = $tools_item->uuid;

            $data = [
                'uuid' => $uui,
                'area_uuid' => $area_uuid,
                'keterangan' => $keterangan,
                'user_uuid' => $this->auth_model->current_user()->uuid,
                'tools_mesin_uuid' => $tools_uuid,
                'kondisi' => isset($tools_mesin_uuid[$tools_item->uuid]) ? $tools_mesin_uuid[$tools_item->uuid] : 2,
                'kelengkapan' => isset($lengkap[$tools_item->uuid]) ? $lengkap[$tools_item->uuid] : 2

            ];

            // Insert per item dalam loop
            $this->db->insert('pengecekan_tools', $data);
        }

        // Periksa apakah ada data yang berhasil di-insert
        return ($this->db->affected_rows() > 0);
    }

    public function acc_fr($area_uuid, $bulan)
    {
        $user = $this->auth_model->current_user();

        $data = [
            'fr_uuid' => $user->uuid,
            'modified_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('area_uuid', $area_uuid);
        $this->db->where('DATE_FORMAT(created_at, "%Y-%m") =', $bulan);

        $this->db->update('pengecekan_tools', $data);

        return $this->db->affected_rows() > 0;
    }

    public function acc_spv($area_uuid, $bulan)
    {
        $user = $this->auth_model->current_user();

        $data = [
            'spv_uuid' => $user->uuid,
            'modified_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('area_uuid', $area_uuid);
        $this->db->where('DATE_FORMAT(created_at, "%Y-%m") =', $bulan);

        $this->db->update('pengecekan_tools', $data);

        return $this->db->affected_rows() > 0;
    }

    public function get_tools_by_area($area_uuid)
    {
        $this->db->select('tm.*');
        $this->db->from('tools_mesin tm');
        $this->db->where('tm.area_uuid', $area_uuid);
        $data = $this->db->get()->result();
        return $data;
    }

    public function get_by_bulan($bulan)
    {
        $this->db->select('t.created_at, t.tools_mesin_uuid, t.kondisi, t.kelengkapan, t.keterangan, a.nama_area, m.nama_tools');
        $this->db->from('pengecekan_tools t');
        $this->db->join('area a', 'a.uuid = t.area_uuid', 'left');
        $this->db->join('tools_mesin m', 'm.uuid = t.tools_mesin_uuid', 'left');
        $this->db->where('DATE_FORMAT(t.created_at, "%Y-%m") =', $bulan);
        $this->db->order_by('t.created_at');
        $data = $this->db->get()->result();

        $result = [];

        // Kelompokkan data berdasarkan area dan tools
        foreach ($data as $row) {
            $tanggal = date('d M Y', strtotime($row->created_at));
            if ($row->kondisi == 1) {
                $row->kondisi = '&#x2713;';
            } elseif ($row->kondisi == 2) {
                $row->kondisi = 'x';
            }

            if ($row->kelengkapan == 1) {
                $row->kelengkapan = '&#x2713;';
            } elseif ($row->kelengkapan == 2) {
                $row->kelengkapan = 'x';
            }
            if (!isset($result[$row->nama_area])) {
                $result[$row->nama_area] = [
                    'tools' => [],
                    'data' => [],
                ];
            }

            // Simpan nama tools untuk setiap area
            if (!in_array($row->nama_tools, $result[$row->nama_area]['tools'])) {
                $result[$row->nama_area]['tools'][] = $row->nama_tools;
            }

            // Kelompokkan data berdasarkan tanggal dan tools
            if (!isset($result[$row->nama_area]['data'][$tanggal])) {
                $result[$row->nama_area]['data'][$tanggal] = [];
            }

            $result[$row->nama_area]['data'][$tanggal][$row->nama_tools] = [
                'kondisi' => $row->kondisi,
                'kelengkapan' => $row->kelengkapan,
                'keterangan' => $row->keterangan ?? '-',
            ];
        }

        return $result;
    }

    public function nama_bulan($bulan)
    {
        $bulanIndonesia = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $tanggalObj = strtotime($bulan . '-01');
        $namaBulan = $bulanIndonesia[date('m', $tanggalObj)];
        $tahun = date('Y', $tanggalObj);

        return $namaBulan . ' ' . $tahun;
    }
}
