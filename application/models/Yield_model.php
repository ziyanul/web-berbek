<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Yield_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Proses_model');
    }
    /* ===========================
     * PRIVATE
     * =========================== */
    private function apply_filter_analisa($filter)
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
    public function get_bad_produk_varian($varian)
    {
        $proses_filkar  = $this->Proses_model->get_uuid('FILKAR');
        $proses_sortasi = $this->Proses_model->get_uuid('SORTASI');
        $this->db->select("
        badpro.nama_badpro
    ");
        foreach ($varian as $v) {
            $alias = $v->varian;
            $this->db->select("
            SUM(
                CASE
                    WHEN tp.varian = '{$v->uuid}'
                    THEN t_badpro.berat
                    ELSE 0
                END
            ) AS `{$alias}`
        ", false);
        }
        $this->db->select("
        SUM(t_badpro.berat) AS total
    ", false);
        $this->db->from('t_badpro');
        $this->db->join(
            'badpro',
            'badpro.uuid = t_badpro.badpro_uuid',
            'left'
        );
        $this->db->join(
            'tbatch tb',
            'tb.uuid = t_badpro.tbatch_uuid',
            'left'
        );
        $this->db->join(
            't_planning tp',
            'tp.uuid = tb.t_planning_uuid',
            'left'
        );
        // FILKAR + SORTASI
        $this->db->where_in(
            't_badpro.proses_uuid',
            [
                $proses_filkar,
                $proses_sortasi
            ]
        );
        // bulan berjalan berdasarkan input bad
        $this->db->where(
            'MONTH(t_badpro.created_at)',
            date('m')
        );
        $this->db->where(
            'YEAR(t_badpro.created_at)',
            date('Y')
        );
        $this->db->where(
            't_badpro.deleted_at',
            NULL
        );
        $this->db->group_by([
            'badpro.uuid',
            'badpro.nama_badpro'
        ]);
        $this->db->order_by(
            'badpro.nama_badpro',
            'ASC'
        );
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
            ->join('t_planning p', 'p.uuid=b.t_planning_uuid')
            ->join('varian v', 'v.uuid=p.varian')
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
    public function get_bad_produk()
    {
        return $this->db
            ->select("
            tbp.badpro_uuid,
            MAX(bp.nama_badpro) AS nama_badpro,
            MAX(bp.urutan) AS urutan,
            SUM(
            tbp.berat
            ) AS berat_badpro,
        ")
            ->from('t_badpro tbp')
            ->join('badpro bp', 'bp.uuid = tbp.badpro_uuid', 'left')
            ->group_by('tbp.badpro_uuid')
            ->order_by('berat_badpro', 'DESC')
            ->where('bp.deleted_at IS NULL')
            ->limit(8)
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
    public function get_master_varian()
    {
        return $this->db
            ->where('deleted_at IS NULL')
            ->order_by('varian')
            ->get('varian')
            ->result();
    }
    public function get_master_mesin()
    {
        $this->db->select('tb.mesin_uuid as uuid, m.nama_mesin');
        $this->db->from('t_badpro tb');
        $this->db->join('mesin m', 'm.uuid = tb.mesin_uuid', 'left');
        $this->db->group_by('m.nama_mesin, tb.mesin_uuid');
        $this->db->where('tb.deleted_at is null', null, false);
        $this->db->where('tb.mesin_uuid !=', '');
        $this->db->order_by('m.nama_mesin', 'ASC');
        $data = $this->db->get()->result();
        return $data;
    }
    public function get_monitoring_analisa($filter)
    {
        $this->db->select("
        MAX(v.varian) AS nama_varian,
        COALESCE(SUM(b.adonan),0)                AS adonan_formula,
        COALESCE(SUM(b.filkar_box),0)            AS filkar_box,
        COALESCE(SUM(b.filkar_kg),0)             AS filkar_kg,
        COALESCE(SUM(b.sortasi_box),0)           AS sortasi_box,
        COALESCE(SUM(b.release_box),0)           AS release_box,
        COALESCE(SUM(b.bad_filkar_rework_kg),0)  AS filkar_rework,
        COALESCE(SUM(b.bad_filkar_reject_kg),0)  AS filkar_reject,
        COALESCE(SUM(b.bad_sortasi_rework_kg),0) AS sortasi_rework,
        COALESCE(SUM(b.bad_sortasi_reject_kg),0) AS sortasi_reject
    ");
        $this->db->from('tbatch b');
        $this->db->join(
            't_planning p',
            'p.uuid = b.t_planning_uuid',
            'left'
        );
        $this->db->join(
            'varian v',
            'v.uuid = p.varian',
            'left'
        );
        $this->apply_filter_analisa($filter);
        $this->db->group_by('p.varian');
        $this->db->order_by('nama_varian', 'ASC', FALSE);
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
    public function get_ringkasan_analisa($filter)
    {
        $this->db->select("
        COUNT(DISTINCT b.uuid)                    AS total_batch,
        COALESCE(SUM(b.adonan),0)                 AS adonan_formula,
        COALESCE(SUM(b.filkar_box),0)             AS filkar_box,
        COALESCE(SUM(b.filkar_kg),0)              AS filkar_kg,
        COALESCE(SUM(b.sortasi_box),0)            AS sortasi_box,
        COALESCE(SUM(b.release_box),0)            AS release_box,
        COALESCE(SUM(b.bad_filkar_rework_kg),0)   AS filkar_rework,
        COALESCE(SUM(b.bad_filkar_reject_kg),0)   AS filkar_reject,
        COALESCE(SUM(b.bad_sortasi_rework_kg),0)  AS sortasi_rework,
        COALESCE(SUM(b.bad_sortasi_reject_kg),0)  AS sortasi_reject
    ");
        $this->db->from('tbatch b');
        $this->db->join(
            't_planning p',
            'p.uuid=b.t_planning_uuid',
            'left'
        );
        $this->apply_filter_analisa($filter);
        $row = $this->db->get()->row();
        if (!$row) {
            return null;
        }
        $row->blm_sortir =
            $row->filkar_box - $row->sortasi_box;
        $row->yield_formula =
            ($row->adonan_formula > 0)
            ? round(($row->filkar_kg / $row->adonan_formula) * 100, 2)
            : 0;
        $row->yield_release =
            ($row->filkar_box > 0)
            ? round(($row->release_box / $row->filkar_box) * 100, 2)
            : 0;
        return $row;
    }
    public function get_bad_produk_varian_analisa($filter)
    {
        // Master Varian
        $varian = $this->get_master_varian();
        $select = "
        bp.uuid,
        MAX(bp.nama_badpro) AS nama_badpro,
        MAX(bp.kategori) AS kategori,
        MAX(pr.nama_proses) AS proses,
    ";
        foreach ($varian as $v) {
            $uuid = $this->db->escape_str($v->uuid);
            $select .= "
            SUM(
                CASE
                    WHEN p.varian = '{$uuid}'
                    THEN tbp.berat
                    ELSE 0
                END
            ) AS `{$v->uuid}`,
        ";
        }
        $select .= "
        SUM(tbp.berat) AS total
    ";
        $this->db->select($select, FALSE);
        $this->db->from('t_badpro tbp');
        $this->db->join(
            'badpro bp',
            'bp.uuid = tbp.badpro_uuid',
            'left'
        );
        $this->db->join(
            'm_proses pr',
            'pr.uuid = bp.proses_uuid',
            'left'
        );
        $this->db->join(
            'tbatch b',
            'b.uuid = tbp.tbatch_uuid',
            'left'
        );
        $this->db->join(
            't_planning p',
            'p.uuid = b.t_planning_uuid',
            'left'
        );
        // Filter tanggal + varian
        $this->apply_filter_analisa($filter);
        // Filter bad produk
        if (!empty($filter['badpro'])) {
            $this->db->where(
                'tbp.badpro_uuid',
                $filter['badpro']
            );
        }
        /*
        Jangan filter mesin di sini.
        Karena:
        - Filkar tidak punya mesin
        - Sortasi punya mesin
        Kalau mesin difilter,
        tabel ini akan kehilangan data Filkar.
    */
        $this->db->where('tbp.deleted_at IS NULL');
        $this->db->group_by([
            'bp.uuid',
            'bp.nama_badpro'
        ]);
        $this->db->order_by(
            'MAX(bp.urutan)',
            'ASC',
            FALSE
        );
        return [
            'varian' => $varian,
            'rows'   => $this->db->get()->result()
        ];
    }
    public function get_bad_produk_mesin_analisa($filter)
{
    // =========================================================
    // MASTER BAD PRODUK
    // =========================================================
    $badproduk = $this->get_master_bad_produk();
    $select = "
        m.uuid AS mesin_uuid,
        MAX(m.nama_mesin) AS mesin,
    ";
    foreach ($badproduk as $bp) {
        $uuid = $this->db->escape_str($bp->uuid);
        $select .= "
            SUM(
                CASE
                    WHEN tbp.badpro_uuid = '{$uuid}'
                    THEN
                        tbp.berat *
                        (
                            COALESCE(tc_mesin.output_mesin, 0)
                            /
                            NULLIF(b.total, 0)
                        )
                    ELSE 0
                END
            ) AS `{$bp->uuid}`,
        ";
    }
    $select .= "
        COALESCE(
            SUM(
                tbp.berat *
                (
                    COALESCE(tc_mesin.output_mesin, 0)
                    /
                    NULLIF(b.total, 0)
                )
            ),
            0
        ) AS total,
        COALESCE(
            SUM(tc_mesin.output_mesin),
            0
        ) AS output_mesin
    ";
    $this->db->select($select, FALSE);
    // =========================================================
    // BAD PRODUK
    // =========================================================
    $this->db->from('t_badpro tbp');
    // Sortasi sebagai sumber bad produk
    $this->db->join(
        'sortasi s',
        's.uuid = tbp.ref_uuid',
        'inner'
    );
    // Batch
    $this->db->join(
        'tbatch b',
        'b.uuid = s.tbatch_uuid',
        'inner'
    );
    // Planning
    $this->db->join(
        't_planning p',
        'p.uuid = b.t_planning_uuid',
        'left'
    );
    // Master bad produk
    $this->db->join(
        'badpro bp',
        'bp.uuid = tbp.badpro_uuid',
        'left'
    );
    // =========================================================
    // COUNTER MESIN
    //
    // Diambil dari subquery supaya:
    // 1. counter tiap mesin dijumlahkan dahulu
    // 2. tidak terjadi duplikasi tbp.berat
    // =========================================================
    $counter_sql = "
        (
            SELECT
                tc.tbatch_uuid,
                tc.mesin_uuid,
                SUM(tc.counter) AS output_mesin
            FROM tcounter tc
            GROUP BY
                tc.tbatch_uuid,
                tc.mesin_uuid
        ) tc_mesin
    ";
    $this->db->join(
        $counter_sql,
        'tc_mesin.tbatch_uuid = b.uuid',
        'inner',
        FALSE
    );
    // Mesin
    $this->db->join(
        'mesin m',
        'm.uuid = tc_mesin.mesin_uuid',
        'left'
    );
    // =========================================================
    // FILTER
    // =========================================================
    $this->apply_filter_analisa($filter);
    // Filter mesin
    if (!empty($filter['mesin'])) {
        $this->db->where(
            'tc_mesin.mesin_uuid',
            $filter['mesin']
        );
    }
    // Filter bad produk
    if (!empty($filter['badpro'])) {
        $this->db->where(
            'tbp.badpro_uuid',
            $filter['badpro']
        );
    }
    // Data aktif
    $this->db->where(
        'tbp.deleted_at IS NULL'
    );
    $this->db->where(
        's.deleted_at IS NULL'
    );
    $this->db->where(
        'b.deleted_at IS NULL'
    );
    $this->db->where(
        'p.deleted_at IS NULL'
    );
    // =========================================================
    // GROUP
    // =========================================================
    $this->db->group_by(
        'm.uuid'
    );
    $this->db->order_by(
        'MAX(m.nama_mesin)',
        'ASC',
        FALSE
    );
    $rows = $this->db->get()->result();
    // =========================================================
    // HITUNG KONTRIBUSI OUTPUT
    // =========================================================
    $total_output = 0;
    foreach ($rows as $row) {
        $total_output += (float) $row->output_mesin;
    }
    foreach ($rows as $row) {
        $row->output_mesin =
            (float) $row->output_mesin;
        $row->total =
            (float) $row->total;
        // Kontribusi mesin terhadap seluruh output
        $row->kontribusi_output =
            ($total_output > 0)
            ? round(
                ($row->output_mesin / $total_output) * 100,
                2
            )
            : 0;
        // Bad / output
        $row->bad_per_output =
            ($row->output_mesin > 0)
            ? round(
                ($row->total / $row->output_mesin) * 100,
                4
            )
            : 0;
    }
    return [
        'badproduk' => $badproduk,
        'rows'      => $rows
    ];
}
    public function get_detail_batch_analisa($filter)
    {
        $this->db->select("
    b.uuid,
    b.kode_batch,
    p.tanggal,
    v.varian,
    (
        SELECT GROUP_CONCAT(
            DISTINCT ms.nama_mesin
            ORDER BY ms.nama_mesin
            SEPARATOR ', '
        )
        FROM tcounter tc
        JOIN mesin ms
            ON ms.uuid = tc.mesin_uuid
        WHERE tc.tbatch_uuid = b.uuid
    ) AS nama_mesin,
    b.adonan,
    b.filkar_box,
    b.filkar_kg,
    b.sortasi_box,
    b.release_box,
    b.bad_filkar_rework_kg,
    b.bad_filkar_reject_kg,
    b.bad_sortasi_rework_kg,
    b.bad_sortasi_reject_kg
", FALSE);
        $this->db->from('tbatch b');
        $this->db->join(
            't_planning p',
            'p.uuid=b.t_planning_uuid',
            'left'
        );
        $this->db->join(
            'varian v',
            'v.uuid=p.varian',
            'left'
        );
        $this->apply_filter_analisa($filter);
        if (!empty($filter['mesin'])) {
            $this->db->where("
        EXISTS
        (
            SELECT 1
            FROM tcounter tc
            WHERE tc.tbatch_uuid = b.uuid
            AND tc.mesin_uuid = " . $this->db->escape($filter['mesin']) . "
        )
    ", NULL, FALSE);
        }
        if (!empty($filter['badpro'])) {
            $this->db->where("
            EXISTS
            (
                SELECT 1
                FROM t_badpro tbp
                WHERE tbp.tbatch_uuid=b.uuid
                AND tbp.badpro_uuid=" . $this->db->escape($filter['badpro']) . "
            )
        ", NULL, FALSE);
        }
        $this->db->order_by('p.tanggal', 'DESC');
        $this->db->order_by('b.kode_batch', 'DESC');
        $rows = $this->db->get()->result();
        foreach ($rows as $r) {
            $r->adonan                 = (float) ($r->adonan ?? 0);
            $r->filkar_box             = (float) ($r->filkar_box ?? 0);
            $r->filkar_kg              = (float) ($r->filkar_kg ?? 0);
            $r->sortasi_box            = (float) ($r->sortasi_box ?? 0);
            $r->release_box            = (float) ($r->release_box ?? 0);
            $r->bad_filkar_rework_kg   = (float) ($r->bad_filkar_rework_kg ?? 0);
            $r->bad_filkar_reject_kg   = (float) ($r->bad_filkar_reject_kg ?? 0);
            $r->bad_sortasi_rework_kg  = (float) ($r->bad_sortasi_rework_kg ?? 0);
            $r->bad_sortasi_reject_kg  = (float) ($r->bad_sortasi_reject_kg ?? 0);
            $r->belum_sortir =
                $r->filkar_box - $r->sortasi_box;
            $r->yield_formula =
                ($r->adonan > 0)
                ? round(($r->filkar_kg / $r->adonan) * 100, 2)
                : 0;
            $r->yield_release =
                ($r->filkar_box > 0)
                ? round(($r->release_box / $r->filkar_box) * 100, 2)
                : 0;
        }
        return $rows;
    }
    public function get_monitoring_filkar()
    {
        $filkar_proses_uuid = $this->Proses_model->get_uuid('FILKAR');
        $sql = "
        SELECT
            v.varian AS nama_varian, v.berat, v.panjang,
            /* =========================
             * ADONAN
             * ========================= */
            SUM(b.adonan) AS adonan_formula,
            /* =========================
             * FILKAR
             * ========================= */
            SUM(b.filkar_box) AS filkar_box,
            SUM(b.filkar_kg) AS filkar_kg,
            /* =========================
             * BAD PRODUK FILKAR
             * ========================= */
            SUM(b.filkar_rework) AS filkar_rework,
            SUM(b.filkar_reject) AS filkar_reject,
            /* =========================
             * YIELD FILKAR
             * ========================= */
            CASE
                WHEN SUM(b.adonan) > 0
                THEN
                    (
                        SUM(b.filkar_kg)
                        /
                        SUM(b.adonan)
                    ) * 100
                ELSE 0
            END AS yield_formula
        FROM (
            /* =====================================================
             * AGREGASI PER BATCH
             * ===================================================== */
            SELECT
                tb.uuid AS tbatch_uuid,
                tp.varian AS varian_uuid,
                MAX(tb.adonan) AS adonan,
                MAX(tb.filkar_box) AS filkar_box,
                MAX(tb.filkar_kg) AS filkar_kg,
                COALESCE(
                    SUM(
                        CASE
                            WHEN bp.kategori = 1
                            THEN tbp.berat
                            ELSE 0
                        END
                    ),
                    0
                ) AS filkar_rework,
                /* Bad Reject */
                COALESCE(
                    SUM(
                        CASE
                            WHEN bp.kategori = 2
                            THEN tbp.berat
                            ELSE 0
                        END
                    ),
                    0
                ) AS filkar_reject
            FROM t_planning tp
            LEFT JOIN tbatch tb
                ON tb.t_planning_uuid = tp.uuid
            LEFT JOIN t_badpro tbp
                ON tbp.tbatch_uuid = tb.uuid
                AND tbp.proses_uuid = " . $this->db->escape($filkar_proses_uuid) . "
                AND tbp.deleted_at IS NULL
            LEFT JOIN badpro bp
                ON bp.uuid = tbp.badpro_uuid
            WHERE MONTH(tp.tanggal) = MONTH(CURDATE())
              AND YEAR(tp.tanggal) = YEAR(CURDATE())
              AND tp.deleted_at IS NULL
              AND tb.deleted_at IS NULL
            GROUP BY
                tb.uuid,
                tp.varian
        ) b
        INNER JOIN varian v
            ON v.uuid = b.varian_uuid
        GROUP BY
            v.uuid,
            v.varian, v.berat, v.panjang
        ORDER BY
            v.varian
    ";
        return $this->db->query($sql)->result();
    }
    public function get_total_filkar()
    {
        $filkar_proses_uuid = $this->Proses_model->get_uuid('FILKAR');
        $sql = "
        SELECT
            SUM(x.adonan) AS adonan,
            SUM(x.filkar_box) AS filkar_box,
            SUM(x.filkar_kg) AS filkar_kg,
            SUM(x.filkar_rework) AS filkar_rework,
            SUM(x.filkar_reject) AS filkar_reject,
            CASE
                WHEN SUM(x.adonan) > 0
                THEN
                    (
                        SUM(x.filkar_kg)
                        /
                        SUM(x.adonan)
                    ) * 100
                ELSE 0
            END AS yield_formula
        FROM (
            SELECT
                tb.uuid AS tbatch_uuid,
                MAX(tb.adonan) AS adonan,
                MAX(tb.filkar_box) AS filkar_box,
                MAX(tb.filkar_kg) AS filkar_kg,
                COALESCE(
                    SUM(
                        CASE
                            WHEN bp.kategori = 1
                            THEN bp.berat
                            ELSE 0
                        END
                    ),
                    0
                ) AS filkar_rework,
                COALESCE(
                    SUM(
                        CASE
                            WHEN bp.kategori = 2
                            THEN bp.berat
                            ELSE 0
                        END
                    ),
                    0
                ) AS filkar_reject
            FROM t_planning tp
            INNER JOIN tbatch tb
                ON tb.t_planning_uuid = tp.uuid
            LEFT JOIN t_badpro bp
                ON bp.tbatch_uuid = tb.uuid
                AND bp.proses_uuid = " . $this->db->escape($filkar_proses_uuid) . "
                AND bp.deleted_at IS NULL
            WHERE MONTH(tp.tanggal) = MONTH(CURDATE())
              AND YEAR(tp.tanggal) = YEAR(CURDATE())
              AND tp.deleted_at IS NULL
              AND tb.deleted_at IS NULL
            GROUP BY tb.uuid
        ) x
    ";
        return $this->db->query($sql)->row();
    }
    public function get_monitoring_sortasi()
{
    $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
    $this->db->select("
        v.varian AS nama_varian,
        v.box_kg AS berat_box,
        /* =========================
           SORTASI BOX
        ========================= */
        SUM(s.jumlah_wip) AS sortasi_box,
        SUM(s.jml_release) AS release_box,
        COALESCE(
            SUM(tb.filkar_box), 0
            -
            SUM(tb.sortasi_box), 0
        ) AS blm_sortir,
        /* =========================
           BAD PRODUK SORTASI
        ========================= */
        COALESCE(
            SUM(tbp.sortasi_rework),
            0
        ) AS sortasi_rework,
        COALESCE(
            SUM(tbp.sortasi_reject),
            0
        ) AS sortasi_reject,
        COALESCE(
            SUM(tbp.sortasi_bad),
            0
        ) AS sortasi_bad,
        COALESCE(
            SUM(s.jumlah_wip) * v.box_kg,
            0
        ) AS sortasi_kg,
        COALESCE(
            SUM(tbp.sortasi_bad)
            /
            NULLIF(
                SUM(s.jumlah_wip) * v.box_kg,
                0
            ) * 100,
            0
        ) AS bad_persen,
        /* =========================
           YIELD SORTASI
           Release box / sortasi box
        ========================= */
        CASE
            WHEN SUM(s.jumlah_wip) > 0
            THEN
            (
                SUM(s.jml_release)
                /
                SUM(s.jumlah_wip)
            ) * 100
            ELSE 0
        END AS yield_sortasi
    ");
    $this->db->from('sortasi s');
    // batch
    $this->db->join(
        'tbatch tb',
        'tb.uuid = s.tbatch_uuid',
        'left'
    );
    // planning
    $this->db->join(
        't_planning tp',
        'tp.uuid = tb.t_planning_uuid',
        'left'
    );
    // varian
    // RELASI DIKEMBALIKAN PERSIS SEPERTI PUNYA ANDA
    $this->db->join(
        'varian v',
        'v.uuid = tp.varian',
        'left'
    );
    $badpro_subquery = "
        (
            SELECT
                tbp.ref_uuid,
                SUM(
                    CASE
                        WHEN bp.kategori = 1
                        THEN tbp.berat
                        ELSE 0
                    END
                ) AS sortasi_rework,
                SUM(
                    CASE
                        WHEN bp.kategori = 2
                        THEN tbp.berat
                        ELSE 0
                    END
                ) AS sortasi_reject,
                SUM(tbp.berat) AS sortasi_bad
            FROM t_badpro tbp
            LEFT JOIN badpro bp
                ON bp.uuid = tbp.badpro_uuid
            WHERE tbp.proses_uuid = '$proses_uuid'
              AND tbp.deleted_at IS NULL
            GROUP BY tbp.ref_uuid
        ) tbp
    ";
    $this->db->join(
        $badpro_subquery,
        'tbp.ref_uuid = s.uuid',
        'left',
        FALSE
    );
    // bulan berjalan berdasarkan kegiatan sortasi
    $this->db->where(
        'MONTH(s.created_at)',
        date('m')
    );
    $this->db->where(
        'YEAR(s.created_at)',
        date('Y')
    );
    $this->db->where(
        's.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->group_by([
        'v.uuid',
        'v.varian',
        'v.box_kg'
    ]);
    $this->db->order_by(
        'v.varian'
    );
    return $this->db->get()->result();
}
    public function get_total_sortasi()
    {
        $data = $this->get_monitoring_sortasi();
        if (empty($data)) {
            return null;
        }
        $total = new stdClass();
        $total->sortasi_box = 0;
        $total->release_box = 0;
        $total->blm_sortir = 0;
        $total->sortasi_rework = 0;
        $total->sortasi_reject = 0;
        $total->sortasi_bad = 0;
        $total->sortasi_kg = 0;
        foreach ($data as $row) {
            $total->sortasi_box += $row->sortasi_box;
            $total->release_box += $row->release_box;
            $total->blm_sortir += $row->blm_sortir;
            $total->sortasi_rework += $row->sortasi_rework;
            $total->sortasi_reject += $row->sortasi_reject;
            $total->sortasi_bad += $row->sortasi_bad;
            $total->sortasi_kg += $row->sortasi_kg;
        }
        if ($total->sortasi_bad > 0) {
            $total->bad_persen =
                ($total->sortasi_bad /
                    $total->sortasi_kg
                ) * 100;
        } else {
            $total->bad_persen = 0;
        }
        if ($total->sortasi_box > 0) {
            $total->yield_sortasi =
                ($total->release_box /
                    $total->sortasi_box
                ) * 100;
        } else {
            $total->yield_sortasi = 0;
        }
        return $total;
    }
    public function get_bad_produk_mesin_dominan()
{
    $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
    /*
     * =====================================================
     * TOTAL SORTASI BOX BULAN BERJALAN
     *
     * Dipakai sebagai pembagi persentase:
     *
     * total bad / seluruh jumlah_wip * 100
     * =====================================================
     */
    $total_sortasi = $this->db
    ->select("
        COALESCE(
            SUM(s.jumlah_wip * v.box_kg),
            0
        ) AS total_sortasi_kg
    ")
    ->from('sortasi s')
    ->join(
        'tbatch tb',
        'tb.uuid = s.tbatch_uuid',
        'left'
    )
    ->join(
        't_planning tp',
        'tp.uuid = tb.t_planning_uuid',
        'left'
    )
    ->join(
        'varian v',
        'v.uuid = tp.varian',
        'left'
    )
    ->where('s.deleted_at', NULL)
    ->where(
        'MONTH(s.created_at)',
        date('m')
    )
    ->where(
        'YEAR(s.created_at)',
        date('Y')
    )
    ->get()
    ->row();
$total_sortasi_kg = (float) $total_sortasi->total_sortasi_kg;
    /*
     * =====================================================
     * MASTER BAD PRODUK
     * =====================================================
     */
    $badproduk = $this->db
        ->select("
            bp.uuid AS badpro_uuid,
            MAX(bp.nama_badpro) AS nama_badpro,
            MAX(bp.urutan) AS urutan
        ")
        ->from('badpro bp')
        ->where('bp.proses_uuid', $proses_uuid)
        ->where('bp.deleted_at', NULL)
        ->group_by([
            'bp.uuid'
        ])
        ->order_by('urutan', 'ASC')

        ->get()
        ->result();
    /*
     * =====================================================
     * MESIN DOMINAN
     * =====================================================
     */
    $mesin = $this->db
        ->select("
            m.uuid AS mesin_uuid,
            m.nama_mesin AS mesin
        ")
        ->from('t_badpro_mesin tbpm')
        ->join(
            't_badpro tbp',
            'tbp.uuid = tbpm.t_badpro_uuid',
            'inner'
        )
        ->join(
            'sortasi s',
            's.uuid = tbp.ref_uuid',
            'inner'
        )
        ->join(
            'mesin m',
            'm.uuid = tbpm.mesin_uuid',
            'inner'
        )
        ->where('tbp.proses_uuid', $proses_uuid)
        ->where('tbp.deleted_at', NULL)
        ->where('tbpm.deleted_at', NULL)
        ->where('s.deleted_at', NULL)
        ->where(
            'MONTH(s.created_at)',
            date('m')
        )
        ->where(
            'YEAR(s.created_at)',
            date('Y')
        )
        ->group_by([
            'm.uuid',
            'm.nama_mesin'
        ])
        ->order_by(
            'm.nama_mesin',
            'ASC'
        )
        ->get()
        ->result();
    /*
     * =====================================================
     * BUAT ROW MESIN
     * =====================================================
     */
    $rows = [];
    foreach ($mesin as $m) {
        $row = new stdClass();
        $row->mesin_uuid = $m->mesin_uuid;
        $row->mesin = $m->mesin;
        foreach ($badproduk as $bp) {
            $row->{$bp->nama_badpro} = 0;
        }
        $row->total = 0;
        $row->persentase = 0;
        $rows[] = $row;
    }
    /*
     * =====================================================
     * LAIN-LAIN
     * =====================================================
     */
    $lain = new stdClass();
    $lain->mesin_uuid = NULL;
    $lain->mesin = 'Lain-lain';
    foreach ($badproduk as $bp) {
        $lain->{$bp->nama_badpro} = 0;
    }
    $lain->total = 0;
    $lain->persentase = 0;
    /*
     * =====================================================
     * AMBIL BAD PRODUK SORTASI
     * =====================================================
     */
    $bad_data = $this->db
        ->select("
            tbp.uuid,
            tbp.badpro_uuid,
            tbp.berat
        ")
        ->from('t_badpro tbp')
        ->join(
            'sortasi s',
            's.uuid = tbp.ref_uuid',
            'inner'
        )
        ->where(
            'tbp.proses_uuid',
            $proses_uuid
        )
        ->where(
            'tbp.deleted_at',
            NULL
        )
        ->where(
            's.deleted_at',
            NULL
        )
        ->where(
            'MONTH(s.created_at)',
            date('m')
        )
        ->where(
            'YEAR(s.created_at)',
            date('Y')
        )
        ->get()
        ->result();
    /*
     * =====================================================
     * PROSES SETIAP BAD PRODUK
     * =====================================================
     */
    foreach ($bad_data as $bad) {
        /*
         * Ambil Mesin Dominan
         */
        $mesin_bad = $this->db
            ->select('mesin_uuid')
            ->from('t_badpro_mesin')
            ->where(
                't_badpro_uuid',
                $bad->uuid
            )
            ->where(
                'deleted_at',
                NULL
            )
            ->get()
            ->result();
        /*
         * =================================================
         * TIDAK ADA MESIN DOMINAN
         * =================================================
         */
        if (empty($mesin_bad)) {
            foreach ($badproduk as $bp) {
                if (
                    $bp->badpro_uuid ===
                    $bad->badpro_uuid
                ) {
                    $lain->{$bp->nama_badpro} +=
                        (float) $bad->berat;
                    $lain->total +=
                        (float) $bad->berat;
                    break;
                }
            }
            continue;
        }
        /*
         * =================================================
         * ADA MESIN DOMINAN
         *
         * Berat dibagi rata
         * =================================================
         */
        $jumlah_mesin = count($mesin_bad);
        $berat_per_mesin =
            (float) $bad->berat /
            $jumlah_mesin;
        foreach ($mesin_bad as $mb) {
            foreach ($rows as $row) {
                if (
                    $row->mesin_uuid ===
                    $mb->mesin_uuid
                ) {
                    foreach ($badproduk as $bp) {
                        if (
                            $bp->badpro_uuid ===
                            $bad->badpro_uuid
                        ) {
                            $row->{$bp->nama_badpro} +=
                                $berat_per_mesin;
                            $row->total +=
                                $berat_per_mesin;
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
    /*
     * =====================================================
     * SORT BERDASARKAN TOTAL BAD TERBESAR
     * =====================================================
     */
    usort($rows, function ($a, $b) {
        return $b->total <=> $a->total;
    });
    /*
     * =====================================================
     * AMBIL 8 MESIN TERBESAR
     * =====================================================
     */
    // $rows = array_slice($rows, 0, 8);
    /*
     * =====================================================
     * LAIN-LAIN
     * Tetap ditampilkan jika ada.
     * =====================================================
     */
    if ($lain->total > 0) {
        $rows[] = $lain;
    }
    return [
        'badproduk' => $badproduk,
        'rows'      => $rows,
        'total_sortasi_kg' => $total_sortasi_kg
    ];
}
function get_pvdc_wire()
{
    $filkar = $this->get_monitoring_filkar();
    foreach ($filkar as $val) {
        // PVDC
        if ((float) $val->panjang > 0) {
            $val->pvdc = round(($val->filkar_kg / $val->panjang / 100), 3);
        } else {
            $val->pvdc = 0;
        }

        // Wire
        if ((float) $val->berat > 0) {
            $val->wire = round(($val->filkar_kg / $val->berat * 0.000302), 3);
        } else {
            $val->wire = 0;
        }

        $val->reject_pvdc = 0;
        $val->reject_wire = 0;
    }
    return $filkar;
}
public function get_dashboard_mesin_bulan_berjalan()
{
    $bulan = date('m');
    $tahun = date('Y');
    /*
    |--------------------------------------------------------------------------
    | MESIN
    |--------------------------------------------------------------------------
    | Ambil mesin yang benar-benar mempunyai counter pada bulan berjalan.
    |--------------------------------------------------------------------------
    */
    $this->db->select('
        m.uuid AS mesin_uuid,
        m.nama_mesin
    ');
    $this->db->from('tcounter tc');
    $this->db->join(
        'tbatch tb',
        'tb.uuid = tc.tbatch_uuid'
    );
    $this->db->join(
        't_planning p',
        'p.uuid = tb.t_planning_uuid'
    );
    $this->db->join(
        'mesin m',
        'm.uuid = tc.mesin_uuid',
        'left'
    );
    $this->db->where('MONTH(p.tanggal)', $bulan);
    $this->db->where('YEAR(p.tanggal)', $tahun);
    $this->db->where(
        'p.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'tb.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'tc.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'm.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'tc.mesin_uuid IS NOT NULL',
        NULL,
        FALSE
    );
    $this->db->where('tc.counter >', 0);
    $this->db->group_by([
        'm.uuid',
        'm.nama_mesin'
    ]);
    $this->db->order_by(
        'm.nama_mesin',
        'ASC'
    );
    $mesin = $this->db->get()->result();
    /*
    |--------------------------------------------------------------------------
    | SUB COUNTER
    |--------------------------------------------------------------------------
    | Counter dikelompokkan berdasarkan:
    |
    | planning + mesin
    |
    | sehingga counter tidak terduplikasi ketika di-join dengan target.
    |--------------------------------------------------------------------------
    */
    $sub_counter = $this->db
        ->select('
            tb.t_planning_uuid,
            tc.mesin_uuid,
            SUM(tc.counter) AS total_counter
        ', FALSE)
        ->from('tcounter tc')
        ->join(
            'tbatch tb',
            'tb.uuid = tc.tbatch_uuid'
        )
        ->join(
            't_planning p',
            'p.uuid = tb.t_planning_uuid'
        )
        ->where('MONTH(p.tanggal)', $bulan)
        ->where('YEAR(p.tanggal)', $tahun)
        ->where(
            'tc.deleted_at IS NULL',
            NULL,
            FALSE
        )
        ->where(
            'tb.deleted_at IS NULL',
            NULL,
            FALSE
        )
        ->where(
            'p.deleted_at IS NULL',
            NULL,
            FALSE
        )
        ->where(
            'tc.mesin_uuid IS NOT NULL',
            NULL,
            FALSE
        )
        ->group_by([
            'tb.t_planning_uuid',
            'tc.mesin_uuid'
        ])
        ->get_compiled_select();
    /*
    |--------------------------------------------------------------------------
    | SUB TARGET
    |--------------------------------------------------------------------------
    |
    | Target dihitung:
    |
    | speed * 50 * jumlah jam planning
    |
    | kemudian dikelompokkan berdasarkan:
    |
    | planning + mesin
    |--------------------------------------------------------------------------
    */
    $this->db->select('
        s.t_planning_uuid,
        s.mesin_uuid,
        SUM(
            s.speed * 50 *
            (
                TIMESTAMPDIFF(
                    SECOND,
                    p.start,
                    p.end
                ) / 3600
            )
        ) AS total_target
    ', FALSE);
    $this->db->from('t_speed s');
    $this->db->join(
        't_planning p',
        'p.uuid = s.t_planning_uuid'
    );
    $this->db->where('MONTH(p.tanggal)', $bulan);
    $this->db->where('YEAR(p.tanggal)', $tahun);
    $this->db->where(
        's.speed >',
        0
    );
    $this->db->where(
        'p.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        's.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        's.mesin_uuid IS NOT NULL',
        NULL,
        FALSE
    );
    $this->db->group_by([
        's.t_planning_uuid',
        's.mesin_uuid'
    ]);
    $sub_target = $this->db->get_compiled_select();
    /*
    |--------------------------------------------------------------------------
    | PERFORMA
    |--------------------------------------------------------------------------
    |
    | Gabungkan counter + target berdasarkan:
    |
    | planning + mesin
    |--------------------------------------------------------------------------
    */
    $this->db->select('
        tc.mesin_uuid,
        SUM(tc.total_counter) AS total_counter,
        SUM(COALESCE(tg.total_target, 0)) AS total_target
    ', FALSE);
    $this->db->from(
        "($sub_counter) tc"
    );
    $this->db->join(
        "($sub_target) tg",
        'tg.t_planning_uuid = tc.t_planning_uuid
         AND tg.mesin_uuid = tc.mesin_uuid',
        'left',
        FALSE
    );
    $this->db->group_by(
        'tc.mesin_uuid'
    );
    $performa = $this->db->get()->result();
    /*
    |--------------------------------------------------------------------------
    | DOWNTIME
    |--------------------------------------------------------------------------
    |
    | Total downtime berdasarkan mesin.
    |
    | Struktur:
    |
    | t_downtime
    |      ↓
    | t_speed
    |      ↓
    | t_planning
    |--------------------------------------------------------------------------
    */
    $this->db->select('
        s.mesin_uuid,
        SUM(td.downtime) AS total_downtime
    ', FALSE);
    $this->db->from(
        't_downtime td'
    );
    $this->db->join(
        't_speed s',
        's.uuid = td.t_speed_uuid'
    );
    $this->db->join(
        't_planning p',
        'p.uuid = s.t_planning_uuid'
    );
    $this->db->where('MONTH(p.tanggal)', $bulan);
    $this->db->where('YEAR(p.tanggal)', $tahun);
    $this->db->where(
        's.speed >',
        0
    );
    $this->db->where(
        'td.downtime >',
        0
    );
    $this->db->where(
        'p.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        's.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->group_by(
        's.mesin_uuid'
    );
    $downtime = $this->db->get()->result();
    /*
    |--------------------------------------------------------------------------
    | SUB DOWNTIME PER SPEED
    |--------------------------------------------------------------------------
    |
    | PENTING:
    |
    | Subquery ini dibuat SEBELUM query losttime dimulai.
    |
    | Ini untuk menghindari Query Builder CodeIgniter membawa state
    | dari query losttime ke dalam subquery.
    |--------------------------------------------------------------------------
    */
    $sub_downtime = $this->db
        ->select('
            td.t_speed_uuid,
            SUM(td.downtime) AS total_downtime
        ', FALSE)
        ->from('t_downtime td')
        ->where(
            'td.downtime >',
            0
        )
        ->group_by(
            'td.t_speed_uuid'
        )
        ->get_compiled_select();
    /*
    |--------------------------------------------------------------------------
    | LOST TIME
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | total waktu
    | - running time
    | - adjustment
    | - downtime
    |
    | Jika hasil negatif maka menjadi 0.
    |--------------------------------------------------------------------------
    */
    $this->db->select('
        s.mesin_uuid,
        SUM(
            GREATEST(
                (
                    (
                        TIMESTAMPDIFF(
                            SECOND,
                            p.start,
                            p.end
                        ) / 3600
                    )
                    -
                    (
                        COALESCE(
                            tc.total_counter,
                            0
                        )
                        / s.speed
                        / 60
                    )
                    -
                    (
                        (
                            TIMESTAMPDIFF(
                                SECOND,
                                p.start,
                                p.end
                            ) / 3600
                        )
                        * 10 / 60
                    )
                ) * 60
                -
                COALESCE(
                    td.total_downtime,
                    0
                ),
                0
            )
        ) AS total_losses
    ', FALSE);
    $this->db->from(
        't_speed s'
    );
    $this->db->join(
        't_planning p',
        'p.uuid = s.t_planning_uuid'
    );
    /*
    |--------------------------------------------------------------------------
    | COUNTER
    |--------------------------------------------------------------------------
    */
    $this->db->join(
        "($sub_counter) tc",
        'tc.t_planning_uuid = s.t_planning_uuid
         AND tc.mesin_uuid = s.mesin_uuid',
        'left',
        FALSE
    );
    /*
    |--------------------------------------------------------------------------
    | DOWNTIME PER SPEED
    |--------------------------------------------------------------------------
    */
    $this->db->join(
        "($sub_downtime) td",
        'td.t_speed_uuid = s.uuid',
        'left',
        FALSE
    );
    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */
    $this->db->where(
        'MONTH(p.tanggal)',
        $bulan
    );
    $this->db->where(
        'YEAR(p.tanggal)',
        $tahun
    );
    $this->db->where(
        's.speed >',
        0
    );
    $this->db->where(
        's.mesin_uuid IS NOT NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'p.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        's.deleted_at IS NULL',
        NULL,
        FALSE
    );
    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */
    $this->db->group_by(
        's.mesin_uuid'
    );
    $losttime = $this->db->get()->result();
    /*
    |--------------------------------------------------------------------------
    | INDEX DATA BERDASARKAN MESIN
    |--------------------------------------------------------------------------
    */
    $performa_map = [];
    foreach ($performa as $row) {
        $performa_map[$row->mesin_uuid] = $row;
    }
    $downtime_map = [];
    foreach ($downtime as $row) {
        $downtime_map[$row->mesin_uuid] = $row;
    }
    $losttime_map = [];
    foreach ($losttime as $row) {
        $losttime_map[$row->mesin_uuid] = $row;
    }
    /*
    |--------------------------------------------------------------------------
    | HASIL FINAL
    |--------------------------------------------------------------------------
    */
    foreach ($mesin as $row) {
        $p = $performa_map[$row->mesin_uuid] ?? null;
        $d = $downtime_map[$row->mesin_uuid] ?? null;
        $l = $losttime_map[$row->mesin_uuid] ?? null;
        /*
        |--------------------------------------------------------------------------
        | COUNTER
        |--------------------------------------------------------------------------
        */
        $total_counter = $p
            ? (float) $p->total_counter
            : 0;
        /*
        |--------------------------------------------------------------------------
        | TARGET
        |--------------------------------------------------------------------------
        */
        $total_target = $p
            ? (float) $p->total_target
            : 0;
        /*
        |--------------------------------------------------------------------------
        | SET COUNTER & TARGET
        |--------------------------------------------------------------------------
        */
        $row->counter = $total_counter;
        $row->target = $total_target;
        /*
        |--------------------------------------------------------------------------
        | PERFORMA
        |--------------------------------------------------------------------------
        */
        $row->performa = $total_target > 0
            ? ($total_counter / $total_target) * 100
            : 0;
        /*
        |--------------------------------------------------------------------------
        | DOWNTIME
        |--------------------------------------------------------------------------
        */
        $row->downtime = $d
            ? (float) $d->total_downtime
            : 0;
        /*
        |--------------------------------------------------------------------------
        | LOST TIME
        |--------------------------------------------------------------------------
        */
        $row->losttime = $l
            ? (float) $l->total_losses
            : 0;
    }
    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */
    return $mesin;
>>>>>>> 42d971f2ad2e1e41f3998fac19da7a82a2751028
}
}
