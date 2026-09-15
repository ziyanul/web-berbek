<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Analisa_model extends CI_Model
{
    private function apply_batch_filters($filters, $alias = 'b')
    {
        if (!empty($filters['varian_uuid'])) {
            $this->db->where('tp.varian', $filters['varian_uuid']);
        }
        if (!empty($filters['plan_uuid'])) {
            $this->db->where($alias . '.t_planning_uuid', $filters['plan_uuid']);
        }
        if (!empty($filters['batch_uuid'])) {
            $this->db->where($alias . '.uuid', $filters['batch_uuid']);
        }
        if (!empty($filters['start'])) {
            $this->db->where($alias . '.tanggal_produksi >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where($alias . '.tanggal_produksi <=', $filters['end']);
        }
    }
    public function get_variants($start, $end)
    {
        $this->db->select('v.uuid, v.varian, COUNT(DISTINCT tp.uuid) AS jumlah_plan', false);
        $this->db->from('t_planning tp');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->where('tp.deleted_at IS NULL', null, false);
        $this->db->where('v.deleted_at IS NULL', null, false);
        if ($start) $this->db->where('tp.tanggal >=', $start);
        if ($end)   $this->db->where('tp.tanggal <=', $end);
        $this->db->group_by(['v.uuid', 'v.varian']);
        $this->db->order_by('v.varian', 'ASC');
        return $this->db->get()->result();
    }
    public function get_plans($start, $end, $varian_uuid = null)
    {
        $this->db->select('tp.uuid, tp.tanggal, tp.plan, tp.varian AS varian_uuid, v.varian');
        $this->db->select('COUNT(DISTINCT b.uuid) AS jumlah_batch', false);
        $this->db->from('t_planning tp');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->join('tbatch b', 'b.t_planning_uuid = tp.uuid AND b.deleted_at IS NULL', 'left');
        $this->db->where('tp.deleted_at IS NULL', null, false);
        if ($start) $this->db->where('tp.tanggal >=', $start);
        if ($end)   $this->db->where('tp.tanggal <=', $end);
        if ($varian_uuid) $this->db->where('tp.varian', $varian_uuid);
        $this->db->group_by(['tp.uuid', 'tp.tanggal', 'tp.plan', 'tp.varian', 'v.varian']);
        $this->db->order_by('tp.tanggal', 'ASC');
        $this->db->order_by('tp.plan', 'ASC');
        return $this->db->get()->result();
    }
    public function get_batches($plan_uuid = null, $varian_uuid = null, $start = null, $end = null)
    {
        $this->db->select('b.uuid, b.kode_batch, b.batch_ke, b.tanggal_produksi, tp.uuid AS plan_uuid, tp.plan, v.uuid AS varian_uuid, v.varian');
        $this->db->select('COALESCE(b.filkar_kg, 0) AS filkar_kg, COALESCE(b.filkar_box, 0) AS filkar_box', false);
        $this->db->from('tbatch b');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'left');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->where('b.deleted_at IS NULL', null, false);
        if ($plan_uuid) $this->db->where('b.t_planning_uuid', $plan_uuid);
        if ($varian_uuid) $this->db->where('tp.varian', $varian_uuid);
        if ($start) $this->db->where('b.tanggal_produksi >=', $start);
        if ($end) $this->db->where('b.tanggal_produksi <=', $end);
        $this->db->order_by('b.tanggal_produksi', 'ASC');
        $this->db->order_by('b.batch_ke', 'ASC');
        return $this->db->get()->result();
    }
    public function get_batch_journey($batch_uuid)
    {
        $batch = $this->db
            ->select('b.*, tp.uuid AS plan_uuid, tp.tanggal AS plan_tanggal, tp.plan, tp.varian AS varian_uuid, v.varian, v.keterangan AS varian_keterangan, v.box_kg')
            ->from('tbatch b')
            ->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'left')
            ->join('varian v', 'v.uuid = tp.varian', 'left')
            ->where('b.uuid', $batch_uuid)
            ->where('b.deleted_at IS NULL', null, false)
            ->get()->row();
        if (!$batch) return null;
        $mp = $this->db
            ->select('mp.*')
            ->from('mp_usage mp')
            ->where('mp.tbatch_uuid', $batch_uuid)
            ->order_by('mp.created_at', 'ASC')
            ->get()->result();
        $counter = $this->db
            ->select('tc.*, m.nama_mesin, m.nama_area, m.device_id AS mesin_device')
            ->from('tcounter tc')
            ->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left')
            ->where('tc.tbatch_uuid', $batch_uuid)
            ->where('tc.deleted_at IS NULL', null, false)
            ->order_by('m.nama_mesin', 'ASC')
            ->get()->result();
        foreach ($counter as $row) {
            $row->performance = null;
            if ((int)$row->counter > 0 && !empty($batch->varian_uuid) && !empty($row->mesin_uuid)) {
                $master = $this->db
                    ->select('speed')
                    ->from('master_speed')
                    ->where('mesin_uuid', $row->mesin_uuid)
                    ->where('varian_uuid', $batch->varian_uuid)
                    ->where('deleted_at', '')
                    ->order_by('created_at', 'DESC')
                    ->limit(1)->get()->row();
                if ($master && (float)$master->speed > 0) {
                    $row->performance = round(((float)$row->speed / (float)$master->speed) * 100, 2);
                }
            }
        }
        $filkar = $this->db
            ->select('f.*, mp.kode_batch AS mp_kode_batch')
            ->from('filkar f')
            ->join('mp_usage mp', 'mp.tbatch_uuid = f.tbatch_uuid', 'left')
            ->where('f.tbatch_uuid', $batch_uuid)
            ->where('f.deleted_at IS NULL', null, false)
            ->order_by('f.created_at', 'ASC')
            ->get()->result();
        $sortasi = $this->db
            ->select('s.*')
            ->from('sortasi s')
            ->where('s.tbatch_uuid', $batch_uuid)
            ->where('s.deleted_at IS NULL', null, false)
            ->order_by('s.created_at', 'ASC')
            ->get()->result();
        $outputs = [];
        // sortasi_output ada pada implementasi Sortasi terbaru; cek keberadaannya agar analisa tidak gagal
        if ($this->db->table_exists('sortasi_output')) {
            $outputs = $this->db
                ->select('so.*, s.jenis_sortasi_uuid')
                ->from('sortasi_output so')
                ->join('sortasi s', 's.uuid = so.sortasi_uuid', 'left')
                ->where('s.tbatch_uuid', $batch_uuid)
                ->where('so.deleted_at IS NULL', null, false)
                ->order_by('so.created_at', 'ASC')
                ->get()->result();
        }
        $badpro = $this->db
            ->select('bp.*, b.nama_badpro, mp.nama_proses, m.nama_mesin')
            ->from('t_badpro bp')
            ->join('badpro b', 'b.uuid = bp.badpro_uuid', 'left')
            ->join('m_proses mp', 'mp.uuid = bp.proses_uuid', 'left')
            ->join('mesin m', 'm.uuid = bp.mesin_uuid', 'left')
            ->where('bp.tbatch_uuid', $batch_uuid)
            ->where('bp.deleted_at IS NULL', null, false)
            ->order_by('bp.created_at', 'ASC')
            ->get()->result();
        return [
            'batch'   => $batch,
            'mp'      => $mp,
            'counter' => $counter,
            'filkar'  => $filkar,
            'sortasi' => $sortasi,
            'outputs' => $outputs,
            'badpro'  => $badpro,
            'drystore'=> $this->get_drystore_by_date_varian($batch->tanggal_produksi, $batch->varian_uuid),
        ];
    }
    private function batch_scope_subquery($filters, $b_alias = 'b')
    {
        $this->db->from('tbatch ' . $b_alias);
        $this->db->join('t_planning tp', 'tp.uuid = ' . $b_alias . '.t_planning_uuid', 'inner');
        $this->db->where($b_alias . '.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where($b_alias . '.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where($b_alias . '.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where($b_alias . '.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where($b_alias . '.uuid', $filters['batch_uuid']);
    }
    public function get_machine_performance($filters)
    {
        $this->db->select('m.uuid AS mesin_uuid, m.nama_mesin, m.nama_area');
        $this->db->select('COUNT(DISTINCT tc.tbatch_uuid) AS jumlah_batch', false);
        $this->db->select('SUM(tc.counter) AS total_counter', false);
        $this->db->select('AVG(tc.speed) AS avg_speed', false);
        $this->db->select('AVG(CASE WHEN ms.speed > 0 THEN (tc.speed / ms.speed) * 100 ELSE NULL END) AS performance', false);
        $this->db->from('tcounter tc');
        $this->db->join('tbatch b', 'b.uuid = tc.tbatch_uuid', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->join('mesin m', 'm.uuid = tc.mesin_uuid', 'left');
        $this->db->join('master_speed ms', 'ms.mesin_uuid = tc.mesin_uuid AND ms.varian_uuid = tp.varian AND ms.deleted_at = \'\'', 'left', false);
        $this->db->where('tc.deleted_at IS NULL', null, false);
        $this->db->where('tc.counter >', 0);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['mesin_uuid'])) $this->db->where('tc.mesin_uuid', $filters['mesin_uuid']);
        $this->db->group_by(['m.uuid', 'm.nama_mesin', 'm.nama_area']);
        $this->db->order_by('performance', 'DESC');
        return $this->db->get()->result();
    }
    private function run_query_or_empty()
    {
        $query = $this->db->get();
        if ($query === false) {
            log_message('error', 'Analisa_model DB error: ' . json_encode($this->db->error()) . ' | SQL: ' . $this->db->last_query());
            return [];
        }
        return $query->result();
    }
    public function get_badpro_analysis($filters)
    {
        $this->db->select('bp.badpro_uuid, COALESCE(bad.nama_badpro, \'-\') AS nama_badpro');
        $this->db->select('SUM(bp.berat) AS total_kg', false);
        $this->db->select('COUNT(DISTINCT bp.tbatch_uuid) AS jumlah_batch', false);
        $this->db->from('t_badpro bp');
        $this->db->join('badpro bad', 'bad.uuid = bp.badpro_uuid', 'left');
        $this->db->join('tbatch b', 'b.uuid = bp.tbatch_uuid', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->where('bp.deleted_at IS NULL', null, false);
        $this->db->where('b.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['proses_uuid'])) $this->db->where('bp.proses_uuid', $filters['proses_uuid']);
        if (!empty($filters['badpro_uuid'])) $this->db->where('bp.badpro_uuid', $filters['badpro_uuid']);
        $this->db->group_by(['bp.badpro_uuid', 'bad.nama_badpro']);
        $this->db->order_by('total_kg', 'DESC');
        $ranking = $this->run_query_or_empty();
        $detail = $this->get_badpro_detail($filters);
        $machine = $this->get_machine_ranking_from_detail($detail);
        return [
            'ranking_badpro' => $ranking,
            'ranking_mesin'  => $machine,
            'detail'         => $detail,
        ];
    }
    private function get_badpro_machine_ranking($filters)
    {
        $this->db->select('m.uuid AS mesin_uuid, COALESCE(m.nama_mesin, \'Tidak diketahui\') AS nama_mesin');
        $this->db->select('SUM(bp.berat) AS total_kg', false);
        $this->db->select('COUNT(DISTINCT bp.tbatch_uuid) AS jumlah_batch', false);
        $this->db->from('t_badpro bp');
        $this->db->join('t_badpro_mesin bpm', 'bpm.t_badpro_uuid = bp.uuid AND bpm.deleted_at IS NULL', 'left');
        $this->db->join('mesin m', 'm.uuid = bpm.mesin_uuid', 'left');
        $this->db->join('tbatch b', 'b.uuid = bp.tbatch_uuid', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->where('bp.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['proses_uuid'])) $this->db->where('bp.proses_uuid', $filters['proses_uuid']);
        if (!empty($filters['mesin_uuid'])) $this->db->where('bpm.mesin_uuid', $filters['mesin_uuid']);
        if (!empty($filters['badpro_uuid'])) $this->db->where('bp.badpro_uuid', $filters['badpro_uuid']);
        $this->db->group_by(['m.uuid', 'm.nama_mesin']);
        $this->db->order_by('total_kg', 'DESC');
        return $this->db->get()->result();
    }
    public function get_yield_analysis($filters)
    {
        $this->db->select('COUNT(DISTINCT b.uuid) AS jumlah_batch', false);
        $this->db->select('COALESCE(SUM(mp.total_output),0) AS total_mp_kg', false);
        $this->db->select('COALESCE(SUM(f.jumlah_kg),0) AS total_filkar_kg', false);
        $this->db->select('COALESCE(SUM(s.jumlah_wip),0) AS total_sortasi_box', false);
        $this->db->from('tbatch b');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->join('(SELECT tbatch_uuid, SUM(total_output) total_output FROM mp_usage GROUP BY tbatch_uuid) mp', 'mp.tbatch_uuid = b.uuid', 'left');
        $this->db->join('(SELECT tbatch_uuid, SUM(jumlah_kg) jumlah_kg FROM filkar WHERE deleted_at IS NULL GROUP BY tbatch_uuid) f', 'f.tbatch_uuid = b.uuid', 'left');
        $this->db->join('(SELECT tbatch_uuid, SUM(jumlah_wip) jumlah_wip FROM sortasi WHERE deleted_at IS NULL GROUP BY tbatch_uuid) s', 's.tbatch_uuid = b.uuid', 'left');
        $this->db->where('b.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        $row = $this->db->get()->row();
        if (!$row) return null;
        $row->filkar_yield = ((float)$row->total_mp_kg > 0) ? round(((float)$row->total_filkar_kg / (float)$row->total_mp_kg) * 100, 2) : 0;
        return $row;
    }
    private function get_drystore_by_date_varian($tanggal, $varian_uuid)
    {
        // Drystore versi sekarang adalah transaksi harian. Ia belum menyimpan tbatch_uuid.
        // Karena itu data ini sengaja dikembalikan sebagai konteks tanggal+varian, bukan diklaim milik batch tertentu.
        if (!$this->db->table_exists('drystore')) return [];
        if (!$this->db->field_exists('tanggal', 'drystore')) return [];
        $this->db->select('d.*');
        $this->db->from('drystore d');
        $this->db->where('d.tanggal', $tanggal);
        $row = $this->db->get()->row();
        if (!$row) return [];
        if ($this->db->table_exists('drystore_waste_transaksi')) {
            $row->transaksi = $this->db
                ->select('t.*, dt.nama AS type_nama, dw.nama AS waste_nama')
                ->from('drystore_waste_transaksi t')
                ->join('drystore_type dt', 'dt.uuid = t.type_uuid', 'left')
                ->join('drystore_waste dw', 'dw.uuid = t.waste_uuid', 'left')
                ->where('t.drystore_uuid', $row->uuid)
                ->get()->result();
        } else {
            $row->transaksi = [];
        }
        return $row;
    }
    public function get_processes()
    {
        return $this->db
            ->select('uuid, kode, nama_proses')
            ->from('m_proses')
            ->where('deleted_at IS NULL', null, false)
            ->order_by('urutan', 'ASC')
            ->order_by('nama_proses', 'ASC')
            ->get()->result();
    }
    public function get_badproducts($filters)
    {
        $this->db->select('bad.uuid, bad.nama_badpro, bad.kategori, COUNT(bp.uuid) AS jumlah_transaksi, COALESCE(SUM(bp.berat),0) AS total_kg', false);
        $this->db->from('badpro bad');
        $this->db->join('t_badpro bp', 'bp.badpro_uuid = bad.uuid AND bp.deleted_at IS NULL', 'inner');
        $this->db->join('tbatch b', 'b.uuid = bp.tbatch_uuid AND b.deleted_at IS NULL', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->where('bad.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['proses_uuid'])) $this->db->where('bp.proses_uuid', $filters['proses_uuid']);
        $this->db->group_by(['bad.uuid', 'bad.nama_badpro', 'bad.kategori']);
        $this->db->order_by('bad.nama_badpro', 'ASC');
        return $this->db->get()->result();
    }
    private function badpro_base_query($filters, $select)
    {
        $this->db->select($select, false);
        $this->db->from('t_badpro bp');
        $this->db->join('badpro bad', 'bad.uuid = bp.badpro_uuid', 'left');
        $this->db->join('m_proses pr', 'pr.uuid = bp.proses_uuid', 'left');
        $this->db->join('tbatch b', 'b.uuid = bp.tbatch_uuid', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->join('varian v', 'v.uuid = tp.varian', 'left');
        $this->db->where('bp.deleted_at IS NULL', null, false);
        $this->db->where('b.deleted_at IS NULL', null, false);
        if (!empty($filters['start'])) $this->db->where('b.tanggal_produksi >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('b.tanggal_produksi <=', $filters['end']);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['proses_uuid'])) $this->db->where('bp.proses_uuid', $filters['proses_uuid']);
        if (!empty($filters['badpro_uuid'])) $this->db->where('bp.badpro_uuid', $filters['badpro_uuid']);
        if (!empty($filters['mesin_uuid'])) {
            $esc = $this->db->escape($filters['mesin_uuid']);
            $this->db->where("EXISTS (SELECT 1 FROM t_badpro_mesin bpmf WHERE bpmf.t_badpro_uuid = bp.uuid AND bpmf.mesin_uuid = {$esc} AND bpmf.deleted_at IS NULL)", null, false);
        }
    }
    public function get_badpro_detail($filters)
    {
        $select = "DATE(b.tanggal_produksi) AS tanggal,
            v.varian,
            COALESCE(pr.kode, pr.nama_proses, '-') AS proses,
            CASE
                WHEN bp.ref_uuid IS NOT NULL AND bp.ref_uuid <> '' THEN
                    CONCAT(COALESCE(pr.kode, 'REF'), ' | ', LEFT(bp.ref_uuid, 8), '...')
                ELSE '-'
            END AS ref,
            tp.plan,
            b.kode_batch,
            COALESCE(GROUP_CONCAT(DISTINCT m.nama_mesin ORDER BY m.nama_mesin SEPARATOR ', '), 'Tidak diketahui') AS mesin,
            COALESCE(bad.nama_badpro, '-') AS nama_badpro,
            CASE WHEN bp.kategori = 1 THEN 'Rework' WHEN bp.kategori = 2 THEN 'Reject' ELSE '-' END AS kategori,
            bp.berat";
        $this->badpro_base_query($filters, $select);
        $this->db->join('t_badpro_mesin bpm', 'bpm.t_badpro_uuid = bp.uuid AND bpm.deleted_at IS NULL', 'left');
        $this->db->join('mesin m', 'm.uuid = bpm.mesin_uuid AND m.deleted_at IS NULL', 'left');
        $this->db->group_by(['bp.uuid', 'b.tanggal_produksi', 'v.varian', 'pr.kode', 'pr.nama_proses', 'bp.ref_uuid', 'tp.plan', 'b.kode_batch', 'bad.nama_badpro', 'bp.kategori', 'bp.berat', 'bp.created_at']);
        $this->db->order_by('b.tanggal_produksi', 'ASC');
        $this->db->order_by('b.kode_batch', 'ASC');
        $this->db->order_by('bp.created_at', 'ASC');
        return $this->run_query_or_empty();
    }
    public function get_badpro_trend($filters)
    {
        // Trend wajib mengembalikan SEMUA tanggal dalam range, walaupun tidak ada bad product.
        // Filter end dibuat inklusif agar tetap benar bila tanggal_produksi ternyata DATETIME.
        $start = !empty($filters['start']) ? substr($filters['start'], 0, 10) : date('Y-m-d');
        $end   = !empty($filters['end']) ? substr($filters['end'], 0, 10) : $start;
        try {
            $d1 = new DateTime($start);
            $d2 = new DateTime($end);
        } catch (Exception $e) {
            return [];
        }
        if ($d2 < $d1) return [];
        // Buat kalender tanggal lebih dulu. Jadi walaupun query tidak menemukan data,
        // response tetap berisi tanggal 0 kg.
        $out = [];
        $map = [];
        for ($d = clone $d1; $d <= $d2; $d->modify('+1 day')) {
            $key = $d->format('Y-m-d');
            $map[$key] = 0.0;
            $out[] = (object)['tanggal' => $key, 'total_kg' => 0.0];
        }
        $this->db->select('DATE(b.tanggal_produksi) AS tanggal, COALESCE(SUM(bp.berat),0) AS total_kg', false);
        $this->db->from('t_badpro bp');
        $this->db->join('tbatch b', 'b.uuid = bp.tbatch_uuid', 'inner');
        $this->db->join('t_planning tp', 'tp.uuid = b.t_planning_uuid', 'inner');
        $this->db->where('bp.deleted_at IS NULL', null, false);
        $this->db->where('b.deleted_at IS NULL', null, false);
        $this->db->where('b.tanggal_produksi >=', $start . ' 00:00:00');
        $endExclusive = (new DateTime($end))
            ->modify('+1 day')
                ->format('Y-m-d') . ' 00:00:00';
        $this->db->where('b.tanggal_produksi <', $endExclusive);
        if (!empty($filters['varian_uuid'])) $this->db->where('tp.varian', $filters['varian_uuid']);
        if (!empty($filters['plan_uuid'])) $this->db->where('b.t_planning_uuid', $filters['plan_uuid']);
        if (!empty($filters['batch_uuid'])) $this->db->where('b.uuid', $filters['batch_uuid']);
        if (!empty($filters['proses_uuid'])) $this->db->where('bp.proses_uuid', $filters['proses_uuid']);
        if (!empty($filters['badpro_uuid'])) $this->db->where('bp.badpro_uuid', $filters['badpro_uuid']);
        $this->db->group_by('DATE(b.tanggal_produksi)');
        $this->db->order_by('tanggal', 'ASC');
        $query = $this->db->get();
        if ($query !== false) {
            foreach ($query->result() as $r) {
                if (isset($map[$r->tanggal])) $map[$r->tanggal] = round((float)$r->total_kg, 3);
            }
        } else {
            log_message('error', 'Analisa trend query error: ' . json_encode($this->db->error()) . ' | SQL: ' . $this->db->last_query());
        }
        foreach ($out as $r) $r->total_kg = $map[$r->tanggal];
        return $out;
    }
    public function get_badpro_journey($filters, $group_by = 'plan')
    {
        if ($group_by === 'batch') {
            $select = 'DATE(b.tanggal_produksi) AS tanggal_produksi, tp.plan, v.varian, b.kode_batch, COUNT(DISTINCT bp.uuid) AS jumlah_transaksi, SUM(bp.berat) AS total_kg';
            $group = ['DATE(b.tanggal_produksi)', 'tp.plan', 'v.varian', 'b.kode_batch'];
            $order = ['tanggal_produksi', 'tp.plan', 'b.kode_batch'];
        } else {
            $select = 'DATE(tp.tanggal) AS tanggal_plan, tp.plan, v.varian, COUNT(DISTINCT b.uuid) AS jumlah_batch, COUNT(DISTINCT bp.uuid) AS jumlah_transaksi, SUM(bp.berat) AS total_kg';
            $group = ['DATE(tp.tanggal)', 'tp.plan', 'v.varian'];
            $order = ['tanggal_plan', 'tp.plan'];
        }
        $this->badpro_base_query($filters, $select);
        $this->db->group_by($group);
        $this->db->order_by($order[0], 'ASC');
        $this->db->order_by($order[1], 'ASC');
        if (isset($order[2])) $this->db->order_by($order[2], 'ASC');
        return $this->run_query_or_empty();
    }
    private function get_machine_ranking_from_detail($detail)
    {
        $map = [];
        foreach ($detail as $r) {
            $machines = array_filter(array_map('trim', explode(',', (string)$r->mesin)));
            if (!$machines) $machines = ['Tidak diketahui'];
            $share = (float)$r->berat / count($machines);
            foreach ($machines as $machine) {
                if (!isset($map[$machine])) $map[$machine] = ['nama_mesin' => $machine, 'total_kg' => 0, 'jumlah_transaksi' => 0, 'batch' => []];
                $map[$machine]['total_kg'] += $share;
                $map[$machine]['jumlah_transaksi']++;
                $map[$machine]['batch'][$r->kode_batch] = true;
            }
        }
        $out = [];
        foreach ($map as $r) {
            $r['total_kg'] = round($r['total_kg'], 3);
            $r['jumlah_batch'] = count($r['batch']);
            unset($r['batch']);
            $out[] = (object)$r;
        }
        usort($out, function($a, $b) { return $b->total_kg <=> $a->total_kg; });
        return $out;
    }
    public function get_badpro_export_data($filters)
    {
        $detail = $this->get_badpro_detail($filters);
        $trend = $this->get_badpro_trend($filters);
        $journeyPlan = $this->get_badpro_journey($filters, 'plan');
        $journeyBatch = $this->get_badpro_journey($filters, 'batch');
        $machine = $this->get_machine_ranking_from_detail($detail);
        $total = 0; $batches = []; $plans = [];
        foreach ($detail as $r) {
            $total += (float)$r->berat;
            $batches[$r->kode_batch] = true;
            $plans[$r->tanggal . '|' . $r->plan] = true;
        }
        return [
            'summary' => [
                'total_bad' => round($total, 3),
                'jumlah_batch' => count($batches),
                'jumlah_plan' => count($plans),
                'mesin_dominan' => !empty($machine) ? $machine[0]->nama_mesin : '-',
                'jumlah_transaksi' => count($detail),
            ],
            'trend' => $trend,
            'machine' => $machine,
            'journey_plan' => $journeyPlan,
            'journey_batch' => $journeyBatch,
            'detail' => $detail,
        ];
    }
}
