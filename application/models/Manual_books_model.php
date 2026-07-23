<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Manual_books_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		
	}

	public function rules()
	{
		return [
			
		];
	}

	public function get_all() 
    {
        $this->db->select('mb.*, a.nama_area, m.nama_mesin');
        $this->db->from('manual_books mb');
        $this->db->join('area a', 'a.uuid = mb.area_uuid', 'left');
        $this->db->join('mesin m', 'm.uuid = mb.mesin_uuid', 'left');
        $this->db->order_by('created_at', 'DESC');
        $data = $this->db->get()->result();

        return $data;
    }

    public function insert_data($pdf)
    {
        $uuid = Uuid::uuid4()->toString(); // Memanggil UUID4
        $area = $this->input->post('area');
        $mesin = $this->input->post('mesin');
        $judul = $this->input->post('judul');
        $keterangan = $this->input->post('keterangan');
        $data = array(
            'uuid' => $uuid,
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'area_uuid' => $area,
            'mesin_uuid' => $mesin,
            'judul' => $judul,
            'keterangan' => $keterangan,
            'pdf' => $pdf
        );
        $this->db->insert('manual_books', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function get_area()
    {
        // Query Database
        $this->db->select('nama_area, uuid');
        $this->db->from('area');
        $data = $this->db->get()->result();
        return $data;
    }
    // Method untuk mengambil tabel mesin dengan nama field (nama mesin)
    public function get_mesin()
    {
        // Query Database
        $this->db->select('nama_mesin');
        $this->db->from('mesin');
        $data = $this->db->get()->result();
        return $data;
    }

    public function get_mesin_by_area($area_uuid)
    {
        $this->db->select('nama_mesin, uuid');
        $this->db->from('mesin');
        $this->db->where('area_uuid', $area_uuid);
        $data = $this->db->get()->result();

        return $data;
    }
}