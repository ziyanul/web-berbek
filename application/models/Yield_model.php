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
        // Master Bad Produk
        $badproduk = $this->get_master_bad_produk();
        $select = "
        m.uuid,
        MAX(m.nama_mesin) AS mesin,
    ";
        foreach ($badproduk as $bp) {
            $uuid = $this->db->escape_str($bp->uuid);
            $select .= "
            SUM(
                CASE
                    WHEN tbp.badpro_uuid = '{$uuid}'
                    THEN tbp.berat
                    ELSE 0
                END
            ) AS `{$bp->uuid}`,
        ";
        }
        $select .= "
        COALESCE(SUM(tbp.berat),0) AS total
    ";
        $this->db->select($select, FALSE);
        $this->db->from('t_badpro tbp');
        $this->db->join(
            'badpro bp',
            'bp.uuid = tbp.badpro_uuid',
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
        $this->db->join(
            'mesin m',
            'm.uuid = tbp.mesin_uuid',
            'left'
        );
        // =========================
        // Hanya Bad Produk Sortasi
        // =========================
        $this->db->where(
            "tbp.mesin_uuid <> ''",
            NULL,
            FALSE
        );
        // Filter tanggal + varian
        $this->apply_filter_analisa($filter);
        // Filter mesin
        if (!empty($filter['mesin'])) {
            $this->db->where(
                'tbp.mesin_uuid',
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
        // data aktif saja
        $this->db->where(
            'tbp.deleted_at IS NULL'
        );
        $this->db->group_by(
            'm.uuid'
        );
        $this->db->order_by(
            'MAX(m.nama_mesin)',
            'ASC',
            FALSE
        );
        return [
            'badproduk' => $badproduk,
            'rows'      => $this->db->get()->result()
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
            v.varian AS nama_varian,

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

                /* Adonan aktual batch */
                MAX(tb.adonan) AS adonan,

                /* Filkar aktual batch */
                MAX(tb.filkar_box) AS filkar_box,
                MAX(tb.filkar_kg) AS filkar_kg,

                /* Bad Rework */
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
            v.varian

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

        /* =========================
           SORTASI BOX
        ========================= */

        SUM(s.jumlah_wip) AS sortasi_box,

        SUM(s.jml_release) AS release_box,

        (
            SUM(s.jumlah_wip)
            -
            SUM(s.jml_release)
        ) AS blm_sortir,


        /* =========================
           BAD PRODUK SORTASI
        ========================= */

        COALESCE(
            SUM(
                CASE
                    WHEN bp.kategori = 1
                    THEN tbp.berat
                    ELSE 0
                END
            ),0
        ) AS sortasi_rework,


        COALESCE(
            SUM(
                CASE
                    WHEN bp.kategori = 2
                    THEN tbp.berat
                    ELSE 0
                END
            ),0
        ) AS sortasi_reject,


        COALESCE(
            SUM(tbp.berat),
            0
        ) AS sortasi_bad,


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
        $this->db->join(
            'varian v',
            'v.uuid = tp.varian',
            'left'
        );


        // bad produk sortasi
        $this->db->join(
            't_badpro tbp',
            "
        tbp.ref_uuid = s.uuid
        AND tbp.proses_uuid = '$proses_uuid'
        AND tbp.deleted_at IS NULL
        ",
            'left'
        );


        $this->db->join(
            'badpro bp',
            'bp.uuid = tbp.badpro_uuid',
            'left'
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
            'v.varian'
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


        foreach ($data as $row) {

            $total->sortasi_box += $row->sortasi_box;

            $total->release_box += $row->release_box;

            $total->blm_sortir += $row->blm_sortir;

            $total->sortasi_rework += $row->sortasi_rework;

            $total->sortasi_reject += $row->sortasi_reject;

            $total->sortasi_bad += $row->sortasi_bad;
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


        $this->db->select("
        m.uuid AS mesin_uuid,
        m.nama_mesin AS mesin,

        bp.uuid AS badpro_uuid,
        bp.nama_badpro,

        SUM(
            tbp.berat *
            (
                tc.counter / tb.total
            )
        ) AS berat


    ");


        $this->db->from('t_badpro tbp');


        $this->db->join(
            'sortasi s',
            's.uuid = tbp.ref_uuid',
            'inner'
        );


        $this->db->join(
            'tbatch tb',
            'tb.uuid = s.tbatch_uuid',
            'inner'
        );


        $this->db->join(
            'tcounter tc',
            'tc.tbatch_uuid = tb.uuid',
            'inner'
        );


        $this->db->join(
            'mesin m',
            'm.uuid = tc.mesin_uuid',
            'left'
        );


        $this->db->join(
            'badpro bp',
            'bp.uuid = tbp.badpro_uuid',
            'left'
        );


        $this->db->where(
            'tbp.proses_uuid',
            $proses_uuid
        );


        $this->db->where(
            'tbp.deleted_at',
            NULL
        );


        $this->db->where(
            's.deleted_at',
            NULL
        );


        $this->db->where(
            'MONTH(s.created_at)',
            date('m')
        );


        $this->db->where(
            'YEAR(s.created_at)',
            date('Y')
        );


        $this->db->group_by([
            'm.uuid',
            'm.nama_mesin',
            'bp.uuid',
            'bp.nama_badpro'
        ]);


        return $this->db->get()->result();
    }
}
