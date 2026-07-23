<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Chemical extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Chemical_model');
		$this->load->model('Am_model');
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
			'data' => $this->Chemical_model->get_chemical_stock(),
			'active_nav' => 'chemical-data'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('chemical/larutan', $data);
		$this->load->view('partials/footer');
	}

	public function master()
	{
		$data = array(
			'data' => $this->Chemical_model->get_all_nama(),
			'active_nav' => 'chemical-master'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/master', $data);
		$this->load->view('partials/footer');
	}
	public function persen()
	{
		$data = array(
			'data' => $this->Chemical_model->get_persen(),
			'active_nav' => 'chemical-persen'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/persen', $data);
		$this->load->view('partials/footer');
	}
	public function detailchemical($uuid)
	{
		$data = array(
			'data' => $this->Chemical_model->get_chemical_nama($uuid),
			'chemical' => $this->Chemical_model->get_chemical_by_uuid($uuid),
			'active_nav' => 'chemical-master'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/detail-master', $data);
		$this->load->view('partials/footer');
	}
	public function tambah()
	{
		$rules = $this->Chemical_model->rules();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Chemical_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
		}
		$data = array(
			'data' => $this->Chemical_model->get_all_nama(),
			'active_nav' => 'chemical-data'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/tambah', $data);
		$this->load->view('partials/footer');
	}
	public function larutan()
	{
		$rules = $this->Chemical_model->rules1();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Chemical_model->insert_pengenceran();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical');
            // }
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
		}

		$data = array(
			// 'master' => $this->Chemical_model->get_all_chemical_master_data(),
			// 'chemical' => $this->Chemical_model->get_all_chemical_data(),
			'kode' => $this->Chemical_model->get_kode_chemical(),
			'active_nav' => 'chemical-data'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('chemical/tambah-pengenceran', $data);
		$this->load->view('partials/footer');
	}
	public function tambahmaster()
	{
		$rules = $this->Chemical_model->rules_master();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Chemical_model->insert_master();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical/master');
            // }
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
		}
		$data = array(
			'active_nav' => 'chemical-master'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/tambah-master', $data);
		$this->load->view('partials/footer');
	}
	public function tambahpersen()
	{
		$rules = $this->Chemical_model->rules_persen();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Chemical_model->insert_persen();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical/persen');
            // }
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
		}

		$data = array(
			'data' => $this->Chemical_model->get_all_chemical_master_data(),
			'active_nav' => 'chemical-persen'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('chemical/tambah-persen', $data);
		$this->load->view('partials/footer');
	}

	public function editpersen($uuid)
	{
		$rules = $this->Chemical_model->rules_persen();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Chemical_model->update_persen($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical/persen');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
		}

		$data = array(
			'data' => $this->Chemical_model->get_persen_name($uuid),
			'chemical' => $this->Chemical_model->get_all_chemical_master_data(),
			'active_nav' => 'chemical-persen'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('chemical/edit-persen', $data);
		$this->load->view('partials/footer');
	}

	public function get_chemical_name($uuid)
	{
		$data = $this->Chemical_model->get_chemical_nama($uuid);
		print_r(json_encode($data));
	}

	public function tambahchemical($uuid) //tambah stock chemical murni
	{
		$rules = $this->Chemical_model->rules_chemical();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Chemical_model->insert_chemical($uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('chemical/master');
            // }
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
				redirect('chemical/master');
			}
		}
		$data = array(
			'data' => $this->Chemical_model->get_chemical_nama($uuid),
			'active_nav' => 'chemical-data'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('chemical/tambah-chemical', $data);
		$this->load->view('partials/footer');
	}
	public function get_persentase_name($uuid)
	{
		$persen = $this->Chemical_model->get_persen_name($uuid);
		echo json_encode($persen);
	}

	public function deletpersen($uuid) 
	{

		$this->Chemical_model->delete_persen($uuid);
		redirect('chemical/persen/');
	}

	public function deletechemical($uuid) 
	{

		$this->Chemical_model->delete_chemical($uuid);
		redirect('chemical/master/');
	}

	public function pengenceran()
	{
		$data = array(
			'data' => $this->Chemical_model->get_larutan_data(),
			'active_nav' => 'pengenceran'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('chemical/pengenceran', $data);
		$this->load->view('partials/footer');
	}

	public function formpengenceran($tanggal)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);
		$data = $this->Chemical_model->get_pengenceran_data($tanggal);
		$logo = base_url("assets/img/cpi-logo.jpg");
		$html = '<html>
		<head>
		<title>FR-Prod-35 FORM PENGENCERAN CHEMICAL</title>
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
		<td style="text-align:center;border:0;"><h3>PENGENCERAN CHEMICAL</h3></td>
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
		<td style="border:0;height:28px;">&nbsp;FR-Prod-35</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;1</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
		<td style="border-left:0;border-right:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;height:28px;">&nbsp;21/09/2020</td> 
		</tr>
		<tr>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
		<td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;1</td> 
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</table>
		<table style="margin-top: 10px; font-size:12px; margin-bottom: 10px; border: none;">
		<tbody>
		<tr style="border: none;">
		<td width="200px" style="border: none; text-align: left;">Tanggal</td><td width="250px" style="border: none;">: ' .$data[0]->tgl. '</td>
		</tr>
		
		</tbody>
		</table><table class="data" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                         <tr>
                            <th rowspan="2" class="font-weight-bold">Nama Chemical</th>
                            <th colspan="2" class="font-weight-bold">Pengenceran (ml)</th>
                            <th rowspan="2" class="font-weight-bold">Total Larutan Chemical</th>
                            <th rowspan="2" width="1" class="font-weight-bold">Petugas</th>
                            <th rowspan="2" width="1" class="font-weight-bold">QC</th>
                            <th rowspan="2" class="font-weight-bold">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Chemical</th>
                            <th>Air</th>
                        </tr> 
                    </thead>
                    <tbody>';
  
    foreach ($data as $row) {
  
        $html .='<tr>
            <td>'.$row->chemical_name.'</td>
            <td>'.$row->chemical_used.'</td>
            <td>'. $row->larutan - $row->chemical_used. '</td>
            <td>' . $row->larutan .'</td>
            <td>' . $row->username . '</td>
            <td></td>
            <td></td>
        </tr>';
    }
		$html .= '</tbody></table><br><br><table width="100%">
		<tr>
		<td style="width: 100px; text-align: center;">Diperiksa Oleh</td>
		<td style="border: none; width: 30px;"></td>
		<td style="border: none; width: 30px;"></td>
		<td style="width: 100px; text-align: center;">Mengetahui</td> 
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
		$dompdf->stream("FR-Prod-35_Formulir Pengenceran Chemical ".$data[0]->tgl.".pdf", array("Attachment" => false));
	}
}