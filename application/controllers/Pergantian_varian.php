<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Pergantian_varian extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->model('Pg_varian_model');
		// $this->load->model('Counter_model');
		$this->load->library('form_validation');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Pg_varian_model->get_all(),
			'active_nav' => 'pergantian_varian'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pg_varian/pg_varian',$data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Pg_varian_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Pg_varian_model->insert();
		
      if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil di tambah.');
				redirect('pergantian_varian');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pergantian Varian gagal di tambah.');
        redirect('pergantian_varian');
			}
		}

		$data = array(
			'varian' => $this->Pg_varian_model->get_all_varian(),
			'active_nav' => 'pergantian_varian'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pg_varian/pg_tambah', $data);
		$this->load->view('partials/footer');
		}	
	public function detail($tanggal, $shift, $area)
	{
		$data = array(
			'active_nav' => 'pergantian_varian',
			'data' => $this->Pg_varian_model->get_by_tanggal($tanggal, $shift, $area),
      
      
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pg_varian/pg_detail', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid)
	{
		$rules = $this->Pg_varian_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
		$area = $this->input->post('area');
		$shift = $this->input->post('shift');
	$r=$this->Pg_varian_model->get_by_uuid($uuid);
		$update = $this->Pg_varian_model->update($uuid);
		if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil di ubah.');
				redirect('pergantian_varian/detail/'.$r->tanggal.'/'.$shift.'/'.$area);
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pergantian Varian gagal di ubah.');
        redirect('pergantian_varian/detail/'.$r->tanggal.'/'.$shift.'/'.$area);
			}
		}

		$data = array(
			'data' => $this->Pg_varian_model->get_by_uuid($uuid),
			'varian' => $this->Pg_varian_model->get_all_varian(),
			'active_nav' => 'pergantian_varian'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pg_varian/pg_edit', $data);
		$this->load->view('partials/footer');
		}	
	public function form($tanggal, $shift, $area)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);

            $v = $this->Pg_varian_model->get_by_tanggal($tanggal, $shift, $area);
            $logo = base_url("assets/img/cpi-logo.jpg");

            $html = '
            <html>
            <head>
            <title>FR-Prod-23 PERGANTIAN VARIAN</title>
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
            <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="120px"></td>
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
            <td style="text-align:center;border:0; text-transform: uppercase;"><h3>PERGANTIAN VARIAN '.$v[0]->area_name.'</h3></td>
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
            <td style="border:0;height:28px;">&nbsp;FR-Prod-23</td> 
            </tr>
            <tr>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
            <td style="border-left:0;border-right:0;height:28px;">:</td>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td> 
            </tr>
            <tr>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
            <td style="border-left:0;border-right:0;height:28px;">:</td>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;28/08/2021</td> 
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
            <td style="border:none;">Tanggal</td>
            <td style="border:none;">: </td>
            <td style="border:none;">'. $v[0]->tgl .'</td>
            </tr>
            <tr>
            <td style="border:none;">Shift</td>
            <td style="border:none;">: </td>
            <td style="border:none;">'. $v[0]->shift_name .'</td>
            </tr>
            </tbody>
            </table>
			<table class="table table-bordered" id="datatables" style="border: 1px solid black; border-collapse: collapse;">
  <thead class="table text-light bg-info">
    <tr>
      <th rowspan="2" style="border: 1px solid black;">No.</th>
      <th colspan="2" style="border: 1px solid black;">Dari Proses Sortasi</th>
      <th colspan="2" style="border: 1px solid black;">Ke Proses Sortasi</th>
      <th colspan="2" style="border: 1px solid black;">Kondisi</th>
      <th rowspan="2" style="border: 1px solid black;">Keterangan</th>
      <th colspan="2" style="border: 1px solid black;">TTD</th>
    </tr>
    <tr>
      <th style="border: 1px solid black;">Varian</th>
      <th style="border: 1px solid black;">Kode Batch</th>
      <th style="border: 1px solid black;">Varian</th>
      <th style="border: 1px solid black;">Kode Batch</th>
      <th style="border: 1px solid black;">Bersih dari Kontaminasi</th>
      <th style="border: 1px solid black;">Belum Bersih dari Kontaminasi</th>
      <th style="border: 1px solid black;">KR/Checker</th>
      <th style="border: 1px solid black;">QC</th>
    </tr>
  </thead>
  <tbody>';
                      $no = 1;
                        foreach ($v as $row) {
                          
                            $html .='<tr style="text-align: center;">
                                <td width="1">'.$no.'</td>
                                <td>'.$row->varian_1.'</td>
                                <td>'.$row->batch_1.'</td>
                                <td>'.$row->varian_2.'</td>
                                <td>'.$row->batch_2.'</td>
                                <td>'.$row->kondisi1.'</td>
                                <td>'.$row->kondisi2.'</td>
                                <td>'.$row->keterangan.'</td>
                                <td>'.$row->username.'</td>
                                <td>'.$row->acc_qc.'</td>

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

<td style="height: 80px; width: 100px;"></td>
<td style="height: 80px; border: none; width: 200px;"></td>
<td style="height: 80px; width: 100px;"></td> 
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
$dompdf->stream("FR-Prod-23_Formulir_.pdf", array("Attachment" => false));
		
	}
}