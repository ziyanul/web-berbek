<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Rr_cooking_model extends CI_Model 
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
				'field' => 'masak',
				'label' => 'Masakan Ke',
				'rules' => 'required'	
			],
			[
				'field' => 'rj_cooking',
				'label' => 'Jumlah Reject',
				'rules' => 'required'	
			]
		];
	}

    public function rules1()
    {
        return [
            [
                'field' => 'masak[]',
                'label' => 'Masakan Ke',
                'rules' => 'required'   
            ],
            [
                'field' => 'rj_cooking[]',
                'label' => 'Jumlah Reject',
                'rules' => 'required'   
            ]
        ];
    }

	public function get_home()
	{
		$this->db->select('m.uuid, m.MR_DATE, v.varian, m.MR_uuid_varian, m.MR_KOPROD, m.MR_NOCHAM, rj.kode_batch, rj.batch, rj.masak, rj.uuid as rj_cooking_uuid');
		$this->db->select('(SELECT r.masak_retort_uuid FROM rj_cooking_rt r WHERE r.masak_retort_uuid = m.uuid LIMIT 1 ) as rc_data', false);
		$this->db->from('masak_retort m');
		$this->db->join('varian v', 'v.uuid = m.MR_uuid_varian', 'left');
		$this->db->join('rj_cooking_rt rj', 'rj.masak_retort_uuid = m.uuid', 'left');
		$this->db->order_by('m.MR_ID', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $v) {
			$v->tanggal = date('d M Y', strtotime($v->MR_DATE));
			$v->tgl = date('Y-m-d', strtotime($v->MR_DATE));

        }
        return $data;
    }

    public function getMasakData($date, $mr_uuid_varian)
    {
        $this->db->select('mr.uuid, mr.MR_NOCHAM, mr.MR_KOPROD, mr.MR_JMLTRAY, rc.batch, rc.masak, rc.rj_cooking, rc.jml_tray');
        $this->db->from('masak_retort mr');
        $this->db->join('rj_cooking_rt rc', 'mr.uuid = rc.masak_retort_uuid', 'left');
        $this->db->where('mr.MR_DATE', $date);
        $this->db->where('mr.MR_UUID_VARIAN', $mr_uuid_varian);
        $this->db->order_by('mr.MR_ID', 'ASC');
        $this->db->order_by('rc.masak', 'ASC');
        $data = $this->db->get()->result_array();
        $finalData = [];
        foreach ($data as $row)
        {
            $finalData[$row['batch']][] = $row;
        }
        return $finalData;
    }

    public function get_info_detail($date, $mr_uuid_varian)
    {
        $this->db->select('mr.MR_DATE, mr.MR_uuid_varian, v.varian, rc.user_uuid, u.fullname as user, rc.kr_uuid, rc.spv_uuid, u1.fullname as kr_name, u2.fullname as spv_name');
        $this->db->from('masak_retort mr');
        $this->db->join('varian v', 'v.uuid = mr.MR_uuid_varian', 'left');
        $this->db->join('rj_cooking_rt rc', 'mr.uuid = rc.masak_retort_uuid', 'left');
        $this->db->join('users u', 'u.uuid = rc.user_uuid', 'left');
        $this->db->join('users u1', 'u1.uuid = rc.kr_uuid', 'left');
        $this->db->join('users u2', 'u2.uuid = rc.spv_uuid', 'left');
        $this->db->where('mr.MR_DATE', $date);
        $this->db->where('mr.MR_uuid_varian', $mr_uuid_varian);
        $this->db->group_by('mr.MR_DATE, mr.MR_uuid_varian');
        $data = $this->db->get()->row();
        $data->tgl = date('d M Y', strtotime($data->MR_DATE));

        return $data;
    }

    public function insert_reject_cooking($uuid, $mr_koprod)
    {
        // Pastikan MR_KOPROD adalah string untuk menghindari error di strpos()
        $mr_koprod = is_string($mr_koprod) ? $mr_koprod : '';
        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        // Data utama
        $data = [
            'uuid'          => $uuid1,
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'masak_retort_uuid' => $uuid,
            'batch'      => $this->input->post('batch_ke'),
            'masak'      => $this->input->post('masak'),
            'jml_tray'   => $this->input->post('jmltray'),
            'rj_cooking'=> $this->input->post('rj_cooking'),
        ];
        $this->db->insert('rj_cooking_rt', $data);

        // Jika MR_KOPROD mengandung "/", tambahkan data kedua
        if (strpos($mr_koprod, '/') !== false) {
            $data_tambahan = [
                'uuid'          => $uuid2,
                'user_uuid' => $this->auth_model->current_user()->uuid,
                'masak_retort_uuid' => $uuid,
                'batch'      => $this->input->post('batch_ke_tambahan'),
                'masak'      => $this->input->post('masak_ke_tambahan'),
                'jml_tray'   => $this->input->post('jmltray_tambahan1'),
                'rj_cooking'=> $this->input->post('rj_tambahan1'),
            ];
            $this->db->insert('rj_cooking_rt', $data_tambahan);
        }
    }

    public function get_masak_retort_uuid($uuid)
    {
     $this->db->select('rj.uuid, rj.masak, rj.batch, rj.rj_cooking, rj.jml_tray, v.varian, mr.MR_NOCHAM, rj.masak_retort_uuid');
     $this->db->from('rj_cooking_rt rj');
     $this->db->join('masak_retort mr', 'mr.uuid = rj.masak_retort_uuid', 'left');
     $this->db->join('varian v', 'v.uuid = mr.MR_uuid_varian', 'left');
     $this->db->where('rj.masak_retort_uuid', $uuid);
     $data = $this->db->get()->result();

     return $data;
 }

 public function update_reject_cooking($uuid)
{
    $data = $this->input->post();

    foreach ($data['batch_ke'] as $index => $batch) {
        $update_data = [
            'batch' => $batch,
            'masak' => $data['masak'][$index],
            'jml_tray' => $data['jmltray'][$index],
            'rj_cooking' => $data['rj_cooking'][$index]
        ];

        $this->db->where('masak_retort_uuid', $uuid);
        $this->db->where('batch', $batch);
        $this->db->update('rj_cooking_rt', $update_data);
    }

    return $this->db->affected_rows();
}


