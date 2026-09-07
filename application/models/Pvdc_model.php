<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Pvdc_model extends CI_Model
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
                'field' => 'pvdc_input',
                'label' => 'Pvdc',
                'rules' => 'required'
            ],
            [
                'field' => 'wire_input',
                'label' => 'Wire',
                'rules' => 'required'
            ]
        ];
    }

    public function get_pvdc_data()
    {
        $this->db->select('pw.*, tp.tanggal, v.varian, v.keterangan');
        $this->db->from('pvdc_wire pw');
        $this->db->join('t_planning tp', 'pw.t_planning_uuid = tp.uuid', 'left');
        $this->db->join('varian v', 'tp.varian = v.uuid', 'left');
        $this->db->order_by('pw.created_at', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $row) {
            $row->nama_varian = $row->varian . ' (' . $row->keterangan . ')';
            $row->tanggal = tanggal_indo($row->tanggal, true);
        }
        return $data;
    }

    public function get_all()
    {
        $this->db->select('tp.uuid as uuid_planning, tp.tanggal, pw.pvdc, pw.wire, v.varian, v.keterangan');
        $this->db->from('t_planning tp');
        $this->db->join('pvdc_wire pw', 'tp.uuid = pw.t_planning_uuid', 'left');
        $this->db->join('varian v', 'tp.varian = v.uuid', 'left');
        $this->db->order_by('tp.created_at', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $row) {
            $row->nama_varian = $row->varian . ' (' . $row->keterangan . ')';
            $row->tanggal = tanggal_indo($row->tanggal, true);
        }
        return $data;
    }

    public function get_by_uuid($t_planning_uuid)
    {
        $data = $this->db->get_where('pvdc_wire', array('t_planning_uuid' => $t_planning_uuid))->row();
        if (empty($data)) {
            $this->db->select('tp.uuid as t_planning_uuid, tp.tanggal, v.varian, v.keterangan');
            $this->db->from('t_planning tp');
            $this->db->join('varian v', 'tp.varian = v.uuid', 'left');
            $this->db->where('tp.uuid', $t_planning_uuid);
            $data = $this->db->get()->row();
            if (!empty($data)) {
                $data->nama_varian = $data->varian . ' (' . $data->keterangan . ')';
                $data->tanggal = tanggal_indo($data->tanggal, true);
                $data->pvdc = 0;
                $data->wire = 0;
            }
        }
        return $data;
    }


    public function insert()
    {
        $t_planning_uuid = $this->input->post('planning_uuid');
        $pvdc_uuid = Uuid::uuid4()->toString();
        $wire = $this->input->post('wire_input');
        $pvdc = $this->input->post('pvdc_input');

        $data_insert = array(
            'uuid' => $pvdc_uuid,
            't_planning_uuid' => $t_planning_uuid,
            'wire' => $wire,
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'pvdc' => $pvdc
        );
        $this->db->insert('pvdc_wire', $data_insert);
        return ($this->db->affected_rows() > 0) ? true : false;
    }


    public function update($uuid)
    {
        $area = $this->input->post('area'); // mendapatkan data dari input area

        $data = array( // inisiasi data yang di input ke database
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'nama_area' => $area,
            'username' => $this->auth_model->current_user()->username,
            'modified_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('area', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
    }
}