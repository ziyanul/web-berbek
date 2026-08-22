<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Formula_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function rules()
	{
		return [
			[
				'field' => 'nama_formula',
				'label' => 'Formula',
				'rules' => 'required'
			]
		];
	}

	public function get_all()
	{
		return $this->db->where('deleted_at', null)
		->get('m_formula')
		->result();
	}

	public function get_by_uuid($uuid)
	{
		$this->db->select('mf.*, v.uuid as uuid_varian, v.varian, v.keterangan as keterangan_varian');
		$this->db->from('m_formula mf');
		$this->db->join('varian v', 'v.uuid = mf.varian_uuid', 'left');
		$this->db->where('mf.uuid', $uuid);
		$this->db->where('mf.deleted_at IS NULL', null, false);

		$formula = $this->db->get()->row();

		$detail = $this->db->get_where('m_formula_detail', [
			'formula_uuid' => $uuid,
			'deleted_at' => null
		])->result();

		return [
			'master' => $formula,
			'detail' => $detail
		];
	}

	public function get_by_varian($varian_uuid)
	{
		return $this->db
		->where('varian_uuid', $varian_uuid)
		->where('deleted_at IS NULL', NULL, FALSE)
		->order_by('nama_formula', 'ASC')
		->get('m_formula')
		->result();
	}

	public function get_formula_by_varian($varian_uuid)
	{
		return $this->db
		->where('varian_uuid', $varian_uuid)
		->where('deleted_at IS NULL', NULL, FALSE)
		->order_by('nama_formula', 'ASC')
		->get('m_formula')
		->row();
	}

	public function insert_master_detail($post)
	{
		$uuid = Uuid::uuid4()->toString();

    // MASTER
		$master = [
			'uuid' => $uuid,
			'created_by' => $this->auth_model->current_user()->uuid,
			'varian_uuid' => $post['varian_uuid'],
			'nama_formula' => $post['nama_formula'],
			'total' => array_sum($post['qty']),
			'keterangan' => $post['keterangan'],
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('m_formula', $master);

    // DETAIL
		foreach ($post['bahan_uuid'] as $i => $bahan_uuid) {

			$this->db->insert('m_formula_detail', [
				'uuid' => Uuid::uuid4()->toString(),
				'formula_uuid' => $uuid,
				'bahan_uuid' => $bahan_uuid,
				'nama_bahan' => $post['nama_bahan'][$i],
				'qty' => $post['qty'][$i],
				'created_at' => date('Y-m-d H:i:s')
			]);
		}
	}

	public function update_master_detail($uuid, $post)
	{
    // UPDATE MASTER
		$this->db->where('uuid', $uuid)
		->update('m_formula', [
			'varian_uuid' => $post['varian_uuid'],
			'nama_formula' => $post['nama_formula'],
			'total' => array_sum($post['qty']),
			'keterangan' => $post['keterangan'],
			'modified_at' => date('Y-m-d H:i:s')
		]);

    // DELETE DETAIL LAMA (soft delete)
		$this->db->where('formula_uuid', $uuid)
		->update('m_formula_detail', [
			'deleted_at' => date('Y-m-d H:i:s')
		]);

    // INSERT DETAIL BARU
		foreach ($post['bahan_uuid'] as $i => $bahan_uuid) {

			$this->db->insert('m_formula_detail', [
				'uuid' => Uuid::uuid4()->toString(),
				'formula_uuid' => $uuid,
				'bahan_uuid' => $bahan_uuid,
				'nama_bahan' => $post['nama_bahan'][$i],
				'qty' => $post['qty'][$i],
				'created_at' => date('Y-m-d H:i:s')
			]);
		}
	}

	public function delete($uuid)
	{
		$this->db->trans_start();

		$this->db->where('uuid', $uuid)
		->update('m_formula', [
			'deleted_at' => date('Y-m-d H:i:s')
		]);

		$this->db->where('formula_uuid', $uuid)
		->update('m_formula_detail', [
			'deleted_at' => date('Y-m-d H:i:s')
		]);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function getByVarian($varian_uuid)
	{
		return $this->db
		->where('varian_uuid', $varian_uuid)
		->where('deleted_at IS NULL', null, false)
		->get('m_formula')
		->result();
	}
}