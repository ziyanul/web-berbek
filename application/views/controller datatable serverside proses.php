<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_sampel extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('penerimaan_sampel_model');
		$this->load->model('produk_model');
		$this->load->model('user_model');
		$this->load->model('pdf_model');

		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$username = $this->auth_model->current_user()->U_USERNM;
		$data = array(
			'title' => 'Penerimaan Sampel',
			'active_nav' => 'penerimaan-sampel',
			'data' => $this->penerimaan_sampel_model->get_all_data(),
			'username' => $username
		);

		$this->load->view('partials/head', $data);
		$this->load->view('lab/penerimaan_sampel/penerimaan_sampel', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$username = $this->auth_model->current_user()->U_USERNM;
		$rules = $this->penerimaan_sampel_model->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == TRUE){
			$insert = $this->penerimaan_sampel_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Penerimaan Sampel berhasil di simpan');
				redirect('penerimaan-sampel');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Penerimaan Sampel gagal di simpan');
				redirect('penerimaan-sampel');
			}
		}


		$jenis_pengujian = array('Produk','Frozen','Seasoning','Bahan Penunjang');

		$data = array(
			'title' => 'Tambah Penerimaan Sampel',
			'active_nav' => 'penerimaan-sampel',
			'jenis' => $this->produk_model->get_all_data(),
			'pengirim' => $this->user_model->get_all_qc(),
			'jenis_pengujian' => $jenis_pengujian,
			'username' => $username
		);

		$this->load->view('partials/head', $data);
		$this->load->view('lab/penerimaan_sampel/penerimaan_sampel_tambah', $data);
		$this->load->view('partials/footer');
	}

	public function edit($id)
	{
		$username = $this->auth_model->current_user()->U_USERNM;
		$this->form_validation->set_rules('kode','Kode','required|max_length[10]');
		$this->form_validation->set_rules('best_before','Best Before','required');

		if($this->form_validation->run() == TRUE){
			$update = $this->penerimaan_sampel_model->update($id);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Penerimaan Sampel berhasil di perbarui');
				redirect('penerimaan-sampel');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Penerimaan Sampel tidak ada yang di perbarui');
				redirect('penerimaan-sampel');
			}
		}

		$sampel = [];
		if (!empty($id)) {
			$sampel = $this->penerimaan_sampel_model->get_by_id($id);
		}

		$jenis_pengujian = array('Produk','Frozen','Seasoning','Bahan Penunjang');

		$data = array(
			'title' => 'Edit Penerimaan Sampel',
			'active_nav' => 'penerimaan-sampel',
			'jenis' => $this->produk_model->get_all_data(),
			'pengirim' => $this->user_model->get_all_qc(),
			'sampel' => $sampel,
			'jenis_pengujian' => $jenis_pengujian,
			'username' => $username
		);

		$this->load->view('partials/head', $data);
		$this->load->view('lab/penerimaan_sampel/penerimaan_sampel_edit', $data);
		$this->load->view('partials/footer');
	}

	public function ajax()
	{
		$list = $this->penerimaan_sampel_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->S_DATE_NEW;
                $row[] = $field->S_JENIS;
                $row[] = $field->S_KODE;
                $row[] = $field->S_EXPIRED_NEW;
                $row[] = $field->S_NOTE;
                $row[] = $field->jenis_pengujian;
                $row[] = $field->S_PENGIRIM;
                $row[] = $field->S_PENERIMA;
                $row[] = $field->S_PROGRESS_TXT;
                $row[] = $field->actions;

                $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->penerimaan_sampel_model->count_all(),
                "recordsFiltered" => $this->penerimaan_sampel_model->count_filtered(),
                "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function document($date)
	{

		$data = array(
			'data' => $this->penerimaan_sampel_model->get_by_date($date), 
			'date' => $date
		);

		$logo = base_url("assets/img/cpi.jpg");


		$html = '
		<style>
			table tr td{border:1px solid #000;}
			table.data tr th{border:1px solid #000;text-align:center;font-size:11px;height:20px;}
			table.data tr td{text-align:center;font-size:10px;}
		</style>
		<table width="100%">
			<tr>
				<td width="15%">
					<table>
						<tbody>
							<tr>
								<td rowspan="2" align="center" valign="middle" height="48px"><img src="'.$logo.'" width="60px"></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="52%">
					<table>
						<tbody>
							<tr>
								<td style="text-align:center;" rowspan="2" height="24px"><h3>FORM</h3></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td style="text-align:center;" rowspan="2" height="24px"><h3>Penerimaan Sampel</h3></td>
							</tr>
							<tr><td></td></tr>
						</tbody>
					</table>
				</td>
				<td>
					<table style="font-size:8px;font-weight:bold;">
						<tbody>
							<tr>
								<td height="19.5px">&nbsp;No. Dokumen</td>
								<td>&nbsp;FR-LAB-01</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Revisi</td>
								<td>&nbsp;1</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Tanggal Efektif</td>
								<td>&nbsp;01 januari 2020</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Halaman</td>
								<td>&nbsp;1 dari 1</td> 
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<br>
		<table>
			<tbody>
				<tr>
					<td style="border:none;" width="80px;">Tanggal : </td>
					<td style="border:none;">'.date('d F Y', strtotime($data['date'])).'</td>
				</tr>
			</tbody>
		</table>
		<br>
		<br>
		<table class="data">
			<thead>
				<tr>
					<th width="6%">No</th>
					<th width="20%">Jenis</th>
					<th width="14.8%">Kode Produk</th>
					<th width="14.8%">Best Before</th>
					<th width="14.8%">Keterangan</th>
					<th width="14.8%">Pengirim</th>
					<th width="14.8%">Penerima</th>
				</tr>
			</thead>
			<tbody>
		';
			
		$no=1;
		foreach ($data['data'] as $row) {
			$best_before = date('d/m/Y', strtotime($row->S_EXPIRED));
			$html .='
				<tr>
					<td width="6%">'.$no.'</td>
					<td width="20%">'.$row->S_JENIS.'</td>
					<td width="14.8%">'.$row->S_KODE.'</td>
					<td width="14.8%">'.$best_before.'</td>
					<td width="14.8%">'.$row->S_NOTE.'</td>
					<td width="14.8%">'.$row->S_PENGIRIM.'</td>
					<td width="14.8%">'.$row->S_PENERIMA.'</td>
				</tr>
			';
			$no++;
		}
				

		$html .= '
			</tbody>
		</table>
		<br>
		<br>
		<br>
		<table>
			<tbody>
				<tr>
					<td style="border:none;" width="85%"></td>
					<td style="border:none;" width="15%" align="center">
						Disetujui oleh,
						<br>
						<br>
						<br>
						<br>
						<u>Indra Lesmana</u>
						<br>
						Laboratorium
					</td>
				</tr>
			</tbody>
		</table>
		';

		$this->pdf_model->pdf($html, 'Form Penerimaan Sampel');
	}

	public function realse_document($date)
	{

		$data = array(
			'data' => $this->penerimaan_sampel_model->get_by_date_realase($date), 
			'date' => $date
		);

		// print_r(sizeof($data['data']));
		// print_r($data['data']);
		// echo "<br>";

		$logo = base_url("assets/img/cpi.jpg");


		$html = '
		<style>
			table tr td{border:1px solid #000;}
			table.data tr th{border:1px solid #000;text-align:center;font-size:11px;height:20px;}
			table.data tr td{text-align:center;font-size:10px;}
		</style>
		<table width="100%">
			<tr>
				<td width="15%">
					<table>
						<tbody>
							<tr>
								<td rowspan="2" align="center" valign="middle" height="48px"><img src="'.$logo.'" width="60px"></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="52%">
					<table>
						<tbody>
							<tr>
								<td style="text-align:center;" rowspan="2" height="24px"><h3>FORM</h3></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td style="text-align:center;" rowspan="2" height="24px"><h3>Penerimaan Sampel</h3></td>
							</tr>
							<tr><td></td></tr>
						</tbody>
					</table>
				</td>
				<td>
					<table style="font-size:8px;font-weight:bold;">
						<tbody>
							<tr>
								<td height="19.5px">&nbsp;No. Dokumen</td>
								<td>&nbsp;FR-LAB-01</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Revisi</td>
								<td>&nbsp;1</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Tanggal Efektif</td>
								<td>&nbsp;01 januari 2020</td> 
							</tr>
							<tr>
								<td height="19.5px">&nbsp;Halaman</td>
								<td>&nbsp;1 dari 1</td> 
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<br>
		<table>
			<tbody>
				<tr>
					<td style="border:none;" width="80px;">Tanggal : </td>
					<td style="border:none;">'.date('d F Y', strtotime($data['date'])).'</td>
				</tr>
			</tbody>
		</table>
		<br>
		<br>
		<table class="data">
			<thead>
				<tr>
					<th width="6%">No</th>
					<th width="20%">Jenis</th>
					<th width="14.8%">Kode Produk</th>
					<th width="14.8%">Best Before</th>
					<th width="14.8%">Keterangan</th>
					<th width="14.8%">Pengirim</th>
					<th width="14.8%">Penerima</th>
				</tr>
			</thead>
			<tbody>
		';
			
		$no=1;
		foreach ($data['data'] as $row) {
			$best_before = date('d/m/Y', strtotime($row->S_EXPIRED));
			$html .='
				<tr>
					<td width="6%">'.$no.'</td>
					<td width="20%">'.$row->S_JENIS.'</td>
					<td width="14.8%">'.$row->S_KODE.'</td>
					<td width="14.8%">'.$best_before.'</td>
					<td width="14.8%">'.$row->S_NOTE.'</td>
					<td width="14.8%">'.$row->S_PENGIRIM.'</td>
					<td width="14.8%">'.$row->S_PENERIMA.'</td>
				</tr>
			';
			$no++;
		}
				

		$html .= '
			</tbody>
		</table>
		<br>
		<br>
		<br>
		<table>
			<tbody>
				<tr>
					<td style="border:none;" width="85%"></td>
					<td style="border:none;" width="15%" align="center">
						Disetujui oleh,
						<br>
						<br>
						<br>
						<br>
						<u>Indra Lesmana</u>
						<br>
						Laboratorium
					</td>
				</tr>
			</tbody>
		</table>
		';

		$this->pdf_model->pdf($html, 'Form Penerimaan Sampel');
	}


	public function get_product_by_id($id, $table)
	{
		$data = $this->produk_model->get_product_by_id($id, $table);
		print_r(json_encode($data));
	}

	public function sampel_accepted($id)
	{
		$data = $this->penerimaan_sampel_model->accept($id);
		
		if ($data) {
			$this->session->set_flashdata('success_msg', 'Data Penerimaan Sampel telah di terima');
			redirect('penerimaan-sampel');
		} else {
			$this->session->set_flashdata('error_msg', 'Data Penerimaan Sampel tidak ada yang di terima');
			redirect('penerimaan-sampel');
		}
	}
}