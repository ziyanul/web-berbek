<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Cekmesin_fillerbatch_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        //$this->dberetort = $this->load->database('e-retort', TRUE);
        // $this->dbwarehouse = $this->load->database('warehouse', TRUE);
        $this->config->load('relasi_uuid');
        $this->filler = $this->config->item('filler_uuid');
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
                'field' => 'mesin',
                'label' => 'Mesin',
                'rules' => 'required'
            ],
        ];
    }

    public function get_all()
    {
      $this->db->select('*');
      $this->db->from('t_planning');
      $this->db->order_by('tanggal', 'DESC');
      $data = $this->db->get()->result();
      foreach ($data as $val)
      {
        $tgl = strtotime($val->tanggal);
        $val->tanggal = date('d-m-Y', $tgl);
    }
    return $data;
}

public function get_batch($uuid) 
{
    $this->db->select('m.*');
    $this->db->from('e-retort-dev.mincing m');
    $this->db->where('planprod_uuid', $uuid);
    $data = $this->db->get()->result();

    foreach ($data as $val) {
        $val->time = date('H:i', strtotime($val->created_at));
    }
    return $data;
}

public function get_batch_uuid($MN_BATCH) 
{
    $this->db->select('m.*');
    $this->db->from('e-retort-dev.mincing m');
    $this->db->where('m.MN_BATCH', $MN_BATCH);
    $data = $this->db->get()->row();

    return $data;
}


public function get_by_uuid($uuid)
{
 $this->db->select('t.uuid, t.tanggal, v.varian, v.panjang as film');
 $this->db->from('t_planning t');
 $this->db->join('varian v', 'v.uuid = t.varian_uuid', 'left');
 $this->db->where('t.uuid', $uuid);
 $data = $this->db->get()->row();
 $data->tgl = date("d M Y", strtotime($data->tanggal));

 return $data;
}

public function get_pm_filler()
{
    $this->db->select('pm.*');
    $this->db->from('pm_filler pm');
    $this->db->order_by('created_at', 'DESC');
    $data = $this->db->get()->result();

    return $data;
}

public function insert_item()
{
    $uuid           = Uuid::uuid4()->toString();
    $mesin      = $this->input->post('mesin');
    $mesin_name     = $this->input->post('mesin_name');
    $kegiatan       = $this->input->post('item');

    $data = array(
        'uuid'          => $uuid,
        'mesin_uuid'    => $mesin,
        'mesin'         => $mesin_name,
        'user_uuid'           => $this->auth_model->current_user()->uuid,
        'username'          => $this->auth_model->current_user()->username,
        'item'      => $kegiatan
    );

    $this->db->insert('pm_filler', $data);
    return ($this->db->affected_rows() > 0) ? true : false;

}

public function update_item($uuid)
{
    $mesin      = $this->input->post('mesin');
    $mesin_name     = $this->input->post('mesin_name');
    $kegiatan       = $this->input->post('item');

    $data = array(
        'mesin'    => $mesin_name,
        'mesin_uuid' => $mesin,
        'user_uuid'     => $this->auth_model->current_user()->uuid,
        'username'      => $this->auth_model->current_user()->username,
        'item'          => $kegiatan
    );

    $this->db->update('pm_filler', $data,  array('uuid' => $uuid ));
    return ($this->db->affected_rows() > 0) ? true : false;

}

public function get_item_by_uuid($uuid)
{
    return $this->db->get_where('pm_filler', array('uuid' => $uuid ))->row();
}

public function get_mesin_filler()
{
    $this->db->where('area_uuid', $this->filler );
    $data = $this->db->get('mesin')->result();

    return $data;
}

public function insert_cek_mesin($batch_uuid) {
    $batch = $this->db->get_where('mincing', array('MN_BATCH' => $batch_uuid))->row();

    
    $mesin_uuid = $this->input->post('mesin');
    $item = $this->input->post('item');
    $keterangan = $this->input->post('keterangan');
    $mesin = $this->cek_mesin_name($mesin_uuid)->nama_mesin;
    $all_item = $this->cek_item_by_mesin($mesin_uuid);

    foreach ($all_item as $kegiatan_item) {
        $uuid = Uuid::uuid4()->toString();
        $item_name = $kegiatan_item->item;
        $pm_filler_uuid = $kegiatan_item->uuid;

        $data = [
            'uuid'             => $uuid,
            'user_uuid'        => $this->auth_model->current_user()->uuid,
            'username'         => $this->auth_model->current_user()->username,
            'tbatch_uuid'      => $batch->MN_BATCH,
            'pm_filler_uuid'   => $pm_filler_uuid,
            'item'             => $item_name,
            'ceklist'        => isset($item[$pm_filler_uuid]) ? $item[$pm_filler_uuid] : 0,
            'keterangan'       => isset($keterangan[$pm_filler_uuid]) ? $keterangan[$pm_filler_uuid] : ''
        ];

        $this->db->insert('pm_filler_ceklist', $data);
        if ($this->db->affected_rows() <= 0) {
            log_message('error', 'Failed to insert ceklist for kegiatan_item: ' . print_r($data, true));
        }
    }

    return true;
}

