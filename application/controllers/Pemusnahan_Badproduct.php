<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pemusnahan_Badproduct extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('pn_badproduct_model');
		$this->load->library('form_validation');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->pn_badproduct_model->get_all(),
			'active_nav' => 'pemusnahan_badproduct'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('pn_badpro/pn_badpro', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->pn_badproduct_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$insert = $this->pn_badproduct_model->insert();

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Bad Product berhasil di tambah.');
				redirect('pemusnahan_badproduct');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Bad Product gagal di tambah.');
				redirect('pemusnahan_badproduct');
			}
		}
		$data = array(
			'varian' => $this->pn_badproduct_model->get_all_varian(),
			'active_nav' => 'pemusnahan_badproduct'
		);
		// echo "<pre>";
		// print_r($data);
		// echo '</pre>';
		$this->load->view('partials/head-form', $data);
		$this->load->view('pn_badpro/pn_tambah', $data);
		$this->load->view('partials/footer');
	}

	public function get_item_name($uuid)
	{
		$data = $this->pn_badproduct_model->get_item_name($uuid);
		print_r(json_encode($data));
	}

	public function detail($tanggal, $shift)
	{
		$data = array(
			'active_nav' => 'pemusnahan_badproduct',
			'data' => $this->pn_badproduct_model->get_by_tanggal($tanggal, $shift),
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('pn_badpro/pn_detail', $data);
		$this->load->view('partials/footer');
	}
	public function edit($uuid)
	{
		$rules1 = $this->pn_badproduct_model->rules1();
		$this->form_validation->set_rules($rules1);

		if ($this->form_validation->run() === TRUE) {
			$r = $this->pn_badproduct_model->get_by_uuid($uuid);

			$update = $this->pn_badproduct_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemusnahan Bad Produk berhasil di ubah.');
				redirect('pemusnahan_badproduct/detail/' . $r->tanggal . '/' . $r->shift);
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemusnahan Bad Produk gagal di ubah.');
				redirect('pemusnahan_badproduct/detail/' . $r->tanggal . '/' . $r->shift);
			}
		}

		$data = array(
			'data' => $this->pn_badproduct_model->get_by_uuid($uuid),
			'varian' => $this->pn_badproduct_model->get_all_varian(),
			'active_nav' => 'pemusnahan_badproduct'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('pn_badpro/pn_edit', $data);
		$this->load->view('partials/footer');
	}

	public function approval_kr($tanggal, $shift)
	{
		$update = $this->pn_badproduct_model->approval_kr($tanggal, $shift);

		if ($update) {
			// Ambil data fullname dari user yang sedang login
			$current_user = $this->auth_model->current_user();

			echo json_encode([
				'status' => true,
				'fullname' => $current_user->fullname, // Pastikan ini benar
				'message' => 'Reservasi berhasil di-ACC.',
			]);
		} else {
			echo json_encode([
				'status' => false,
				'message' => 'Gagal menyetujui reservasi.',
			]);
		}
	}

	public function approval_spv($tanggal, $shift)
	{
		$update = $this->pn_badproduct_model->approval_spv($tanggal, $shift);

		if ($update) {
			// Ambil data fullname dari user yang sedang login
			$current_user = $this->auth_model->current_user();

			echo json_encode([
				'status' => true,
				'fullname' => $current_user->fullname, // Pastikan ini benar
				'message' => 'Reservasi berhasil di-ACC.',
			]);
		} else {
			echo json_encode([
				'status' => false,
				'message' => 'Gagal menyetujui reservasi.',
			]);
		}
	}
	public function form($tanggal, $shift)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);

		$v = $this->pn_badproduct_model->get_by_tanggal($tanggal, $shift);
		$logo = base_url("assets/img/cpi-logo.jpg");

		$html = '
		<html>
		<head>
		<title>FR-Prod-21 FORM PEMUSNAHAN BAD PRODUK</title>
		<meta name="author" content="Arthur Herbert Fonzarelli">
		<meta name="keywords" content="fonzie, cool, ehhhhhhh">
		</head>
		<body>

		<style>
		@page { margin: 20px; }
		table {border-collapse: collapse; }
		table tr td{border:1px solid #000;}
		table thead tr {background-color:#dbe5f1}
		table thead tr#standar{background-color:#b8cce4!important;}
		table.data tr th{border:1px solid #000;text-align:center;font-weight:normal;font-size:14px;}
		table.data tr td{text-align:center;}
		</style>
		<table width="100%">
		<tr>
		<td width="140">
		<table width="100%">
		<tbody>
		<tr>
		<td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="120px"></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td width="450">
		<table width="100%">
		<tbody>
		<tr>
		<td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3>FORM</h3></td>
		</tr>
		<tr>
		<td style="text-align:center;border:0; text-transform: uppercase;"><h3>PEMUSNAHAN BAD PRODUK</h3></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td>
		<table width="101%" style="margin-left:-1px;">
		<tbody>
		<tr>
		<td style="border:0;height:28px;">&nbsp;No. Dokumen</td>
		<td style="border:0;height:28px;">:</td>
		<td style="border:0;height:28px;">&nbsp;FR-Prod-21</td>
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td>
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;01/01/2020</td>
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;1 dari 1</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</table>

		<table>
		<tbody>
		<tr>
		<td style="border:none; width:15px">Tanggal</td>
		<td style="border:none; width:10px">: </td>
		<td style="border:none;">' . $v[0]->tgl . '</td>
		</tr>
		</tbody>
		</table>



		<table class="table table-bordered" id="datatables" style="border: 1px solid black; border-collapse: collapse; width: 100%">
		<thead class="table text-light bg-info ">
		<tr>
		<th style="border: 1px solid black;" rowspan="2">No.</th>
		<th style="border: 1px solid black;" rowspan="2">Kode Produk</th>
		<th style="border: 1px solid black;" rowspan="2">Varian</th>
		<th style="border: 1px solid black;" rowspan="2">Qty (Kg)</th>
		<th style="border: 1px solid black;" colspan="2">Paraf</th>
		</tr>
		<tr>
		<th style="border: 1px solid black;">Checker</th>
		<th style="border: 1px solid black;">QC</th>
		</tr></thead>

		<tbody>';
		$no = 1;
		foreach ($v as $row) {

			$html .= '<tr style="text-align: center;">
			<td width="1">' . $no . '</td>
			<td>' . $row->kode_produksi . '</td>
			<td>' . $row->varian . '</td>
			<td>' . $row->qty_kg . '</td>
			<td>' . $row->username . '</td>
			<td>' . $row->acc_qc . '</td>
			</tr>';
			$no++;
		}
		$html .= '</tbody></table>';
		$html .= '<br><table width="100%">
		<tr>

		<td style="width: 100px; text-align: center;">Mengetahui</td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Disetujui</td>
		</tr>
		<tr>

		<td style="height: 80px; width: 100px; text-align:center;">' . $v[0]->kr_name . '</td>
		<td style="height: 80px; border: none; width: 200px;"></td>
		<td style="height: 80px; width: 100px; text-align:center;">' . $v[0]->spv . '</td>
		</tr>
		<tr>

		<td style="width: 100px; text-align: center;">Koordinator</td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Spv. Produksi</td>
		</tr>
		</table>';

		$html .= '</body></html>';
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream("FR-Prod-21_Formulir_.pdf", array("Attachment" => false));
	}
}
