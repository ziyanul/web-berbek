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

    function get_all()
    {
        $this->db->select('*');
        $this->db->from('drystore-type');
        $this->db->order_by('type', 'ASC');
        $data = $this->db->get()->result();
        return $data;
    }


}