public function last_batch($uuid)
{   
    $this->db->select('batch_ke, t_planning_uuid');
    $this->db->order_by('created_at', 'DESC');
    $data = $this->db->get_where('tbatch', array('t_planning_uuid' => $uuid))->row();
    if ($data === null) {
        $data = new stdClass(); // Creating a new stdClass object
        $data->batch_ke = 0; // Setting the batch_ke property to 0
        $data->t_planning_uuid = $uuid; // Setting the created_at property to null
    }
    return $data;
}

public function get_item_by_mesin($mesin_uuid)
{
    return $this->db->get_where('pm_filler', array('mesin_uuid' => $mesin_uuid))->result();
}

public function cek_item_by_mesin($mesin_uuid)
{
    $this->db->select('uuid, item');
    $this->db->from('pm_filler');
    $this->db->where('mesin_uuid', $mesin_uuid);
    $data = $this->db->get()->result();

    // Debugging jika terjadi kesalahan data
    if (empty($data)) {
        log_message('error', 'No items found for mesin_uuid: ' . $mesin_uuid);
    }

    return $data;
}

public function cek_mesin_name($uuid)
{
    return $this->db->select('nama_mesin')->from('mesin')->where('uuid', $uuid)->get()->row();
}

public function get_cek_by_batch($MN_BATCH)
{
    $this->db->select('pmc.*, m.planprod_uuid, m.MN_BATCH, pm.mesin');
    $this->db->from('pm_filler_ceklist pmc');
    $this->db->join('mincing m', 'm.MN_BATCH = pmc.tbatch_uuid', 'left');
    
    $this->db->join('pm_filler pm', 'pm.uuid = pmc.pm_filler_uuid', 'left');
    $this->db->where('pmc.tbatch_uuid', $MN_BATCH);
    $data = $this->db->get()->result();

    return $data;
}

public function get_item_by_t_planning_uuid($uuid) {
    $this->db->select('tp.uuid as form_uuid, v.varian, pmc.item, GROUP_CONCAT(pmc.ceklist ORDER BY tb.MN_BATCH ASC) as ceklists, MAX(CAST(SUBSTR(tb.MN_BATCH, 6, 2) AS UNSIGNED)) AS max_batch_ke, pm.mesin, GROUP_CONCAT(pmc.keterangan ORDER BY tb.MN_BATCH ASC) as keterangan_group');
    $this->db->from('pm_filler_ceklist pmc');
    $this->db->join('mincing tb', 'tb.MN_BATCH = pmc.tbatch_uuid', 'inner');
    $this->db->join('t_planning tp', 'tp.uuid = tb.planprod_uuid', 'inner');
    $this->db->join('varian v', 'v.uuid = tp.varian_uuid', 'left');
    $this->db->join('pm_filler pm', 'pm.uuid = pmc.pm_filler_uuid', 'left');
    $this->db->where('tp.uuid', $uuid);
    $this->db->group_by('pmc.item, pm.mesin');
    $this->db->order_by('pm.mesin', 'ASC');
    $this->db->order_by('pm.id', 'ASC');
    return $this->db->get()->result();
}

public function get_by_area($area_uuid, $MN_BATCH)  
{
    $this->db->select('m.uuid, m.nama_mesin, 
        IF(COUNT(pfcl.pm_filler_uuid) > 0, 1, 0) AS is_used', false);
    $this->db->from('mesin m');
    $this->db->join('pm_filler pf', 'pf.mesin_uuid = m.uuid', 'left');
    $this->db->join('pm_filler_ceklist pfcl', 'pf.uuid = pfcl.pm_filler_uuid AND pfcl.tbatch_uuid = '.$this->db->escape($MN_BATCH), 'left');
    $this->db->where('m.area_uuid', $area_uuid);
    $this->db->group_by('m.uuid, m.nama_mesin');
    $this->db->order_by('m.created_at', 'ASC');

    return $this->db->get()->result();
}





}