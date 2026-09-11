<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Sortasi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Proses_model');
        $this->load->model('Counter_model');
        //$this->dberetort = $this->load->database('e-retort', TRUE);
    }
    public function rules()
    {
        return [
            [
                'field' => 'tbatch_uuid',
                'label' => 'Kode Batch',
                'rules' => 'required'
            ],
            [
                'field' => 'jumlah_sortir',
                'label' => 'Jumlah Sortir',
                'rules' => 'required'
            ],
            [
                'field' => 'release_box',
                'label' => 'Jumlah Release',
                'rules' => 'required'
            ],
            [
                'field' => 'mulai',
                'label' => 'Mulai',
                'rules' => 'required'
            ],
            [
                'field' => 'selesai',
                'label' => 'Selesai',
                'rules' => 'required'
            ],
            [
                'field' => 'jenis_sortasi_uuid',
                'label' => 'Jenis Sortasi',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_mp',
                'label' => 'Jumlah MP',
                'rules' => 'required'
            ]
        ];
    }
    public function rules_jenis()
    {
        return [
            [
                'field' => 'jenis',
                'label' => 'Jenis Sortasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ],
        ];
    }
    public function get_all()
    {
        // Halaman index Sortasi ditampilkan per BATCH, bukan per transaksi.
        // "WIP" = WIP awal dari Filkar. Sisa WIP diambil dari ledger sortasi_wip.
        $this->ensure_initial_wip_all_batches();
        $sql = "
            SELECT
                b.uuid,
                b.kode_batch,
                b.filkar_box AS wip_awal_box,
                COALESCE(v.varian, '-') AS varian,
                COALESCE(v.box_kg, 0) AS box_kg,
                COALESCE((
                    SELECT SUM(sw.jumlah_awal - sw.jumlah_terpakai)
                    FROM sortasi_wip sw
                    WHERE sw.tbatch_uuid = b.uuid
                      AND sw.deleted_at IS NULL
                      AND sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')
                ), 0) AS sisa_wip_box,
                COALESCE((
                    SELECT SUM(s.jumlah_wip)
                    FROM sortasi s
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                ), 0) AS total_sortasi_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    INNER JOIN sortasi s2 ON s2.uuid = so.sortasi_uuid
                    WHERE s2.tbatch_uuid = b.uuid
                      AND s2.deleted_at IS NULL
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'RELEASE'
                ), 0) AS release_box
            FROM tbatch b
            LEFT JOIN t_planning tp ON tp.uuid = b.t_planning_uuid
            LEFT JOIN varian v ON v.uuid = tp.varian
            WHERE b.deleted_at IS NULL
              AND COALESCE(b.filkar_box, 0) > 0
            ORDER BY b.created_at DESC, b.kode_batch DESC
        ";
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            $row->wip_awal_kg = (float)$row->wip_awal_box * (float)$row->box_kg;
            $row->total_sortasi_kg = (float)$row->total_sortasi_box * (float)$row->box_kg;
            $row->release_kg = (float)$row->release_box * (float)$row->box_kg;
            $row->sisa_wip_kg = (float)$row->sisa_wip_box * (float)$row->box_kg;
        }
        return $rows;
    }
    /**
     * Ringkasan lengkap satu batch untuk halaman detail batch.
     */
    public function get_batch_detail($tbatch_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $sql = "
            SELECT
                b.uuid,
                b.kode_batch,
                b.adonan,
                b.filkar_kg,
                b.filkar_box,
                COALESCE(v.varian, '-') AS varian,
                COALESCE(v.keterangan, '') AS varian_keterangan,
                COALESCE(v.box_kg, 0) AS box_kg,
                COALESCE((
                    SELECT SUM(s.jumlah_wip)
                    FROM sortasi s
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                ), 0) AS total_sortasi_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    INNER JOIN sortasi s ON s.uuid = so.sortasi_uuid
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'RELEASE'
                ), 0) AS release_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    INNER JOIN sortasi s ON s.uuid = so.sortasi_uuid
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'TAMPUNG'
                ), 0) AS tampung_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    INNER JOIN sortasi s ON s.uuid = so.sortasi_uuid
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'KASAR'
                ), 0) AS kasar_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    INNER JOIN sortasi s ON s.uuid = so.sortasi_uuid
                    WHERE s.tbatch_uuid = b.uuid
                      AND s.deleted_at IS NULL
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'CUCI'
                ), 0) AS cuci_box,
                COALESCE((
                    SELECT SUM(tb2.berat)
                    FROM t_badpro tb2
                    WHERE tb2.tbatch_uuid = b.uuid
                      AND tb2.proses_uuid = (SELECT uuid FROM m_proses WHERE kode = 'SORTASI' LIMIT 1)
                      AND tb2.deleted_at IS NULL
                ), 0) AS bad_kg,
                COALESCE((
                    SELECT SUM(sw.jumlah_awal - sw.jumlah_terpakai)
                    FROM sortasi_wip sw
                    WHERE sw.tbatch_uuid = b.uuid
                      AND sw.deleted_at IS NULL
                      AND sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')
                ), 0) AS sisa_wip_box
            FROM tbatch b
            LEFT JOIN t_planning tp ON tp.uuid = b.t_planning_uuid
            LEFT JOIN varian v ON v.uuid = tp.varian
            WHERE b.uuid = ?
              AND b.deleted_at IS NULL
            LIMIT 1
        ";
        $row = $this->db->query($sql, [$tbatch_uuid])->row();
        if (!$row) {
            return null;
        }
        $row->wip_awal_kg = (float)$row->filkar_box * (float)$row->box_kg;
        $row->total_sortasi_kg = (float)$row->total_sortasi_box * (float)$row->box_kg;
        $row->release_kg = (float)$row->release_box * (float)$row->box_kg;
        $row->tampung_kg = (float)$row->tampung_box * (float)$row->box_kg;
        $row->kasar_kg = (float)$row->kasar_box * (float)$row->box_kg;
        $row->cuci_kg = (float)$row->cuci_box * (float)$row->box_kg;
        $row->sisa_wip_kg = (float)$row->sisa_wip_box * (float)$row->box_kg;
        // Neraca material batch.
        $row->neraca_keluar_kg =
            (float)$row->release_kg +
            (float)$row->tampung_kg +
            (float)$row->kasar_kg +
            (float)$row->cuci_kg +
            (float)$row->bad_kg +
            (float)$row->sisa_wip_kg;
        $row->selisih_neraca_kg = (float)$row->wip_awal_kg - (float)$row->neraca_keluar_kg;
        return $row;
    }
    /**
     * Riwayat semua transaksi Sortasi dalam satu batch.
     * Satu baris = satu transaksi Sortasi.
     */
    public function get_sortasi_history_by_batch($tbatch_uuid)
    {
        $sql = "
            SELECT
                s.uuid,
                s.created_at,
                s.jam_mulai,
                s.jam_selesai,
                s.jumlah_wip,
                s.jml_release,
                s.jml_mp,
                s.keterangan,
                COALESCE(js.kode, '-') AS jenis_sortasi_kode,
                COALESCE(js.nama, '-') AS jenis_sortasi_nama,
                COALESCE(u.fullname, '-') AS fullname,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    WHERE so.sortasi_uuid = s.uuid
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'RELEASE'
                ), 0) AS release_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    WHERE so.sortasi_uuid = s.uuid
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'TAMPUNG'
                ), 0) AS tampung_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    WHERE so.sortasi_uuid = s.uuid
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'KASAR'
                ), 0) AS kasar_box,
                COALESCE((
                    SELECT SUM(so.jumlah)
                    FROM sortasi_output so
                    WHERE so.sortasi_uuid = s.uuid
                      AND so.deleted_at IS NULL
                      AND so.jenis_output = 'CUCI'
                ), 0) AS cuci_box,
                COALESCE((
                    SELECT SUM(bp.berat)
                    FROM t_badpro bp
                    WHERE bp.ref_uuid = s.uuid
                      AND bp.deleted_at IS NULL
                ), 0) AS bad_kg
            FROM sortasi s
            LEFT JOIN jenis_sortasi js ON js.uuid = s.jenis_sortasi_uuid
            LEFT JOIN users u ON u.uuid = s.user_uuid
            WHERE s.tbatch_uuid = ?
              AND s.deleted_at IS NULL
            ORDER BY s.created_at ASC
        ";
        $rows = $this->db->query($sql, [$tbatch_uuid])->result();
        $batch = $this->get_batch_detail($tbatch_uuid);
        $boxkg = $batch ? (float)$batch->box_kg : 0;
        foreach ($rows as $row) {
            $row->jumlah_wip_kg = (float)$row->jumlah_wip * $boxkg;
            $row->release_kg = (float)$row->release_box * $boxkg;
            $row->tampung_kg = (float)$row->tampung_box * $boxkg;
            $row->kasar_kg = (float)$row->kasar_box * $boxkg;
            $row->cuci_kg = (float)$row->cuci_box * $boxkg;
            $row->total_output_kg =
                (float)$row->release_kg +
                (float)$row->tampung_kg +
                (float)$row->kasar_kg +
                (float)$row->cuci_kg +
                (float)$row->bad_kg;
        }
        return $rows;
    }
    /**
     * WIP ledger aktif dalam satu batch.
     * Menunjukkan sumber WIP sehingga detail batch tetap bisa ditelusuri.
     */
    public function get_wip_ledger_by_batch($tbatch_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $sql = "
            SELECT
                sw.uuid,
                sw.jenis_wip,
                sw.jumlah_awal,
                sw.jumlah_terpakai,
                (sw.jumlah_awal - sw.jumlah_terpakai) AS sisa_wip,
                sw.satuan,
                sw.created_at,
                sw.source_sortasi_uuid,
                so.jenis_output,
                ss.created_at AS source_created_at
            FROM sortasi_wip sw
            LEFT JOIN sortasi_output so ON so.uuid = sw.sortasi_output_uuid
            LEFT JOIN sortasi ss ON ss.uuid = sw.source_sortasi_uuid
            WHERE sw.tbatch_uuid = ?
              AND sw.deleted_at IS NULL
              AND (sw.jumlah_awal - sw.jumlah_terpakai) > 0
            ORDER BY sw.created_at ASC
        ";
        $rows = $this->db->query($sql, [$tbatch_uuid])->result();
        $batch = $this->get_batch_detail($tbatch_uuid);
        $boxkg = $batch ? (float)$batch->box_kg : 0;
        foreach ($rows as $row) {
            $row->jumlah_awal_kg = (float)$row->jumlah_awal * $boxkg;
            $row->jumlah_terpakai_kg = (float)$row->jumlah_terpakai * $boxkg;
            $row->sisa_wip_kg = (float)$row->sisa_wip * $boxkg;
        }
        return $rows;
    }
    /**
     * Semua Bad Produk dalam satu batch.
     */
    public function get_badpro_by_batch($tbatch_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        $rows = $this->db
            ->select('t_badpro.*, badpro.nama_badpro')
            ->from('t_badpro')
            ->join('badpro', 'badpro.uuid = t_badpro.badpro_uuid', 'left')
            ->where('t_badpro.tbatch_uuid', $tbatch_uuid)
            ->where('t_badpro.proses_uuid', $proses_uuid)
            ->where('t_badpro.deleted_at', NULL)
            ->order_by('t_badpro.created_at', 'ASC')
            ->get()
            ->result();
        foreach ($rows as $row) {
            $row->kategori_nama = ((int)$row->kategori === 1) ? 'Rework' : (((int)$row->kategori === 2) ? 'Reject' : '-');
        }
        return $rows;
    }
    public function get_by_uuid($uuid)
    {
        $this->db->select("
        s.*,
        js.kode AS jenis_sortasi_kode,
        js.nama AS jenis_sortasi_nama,
        tb.kode_batch,
        tb.filkar_box,
        tb.sortasi_box,
        tb.release_box,
        tb.bad_sortasi_rework_kg,
        tb.bad_sortasi_reject_kg,
        v.uuid AS varian_uuid,
        v.varian,
        v.keterangan AS varian_keterangan,
        v.box_kg,
        u.fullname
    ");
        $this->db->from('sortasi s');
        $this->db->join(
            'jenis_sortasi js',
            'js.uuid = s.jenis_sortasi_uuid',
            'left'
        );
        $this->db->join(
            'tbatch tb',
            'tb.uuid = s.tbatch_uuid',
            'left'
        );
        $this->db->join(
            't_planning tp',
            'tp.uuid = tb.t_planning_uuid',
            'left'
        );
        $this->db->join(
            'varian v',
            'v.uuid = tp.varian',
            'left'
        );
        $this->db->join(
            'users u',
            'u.uuid = s.user_uuid',
            'left'
        );
        $this->db->where('s.uuid', $uuid);
        $this->db->where('s.deleted_at IS NULL', NULL, FALSE);
        return $this->db->get()->row();
    }
    private function get_batch_uuid($uuid)
    {
        return $this->db
            ->select('tb.*, v.box_kg')
            ->from('tbatch tb')
            ->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left')
            ->join('varian v', 'v.uuid = tp.varian', 'left')
            ->where('tb.uuid', $uuid)
            ->where('tb.deleted_at', NULL)
            ->get()
            ->row();
    }
    public function get_mesin_batch($tbatch_uuid)
    {
        $this->db->distinct();
        $this->db->select("
        m.uuid,
        m.nama_mesin
    ");
        $this->db->from('tcounter tc');
        $this->db->join(
            'mesin m',
            'm.uuid = tc.mesin_uuid',
            'left'
        );
        $this->db->where('tc.tbatch_uuid', $tbatch_uuid);
        $this->db->where('tc.counter >', 0);
        $this->db->where('tc.deleted_at', NULL);
        $this->db->where('m.deleted_at', NULL);
        $this->db->order_by('m.nama_mesin');
        return $this->db->get()->result();
    }
    public function insert()
    {
        $this->db->trans_begin();
        try {
            $uuid=Uuid::uuid4()->toString();
            $tbatch_uuid=$this->input->post('tbatch_uuid');
            $jenis=$this->input->post('jenis_sortasi_uuid');
            $proses=$this->Proses_model->get_uuid('SORTASI');
            $user=$this->Auth_model->current_user()->uuid;
            $batch=$this->get_batch_uuid($tbatch_uuid);
            if(!$batch || (float)$batch->box_kg<=0) throw new Exception('Data batch atau berat per box tidak valid.');
            if(!$tbatch_uuid || !$jenis) throw new Exception('Batch dan jenis sortasi wajib diisi.');
            $boxkg=(float)$batch->box_kg;
            $wu=$this->input->post('wip_uuid') ?: [];
            $wj=$this->input->post('wip_jumlah') ?: [];
            $input_box=0; $used=[];
            foreach($wu as $i=>$w){
                $q=isset($wj[$i])?(float)$wj[$i]:0; if($q<=0) continue;
                $r=$this->db->where('uuid',$w)->where('tbatch_uuid',$tbatch_uuid)->where('deleted_at IS NULL',NULL,FALSE)->get('sortasi_wip')->row();
                if(!$r) throw new Exception('Data WIP tidak valid.');
                $avail=(float)$r->jumlah_awal-(float)$r->jumlah_terpakai;
                if($q>$avail+0.000001) throw new Exception('Jumlah WIP melebihi WIP tersedia.');
                $input_box+=$q; $used[]=['uuid'=>$w,'jumlah'=>$q];
            }
            if($input_box<=0) throw new Exception('Jumlah WIP yang digunakan harus lebih dari 0.');
            $input_kg=$input_box*$boxkg;
            $release=(float)($this->input->post('release_box')?:0);
            $tampung=(float)($this->input->post('output_tampung')?:0);
            $kasar=(float)($this->input->post('output_kasar')?:0);
            $cuci=(float)($this->input->post('output_cuci')?:0);
            $bp=$this->input->post('badpro_uuid') ?: [];
            $bj=$this->input->post('badpro_berat') ?: [];
            $badkg=0;
            foreach($bp as $i=>$x){ $q=isset($bj[$i])?(float)$bj[$i]:0; if($x && $q>0)$badkg+=$q; }
            $knownkg=($release+$tampung+$kasar+$cuci)*$boxkg+$badkg;
            if($knownkg>$input_kg+0.000001) throw new Exception('Total output dan Bad melebihi WIP yang digunakan.');
            $sisa_kg=max(0,$input_kg-$knownkg);
            $sisa_box=$sisa_kg/$boxkg;
            $this->db->insert('sortasi',[
                'uuid'=>$uuid,'tbatch_uuid'=>$tbatch_uuid,'proses_uuid'=>$proses,
                'jenis_sortasi_uuid'=>$jenis,'jml_release'=>$release,'jumlah_wip'=>$input_box,
                'keterangan'=>$this->input->post('keterangan'),'jam_mulai'=>$this->input->post('mulai'),
                'jam_selesai'=>$this->input->post('selesai'),'jml_mp'=>$this->input->post('jml_mp'),
                'user_uuid'=>$user
            ]);
            if(!$this->db->trans_status()) throw new Exception('Gagal menyimpan data Sortasi.');
            foreach($used as $x){
                $this->db->insert('sortasi_wip_detail',[
                    'uuid'=>Uuid::uuid4()->toString(),'sortasi_uuid'=>$uuid,'sortasi_wip_uuid'=>$x['uuid'],
                    'jumlah'=>$x['jumlah'],'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')
                ]);
                $this->db->set('jumlah_terpakai','jumlah_terpakai + '.$x['jumlah'],FALSE)->where('uuid',$x['uuid'])->update('sortasi_wip');
            }
            foreach(['RELEASE'=>$release,'TAMPUNG'=>$tampung,'KASAR'=>$kasar,'CUCI'=>$cuci] as $type=>$q){
                if($q<=0) continue;
                $ou=Uuid::uuid4()->toString();
                $this->db->insert('sortasi_output',[
                    'uuid'=>$ou,'sortasi_uuid'=>$uuid,'jenis_output'=>$type,'jumlah'=>$q,'satuan'=>'BOX',
                    'keterangan'=>NULL,'created_at'=>date('Y-m-d H:i:s')
                ]);
                if(in_array($type,['TAMPUNG','KASAR'],TRUE)){
                    $this->db->insert('sortasi_wip',[
                        'uuid'=>Uuid::uuid4()->toString(),'tbatch_uuid'=>$tbatch_uuid,'sortasi_output_uuid'=>$ou,
                        'source_sortasi_uuid'=>$uuid,'jenis_wip'=>$type,'jumlah_awal'=>$q,'jumlah_terpakai'=>0,
                        'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')
                    ]);
                }
            }
            if($sisa_box>0.000001){
                $this->db->insert('sortasi_wip',[
                    'uuid'=>Uuid::uuid4()->toString(),'tbatch_uuid'=>$tbatch_uuid,'sortasi_output_uuid'=>NULL,
                    'source_sortasi_uuid'=>$uuid,'jenis_wip'=>'BELUM_SORTIR','jumlah_awal'=>$sisa_box,
                    'jumlah_terpakai'=>0,'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')
                ]);
            }
            $mesins=$this->input->post('mesin_uuid') ?: [];
            foreach($bp as $i=>$bpu){
                if(!$bpu) continue;
                $berat=isset($bj[$i])?(float)$bj[$i]:0; if($berat<=0) continue;
                $master=$this->db->select('kategori')->where('uuid',$bpu)->where('deleted_at IS NULL',NULL,FALSE)->get('badpro')->row();
                if(!$master) throw new Exception('Bad Produk tidak ditemukan.');
                $bu=Uuid::uuid4()->toString();
                $this->db->insert('t_badpro',[
                    'uuid'=>$bu,'tbatch_uuid'=>$tbatch_uuid,'kode_batch'=>$batch->kode_batch,'proses_uuid'=>$proses,
                    'ref_uuid'=>$uuid,'badpro_uuid'=>$bpu,'mesin_uuid'=>NULL,'berat'=>$berat,'kategori'=>$master->kategori,
                    'keterangan'=>'','created_by'=>$user,'created_at'=>date('Y-m-d H:i:s')
                ]);
                foreach(($mesins[$i]??[]) as $m){
                    if(!$m) continue;
                    $this->db->insert('t_badpro_mesin',[
                        'uuid'=>Uuid::uuid4()->toString(),'user_uuid'=>$user,'t_badpro_uuid'=>$bu,'mesin_uuid'=>$m,
                        'created_at'=>date('Y-m-d H:i:s')
                    ]);
                }
            }
            $this->update_total_bad_sortasi($tbatch_uuid);
            $this->update_total_release_batch($tbatch_uuid);
            if(!$this->db->trans_status()) throw new Exception('Gagal menyimpan transaksi Sortasi.');
            $this->db->trans_commit(); return TRUE;
        }catch(Exception $e){$this->db->trans_rollback();log_message('error','Insert Sortasi Error: '.$e->getMessage());return FALSE;}
    }
    public function update($uuid)
    {
        $this->db->trans_begin();
        try{
            $old=$this->get_by_uuid($uuid); if(!$old) throw new Exception('Data Sortasi tidak ditemukan.');
            $oldbatch=$old->tbatch_uuid;
            $tbatch_uuid=$this->input->post('tbatch_uuid');
            $jenis=$this->input->post('jenis_sortasi_uuid');
            $proses=$this->Proses_model->get_uuid('SORTASI');
            $user=$this->Auth_model->current_user()->uuid;
            $batch=$this->get_batch_uuid($tbatch_uuid); if(!$batch || (float)$batch->box_kg<=0) throw new Exception('Data batch atau berat per box tidak valid.');
            $boxkg=(float)$batch->box_kg;
            $generated=$this->db->where('source_sortasi_uuid',$uuid)->where('deleted_at IS NULL',NULL,FALSE)->get('sortasi_wip')->result();
            foreach($generated as $g) if((float)$g->jumlah_terpakai>0) throw new Exception('Sortasi tidak dapat diedit karena WIP hasil transaksi ini sudah digunakan.');
            $details=$this->db->where('sortasi_uuid',$uuid)->get('sortasi_wip_detail')->result();
            foreach($details as $d) $this->db->set('jumlah_terpakai','jumlah_terpakai - '.(float)$d->jumlah,FALSE)->where('uuid',$d->sortasi_wip_uuid)->update('sortasi_wip');
            $this->db->where('sortasi_uuid',$uuid)->delete('sortasi_wip_detail');
            $this->db->where('source_sortasi_uuid',$uuid)->update('sortasi_wip',['deleted_at'=>date('Y-m-d H:i:s')]);
            $this->db->where('sortasi_uuid',$uuid)->update('sortasi_output',['deleted_at'=>date('Y-m-d H:i:s')]);
            $wu=$this->input->post('wip_uuid')?:[];$wj=$this->input->post('wip_jumlah')?:[];$input_box=0;$used=[];
            foreach($wu as $i=>$w){
                $q=isset($wj[$i])?(float)$wj[$i]:0;if($q<=0)continue;
                $r=$this->db->where('uuid',$w)->where('tbatch_uuid',$tbatch_uuid)->where('deleted_at IS NULL',NULL,FALSE)->get('sortasi_wip')->row();
                if(!$r)throw new Exception('Data WIP tidak valid.');
                $avail=(float)$r->jumlah_awal-(float)$r->jumlah_terpakai;
                if($q>$avail+0.000001)throw new Exception('Jumlah WIP melebihi WIP tersedia.');
                $input_box+=$q;$used[]=['uuid'=>$w,'jumlah'=>$q];
            }
            if($input_box<=0)throw new Exception('Jumlah WIP yang digunakan harus lebih dari 0.');
            $input_kg=$input_box*$boxkg;
            $release=(float)($this->input->post('release_box')?:0);$tampung=(float)($this->input->post('output_tampung')?:0);
            $kasar=(float)($this->input->post('output_kasar')?:0);$cuci=(float)($this->input->post('output_cuci')?:0);
            $bp=$this->input->post('badpro_uuid')?:[];$bj=$this->input->post('badpro_berat')?:[];$badkg=0;
            foreach($bp as $i=>$x){$q=isset($bj[$i])?(float)$bj[$i]:0;if($x&&$q>0)$badkg+=$q;}
            $knownkg=($release+$tampung+$kasar+$cuci)*$boxkg+$badkg;
            if($knownkg>$input_kg+0.000001)throw new Exception('Total output dan Bad melebihi WIP yang digunakan.');
            $sisa_box=max(0,($input_kg-$knownkg)/$boxkg);
            $this->db->where('uuid',$uuid)->update('sortasi',[
                'tbatch_uuid'=>$tbatch_uuid,'jenis_sortasi_uuid'=>$jenis,'jml_release'=>$release,'jumlah_wip'=>$input_box,
                'keterangan'=>$this->input->post('keterangan'),'jam_mulai'=>$this->input->post('mulai'),'jam_selesai'=>$this->input->post('selesai'),
                'jml_mp'=>$this->input->post('jml_mp'),'modified_at'=>date('Y-m-d H:i:s')
            ]);
            foreach($used as $x){
                $this->db->insert('sortasi_wip_detail',['uuid'=>Uuid::uuid4()->toString(),'sortasi_uuid'=>$uuid,'sortasi_wip_uuid'=>$x['uuid'],'jumlah'=>$x['jumlah'],'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')]);
                $this->db->set('jumlah_terpakai','jumlah_terpakai + '.$x['jumlah'],FALSE)->where('uuid',$x['uuid'])->update('sortasi_wip');
            }
            foreach(['RELEASE'=>$release,'TAMPUNG'=>$tampung,'KASAR'=>$kasar,'CUCI'=>$cuci] as $type=>$q){
                if($q<=0)continue;$ou=Uuid::uuid4()->toString();
                $this->db->insert('sortasi_output',['uuid'=>$ou,'sortasi_uuid'=>$uuid,'jenis_output'=>$type,'jumlah'=>$q,'satuan'=>'BOX','keterangan'=>NULL,'created_at'=>date('Y-m-d H:i:s')]);
                if(in_array($type,['TAMPUNG','KASAR'],TRUE))$this->db->insert('sortasi_wip',['uuid'=>Uuid::uuid4()->toString(),'tbatch_uuid'=>$tbatch_uuid,'sortasi_output_uuid'=>$ou,'source_sortasi_uuid'=>$uuid,'jenis_wip'=>$type,'jumlah_awal'=>$q,'jumlah_terpakai'=>0,'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')]);
            }
            if($sisa_box>0.000001)$this->db->insert('sortasi_wip',['uuid'=>Uuid::uuid4()->toString(),'tbatch_uuid'=>$tbatch_uuid,'sortasi_output_uuid'=>NULL,'source_sortasi_uuid'=>$uuid,'jenis_wip'=>'BELUM_SORTIR','jumlah_awal'=>$sisa_box,'jumlah_terpakai'=>0,'satuan'=>'BOX','created_at'=>date('Y-m-d H:i:s')]);
            $old_bad_rows = $this->db
                ->select('uuid')
                ->where('ref_uuid', $uuid)
                ->where('proses_uuid', $proses)
                ->where('deleted_at', NULL)
                ->get('t_badpro')
                ->result();
            foreach ($old_bad_rows as $old_bad) {
                $this->db->where('t_badpro_uuid', $old_bad->uuid)
                    ->update('t_badpro_mesin', [
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
            }
            $this->db->where('ref_uuid',$uuid)
                ->where('proses_uuid',$proses)
                ->update('t_badpro',['deleted_at'=>date('Y-m-d H:i:s')]);
            $mesins=$this->input->post('mesin_uuid')?:[];
            foreach($bp as $i=>$bpu){
                if(!$bpu)continue;$berat=isset($bj[$i])?(float)$bj[$i]:0;if($berat<=0)continue;
                $master=$this->db->select('kategori')->where('uuid',$bpu)->where('deleted_at IS NULL',NULL,FALSE)->get('badpro')->row();
                if(!$master)throw new Exception('Bad Produk tidak ditemukan.');
                $bu=Uuid::uuid4()->toString();
                $this->db->insert('t_badpro',['uuid'=>$bu,'tbatch_uuid'=>$tbatch_uuid,'kode_batch'=>$batch->kode_batch,'proses_uuid'=>$proses,'ref_uuid'=>$uuid,'badpro_uuid'=>$bpu,'mesin_uuid'=>NULL,'berat'=>$berat,'kategori'=>$master->kategori,'keterangan'=>'','created_by'=>$user,'created_at'=>date('Y-m-d H:i:s')]);
                foreach(($mesins[$i]??[]) as $m)if($m)$this->db->insert('t_badpro_mesin',['uuid'=>Uuid::uuid4()->toString(),'user_uuid'=>$user,'t_badpro_uuid'=>$bu,'mesin_uuid'=>$m,'created_at'=>date('Y-m-d H:i:s')]);
            }
            $this->update_total_bad_sortasi($oldbatch);$this->update_total_release_batch($oldbatch);
            if($oldbatch!==$tbatch_uuid){$this->update_total_bad_sortasi($tbatch_uuid);$this->update_total_release_batch($tbatch_uuid);}
            if(!$this->db->trans_status())throw new Exception('Gagal mengubah transaksi Sortasi.');
            $this->db->trans_commit();return TRUE;
        }catch(Exception $e){$this->db->trans_rollback();log_message('error','Update Sortasi Error: '.$e->getMessage());return FALSE;}
    }
    private function update_total_release_batch($tbatch_uuid)
    {
        $proses_uuid=$this->Proses_model->get_uuid('SORTASI');
        $row=$this->db->select_sum('jml_release')->where('tbatch_uuid',$tbatch_uuid)->where('proses_uuid',$proses_uuid)->where('deleted_at',NULL)->get('sortasi')->row();
        $this->db->where('uuid',$tbatch_uuid)->update('tbatch',['release_box'=>(float)($row->jml_release??0)]);
    }
    private function update_total_sortasi($tbatch_uuid)
    {
        // total box yang sudah disortasi
        $this->db->select_sum('jml_release');
        $this->db->select_sum('jumlah_wip');
        $this->db->where('tbatch_uuid', $tbatch_uuid);
        $this->db->where('deleted_at', NULL);
        $total = $this->db->get('sortasi')->row();
        // ambil box_kg dari batch
        // $batch = $this->Counter_model->get_batch_uuid($tbatch_uuid);
        $jumlah_box = $total->jml_release ?? 0;
        $wip_box = $total->jumlah_wip ?? 0;
        // $jumlah_kg  = $jumlah_box * ($batch->box_kg ?? 0);
        $this->db->where('uuid', $tbatch_uuid);
        $this->db->update('tbatch', [
            'release_box' => $jumlah_box,
            'sortasi_box' => $wip_box
        ]);
    }
    public function update_total_bad_sortasi($tbatch_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        $this->db->select("
			SUM(CASE WHEN badpro.kategori = 1 THEN t_badpro.berat ELSE 0 END) AS rework,
			SUM(CASE WHEN badpro.kategori = 2 THEN t_badpro.berat ELSE 0 END) AS reject
			");
        $this->db->from('t_badpro');
        $this->db->join('badpro', 'badpro.uuid=t_badpro.badpro_uuid');
        $this->db->where('t_badpro.tbatch_uuid', $tbatch_uuid);
        $this->db->where('t_badpro.proses_uuid', $proses_uuid);
        $this->db->where('t_badpro.deleted_at', NULL);
        $total = $this->db->get()->row();
        $this->db->where('uuid', $tbatch_uuid);
        $this->db->update('tbatch', [
            'bad_sortasi_rework_kg' => $total->rework ?? 0,
            'bad_sortasi_reject_kg' => $total->reject ?? 0,
        ]);
    }
    private function ensure_initial_wip_all_batches()
    {
        $rows=$this->db->select('uuid')->where('deleted_at',NULL)->where('filkar_box >',0)->get('tbatch')->result();
        foreach($rows as $r)$this->ensure_initial_wip($r->uuid);
    }
    public function get_batch()
    {
        $this->ensure_initial_wip_all_batches();
        $this->db->select("
            b.uuid,b.kode_batch,b.adonan,b.filkar_box,
            COALESCE((SELECT SUM(sw.jumlah_awal-sw.jumlah_terpakai) FROM sortasi_wip sw
              WHERE sw.tbatch_uuid=b.uuid AND sw.deleted_at IS NULL
              AND sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')),0) AS sisa_wip,
            v.varian,v.keterangan,v.kontainer_kg,v.box_kg
        ",FALSE)->from('tbatch b')
        ->join('t_planning p','p.uuid=b.t_planning_uuid','left')
        ->join('varian v','v.uuid=p.varian','left')
        ->where('b.deleted_at',NULL)->having('sisa_wip >',0)
        ->order_by('b.created_at','DESC')->order_by('b.kode_batch','DESC');
        return $this->db->get()->result();
    }
    public function get_badpro($proses = null)
    {
        $this->db->select('*, badpro.uuid as uuid_badpro');
        $this->db->from('badpro');
        if ($proses != null) {
            $this->db->join('m_proses', 'm_proses.uuid = badpro.proses_uuid', 'left');
            $this->db->where('m_proses.kode', $proses);
        }
        $this->db->where('badpro.deleted_at', NULL);
        $this->db->order_by('badpro.nama_badpro');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            if ($val->kategori == 1) {
                $val->kategori_nama = 'Rework';
            } elseif ($val->kategori == 2) {
                $val->kategori_nama = 'Reject';
            }
        }
        return $data;
    }
    public function get_badpro_by_ref($ref_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        /*
     * =====================================================
     * AMBIL DATA BAD PRODUK
     * =====================================================
     */
        $this->db->select("
        t_badpro.uuid,
        t_badpro.tbatch_uuid,
        t_badpro.proses_uuid,
        t_badpro.ref_uuid,
        t_badpro.badpro_uuid,
        t_badpro.berat,
        t_badpro.keterangan,
        t_badpro.created_at,
        badpro.nama_badpro,
        badpro.kategori
    ");
        $this->db->from('t_badpro');
        $this->db->join(
            'badpro',
            'badpro.uuid = t_badpro.badpro_uuid',
            'left'
        );
        $this->db->where(
            't_badpro.ref_uuid',
            $ref_uuid
        );
        $this->db->where(
            't_badpro.proses_uuid',
            $proses_uuid
        );
        $this->db->where(
            't_badpro.deleted_at',
            NULL
        );
        $this->db->order_by(
            'badpro.nama_badpro',
            'ASC'
        );
        $rows = $this->db->get()->result();
        /*
     * =====================================================
     * AMBIL MESIN DOMINAN SETIAP BAD PRODUK
     * =====================================================
     */
        foreach ($rows as $r) {
            $this->db->select("
            mesin.uuid,
            mesin.nama_mesin
        ");
            $this->db->from('t_badpro_mesin');
            $this->db->join(
                'mesin',
                'mesin.uuid = t_badpro_mesin.mesin_uuid',
                'left'
            );
            $this->db->where(
                't_badpro_mesin.t_badpro_uuid',
                $r->uuid
            );
            $this->db->where(
                't_badpro_mesin.deleted_at',
                NULL
            );
            $this->db->where(
                'mesin.deleted_at',
                NULL
            );
            $this->db->order_by(
                'mesin.nama_mesin',
                'ASC'
            );
            $mesin = $this->db->get()->result();
            /*
         * Simpan nama mesin dalam bentuk array
         */
            $r->mesin = $mesin;
            /*
         * Untuk tampilan tabel
         */
            $nama_mesin = [];
            foreach ($mesin as $m) {
                $nama_mesin[] = $m->nama_mesin;
            }
            $r->nama_mesin = implode(', ', $nama_mesin);
        }
        return $rows;
    }
    public function get_batch_info($uuid)
    {
        $this->ensure_initial_wip($uuid);
        $this->db->select("tb.uuid,tb.kode_batch,tb.filkar_box,v.box_kg,
          COALESCE((SELECT SUM(sw.jumlah_awal-sw.jumlah_terpakai) FROM sortasi_wip sw
            WHERE sw.tbatch_uuid=tb.uuid AND sw.deleted_at IS NULL
            AND sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')),0) AS sisa_wip",FALSE)
        ->from('tbatch tb')->join('t_planning tp','tp.uuid=tb.t_planning_uuid')
        ->join('varian v','v.uuid=tp.varian')->where('tb.uuid',$uuid);
        $r=$this->db->get()->row();
        if(!$r)return NULL;
        $r->sisa_wip_kg=(float)$r->sisa_wip*(float)$r->box_kg;
        return $r;
    }
    public function delete($uuid)
    {
        $data = $this->get_by_uuid($uuid);
        if (!$data) {
            return false;
        }
        $this->db->trans_begin();
        try {
            $now = date('Y-m-d H:i:s');
            /*
             * WIP hasil Sortasi ini tidak boleh dihapus jika
             * sudah dipakai oleh Sortasi berikutnya.
             */
            $generated_wip = $this->db
                ->where('source_sortasi_uuid', $uuid)
                ->where('deleted_at IS NULL', NULL, FALSE)
                ->get('sortasi_wip')
                ->result();
            foreach ($generated_wip as $wip) {
                if ((float)$wip->jumlah_terpakai > 0) {
                    throw new Exception(
                        'Sortasi tidak dapat dihapus karena WIP hasil transaksi ini sudah digunakan.'
                    );
                }
            }
            /*
        |--------------------------------------------------------------------------
        | 1. KEMBALIKAN WIP YANG DIPAKAI
        |--------------------------------------------------------------------------
        */
            $details = $this->db
                ->where('sortasi_uuid', $uuid)
                ->get('sortasi_wip_detail')
                ->result();
            foreach ($details as $detail) {
                $this->db
                    ->set(
                        'jumlah_terpakai',
                        'jumlah_terpakai - ' .
                            (float) $detail->jumlah,
                        FALSE
                    )
                    ->where(
                        'uuid',
                        $detail->sortasi_wip_uuid
                    )
                    ->update('sortasi_wip');
            }
            /*
        |--------------------------------------------------------------------------
        | 2. SOFT DELETE DETAIL WIP
        |--------------------------------------------------------------------------
        */
            $this->db
                ->where('sortasi_uuid', $uuid)
                ->delete('sortasi_wip_detail');
            /*
        |--------------------------------------------------------------------------
        | 3. SOFT DELETE OUTPUT
        |--------------------------------------------------------------------------
        */
            $this->db
                ->where('sortasi_uuid', $uuid)
                ->update('sortasi_output', [
                    'deleted_at' => $now
                ]);
            /*
        |--------------------------------------------------------------------------
        | 4. WIP HASIL TAMPUNG/KASAR DARI SORTASI INI
        |    JUGA DINONAKTIFKAN
        |--------------------------------------------------------------------------
        */
            $this->db
                ->where('source_sortasi_uuid', $uuid)
                ->update('sortasi_wip', [
                    'deleted_at' => $now
                ]);
            $outputs = $this->db
                ->select('uuid')
                ->where('sortasi_uuid', $uuid)
                ->get('sortasi_output')
                ->result();
            foreach ($outputs as $output) {
                $this->db
                    ->where(
                        'sortasi_output_uuid',
                        $output->uuid
                    )
                    ->update('sortasi_wip', [
                        'deleted_at' => $now
                    ]);
            }
            /*
        |--------------------------------------------------------------------------
        | 5. SOFT DELETE SORTASI
        |--------------------------------------------------------------------------
        */
            $this->db
                ->where('uuid', $uuid)
                ->update('sortasi', [
                    'deleted_at' => $now
                ]);
            /*
        |--------------------------------------------------------------------------
        | 6. SOFT DELETE BAD PRODUK
        |--------------------------------------------------------------------------
        */
            $proses_uuid =
                $this->Proses_model
                ->get_uuid('SORTASI');
            $this->db
                ->where('ref_uuid', $uuid)
                ->where('proses_uuid', $proses_uuid)
                ->update('t_badpro', [
                    'deleted_at' => $now
                ]);
            /*
        |--------------------------------------------------------------------------
        | 7. UPDATE TOTAL BAD
        |--------------------------------------------------------------------------
        */
            $this->update_total_bad_sortasi(
                $data->tbatch_uuid
            );
            if ($this->db->trans_status()) {
                $this->db->trans_commit();
                return true;
            }
            $this->db->trans_rollback();
            return false;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message(
                'error',
                'Delete Sortasi Error: ' .
                    $e->getMessage()
            );
            return false;
        }
    }
    public function get_badpro_summary_by_ref($ref_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        $this->db->select("
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 1
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS rework_kg,
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 2
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS reject_kg,
        COALESCE(
            SUM(t_badpro.berat),
            0
        ) AS total_bad_kg
    ", FALSE);
        $this->db->from('t_badpro');
        $this->db->join(
            'badpro',
            'badpro.uuid = t_badpro.badpro_uuid',
            'left'
        );
        $this->db->where(
            't_badpro.ref_uuid',
            $ref_uuid
        );
        $this->db->where(
            't_badpro.proses_uuid',
            $proses_uuid
        );
        $this->db->where(
            't_badpro.deleted_at',
            NULL
        );
        return $this->db->get()->row();
    }
    public function get_batch_edit($tbatch_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $this->db->select("
            b.uuid,b.kode_batch,b.adonan,b.filkar_box,
            COALESCE((SELECT SUM(sw.jumlah_awal-sw.jumlah_terpakai) FROM sortasi_wip sw
              WHERE sw.tbatch_uuid=b.uuid AND sw.deleted_at IS NULL
              AND sw.jenis_wip IN ('BELUM_SORTIR','TAMPUNG','KASAR')),0) AS sisa_wip,
            v.varian,v.keterangan,v.kontainer_kg,v.box_kg
        ",FALSE)->from('tbatch b')
        ->join('t_planning p','p.uuid=b.t_planning_uuid','left')
        ->join('varian v','v.uuid=p.varian','left')
        ->where('b.deleted_at',NULL);
        $this->db->group_start()->having('sisa_wip >',0)->or_where('b.uuid',$tbatch_uuid)->group_end();
        $this->db->order_by('b.created_at','DESC')->order_by('b.kode_batch','DESC');
        return $this->db->get()->result();
    }
    /*
*=======================================
JENIS SORTASI
*=======================================
*/
    public function get_all_jenis()
    {
        return $this->db->get('jenis_sortasi')->result();
    }
    public function get_jenis_by_uuid($uuid)
    {
        return $this->db->get_where('jenis_sortasi', array('uuid' => $uuid))->row();
    }
    public function insert_jenis()
    {
        $uuid = Uuid::uuid4()->toString();
        $jenis = $this->input->post('jenis');
        $keterangan = $this->input->post('keterangan');
        $data = array(
            'uuid' => $uuid,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'user_uuid'     => $this->auth_model->current_user()->uuid
        );
        $this->db->insert('jenis_sortasi', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    public function update_jenis($uuid)
    {
        $jenis = $this->input->post('jenis');
        $keterangan = $this->input->post('keterangan');
        $data = array(
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'modified_at' => date('Y-m-d h:i:s')
        );
        $this->db->update('jenis_sortasi', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
    }
    public function get_jenis_sortasi()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('jenis_sortasi')
            ->result();
    }
    public function get_wip_batch($tbatch_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $this->db->select("
        sw.uuid,
        sw.tbatch_uuid,
        sw.jenis_wip,
        sw.jumlah_awal,
        sw.jumlah_terpakai,
        (
            sw.jumlah_awal - sw.jumlah_terpakai
        ) AS sisa_wip,
        sw.satuan
    ");
        $this->db->from('sortasi_wip sw');
        $this->db->where(
            'sw.tbatch_uuid',
            $tbatch_uuid
        );
        $this->db->where(
            'sw.deleted_at IS NULL',
            NULL,
            FALSE
        );
        $this->db->having('sisa_wip >', 0);
        $this->db->order_by('sw.created_at', 'ASC');
        return $this->db->get()->result();
    }
    public function get_output_by_sortasi($sortasi_uuid)
    {
        return $this->db
            ->where('sortasi_uuid', $sortasi_uuid)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->order_by('id', 'ASC')
            ->get('sortasi_output')
            ->result();
    }
    private function ensure_initial_wip($tbatch_uuid)
    {
        $exists = $this->db
            ->where('tbatch_uuid', $tbatch_uuid)
            ->where('jenis_wip', 'BELUM_SORTIR')
            ->where('sortasi_output_uuid IS NULL', NULL, FALSE)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->count_all_results('sortasi_wip');
        if ($exists > 0) {
            return;
        }
        $batch = $this->db
            ->select('filkar_box')
            ->where('uuid', $tbatch_uuid)
            ->get('tbatch')
            ->row();
        if (!$batch || $batch->filkar_box <= 0) {
            return;
        }
        $this->db->insert('sortasi_wip', [
            'uuid'          => Uuid::uuid4()->toString(),
            'tbatch_uuid'   => $tbatch_uuid,
            'jenis_wip'     => 'BELUM_SORTIR',
            'jumlah_awal'   => $batch->filkar_box,
            'jumlah_terpakai' => 0,
            'satuan'        => 'BOX'
        ]);
    }
    public function get_wip_for_edit($tbatch_uuid, $sortasi_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $this->db->select("
        sw.uuid,
        sw.jenis_wip,
        sw.jumlah_awal,
        sw.jumlah_terpakai,
        COALESCE(
            (
                SELECT SUM(swd.jumlah)
                FROM sortasi_wip_detail swd
                WHERE swd.sortasi_wip_uuid = sw.uuid
                AND swd.sortasi_uuid = " . $this->db->escape($sortasi_uuid) . "
            ), 0
        ) AS dipakai_edit
    ", FALSE);
        $this->db->from('sortasi_wip sw');
        $this->db->where(
            'sw.tbatch_uuid',
            $tbatch_uuid
        );
        $this->db->group_start();
            $this->db->where('sw.source_sortasi_uuid IS NULL', NULL, FALSE);
            $this->db->or_where('sw.source_sortasi_uuid !=', $sortasi_uuid);
        $this->db->group_end();
        $this->db->where(
            'sw.deleted_at IS NULL',
            NULL,
            FALSE
        );
        $this->db->order_by(
            'sw.created_at',
            'ASC'
        );
        $rows = $this->db->get()->result();
        foreach ($rows as $row) {
            $row->sisa_wip =
                ((float) $row->jumlah_awal
                    -
                    (float) $row->jumlah_terpakai)
                +
                (float) $row->dipakai_edit;
        }
        return $rows;
    }
    /**
 * =========================================================
 * LIST OUTPUT CUCI YANG MASIH MEMILIKI SISA
 * =========================================================
 */
public function get_cuci()
{
    $this->db->select("
        so.uuid AS sortasi_output_uuid,
        so.sortasi_uuid,
        so.jumlah AS jumlah_output,
        COALESCE((
            SELECT SUM(cd.jumlah_box)
            FROM t_cuci_detail cd
            WHERE cd.sortasi_uuid = so.sortasi_uuid
              AND cd.deleted_at IS NULL
        ), 0) AS sudah_dicuci,
        (
            so.jumlah -
            COALESCE((
                SELECT SUM(cd.jumlah_box)
                FROM t_cuci_detail cd
                WHERE cd.sortasi_uuid = so.sortasi_uuid
                  AND cd.deleted_at IS NULL
            ), 0)
        ) AS sisa_belum_dicuci,
        tb.uuid AS tbatch_uuid,
        tb.kode_batch,
        v.uuid AS varian_uuid,
        v.varian,
        v.box_kg,
        s.created_at AS sortasi_created_at
    ", FALSE);
    $this->db->from('sortasi_output so');
    $this->db->join(
        'sortasi s',
        's.uuid = so.sortasi_uuid',
        'left'
    );
    $this->db->join(
        'tbatch tb',
        'tb.uuid = s.tbatch_uuid',
        'left'
    );
    $this->db->join(
        't_planning tp',
        'tp.uuid = tb.t_planning_uuid',
        'left'
    );
    $this->db->join(
        'varian v',
        'v.uuid = tp.varian',
        'left'
    );
    $this->db->where('so.jenis_output', 'CUCI');
    $this->db->where('so.deleted_at IS NULL', NULL, FALSE);
    $this->db->where('s.deleted_at IS NULL', NULL, FALSE);
    $this->db->where('tb.deleted_at IS NULL', NULL, FALSE);
    /*
     * Hanya tampilkan yang masih ada sisa untuk dicuci
     */
    $this->db->having('sisa_belum_dicuci >', 0);
    $this->db->order_by('so.created_at', 'DESC');
    return $this->db->get()->result();
}
/**
 * =========================================================
 * DETAIL SUMBER CUCI
 * =========================================================
 */
public function get_cuci_detail($sortasi_uuid)
{
    $this->db->select("
        so.uuid AS sortasi_output_uuid,
        so.sortasi_uuid,
        so.jumlah AS jumlah_output,
        COALESCE((
            SELECT SUM(cd.jumlah_box)
            FROM t_cuci_detail cd
            WHERE cd.sortasi_uuid = so.sortasi_uuid
              AND cd.deleted_at IS NULL
        ), 0) AS sudah_dicuci,
        (
            so.jumlah -
            COALESCE((
                SELECT SUM(cd.jumlah_box)
                FROM t_cuci_detail cd
                WHERE cd.sortasi_uuid = so.sortasi_uuid
                  AND cd.deleted_at IS NULL
            ), 0)
        ) AS sisa_belum_dicuci,
        tb.uuid AS tbatch_uuid,
        tb.kode_batch,
        v.uuid AS varian_uuid,
        v.varian,
        v.keterangan,
        v.box_kg,
        tp.uuid AS t_planning_uuid
    ", FALSE);
    $this->db->from('sortasi_output so');
    $this->db->join(
        'sortasi s',
        's.uuid = so.sortasi_uuid',
        'left'
    );
    $this->db->join(
        'tbatch tb',
        'tb.uuid = s.tbatch_uuid',
        'left'
    );
    $this->db->join(
        't_planning tp',
        'tp.uuid = tb.t_planning_uuid',
        'left'
    );
    $this->db->join(
        'varian v',
        'v.uuid = tp.varian',
        'left'
    );
    $this->db->where(
        'so.sortasi_uuid',
        $sortasi_uuid
    );
    $this->db->where(
        'so.jenis_output',
        'CUCI'
    );
    $this->db->where(
        'so.deleted_at IS NULL',
        NULL,
        FALSE
    );
    return $this->db
        ->get()
        ->row();
}
/**
 * =========================================================
 * INSERT CUCI
 * =========================================================
 */
public function insert_cuci()
{
    $this->db->trans_begin();
    try {
        $user =
            $this->Auth_model
                ->current_user();
        $items =
            $this->input
                ->post('items', TRUE);
        $varian_uuid =
            $this->input
                ->post('varian_uuid', TRUE);
        $total_box =
            (int) $this->input
                ->post('total_box', TRUE);
        $kode_batch_hasil =
            trim(
                $this->input
                    ->post(
                        'kode_batch_hasil',
                        TRUE
                    )
            );
        $keterangan =
            trim(
                $this->input
                    ->post(
                        'keterangan',
                        TRUE
                    )
            );
        /*
         * -----------------------------------------------------
         * VALIDASI DASAR
         * -----------------------------------------------------
         */
        if (!$varian_uuid) {
            throw new Exception(
                'Varian wajib dipilih.'
            );
        }
        if ($total_box <= 0) {
            throw new Exception(
                'Jumlah Cuci harus lebih dari 0.'
            );
        }
        if (!$kode_batch_hasil) {
            throw new Exception(
                'Kode batch hasil Cuci wajib diisi.'
            );
        }
        if (
            empty($items) ||
            !is_array($items)
        ) {
            throw new Exception(
                'Sumber Cuci belum dipilih.'
            );
        }
        /*
         * -----------------------------------------------------
         * CEK KODE BATCH
         * -----------------------------------------------------
         */
        $cek_batch =
            $this->db
                ->where(
                    'kode_batch',
                    $kode_batch_hasil
                )
                ->where(
                    'deleted_at IS NULL',
                    NULL,
                    FALSE
                )
                ->get('tbatch')
                ->row();
        if ($cek_batch) {
            throw new Exception(
                'Kode batch ' .
                $kode_batch_hasil .
                ' sudah digunakan.'
            );
        }
        /*
         * -----------------------------------------------------
         * VALIDASI ITEM
         * -----------------------------------------------------
         */
        $detail = [];
        $total_detail = 0;
        foreach ($items as $item) {
            if (
                empty($item['check'])
            ) {
                continue;
            }
            $sortasi_uuid =
                isset($item['sortasi_uuid'])
                    ? trim($item['sortasi_uuid'])
                    : '';
            $tbatch_uuid =
                isset($item['tbatch_uuid'])
                    ? trim($item['tbatch_uuid'])
                    : '';
            $jumlah =
                isset($item['jumlah'])
                    ? (int) $item['jumlah']
                    : 0;
            if (
                !$sortasi_uuid ||
                !$tbatch_uuid ||
                $jumlah <= 0
            ) {
                continue;
            }
            /*
             * Ambil output CUCI
             */
            $source =
                $this->get_cuci_detail(
                    $sortasi_uuid
                );
            if (!$source) {
                throw new Exception(
                    'Sumber output Cuci tidak ditemukan.'
                );
            }
            /*
             * Pastikan varian sesuai
             */
            if (
                $source->varian_uuid
                != $varian_uuid
            ) {
                throw new Exception(
                    'Varian sumber Cuci tidak sesuai.'
                );
            }
            /*
             * Pastikan batch sesuai
             */
            if (
                $source->tbatch_uuid
                != $tbatch_uuid
            ) {
                throw new Exception(
                    'Batch sumber Cuci tidak valid.'
                );
            }
            /*
             * Pastikan jumlah tidak melebihi sisa
             */
            $sisa =
                (float)
                $source->sisa_belum_dicuci;
            if (
                $jumlah >
                $sisa
            ) {
                throw new Exception(
                    'Jumlah Cuci batch ' .
                    $source->kode_batch .
                    ' melebihi sisa.'
                );
            }
            $detail[] = [
                'sortasi_uuid' =>
                    $source->sortasi_uuid,
                'tbatch_uuid' =>
                    $source->tbatch_uuid,
                'jumlah_box' =>
                    $jumlah,
                'varian_uuid' =>
                    $source->varian_uuid
            ];
            $total_detail += $jumlah;
        }
        if (
            $total_detail !=
            $total_box
        ) {
            throw new Exception(
                'Total Cuci tidak sama dengan total detail.'
            );
        }
        if (empty($detail)) {
            throw new Exception(
                'Tidak ada sumber Cuci yang dipilih.'
            );
        }
        /*
         * -----------------------------------------------------
         * AMBIL PLANNING DARI SUMBER PERTAMA
         * -----------------------------------------------------
         */
        $source_first =
            $this->get_cuci_detail(
                $detail[0]['sortasi_uuid']
            );
        if (!$source_first) {
            throw new Exception(
                'Data planning sumber tidak ditemukan.'
            );
        }
        /*
         * -----------------------------------------------------
         * BUAT BATCH HASIL
         * -----------------------------------------------------
         */
        $batch_uuid_hasil =
            Uuid::uuid4()
                ->toString();
        $this->db->insert(
            'tbatch',
            [
                'uuid' =>
                    $batch_uuid_hasil,
                'user_uuid' =>
                    $user->uuid,
                'username' =>
                    isset($user->username)
                        ? $user->username
                        : $user->fullname,
                't_planning_uuid' =>
                    $source_first
                        ->t_planning_uuid,
                'batch_ke' =>
                    0,
                'kode_batch' =>
                    $kode_batch_hasil,
                'tanggal_produksi' =>
                    date('Y-m-d'),
                'total' =>
                    $total_box,
                'adonan' =>
                    NULL,
                'rework_used' =>
                    NULL,
                'filkar_kg' =>
                    NULL,
                'filkar_box' =>
                    NULL,
                'sortasi_box' =>
                    NULL,
                'release_box' =>
                    NULL,
                'bad_filkar_rework_kg' =>
                    0,
                'bad_filkar_reject_kg' =>
                    0,
                'bad_sortasi_rework_kg' =>
                    NULL,
                'bad_sortasi_reject_kg' =>
                    NULL,
                'created_at' =>
                    date('Y-m-d H:i:s'),
                'modified_at' =>
                    date('Y-m-d H:i:s')
            ]
        );
        /*
         * -----------------------------------------------------
         * HEADER CUCI
         * -----------------------------------------------------
         */
        $cuci_uuid =
            Uuid::uuid4()
                ->toString();
        $this->db->insert(
            't_cuci',
            [
                'uuid' =>
                    $cuci_uuid,
                'varian_uuid' =>
                    $varian_uuid,
                'kode_batch_hasil' =>
                    $kode_batch_hasil,
                'tbatch_uuid_hasil' =>
                    $batch_uuid_hasil,
                'jumlah_box_hasil' =>
                    $total_box,
                'status' =>
                    1,
                'keterangan' =>
                    $keterangan,
                'user_uuid' =>
                    $user->uuid,
                'created_at' =>
                    date('Y-m-d H:i:s'),
                'modified_at' =>
                    date('Y-m-d H:i:s')
            ]
        );
        /*
         * -----------------------------------------------------
         * DETAIL CUCI
         * -----------------------------------------------------
         */
        foreach ($detail as $row) {
            $this->db->insert(
                't_cuci_detail',
                [
                    'uuid' =>
                        Uuid::uuid4()
                            ->toString(),
                    't_cuci_uuid' =>
                        $cuci_uuid,
                    'sortasi_uuid' =>
                        $row['sortasi_uuid'],
                    'tbatch_uuid' =>
                        $row['tbatch_uuid'],
                    'jumlah_box' =>
                        $row['jumlah_box'],
                    'varian_uuid' =>
                        $row['varian_uuid'],
                    'created_at' =>
                        date('Y-m-d H:i:s'),
                    'modified_at' =>
                        date('Y-m-d H:i:s')
                ]
            );
        }
        /*
         * -----------------------------------------------------
         * BUAT WIP BATCH HASIL CUCI
         * -----------------------------------------------------
         */
        $this->db->insert(
            'sortasi_wip',
            [
                'uuid' =>
                    Uuid::uuid4()
                        ->toString(),
                'tbatch_uuid' =>
                    $batch_uuid_hasil,
                'sortasi_output_uuid' =>
                    NULL,
                'jenis_wip' =>
                    'BELUM_SORTIR',
                'jumlah_awal' =>
                    $total_box,
                'jumlah_terpakai' =>
                    0,
                'satuan' =>
                    'BOX',
                'created_at' =>
                    date('Y-m-d H:i:s'),
                'modified_at' =>
                    date('Y-m-d H:i:s')
            ]
        );
        /*
         * -----------------------------------------------------
         * CEK TRANSAKSI
         * -----------------------------------------------------
         */
        if (
            !$this->db
                ->trans_status()
        ) {
            throw new Exception(
                'Gagal menyimpan transaksi Cuci.'
            );
        }
        $this->db
            ->trans_commit();
        return [
            'status' =>
                TRUE,
            'message' =>
                'Data Cuci berhasil disimpan.'
        ];
    } catch (Exception $e) {
        $this->db
            ->trans_rollback();
        log_message(
            'error',
            'Insert Cuci Error: ' .
            $e->getMessage()
        );
        return [
            'status' =>
                FALSE,
            'message' =>
                $e->getMessage()
        ];
    }
}
public function get_cuci_by_varian($varian_uuid)
{
    $this->db->select("
        so.uuid AS sortasi_output_uuid,
        so.sortasi_uuid,
        so.jumlah AS jumlah_output,
        COALESCE(
            (
                SELECT SUM(cd.jumlah_box)
                FROM t_cuci_detail cd
                WHERE cd.sortasi_uuid = so.sortasi_uuid
                  AND cd.deleted_at IS NULL
            ),
            0
        ) AS sudah_dicuci,
        (
            so.jumlah -
            COALESCE(
                (
                    SELECT SUM(cd.jumlah_box)
                    FROM t_cuci_detail cd
                    WHERE cd.sortasi_uuid = so.sortasi_uuid
                      AND cd.deleted_at IS NULL
                ),
                0
            )
        ) AS sisa_belum_dicuci,
        tb.uuid AS tbatch_uuid,
        tb.kode_batch,
        v.uuid AS varian_uuid,
        v.varian,
        v.keterangan,
        v.box_kg
    ", FALSE);
    $this->db->from('sortasi_output so');
    $this->db->join(
        'sortasi s',
        's.uuid = so.sortasi_uuid',
        'left'
    );
    $this->db->join(
        'tbatch tb',
        'tb.uuid = s.tbatch_uuid',
        'left'
    );
    $this->db->join(
        't_planning tp',
        'tp.uuid = tb.t_planning_uuid',
        'left'
    );
    $this->db->join(
        'varian v',
        'v.uuid = tp.varian',
        'left'
    );
    $this->db->where(
        'so.jenis_output',
        'CUCI'
    );
    $this->db->where(
        'so.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        's.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'tb.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->where(
        'v.uuid',
        $varian_uuid
    );
    $this->db->having(
        'sisa_belum_dicuci >',
        0
    );
    $this->db->order_by(
        'so.created_at',
        'ASC'
    );
    return $this->db
        ->get()
        ->result();
}
public function get_cuci_history()
{
    $this->db->select("
        c.uuid,
        c.varian_uuid,
        c.kode_batch_hasil,
        c.tbatch_uuid_hasil,
        c.jumlah_box_hasil,
        c.status,
        c.keterangan,
        c.created_at,
        v.varian,
        v.keterangan AS varian_keterangan,
        (
            SELECT COUNT(cd.id)
            FROM t_cuci_detail cd
            WHERE cd.t_cuci_uuid = c.uuid
              AND cd.deleted_at IS NULL
        ) AS jumlah_sumber
    ", FALSE);
    $this->db->from('t_cuci c');
    $this->db->join(
        'varian v',
        'v.uuid = c.varian_uuid',
        'left'
    );
    $this->db->where(
        'c.deleted_at IS NULL',
        NULL,
        FALSE
    );
    $this->db->order_by(
        'c.created_at',
        'DESC'
    );
    return $this->db
        ->get()
        ->result();
}
public function get_cuci_by_uuid($uuid)
{
    $this->db->select("
        c.*,
        v.varian,
        v.keterangan AS varian_keterangan
    ");
    $this->db->from('t_cuci c');
    $this->db->join(
        'varian v',
        'v.uuid = c.varian_uuid',
        'left'
    );
    $this->db->where(
        'c.uuid',
        $uuid
    );
    $this->db->where(
        'c.deleted_at IS NULL',
        NULL,
        FALSE
    );
    return $this->db
        ->get()
        ->row();
}
public function get_cuci_details($cuci_uuid)
{
    $this->db->select("
        cd.uuid,
        cd.t_cuci_uuid,
        cd.sortasi_uuid,
        cd.tbatch_uuid,
        cd.jumlah_box,
        cd.varian_uuid,
        tb.kode_batch,
        so.jumlah AS jumlah_output,
        COALESCE((
            SELECT SUM(x.jumlah_box)
            FROM t_cuci_detail x
            WHERE x.sortasi_uuid = cd.sortasi_uuid
              AND x.deleted_at IS NULL
        ), 0) AS total_dicuci
    ", FALSE);
    $this->db->from('t_cuci_detail cd');
    $this->db->join(
        'tbatch tb',
        'tb.uuid = cd.tbatch_uuid',
        'left'
    );
    $this->db->join(
        'sortasi_output so',
        "so.sortasi_uuid = cd.sortasi_uuid
         AND so.jenis_output = 'CUCI'
         AND so.deleted_at IS NULL",
        'left'
    );
    $this->db->where(
        'cd.t_cuci_uuid',
        $cuci_uuid
    );
    $this->db->where(
        'cd.deleted_at IS NULL',
        NULL,
        FALSE
    );
    return $this->db
        ->get()
        ->result();
}
public function cuci_batch_sudah_dipakai($tbatch_uuid)
{
    if (!$tbatch_uuid) {
        return FALSE;
    }
    $row = $this->db
        ->select('COUNT(*) AS total', FALSE)
        ->from('sortasi')
        ->where(
            'tbatch_uuid',
            $tbatch_uuid
        )
        ->where(
            'deleted_at IS NULL',
            NULL,
            FALSE
        )
        ->get()
        ->row();
    return $row && (int)$row->total > 0;
}
public function update_cuci($cuci_uuid)
{
    $this->db->trans_begin();
    try {
        $user =
            $this->Auth_model
                ->current_user();
        /*
         * -----------------------------------------------------
         * AMBIL HEADER
         * -----------------------------------------------------
         */
        $cuci =
            $this->get_cuci_by_uuid(
                $cuci_uuid
            );
        if (!$cuci) {
            throw new Exception(
                'Data Cuci tidak ditemukan.'
            );
        }
        /*
         * Jangan edit jika batch hasil
         * sudah masuk proses Sortasi.
         */
        if (
            $this->cuci_batch_sudah_dipakai(
                $cuci->tbatch_uuid_hasil
            )
        ) {
            throw new Exception(
                'Cuci tidak dapat diedit karena batch hasil sudah digunakan pada proses Sortasi.'
            );
        }
        $items =
            $this->input
                ->post('items', TRUE);
        $varian_uuid =
            $this->input
                ->post('varian_uuid', TRUE);
        $total_box =
            (int) $this->input
                ->post('total_box', TRUE);
        $kode_batch_hasil =
            trim(
                $this->input
                    ->post(
                        'kode_batch_hasil',
                        TRUE
                    )
            );
        $keterangan =
            trim(
                $this->input
                    ->post(
                        'keterangan',
                        TRUE
                    )
            );
        if (!$varian_uuid) {
            throw new Exception(
                'Varian wajib dipilih.'
            );
        }
        if ($total_box <= 0) {
            throw new Exception(
                'Jumlah Cuci harus lebih dari 0.'
            );
        }
        if (!$kode_batch_hasil) {
            throw new Exception(
                'Kode batch wajib diisi.'
            );
        }
        /*
         * -----------------------------------------------------
         * CEK KODE BATCH
         * -----------------------------------------------------
         *
         * Boleh menggunakan kode lama.
         * Yang dicek hanya batch lain.
         */
        $cek_batch =
            $this->db
                ->where(
                    'kode_batch',
                    $kode_batch_hasil
                )
                ->where(
                    'uuid !=',
                    $cuci->tbatch_uuid_hasil
                )
                ->where(
                    'deleted_at IS NULL',
                    NULL,
                    FALSE
                )
                ->get('tbatch')
                ->row();
        if ($cek_batch) {
            throw new Exception(
                'Kode batch ' .
                $kode_batch_hasil .
                ' sudah digunakan.'
            );
        }
        /*
         * -----------------------------------------------------
         * DETAIL BARU
         * -----------------------------------------------------
         */
        $detail = [];
        $total_detail = 0;
        foreach ($items as $item) {
            if (
                empty($item['check'])
            ) {
                continue;
            }
            $sortasi_uuid =
                isset($item['sortasi_uuid'])
                    ? trim($item['sortasi_uuid'])
                    : '';
            $tbatch_uuid =
                isset($item['tbatch_uuid'])
                    ? trim($item['tbatch_uuid'])
                    : '';
            $jumlah =
                isset($item['jumlah'])
                    ? (int)$item['jumlah']
                    : 0;
            if (
                !$sortasi_uuid ||
                !$tbatch_uuid ||
                $jumlah <= 0
            ) {
                continue;
            }
            /*
             * Ambil sumber.
             *
             * Di sini total lama masih ikut dihitung.
             * Karena nanti detail lama kita soft delete,
             * maka untuk validasi kita tambahkan kembali
             * jumlah lama dari transaksi ini.
             */
            $source =
                $this->get_cuci_detail(
                    $sortasi_uuid
                );
            if (!$source) {
                throw new Exception(
                    'Sumber Cuci tidak ditemukan.'
                );
            }
            if (
                $source->varian_uuid
                != $varian_uuid
            ) {
                throw new Exception(
                    'Varian sumber Cuci tidak sesuai.'
                );
            }
            if (
                $source->tbatch_uuid
                != $tbatch_uuid
            ) {
                throw new Exception(
                    'Batch sumber Cuci tidak sesuai.'
                );
            }
            /*
             * Cari jumlah lama dari transaksi
             * yang sedang diedit.
             */
            $old_row =
                $this->db
                    ->select(
                        'jumlah_box'
                    )
                    ->where(
                        't_cuci_uuid',
                        $cuci_uuid
                    )
                    ->where(
                        'sortasi_uuid',
                        $sortasi_uuid
                    )
                    ->where(
                        'deleted_at IS NULL',
                        NULL,
                        FALSE
                    )
                    ->get('t_cuci_detail')
                    ->row();
            $old_qty =
                $old_row
                    ? (int)$old_row->jumlah_box
                    : 0;
            /*
             * Sisa sebenarnya untuk transaksi edit.
             */
            $sisa =
                (float)
                $source->sisa_belum_dicuci;
            $sisa += $old_qty;
            if ($jumlah > $sisa) {
                throw new Exception(
                    'Jumlah Cuci batch ' .
                    $source->kode_batch .
                    ' melebihi sisa.'
                );
            }
            $detail[] = [
                'sortasi_uuid' =>
                    $source->sortasi_uuid,
                'tbatch_uuid' =>
                    $source->tbatch_uuid,
                'jumlah_box' =>
                    $jumlah,
                'varian_uuid' =>
                    $source->varian_uuid
            ];
            $total_detail += $jumlah;
        }
        if (
            empty($detail)
        ) {
            throw new Exception(
                'Tidak ada sumber Cuci yang dipilih.'
            );
        }
        if (
            $total_detail !=
            $total_box
        ) {
            throw new Exception(
                'Total Cuci tidak sama dengan total detail.'
            );
        }
        /*
         * -----------------------------------------------------
         * UPDATE HEADER CUCI
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'uuid',
                $cuci_uuid
            )
            ->update(
                't_cuci',
                [
                    'varian_uuid' =>
                        $varian_uuid,
                    'kode_batch_hasil' =>
                        $kode_batch_hasil,
                    'jumlah_box_hasil' =>
                        $total_box,
                    'keterangan' =>
                        $keterangan,
                    'modified_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )
                ]
            );
        /*
         * -----------------------------------------------------
         * UPDATE TBATCH HASIL
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'uuid',
                $cuci->tbatch_uuid_hasil
            )
            ->update(
                'tbatch',
                [
                    'kode_batch' =>
                        $kode_batch_hasil,
                    'total' =>
                        $total_box,
                    'modified_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )
                ]
            );
        /*
         * -----------------------------------------------------
         * SOFT DELETE DETAIL LAMA
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                't_cuci_uuid',
                $cuci_uuid
            )
            ->where(
                'deleted_at IS NULL',
                NULL,
                FALSE
            )
            ->update(
                't_cuci_detail',
                [
                    'deleted_at' =>
                        date(
                            'Y-m-d H:i:s'
                        ),
                    'modified_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )
                ]
            );
        /*
         * -----------------------------------------------------
         * INSERT DETAIL BARU
         * -----------------------------------------------------
         */
        foreach ($detail as $row) {
            $this->db->insert(
                't_cuci_detail',
                [
                    'uuid' =>
                        Uuid::uuid4()
                            ->toString(),
                    't_cuci_uuid' =>
                        $cuci_uuid,
                    'sortasi_uuid' =>
                        $row['sortasi_uuid'],
                    'tbatch_uuid' =>
                        $row['tbatch_uuid'],
                    'jumlah_box' =>
                        $row['jumlah_box'],
                    'varian_uuid' =>
                        $row['varian_uuid'],
                    'created_at' =>
                        date(
                            'Y-m-d H:i:s'
                        ),
                    'modified_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )
                ]
            );
        }
        /*
         * -----------------------------------------------------
         * UPDATE WIP HASIL CUCI
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'tbatch_uuid',
                $cuci->tbatch_uuid_hasil
            )
            ->where(
                'jenis_wip',
                'BELUM_SORTIR'
            )
            ->where(
                'deleted_at IS NULL',
                NULL,
                FALSE
            )
            ->update(
                'sortasi_wip',
                [
                    'jumlah_awal' =>
                        $total_box,
                    'modified_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )
                ]
            );
        if (
            !$this->db
                ->trans_status()
        ) {
            throw new Exception(
                'Gagal mengubah transaksi Cuci.'
            );
        }
        $this->db
            ->trans_commit();
        return [
            'status' =>
                TRUE,
            'message' =>
                'Data Cuci berhasil diubah.'
        ];
    } catch (Exception $e) {
        $this->db
            ->trans_rollback();
        log_message(
            'error',
            'Update Cuci Error: ' .
            $e->getMessage()
        );
        return [
            'status' =>
                FALSE,
            'message' =>
                $e->getMessage()
        ];
    }
}
public function delete_cuci($cuci_uuid)
{
    $this->db->trans_begin();
    try {
        $cuci =
            $this->get_cuci_by_uuid(
                $cuci_uuid
            );
        if (!$cuci) {
            throw new Exception(
                'Data Cuci tidak ditemukan.'
            );
        }
        /*
         * -----------------------------------------------------
         * CEK BATCH HASIL SUDAH DIPAKAI SORTASI
         * -----------------------------------------------------
         */
        if (
            $this->cuci_batch_sudah_dipakai(
                $cuci->tbatch_uuid_hasil
            )
        ) {
            throw new Exception(
                'Cuci tidak dapat dihapus karena batch hasil sudah digunakan pada proses Sortasi.'
            );
        }
        $now =
            date('Y-m-d H:i:s');
        /*
         * -----------------------------------------------------
         * SOFT DELETE DETAIL
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                't_cuci_uuid',
                $cuci_uuid
            )
            ->where(
                'deleted_at IS NULL',
                NULL,
                FALSE
            )
            ->update(
                't_cuci_detail',
                [
                    'deleted_at' =>
                        $now,
                    'modified_at' =>
                        $now
                ]
            );
        /*
         * -----------------------------------------------------
         * SOFT DELETE HEADER
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'uuid',
                $cuci_uuid
            )
            ->update(
                't_cuci',
                [
                    'deleted_at' =>
                        $now,
                    'modified_at' =>
                        $now
                ]
            );
        /*
         * -----------------------------------------------------
         * SOFT DELETE WIP HASIL CUCI
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'tbatch_uuid',
                $cuci->tbatch_uuid_hasil
            )
            ->where(
                'jenis_wip',
                'BELUM_SORTIR'
            )
            ->where(
                'deleted_at IS NULL',
                NULL,
                FALSE
            )
            ->update(
                'sortasi_wip',
                [
                    'deleted_at' =>
                        $now,
                    'modified_at' =>
                        $now
                ]
            );
        /*
         * -----------------------------------------------------
         * SOFT DELETE BATCH HASIL
         * -----------------------------------------------------
         */
        $this->db
            ->where(
                'uuid',
                $cuci->tbatch_uuid_hasil
            )
            ->where(
                'deleted_at IS NULL',
                NULL,
                FALSE
            )
            ->update(
                'tbatch',
                [
                    'deleted_at' =>
                        $now,
                    'modified_at' =>
                        $now
                ]
            );
        if (
            !$this->db
                ->trans_status()
        ) {
            throw new Exception(
                'Gagal menghapus transaksi Cuci.'
            );
        }
        $this->db
            ->trans_commit();
        return [
            'status' =>
                TRUE,
            'message' =>
                'Data Cuci berhasil dihapus.'
        ];
    } catch (Exception $e) {
        $this->db
            ->trans_rollback();
        log_message(
            'error',
            'Delete Cuci Error: ' .
            $e->getMessage()
        );
        return [
            'status' =>
                FALSE,
            'message' =>
                $e->getMessage()
        ];
    }
}
}