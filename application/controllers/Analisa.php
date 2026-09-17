<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Analisa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Analisa_model');
        if (!$this->Auth_model->current_user()) redirect('login');
    }

    public function index()
    {
        $data = ['title'=>'Analisa Produksi','active_nav'=>'analisa'];
        $this->load->view('partials/head-yield',$data);
        $this->load->view('analisa/index',$data);
        $this->load->view('partials/footer');
    }
    private function filters(){
        return [
            'start'=>$this->input->get('start',true), 'end'=>$this->input->get('end',true),
            'varian_uuid'=>$this->input->get('varian_uuid',true),
            'batch_uuid'=>$this->input->get('batch_uuid',true), 'proses_uuid'=>$this->input->get('proses_uuid',true),
            'badpro_uuid'=>$this->input->get('badpro_uuid',true), 'mesin_uuid'=>$this->input->get('mesin_uuid',true)
        ];
    }
    private function json($data,$status=200){
        $this->output->set_status_header($status)->set_content_type('application/json','utf-8')->set_output(json_encode($data));
    }
    public function variants(){ $this->json($this->Analisa_model->get_variants($this->input->get('start',true),$this->input->get('end',true))); }
    public function plans(){ $this->json($this->Analisa_model->get_plans($this->filters())); }
    public function batches(){ $this->json($this->Analisa_model->get_batches($this->filters())); }
    public function processes(){ $this->json($this->Analisa_model->get_processes()); }
    public function machines(){ $this->json($this->Analisa_model->get_machines($this->filters())); }
    public function badproducts(){ $this->json($this->Analisa_model->get_badproducts($this->filters())); }

    public function journey(){
        try { $this->json($this->Analisa_model->get_journey_analysis($this->filters())); }
        catch(Throwable $e){ log_message('error','Analisa journey: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function performance(){
        try { $this->json($this->Analisa_model->get_machine_performance_full($this->filters())); }
        catch(Throwable $e){ log_message('error','Analisa performance: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function badpro(){
        try { $this->json($this->Analisa_model->get_badpro_analysis($this->filters())); }
        catch(Throwable $e){ log_message('error','Analisa badpro: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function badpro_trend(){
        try { $this->json($this->Analisa_model->get_badpro_trend($this->filters())); }
        catch(Throwable $e){ log_message('error','Analisa trend: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function badpro_journey(){
        try { $this->json($this->Analisa_model->get_badpro_journey($this->filters(),$this->input->get('group_by',true)==='batch'?'batch':'plan')); }
        catch(Throwable $e){ log_message('error','Analisa bad journey: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function yield_analysis(){
        try { $this->json($this->Analisa_model->get_yield_analysis_full($this->filters())); }
        catch(Throwable $e){ log_message('error','Analisa yield: '.$e->getMessage()); $this->json(['error'=>$e->getMessage()],500); }
    }
    public function export()
    {
        $f=$this->filters();
        try {
            if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) { show_error('PhpSpreadsheet tidak tersedia.'); return; }
            $ss=new Spreadsheet();
            $ss->removeSheetByIndex(0);
            $write=function($name,$headers,$rows) use (&$ss){
                $sh=$ss->createSheet(); $sh->setTitle(substr($name,0,31));
                $sh->fromArray([$headers],null,'A1'); $r=2;
                foreach($rows as $x){
                    $a=is_object($x)?(array)$x:$x; $vals=[];
                    foreach($headers as $key=>$label){ $vals[]=$a[$key]??''; }
                    $sh->fromArray([$vals],null,'A'.$r++);
                }
                $sh->freezePane('A2');
                foreach(range(1,count($headers)) as $c)$sh->getColumnDimensionByColumn($c)->setWidth(18);
                return $sh;
            };
            $formula=function($title,$rows) use (&$ss){
                $sh=$ss->createSheet(); $sh->setTitle(substr($title,0,31));
                $sh->fromArray([['Rumus / Keterangan']],null,'A1'); $r=2;
                foreach($rows as $x)$sh->fromArray([[$x]],null,'A'.$r++);
                $sh->getColumnDimension('A')->setWidth(100); return $sh;
            };

            // Semua export selalu mencakup keempat mode analisa.
            $j=$this->Analisa_model->get_journey_analysis($f);
            $write('Perjalanan Planning',[
                'tanggal_plan_display'=>'Tanggal Planning','varian'=>'Varian','jumlah_batch'=>'Jumlah Batch','mp_total_kg'=>'MP Total (KG)','counter'=>'Counter','filkar_kg'=>'Filkar (KG)','sortasi_input_box'=>'Sortasi Input (BOX)','bad_kg'=>'Bad Product (KG)','tampung_box'=>'Tampung (BOX)','kasar_box'=>'Kasar (BOX)','cuci_box'=>'Cuci (BOX)','release_box'=>'Release (BOX)','sisa_wip_box'=>'Sisa WIP (BOX)','bad_rework_kg'=>'Bad Rework (KG)','hasil_kupas_kg'=>'Hasil Kupas (KG)','belum_kupas_kg'=>'Belum Kupas (KG)','terpakai_rework_kg'=>'Terpakai Rework (KG)','sisa_kupas_kg'=>'Sisa Hasil Kupas (KG)'
            ],$j['plans']);
            $write('Perjalanan Batch',[
                'tanggal_produksi_display'=>'Tanggal Produksi','varian'=>'Varian','kode_batch'=>'Kode Batch','mp_formula_kg'=>'MP Formula (KG)','mp_rework_kg'=>'MP Rework (KG)','mp_total_kg'=>'MP Total (KG)','counter'=>'Counter','filkar_kg'=>'Filkar (KG)','filkar_box'=>'Filkar (BOX)','sortasi_input_box'=>'Sortasi Input (BOX)','release_box'=>'Release (BOX)','tampung_box'=>'Tampung (BOX)','kasar_box'=>'Kasar (BOX)','cuci_box'=>'Cuci (BOX)','bad_kg'=>'Bad Product (KG)','sisa_wip_box'=>'Sisa WIP (BOX)','bad_rework_kg'=>'Bad Rework (KG)','hasil_kupas_kg'=>'Hasil Kupas (KG)','belum_kupas_kg'=>'Belum Kupas (KG)','terpakai_rework_kg'=>'Terpakai Rework (KG)','sisa_kupas_kg'=>'Sisa Hasil Kupas (KG)','batch_hasil_cuci'=>'Batch Hasil Cuci'
            ],$j['batches']);

            $y=$this->Analisa_model->get_yield_analysis_full($f);
            $write('Yield Ringkasan',[
                'jumlah_planning'=>'Jumlah Planning','jumlah_batch'=>'Jumlah Batch','mp_formula_kg'=>'MP Formula (KG)','mp_rework_kg'=>'MP Rework (KG)','mp_total_kg'=>'MP Total (KG)','filkar_kg'=>'Filkar (KG)','yield_filkar_pct'=>'Yield Filkar (%)','release_box'=>'Release (BOX)','release_kg'=>'Release (KG)','bad_kg'=>'Bad Product (KG)','yield_release_pct'=>'Yield Release (%)','pvdc'=>'PVDC Dipakai (ROLL)','onproduk_pvdc'=>'PVDC Onproduk (ROLL)','reject_pvdc'=>'PVDC Reject (ROLL)','reject_pvdc_persen'=>'PVDC Reject (%)','wire'=>'Wire Dipakai (ROLL)','onproduk_wire'=>'Wire Onproduk (ROLL)','reject_wire'=>'Wire Reject (ROLL)','reject_wire_persen'=>'Wire Reject (%)','bad_rework_kg'=>'Bad Rework (KG)','hasil_kupas_kg'=>'Hasil Kupas (KG)','belum_kupas_kg'=>'Belum Kupas (KG)','terpakai_rework_kg'=>'Terpakai Rework (KG)','sisa_kupas_kg'=>'Sisa Hasil Kupas (KG)'
            ],[$y]);
            $write('Yield Planning',[
                'tanggal_plan_display'=>'Tanggal Planning','varian'=>'Varian','jumlah_batch'=>'Jumlah Batch','mp_formula_kg'=>'MP Formula (KG)','mp_rework_kg'=>'MP Rework (KG)','mp_total_kg'=>'MP Total (KG)','filkar_kg'=>'Filkar (KG)','yield_filkar_pct'=>'Yield Filkar (%)','release_box'=>'Release (BOX)','release_kg'=>'Release (KG)','bad_kg'=>'Bad Product (KG)','yield_release_pct'=>'Yield Release (%)','pvdc'=>'PVDC Dipakai (ROLL)','onproduk_pvdc'=>'PVDC Onproduk','reject_pvdc'=>'PVDC Reject','reject_pvdc_persen'=>'PVDC Reject (%)','wire'=>'Wire Dipakai (ROLL)','onproduk_wire'=>'Wire Onproduk','reject_wire'=>'Wire Reject','reject_wire_persen'=>'Wire Reject (%)'
            ],$y['plans']);
            $formula('Rumus Yield',[
                'Yield Filkar = Filkar KG / Total MP KG × 100%',
                'Yield Release = Release KG / (Release KG + Bad Product KG) × 100%',
                'PVDC Onproduk = Actual Batch × PVDC per Batch',
                'PVDC Reject = Pemakaian PVDC - PVDC Onproduk',
                'PVDC Reject % = PVDC Reject / Pemakaian PVDC × 100%',
                'Wire Onproduk = Actual Batch × Wire per Batch',
                'Wire Reject = Pemakaian Wire - Wire Onproduk',
                'Wire Reject % = Wire Reject / Pemakaian Wire × 100%'
            ]);

            $p=$this->Analisa_model->get_machine_performance_full($f);
            $write('Performa Mesin',['nama_mesin'=>'Mesin','jumlah_batch'=>'Jumlah Batch','total_counter'=>'Counter Aktual','total_target'=>'Target Counter','performance_pct'=>'Performa (%)','bad_kg'=>'Bad Product (KG)','bad_per_counter'=>'Bad / Counter'],$p['machines']);
            $write('Bad Per Mesin',['nama_mesin'=>'Mesin','proses'=>'Proses','nama_badpro'=>'Bad Product','total_kg'=>'Bad Product (KG)'],$p['bad_by_machine']);
            $formula('Rumus Performa Mesin',['Target Counter = Speed × 50 × Durasi Planning (jam)','Performa Mesin = Counter Aktual / Target Counter × 100%']);

            $b=$this->Analisa_model->get_badpro_analysis($f);
            $write('Bad Ranking',['nama_badpro'=>'Bad Product','total_kg'=>'Total KG','jumlah_transaksi'=>'Jumlah Transaksi Bad Product'],$b['ranking_badpro']);
            $write('Bad Trend',['tanggal_display'=>'Tanggal','total_kg'=>'Total Bad Product (KG)'],$b['trend']);
            $write('Ranking Mesin',['nama_mesin'=>'Mesin','total_kg'=>'Total Bad Product (KG)','jumlah_transaksi'=>'Jumlah Transaksi Bad Product','jumlah_batch'=>'Jumlah Batch'],$b['ranking_mesin']);
            $write('Bad Per Planning',['tanggal_plan_display'=>'Tanggal Planning','varian'=>'Varian','jumlah_batch'=>'Jumlah Batch','total_kg'=>'Total Bad Product (KG)'],$b['journey_plan']);
            $write('Bad Per Batch',['tanggal_produksi_display'=>'Tanggal Produksi','varian'=>'Varian','kode_batch'=>'Kode Batch','total_kg'=>'Total Bad Product (KG)'],$b['journey_batch']);
            $write('Bad Detail',['tanggal_display'=>'Tanggal','varian'=>'Varian','proses'=>'Proses','kode_batch'=>'Kode Batch','mesin'=>'Mesin','nama_badpro'=>'Bad Product','kategori'=>'Kategori','berat'=>'Berat (KG)'],$b['detail']);
            $write('Rework',['komponen'=>'Komponen','kg'=>'KG'],[['komponen'=>'Bad Rework','kg'=>$b['rework']['bad_rework_kg']],['komponen'=>'Hasil Kupas','kg'=>$b['rework']['hasil_kupas_kg']],['komponen'=>'Belum Kupas','kg'=>$b['rework']['belum_kupas_kg']],['komponen'=>'Terpakai Rework','kg'=>$b['rework']['terpakai_kg']],['komponen'=>'Sisa Hasil Kupas','kg'=>$b['rework']['sisa_kupas_kg']]]);

            $formula('Informasi Export',['Periode: '.($f['start']?:'-').' s/d '.($f['end']?:'-'),'Filter Varian: '.($f['varian_uuid']?:'Semua'),'Filter Batch: '.($f['batch_uuid']?:'Semua'),'Filter Proses: '.($f['proses_uuid']?:'Semua'),'Filter Bad Product: '.($f['badpro_uuid']?:'Semua'),'Filter Mesin: '.($f['mesin_uuid']?:'Semua'),'Export: seluruh 4 mode analisa']);
            $fn='Analisa_Semua_Mode_'.date('Ymd_His').'.xlsx'; if(ob_get_length())ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); header('Content-Disposition: attachment;filename="'.$fn.'"'); header('Cache-Control:max-age=0');
            (new Xlsx($ss))->save('php://output'); exit;
        } catch(Throwable $e){ log_message('error','Analisa export: '.$e->getMessage()); show_error('Export gagal: '.$e->getMessage(),500); }
    }

}
