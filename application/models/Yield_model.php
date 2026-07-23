<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Yield_model extends CI_Model
{
    /* ===========================
     * MASTER
     * =========================== */

    public function list_varian()
{
    return $this->db
        ->order_by('varian')
        ->get('varian')
        ->result();
}

public function list_mesin()
{
    $this->db->select('tb.mesin_uuid, m.nama_mesin');
    $this->db->from('t_badpro tb');
    $this->db->join('mesin m', 'm.uuid = tb.mesin_uuid');
    $this->db->group_by('tb.mesin_uuid, m.nama_mesin');
    $this->db->order_by('nama_mesin');
    return $this->db->get()->result();
}

public function list_badpro()
{
    return $this->db
        ->order_by('urutan')
        ->get('badpro')
        ->result();
}

public function list_planning($filter = [])
{
    $this->db->select("
        tp.uuid,
        tp.tanggal,
        v.varian
    ");

    $this->db->from('t_planning tp');
    $this->db->join('varian v', 'v.uuid = tp.varian');

    $this->apply_filter($filter);

    $this->db->order_by('tp.tanggal', 'DESC');

    return $this->db->get()->result();
}

public function list_batch($filter = [])
{
    $this->db->select("
        b.uuid,
        b.kode_batch
    ");

    $this->db->from('tbatch b');

    $this->db->join(
        't_planning p',
        'p.uuid=b.t_planning_uuid'
    );

    if (!empty($filter['planning'])) {
        $this->db->where('p.uuid',$filter['planning']);
    }

    return $this->db
        ->group_by('b.uuid')
        ->order_by('kode_batch')
        ->get()
        ->result();
}

/* ===========================
     * PRIVATE
     * =========================== */
private function apply_filter($filter = [])
{
    if (!empty($filter['tanggal_awal'])) {
        $this->db->where('p.tanggal >=', $filter['tanggal_awal']);
    }

    if (!empty($filter['tanggal_akhir'])) {
        $this->db->where('p.tanggal <=', $filter['tanggal_akhir']);
    }

    if (!empty($filter['varian'])) {
        $this->db->where('p.varian', $filter['varian']);
    }

    if (!empty($filter['mesin'])) {
        $this->db->where('tbp.mesin_uuid', $filter['mesin']);
    }

    if (!empty($filter['badpro'])) {
        $this->db->where('tbp.badpro_uuid', $filter['badpro']);
    }

    if (!empty($filter['planning'])) {
        $this->db->where('p.uuid', $filter['planning']);
    }

    if (!empty($filter['batch'])) {
        $this->db->where('b.uuid', $filter['batch']);
    }
}

/* ===========================
     * DASHBOARD
     * =========================== */

public function get_yield_produksi($bulan = null, $tahun = null)
    {
        if (!$bulan) $bulan = date('m');
        if (!$tahun) $tahun = date('Y');
        $this->db->select("MAX(varian.varian) AS nama_varian,
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

public function get_bad_produk_varian($bulan = null, $tahun = null)
{
    if (!$bulan) $bulan = date('m');
    if (!$tahun) $tahun = date('Y');

    // ambil daftar varian yang ada pada bulan tsb
    $varian = $this->get_varian_yield($bulan, $tahun);

    $select = "
        bp.uuid,
        MAX(bp.nama_badpro) AS nama_badpro,
        MAX(bp.urutan) AS urutan,
    ";

    foreach ($varian as $v) {

        $kode = $v->varian;

        $select .= "
            SUM(
                CASE
                    WHEN vr.varian = '{$kode}'
                    THEN tbp.berat
                    ELSE 0
                END
            ) AS `{$kode}`,
        ";
    }

    $select .= "
        SUM(tbp.berat) AS total
    ";

    $this->db->select($select, false);

    $this->db->from('t_badpro tbp');

    $this->db->join(
        'badpro bp',
        'bp.uuid = tbp.badpro_uuid'
    );

    $this->db->join(
        'tbatch b',
        'b.uuid = tbp.tbatch_uuid'
    );

    $this->db->join(
        't_planning p',
        'p.uuid = b.t_planning_uuid'
    );

    $this->db->join(
        'varian vr',
        'vr.uuid = p.varian'
    );

    $this->db->where('MONTH(p.tanggal)', $bulan);
    $this->db->where('YEAR(p.tanggal)', $tahun);

    $this->db->group_by('bp.uuid');

    $this->db->order_by('MAX(bp.urutan)', 'ASC', false);

    return $this->db->get()->result();
}

public function get_varian_yield($bulan = null, $tahun = null)
{
    if (!$bulan) $bulan = date('m');
    if (!$tahun) $tahun = date('Y');

    return $this->db
        ->select("
            v.uuid,
            MAX(v.varian) AS varian
        ")
        ->from('tbatch b')
        ->join('t_planning p','p.uuid=b.t_planning_uuid')
        ->join('varian v','v.uuid=p.varian')
        ->where('MONTH(p.tanggal)', $bulan)
        ->where('YEAR(p.tanggal)', $tahun)
        ->group_by('v.uuid')
        ->order_by('varian')
        ->get()
        ->result();
}

public function get_master_bad_produk()
{
    return $this->db
        ->select("
            uuid,
            MAX(nama_badpro) AS nama_badpro,
            MAX(urutan) AS urutan
        ")
        ->from('badpro')
        ->group_by('uuid')
        ->order_by('urutan')
        ->where('deleted_at IS NULL')
        ->get()
        ->result();
}

public function get_bad_produk_mesin($bulan = null, $tahun = null)
{
    if (!$bulan) $bulan = date('m');
    if (!$tahun) $tahun = date('Y');

    $badproduk = $this->get_master_bad_produk();

    $select = "
        m.uuid,
        MAX(m.nama_mesin) AS mesin,
    ";

    foreach ($badproduk as $bp) {

        $select .= "
            SUM(
                CASE
                    WHEN tbp.badpro_uuid = '{$bp->uuid}'
                    THEN tbp.berat
                    ELSE 0
                END
            ) AS `{$bp->nama_badpro}`,
        ";
    }

    $select .= "
        SUM(tbp.berat) AS total
    ";

    $this->db->select($select, FALSE);

    $this->db->from('t_badpro tbp');

    $this->db->join(
        'tbatch b',
        'b.uuid = tbp.tbatch_uuid'
    );

    $this->db->join(
        't_planning p',
        'p.uuid = b.t_planning_uuid'
    );

    // sesuaikan nama tabel mesin Anda
    $this->db->join(
        'mesin m',
        'm.uuid = tbp.mesin_uuid'
    );

    $this->db->where('MONTH(p.tanggal)', $bulan);
    $this->db->where('YEAR(p.tanggal)', $tahun);

    $this->db->group_by('m.uuid');

    $this->db->order_by('MAX(m.nama_mesin)', 'ASC', FALSE);

    return $this->db->get()->result();
}

/* ===========================
     * ANALISA
     * =========================== */
    
public function monitoring_filter($filter = [])
    {
        
    }

    public function bad_produk_varian_filter($filter = [])
    {
       
    }

    public function bad_produk_mesin_filter($filter = [])
    {
       
    }
    
    
    public function detail_mp_usage()
    {
        
    }
    public function detail_filkar()
    {
       
    }
    public function detail_sortasi()
    {
        
    }
    public function detail_bad_produk()
    {
       
    }

    

}