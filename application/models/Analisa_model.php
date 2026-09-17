<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analisa_model extends CI_Model
{
    private function endExclusive($end)
    {
        return date('Y-m-d', strtotime($end . ' +1 day'));
    }

    private function formatTanggal($tanggal)
    {
        if (empty($tanggal)) return '-';
        return function_exists('tanggal_indo') ? tanggal_indo($tanggal) : date('d/m/Y', strtotime($tanggal));
    }

    private function applyBatchFilters($f, $dateField = 'b.tanggal_produksi')
    {
        if (!empty($f['start'])) $this->db->where($dateField . ' >=', $f['start']);
        if (!empty($f['end'])) $this->db->where($dateField . ' <', $this->endExclusive($f['end']));
        if (!empty($f['varian_uuid'])) $this->db->where('tp.varian', $f['varian_uuid']);
        if (!empty($f['plan_uuid'])) $this->db->where('b.t_planning_uuid', $f['plan_uuid']);
        if (!empty($f['batch_uuid'])) $this->db->where('b.uuid', $f['batch_uuid']);
    }

    private function baseBatch($f)
    {
        $this->db->from('tbatch b')
            ->join('t_planning tp', 'tp.uuid=b.t_planning_uuid', 'inner')
            ->join('varian v', 'v.uuid=tp.varian', 'left')
            ->where('b.deleted_at IS NULL', null, false)
            ->where('tp.deleted_at IS NULL', null, false);
        $this->applyBatchFilters($f);
    }

    public function get_variants($start, $end)
    {
        $this->db->select('v.uuid,v.varian')
            ->from('tbatch b')
            ->join('t_planning tp', 'tp.uuid=b.t_planning_uuid', 'inner')
            ->join('varian v', 'v.uuid=tp.varian', 'inner')
            ->where('b.deleted_at IS NULL', null, false)
            ->where('tp.deleted_at IS NULL', null, false);
        $this->applyBatchFilters(['start'=>$start, 'end'=>$end]);
        return $this->db->group_by(['v.uuid','v.varian'])->order_by('v.varian')->get()->result();
    }

    public function get_plans($f)
    {
        $this->db->select('tp.uuid,tp.tanggal,v.varian,COUNT(DISTINCT b.uuid) jumlah_batch', false)
            ->from('t_planning tp')
            ->join('tbatch b', 'b.t_planning_uuid=tp.uuid AND b.deleted_at IS NULL', 'left')
            ->join('varian v', 'v.uuid=tp.varian', 'left')
            ->where('tp.deleted_at IS NULL', null, false);
        if (!empty($f['start'])) $this->db->where('tp.tanggal >=', $f['start']);
        if (!empty($f['end'])) $this->db->where('tp.tanggal <', $this->endExclusive($f['end']));
        if (!empty($f['varian_uuid'])) $this->db->where('tp.varian', $f['varian_uuid']);
        $rows = $this->db->group_by(['tp.uuid','tp.tanggal','v.varian'])
            ->order_by('tp.tanggal')->order_by('v.varian')->get()->result();
        foreach ($rows as $r) $r->tanggal_display = $this->formatTanggal($r->tanggal);
        return $rows;
    }

    public function get_batches($f)
    {
        $this->db->select('b.uuid,b.kode_batch,b.tanggal_produksi,tp.uuid plan_uuid,tp.tanggal tanggal_plan,v.varian')
            ->from('tbatch b')
            ->join('t_planning tp', 'tp.uuid=b.t_planning_uuid')
            ->join('varian v', 'v.uuid=tp.varian', 'left')
            ->where('b.deleted_at IS NULL', null, false)
            ->where('tp.deleted_at IS NULL', null, false);
        $this->applyBatchFilters($f);
        $rows = $this->db->order_by('b.tanggal_produksi')->order_by('b.kode_batch')->get()->result();
        foreach ($rows as $r) {
            $r->tanggal_produksi_display = $this->formatTanggal($r->tanggal_produksi);
            $r->tanggal_plan_display = $this->formatTanggal($r->tanggal_plan);
        }
        return $rows;
    }

    public function get_processes()
    {
        return $this->db->select('uuid,kode,nama_proses')->from('m_proses')
            ->where('deleted_at IS NULL', null, false)->order_by('urutan')->get()->result();
    }

    /** Mesin filter hanya mesin yang benar-benar ada di tcounter.mesin_uuid. */
    public function get_machines($f = [])
    {
        $this->db->select('m.uuid,m.nama_mesin,m.nama_area')
            ->from('tcounter tc')
            ->join('tbatch b', 'b.uuid=tc.tbatch_uuid')
            ->join('t_planning tp', 'tp.uuid=b.t_planning_uuid')
            ->join('mesin m', 'm.uuid=tc.mesin_uuid', 'inner')
            ->where('tc.deleted_at IS NULL', null, false)
            ->where('b.deleted_at IS NULL', null, false)
            ->where('tp.deleted_at IS NULL', null, false)
            ->where('m.deleted_at IS NULL', null, false)
            ->where('tc.mesin_uuid IS NOT NULL', null, false)
            ->where('tc.mesin_uuid <>', '')
            ->where('tc.counter >', 0);
        $this->applyBatchFilters($f);
        return $this->db->group_by(['m.uuid','m.nama_mesin','m.nama_area'])->order_by('m.nama_mesin')->get()->result();
    }

    public function get_badproducts($f)
    {
        $this->db->select('bad.uuid,bad.nama_badpro,SUM(bp.berat) total_kg,COUNT(*) jumlah_transaksi', false)
            ->from('badpro bad')
            ->join('t_badpro bp', 'bp.badpro_uuid=bad.uuid AND bp.deleted_at IS NULL')
            ->join('tbatch b', 'b.uuid=bp.tbatch_uuid')
            ->join('t_planning tp', 'tp.uuid=b.t_planning_uuid')
            ->where('bad.deleted_at IS NULL', null, false);
        $this->applyBatchFilters($f);
        if (!empty($f['proses_uuid'])) $this->db->where('bp.proses_uuid', $f['proses_uuid']);
        if (!empty($f['mesin_uuid'])) {
            $this->db->where('(bp.mesin_uuid=' . $this->db->escape($f['mesin_uuid']) . ' OR EXISTS (SELECT 1 FROM t_badpro_mesin bm WHERE bm.t_badpro_uuid=bp.uuid AND bm.mesin_uuid=' . $this->db->escape($f['mesin_uuid']) . ' AND bm.deleted_at IS NULL))', null, false);
        }
        $this->db->group_by(['bad.uuid','bad.nama_badpro']);
        return $this->db->order_by('bad.nama_badpro')->get()->result();
    }

    public function get_badpro_detail($f)
    {
        $sql = "SELECT bp.uuid,v.uuid varian_uuid,b.tanggal_produksi tanggal,v.varian,
                COALESCE(pr.kode,pr.nama_proses,'-') proses,
                b.kode_batch,COALESCE(NULLIF(bp.mesin_uuid,''),'-') mesin_uuid,
                bad.nama_badpro,
                CASE WHEN bp.kategori=1 THEN 'Rework' WHEN bp.kategori=2 THEN 'Reject' ELSE '-' END kategori,
                bp.berat,bp.created_at
                FROM t_badpro bp
                JOIN tbatch b ON b.uuid=bp.tbatch_uuid
                JOIN t_planning tp ON tp.uuid=b.t_planning_uuid
                LEFT JOIN varian v ON v.uuid=tp.varian
                LEFT JOIN badpro bad ON bad.uuid=bp.badpro_uuid
                LEFT JOIN m_proses pr ON pr.uuid=bp.proses_uuid
                WHERE bp.deleted_at IS NULL AND b.deleted_at IS NULL AND tp.deleted_at IS NULL";
        $params=[];
        if (!empty($f['start'])) {$sql.=' AND b.tanggal_produksi >= ?'; $params[]=$f['start'];}
        if (!empty($f['end'])) {$sql.=' AND b.tanggal_produksi < ?'; $params[]=$this->endExclusive($f['end']);}
        if (!empty($f['varian_uuid'])) {$sql.=' AND tp.varian = ?'; $params[]=$f['varian_uuid'];}
        if (!empty($f['plan_uuid'])) {$sql.=' AND b.t_planning_uuid = ?'; $params[]=$f['plan_uuid'];}
        if (!empty($f['batch_uuid'])) {$sql.=' AND b.uuid = ?'; $params[]=$f['batch_uuid'];}
        if (!empty($f['proses_uuid'])) {$sql.=' AND bp.proses_uuid = ?'; $params[]=$f['proses_uuid'];}
        if (!empty($f['badpro_uuid'])) {$sql.=' AND bp.badpro_uuid = ?'; $params[]=$f['badpro_uuid'];}
        if (!empty($f['mesin_uuid'])) {
            $sql.=' AND (bp.mesin_uuid=? OR EXISTS(SELECT 1 FROM t_badpro_mesin z WHERE z.t_badpro_uuid=bp.uuid AND z.mesin_uuid=? AND z.deleted_at IS NULL))';
            $params[]=$f['mesin_uuid']; $params[]=$f['mesin_uuid'];
        }
        $sql.=' ORDER BY b.tanggal_produksi,b.kode_batch,bp.created_at';
        $rows=$this->db->query($sql,$params)->result();
        foreach ($rows as $r) {
            $names=[];
            if (!empty($r->mesin_uuid) && $r->mesin_uuid!=='-') {
                $m=$this->db->select('nama_mesin')->where('uuid',$r->mesin_uuid)->where('deleted_at IS NULL',null,false)->get('mesin')->row();
                if ($m && $m->nama_mesin) $names[]=$m->nama_mesin;
            }
            if ($this->db->table_exists('t_badpro_mesin')) {
                $ms=$this->db->select('m.nama_mesin')->from('t_badpro_mesin x')->join('mesin m','m.uuid=x.mesin_uuid','left')
                    ->where('x.t_badpro_uuid',$r->uuid)->where('x.deleted_at IS NULL',null,false)->get()->result();
                foreach($ms as $m) if(!empty($m->nama_mesin)) $names[]=$m->nama_mesin;
            }
            $r->mesin=implode(', ',array_values(array_unique($names))) ?: '-';
            $r->tanggal_display=$this->formatTanggal($r->tanggal);
            unset($r->mesin_uuid,$r->uuid);
        }
        return $rows;
    }

    public function get_badpro_trend($f)
    {
        $start=$f['start'] ?: date('Y-m-01'); $end=$f['end'] ?: date('Y-m-d'); $map=[];
        $this->db->select('DATE(b.tanggal_produksi) tanggal,SUM(bp.berat) total_kg',false)
            ->from('t_badpro bp')->join('tbatch b','b.uuid=bp.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')
            ->where('bp.deleted_at IS NULL',null,false)->where('b.deleted_at IS NULL',null,false)->where('tp.deleted_at IS NULL',null,false)
            ->where('b.tanggal_produksi >=',$start)->where('b.tanggal_produksi <',$this->endExclusive($end));
        if(!empty($f['varian_uuid']))$this->db->where('tp.varian',$f['varian_uuid']);
        if(!empty($f['plan_uuid']))$this->db->where('b.t_planning_uuid',$f['plan_uuid']);
        if(!empty($f['batch_uuid']))$this->db->where('b.uuid',$f['batch_uuid']);
        if(!empty($f['proses_uuid']))$this->db->where('bp.proses_uuid',$f['proses_uuid']);
        if(!empty($f['badpro_uuid']))$this->db->where('bp.badpro_uuid',$f['badpro_uuid']);
        if(!empty($f['mesin_uuid']))$this->db->where('(bp.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' OR EXISTS(SELECT 1 FROM t_badpro_mesin bm WHERE bm.t_badpro_uuid=bp.uuid AND bm.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' AND bm.deleted_at IS NULL))',null,false);
        foreach($this->db->group_by('DATE(b.tanggal_produksi)')->get()->result() as $r)$map[$r->tanggal]=(float)$r->total_kg;
        $out=[]; for($d=new DateTime($start);$d<=new DateTime($end);$d->modify('+1 day')){$k=$d->format('Y-m-d');$out[]=(object)['tanggal'=>$k,'tanggal_display'=>$this->formatTanggal($k),'total_kg'=>$map[$k]??0];}
        return $out;
    }

    /** Bad product -> mesin dengan alokasi berat rata jika satu transaksi punya beberapa mesin. */
    private function badMachineRows($f)
    {
        $this->db->select('bp.uuid bp_uuid,bp.tbatch_uuid,bp.berat,bp.mesin_uuid,bp.badpro_uuid,bp.proses_uuid',false)
            ->from('t_badpro bp')->join('tbatch b','b.uuid=bp.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')
            ->where('bp.deleted_at IS NULL',null,false)->where('b.deleted_at IS NULL',null,false)->where('tp.deleted_at IS NULL',null,false);
        $this->applyBatchFilters($f);
        if(!empty($f['proses_uuid']))$this->db->where('bp.proses_uuid',$f['proses_uuid']);
        if(!empty($f['badpro_uuid']))$this->db->where('bp.badpro_uuid',$f['badpro_uuid']);
        if(!empty($f['mesin_uuid']))$this->db->where('(bp.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' OR EXISTS(SELECT 1 FROM t_badpro_mesin z WHERE z.t_badpro_uuid=bp.uuid AND z.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' AND z.deleted_at IS NULL))',null,false);
        $badRows=$this->db->get()->result(); if(!$badRows)return [];
        $ids=array_map(function($r){return $r->bp_uuid;},$badRows); $in=implode(',',array_map([$this->db,'escape'],$ids));
        $relations=[];
        foreach($this->db->query("SELECT uuid bp_uuid,mesin_uuid FROM t_badpro WHERE uuid IN ($in) AND mesin_uuid IS NOT NULL AND mesin_uuid<>''")->result() as $r)$relations[]=$r;
        if($this->db->table_exists('t_badpro_mesin')) foreach($this->db->query("SELECT t_badpro_uuid bp_uuid,mesin_uuid FROM t_badpro_mesin WHERE t_badpro_uuid IN ($in) AND deleted_at IS NULL AND mesin_uuid IS NOT NULL AND mesin_uuid<>''")->result() as $r)$relations[]=$r;
        $unique=[]; foreach($relations as $r)$unique[$r->bp_uuid.'|'.$r->mesin_uuid]=$r;
        $byBp=[]; foreach($unique as $r)$byBp[$r->bp_uuid][]=$r->mesin_uuid;
        $machineNames=[]; $badNames=[];
        $mids=[]; foreach($unique as $r)$mids[$r->mesin_uuid]=true;
        if($mids){$mi=implode(',',array_map([$this->db,'escape'],array_keys($mids)));foreach($this->db->query("SELECT uuid,nama_mesin FROM mesin WHERE uuid IN ($mi)")->result() as $r)$machineNames[$r->uuid]=$r->nama_mesin;}
        $bids=[]; foreach($badRows as $r)$bids[$r->badpro_uuid]=true;
        if($bids){$bi=implode(',',array_map([$this->db,'escape'],array_keys($bids)));foreach($this->db->query("SELECT uuid,nama_badpro FROM badpro WHERE uuid IN ($bi)")->result() as $r)$badNames[$r->uuid]=$r->nama_badpro;}
        $processNames=[]; $pids=[]; foreach($badRows as $r) if(!empty($r->proses_uuid)) $pids[$r->proses_uuid]=true;
        if($pids){$pi=implode(',',array_map([$this->db,'escape'],array_keys($pids)));foreach($this->db->query("SELECT uuid,kode,nama_proses FROM m_proses WHERE uuid IN ($pi)")->result() as $r)$processNames[$r->uuid]=$r->kode ?: $r->nama_proses;}
        $out=[];
        foreach($badRows as $r){$ms=$byBp[$r->bp_uuid]??[];if(!$ms)continue;$alloc=(float)$r->berat/count($ms);foreach($ms as $machineId){$key=$machineId.'|'.$r->badpro_uuid.'|'.$r->proses_uuid;if(!isset($out[$key]))$out[$key]=(object)['mesin_uuid'=>$machineId,'nama_mesin'=>$machineNames[$machineId]??'Tidak diketahui','badpro_uuid'=>$r->badpro_uuid,'nama_badpro'=>$badNames[$r->badpro_uuid]??'Tidak diketahui','proses_uuid'=>$r->proses_uuid,'proses'=>$processNames[$r->proses_uuid]??'-','total_kg'=>0];$out[$key]->total_kg+=$alloc;}}
        $out=array_values($out); usort($out,function($a,$b){return $b->total_kg<=>$a->total_kg;}); return $out;
    }

    private function badByMachineProduct($f){return $this->badMachineRows($f);}

    public function get_badpro_journey($f,$group)
    {
        if($group==='batch'){
            $select='DATE(b.tanggal_produksi) tanggal_produksi,v.varian,b.kode_batch,SUM(bp.berat) total_kg';
            $groups=['b.tanggal_produksi','v.varian','b.kode_batch'];
        }else{
            $select='tp.tanggal tanggal_plan,v.varian,COUNT(DISTINCT b.uuid) jumlah_batch,SUM(bp.berat) total_kg';
            $groups=['tp.tanggal','v.varian'];
        }
        $this->db->select($select,false)->from('t_badpro bp')->join('tbatch b','b.uuid=bp.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')->join('varian v','v.uuid=tp.varian','left')->where('bp.deleted_at IS NULL',null,false);
        $this->applyBatchFilters($f);
        if(!empty($f['proses_uuid']))$this->db->where('bp.proses_uuid',$f['proses_uuid']);
        if(!empty($f['badpro_uuid']))$this->db->where('bp.badpro_uuid',$f['badpro_uuid']);
        $rows=$this->db->group_by($groups)->order_by($group==='batch'?'b.tanggal_produksi':'tp.tanggal')->get()->result();
        foreach($rows as $r){if($group==='batch')$r->tanggal_produksi_display=$this->formatTanggal($r->tanggal_produksi);else$r->tanggal_plan_display=$this->formatTanggal($r->tanggal_plan);}
        return $rows;
    }

    public function get_badpro_analysis($f)
    {
        $d=$this->get_badpro_detail($f); $machine=$this->badMachineRows($f);
        $this->db->select('bad.uuid,bad.nama_badpro,SUM(bp.berat) total_kg,COUNT(*) jumlah_transaksi',false)->from('t_badpro bp')->join('badpro bad','bad.uuid=bp.badpro_uuid','left')->join('tbatch b','b.uuid=bp.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')->where('bp.deleted_at IS NULL',null,false);
        $this->applyBatchFilters($f); if(!empty($f['proses_uuid']))$this->db->where('bp.proses_uuid',$f['proses_uuid']); if(!empty($f['badpro_uuid']))$this->db->where('bp.badpro_uuid',$f['badpro_uuid']);
        $ranking=$this->db->group_by(['bad.uuid','bad.nama_badpro'])->order_by('total_kg','DESC')->get()->result();
        return ['ranking_badpro'=>$ranking,'ranking_mesin'=>$this->badMachineRanking($f),'detail'=>$d,'journey_batch'=>$this->get_badpro_journey($f,'batch'),'journey_plan'=>$this->get_badpro_journey($f,'plan'),'trend'=>$this->get_badpro_trend($f),'rework'=>$this->get_rework_analysis($f)];
    }

    private function badMachineRanking($f)
    {
        $rows=$this->badMachineRows($f); $map=[];
        foreach($rows as $r){$id=$r->mesin_uuid;if(!isset($map[$id]))$map[$id]=(object)['mesin_uuid'=>$id,'nama_mesin'=>$r->nama_mesin,'total_kg'=>0,'jumlah_transaksi'=>0,'jumlah_batch'=>0];$map[$id]->total_kg+=(float)$r->total_kg;}
        // Transaction/batch count dihitung dari transaksi bad product asli, bukan dari hasil alokasi.
        $this->db->select('bp.uuid bp_uuid,bp.tbatch_uuid,bp.mesin_uuid',false)->from('t_badpro bp')->join('tbatch b','b.uuid=bp.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')->where('bp.deleted_at IS NULL',null,false);
        $this->applyBatchFilters($f); if(!empty($f['proses_uuid']))$this->db->where('bp.proses_uuid',$f['proses_uuid']); if(!empty($f['badpro_uuid']))$this->db->where('bp.badpro_uuid',$f['badpro_uuid']);
        if(!empty($f['mesin_uuid']))$this->db->where('(bp.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' OR EXISTS(SELECT 1 FROM t_badpro_mesin z WHERE z.t_badpro_uuid=bp.uuid AND z.mesin_uuid='.$this->db->escape($f['mesin_uuid']).' AND z.deleted_at IS NULL))',null,false);
        $base=$this->db->get()->result();
        foreach($base as $r){$ms=[];if(!empty($r->mesin_uuid))$ms[]=$r->mesin_uuid;if($this->db->table_exists('t_badpro_mesin'))foreach($this->db->select('mesin_uuid')->where('t_badpro_uuid',$r->bp_uuid)->where('deleted_at IS NULL',null,false)->get('t_badpro_mesin')->result() as $m)if(!empty($m->mesin_uuid))$ms[]=$m->mesin_uuid;foreach(array_unique($ms) as $id){if(isset($map[$id])){$map[$id]->jumlah_transaksi++;$map[$id]->jumlah_batch++;}}}
        // jumlah_batch di atas bisa terduplikasi dalam satu batch; hitung ulang unik.
        foreach($map as $id=>$r){$batches=[];foreach($base as $bp){$ms=[];if(!empty($bp->mesin_uuid))$ms[]=$bp->mesin_uuid;if($this->db->table_exists('t_badpro_mesin'))foreach($this->db->select('mesin_uuid')->where('t_badpro_uuid',$bp->bp_uuid)->where('deleted_at IS NULL',null,false)->get('t_badpro_mesin')->result() as $m)$ms[]=$m->mesin_uuid;if(in_array($id,array_unique($ms)))$batches[$bp->tbatch_uuid]=true;}$r->jumlah_batch=count($batches);}
        $out=array_values($map);usort($out,function($a,$b){return $b->total_kg<=>$a->total_kg;});return $out;
    }

    /**
     * Yield sesuai kebutuhan Analisa:
     * - Yield Filkar = Filkar KG / Total MP KG * 100
     * - Yield Release = Release KG / (Release KG + Bad Product KG) * 100
     * Tidak menampilkan overall yield / neraca selisih.
     */
    public function get_yield_analysis_full($f)
    {
        $this->baseBatch($f);
        $batch=$this->db->select('b.uuid')->get()->result();
        $ids=array_map(function($x){return $x->uuid;},$batch);
        $out=(object)['jumlah_planning'=>0,'jumlah_batch'=>count($ids),'mp_formula_kg'=>0,'mp_rework_kg'=>0,'mp_total_kg'=>0,'counter'=>0,'filkar_kg'=>0,'filkar_box'=>0,'sortasi_input_box'=>0,'release_box'=>0,'release_kg'=>0,'tampung_box'=>0,'kasar_box'=>0,'cuci_box'=>0,'bad_kg'=>0,'sisa_wip_box'=>0,'yield_filkar_pct'=>0,'yield_release_pct'=>0,'bad_rework_kg'=>0,'hasil_kupas_kg'=>0,'belum_kupas_kg'=>0,'terpakai_rework_kg'=>0,'sisa_kupas_kg'=>0,'pvdc'=>0,'wire'=>0,'onproduk_pvdc'=>0,'reject_pvdc'=>0,'reject_pvdc_persen'=>0,'onproduk_wire'=>0,'reject_wire'=>0,'reject_wire_persen'=>0,'plans'=>[]];

        $this->db->select('COUNT(DISTINCT tp.uuid) jumlah_planning')->from('t_planning tp')->where('tp.deleted_at IS NULL',null,false);
        if(!empty($f['start']))$this->db->where('tp.tanggal >=',$f['start']);
        if(!empty($f['end']))$this->db->where('tp.tanggal <',$this->endExclusive($f['end']));
        if(!empty($f['varian_uuid']))$this->db->where('tp.varian',$f['varian_uuid']);
        if(!empty($f['plan_uuid']))$this->db->where('tp.uuid',$f['plan_uuid']);
        $out->jumlah_planning=(int)$this->db->get()->row()->jumlah_planning;
        if(!$ids)return (array)$out;

        $list=implode(',',array_map([$this->db,'escape'],$ids));
        $q=$this->db->query("SELECT COALESCE(SUM(formula_kg),0) formula,COALESCE(SUM(rework_kg),0) rework,COALESCE(SUM(total_output),0) total FROM mp_usage WHERE tbatch_uuid IN ($list)")->row();
        $out->mp_formula_kg=(float)$q->formula;$out->mp_rework_kg=(float)$q->rework;$out->mp_total_kg=(float)$q->total;
        $q=$this->db->query("SELECT COALESCE(SUM(counter),0) total FROM tcounter WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL")->row();$out->counter=(int)$q->total;
        $q=$this->db->query("SELECT COALESCE(SUM(jumlah_kg),0) kg,COALESCE(SUM(jumlah_box),0) box FROM filkar WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL")->row();$out->filkar_kg=(float)$q->kg;$out->filkar_box=(float)$q->box;
        $q=$this->db->query("SELECT COALESCE(SUM(jumlah_wip),0) box FROM sortasi WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL")->row();$out->sortasi_input_box=(float)$q->box;
        if($this->db->table_exists('sortasi_output')){
            foreach(['RELEASE'=>'release_box','TAMPUNG'=>'tampung_box','KASAR'=>'kasar_box','CUCI'=>'cuci_box'] as $t=>$p){$q=$this->db->query("SELECT COALESCE(SUM(so.jumlah),0) box FROM sortasi_output so JOIN sortasi s ON s.uuid=so.sortasi_uuid WHERE s.tbatch_uuid IN ($list) AND s.deleted_at IS NULL AND so.deleted_at IS NULL AND so.jenis_output=?",[$t])->row();$out->$p=(float)$q->box;}
            $releaseKg=0;
            $rq=$this->db->query("SELECT s.tbatch_uuid,COALESCE(SUM(so.jumlah),0) qty,v.box_kg FROM sortasi_output so JOIN sortasi s ON s.uuid=so.sortasi_uuid JOIN tbatch b ON b.uuid=s.tbatch_uuid JOIN t_planning tp ON tp.uuid=b.t_planning_uuid JOIN varian v ON v.uuid=tp.varian WHERE s.tbatch_uuid IN ($list) AND s.deleted_at IS NULL AND so.deleted_at IS NULL AND so.jenis_output='RELEASE' GROUP BY s.tbatch_uuid,v.box_kg")->result();foreach($rq as $r)$releaseKg+=(float)$r->qty*(float)$r->box_kg;
            $out->release_kg=$releaseKg;
        }
        $q=$this->db->query("SELECT COALESCE(SUM(berat),0) kg FROM t_badpro WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL")->row();$out->bad_kg=(float)$q->kg;
        if($this->db->table_exists('sortasi_wip')){$q=$this->db->query("SELECT COALESCE(SUM(jumlah_awal-jumlah_terpakai),0) box FROM sortasi_wip WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL AND jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')")->row();$out->sisa_wip_box=(float)$q->box;}
        $out->yield_filkar_pct=$out->mp_total_kg>0?round(($out->filkar_kg/$out->mp_total_kg)*100,2):0;
        $rw=$this->get_rework_analysis($f,$ids);foreach($rw as $k=>$v)$out->$k=$v;
        $out->yield_release_pct=($out->release_kg+$out->bad_kg)>0?round(($out->release_kg/($out->release_kg+$out->bad_kg))*100,2):0;
        $out->plans=$this->get_yield_plan_details($f,$ids);
        foreach($out->plans as $r){$out->pvdc+=(float)$r->pvdc;$out->wire+=(float)$r->wire;$out->onproduk_pvdc+=(float)$r->onproduk_pvdc;$out->reject_pvdc+=(float)$r->reject_pvdc;$out->onproduk_wire+=(float)$r->onproduk_wire;$out->reject_wire+=(float)$r->reject_wire;}
        $out->reject_pvdc_persen=$out->pvdc>0?round(($out->reject_pvdc/$out->pvdc)*100,2):0;
        $out->reject_wire_persen=$out->wire>0?round(($out->reject_wire/$out->wire)*100,2):0;
        return (array)$out;
    }

    /** Detail Yield per Plan Produksi, termasuk pemakaian PVDC & Wire yang diinput per planning. */
    private function get_yield_plan_details($f,$ids)
    {
        if(!$ids)return [];
        $list=implode(',',array_map([$this->db,'escape'],$ids));
        $rows=$this->db->query("SELECT b.uuid,b.t_planning_uuid,tp.tanggal tanggal_plan,tp.varian varian_uuid,v.varian FROM tbatch b JOIN t_planning tp ON tp.uuid=b.t_planning_uuid LEFT JOIN varian v ON v.uuid=tp.varian WHERE b.uuid IN ($list) AND b.deleted_at IS NULL AND tp.deleted_at IS NULL ORDER BY tp.tanggal,v.varian")->result();
        $plans=[];
        foreach($rows as $r){$id=$r->t_planning_uuid;if(!isset($plans[$id]))$plans[$id]=(object)['plan_uuid'=>$id,'tanggal_plan'=>$r->tanggal_plan,'tanggal_plan_display'=>$this->formatTanggal($r->tanggal_plan),'varian'=>$r->varian,'varian_uuid'=>$r->varian_uuid,'jumlah_batch'=>0,'mp_formula_kg'=>0,'mp_rework_kg'=>0,'mp_total_kg'=>0,'filkar_kg'=>0,'yield_filkar_pct'=>0,'release_box'=>0,'release_kg'=>0,'bad_kg'=>0,'yield_release_pct'=>0,'pvdc'=>0,'onproduk_pvdc'=>0,'reject_pvdc'=>0,'reject_pvdc_persen'=>0,'wire'=>0,'onproduk_wire'=>0,'reject_wire'=>0,'reject_wire_persen'=>0];$plans[$id]->jumlah_batch++;}
        $pid=array_keys($plans);$pin=implode(',',array_map([$this->db,'escape'],$pid));
        $mp=$this->db->query("SELECT tb.t_planning_uuid,SUM(mu.formula_kg) formula,SUM(mu.rework_kg) rework,SUM(mu.total_output) total FROM mp_usage mu JOIN tbatch tb ON tb.uuid=mu.tbatch_uuid WHERE tb.uuid IN ($list) GROUP BY tb.t_planning_uuid")->result();foreach($mp as $r){$p=$plans[$r->t_planning_uuid]??null;if($p){$p->mp_formula_kg=(float)$r->formula;$p->mp_rework_kg=(float)$r->rework;$p->mp_total_kg=(float)$r->total;}}
        $q=$this->db->query("SELECT b.t_planning_uuid,SUM(f.jumlah_kg) kg FROM filkar f JOIN tbatch b ON b.uuid=f.tbatch_uuid WHERE b.uuid IN ($list) AND f.deleted_at IS NULL GROUP BY b.t_planning_uuid")->result();foreach($q as $r)if(isset($plans[$r->t_planning_uuid]))$plans[$r->t_planning_uuid]->filkar_kg=(float)$r->kg;
        $q=$this->db->query("SELECT b.t_planning_uuid,SUM(so.jumlah) qty FROM sortasi_output so JOIN sortasi s ON s.uuid=so.sortasi_uuid JOIN tbatch b ON b.uuid=s.tbatch_uuid WHERE b.uuid IN ($list) AND s.deleted_at IS NULL AND so.deleted_at IS NULL AND so.jenis_output='RELEASE' GROUP BY b.t_planning_uuid")->result();foreach($q as $r)if(isset($plans[$r->t_planning_uuid]))$plans[$r->t_planning_uuid]->release_box=(float)$r->qty;
        $q=$this->db->query("SELECT b.t_planning_uuid,SUM(so.jumlah*v.box_kg) kg FROM sortasi_output so JOIN sortasi s ON s.uuid=so.sortasi_uuid JOIN tbatch b ON b.uuid=s.tbatch_uuid JOIN t_planning tp ON tp.uuid=b.t_planning_uuid JOIN varian v ON v.uuid=tp.varian WHERE b.uuid IN ($list) AND s.deleted_at IS NULL AND so.deleted_at IS NULL AND so.jenis_output='RELEASE' GROUP BY b.t_planning_uuid")->result();foreach($q as $r)if(isset($plans[$r->t_planning_uuid]))$plans[$r->t_planning_uuid]->release_kg=(float)$r->kg;
        $q=$this->db->query("SELECT b.t_planning_uuid,SUM(bp.berat) kg FROM t_badpro bp JOIN tbatch b ON b.uuid=bp.tbatch_uuid WHERE b.uuid IN ($list) AND bp.deleted_at IS NULL GROUP BY b.t_planning_uuid")->result();foreach($q as $r)if(isset($plans[$r->t_planning_uuid]))$plans[$r->t_planning_uuid]->bad_kg=(float)$r->kg;
        if($this->db->table_exists('pvdc_wire')){
            $q=$this->db->query("SELECT pw.t_planning_uuid,SUM(pw.pvdc) pvdc,SUM(pw.wire) wire FROM pvdc_wire pw JOIN t_planning tp ON tp.uuid=pw.t_planning_uuid WHERE pw.t_planning_uuid IN ($pin) AND tp.deleted_at IS NULL GROUP BY pw.t_planning_uuid")->result();foreach($q as $r){if(isset($plans[$r->t_planning_uuid])){$plans[$r->t_planning_uuid]->pvdc=(float)$r->pvdc;$plans[$r->t_planning_uuid]->wire=(float)$r->wire;}}
        }
        $formula=[];foreach($this->db->query("SELECT varian_uuid,total FROM m_formula WHERE varian_uuid IN (SELECT DISTINCT varian FROM t_planning WHERE uuid IN ($pin))")->result() as $r)$formula[$r->varian_uuid]=(float)$r->total;
        $variant=[];foreach($this->db->query("SELECT uuid,varian,pvdc_batch,wire_batch,box_kg FROM varian WHERE uuid IN (SELECT DISTINCT varian FROM t_planning WHERE uuid IN ($pin))")->result() as $r)$variant[$r->uuid]=$r;
        foreach($plans as $p){$p->yield_filkar_pct=$p->mp_total_kg>0?round($p->filkar_kg/$p->mp_total_kg*100,2):0;$p->yield_release_pct=($p->release_kg+$p->bad_kg)>0?round($p->release_kg/($p->release_kg+$p->bad_kg)*100,2):0;$p->actual_batch=0;$fm=(float)($formula[$p->varian_uuid]??0);$vf=$variant[$p->varian_uuid]??null;if($vf){$p->formula_kg=$fm;$p->actual_batch=$fm>0?$p->mp_total_kg/$fm:0;$p->onproduk_pvdc=$p->actual_batch*(float)$vf->pvdc_batch;$p->reject_pvdc=$p->pvdc-$p->onproduk_pvdc;$p->reject_pvdc_persen=$p->pvdc>0?round($p->reject_pvdc/$p->pvdc*100,2):0;$p->onproduk_wire=$p->actual_batch*(float)$vf->wire_batch;$p->reject_wire=$p->wire-$p->onproduk_wire;$p->reject_wire_persen=$p->wire>0?round($p->reject_wire/$p->wire*100,2):0;}}
        return array_values($plans);
    }

    /** Performa identik rumus dashboard: counter / target * 100, target = speed * 50 * durasi planning. */
    public function get_machine_performance_full($f)
    {
        // Mesin hanya yang memiliki counter > 0 dalam range/filter.
        $this->db->select('tc.mesin_uuid,m.nama_mesin',false)->from('tcounter tc')->join('tbatch b','b.uuid=tc.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')->join('mesin m','m.uuid=tc.mesin_uuid','inner')->where('tc.deleted_at IS NULL',null,false)->where('tc.counter >',0)->where('b.deleted_at IS NULL',null,false)->where('tp.deleted_at IS NULL',null,false)->where('m.deleted_at IS NULL',null,false);
        if(!empty($f['start']))$this->db->where('tp.tanggal >=',$f['start']); if(!empty($f['end']))$this->db->where('tp.tanggal <',$this->endExclusive($f['end'])); if(!empty($f['varian_uuid']))$this->db->where('tp.varian',$f['varian_uuid']); if(!empty($f['plan_uuid']))$this->db->where('b.t_planning_uuid',$f['plan_uuid']); if(!empty($f['batch_uuid']))$this->db->where('b.uuid',$f['batch_uuid']); if(!empty($f['mesin_uuid']))$this->db->where('tc.mesin_uuid',$f['mesin_uuid']);
        $mesin=$this->db->group_by(['tc.mesin_uuid','m.nama_mesin'])->order_by('m.nama_mesin')->get()->result();

        // Counter per planning + mesin, persis pola dashboard.
        $subCounter=$this->db->select('tb.t_planning_uuid,tc.mesin_uuid,SUM(tc.counter) total_counter',false)->from('tcounter tc')->join('tbatch tb','tb.uuid=tc.tbatch_uuid')->join('t_planning p','p.uuid=tb.t_planning_uuid')->where('tc.deleted_at IS NULL',null,false)->where('tb.deleted_at IS NULL',null,false)->where('p.deleted_at IS NULL',null,false)->where('tc.mesin_uuid IS NOT NULL',null,false)->where('tc.counter >',0);
        if(!empty($f['start']))$this->db->where('p.tanggal >=',$f['start']); if(!empty($f['end']))$this->db->where('p.tanggal <',$this->endExclusive($f['end'])); if(!empty($f['varian_uuid']))$this->db->where('p.varian',$f['varian_uuid']); if(!empty($f['plan_uuid']))$this->db->where('tb.t_planning_uuid',$f['plan_uuid']); if(!empty($f['batch_uuid']))$this->db->where('tb.uuid',$f['batch_uuid']); if(!empty($f['mesin_uuid']))$this->db->where('tc.mesin_uuid',$f['mesin_uuid']);
        $subCounter=$this->db->group_by(['tb.t_planning_uuid','tc.mesin_uuid'])->get_compiled_select();

        // Target per planning + mesin dari t_speed, sama dengan dashboard.
        $subTarget=$this->db->select('s.t_planning_uuid,s.mesin_uuid,SUM(s.speed*50*(TIMESTAMPDIFF(SECOND,p.start,p.end)/3600)) total_target',false)->from('t_speed s')->join('t_planning p','p.uuid=s.t_planning_uuid')->where('s.speed >',0)->where('s.mesin_uuid IS NOT NULL',null,false)->where('s.deleted_at IS NULL',null,false)->where('p.deleted_at IS NULL',null,false);
        if(!empty($f['start']))$this->db->where('p.tanggal >=',$f['start']); if(!empty($f['end']))$this->db->where('p.tanggal <',$this->endExclusive($f['end'])); if(!empty($f['varian_uuid']))$this->db->where('p.varian',$f['varian_uuid']); if(!empty($f['plan_uuid']))$this->db->where('p.uuid',$f['plan_uuid']); if(!empty($f['mesin_uuid']))$this->db->where('s.mesin_uuid',$f['mesin_uuid']);
        $subTarget=$this->db->group_by(['s.t_planning_uuid','s.mesin_uuid'])->get_compiled_select();

        $perf=$this->db->select('tc.mesin_uuid,SUM(tc.total_counter) total_counter,SUM(COALESCE(tg.total_target,0)) total_target',false)->from("($subCounter) tc")->join("($subTarget) tg",'tg.t_planning_uuid=tc.t_planning_uuid AND tg.mesin_uuid=tc.mesin_uuid','left',false)->group_by('tc.mesin_uuid')->get()->result();
        $pmap=[];foreach($perf as $p)$pmap[$p->mesin_uuid]=$p;
        $bad=$this->badMachineRanking($f);$bmap=[];foreach($bad as $b)$bmap[$b->mesin_uuid]=$b;
        foreach($mesin as $m){$p=$pmap[$m->mesin_uuid]??null;$m->total_counter=$p?(float)$p->total_counter:0;$m->total_target=$p?(float)$p->total_target:0;$m->performance_pct=$m->total_target>0?round(($m->total_counter/$m->total_target)*100,2):0;$m->jumlah_batch=$this->countMachineBatches($f,$m->mesin_uuid);$m->avg_speed=0;$m->master_speed=0;$m->bad_kg=isset($bmap[$m->mesin_uuid])?(float)$bmap[$m->mesin_uuid]->total_kg:0;$m->bad_per_counter=$m->total_counter>0?round($m->bad_kg/$m->total_counter,6):0;}
        return ['machines'=>$mesin,'bad_by_machine'=>$this->badByMachineProduct($f)];
    }

    private function countMachineBatches($f,$mesinUuid)
    {
        $this->db->select('COUNT(DISTINCT b.uuid) n',false)->from('tcounter tc')->join('tbatch b','b.uuid=tc.tbatch_uuid')->join('t_planning tp','tp.uuid=b.t_planning_uuid')->where('tc.mesin_uuid',$mesinUuid)->where('tc.deleted_at IS NULL',null,false)->where('b.deleted_at IS NULL',null,false)->where('tp.deleted_at IS NULL',null,false)->where('tc.counter >',0);if(!empty($f['start']))$this->db->where('tp.tanggal >=',$f['start']);if(!empty($f['end']))$this->db->where('tp.tanggal <',$this->endExclusive($f['end']));if(!empty($f['varian_uuid']))$this->db->where('tp.varian',$f['varian_uuid']);if(!empty($f['plan_uuid']))$this->db->where('b.t_planning_uuid',$f['plan_uuid']);if(!empty($f['batch_uuid']))$this->db->where('b.uuid',$f['batch_uuid']);return (int)$this->db->get()->row()->n;
    }

    /**
     * Pergerakan rework hanya di dalam range/filter analisa.
     * Tidak membawa saldo/stok dari transaksi sebelum range.
     *
     * Bad Rework  = t_badpro kategori Rework (1)
     * Hasil Kupas  = rwk_kupas.berat
     * Terpakai     = rwk_pakai.dipakai melalui mp_usage.tbatch_uuid
     * Belum Kupas  = Bad Rework periode - Hasil Kupas periode
     * Sisa Kupas   = Hasil Kupas periode - Terpakai periode
     */
    public function get_rework_analysis($f, $batchIds = [])
    {
        $ids = $batchIds;
        if (!$ids) {
            $this->baseBatch($f);
            $rows = $this->db->select('b.uuid')->get()->result();
            $ids = array_map(function($x){ return $x->uuid; }, $rows);
        }
        if (!$ids) {
            return ['bad_rework_kg'=>0,'hasil_kupas_kg'=>0,'belum_kupas_kg'=>0,'terpakai_kg'=>0,'sisa_kupas_kg'=>0];
        }
        $list = implode(',', array_map([$this->db,'escape'], $ids));

        $sql="SELECT COALESCE(SUM(bp.berat),0) kg
            FROM t_badpro bp
            WHERE bp.tbatch_uuid IN ($list)
              AND bp.deleted_at IS NULL
              AND bp.kategori=1";
        $params=[];
        if(!empty($f['start'])){$sql.=' AND bp.created_at >= ?';$params[]=$f['start'].' 00:00:00';}
        if(!empty($f['end'])){$sql.=' AND bp.created_at < ?';$params[]=$this->endExclusive($f['end']).' 00:00:00';}
        $q=$this->db->query($sql,$params)->row();
        $bad = (float)($q->kg ?? 0);

        $sql="SELECT COALESCE(SUM(k.berat),0) kg
            FROM rwk_kupas k
            WHERE k.tbatch_uuid IN ($list)
              AND k.deleted_at IS NULL";
        $params=[];
        if(!empty($f['start'])){$sql.=' AND k.created_at >= ?';$params[]=$f['start'].' 00:00:00';}
        if(!empty($f['end'])){$sql.=' AND k.created_at < ?';$params[]=$this->endExclusive($f['end']).' 00:00:00';}
        $q=$this->db->query($sql,$params)->row();
        $kupas = (float)($q->kg ?? 0);

        $sql="SELECT COALESCE(SUM(p.dipakai),0) kg
            FROM rwk_pakai p
            JOIN mp_usage mu ON mu.uuid=p.mp_usage_uuid
            WHERE mu.tbatch_uuid IN ($list)
              AND p.deleted_at IS NULL";
        $params=[];
        if(!empty($f['start'])){$sql.=' AND p.created_at >= ?';$params[]=$f['start'].' 00:00:00';}
        if(!empty($f['end'])){$sql.=' AND p.created_at < ?';$params[]=$this->endExclusive($f['end']).' 00:00:00';}
        $q=$this->db->query($sql,$params)->row();
        $pakai = (float)($q->kg ?? 0);

        return [
            'bad_rework_kg' => round($bad,3),
            'hasil_kupas_kg' => round($kupas,3),
            'belum_kupas_kg' => round(max(0,$bad-$kupas),3),
            'terpakai_kg' => round($pakai,3),
            'sisa_kupas_kg' => round(max(0,$kupas-$pakai),3),
        ];
    }

    private function get_rework_by_batch($ids,$f=[])
    {
        if (!$ids) return [];
        $list=implode(',',array_map([$this->db,'escape'],$ids));
        $out=[];
        foreach($this->db->query("SELECT bp.tbatch_uuid,SUM(bp.berat) kg FROM t_badpro bp WHERE bp.tbatch_uuid IN ($list) AND bp.deleted_at IS NULL AND bp.kategori=1" . (!empty($f['start'])?" AND bp.created_at >= ".$this->db->escape($f['start'].' 00:00:00'):'') . (!empty($f['end'])?" AND bp.created_at < ".$this->db->escape($this->endExclusive($f['end']).' 00:00:00'):'') . " GROUP BY bp.tbatch_uuid")->result() as $r)$out[$r->tbatch_uuid]['bad_rework_kg']=(float)$r->kg;
        foreach($this->db->query("SELECT k.tbatch_uuid,SUM(k.berat) kg FROM rwk_kupas k WHERE k.tbatch_uuid IN ($list) AND k.deleted_at IS NULL" . (!empty($f['start'])?" AND k.created_at >= ".$this->db->escape($f['start'].' 00:00:00'):'') . (!empty($f['end'])?" AND k.created_at < ".$this->db->escape($this->endExclusive($f['end']).' 00:00:00'):'') . " GROUP BY k.tbatch_uuid")->result() as $r)$out[$r->tbatch_uuid]['hasil_kupas_kg']=(float)$r->kg;
        foreach($this->db->query("SELECT mu.tbatch_uuid,SUM(p.dipakai) kg FROM rwk_pakai p JOIN mp_usage mu ON mu.uuid=p.mp_usage_uuid WHERE mu.tbatch_uuid IN ($list) AND p.deleted_at IS NULL" . (!empty($f['start'])?" AND p.created_at >= ".$this->db->escape($f['start'].' 00:00:00'):'') . (!empty($f['end'])?" AND p.created_at < ".$this->db->escape($this->endExclusive($f['end']).' 00:00:00'):'') . " GROUP BY mu.tbatch_uuid")->result() as $r)$out[$r->tbatch_uuid]['terpakai_kg']=(float)$r->kg;
        foreach($out as $id=>$v){$bad=(float)($v['bad_rework_kg']??0);$kup=(float)($v['hasil_kupas_kg']??0);$pak=(float)($v['terpakai_kg']??0);$out[$id]['bad_rework_kg']=$bad;$out[$id]['hasil_kupas_kg']=$kup;$out[$id]['terpakai_kg']=$pak;$out[$id]['belum_kupas_kg']=max(0,$bad-$kup);$out[$id]['sisa_kupas_kg']=max(0,$kup-$pak);}
        return $out;
    }

    public function get_journey_analysis($f)
    {
        $this->baseBatch($f);
        $rows=$this->db->select('b.uuid,b.kode_batch,b.tanggal_produksi,tp.uuid plan_uuid,tp.tanggal tanggal_plan,tp.varian varian_uuid,v.varian')
            ->order_by('b.tanggal_produksi')->order_by('b.kode_batch')->get()->result();
        $ids=array_map(function($x){return $x->uuid;},$rows);
        if(!$ids)return ['plans'=>[],'batches'=>[],'summary'=>[]];
        $list=implode(',',array_map([$this->db,'escape'],$ids));

        $mp=[];
        foreach($this->db->query("SELECT tbatch_uuid,SUM(formula_kg) formula,SUM(rework_kg) rework,SUM(total_output) total FROM mp_usage WHERE tbatch_uuid IN ($list) GROUP BY tbatch_uuid")->result() as $r)$mp[$r->tbatch_uuid]=$r;
        $co=[];
        foreach($this->db->query("SELECT tbatch_uuid,SUM(counter) counter FROM tcounter WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL GROUP BY tbatch_uuid")->result() as $r)$co[$r->tbatch_uuid]=$r;
        $fi=[];
        foreach($this->db->query("SELECT tbatch_uuid,SUM(jumlah_kg) kg,SUM(jumlah_box) box FROM filkar WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL GROUP BY tbatch_uuid")->result() as $r)$fi[$r->tbatch_uuid]=$r;

        $so=[];
        if($this->db->table_exists('sortasi_output')){
            foreach($this->db->query("SELECT s.tbatch_uuid,so.jenis_output,SUM(so.jumlah) jumlah FROM sortasi_output so JOIN sortasi s ON s.uuid=so.sortasi_uuid WHERE s.tbatch_uuid IN ($list) AND s.deleted_at IS NULL AND so.deleted_at IS NULL GROUP BY s.tbatch_uuid,so.jenis_output")->result() as $r)$so[$r->tbatch_uuid][$r->jenis_output]=(float)$r->jumlah;
        }
        $bad=[];
        foreach($this->db->query("SELECT tbatch_uuid,SUM(berat) kg FROM t_badpro WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL GROUP BY tbatch_uuid")->result() as $r)$bad[$r->tbatch_uuid]=(float)$r->kg;
        $wip=[];
        if($this->db->table_exists('sortasi_wip')){
            foreach($this->db->query("SELECT tbatch_uuid,SUM(jumlah_awal-jumlah_terpakai) box FROM sortasi_wip WHERE tbatch_uuid IN ($list) AND deleted_at IS NULL AND jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR') GROUP BY tbatch_uuid")->result() as $r)$wip[$r->tbatch_uuid]=(float)$r->box;
        }
        $cuci=[];
        if($this->db->table_exists('t_cuci_detail') && $this->db->table_exists('t_cuci')){
            foreach($this->db->query("SELECT cd.tbatch_uuid,SUM(cd.jumlah_box) jumlah,tc.kode_batch_hasil FROM t_cuci_detail cd JOIN t_cuci tc ON tc.uuid=cd.t_cuci_uuid WHERE cd.tbatch_uuid IN ($list) AND cd.deleted_at IS NULL GROUP BY cd.tbatch_uuid,tc.kode_batch_hasil")->result() as $r)$cuci[$r->tbatch_uuid][]=$r;
        }
        $rework=$this->get_rework_by_batch($ids,$f);

        foreach($rows as $r){
            $id=$r->uuid;
            $r->mp_formula_kg=(float)($mp[$id]->formula??0); $r->mp_rework_kg=(float)($mp[$id]->rework??0); $r->mp_total_kg=(float)($mp[$id]->total??0);
            $r->counter=(int)($co[$id]->counter??0); $r->filkar_kg=(float)($fi[$id]->kg??0); $r->filkar_box=(float)($fi[$id]->box??0);
            $r->sortasi_input_box=(float)$this->sortasiInput($id);
            $r->release_box=(float)($so[$id]['RELEASE']??0); $r->tampung_box=(float)($so[$id]['TAMPUNG']??0); $r->kasar_box=(float)($so[$id]['KASAR']??0); $r->cuci_box=(float)($so[$id]['CUCI']??0);
            $r->bad_kg=(float)($bad[$id]??0); $r->sisa_wip_box=(float)($wip[$id]??0);
            $rw=$rework[$id]??[]; $r->bad_rework_kg=(float)($rw['bad_rework_kg']??0); $r->hasil_kupas_kg=(float)($rw['hasil_kupas_kg']??0); $r->belum_kupas_kg=(float)($rw['belum_kupas_kg']??0); $r->terpakai_rework_kg=(float)($rw['terpakai_kg']??0); $r->sisa_kupas_kg=(float)($rw['sisa_kupas_kg']??0);
            $r->batch_hasil_cuci='';
            if(isset($cuci[$id]))$r->batch_hasil_cuci=implode(', ',array_unique(array_map(function($x){return $x->kode_batch_hasil;},$cuci[$id])));
            $r->tanggal_produksi_display=$this->formatTanggal($r->tanggal_produksi); $r->tanggal_plan_display=$this->formatTanggal($r->tanggal_plan);
        }
        $planMap=[];
        foreach($rows as $r){
            $key=$r->plan_uuid;
            if(!isset($planMap[$key]))$planMap[$key]=(object)['plan_uuid'=>$key,'tanggal_plan'=>$r->tanggal_plan,'tanggal_plan_display'=>$r->tanggal_plan_display,'varian'=>$r->varian,'varian_uuid'=>$r->varian_uuid,'jumlah_batch'=>0,'mp_total_kg'=>0,'counter'=>0,'filkar_kg'=>0,'sortasi_input_box'=>0,'bad_kg'=>0,'tampung_box'=>0,'kasar_box'=>0,'cuci_box'=>0,'release_box'=>0,'sisa_wip_box'=>0,'bad_rework_kg'=>0,'hasil_kupas_kg'=>0,'belum_kupas_kg'=>0,'terpakai_rework_kg'=>0,'sisa_kupas_kg'=>0];
            $p=$planMap[$key]; $p->jumlah_batch++;
            foreach(['mp_total_kg','counter','filkar_kg','sortasi_input_box','bad_kg','tampung_box','kasar_box','cuci_box','release_box','sisa_wip_box','bad_rework_kg','hasil_kupas_kg','belum_kupas_kg','terpakai_rework_kg','sisa_kupas_kg'] as $k)$p->$k+=(float)$r->$k;
        }
        return ['plans'=>array_values($planMap),'batches'=>$rows,'summary'=>[]];
    }

    private function sortasiInput($id){$r=$this->db->select('COALESCE(SUM(jumlah_wip),0) n',false)->where('tbatch_uuid',$id)->where('deleted_at IS NULL',null,false)->get('sortasi')->row();return $r?(float)$r->n:0;}
    public function get_badpro_export_data($f){return $this->get_badpro_analysis($f);}
}
