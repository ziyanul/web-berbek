<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Pbelah_model extends CI_Model
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
                'field' => 'jenis_pb',
                'label' => 'Area',
                'rules' => 'required'
            ]
        ];
    }

    public function rules1()
    {
        return [
            [
                'field' => 'kode_barang',
                'label' => 'Kode Barang',
                'rules' => 'required'
            ]
        ];
    }
    public function rules2()
    {
        return [
            [
                'field' => 'kode_pb',
                'label' => 'Kode Barang',
                'rules' => 'required'
            ]
        ];
    }

    public function rules3()
    {
        return [
            [
                'field' => 'area',
                'label' => 'Area',
                'rules' => 'required'
            ]
        ];
    }

    public function rules4()
    {
        return [
            [
                'field' => 'jenis_pb',
                'label' => 'Area',
                'rules' => 'required'
            ]
        ];
    }

    public function get_all_pengecekan($tanggal)
    {
        // $this->db->select('pb.*, a.nama_area, s.lokasi, jp.jenis_barang, kp.kode_barang');
        $this->db->select('pb.uuid, pb.kondisi, kp.kode_barang, jp.jenis_barang, s.lokasi, a.nama_area, u.fullname');
        $this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = pb.spv_uuid) AS spv", false);
        $this->db->select("(SELECT u.fullname FROM users u WHERE u.uuid = pb.frm_uuid) AS leader", false);
        $this->db->from('pengecekan_pbelah pb');
        $this->db->join('jenis_pbelah jp', 'jp.uuid = pb.jenis_pbelah_uuid', 'left');
        $this->db->join('kode_pbelah kp', 'kp.uuid = pb.kode_pbelah_uuid', 'left');
        $this->db->join('sub_area s', 's.uuid = jp.sub_area_uuid', 'left');
        $this->db->join('area a', 'a.uuid = s.area_uuid', 'left');
        $this->db->join('users u', 'u.uuid = pb.user_uuid', 'left');
        $this->db->where("DATE_FORMAT(pb.created_at, '%Y-%m-%d') =", $tanggal);
        $this->db->order_by('a.nama_area, s.lokasi, jp.jenis_barang, kp.kode_barang');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            if ($val->kondisi == 1) {
                $val->baik = '&#x2713;';
                $val->tidak = '-';
            } elseif ($val->kondisi == 2) {
                $val->baik = '-';
                $val->tidak = '&#88;';
            }
        }

        return $data;
    }


    public function get_data_by_tanggal()
    {
        $this->db->select("pb.uuid, DATE_FORMAT(pb.created_at, '%Y-%m-%d') as tanggal, pb.created_at");
        $this->db->from('pengecekan_pbelah pb');
        $this->db->group_by('pb.uuid, tanggal, pb.created_at');
        $this->db->order_by('tanggal', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $val->tgl = date('d M Y', strtotime($val->created_at));
            $nhari =  date('l', strtotime($val->tgl));
            $namahari = [
                'Sunday'    => 'Minggu',
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu'
            ];
            $val->nama_hari = $namahari[$nhari];
        }

        return $data;
    }

    public function insert_form()
    {
        $sub_area_uuid = $this->input->post('sub_area');
        $area_uuid = $this->input->post('area');
        $kode_barang_input = $this->input->post('kode'); // Ambil data dari checkbox
        $all_kode = $this->get_kode_by_sub_area($sub_area_uuid); // Ambil semua kode terkait

        // Loop untuk setiap kode barang
        foreach ($all_kode as $kode_item) {
            $uui = Uuid::uuid4()->toString();
            $kode_uuid = $kode_item->uuid;
            $jenis_pbelah = $kode_item->jenis_pbelah_uuid;


            $data = [
                'uuid' => $uui,
                // 'area_uuid' => $area_uuid,
                // 'sub_area_uuid' => $sub_area_uuid,
                'user_uuid' => $this->auth_model->current_user()->uuid,
                'jenis_pbelah_uuid' => $jenis_pbelah,
                'kode_pbelah_uuid' => $kode_uuid,
                'kondisi' => isset($kode_barang_input[$kode_item->uuid]) ? $kode_barang_input[$kode_item->uuid] : 2
            ];

            // Insert per item dalam loop
            $this->db->insert('pengecekan_pbelah', $data);
        }

        // Periksa apakah ada data yang berhasil di-insert
        return ($this->db->affected_rows() > 0);
    }

    public function insert_jenis()
    {
        $uuid = Uuid::uuid4()->toString();
        $area = $this->input->post('area');
        $sub_area = $this->input->post('sub_area');
        $jenis_pb = $this->input->post('jenis_pb');

        $data = [
            'uuid'          => $uuid,
            'area_uuid'     => $area,
            'sub_area_uuid'     => $sub_area,
            'user_uuid'     => $this->auth_model->current_user()->uuid,
            'jenis_barang'  => $jenis_pb
        ];

        $this->db->insert('jenis_pbelah', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function insert_kode($area_uuid)
    {
        $uuid = Uuid::uuid4()->toString();
        $kode_pb = $this->input->post('kode_barang');
        $uuid_jenis = $this->input->post('jenis_pb');

        $data = [
            'uuid' => $uuid,
            'jenis_pbelah_uuid' => $uuid_jenis,
            'user_uuid'           => $this->auth_model->current_user()->uuid,
            'kode_barang' => $kode_pb
        ];

        $this->db->insert('kode_pbelah', $data);
        return ($this->db->affected_rows() > 0);
    }

    public function get_pengecekan_by_uuid($uuid)
    {
        $this->db->select("*, DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('pengecekan_pbelah');
        $this->db->where('uuid', $uuid);
        $query = $this->db->get();
        return $query->row();
    }

    // Fungsi untuk mengupdate data pengecekan pbelah
    public function updatejenis($uuid)
    {
        $jenis_pb = $this->input->post('jenis_pb');

        $data = array( // inisiasi data yang di input ke database
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'jenis_barang' => $jenis_pb,
            'modified_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('jenis_pbelah', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function updatekode($uuid)
    {
        $kode_pb = $this->input->post('kode_pb');

        $data = array(
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'kode_barang' => $kode_pb,
            'modified_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('kode_pbelah', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function update_detail($uuid)
    {
        $kondisi = $this->input->post('kondisi');

        $data = array(
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'kondisi' => $kondisi,
            'modified_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('pengecekan_pbelah', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    // Fungsi untuk menghapus data pengecekan pbelah
    public function delete_pengecekan($uuid)
    {
        $this->db->where('uuid', $uuid);
        return $this->db->delete('pengecekan_pbelah');
    }

    public function get_all_jenis_pbelah()
    {
        $this->db->select('
        j.area_uuid,
        j.sub_area_uuid,
        a.nama_area,
        s.lokasi
    ');
        $this->db->from('jenis_pbelah j');
        $this->db->join('area a', 'a.uuid = j.area_uuid', 'left');
        $this->db->join('sub_area s', 's.uuid = j.sub_area_uuid', 'left');

        $this->db->group_by([
            'j.area_uuid',
            'j.sub_area_uuid',
            'a.nama_area',
            's.lokasi'
        ]);

        $this->db->order_by('a.created_at', 'ASC');
        $this->db->order_by('s.created_at', 'ASC');

        return $this->db->get()->result();
    }

    // Fungsi untuk mendapatkan kode barang
    public function get_all_kode_pbelah()
    {
        return $this->db->get('kode_pbelah')->result();
    }

    public function get_all_by_sub_area($sub_area_uuid)
    {
        $this->db->select('j.*, s.lokasi');
        $this->db->from('jenis_pbelah j');
        // $this->db->join('area a', 'a.uuid = j.area_uuid', 'left');
        $this->db->join('sub_area s', 's.uuid = j.sub_area_uuid', 'left');
        $this->db->order_by('j.created_at', 'ASC');
        $this->db->where('j.sub_area_uuid', $sub_area_uuid);
        $data = $this->db->get()->result();

        return $data;
    }

    public function get_kode_by_jenis($jenis_pbelah_uuid)
    {
        $this->db->select('k.*, j.jenis_barang, a.nama_area, j.area_uuid');
        $this->db->from('kode_pbelah k');
        $this->db->order_by('k.created_at', 'ASC');
        $this->db->join('jenis_pbelah j', 'j.uuid = k.jenis_pbelah_uuid', 'left');
        $this->db->join('area a', 'j.area_uuid = a.uuid', 'left');
        $this->db->where('k.jenis_pbelah_uuid', $jenis_pbelah_uuid);
        $data = $this->db->get()->result();

        return $data;
    }

    public function get_jenis_by_uuid($uuid)
    {
        $this->db->select('p.*, s.lokasi');
        $this->db->from('jenis_pbelah p');
        $this->db->where('p.uuid', $uuid);
        $this->db->join('sub_area s', 's.uuid = p.sub_area_uuid');
        $data = $this->db->get()->row();

        return $data;
    }

    public function get_kode_uuid($uuid)
    {
        $data = $this->db->get_where('kode_pbelah', array('uuid' => $uuid))->row();
        return $data;
    }

    public function get_jenis_for_nav($jenis_pbelah_uuid)
    {
        $this->db->select('j.*, s.lokasi, a.nama_area');
        $this->db->from('jenis_pbelah j');
        $this->db->where('j.uuid', $jenis_pbelah_uuid);
        $this->db->join('sub_area s', 's.uuid = j.sub_area_uuid', 'left');
        $this->db->join('area a', 'a.uuid = j.area_uuid', 'left');
        $data = $this->db->get()->row();

        return $data;
    }

    public function get_jenis($sub_area_uuid)
    {
        return $this->db->get_where('jenis_pbelah', array('sub_area_uuid' => $sub_area_uuid))->result();
    }

    public function get_kode_by_sub_area($sub_area_uuid)
    {
        $this->db->select('kb.*, jb.area_uuid, kb.jenis_pbelah_uuid');
        $this->db->from('kode_pbelah kb');
        $this->db->join('jenis_pbelah jb', 'jb.uuid=kb.jenis_pbelah_uuid', 'left');
        $this->db->where('jb.sub_area_uuid', $sub_area_uuid);
        $data = $this->db->get()->result();
        return $data;
    }

    public function get_all_kode()
    {
        $this->db->select('k.*, j.jenis_barang, s.lokasi');
        $this->db->from('kode_pbelah k');
        $this->db->join('jenis_pbelah j', 'j.uuid = k.jenis_pbelah_uuid', 'left');
        $this->db->join('sub_area s', 's.uuid = j.sub_area_uuid', 'left');
        $this->db->order_by('k.created_at', 'ASC');
        $this->db->group_by('s.lokasi, j.jenis_barang');
        $data = $this->db->get()->result();

        return $data;
    }
}
