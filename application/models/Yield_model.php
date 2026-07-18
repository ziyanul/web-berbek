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
            SUM(tbatch.adonan)          AS adonan_formula,
            SUM(tbatch.filkar_box)              AS filkar_box,
            SUM(tbatch.filkar_kg)               AS filkar_kg,
            SUM(tbatch.sortasi_box)             AS sortasi_box,
            SUM(tbatch.release_box)             AS release_box,
            SUM(tbatch.bad_filkar_rework_kg)       AS filkar_rework,
            SUM(tbatch.bad_filkar_reject_kg)       AS filkar_reject,
            SUM(tbatch.bad_sortasi_rework_kg)      AS sortasi_rework,
            SUM(tbatch.bad_sortasi_reject_kg)      AS sortasi_reject
        ");
        $this->db->from('tbatch');
        $this->db->join(
            't_planning',
            't_planning.uuid = tbatch.t_planning_uuid',
            'left'
        );
        $this->db->join(
            'varian',
            't_planning.varian = varian.uuid',
            'left'
        );
        $this->db->where('MONTH(t_planning.tanggal)', $bulan);
        $this->db->where('YEAR(t_planning.tanggal)', $tahun);
        $this->db->group_by('t_planning.varian');
        $this->db->order_by('t_planning.varian');
        $rows = $this->db->get()->result();
        foreach ($rows as &$r) {
            $r->blm_sortir = $r->filkar_box - $r->sortasi_box;
            $produksi =
                $r->release_box;
            $yield_formula = 0;
            $yield_rework  = 0;
            if ($r->adonan_formula > 0) {
                $yield_formula = ($produksi / $r->adonan_formula) * 100;
            }
            $r->yield_formula = round($yield_formula, 2);
            $r->yield_rework  = round($yield_rework, 2);
        }
        return $rows;
    }
}
