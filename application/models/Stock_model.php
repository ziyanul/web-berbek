<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Stock_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
    //$this->dberetort = $this->load->database('e-retort', TRUE);
    $this->dbwarehouse = $this->load->database('warehouse', TRUE);
    $this->config->load('relasi_uuid');
    $this->mp = $this->config->item('mp_uuid');
    $this->filler = $this->config->item('filler_uuid');
  }

  public function rules()
  {
    return [
      [
        'field' => 'kegiatan',
        'label' => 'Kegiatan',
        'rules' => 'required'
      ],
      [
        'field' => 'target',
        'label' => 'Target',
        'rules' => 'required'
      ]
    ];
  }

  public function get_all()
  {
    $warehouse_db_name = $this->dbwarehouse->database;
    $this->db->select('bb.uuid, a.nama_area, bb.item_barang_uuid, bb.item_barang');
    $this->db->select("(SELECT SUM(wh.qty_dikirim) FROM `{$warehouse_db_name}`.`bahanbaku_wh` wh WHERE bb.uuid = wh.bahanbaku_mp_uuid AND wh.kondisi_kemasan = 1 AND wh.kontaminasi = 1 AND wh.kondisi_pallet = 1) as total_qty", false);
    $this->db->from('bahanbaku bb');
    $this->db->join(area a', 'bb.area_uuid = a.uuid', 'left');
    $this->db->where('bb.deleted_at', NULL);
    $this->db->group_by('bb.item_barang_uuid');
    $data = $this->db->get()->result();
    return $data;
  }

  public function get_detail_mp($item_uuid)
  {
    $this->db->select('wh.qty_dikirim, wh.kode_produk, wh.exp_date, bb.item_barang, wh.jam_terima, bb.no_reservasi');
    $this->db->from($this->dbwarehouse->database.'.bahanbaku_wh wh');
    $this->db->join('bahanbaku bb', 'bb.uuid = wh.bahanbaku_mp_uuid', 'left');
    $this->db->where('bb.item_barang_uuid', $item_uuid);
    $this->db->where('wh.kondisi_kemasan', 1);
    $this->db->where('wh.kontaminasi', 1);
    $this->db->where('wh.kondisi_pallet', 1);
    $this->db->order_by('wh.created_at', 'ASC');
    $data = $this->db->get()->result();

    return $data;
  }

  public function get_area_data($area_uuid)
  {
    $this->db->select('bb.uuid, a.nama_area, bb.item_barang_uuid, bb.item_barang');
    $this->db->select("COALESCE((SELECT SUM(wh.qty_dikirim) FROM `{$this->dbwarehouse->database}`.`bahanbaku_wh` wh 
                    WHERE bb.uuid = wh.bahanbaku_mp_uuid 
                    AND wh.kondisi_kemasan = 1 
                    AND wh.kontaminasi = 1 
                    AND wh.kondisi_pallet = 1), 0) as total_qty", false);
    $this->db->from('bahanbaku bb');
    $this->db->join('area a', 'bb.area_uuid = a.uuid', 'left');
    $this->db->where('bb.deleted_at', NULL);
    
    if (!empty($area_uuid)) {
        $this->db->where('bb.area_uuid', $area_uuid); // Filter berdasarkan area
    }

    $this->db->group_by('bb.item_barang_uuid');
    $data = $this->db->get()->result();
    return $data;
  }
}