public function get_masak_data($uuid)
{
    $this->db->select('m.uuid,m.MR_uuid_varian, m.MR_DATE, m.MR_NMPRODUK, m.MR_KOPROD, m.MR_NOCHAM, m.MR_JMLTRAY');
    $this->db->from('masak_retort m');
    $this->db->where('m.uuid', $uuid);
    $data = $this->db->get()->row();

    return $data;
}

public function update_kr($date, $varian_uuid)
{
    // Dapatkan UUID user saat ini
    $current_uuid = $this->auth_model->current_user()->uuid;

    // Lakukan update pada rj_cooking_rt
    $this->db->query("
        UPDATE rj_cooking_rt rc
        JOIN masak_retort mr 
        ON rc.masak_retort_uuid = mr.uuid
        SET rc.kr_uuid = ?
        WHERE mr.MR_DATE = ? AND mr.MR_uuid_varian = ?
        ", [$current_uuid, $date, $varian_uuid]);

    return $this->db->affected_rows();

}

public function update_spv($date, $varian_uuid)
{
    // Dapatkan UUID user saat ini
    $current_uuid = $this->auth_model->current_user()->uuid;

    // Lakukan update pada rj_cooking_rt
    $this->db->query("
        UPDATE rj_cooking_rt rc
        JOIN masak_retort mr 
        ON rc.masak_retort_uuid = mr.uuid
        SET rc.spv_uuid = ?
        WHERE mr.MR_DATE = ? AND mr.MR_uuid_varian = ?
        ", [$current_uuid, $date, $varian_uuid]);

    return $this->db->affected_rows();

}

}