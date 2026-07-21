<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Yield_model extends CI_Model
{
    public function monitoring_bulan($bulan = null, $tahun = null)
{
    if (!$bulan) $bulan = date('m');
    if (!$tahun) $tahun = date('Y');

    $this->db->select("
        MAX(varian.varian) AS nama_varian,
        COALESCE(SUM(tbatch.adonan),0)                     AS adonan_formula,
COALESCE(SUM(tbatch.filkar_box),0)                 AS filkar_box,
COALESCE(SUM(tbatch.filkar_kg),0)                  AS filkar_kg,
COALESCE(SUM(tbatch.sortasi_box),0)                AS sortasi_box,
COALESCE(SUM(tbatch.release_box),0)                AS release_box,
COALESCE(SUM(tbatch.bad_filkar_rework_kg),0)       AS filkar_rework,
COALESCE(SUM(tbatch.bad_filkar_reject_kg),0)       AS filkar_reject,
COALESCE(SUM(tbatch.bad_sortasi_rework_kg),0)      AS sortasi_rework,
COALESCE(SUM(tbatch.bad_sortasi_reject_kg),0)      AS sortasi_reject
    ");

    $this->db->from('tbatch');
    $this->db->join('t_planning', 't_planning.uuid = tbatch.t_planning_uuid', 'left');
    $this->db->join('varian', 't_planning.varian = varian.uuid', 'left');

    $this->db->where('MONTH(t_planning.tanggal)', $bulan);
    $this->db->where('YEAR(t_planning.tanggal)', $tahun);

    $this->db->group_by('t_planning.varian');
    $this->db->order_by('t_planning.varian');

    $rows = $this->db->get()->result();

    $total = (object)[
        'adonan_formula' => 0,
        'filkar_box' => 0,
        'filkar_kg' => 0,
        'sortasi_box' => 0,
        'release_box' => 0,
        'blm_sortir' => 0,
        'filkar_rework' => 0,
        'filkar_reject' => 0,
        'sortasi_rework' => 0,
        'sortasi_reject' => 0,
        'yield_formula' => 0,
        'yield_release' => 0,
    ];

    foreach ($rows as $r) {

        $r->blm_sortir = $r->filkar_box - $r->sortasi_box;

        $r->yield_formula = ($r->adonan_formula > 0)
            ? round(($r->filkar_kg / $r->adonan_formula) * 100, 2)
            : 0;

        $r->yield_release = ($r->filkar_box > 0)
            ? round(($r->release_box / $r->filkar_box) * 100, 2)
            : 0;

        $total->adonan_formula += (float)$r->adonan_formula;
        $total->filkar_box += (float)$r->filkar_box;
        $total->filkar_kg += (float)$r->filkar_kg;
        $total->sortasi_box += (float)$r->sortasi_box;
        $total->release_box += (float)$r->release_box;
        $total->blm_sortir += (float)$r->blm_sortir;
        $total->filkar_rework += (float)$r->filkar_rework;
        $total->filkar_reject += (float)$r->filkar_reject;
        $total->sortasi_rework += (float)$r->sortasi_rework;
        $total->sortasi_reject += (float)$r->sortasi_reject;
    }

    $total->yield_formula = ($total->adonan_formula > 0)
        ? round(($total->filkar_kg / $total->adonan_formula) * 100, 2)
        : 0;

    $total->yield_release = ($total->filkar_box > 0)
        ? round(($total->release_box / $total->filkar_box) * 100, 2)
        : 0;

    return [
        'rows'  => $rows,
        'total' => $total
    ];
}

    
}