<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class Yieldportal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Yield_model');
    }
    public function dashboard()
    {
        $data['title'] = 'Dashboard Yield';
        $data['monitoring_filkar'] =
            $this->Yield_model
            ->get_monitoring_filkar();
            $data['dashboard_mesin'] = $this->Yield_model
        ->get_dashboard_mesin_bulan_berjalan();
        $data['total_filkar'] =
            $this->Yield_model
            ->get_total_filkar();
        $data['monitoring_sortasi'] =
            $this->Yield_model
            ->get_monitoring_sortasi();
        $data['total_sortasi'] =
            $this->Yield_model
            ->get_total_sortasi();
        $data['varian'] = $this->Yield_model->get_varian_yield();
        $data['pvdc'] = $this->Yield_model->get_pvdc_wire();
        $data['bad_produk_varian'] =
            $this->Yield_model->get_bad_produk_varian(
                $data['varian']
            );
        // ==========================================
        // BAD PRODUK PER MESIN
        // revisi berikutnya
        // ==========================================
        $bad_mesin =
            $this->Yield_model
            ->get_bad_produk_mesin_dominan();
        $data['badproduk'] =
            $bad_mesin['badproduk'];
        $data['bad_produk_mesin'] =
            $bad_mesin['rows'];
        $data['total_sortasi_kg'] =
            $bad_mesin['total_sortasi_kg'];
        $this->load->view('dashboard/dashboard-yield', $data);
    }
    public function analisa()
    {
        $data['title'] = 'Analisa Yield';
        $data['filter_options'] = $this->Yield_model->get_analisa_filter_options([
            'tanggal_awal' => date('Y-m-01'),
            'tanggal_akhir' => date('Y-m-d')
        ]);
        $data['active_nav'] = 'yield';
        $this->load->view('partials/head-yield', $data);
        $this->load->view('yield/analisa', $data);
        $this->load->view('partials/footer');
    }
    private function analisa_filter_from_input($source = 'post')
    {
        $get = $source === 'get';
        $read = function ($key) use ($get) {
            return $get ? $this->input->get($key) : $this->input->post($key);
        };
        return [
            'tanggal_awal'  => $read('tanggal_awal'),
            'tanggal_akhir' => $read('tanggal_akhir'),
            'plan'          => $read('plan'),
            'batch'         => $read('batch'),
            'varian'        => $read('varian'),
            'mesin'         => $read('mesin'),
            'badpro'        => $read('badpro')
        ];
    }
    public function ajax_filter_options()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $options = $this->Yield_model->get_analisa_filter_options($this->analisa_filter_from_input());
        $scope = $this->Yield_model->get_scope_summary($this->analisa_filter_from_input());
        echo json_encode(['status' => true, 'options' => $options, 'scope' => $scope]);
    }
    public function ajax_analisa()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
    }
    $filter = $this->analisa_filter_from_input();
    $old_debug = $this->db->db_debug;
    $this->db->db_debug = false;
    try {
        // 1. Ringkasan
        $ringkasan = $this->Yield_model->get_ringkasan_analisa($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_ringkasan_analisa: ' . $error['message']
            );
        }
        // 2. Monitoring
        $monitoring = $this->Yield_model->get_monitoring_analisa($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_monitoring_analisa: ' . $error['message']
            );
        }
        // 3. Bad Produk Varian
        $bad_produk_varian =
            $this->Yield_model->get_bad_produk_varian_analisa($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_bad_produk_varian_analisa: ' . $error['message']
            );
        }
        // 4. Bad Produk Mesin
        $bad_produk_mesin =
            $this->Yield_model->get_bad_produk_mesin_analisa($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_bad_produk_mesin_analisa: ' . $error['message']
            );
        }
        // 5. Detail Batch
        $detail_batch =
            $this->Yield_model->get_detail_batch_analisa($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_detail_batch_analisa: ' . $error['message']
            );
        }
        // 6. Scope
        $scope = $this->Yield_model->get_scope_summary($filter);
        $error = $this->db->error();
        if (!empty($error['code'])) {
            throw new RuntimeException(
                'get_scope_summary: ' . $error['message']
            );
        }
        $response = [
            'status' => true,
            'scope' => $scope,
            'ringkasan' => $this->load->view(
                'yield/ajax/ringkasan',
                ['ringkasan' => $ringkasan],
                true
            ),
            'monitoring' => $this->load->view(
                'yield/ajax/monitoring',
                [
                    'monitoring' => $monitoring['rows'],
                    'total' => $monitoring['total']
                ],
                true
            ),
            'badproduk_varian' => $this->load->view(
                'yield/ajax/badproduk_varian',
                $bad_produk_varian,
                true
            ),
            'badproduk_mesin' => $this->load->view(
                'yield/ajax/badproduk_mesin',
                $bad_produk_mesin,
                true
            ),
            'detail_batch' => $this->load->view(
                'yield/ajax/detail_batch',
                ['detail_batch' => $detail_batch],
                true
            )
        ];
        $this->db->db_debug = $old_debug;
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    } catch (Throwable $e) {
        $db_error = $this->db->error();
        $response = [
            'status' => false,
            'message' => 'Data analisa gagal dimuat.',
            'error' => $e->getMessage(),
            'db_error' => $db_error,
            'last_query' => $this->db->last_query()
        ];
        $this->db->db_debug = $old_debug;
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
    public function export_excel()
    {
        $filter = $this->analisa_filter_from_input('get');
        $ringkasan = $this->Yield_model->get_ringkasan_analisa($filter);
        $monitoring = $this->Yield_model->get_monitoring_analisa($filter);
        $badVarian = $this->Yield_model->get_bad_produk_varian_analisa($filter);
        $badMesin = $this->Yield_model->get_bad_produk_mesin_analisa($filter);
        $detail = $this->Yield_model->get_detail_batch_analisa($filter);
        $scope = $this->Yield_model->get_scope_summary($filter);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Produksi')
            ->setTitle('Analisa Yield Produksi');
        $this->excel_ringkasan($spreadsheet, $ringkasan, $scope);
        $this->excel_monitoring($spreadsheet, $monitoring, $scope);
        $this->excel_bad_varian($spreadsheet, $badVarian, $scope);
        $this->excel_bad_mesin($spreadsheet, $badMesin, $scope);
        $this->excel_detail($spreadsheet, $detail, $scope);
        $spreadsheet->setActiveSheetIndex(0);
        $filename = 'Analisa_Yield_' . date('Ymd_His') . '.xlsx';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
    private function excel_title($sheet, $title, $scope, $lastCol)
    {
        $sheet->setTitle(substr($title, 0, 31));
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', $scope['label']);
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle("A2:{$lastCol}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:{$lastCol}4")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }
    private function excel_header($sheet, $range)
    {
        $sheet->getStyle($range)->getFont()->setBold(true);
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle($range)->getFill()->getStartColor()->setARGB('FFE7E6E6');
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    private function excel_ringkasan($ss, $data, $scope)
    {
        $s=$ss->getActiveSheet(); $this->excel_title($s,'RINGKASAN ANALISA YIELD',$scope,'M');
        $headers=['Total Batch','Adonan (Kg)','Filkar Box','Filkar Kg','Sortasi','Release','Belum Sortir','Yield Filkar','Yield Release','Rework Filkar','Reject Filkar','Rework Sortasi','Reject Sortasi'];
        foreach($headers as $i=>$h)$s->setCellValueByColumnAndRow($i+1,4,$h);
        $this->excel_header($s,'A4:M4');
        $r=5; $d=$data ?: (object)array_fill_keys(['total_batch','adonan_formula','filkar_box','filkar_kg','sortasi_box','release_box','blm_sortir','yield_formula','yield_release','filkar_rework','filkar_reject','sortasi_rework','sortasi_reject'],0);
        foreach(array_values((array)$d) as $i=>$v){ if($i>12) break; $s->setCellValueByColumnAndRow($i+1,$r,(float)$v); }
        $s->getStyle('A4:M5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach(range('A','M') as $c)$s->getColumnDimension($c)->setAutoSize(true);
    }
    private function excel_monitoring($ss,$data,$scope)
    {
        $s=$ss->createSheet();$this->excel_title($s,'MONITORING PRODUKSI',$scope,'M');
        $h=['Varian','Adonan','Filkar Box','Filkar Kg','Sortasi','Release','Belum Sortir','Rework Filkar','Reject Filkar','Rework Sortasi','Reject Sortasi','Yield Filkar','Yield Release'];
        foreach($h as $i=>$v)$s->setCellValueByColumnAndRow($i+1,4,$v);$this->excel_header($s,'A4:M4');
        $r=5;foreach($data['rows'] as $d){$vals=[$d->nama_varian,$d->adonan_formula,$d->filkar_box,$d->filkar_kg,$d->sortasi_box,$d->release_box,$d->blm_sortir,$d->filkar_rework,$d->filkar_reject,$d->sortasi_rework,$d->sortasi_reject,$d->yield_formula,$d->yield_release];foreach($vals as $i=>$v)$s->setCellValueByColumnAndRow($i+1,$r,$v);$r++;}foreach(range('A','M') as $c)$s->getColumnDimension($c)->setAutoSize(true);
    }
    private function excel_bad_varian($ss,$data,$scope)
    {
        $s=$ss->createSheet();$cols=2+count($data['varian'])+1;$last=$this->excel_col($cols);$this->excel_title($s,'BAD PRODUK PER VARIAN',$scope,$last);
        $h=['Bad Produk','Proses'];foreach($data['varian'] as $v)$h[]=$v->varian;$h[]='TOTAL';foreach($h as $i=>$v)$s->setCellValueByColumnAndRow($i+1,4,$v);$this->excel_header($s,"A4:{$last}4");
        $r=5;foreach($data['rows'] as $d){$vals=[$d->nama_badpro,$d->proses];foreach($data['varian'] as $v)$vals[]=(float)($d->{$v->uuid}??0);$vals[]=(float)$d->total;foreach($vals as $i=>$v)$s->setCellValueByColumnAndRow($i+1,$r,$v);$r++;}for($i=1;$i<=$cols;$i++)$s->getColumnDimension($this->excel_col($i))->setAutoSize(true);
    }
    private function excel_bad_mesin($ss,$data,$scope)
    {
        $s=$ss->createSheet();$cols=count($data['badproduk'])+2;$last=$this->excel_col($cols);$this->excel_title($s,'BAD PRODUK PER MESIN',$scope,$last);
        $h=['Mesin'];foreach($data['badproduk'] as $bp)$h[]=$bp->nama_badpro;$h[]='TOTAL';foreach($h as $i=>$v)$s->setCellValueByColumnAndRow($i+1,4,$v);$this->excel_header($s,"A4:{$last}4");
        $r=5;foreach($data['rows'] as $d){$vals=[$d->mesin];foreach($data['badproduk'] as $bp)$vals[]=(float)($d->{$bp->uuid}??0);$vals[]=(float)$d->total;foreach($vals as $i=>$v)$s->setCellValueByColumnAndRow($i+1,$r,$v);$r++;}for($i=1;$i<=$cols;$i++)$s->getColumnDimension($this->excel_col($i))->setAutoSize(true);
    }
    private function excel_detail($ss,$rows,$scope)
    {
        $s=$ss->createSheet();$this->excel_title($s,'DETAIL BATCH',$scope,'Q');
        $h=['No','Tanggal','Plan','Kode Batch','Varian','Mesin','Adonan','Filkar Box','Filkar Kg','Sortasi','Release','Belum Sortir','Filkar Rework','Filkar Reject','Sortasi Rework','Sortasi Reject','Yield Release'];foreach($h as $i=>$v)$s->setCellValueByColumnAndRow($i+1,4,$v);$this->excel_header($s,'A4:Q4');
        $r=5;$no=1;foreach($rows as $d){$vals=[$no++,date('d-m-Y',strtotime($d->tanggal)),$d->plan,$d->kode_batch,$d->varian,$d->nama_mesin,$d->adonan,$d->filkar_box,$d->filkar_kg,$d->sortasi_box,$d->release_box,$d->belum_sortir,$d->bad_filkar_rework_kg,$d->bad_filkar_reject_kg,$d->bad_sortasi_rework_kg,$d->bad_sortasi_reject_kg,$d->yield_release];foreach($vals as $i=>$v)$s->setCellValueByColumnAndRow($i+1,$r,$v);$r++;}foreach(range('A','Q') as $c)$s->getColumnDimension($c)->setAutoSize(true);
    }
    private function excel_col($n){$s='';while($n>0){$n--; $s=chr(65+($n%26)).$s;$n=intdiv($n,26);}return $s;}
}