<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Track_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Filler_model');
		$this->load->model('Rj_filler_model');
		// $this->dberetort = $this->load->database('e-retort', TRUE);
		$this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');
	}

	public function get_data_by_mesin_and_range($mesin_uuid, $awal, $akhir)
{
    if (!$awal || !$akhir || !$mesin_uuid) return [];

    // Ambil data planning dalam range tanggal
    $this->db->select('uuid, tanggal, varian, formula, filkar');
    $this->db->from('t_planning');
    $this->db->where('tanggal >=', $awal);
    $this->db->where('tanggal <=', $akhir);
    $planning = $this->db->get()->result_array();

    if (empty($planning)) return [];

    // Mapping UUID ke tanggal, varian, formula
    $planning_map = [];
    foreach ($planning as $val) {
        $tanggal_format = date('d F Y', strtotime($val['tanggal']));
        $nama_varian = '-';
        switch ($val['varian']) {
            case 1: $nama_varian = 'OKEY'; break;
            case 2: $nama_varian = 'CHAMP AYAM'; break;
            case 3: $nama_varian = 'CHAMP SAPI'; break;
            case 4: $nama_varian = 'CHAMP OTAK-OTAK'; break;
        }
        $planning_map[$val['uuid']] = [
            'tanggal_format' => $tanggal_format,
            'nama_varian'    => $nama_varian,
            'formula'        => $val['formula'] ?? 0
        ];
    }

    $planning_uuids = array_keys($planning_map);

    $tables = [
        'rj_filler'   => 't_planning_uuid',
        'ch_rj_mesin' => 't_planning_uuid',
        'v_smfgmsn'   => 't_planning_uuid'
    ];

    $data = [];

    foreach ($tables as $table => $planning_key) {
        $this->db->select("$planning_key, SUM(berat) as total_berat");
        $this->db->from($table);
        $this->db->where_in("$planning_key", $planning_uuids);
        $this->db->where("mesin_uuid", $mesin_uuid);
        $this->db->group_by($planning_key);
        $query = $this->db->get();

        if ($query === false) {
            log_message('error', "Query $table gagal: " . $this->db->last_query());
            return false;
        }

        $result = $query->result_array();

        // Tambahkan info dari mapping dan hitung persentase
        foreach ($result as $key => $row) {
            $uuid = $row[$planning_key];
            $formula = floatval($planning_map[$uuid]['formula']);
            $berat = floatval($row['total_berat']);

            $persen = ($formula > 0) ? round(($berat / $formula) * 100, 2) : 0;

            $result[$key]['tanggal_format'] = $planning_map[$uuid]['tanggal_format'] ?? '-';
            $result[$key]['nama_varian']    = $planning_map[$uuid]['nama_varian'] ?? '-';
            $result[$key]['formula']        = $formula;
            $result[$key]['persentase']     = $persen;
        }

        $data[$table] = $result;
    }

    return $data;
}


}