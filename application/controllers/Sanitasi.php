<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Sanitasi extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sanitasi_model');
		$this->load->model('Chemical_model');
		$this->load->model('Gmp_model');
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Sanitasi_model->get_cheklist_sanitasi(),
			'active_nav' => 'sanitasi-data'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/sanitasi', $data);
		$this->load->view('partials/footer');
	}

	public function detail($area_uuid, $tanggal)
	{
		$data = array(
			'data' => $this->Sanitasi_model->get_by_area_uuid($area_uuid, $tanggal),
			'active_nav' => 'sanitasi-data'
		);


		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/detail', $data);
		$this->load->view('partials/footer');
	}

	public function tambahchek()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$insert = $this->Sanitasi_model->insert_kondisi();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('sanitasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
				redirect('sanitasi/');
				
			}
		}
		$data = array(
			'area' => $this->Gmp_model->get_area(),
			'lokasi' => $this->Gmp_model->get_lokasi(),
			'kegiatan' => $this->Gmp_model->get_kegiatan(),
			'kondisi' => $this->Sanitasi_model->get_kondisi(),
			'active_nav' => 'sanitasi-data'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/tambah', $data);
		$this->load->view('partials/footer');
	}
	
	public function tindakan($uuid)
	{
		$rules = $this->Sanitasi_model->rules1();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			$d = $this->Sanitasi_model->get_by_uuid($uuid);
			$update = $this->Sanitasi_model->do_tindakan($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
				redirect('sanitasi/detail/' . $d->area_uuid .'/'. $d->tanggal);
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
				redirect('sanitasi/detail/' . $d->area_uuid .'/'. $d->tanggal);
			}
		}

		$data = array(
			'tindakan'	=> $this->Sanitasi_model->get_tindakan(),
			'data' => $this->Sanitasi_model->all_chemical_used($uuid),
			// 'tinkon' => $this->Sanitasi_model->get_tindakan_by_kondisi(),
			'active_nav' => 'sanitasi-data'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/tindakan', $data);
		$this->load->view('partials/footer');
	}

	public function editcek($uuid)
	{
		$rules = $this->Sanitasi_model->rules1();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			$d = $this->Sanitasi_model->get_by_uuid($uuid);
			$update = $this->Sanitasi_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
				redirect('sanitasi/detail/' . $d->area_uuid .'/'. $d->tanggal);
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
				redirect('sanitasi/detail/' . $d->area_uuid .'/'. $d->tanggal);
			}
		}
		
		$data = array(
			'data' => $this->Sanitasi_model->all_chemical_used($uuid),
			
			'active_nav' => 'sanitasi-data'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/edit-cek', $data);
		$this->load->view('partials/footer');
	}

	public function get_kode_target($kegiatan_uuid)
	{
		$kegiatan = $this->Sanitasi_model->get_sanitasi_chemical($kegiatan_uuid);

		log_message('debug', 'Kegiatan data: ' . json_encode($kegiatan));
		echo json_encode($kegiatan);
	}

	public function kondisi()
	{
		$rules = [
			[
				'field' => 'kondisi',
				'label' => 'Kondisi',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Sanitasi_model->insert_master_kondisi();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('sanitasi/kondisi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('sanitasi/kondisi');
			}
		}

		$data = array(
			'data'	=> $this->Sanitasi_model->get_kondisi(),
			'active_nav' => 'm_kondisi'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/tambah-kondisi', $data);
		$this->load->view('partials/footer');
	}

	public function mtindakan()
	{
		$rules = [
			[
				'field' => 'tindakan',
				'label' => 'Tindakan',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Sanitasi_model->insert_master_tindakan();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('sanitasi/mtindakan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('sanitasi/mtindakan');
			}
		}

		$data = array(
			'data'	=> $this->Sanitasi_model->get_tindakan(),
			'active_nav' => 'm_tindakan'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sanitasi/tambah-tindakan', $data);
		$this->load->view('partials/footer');
	}

	public function get_tindakan_kegiatan($tindakan, $kegiatan_gmp_uuid)
	{
		$data = $this->Sanitasi_model->get_tindakan_by_kegiatan($tindakan, $kegiatan_gmp_uuid);
		print_r(json_encode($data));
	}

	public function form($area_uuid, $tanggal)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);
		$sanitasi = $this->Sanitasi_model->get_by_area_uuid($area_uuid, $tanggal);
		$kondisi = $this->Sanitasi_model->get_kondisi_nomor();
		$tindakan = $this->Sanitasi_model->get_tindakan_nomor();
		$logo = base_url("assets/img/cpi-logo.jpg");
		$html = '<html>
		<head>
		<title>FR-Prod-02 FORM PEMAKAIAN PVDC & WIRE</title>
		<meta name="author" content="Arthur Herbert Fonzarelli">
		<meta charset="UTF-8">
		<meta name="keywords" content="fonzie, cool, ehhhhhhh">
		<style>
		@page { margin: 20px; }
		table { border-collapse: collapse;}
		table tr td { border: 1px solid #000; }
		table thead tr { border: 1px solid #000; background-color: #dbe5f1 }
		table thead tr#standar { border: 1px solid #000; background-color: #b8cce4 !important; }
		table.data tr th { border: 1px solid #000; text-align: center; font-weight: normal; font-size: 14px; }
		table.data tr td { text-align: center; }
		</style>
		</head>
		<body>
		<table width="100%">
		<tr>
		<td width="140">
		<table width="100%">
		<tbody>
		<tr>
		<td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="120px"></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td width="500">
		<table width="100%">
		<tbody>
		<tr>
		<td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3 style="font-weight:normal">FORM</h3></td>
		</tr>
		<tr>
		<td style="text-align:center;border:0;"><h3>CHECKLIST KEBERSIHAN SANITASI</h3></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td>
		<table width="101%" style="margin-left:-1px; font-size:12px;">
		<tbody>
		<tr>
		<td style="border:0;height:28px;">&nbsp;No. Dokumen</td>
		<td style="border:0;height:28px;">:</td>
		<td style="border:0;height:28px;">&nbsp;FR-Prod-13</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;1</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;01/10/2020</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;4 dari 4</td> 
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</table>
		<table style="margin-top: 10px; font-size:12px; margin-bottom: 10px; border: none;">
		<tbody>
		<tr style="border: none;">
		<td width="200px" style="border: none; text-align: left;">Tanggal</td><td width="250px" style="border: none;">: ' . $sanitasi[0]->tgl . '</td>
		</tr>
		<tr>
		<td width="200px" style="text-align: left; border: none;">Area</td><td width="250px" style="border: none;">: ' . $sanitasi[0]->area . '</td>
		</tr>
		</tbody>
		</table>
		<table style="font-size: 10px;">
		<thead>
		<tr>
		<th rowspan="3" style="border: 1px solid black;">No</th>
		<th rowspan="3" style="border: 1px solid black;">Item Pemeriksaan</th>
		<th colspan="16" style="border: 1px solid black;">Periode Waktu Pengecekan</th>
		<th rowspan="3" style="border: 1px solid black;">Keterangan</th>
		</tr>
		<tr>
		<th colspan="2" style="border: 1px solid black;">09.00</th>
		<th colspan="2" style="border: 1px solid black;">12.00</th>
		<th colspan="2" style="border: 1px solid black;">15.00</th>
		<th colspan="2" style="border: 1px solid black;">18.00</th>
		<th colspan="2" style="border: 1px solid black;">21.00</th>
		<th colspan="2" style="border: 1px solid black;">00.00</th>
		<th colspan="2" style="border: 1px solid black;">03.00</th>
		<th colspan="2" style="border: 1px solid black;">06.00</th>
		</tr>
		<tr>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		<th style="border: 1px solid black;">K</th>
		<th style="border: 1px solid black;">T</th>
		</tr>
		</thead>
		<tbody>';
		$no = 1;
		foreach ($sanitasi as $row) {
			$html .= '<tr style="text-align: center;">';
			$html .= '<td width="7%">' . $no . '</td>';
			$html .= '<td style="text-align: left;" width="7%" >' . $row->nama_item . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '09:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '09:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '12:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '12:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '15:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '15:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '18:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '18:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '21:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '21:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '00:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '00:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '03:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '03:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_kondisi == '06:00:00' ? $row->kondis : '') . '</td>';
			$html .= '<td width="30%">' . ($row->waktu_tindakan == '06:00' ? $row->no_tindak : '') . '</td>';
			$html .= '<td width="30%"></td>';
			$html .= '</tr>';
			$no++;
		}
		$html .= '
		<tr style="text-align: center">
		<td></td>
		<td></td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td>PIC</td>
		<td>Verifikasi</td>
		<td></td>
		</tr><tr>
		<td style="height: 20px"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		</tr>
		<tr style="text-align: center">
		<td></td>
		<td></td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td>Koordinator</td>
		<td>QC</td>
		<td></td>
		</tr>
		</tbody>
		</table>';
		$html .= '<span style="font-size: 8px;";>*Kondisi Area &nbsp;';
		foreach ($kondisi as $val1) {
			$html .= '<span>' . $val1->no_kondisi . ':' . $val1->kondisi .',&nbsp;&nbsp;</span>';
		}
		$html .= '<br>**Tindakan Perbaikan yang Diambil &nbsp;';
		foreach ($tindakan as $value) {
			$html .= '<span>' . $value->no_tindakan . ':' . $value->tindakan .',&nbsp;&nbsp;</span>';
		}
		$html .= '<br><br><table width="100%">
		<tr>
		<td style="width: 100px; text-align: center;">Diperiksa Oleh</td>
		<td style="border: none; width: 30px;"></td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Disetujui Oleh</td> 
		</tr>
		<tr>
		<td style="height: 60px; width: 100px;"></td>
		<td style="height: 60px; border: none; width: 30px;"></td>
		<td style="height: 60px; border: none; width: 30px;"></td>
		<td style="height: 60px; width: 100px;"></td> 
		</tr>
		<tr>
		<td style="width: 100px; text-align: center;">Foreman Produksi</td>
		<td style="border: none; width: 30px;"></td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Spv. Produksi</td> 
		</tr>
		</table>';
		$html .= '</body></html>';
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream("FR-Prod-13_Formulir_.pdf", array("Attachment" => false));
	}

	public function formchemical($tanggal)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);
		$chemical = $this->Sanitasi_model->get_chemical_by_area($tanggal);
		$logo = base_url("assets/img/cpi-logo.jpg");
		$html = '<html>
		<head>
		<title>FR-Prod-02 FORM PEMAKAIAN PVDC & WIRE</title>
		<meta name="author" content="Arthur Herbert Fonzarelli">
		<meta charset="UTF-8">
		<meta name="keywords" content="fonzie, cool, ehhhhhhh">
		<style>
		@page { margin: 20px; }
		table { border-collapse: collapse;}
		table tr td { border: 1px solid #000; }
		table thead tr { border: 1px solid #000; background-color: #dbe5f1 }
		table thead tr#standar { border: 1px solid #000; background-color: #b8cce4 !important; }
		table.data tr th { border: 1px solid #000; text-align: center; font-weight: normal; font-size: 14px; }
		table.data tr td { text-align: center; }
		</style>
		</head>
		<body>
		<table width="100%">
		<tr>
		<td width="140">
		<table width="100%">
		<tbody>
		<tr>
		<td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="120px"></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td width="500">
		<table width="100%">
		<tbody>
		<tr>
		<td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3 style="font-weight:normal">FORM</h3></td>
		</tr>
		<tr>
		<td style="text-align:center;border:0;"><h3>PEMAKAIAN CHEMICAL</h3></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td>
		<table width="101%" style="margin-left:-1px; font-size:12px;">
		<tbody>
		<tr>
		<td style="border:0;height:28px;">&nbsp;No. Dokumen</td>
		<td style="border:0;height:28px;">:</td>
		<td style="border:0;height:28px;">&nbsp;FR-Prod-13</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;1</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;01/10/2020</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;4 dari 4</td> 
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</table>
		<table style="margin-top: 10px; font-size:12px; margin-bottom: 10px; border: none;">
		<tbody>
		<tr style="border: none;">
		<td width="200px" style="border: none; text-align: left;">Tanggal</td><td width="250px" style="border: none;">: ' . $chemical[0]->tgl . '</td>
		</tr>
		</tbody>
		</table>
		<table><thead> 
		<tr>
		<th rowspan="3" class="align-middle">No</th>
		<th rowspan="3" class="align-middle">Area</th>
		<th rowspan="3" class="align-middle">Item yang dibersihkan</th>
		<th rowspan="3" class="align-middle">Kode Chemical</th>
		<th colspan="12">Pemakaian Larutan Chemical (ml)</th>
		<th rowspan="3">Total</th>
		</tr>
		<tr>
		<th colspan="4">Shift 1</th>
		<th colspan="4">Shift 2</th>
		<th colspan="4">Shift 3</th>
		</tr>
		<tr>
		<th>08.00</th>
		<th>10.00</th>
		<th>12.00</th>
		<th>14.00</th>
		<th>16.00</th>
		<th>18.00</th>
		<th>20.00</th>
		<th>22.00</th>
		<th>24.00</th>
		<th>02.00</th>
		<th>04.00</th>
		<th>06.00</th>
		</tr></thead>
		<tbody>';
		$current_area = '';
		$current_item = '';
		$area_rowspan_count = [];
		$item_rowspan_count = [];
		$row_number = 1;
		foreach ($chemical as $row) {
			if (!isset($area_rowspan_count[$row->area])) {
				$area_rowspan_count[$row->area] = 0;
			}
			$area_rowspan_count[$row->area]++;
			if (!isset($item_rowspan_count[$row->kegiatan_uuid])) {
				$item_rowspan_count[$row->kegiatan_uuid] = 0;
			}
			$item_rowspan_count[$row->kegiatan_uuid]++;
		}
		foreach ($chemical as $row) {
			$html .= '<tr><td class="align-middle">' . $row_number++ . '</td>';
			if ($current_area != $row->area) {
				$current_area = $row->area;
				$html .= '<td class="align-middle" rowspan="' . $area_rowspan_count[$row->area] . '">' . $row->area . '</td>';
			}
			if ($current_item != $row->kegiatan_uuid) {
				$current_item = $row->kegiatan_uuid;
				$html .= '<td class="align-middle" rowspan="' . $item_rowspan_count[$row->kegiatan_uuid] . '">' . $row->nama_item . '</td>';
			}
			$html .= '<td class="align-middle">' . $row->kode_chemical . '</td>';
			$html .= '<td class="align-middle">' . $row->jam8 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam10 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam12 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam14 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam16 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam18 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam20 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam22 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam0 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam2 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam4 . '</td>';
			$html .= '<td class="align-middle">' . $row->jam6 . '</td>';
			$html .= '<td class="align-middle">' . $row->total_used . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table><br><br><table width="100%">
		<tr>
		<td style="width: 100px; text-align: center;">Diperiksa Oleh</td>
		<td style="border: none; width: 30px;"></td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Disetujui Oleh</td> 
		</tr>
		<tr>
		<td style="height: 60px; width: 100px;"></td>
		<td style="height: 60px; border: none; width: 30px;"></td>
		<td style="height: 60px; border: none; width: 30px;"></td>
		<td style="height: 60px; width: 100px;"></td> 
		</tr>
		<tr>
		<td style="width: 100px; text-align: center;">Foreman Produksi</td>
		<td style="border: none; width: 30px;"></td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Spv. Produksi</td> 
		</tr>
		</table>';
		$html .= '</body></html>';
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream("FR-Prod-13_Formulir_.pdf", array("Attachment" => false));

	}

	public function get_kondisi_data()
	{
		$data1 = $this->Sanitasi_model->get_kondisi();
		print_r(json_encode($data1));
	}
}

