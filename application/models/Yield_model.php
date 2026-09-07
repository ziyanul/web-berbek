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
    private function normalize_analisa_filter($filter = [])
    {
        return [
            'tanggal_awal'  => !empty($filter['tanggal_awal']) ? $filter['tanggal_awal'] : null,
            'tanggal_akhir' => !empty($filter['tanggal_akhir']) ? $filter['tanggal_akhir'] : null,
            'plan'          => !empty($filter['plan']) ? $filter['plan'] : null,
            'batch'         => !empty($filter['batch']) ? $filter['batch'] : null,
            'varian'        => !empty($filter['varian']) ? $filter['varian'] : null,
            'mesin'         => !empty($filter['mesin']) ? $filter['mesin'] : null,
            'badpro'        => !empty($filter['badpro']) ? $filter['badpro'] : null,
        ];
    }
    /**
     * Semua analisa menggunakan scope batch yang sama.
     * Filter mesin = batch yang pernah berhubungan dengan mesin melalui counter
     * atau bad product. Filter bad product = batch yang memiliki bad product tersebut.
     */
    private function apply_analisa_scope($filter, $alias = 'b', $exclude = '')
    {
        $filter = $this->normalize_analisa_filter($filter);
        if ($exclude !== 'tanggal' && !empty($filter['tanggal_awal'])) {
            $this->db->where('p.tanggal >=', $filter['tanggal_awal']);
        }
        if ($exclude !== 'tanggal' && !empty($filter['tanggal_akhir'])) {
            $this->db->where('p.tanggal <=', $filter['tanggal_akhir']);
        }
        if ($exclude !== 'plan' && !empty($filter['plan'])) {
            $this->db->where($alias . '.t_planning_uuid', $filter['plan']);
        }
        if ($exclude !== 'batch' && !empty($filter['batch'])) {
            $this->db->where($alias . '.uuid', $filter['batch']);
        }
        if ($exclude !== 'varian' && !empty($filter['varian'])) {
            $this->db->where('p.varian', $filter['varian']);
        }
        if ($exclude !== 'mesin' && !empty($filter['mesin'])) {
            $mesin = $this->db->escape($filter['mesin']);
            $this->db->where("EXISTS (
                SELECT 1 FROM tcounter tc
                WHERE tc.tbatch_uuid = {$alias}.uuid
                  AND tc.mesin_uuid = {$mesin}
                  AND tc.deleted_at IS NULL
            ) OR EXISTS (
                SELECT 1
                FROM t_badpro tbpm
                LEFT JOIN t_badpro_mesin tbpmx ON tbpmx.t_badpro_uuid = tbpm.uuid
                    AND tbpmx.deleted_at IS NULL
                WHERE tbpm.tbatch_uuid = {$alias}.uuid
                  AND tbpm.deleted_at IS NULL
                  AND (tbpm.mesin_uuid = {$mesin} OR tbpmx.mesin_uuid = {$mesin})
            )", NULL, FALSE);
        }
        if ($exclude !== 'badpro' && !empty($filter['badpro'])) {
            $badpro = $this->db->escape($filter['badpro']);
            $this->db->where("EXISTS (
                SELECT 1 FROM t_badpro tbpb
                WHERE tbpb.tbatch_uuid = {$alias}.uuid
                  AND tbpb.badpro_uuid = {$badpro}
                  AND tbpb.deleted_at IS NULL
            )", NULL, FALSE);
        }
    }
    private function apply_active_batch($alias = 'b')
    {
        $this->db->where($alias . '.deleted_at IS NULL', NULL, FALSE);
        $this->db->where('p.deleted_at IS NULL', NULL, FALSE);
    }
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
        $this->db->select('m.uuid, m.nama_mesin');
        $this->db->from('mesin m');
        $this->db->where('m.deleted_at IS NULL', NULL, FALSE);
        $this->db->where("(
            EXISTS (SELECT 1 FROM tcounter tc WHERE tc.mesin_uuid = m.uuid AND tc.deleted_at IS NULL)
            OR EXISTS (SELECT 1 FROM t_badpro tbp WHERE tbp.mesin_uuid = m.uuid AND tbp.deleted_at IS NULL)
            OR EXISTS (SELECT 1 FROM t_badpro_mesin tbpm WHERE tbpm.mesin_uuid = m.uuid AND tbpm.deleted_at IS NULL)
        )", NULL, FALSE);
        $this->db->order_by('m.nama_mesin', 'ASC');
        return $this->db->get()->result();
    }
    /**
     * Dropdown cascading. Setiap pilihan dihitung dari pilihan filter lainnya.
     * Batch dikembalikan sudah dikelompokkan berdasarkan Plan Produksi.
     */
    public function get_analisa_filter_options($filter = [])
    {
        $filter = $this->normalize_analisa_filter($filter);
        // PLAN + BATCH GROUP
        $this->db->select('p.uuid AS plan_uuid, p.tanggal, p.plan, p.varian AS plan_varian_uuid, v.varian, b.uuid AS batch_uuid, b.kode_batch');
        $this->db->from('tbatch b');
        $this->db->join('t_planning p', 'p.uuid = b.t_planning_uuid', 'inner');
        $this->db->join('varian v', 'v.uuid = p.varian', 'left');
        $this->apply_analisa_scope($filter, 'b', 'batch');
        $this->apply_active_batch('b');
        $this->db->group_by(['p.uuid', 'p.tanggal', 'p.plan', 'p.varian', 'v.varian', 'b.uuid', 'b.kode_batch']);
        $this->db->order_by('p.tanggal', 'DESC');
        $this->db->order_by('p.plan', 'ASC');
        $this->db->order_by('b.kode_batch', 'ASC');
        $batch_rows = $this->db->get()->result();
        $plans = [];
        foreach ($batch_rows as $r) {
            if (!isset($plans[$r->plan_uuid])) {
                $label = date('d-m-Y', strtotime($r->tanggal)) . ' | Plan ' . (int)$r->plan;
                if (!empty($r->varian)) $label .= ' | ' . $r->varian;
                $plans[$r->plan_uuid] = [
                    'uuid' => $r->plan_uuid,
                    'tanggal' => $r->tanggal,
                    'plan' => $r->plan,
                    'varian' => $r->varian,
                    'label' => $label,
                    'batches' => []
                ];
            }
            $plans[$r->plan_uuid]['batches'][] = [
                'uuid' => $r->batch_uuid,
                'kode_batch' => $r->kode_batch
            ];
        }
        // VARIAN
        $this->db->select('v.uuid, v.varian');
        $this->db->from('tbatch b');
        $this->db->join('t_planning p', 'p.uuid=b.t_planning_uuid', 'inner');
        $this->db->join('varian v', 'v.uuid=p.varian', 'left');
        $this->apply_analisa_scope($filter, 'b', 'varian');
        $this->apply_active_batch('b');
        $this->db->where('v.deleted_at IS NULL', NULL, FALSE);
        $this->db->group_by(['v.uuid', 'v.varian']);
        $this->db->order_by('v.varian', 'ASC');
        $varians = $this->db->get()->result();
        // MESIN: hanya mesin yang berhubungan dengan batch dalam scope.
        $this->db->select('m.uuid, m.nama_mesin');
        $this->db->from('tbatch b');
        $this->db->join('t_planning p', 'p.uuid=b.t_planning_uuid', 'inner');
        $this->db->join('mesin m', 'm.uuid IS NOT NULL', 'inner', FALSE);
        $this->apply_analisa_scope($filter, 'b', 'mesin');
        $this->apply_active_batch('b');
        $this->db->where("(
            EXISTS (SELECT 1 FROM tcounter tc WHERE tc.tbatch_uuid=b.uuid AND tc.mesin_uuid=m.uuid AND tc.deleted_at IS NULL)
            OR EXISTS (SELECT 1 FROM t_badpro tbp WHERE tbp.tbatch_uuid=b.uuid AND tbp.mesin_uuid=m.uuid AND tbp.deleted_at IS NULL)
            OR EXISTS (
                SELECT 1 FROM t_badpro_mesin tbpm
                INNER JOIN t_badpro tbpx ON tbpx.uuid=tbpm.t_badpro_uuid AND tbpx.deleted_at IS NULL
                WHERE tbpx.tbatch_uuid=b.uuid AND tbpm.mesin_uuid=m.uuid AND tbpm.deleted_at IS NULL
            )
        )", NULL, FALSE);
        $this->db->group_by(['m.uuid', 'm.nama_mesin']);
        $this->db->order_by('m.nama_mesin', 'ASC');
        $mesins = $this->db->get()->result();
        // BAD PRODUK
        $this->db->select('bp.uuid, bp.nama_badpro, bp.urutan');
        $this->db->from('t_badpro tbp');
        $this->db->join('badpro bp', 'bp.uuid=tbp.badpro_uuid', 'inner');
        $this->db->join('tbatch b', 'b.uuid=tbp.tbatch_uuid', 'inner');
        $this->db->join('t_planning p', 'p.uuid=b.t_planning_uuid', 'inner');
        $this->apply_analisa_scope($filter, 'b', 'badpro');
        $this->db->where('tbp.deleted_at IS NULL', NULL, FALSE);
        $this->db->where('bp.deleted_at IS NULL', NULL, FALSE);
        $this->db->group_by(['bp.uuid', 'bp.nama_badpro', 'bp.urutan']);
        $this->db->order_by('bp.urutan', 'ASC');
        $badpro = $this->db->get()->result();
        return [
            'plans' => array_values($plans),
            'varian' => $varians,
            'mesin' => $mesins,
            'badpro' => $badpro
        ];
    }
    public function get_monitoring_analisa($filter)
    {
        $sortasi_proses = $this->Proses_model->get_uuid('SORTASI');
        $bad_sortasi_sql = "(
            SELECT tbp.tbatch_uuid,
                   SUM(CASE WHEN bp.kategori=1 THEN tbp.berat ELSE 0 END) AS rework,
                   SUM(CASE WHEN bp.kategori=2 THEN tbp.berat ELSE 0 END) AS reject,
                   SUM(tbp.berat) AS bad_total
            FROM t_badpro tbp
            LEFT JOIN badpro bp ON bp.uuid=tbp.badpro_uuid
            WHERE tbp.proses_uuid=" . $this->db->escape($sortasi_proses) . "
              AND tbp.deleted_at IS NULL
            GROUP BY tbp.tbatch_uuid
        ) bads";
        $release_sql = "(
            SELECT s.tbatch_uuid, SUM(so.jumlah) AS release_box
            FROM sortasi_output so
            INNER JOIN sortasi s ON s.uuid=so.sortasi_uuid
            WHERE so.jenis_output='RELEASE'
              AND so.deleted_at IS NULL
              AND s.deleted_at IS NULL
            GROUP BY s.tbatch_uuid
        ) rel";
        $wip_sql = "(
            SELECT sw.tbatch_uuid, SUM(sw.jumlah_awal-sw.jumlah_terpakai) AS wip_box
            FROM sortasi_wip sw
            WHERE sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')
              AND sw.deleted_at IS NULL
            GROUP BY sw.tbatch_uuid
        ) wip";
        $this->db->select("v.varian AS nama_varian,
            COALESCE(MAX(v.box_kg),0) AS box_kg,
            COALESCE(SUM(b.adonan),0) AS adonan_formula,
            COALESCE(SUM(b.filkar_box),0) AS filkar_box,
            COALESCE(SUM(b.filkar_kg),0) AS filkar_kg,
            COALESCE(SUM(srt.total_sortasi),0) AS sortasi_box,
            COALESCE(SUM(rel.release_box),0) AS release_box,
            COALESCE(SUM(rel.release_box * v.box_kg),0) AS release_kg,
            COALESCE(SUM(wip.wip_box),0) AS blm_sortir,
            COALESCE(SUM(b.bad_filkar_rework_kg),0) AS filkar_rework,
            COALESCE(SUM(b.bad_filkar_reject_kg),0) AS filkar_reject,
            COALESCE(SUM(bads.rework),0) AS sortasi_rework,
            COALESCE(SUM(bads.reject),0) AS sortasi_reject,
            COALESCE(SUM(bads.bad_total),0) AS sortasi_bad");
        $this->db->from('tbatch b');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','left');
        $this->db->join('varian v','v.uuid=p.varian','left');
        $this->db->join("(
            SELECT tbatch_uuid, SUM(jumlah_wip) AS total_sortasi
            FROM sortasi WHERE deleted_at IS NULL GROUP BY tbatch_uuid
        ) srt", 'srt.tbatch_uuid=b.uuid', 'left', FALSE);
        $this->db->join($release_sql, 'rel.tbatch_uuid=b.uuid', 'left', FALSE);
        $this->db->join($wip_sql, 'wip.tbatch_uuid=b.uuid', 'left', FALSE);
        $this->db->join($bad_sortasi_sql, 'bads.tbatch_uuid=b.uuid', 'left', FALSE);
        $this->apply_analisa_scope($filter, 'b');
        $this->apply_active_batch('b');
        $this->db->group_by(['p.varian','v.varian']);
        $this->db->order_by('nama_varian','ASC');
        $rows=$this->db->get()->result();
        $total=(object)[
            'adonan_formula'=>0,'filkar_box'=>0,'filkar_kg'=>0,'sortasi_box'=>0,'release_box'=>0,'release_kg'=>0,
            'blm_sortir'=>0,'filkar_rework'=>0,'filkar_reject'=>0,'sortasi_rework'=>0,'sortasi_reject'=>0,'sortasi_bad'=>0,
            'yield_formula'=>0,'yield_release'=>0
        ];
        foreach($rows as $r){
            $r->yield_formula=$r->adonan_formula>0?round(($r->filkar_kg/$r->adonan_formula)*100,2):0;
            $bad=(float)($r->sortasi_bad ?? 0);
            $release_kg=(float)$r->release_kg;
            $r->yield_release=($release_kg+$bad)>0?round(($release_kg/($release_kg+$bad))*100,2):0;
            foreach(['adonan_formula','filkar_box','filkar_kg','sortasi_box','release_box','blm_sortir','filkar_rework','filkar_reject','sortasi_rework','sortasi_reject','sortasi_bad'] as $k)$total->$k+=(float)$r->$k;
        }
        $total->yield_formula=$total->adonan_formula>0?round(($total->filkar_kg/$total->adonan_formula)*100,2):0;
        $bad=(float)($total->sortasi_bad ?? 0);
        $total->yield_release=($total->release_kg+$bad)>0?round(($total->release_kg/($total->release_kg+$bad))*100,2):0;
        return ['rows'=>$rows,'total'=>$total];
    }
    public function get_ringkasan_analisa($filter)
    {
        $m=$this->get_monitoring_analisa($filter);
        if(empty($m['rows'])) return null;
        $t=$m['total'];
        $t->total_batch=count($this->get_scope_batch_uuids($filter));
        return $t;
    }
    private function get_scope_batch_uuids($filter)
    {
        $this->db->select('b.uuid');
        $this->db->from('tbatch b');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','inner');
        $this->apply_analisa_scope($filter,'b');
        $this->apply_active_batch('b');
        return array_column($this->db->get()->result_array(),'uuid');
    }
    public function get_scope_summary($filter)
    {
        $filter=$this->normalize_analisa_filter($filter);
        $ids=$this->get_scope_batch_uuids($filter);
        $parts=[];
        if($filter['tanggal_awal']||$filter['tanggal_akhir']){
            $parts[]='Tanggal: '.($filter['tanggal_awal']?:'awal').' s/d '.($filter['tanggal_akhir']?:'akhir');
        }
        if($filter['plan']){
            $r=$this->db->select('tanggal,plan')->where('uuid',$filter['plan'])->get('t_planning')->row();
            if($r)$parts[]='Plan '.$r->plan.' ('.date('d-m-Y',strtotime($r->tanggal)).')';
        }
        if($filter['batch']){
            $r=$this->db->select('kode_batch')->where('uuid',$filter['batch'])->get('tbatch')->row();
            if($r)$parts[]='Batch: '.$r->kode_batch;
        }
        if($filter['varian']){
            $r=$this->db->select('varian')->where('uuid',$filter['varian'])->get('varian')->row();
            if($r)$parts[]='Varian: '.$r->varian;
        }
        if($filter['mesin']){
            $r=$this->db->select('nama_mesin')->where('uuid',$filter['mesin'])->get('mesin')->row();
            if($r)$parts[]='Mesin: '.$r->nama_mesin;
        }
        if($filter['badpro']){
            $r=$this->db->select('nama_badpro')->where('uuid',$filter['badpro'])->get('badpro')->row();
            if($r)$parts[]='Bad: '.$r->nama_badpro;
        }
        $has_bad=false;
        if(!empty($ids)){
            $this->db->where_in('tbatch_uuid',$ids)->where('deleted_at IS NULL',NULL,FALSE);
            $has_bad=$this->db->count_all_results('t_badpro')>0;
        }
        return [
            'total_batch'=>count($ids),
            'has_badpro'=>$has_bad,
            'label'=>empty($parts)?'Semua data':' '.implode(' | ',$parts).' | '.$this->count_label(count($ids)).''
        ];
    }
    private function count_label($n)
    {
        return number_format($n).' Batch';
    }
    private function get_scope_varians($filter)
    {
        $this->db->select('v.uuid,v.varian');
        $this->db->from('tbatch b');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','inner');
        $this->db->join('varian v','v.uuid=p.varian','left');
        $this->apply_analisa_scope($filter,'b');
        $this->apply_active_batch('b');
        $this->db->group_by(['v.uuid','v.varian']);
        $this->db->order_by('v.varian','ASC');
        return $this->db->get()->result();
    }
    public function get_bad_produk_varian_analisa($filter)
    {
        $varian=$this->get_scope_varians($filter);
        if(empty($varian)) return ['varian'=>[],'rows'=>[]];
        $select="bp.uuid, MAX(bp.nama_badpro) AS nama_badpro, MAX(bp.kategori) AS kategori, MAX(pr.nama_proses) AS proses,";
        foreach($varian as $v){
            $uuid=$this->db->escape_str($v->uuid);
            $select.="SUM(CASE WHEN p.varian='{$uuid}' THEN tbp.berat ELSE 0 END) AS `{$v->uuid}`,";
        }
        $select.="SUM(tbp.berat) AS total";
        $this->db->select($select,FALSE);
        $this->db->from('t_badpro tbp');
        $this->db->join('badpro bp','bp.uuid=tbp.badpro_uuid','left');
        $this->db->join('m_proses pr','pr.uuid=tbp.proses_uuid','left');
        $this->db->join('tbatch b','b.uuid=tbp.tbatch_uuid','inner');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','inner');
        $this->apply_analisa_scope($filter,'b');
        $this->db->where('tbp.deleted_at IS NULL',NULL,FALSE);
        $this->db->where('bp.deleted_at IS NULL',NULL,FALSE);
        $this->db->group_by(['bp.uuid','bp.nama_badpro']);
        $this->db->order_by('MAX(bp.urutan)','ASC',FALSE);
        return ['varian'=>$varian,'rows'=>$this->db->get()->result()];
    }
    public function get_bad_produk_mesin_analisa($filter = [])
{
    $filter = $this->normalize_analisa_filter($filter);
    /*
     * Ambil bad product yang masuk scope analisa.
     */
    $badpro_options = $this->get_analisa_filter_options($filter);
    $badpros = isset($badpro_options['badpro'])
        ? $badpro_options['badpro']
        : [];
    $mesins = isset($badpro_options['mesin'])
        ? $badpro_options['mesin']
        : [];
    if (empty($badpros) || empty($mesins)) {
        return [
            'columns' => [],
            'rows' => [],
            'total' => 0
        ];
    }
    /*
     * Ambil UUID badpro.
     */
    $badpro_uuids = [];
    foreach ($badpros as $row) {
        $uuid = '';
        if (is_object($row)) {
            $uuid = isset($row->uuid) ? $row->uuid : '';
        } elseif (is_array($row)) {
            $uuid = isset($row['uuid']) ? $row['uuid'] : '';
        }
        if ($uuid !== '') {
            $badpro_uuids[] = $uuid;
        }
    }
    /*
     * Ambil UUID mesin.
     */
    $mesin_uuids = [];
    foreach ($mesins as $row) {
        $uuid = '';
        if (is_object($row)) {
            $uuid = isset($row->uuid) ? $row->uuid : '';
        } elseif (is_array($row)) {
            $uuid = isset($row['uuid']) ? $row['uuid'] : '';
        }
        if ($uuid !== '') {
            $mesin_uuids[] = $uuid;
        }
    }
    $badpro_uuids = array_values(array_unique($badpro_uuids));
    $mesin_uuids  = array_values(array_unique($mesin_uuids));
    if (empty($badpro_uuids) || empty($mesin_uuids)) {
        return [
            'columns' => [],
            'rows' => [],
            'total' => 0
        ];
    }
    /*
     * ---------------------------------------------------------
     * BADPRO -> MESIN
     *
     * Satu bad product bisa mempunyai beberapa mesin.
     * Berat bad product dibagi rata ke seluruh mesin yang
     * terhubung dengan bad product tersebut.
     *
     * Relasi:
     * t_badpro.uuid
     *      -> t_badpro_mesin.t_badpro_uuid
     *
     * Jika t_badpro.mesin_uuid terisi tetapi belum ada
     * record di t_badpro_mesin, tetap dianggap sebagai
     * mesin bad product tersebut.
     * ---------------------------------------------------------
     */
    $badpro_sql = $this->db->escape($badpro_uuids);
    $mesin_sql  = $this->db->escape($mesin_uuids);
    /*
     * Daftar hubungan badpro -> mesin.
     *
     * Bagian pertama mengambil relasi dari t_badpro_mesin.
     * Bagian kedua mengambil mesin_uuid langsung dari t_badpro.
     *
     * DISTINCT mencegah duplikasi relasi.
     */
    $link_sql = "
        SELECT DISTINCT
            tbp.uuid AS bad_uuid,
            tbp.tbatch_uuid,
            tbp.badpro_uuid,
            tbp.berat,
            tbpm.mesin_uuid
        FROM t_badpro tbp
        INNER JOIN t_badpro_mesin tbpm
            ON tbpm.t_badpro_uuid = tbp.uuid
            AND tbpm.deleted_at IS NULL
        WHERE tbp.deleted_at IS NULL
          AND tbp.badpro_uuid IN (" . implode(',', array_map([$this->db, 'escape'], $badpro_uuids)) . ")
        UNION
        SELECT DISTINCT
            tbp.uuid AS bad_uuid,
            tbp.tbatch_uuid,
            tbp.badpro_uuid,
            tbp.berat,
            tbp.mesin_uuid
        FROM t_badpro tbp
        WHERE tbp.deleted_at IS NULL
          AND tbp.mesin_uuid IS NOT NULL
          AND tbp.mesin_uuid <> ''
          AND tbp.badpro_uuid IN (" . implode(',', array_map([$this->db, 'escape'], $badpro_uuids)) . ")
    ";
    /*
     * Hitung jumlah mesin per bad product.
     */
    $machine_count_sql = "
        SELECT
            z.bad_uuid,
            COUNT(DISTINCT z.mesin_uuid) AS machine_count
        FROM (
            SELECT DISTINCT
                tbp.uuid AS bad_uuid,
                tbpm.mesin_uuid
            FROM t_badpro tbp
            INNER JOIN t_badpro_mesin tbpm
                ON tbpm.t_badpro_uuid = tbp.uuid
                AND tbpm.deleted_at IS NULL
            WHERE tbp.deleted_at IS NULL
              AND tbp.badpro_uuid IN (" . implode(',', array_map([$this->db, 'escape'], $badpro_uuids)) . ")
            UNION
            SELECT DISTINCT
                tbp.uuid AS bad_uuid,
                tbp.mesin_uuid
            FROM t_badpro tbp
            WHERE tbp.deleted_at IS NULL
              AND tbp.mesin_uuid IS NOT NULL
              AND tbp.mesin_uuid <> ''
              AND tbp.badpro_uuid IN (" . implode(',', array_map([$this->db, 'escape'], $badpro_uuids)) . ")
        ) z
        GROUP BY z.bad_uuid
    ";
    /*
     * ---------------------------------------------------------
     * Dynamic kolom per Bad Produk
     * ---------------------------------------------------------
     */
    $select = [
        'm.uuid AS mesin_uuid',
        'MAX(m.nama_mesin) AS mesin'
    ];
    foreach ($badpro_uuids as $badpro_uuid) {
        $alias = $this->db->escape_str($badpro_uuid);
        $select[] = "
            COALESCE(
                SUM(
                    CASE
                        WHEN linkbad.badpro_uuid = " . $this->db->escape($badpro_uuid) . "
                        THEN linkbad.berat / NULLIF(linkbad.machine_count, 0)
                        ELSE 0
                    END
                ),
                0
            ) AS `" . $alias . "`
        ";
    }
    /*
     * Total semua bad product.
     */
    $select[] = "
        COALESCE(
            SUM(
                linkbad.berat / NULLIF(linkbad.machine_count, 0)
            ),
            0
        ) AS total
    ";
    /*
     * ---------------------------------------------------------
     * Query utama
     * ---------------------------------------------------------
     *
     * Sengaja menggunakan query SQL langsung, bukan
     * Query Builder untuk derived table UNION, karena
     * Query Builder CI sebelumnya meng-escape bagian UNION
     * sehingga menjadi:
     *
     * `mesin_uuid FROM t_badpro_mesin ...
     *
     * yang menyebabkan HTTP 500.
     * ---------------------------------------------------------
     */
    $sql = "
        SELECT
            " . implode(",\n", $select) . "
        FROM (
            SELECT
                l.bad_uuid,
                l.tbatch_uuid,
                l.badpro_uuid,
                l.berat,
                l.mesin_uuid,
                mc.machine_count
            FROM (
                " . $link_sql . "
            ) l
            INNER JOIN (
                " . $machine_count_sql . "
            ) mc
                ON mc.bad_uuid = l.bad_uuid
        ) linkbad
        INNER JOIN tbatch b
            ON b.uuid = linkbad.tbatch_uuid
        INNER JOIN t_planning p
            ON p.uuid = b.t_planning_uuid
        INNER JOIN badpro bp
            ON bp.uuid = linkbad.badpro_uuid
        INNER JOIN mesin m
            ON m.uuid = linkbad.mesin_uuid
        WHERE p.tanggal >= " . $this->db->escape($filter['date_from']) . "
          AND p.tanggal <= " . $this->db->escape($filter['date_to']) . "
    ";
    /*
     * Scope plan.
     */
    if (!empty($filter['plan'])) {
        $sql .= "
            AND b.t_planning_uuid = "
            . $this->db->escape($filter['plan']);
    }
    /*
     * Scope batch.
     */
    if (!empty($filter['batch'])) {
        $sql .= "
            AND b.uuid = "
            . $this->db->escape($filter['batch']);
    }
    /*
     * Scope varian.
     * Relasi yang benar:
     * t_planning.varian = varian.uuid
     */
    if (!empty($filter['varian'])) {
        $sql .= "
            AND p.varian = "
            . $this->db->escape($filter['varian']);
    }
    /*
     * Scope mesin.
     */
    if (!empty($filter['mesin'])) {
        $sql .= "
            AND m.uuid = "
            . $this->db->escape($filter['mesin']);
    }
    /*
     * Scope bad product.
     */
    if (!empty($filter['badpro'])) {
        $sql .= "
            AND linkbad.badpro_uuid = "
            . $this->db->escape($filter['badpro']);
    }
    /*
     * Batch aktif.
     */
    $sql .= "
        AND b.deleted_at IS NULL
        AND p.deleted_at IS NULL
    ";
    $sql .= "
        GROUP BY m.uuid
        ORDER BY MAX(m.nama_mesin) ASC
    ";
    $query = $this->db->query($sql);
    if ($query === false) {
        return [
            'columns' => [],
            'rows' => [],
            'total' => 0
        ];
    }
    $rows = $query->result();
    /*
     * Kolom bad product untuk view.
     */
    $columns = [];
    foreach ($badpros as $row) {
        if (is_object($row)) {
            $uuid = isset($row->uuid) ? $row->uuid : '';
            $nama = isset($row->badpro) ? $row->badpro : (
                isset($row->nama_badpro) ? $row->nama_badpro : ''
            );
        } else {
            $uuid = isset($row['uuid']) ? $row['uuid'] : '';
            $nama = isset($row['badpro']) ? $row['badpro'] : (
                isset($row['nama_badpro']) ? $row['nama_badpro'] : ''
            );
        }
        if ($uuid === '') {
            continue;
        }
        $columns[] = [
            'uuid' => $uuid,
            'nama' => $nama
        ];
    }
    /*
     * Total.
     */
    $total = 0;
    foreach ($rows as $row) {
        $total += (float) $row->total;
    }
    return [
        'columns' => $columns,
        'rows' => $rows,
        'total' => $total
    ];
}
    private function get_scope_machine_counters($filter)
    {
        $this->db->select('tc.mesin_uuid,SUM(tc.counter) AS output_mesin');
        $this->db->from('tcounter tc');
        $this->db->join('tbatch b','b.uuid=tc.tbatch_uuid','inner');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','inner');
        $this->apply_analisa_scope($filter,'b');
        $this->db->where('tc.deleted_at IS NULL',NULL,FALSE);
        $this->db->group_by('tc.mesin_uuid');
        return $this->db->get()->result();
    }
    private function get_scope_badpro($filter)
    {
        $o=$this->get_analisa_filter_options($filter);
        return $o['badpro'];
    }
    private function get_scope_mesins($filter)
    {
        $o=$this->get_analisa_filter_options($filter);
        return $o['mesin'];
    }
    public function get_detail_batch_analisa($filter)
    {
        $sortasi_proses=$this->Proses_model->get_uuid('SORTASI');
        $this->db->select("b.uuid,b.kode_batch,p.tanggal,p.plan,v.varian,v.box_kg,b.adonan,b.filkar_box,b.filkar_kg,
            COALESCE(srt.total_sortasi,0) AS sortasi_box,
            COALESCE(rel.release_box,0) AS release_box,
            COALESCE(rel.release_box * v.box_kg,0) AS release_kg,
            COALESCE(wip.wip_box,0) AS belum_sortir,
            b.bad_filkar_rework_kg,b.bad_filkar_reject_kg,
            COALESCE(bads.rework,0) AS bad_sortasi_rework_kg,
            COALESCE(bads.reject,0) AS bad_sortasi_reject_kg,
            COALESCE(bads.bad_total,0) AS bad_sortasi_total_kg,
            COALESCE(ms.nama_mesin,'-') AS nama_mesin",FALSE);
        $this->db->from('tbatch b');
        $this->db->join('t_planning p','p.uuid=b.t_planning_uuid','left');
        $this->db->join('varian v','v.uuid=p.varian','left');
        $this->db->join("(SELECT tbatch_uuid,SUM(jumlah_wip) total_sortasi FROM sortasi WHERE deleted_at IS NULL GROUP BY tbatch_uuid) srt",'srt.tbatch_uuid=b.uuid','left',FALSE);
        $this->db->join("(SELECT s.tbatch_uuid,SUM(so.jumlah) release_box FROM sortasi_output so INNER JOIN sortasi s ON s.uuid=so.sortasi_uuid WHERE so.jenis_output='RELEASE' AND so.deleted_at IS NULL AND s.deleted_at IS NULL GROUP BY s.tbatch_uuid) rel",'rel.tbatch_uuid=b.uuid','left',FALSE);
        $this->db->join("(SELECT tbatch_uuid,SUM(jumlah_awal-jumlah_terpakai) wip_box FROM sortasi_wip WHERE jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR') AND deleted_at IS NULL GROUP BY tbatch_uuid) wip",'wip.tbatch_uuid=b.uuid','left',FALSE);
        $this->db->join("(SELECT tbp.tbatch_uuid,SUM(CASE WHEN bp.kategori=1 THEN tbp.berat ELSE 0 END) rework,SUM(CASE WHEN bp.kategori=2 THEN tbp.berat ELSE 0 END) reject, SUM(tbp.berat) bad_total FROM t_badpro tbp LEFT JOIN badpro bp ON bp.uuid=tbp.badpro_uuid WHERE tbp.proses_uuid=".$this->db->escape($sortasi_proses)." AND tbp.deleted_at IS NULL GROUP BY tbp.tbatch_uuid) bads",'bads.tbatch_uuid=b.uuid','left',FALSE);
        $this->db->join("(SELECT tc.tbatch_uuid, GROUP_CONCAT(DISTINCT m.nama_mesin ORDER BY m.nama_mesin SEPARATOR ', ') nama_mesin FROM tcounter tc INNER JOIN mesin m ON m.uuid=tc.mesin_uuid WHERE tc.deleted_at IS NULL GROUP BY tc.tbatch_uuid) ms",'ms.tbatch_uuid=b.uuid','left',FALSE);
        $this->apply_analisa_scope($filter,'b');
        $this->apply_active_batch('b');
        $this->db->order_by('p.tanggal','DESC');$this->db->order_by('b.kode_batch','DESC');
        $rows=$this->db->get()->result();
        foreach($rows as $r){
            foreach(['adonan','filkar_box','filkar_kg','sortasi_box','release_box','belum_sortir','bad_filkar_rework_kg','bad_filkar_reject_kg','bad_sortasi_rework_kg','bad_sortasi_reject_kg'] as $k)$r->$k=(float)($r->$k??0);
            $r->yield_formula=$r->adonan>0?round(($r->filkar_kg/$r->adonan)*100,2):0;
            $bad=(float)$r->bad_sortasi_total_kg;
            $r->yield_release=($r->release_kg+$bad)>0?round(($r->release_kg/($r->release_kg+$bad))*100,2):0;
        }
        return $rows;
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
        foreach ([
            'sortasi_box', 'release_box', 'blm_sortir',
            'sortasi_rework', 'sortasi_reject', 'sortasi_bad',
            'sortasi_kg', 'release_kg'
        ] as $k) {
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
}
}
