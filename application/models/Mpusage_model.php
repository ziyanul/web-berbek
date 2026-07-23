<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Mpusage_model extends CI_Model
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
				'field' => 'formula_uuid',
				'label' => 'Formula',
				'rules' => 'required'
			]
		];
	}
	public function get_all()
	{
		$this->db->select('tp.tanggal, v.varian, mp.kode_batch, mp.formula_kg, mp.rework_kg, mp.total_output, mp.batch_persen, mp.uuid as uuid_mp');
		$this->db->from('mp_usage mp');
		$this->db->join('t_planning tp', 'mp.t_planning_uuid = tp.uuid', 'left');
		$this->db->join('varian v', 'mp.varian_uuid = v.uuid', 'left');
		// $this->db->order_by('mp.kode_batch', 'ASC');
		$this->db->order_by('mp.created_at', 'DESC');
		$data = $this->db->get()->result();
		return $data;
	}
	public function get_by_uuid($uuid)
	{
		$this->db->select('
			tp.tanggal,
			v.varian,
			v.uuid as uuid_varian,
			mp.tbatch_uuid,
			mp.uuid as uuid_mp,
			mp.kode_batch,
			mp.formula_uuid,
			mp.formula_kg,
			mp.rework_kg,
			mp.total_output,
			mp.batch_persen,
			rp.plastik,
			rp.metal
			');
		$this->db->from('mp_usage mp');
		$this->db->join(
			't_planning tp',
			'mp.t_planning_uuid = tp.uuid',
			'left'
		);
		$this->db->join(
			'varian v',
			'mp.varian_uuid = v.uuid',
			'left'
		);
		$this->db->join(
			'rwk_pakai rp',
			'rp.mp_usage_uuid = mp.uuid',
			'left'
		);
		$this->db->where('mp.uuid', $uuid);
		return $this->db->get()->row();
	}
	private function get_rework_fifo($varian_uuid)
	{
		$this->db
			->where('varian_uuid', $varian_uuid)
			->order_by('created_at', 'ASC');
		return $this->db->get('rwk_kupas')->result();
	}
	private function get_remaining_stock($rwk_kupas_uuid)
	{
		$stok = $this->db
			->where('uuid', $rwk_kupas_uuid)
			->get('rwk_kupas')
			->row();
		$pakai = $this->db
			->select_sum('dipakai')
			->where('rwk_kupas_uuid', $rwk_kupas_uuid)
			->get('rwk_pakai')
			->row();
		return $stok->berat - ($pakai->dipakai ?? 0);
	}
	public function update_mp_usage($uuid)
	{
		$this->db->trans_begin();
		try {
			$rework_kg = $this->input->post('use_rework')
				? (float)$this->input->post('rework_kg')
				: 0;
			/*
        |--------------------------------------------------------------------------
        | UPDATE MP USAGE
        |--------------------------------------------------------------------------
        */
			$formula_kg  = (float)$this->input->post('formula_kg');
			$rework_kg   = $this->input->post('use_rework')
				? (float)$this->input->post('rework_kg')
				: 0;
			$total_output = $formula_kg + $rework_kg;
			$mp_usage = [
				'formula_uuid' => $this->input->post('formula_uuid'),
				'formula_kg'   => $this->input->post('formula_kg'),
				'rework_kg'    => $rework_kg,
				'total_output' => $total_output,
				'batch_persen' 		=> $this->input->post('batch_persen'),
				'modified_at'  => date('Y-m-d H:i:s')
			];
			$this->db
				->where('uuid', $uuid)
				->update('mp_usage', $mp_usage);
			/*
        |--------------------------------------------------------------------------
        | update tbatch
        |--------------------------------------------------------------------------
        */
			$mp = $this->db
				->select('tbatch_uuid')
				->where('uuid', $uuid)
				->get('mp_usage')
				->row();
			$this->db
				->where('uuid', $mp->tbatch_uuid)
				->update('tbatch', [
					'adonan'     => $total_output
				]);
			/*
        |--------------------------------------------------------------------------
        | HAPUS mp_usage_detail LAMA
        |--------------------------------------------------------------------------
        */
			$this->db
				->where('mp_usage_uuid', $uuid)
				->delete('mp_usage_detail');
			/*
        |--------------------------------------------------------------------------
        | ambil data formula detail aktif
        |--------------------------------------------------------------------------
        */
			$formula_detail = $this->db
				->where('formula_uuid', $this->input->post('formula_uuid'))
				->where('deleted_at', NULL)
				->get('m_formula_detail')
				->result();
			$batch = (float)$this->input->post('batch_persen');
			$data_insert = [];
			foreach ($formula_detail as $row) {
				$data_insert[] = [
					'uuid'                  => Uuid::uuid4()->toString(),
					'mp_usage_uuid'         => $uuid,
					'm_formula_detail_uuid' => $row->uuid,
					'bahan_uuid'            => $row->bahan_uuid,
					'nama_bahan'            => $row->nama_bahan,
					'qty'                   => $row->qty * $batch,
					'created_at'            => date('Y-m-d H:i:s')
				];
			}
			if (!empty($data_insert)) {
				$this->db->insert_batch('mp_usage_detail', $data_insert);
			}
			/*
        |--------------------------------------------------------------------------
        | HAPUS ALOKASI FIFO LAMA
        |--------------------------------------------------------------------------
        */
			$this->db
				->where('mp_usage_uuid', $uuid)
				->delete('rwk_pakai');
			/*
        |--------------------------------------------------------------------------
        | FIFO REWORK
        |--------------------------------------------------------------------------
        */
			if ($rework_kg > 0) {
				$need = $rework_kg;
				$fifo = $this->get_rework_fifo(
					$this->input->post('varian_uuid')
				);
				foreach ($fifo as $item) {
					if ($need <= 0) {
						break;
					}
					$available = $this->get_remaining_stock($item->uuid);
					if ($available <= 0) {
						continue;
					}
					$pakai = min($need, $available);
					$rwk_pakai = [
						'uuid'           => Uuid::uuid4()->toString(),
						'mp_usage_uuid'  => $uuid,
						'rwk_kupas_uuid' => $item->uuid,
						'dipakai'        => $pakai,
						'plastik'        => $this->input->post('plastik'),
						'metal'          => $this->input->post('metal'),
						'created_at'     => date('Y-m-d H:i:s')
					];
					$this->db->insert('rwk_pakai', $rwk_pakai);
					$need -= $pakai;
				}
				/*
            |--------------------------------------------------------------------------
            | CEK STOK CUKUP
            |--------------------------------------------------------------------------
            */
				if ($need > 0) {
					throw new Exception(
						'Stok rework tidak mencukupi. Kekurangan ' .
							number_format($need, 2) .
							' Kg'
					);
				}
			}
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Gagal menyimpan data');
			}
			$this->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', $e->getMessage());
			return false;
		}
	}
	public function get_total_rework_available($varian_uuid)
	{
		$this->db->select('uuid, berat');
		$this->db->from('rwk_kupas');
		$this->db->where('varian_uuid', $varian_uuid);
		$this->db->order_by('created_at', 'ASC');
		$rwk = $this->db->get()->result();
		$total = 0;
		foreach ($rwk as $row) {
			$this->db->select_sum('dipakai');
			$this->db->where('rwk_kupas_uuid', $row->uuid);
			$pakai = $this->db->get('rwk_pakai')->row();
			$sisa = $row->berat - ($pakai->dipakai ?? 0);
			if ($sisa > 0) {
				$total += $sisa;
			}
		}
		return $total;
	}
	private function insert_mp_usage_detail($mp_usage_uuid, $formula_uuid)
	{
		// |--------------------------------------------------------------------------
		// | AMBIL FORMULA DETAIL AKTIF
		// |--------------------------------------------------------------------------
		$formula_detail = $this->db
			->where('formula_uuid', $formula_uuid)
			->where('status', 1)
			->get('m_formula_detail')
			->result();
		foreach ($formula_detail as $row) {
			$detail = [
				'uuid'                  => Uuid::uuid4()->toString(),
				'mp_usage_uuid'         => $mp_usage_uuid,
				'm_formula_detail_uuid' => $row->uuid,
				'bahan_uuid'            => $row->bahan_uuid,
				'qty'                   => $row->qty,
				'created_at'            => date('Y-m-d H:i:s')
			];
			$this->db->insert(
				'mp_usage_detail',
				$detail
			);
		}
	}
}
