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
    /* =========================
             * tambahan untuk monitoring filkar
             * ========================= */
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
        $bulan = (int) date('m');
        $tahun = (int) date('Y');
        /*
         * Dashboard Sortasi tetap memakai bulan berjalan berdasarkan
         * waktu kegiatan Sortasi (s.created_at), tetapi sumber data
         * Release/WIP/Bad mengikuti struktur Sortasi terbaru.
         */
        $sortasi_sql = "(
            SELECT s.tbatch_uuid,
                   SUM(s.jumlah_wip) AS sortasi_box
            FROM sortasi s
            WHERE s.deleted_at IS NULL
              AND MONTH(s.created_at) = {$bulan}
              AND YEAR(s.created_at) = {$tahun}
            GROUP BY s.tbatch_uuid
        ) srt";
        $release_sql = "(
            SELECT s.tbatch_uuid,
                   SUM(so.jumlah) AS release_box
            FROM sortasi_output so
            INNER JOIN sortasi s ON s.uuid = so.sortasi_uuid
            WHERE so.jenis_output = 'RELEASE'
              AND so.deleted_at IS NULL
              AND s.deleted_at IS NULL
              AND MONTH(s.created_at) = {$bulan}
              AND YEAR(s.created_at) = {$tahun}
            GROUP BY s.tbatch_uuid
        ) rel";
        /* Bad dikaitkan ke transaksi Sortasi melalui ref_uuid agar
         * bad yang masuk pada transaksi bulan berjalan saja yang dihitung. */
        $bad_sql = "(
            SELECT s.tbatch_uuid,
                   SUM(CASE WHEN bp.kategori = 1 THEN tbp.berat ELSE 0 END) AS sortasi_rework,
                   SUM(CASE WHEN bp.kategori = 2 THEN tbp.berat ELSE 0 END) AS sortasi_reject,
                   SUM(tbp.berat) AS sortasi_bad
            FROM t_badpro tbp
            INNER JOIN sortasi s ON s.uuid = tbp.ref_uuid
            LEFT JOIN badpro bp ON bp.uuid = tbp.badpro_uuid
            WHERE tbp.proses_uuid = " . $this->db->escape($proses_uuid) . "
              AND tbp.deleted_at IS NULL
              AND s.deleted_at IS NULL
              AND MONTH(s.created_at) = {$bulan}
              AND YEAR(s.created_at) = {$tahun}
            GROUP BY s.tbatch_uuid
        ) bads";
        /* Sisa WIP adalah saldo ledger aktif saat dashboard dibuka.
         * Batch yang ditampilkan tetap dibatasi oleh aktivitas Sortasi bulan berjalan. */
        $wip_sql = "(
            SELECT sw.tbatch_uuid,
                   SUM(sw.jumlah_awal - sw.jumlah_terpakai) AS wip_box
            FROM sortasi_wip sw
            WHERE sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')
              AND sw.deleted_at IS NULL
            GROUP BY sw.tbatch_uuid
        ) wip";
        $this->db->select("v.varian AS nama_varian,
            v.box_kg AS berat_box,
            COALESCE(SUM(srt.sortasi_box), 0) AS sortasi_box,
            COALESCE(SUM(rel.release_box), 0) AS release_box,
            COALESCE(SUM(wip.wip_box), 0) AS blm_sortir,
            COALESCE(SUM(wip.wip_box * v.box_kg), 0) AS blm_sortir_kg,
            COALESCE(SUM(bads.sortasi_rework), 0) AS sortasi_rework,
            COALESCE(SUM(bads.sortasi_reject), 0) AS sortasi_reject,
            COALESCE(SUM(bads.sortasi_bad), 0) AS sortasi_bad,
            COALESCE(SUM(rel.release_box * v.box_kg), 0) AS release_kg");
        $this->db->from('tbatch tb');
        $this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'inner');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->join($sortasi_sql, 'srt.tbatch_uuid = tb.uuid', 'inner', FALSE);
        $this->db->join($release_sql, 'rel.tbatch_uuid = tb.uuid', 'left', FALSE);
        $this->db->join($bad_sql, 'bads.tbatch_uuid = tb.uuid', 'left', FALSE);
        $this->db->join($wip_sql, 'wip.tbatch_uuid = tb.uuid', 'left', FALSE);
        $this->db->where('tp.deleted_at IS NULL', NULL, FALSE);
        $this->db->where('tb.deleted_at IS NULL', NULL, FALSE);
        $this->db->group_by(['v.uuid', 'v.varian', 'v.box_kg']);
        $this->db->order_by('v.varian', 'ASC');
        $rows = $this->db->get()->result();
        foreach ($rows as $r) {
            $r->sortasi_kg = (float) $r->sortasi_box * (float) $r->berat_box;
            $r->bad_persen = $r->sortasi_kg > 0
                ? round(((float) $r->sortasi_bad / $r->sortasi_kg) * 100, 2)
                : 0;
            /* Yield terbaru:
             * Release Kg / (Release Kg + Bad Kg) x 100. */
            $denominator = (float) $r->release_kg + (float) $r->sortasi_bad;
            $r->yield_sortasi = $denominator > 0
                ? round(((float) $r->release_kg / $denominator) * 100, 2)
                : 0;
        }
        return $rows;
    }
    public function get_total_sortasi()
    {
        $data = $this->get_monitoring_sortasi();
        if (empty($data)) return null;
        $total = new stdClass();
        foreach (
            [
                'sortasi_box',
                'release_box',
                'blm_sortir',
                'blm_sortir_kg',
                'sortasi_rework',
                'sortasi_reject',
                'sortasi_bad',
                'sortasi_kg',
                'release_kg'
            ] as $k
        ) {
            $total->$k = 0;
        }
        foreach ($data as $row) {
            foreach (array_keys((array) $total) as $k) {
                $total->$k += (float) ($row->$k ?? 0);
            }
        }
        $total->bad_persen = $total->sortasi_kg > 0
            ? ($total->sortasi_bad / $total->sortasi_kg) * 100
            : 0;
        /* Rumus Yield terbaru harus sama dengan baris detail. */
        $denominator = $total->release_kg + $total->sortasi_bad;
        $total->yield_sortasi = $denominator > 0
            ? ($total->release_kg / $denominator) * 100
            : 0;
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
        $bulan = date('m');
        $tahun = date('Y');
        // Pilih master data varian
        $this->db->select('
        tp.varian AS uuid_varian,
        v.varian,
        mf.total AS formula,
        v.pvdc_batch,
        v.wire_batch
    ');
        // Subquery untuk PVDC & Wire (Dijumlahkan terpisah agar tidak duplikat)
        $this->db->select("
        (SELECT COALESCE(SUM(pw.pvdc), 0)
         FROM pvdc_wire pw
         JOIN t_planning tp2 ON pw.t_planning_uuid = tp2.uuid
         WHERE tp2.varian = tp.varian
         AND MONTH(tp2.tanggal) = '$bulan'
         AND YEAR(tp2.tanggal) = '$tahun'
         AND tp2.deleted_at IS NULL) AS pvdc
    ");
        $this->db->select("
        (SELECT COALESCE(SUM(pw.wire), 0)
         FROM pvdc_wire pw
         JOIN t_planning tp2 ON pw.t_planning_uuid = tp2.uuid
         WHERE tp2.varian = tp.varian
         AND MONTH(tp2.tanggal) = '$bulan'
         AND YEAR(tp2.tanggal) = '$tahun'
         AND tp2.deleted_at IS NULL) AS wire
    ");
        // Subquery untuk MP Usage (Dijumlahkan terpisah)
        $this->db->select("
        (SELECT COALESCE(SUM(m.batch_persen), 0)
         FROM mp_usage m
         JOIN t_planning tp3 ON m.t_planning_uuid = tp3.uuid
         WHERE tp3.varian = tp.varian
         AND MONTH(tp3.tanggal) = '$bulan'
         AND YEAR(tp3.tanggal) = '$tahun'
         AND tp3.deleted_at IS NULL) AS jumlah_batch
    ");
        $this->db->select("
        (SELECT COALESCE(SUM(m.formula_kg + m.rework_kg), 0)
         FROM mp_usage m
         JOIN t_planning tp3 ON m.t_planning_uuid = tp3.uuid
         WHERE tp3.varian = tp.varian
         AND MONTH(tp3.tanggal) = '$bulan'
         AND YEAR(tp3.tanggal) = '$tahun'
         AND tp3.deleted_at IS NULL) AS bahan_baku
    ");
        $this->db->from('t_planning tp');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->join('m_formula mf', 'mf.varian_uuid = tp.varian', 'left');
        // Filter Bulan & Tahun Berjalan
        $this->db->where('MONTH(tp.tanggal)', $bulan);
        $this->db->where('YEAR(tp.tanggal)', $tahun);
        $this->db->where('tp.deleted_at IS NULL', NULL, FALSE);
        // Group By cukup pada master varian saja
        $this->db->group_by('tp.varian, v.varian, mf.total, v.pvdc_batch, v.wire_batch');
        $data = $this->db->get()->result();
        // Kalkulasi logic persentase
        foreach ($data as $val) {
            if ((float) $val->jumlah_batch > 0 && (float) $val->formula > 0) {
                // Hitung Actual Batch (Sama untuk PVDC dan Wire)
                $val->actual_batch = $val->bahan_baku / $val->formula;
                // Kalkulasi PVDC
                $val->onproduk_pvdc = $val->actual_batch * $val->pvdc_batch;
                $val->reject_pvdc = $val->pvdc - $val->onproduk_pvdc;
                $val->reject_pvdc_persen = $val->pvdc > 0 ? round(($val->reject_pvdc / $val->pvdc) * 100, 2) : 0;
                // Kalkulasi WIRE
                $val->onproduk_wire = $val->actual_batch * $val->wire_batch;
                $val->reject_wire = $val->wire - $val->onproduk_wire;
                $val->reject_wire_persen = $val->wire > 0 ? round(($val->reject_wire / $val->wire) * 100, 2) : 0;
            } else {
                $val->actual_batch = 0;
                // Default PVDC
                $val->onproduk_pvdc = 0;
                $val->reject_pvdc = 0;
                $val->reject_pvdc_persen = 0;
                // Default WIRE
                $val->onproduk_wire = 0;
                $val->reject_wire = 0;
                $val->reject_wire_persen = 0;
            }
        }
        return $data;
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
    }
}
