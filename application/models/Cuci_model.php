<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Cuci_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Proses_model');
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

    public function get_all()
    {
    }

    public function get_batch()
    {
    }

    public function insert()
    {
    }

    public function get_by_uuid($uuid)
    {
    }
}