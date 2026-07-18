<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pbelah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('area_model');
		$this->load->model('sub_area_model');
		$this->load->model('pbelah_model');
		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		
        
        $data = array(
			'data' => $this->pbelah_model->get_data_by_tanggal(),
			'active_nav' => 'pbelah'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/pbelah');
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->pbelah_model->rules3();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
	
			$insert = $this->pbelah_model->insert_form();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pbelah/');
			} else {
				redirect('pbelah/');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
        
        $data = array(
			// 'data' => $this->pbelah_model->get_all_jenis_pbelah(),
            'area' => $this->area_model->get_all(),
			'active_nav' => 'pbelah'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/tambah');
		$this->load->view('partials/footer');
	}

	public function detail($tanggal)
	{
		
        
        $data = array(
			'data' => $this->pbelah_model->get_all_pengecekan($tanggal),
			'hari' => $this->pbelah_model->get_data_by_tanggal(),
			'active_nav' => 'pbelah'
		);


		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/detail-pbelah');
		$this->load->view('partials/footer');
	}

	public function editdetail($uuid)
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
		$redi = $this->pbelah_model->get_pengecekan_by_uuid($uuid);
			$update = $this->pbelah_model->update_detail($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pbelah/detail/'. $redi->tanggal);
			} else {
				redirect('pbelah/detail/'. $redi->tanggal);
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
        
        $data = array(
			'data' => $this->pbelah_model->get_pengecekan_by_uuid($uuid),
			'active_nav' => 'pbelah'
		);


		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/edit-detail-pbelah');
		$this->load->view('partials/footer');
	}

	public function jenis()
	{
        
        $data = array(
			'data' => $this->pbelah_model->get_all_jenis_pbelah(),
			'active_nav' => 'jenis-pbelah'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/jenis-pbelah');
		$this->load->view('partials/footer');
	}

	public function kode()
	{
        
        $data = array(
			'data' => $this->pbelah_model->get_all_kode(),
			'active_nav' => 'kode-pbelah'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/kode-pbelah');
		$this->load->view('partials/footer');
	}

	public function tambahjenis()
	{
		$rules = $this->pbelah_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
	
			$insert = $this->pbelah_model->insert_jenis();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pbelah/jenis');
			} else {
				redirect('pbelah/jenis');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
        
        $data = array(
            'area' => $this->area_model->get_all(),
			'active_nav' => 'jenis-pbelah'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/tambah-jenis');
		$this->load->view('partials/footer');
	}
	
	public function detailjenis($sub_area_uuid)
	{
		$rules1 = $this->pbelah_model->rules1();
		$this->form_validation->set_rules($rules1);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->pbelah_model->insert_kode($sub_area_uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pbelah/detailjenis/'.$sub_area_uuid);
			} else {
				redirect('pbelah/detailjenis'.$sub_area_uuid);
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		
		$data = array(
			'data' => $this->pbelah_model->get_all_by_sub_area($sub_area_uuid),
			'active_nav' => 'jenis-pbelah'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/jenis-detail');
		$this->load->view('partials/footer');
	}

	
	public function editjenispb($uuid) //harus ada parameternya
	{
		$rules = $this->pbelah_model->rules4();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true
	$area_name = $this->pbelah_model->get_jenis_by_uuid($uuid);
			$update = $this->pbelah_model->updatejenis($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
				redirect('pbelah/detailjenis/'.$area_name->sub_area_uuid);
			} else { // Jika update sama dg false
				redirect('pbelah/detailjenis/'. $area_name->sub_area_uuid);
				$this->session->set_flashdata('error_msg', 'Data di ubah.');
			}
		}

		$data = array(
			'data' => $this->pbelah_model->get_jenis_by_uuid($uuid),
			'active_nav' => 'jenis-pbelah'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/edit-jenispb', $data);
		$this->load->view('partials/footer');
	}

	

	public function detailkodepb($jenis_pbelah_uuid)
	{		
		$data = array(
			'data' => $this->pbelah_model->get_kode_by_jenis($jenis_pbelah_uuid),
			'nav' => $this->pbelah_model->get_jenis_for_nav($jenis_pbelah_uuid),
			'active_nav' => 'kode-pbelah'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/detail-kode-pb.php');
		$this->load->view('partials/footer');
	}

	public function tambahkode()
	{
		$rules1 = $this->pbelah_model->rules1();
		$this->form_validation->set_rules($rules1);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->pbelah_model->insert_kode($sub_area_uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pbelah/kode/');
			} else {
				redirect('pbelah/kode');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		
		$data = array(
			// 'data' => $this->pbelah_model->get_all_by_sub_area($sub_area_uuid),
			'area' => $this->pbelah_model->get_all_area(),
			'active_nav' => 'kode-pbelah'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/tambah-kode');
		$this->load->view('partials/footer');
	}

	public function editkodepb($uuid) //harus ada parameternya
	{
		$rules = $this->pbelah_model->rules2();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true
	$data = $this->pbelah_model->get_kode_uuid($uuid);
			$update = $this->pbelah_model->updatekode($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
				redirect('pbelah/detailkodepb/'.$data->jenis_pbelah_uuid);
			} else { // Jika update sama dg false
				redirect('pbelah/detailkodepb/'.$data->jenis_pbelah_uuid);
				$this->session->set_flashdata('error_msg', 'Data di ubah.');
			}
		}

		$data = array(
			'data' => $this->pbelah_model->get_kode_uuid($uuid),
			'active_nav' => 'kode-pbelah'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('pbelah/edit-kodepb', $data);
		$this->load->view('partials/footer');
	}

	public function get_lokasi_by_area($area_uuid)
	{
		$data = $this->sub_area_model->get_sub_area($area_uuid);
		print_r(json_encode($data));
	}

	public function get_jenis_by_sub_area($sub_area_uuid)
	{
		$data = $this->pbelah_model->get_jenis($sub_area_uuid);
		print_r(json_encode($data));
	}

public function get_kode_by_sub_area($sub_area_uuid)
{
    $kode_barang = $this->pbelah_model->get_kode_by_sub_area($sub_area_uuid);
    echo json_encode($kode_barang);
}


public function formpengecekan($tanggal) {
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    $data = $this->pbelah_model->get_all_pengecekan($tanggal);
    $hari = $this->pbelah_model->get_data_by_tanggal();
    $logo = base_url("assets/img/cpi-logo.jpg");

    // Header dokumen
    $header = '
        <table style="table-layout: fixed; width: 100%; border: 1px solid black">
  <tr>
    <td rowspan="4" style="border: 1px solid black;"><img src="'.$logo.'" width="120px"></td>
    <td style="text-align: center; border-right: 1px solid black; font-size: 26px;" rowspan="2" width="60%">FORM</td>
    <td>No. Dokumen</td>
    <td>:     FR-Prod-09</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid black; border-bottom: 1px solid black">Revisi</td>
    <td style="border-top: 1px solid black; border-bottom: 1px solid black">:     0</td>
  </tr>
  <tr>
    <td style="text-align: center; border-right: 1px solid black; border-top: 1px solid black; font-size: 24px;" rowspan="2" width="60%">PENGECEKAN BARANG PECAH BELAH</td>
    <td style="border-bottom: 1px solid black">Tanggal Efektif</td>
    <td style="border-bottom: 1px solid black">:     21-09-2017</td>
  </tr>
  <tr>
    <td>Halaman</td>
    <td>:    1 dari 4</td>
  </tr>
</table>
        <table style="margin-top: 10px; margin-bottom: 6px; font-size: 12px;">
            <tr>
                <td width="200px" style="border: none;">Tanggal</td>
                <td style="border-bottom: 1px solid black ;">: ' . $hari[0]->nama_hari . ', ' . $hari[0]->tgl . '</td>
            </tr>
        </table>
    ';

    // Header tabel
    $tableHeader = '
        <table width="100%" style="font-size: 12px; border-collapse: collapse; border: 1px solid black;">
            <thead>
                <tr>
                    <th>Area</th>
                    <th style="border-right: 1px solid black; border-left: 1px solid black;">Sub Area</th>
                    <th>Jenis Barang</th>
                    <th style="border-right: 1px solid black; border-left: 1px solid black;">Kode Barang</th>
                    <th>Ya</th>
                    <th style="border-left: 1px solid black;">Tidak</th>
                </tr>
            </thead>
            <tbody>
    ';

    $html = '<html><head><style>@page { margin: 20px; } .page-break { page-break-after: always; }</style></head><body>';
    $html .= $header . $tableHeader;

    // Variabel kontrol area dan sub-area
    $currentArea = '';
    $currentSubArea = '';
    $currentJenisBarang = '';

    // Hitung jumlah baris untuk rowspan
    $areaCounts = [];
    foreach ($data as $row) {
        if (!isset($areaCounts[$row->nama_area])) {
            $areaCounts[$row->nama_area] = 0;
        }
        $areaCounts[$row->nama_area]++;
    }

    $renderedAreas = []; // Untuk melacak area yang sudah ditampilkan

    foreach ($data as $index => $row) {
        $html .= '<tr>';

        // Tampilkan area hanya sekali dengan rowspan
        if (!in_array($row->nama_area, $renderedAreas)) {
            $html .= '<td rowspan="' . $areaCounts[$row->nama_area] . '" style="border: 1px solid black; padding: 5px;">' . $row->nama_area . '</td>';
            $renderedAreas[] = $row->nama_area;
        }

        // Tampilkan sub-area jika berbeda
        if ($currentSubArea != $row->lokasi) {
            $html .= '<td style="border: 1px solid black; padding: 5px;">' . $row->lokasi . '</td>';
            $currentSubArea = $row->lokasi;
        } else {
            $html .= '<td style="border: 1px solid black; padding: 5px;"></td>';
        }

        // Tampilkan jenis barang jika berbeda
        if ($currentJenisBarang != $row->jenis_barang) {
            $html .= '<td style="border: 1px solid black; padding: 5px;">' . $row->jenis_barang . '</td>';
            $currentJenisBarang = $row->jenis_barang;
        } else {
            $html .= '<td style="border: 1px solid black; padding: 5px;"></td>';
        }

        // Kolom kode barang dan kondisi
        $html .= '
            <td style="border: 1px solid black; padding: 5px;">' . $row->kode_barang . '</td>
            <td style="border: 1px solid black; text-align: center; padding: 5px;">' . $row->baik . '</td>
            <td style="border: 1px solid black; text-align: center; padding: 5px;">' . $row->tidak . '</td>
        </tr>';

        // Tambahkan page break setiap 15 data (opsional)
        if (($index + 1) % 18 == 0) {
            $html .= '</tbody></table>';
            $html .= '<div class="page-break"></div>'; // Page break
            $html .= $header . $tableHeader; // Tambahkan ulang header di halaman baru
        }
    }

    // Tutup tabel dan dokumen HTML
    $html .= '</tbody></table>
	<table width="100%" style="margin-top: 50px">
<tbody>
<tr style="text-align:center;">
<td>Dibuat:</td>
<td style="border:none;" width="25%"></td>

<td>Disetujui:</td>
<td style="border:none;" width="25%"></td>

<td>Diketahui:</td>
</tr>
<tr>
<td><br> <br></td>
<td style="border:none;" width="25%"></td>

<td><br> <br></td>
<td style="border:none;" width="25%"></td>

<td><br> <br></td>
</tr>
<tr style="text-align:center;">
<td>Checker Filler</td>
<td style="border:none;" width="25%"></td>

<td>Koordinator MP</td>
<td style="border:none;" width="25%"></td>

<td>SPV. Produksi</td>
</tr>
</tbody>
</table>';
    $html .= '</body></html>';

    // Render dan keluarkan PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("FR-Prod-13_Formulir_.pdf", array("Attachment" => false));
}


}