<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Monitor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Part_model');
		$this->load->model('Monitor_model');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
		$this->load->library('spreadsheet_lib');
		$this->load->model('Auth_model');
		$this->load->library('upload');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Monitor_model->prepare_active_display(),
			'active_nav' => 'monitor'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor', $data);
		$this->load->view('partials/footer');
	}

	public function tpm()
	{
		$data = array(
			'data' => $this->Monitor_model->get_tpm(),
			'active_nav' => 'tpm-part'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-tpm', $data);
		$this->load->view('partials/footer');
	}

	public function history()
	{
		$data = array(
			'data' => $this->Monitor_model->prepare_history_display(),
			'active_nav' => 'histori-part'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-history', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Monitor_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {

			$insert = $this->Monitor_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data SparePart berhasil di simpan.');
				redirect($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : 'monitor');
			} else {
				$this->session->set_flashdata('error_msg', $this->session->flashdata('error_msg'));
				redirect($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : 'monitor');
				
			}
		}

		$data = array(
			'area' => $this->Area_model->get_all(),
			'mesin' => $this->Mesin_model->get_all(),
			'part' => $this->Part_model->get_all(),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'tpm-part':'monitor')
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-tambah', $data);
		$this->load->view('partials/footer');
	}

	public function tindakan($uuid)
	{
		$rules = [
			[
				'field' => 'pelaksana',
				'label' => 'pelaksana',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'catatan',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Monitor_model->tindakan($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Berhasil Di Simpan');
				redirect ('monitor/tpm');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');

				redirect('monitor/tpm');
			}
		}

		$data = array(
			'data' => $this->Monitor_model->get_by_uuid($uuid),
			'active_nav' => 'tpm-part'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-tindakan', $data);
		$this->load->view('partials/footer');
	}

	public function ubah($uuid)
	{
		$rules = [
			[
				'field' => 'jadwal',
				'label' => 'Jadwal',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Monitor_model->ubah_data($uuid); 
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Berhasil Di Simpan');
				redirect('monitor/tpm');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				
				redirect('monitor/tpm');
			}
		}

		$data = array(
			'data' => $this->Monitor_model->get_by_uuid($uuid),
			'active_nav' => 'tpm-part',
			'area' => $this->Area_model->get_all(),
			'mesin' => $this->Mesin_model->get_all(),
			'part' => $this->Part_model->get_all()
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-ubah', $data);
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			'active_nav' => ($this->uri->segment(2)=='tpm'?'tpm-part':($this->uri->segment(2)=='history'?'histori-part':'monitor')),
			'data' => $this->Monitor_model->get_by_uuid($uuid)
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-detail', $data);
		$this->load->view('partials/footer');
	}

	public function detailcek($uuid)
	{
		$data = array(
			'active_nav' => 'tpm-part',
			'data' => $this->Monitor_model->get_part_lama($uuid)
		);


		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-cek', $data);
		$this->load->view('partials/footer');
	}

	public function status($uuid)
	{
		$rules = [[
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'required'
		]];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$status 	= $this->input->post('status');	
			if ($status == 4) {
				$insertstatus = $this->Monitor_model->status($uuid);
				if ($insertstatus) {
					$this->session->set_flashdata('success_msg', 'Update Status berhasil di simpan.');
					redirect($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : 'monitor');
				} 
			} else if ($status == 3) {
				$insertstatus = $this->Monitor_model->status($uuid);
				$insertnew = $this->Monitor_model->getPartDataByUuid($uuid);
				if ($insertstatus && $insertnew) {
					$this->session->set_flashdata('success_msg', 'Update Status berhasil di simpan.');
					redirect($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : 'monitor');
				}
			}else {
				$this->session->set_flashdata('error_msg', 'Invalid status value');
			}
		}

		$data = array (
			'data' => $this->Monitor_model->get_by_uuid($uuid),
			'status' => $this->Monitor_model->get_status_by_part_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'tpm-part':'monitor')
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('monitor/monitor-status', $data);
		$this->load->view('partials/footer');
	}

	public function get_mesin_name($uuid)
	{
		$data = array(
			$this->Monitor_model->get_mesin_name($uuid),
			$this->Monitor_model->get_part_name($uuid)
		);

		print_r(json_encode($data));
	}
	
	public function get_part_by_mesin($mesin_uuid)
	{
		$data = $this->Part_model->get_by_mesin($mesin_uuid);
		print_r(json_encode($data));
	}

	public function delete_kegiatan($uuid) 
	{
		$this->Monitor_model->delete_kegiatan($uuid);
		redirect('monitor/tpm');
	}

	public function file_check_foto($str)
	{
		$allowed_mime_types = ['image/jpeg', 'image/png'];
		$mime_type = $_FILES['foto']['type'];
		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check_foto', 'File harus berformat JPEG atau PNG');
			return false;
		}
		return true;
	}

	public function file_check_dokumen($str)
	{
		$allowed_mime_types = ['image/jpeg', 'image/png'];
		$mime_type = $_FILES['dokumen']['type'];
		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check_dokumen', 'File harus berformat JPEG atau PNG');
			return false;
		}
		return true;
	}

	public function approve($uuid)
	{
		$this->Monitor_model->approve_replacement($uuid);
		redirect('monitor/tpm');
	}

	public function reject($uuid)
	{
		$this->Monitor_model->reject_replacement($uuid);
		redirect('monitor/tpm');
	}

	public function export_history()
	{
		ini_set('display_errors', 0);
		error_reporting(0);
		$area = $this->input->get('area');
		$mesin = $this->input->get('mesin');
		$kondisi = $this->input->get('kondisi');
		$start = $this->input->get('start');
		$end = $this->input->get('end');

		$data = $this->Monitor_model->get_history_export($area,$mesin,$kondisi,$start,$end);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

    // ======================
    // JUDUL REPORT
    // ======================

		$sheet->mergeCells('A1:O1');
		$sheet->setCellValue('A1','REPORT HISTORY PERGANTIAN SPAREPART');

		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    // ======================
    // HEADER TABEL
    // ======================

		$sheet->setCellValue('A3','No');
		$sheet->setCellValue('B3','Tanggal');
		$sheet->setCellValue('C3','Area');
		$sheet->setCellValue('D3','Mesin');
		$sheet->setCellValue('E3','Nama Part');
		$sheet->setCellValue('F3','Run Hours');
		$sheet->setCellValue('G3','Lifetime');
		$sheet->setCellValue('H3','Kondisi');
		$sheet->setCellValue('I3','Pengaju');
		$sheet->setCellValue('J3','Pelaksana');
		$sheet->setCellValue('K3','ACC');
		$sheet->setCellValue('L3','Harga');
		$sheet->setCellValue('M3','Di Pasang');
		$sheet->setCellValue('N3','Di Ganti');
		$sheet->setCellValue('O3','Catatan');

		$headerStyle = [
			'font' => [
				'bold' => true,
				'color' => ['rgb' => 'FFFFFF']
			],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => '007BFF']
			],
			'alignment' => [
				'horizontal' => 'center'
			]
		];

		$sheet->getStyle('A3:O3')->applyFromArray($headerStyle);

    // ======================
    // DATA
    // ======================

		$row = 4;
		$no = 1;

		foreach($data as $d){

			$sheet->setCellValue('A'.$row, $no);
			$sheet->setCellValue('B'.$row, date('d-m-Y',strtotime($d->created_at)));
			$sheet->setCellValue('C'.$row, $d->nama_area);
			$sheet->setCellValue('D'.$row, $d->nama_mesin);
			$sheet->setCellValue('E'.$row, $d->nama_part);
			$sheet->setCellValue('F'.$row, (int)$d->final_rh);
			$sheet->setCellValue('G'.$row, (int)$d->lifetime);
			$sheet->setCellValue('H'.$row, $d->kondisi);
			$sheet->setCellValue('I'.$row, $d->username);
			$sheet->setCellValue('J'.$row, $d->nama_pelaksana);
			$sheet->setCellValue('K'.$row, $d->nama_foreman);
			$sheet->setCellValue('L'.$row, (int)$d->harga);
			$sheet->setCellValue('M'.$row, date('d-m-Y',strtotime($d->installed_at)));
			$sheet->setCellValue('N'.$row, date('d-m-Y',strtotime($d->removed_at)));
			$sheet->setCellValue('O'.$row, $d->catatan);


        // WARNA KONDISI

			if($d->kondisi == 'Over Lifetime'){

				$sheet->getStyle('H'.$row)->applyFromArray([
					'font'=>['bold'=>true],
					'fill'=>[
						'fillType'=>'solid',
						'startColor'=>['rgb'=>'FF4C4C']
					]
				]);

			}elseif($d->kondisi == 'Warning'){

				$sheet->getStyle('H'.$row)->applyFromArray([
					'font'=>['bold'=>true],
					'fill'=>[
						'fillType'=>'solid',
						'startColor'=>['rgb'=>'FFD966']
					]
				]);

			}else{

				$sheet->getStyle('H'.$row)->applyFromArray([
					'font'=>['bold'=>true],
					'fill'=>[
						'fillType'=>'solid',
						'startColor'=>['rgb'=>'92D050']
					]
				]);

			}

			$row++;
			$no++;
		}

    // ======================
    // AUTO WIDTH
    // ======================

		foreach(range('A','O') as $col){
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

    // ======================
    // BORDER
    // ======================

		$styleArray = [
			'borders'=>[
				'allBorders'=>[
					'borderStyle'=>'thin'
				]
			]
		];

		$sheet->getStyle('A3:O'.($row-1))->applyFromArray($styleArray);

    // ======================
    // FREEZE HEADER
    // ======================

		$sheet->freezePane('A4');

    // ======================
    // DOWNLOAD FILE
    // ======================

		$filename = "Report_History_Sparepart_".date('Ymd_His').".xlsx";

		while (ob_get_level()) {
			ob_end_clean();
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}