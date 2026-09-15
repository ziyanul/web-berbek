<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Analisa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Analisa_model');
        // Library ini dipakai project existing untuk memuat PhpSpreadsheet/autoloader.
        $this->load->library('spreadsheet_lib');
        if (!$this->Auth_model->current_user()) redirect('login');
    }

    public function index()
    {
        $data = ['title' => 'Analisa Produksi', 'active_nav' => 'analisa'];
        $this->load->view('partials/head-yield', $data);
        $this->load->view('analisa/index', $data);
        $this->load->view('partials/footer');
    }

    public function variants()
    {
        $this->_json($this->Analisa_model->get_variants($this->input->get('start', true), $this->input->get('end', true)));
    }

    public function plans()
    {
        $this->_json($this->Analisa_model->get_plans($this->input->get('start', true), $this->input->get('end', true), $this->input->get('varian_uuid', true)));
    }

    public function batches()
    {
        $this->_json($this->Analisa_model->get_batches($this->input->get('plan_uuid', true), $this->input->get('varian_uuid', true), $this->input->get('start', true), $this->input->get('end', true)));
    }

    public function processes()
    {
        $this->_json($this->Analisa_model->get_processes());
    }

    public function badproducts()
    {
        try {
            $this->_json($this->Analisa_model->get_badproducts($this->_filters()));
        } catch (Throwable $e) {
            log_message('error', 'Analisa badproducts exception: ' . $e->getMessage());
            $this->_json(['error' => 'Query daftar bad product gagal. Lihat application/logs untuk detail.'], 500);
        }
    }

    public function batch($uuid = null)
    {
        if (!$uuid) return $this->_json(['error' => 'UUID batch wajib diisi.'], 400);
        $data = $this->Analisa_model->get_batch_journey($uuid);
        if (!$data) return $this->_json(['error' => 'Batch tidak ditemukan.'], 404);
        $this->_json($data);
    }

    public function performance()
    {
        $this->_json($this->Analisa_model->get_machine_performance($this->_filters()));
    }

    public function badpro()
    {
        try {
            $this->_json($this->Analisa_model->get_badpro_analysis($this->_filters()));
        } catch (Throwable $e) {
            log_message('error', 'Analisa badpro exception: ' . $e->getMessage());
            $this->_json(['error' => 'Query analisa bad product gagal. Lihat application/logs untuk detail.'], 500);
        }
    }

    public function badpro_trend()
    {
        try {
            $this->_json($this->Analisa_model->get_badpro_trend($this->_filters()));
        } catch (Throwable $e) {
            log_message('error', 'Analisa badpro_trend exception: ' . $e->getMessage());
            $this->_json(['error' => 'Query trend bad product gagal. Lihat application/logs untuk detail.'], 500);
        }
    }

    public function badpro_journey()
    {
        try {
            $group_by = $this->input->get('group_by', true) === 'batch' ? 'batch' : 'plan';
            $this->_json($this->Analisa_model->get_badpro_journey($this->_filters(), $group_by));
        } catch (Throwable $e) {
            log_message('error', 'Analisa badpro_journey exception: ' . $e->getMessage());
            $this->_json(['error' => 'Query perjalanan bad product gagal. Lihat application/logs untuk detail.'], 500);
        }
    }

    public function yield_analysis()
    {
        $this->_json($this->Analisa_model->get_yield_analysis($this->_filters()));
    }

    /** Export Bad Product analysis: Summary, Trend, Mesin, Perjalanan Plan, Perjalanan Batch, Detail. */
    public function export_badpro()
    {
        $filters = $this->_filters();
        try {
            $data = $this->Analisa_model->get_badpro_export_data($filters);
            if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
                // Fallback Excel-compatible HTML jika PhpSpreadsheet tidak tersedia.
                $this->_export_badpro_html($data, $filters);
                return;
            }

            $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Sistem Produksi')
            ->setTitle('Analisa Bad Product')
            ->setSubject('Analisa Bad Product');

        $this->_sheetSummary($spreadsheet->getActiveSheet(), $data['summary'], $filters);
        $this->_sheetTrend($spreadsheet->createSheet(), $data['trend']);
        $this->_sheetTable($spreadsheet->createSheet(), 'Breakdown Mesin', ['Rank', 'Mesin', 'Total Kg', 'Jumlah Transaksi', 'Jumlah Batch'], $data['machine']);
        $this->_sheetTable($spreadsheet->createSheet(), 'Perjalanan Per Plan', ['Tanggal Plan', 'Plan', 'Varian', 'Jumlah Batch', 'Total Bad (Kg)'], $data['journey_plan']);
        $this->_sheetTable($spreadsheet->createSheet(), 'Perjalanan Per Kode Batch', ['Tanggal Produksi', 'Plan', 'Varian', 'Kode Batch', 'Total Bad (Kg)'], $data['journey_batch']);
        $this->_sheetTable($spreadsheet->createSheet(), 'Detail Bad Product', ['Tanggal', 'Varian', 'Proses', 'REF', 'Plan', 'Kode Batch', 'Mesin', 'Bad Product', 'Kategori', 'Berat (Kg)'], $data['detail']);

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $sheet->getDefaultRowDimension()->setRowHeight(18);
            $sheet->freezePane('A2');
        }

        $filename = 'Analisa_Bad_Product_' . date('Ymd_His') . '.xlsx';
        $spreadsheet->setActiveSheetIndex(0);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
            (new Xlsx($spreadsheet))->save('php://output');
            exit;
        } catch (Throwable $e) {
            log_message('error', 'Analisa export_badpro exception: ' . $e->getMessage());
            // Jangan gagal total hanya karena PhpSpreadsheet/query export bermasalah.
            try {
                $data = isset($data) ? $data : $this->Analisa_model->get_badpro_export_data($filters);
                $this->_export_badpro_html($data, $filters);
            } catch (Throwable $e2) {
                log_message('error', 'Analisa export fallback exception: ' . $e2->getMessage());
                show_error('Export Excel gagal: ' . $e2->getMessage(), 500);
            }
        }
    }

    private function _export_badpro_html($data, $filters)
    {
        $filename = 'Analisa_Bad_Product_' . date('Ymd_His') . '.xls';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<h2>ANALISA BAD PRODUCT</h2>';
        echo '<p>Periode: ' . htmlspecialchars(($filters['start'] ?: '-') . ' s/d ' . ($filters['end'] ?: '-')) . '</p>';
        echo '<p>Varian: ' . htmlspecialchars($filters['varian_name'] ?: 'Semua Varian') . ' | Proses: ' . htmlspecialchars($filters['proses_name'] ?: 'Semua Proses') . ' | Bad Product: ' . htmlspecialchars($filters['badpro_name'] ?: 'Semua Bad Product') . '</p>';
        $sections = [
            ['Trend Harian', ['Tanggal','Berat Bad (Kg)'], $data['trend'], ['tanggal','total_kg']],
            ['Breakdown Mesin', ['Rank','Mesin','Total Kontribusi Kg','Transaksi','Batch'], $data['machine'], ['nama_mesin','total_kg','jumlah_transaksi','jumlah_batch']],
            ['Perjalanan Per Plan', ['Tanggal Plan','Plan','Varian','Jumlah Batch','Total Bad (Kg)'], $data['journey_plan'], ['tanggal_plan','plan','varian','jumlah_batch','total_kg']],
            ['Perjalanan Per Kode Batch', ['Tanggal Produksi','Plan','Varian','Kode Batch','Total Bad (Kg)'], $data['journey_batch'], ['tanggal_produksi','plan','varian','kode_batch','total_kg']],
            ['Detail Bad Product', ['Tanggal','Varian','Proses','REF','Plan','Kode Batch','Mesin','Bad Product','Kategori','Berat (Kg)'], $data['detail'], ['tanggal','varian','proses','ref','plan','kode_batch','mesin','nama_badpro','kategori','berat']],
        ];
        foreach ($sections as $section) {
            echo '<h3>' . htmlspecialchars($section[0]) . '</h3><table border="1"><thead><tr>';
            foreach ($section[1] as $h) echo '<th>' . htmlspecialchars($h) . '</th>';
            echo '</tr></thead><tbody>';
            foreach ($section[2] as $row) { echo '<tr>'; $arr = is_object($row) ? (array)$row : $row; foreach ($section[3] as $key) echo '<td>' . htmlspecialchars((string)($arr[$key] ?? '')) . '</td>'; echo '</tr>'; }
            echo '</tbody></table><br>';
        }
        echo '</body></html>';
        exit;
    }

    private function _sheetSummary($sheet, $summary, $filters)
    {
        $sheet->setTitle('Summary');
        $sheet->fromArray([['ANALISA BAD PRODUCT']], null, 'A1');
        $sheet->fromArray([
            ['Periode', ($filters['start'] ?: '-') . ' s/d ' . ($filters['end'] ?: '-')],
            ['Varian', $filters['varian_name'] ?: 'Semua Varian'],
            ['Proses', $filters['proses_name'] ?: 'Semua Proses'],
            ['Bad Product', $filters['badpro_name'] ?: 'Semua Bad Product'],
            ['Total Bad (Kg)', (float)$summary['total_bad']],
            ['Jumlah Batch', (int)$summary['jumlah_batch']],
            ['Jumlah Plan', (int)$summary['jumlah_plan']],
            ['Mesin Dominan', $summary['mesin_dominan'] ?: '-'],
            ['Total Transaksi', (int)$summary['jumlah_transaksi']],
        ], null, 'A3');
        $this->_styleSheet($sheet, 'A1:B12');
    }

    private function _sheetTrend($sheet, $rows)
    {
        $sheet->setTitle('Trend Harian');
        $sheet->fromArray([['Tanggal', 'Berat Bad (Kg)']], null, 'A1');
        $out = [];
        foreach ($rows as $r) $out[] = [$r->tanggal, (float)$r->total_kg];
        if ($out) $sheet->fromArray($out, null, 'A2');
        $this->_styleSheet($sheet, 'A1:B' . max(1, count($out) + 1));
    }

    private function _sheetTable($sheet, $title, $headers, $rows)
    {
        $safeTitle = preg_replace('/[\\\/?*\[\]:]/', '', $title);
        $sheet->setTitle(substr($safeTitle, 0, 31));
        $sheet->fromArray([$headers], null, 'A1');
        $out = [];
        foreach ($rows as $r) {
            if (is_object($r)) $r = (array)$r;
            $out[] = array_values($r);
        }
        if ($out) $sheet->fromArray($out, null, 'A2');
        $this->_styleSheet($sheet, 'A1:' . $this->_col(count($headers)) . max(1, count($out) + 1));
    }

    private function _styleSheet($sheet, $range)
    {
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);
        $max = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($c = 1; $c <= $max; $c++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }
    }

    private function _col($n)
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($n);
    }

    private function _filters()
    {
        return [
            'start'       => $this->input->get('start', true),
            'end'         => $this->input->get('end', true),
            'varian_uuid' => $this->input->get('varian_uuid', true),
            'plan_uuid'   => $this->input->get('plan_uuid', true),
            'batch_uuid'  => $this->input->get('batch_uuid', true),
            'proses_uuid' => $this->input->get('proses_uuid', true),
            'mesin_uuid'  => $this->input->get('mesin_uuid', true),
            'badpro_uuid' => $this->input->get('badpro_uuid', true),
            'varian_name' => $this->input->get('varian_name', true),
            'proses_name' => $this->input->get('proses_name', true),
            'badpro_name' => $this->input->get('badpro_name', true),
        ];
    }

    private function _json($data, $status = 200)
    {
        $this->output->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
