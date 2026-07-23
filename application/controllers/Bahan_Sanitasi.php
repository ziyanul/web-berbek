<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Bahan_Sanitasi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('bahan_sanitasi_model');
		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->bahan_sanitasi_model->get_all(),
			'active_nav' => 'bahan_sanitasi'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('bahan_sanitasi/bahan_home',$data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->bahan_sanitasi_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
        $today = date('Y-m-d');
        $tgl = date('Y-m-d', strtotime($today));
			$insert = $this->bahan_sanitasi_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('bahan_sanitasi/detail_'.$tgl);
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('bahan_sanitasi/detail_'.$tgl);
			}
		}

		$data = array(
			'jenis' => $this->bahan_sanitasi_model->get_item(),
			'urut' => $this->bahan_sanitasi_model->generate_reservasi_number(),
			'active_nav' => 'bahan_sanitasi'
		);
	
		$this->load->view('partials/head', $data);
		$this->load->view('bahan_sanitasi/bahan_tambah');
		$this->load->view('partials/footer');
	}

	public function detail($tanggal)
	{
		$data = array(
			'data' => $this->bahan_sanitasi_model->get_all_sanitasi($tanggal),
			'active_nav' => 'bahan_sanitasi'
		);
	
		$this->load->view('partials/head', $data);
		$this->load->view('bahan_sanitasi/bahan_detail', $data);
		$this->load->view('partials/footer');
	}

    public function approval_sanitasi($tanggal, $area_uuid)
{
    $update = $this->bahan_sanitasi_model->approval_sanitasi($tanggal, $area_uuid);

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

	public function kirim($bahan_uuid)
	{

		$data = array(
			'data' => $this->bahan_sanitasi_model->get_kirim_sanitasi($bahan_uuid),
			'nav' => $this->bahan_sanitasi_model->get_by_uuid($bahan_uuid),
			'active_nav' => 'bahan_sanitasi'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('bahan_sanitasi/bahan_kirim', $data);
		$this->load->view('partials/footer');
	}
	public function diterima($uuid)
	{
		$update = $this->bahan_sanitasi_model->update_diterima($uuid);
		if ($update) {
			$this->session->set_flashdata('success_msg', 'Bahan Berhasil Diterima');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Bahan Gagal Diterima');
			echo json_encode(array('status' => FALSE));
		}
	}

    public function get_item_sanitasi($uuid)
	{
		$data = $this->bahan_sanitasi_model->get_item_sanitasi($uuid);
		print_r(json_encode($data));
	}

	public function form($tanggal)
        {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $dompdf = new Dompdf($options);

            $data = $this->bahan_sanitasi_model->get_form_sanitasi($tanggal);

            $logo = base_url("assets/img/cpi-logo.jpg");

            $html = '
            <html>
            <head>
            <title>FR-Prod-36 FORM PENERIMAAN BAHAN BAKU AREA PRODUKSI</title>
            <meta name="author" content="Arthur Herbert Fonzarelli">
            <meta name="keywords" content="fonzie, cool, ehhhhhhh">
            </head>
            <body>

            <style>
            @page { margin: 10px; }
            body {sans-serif; font-size: 10px; }
            table { width: 100%; border-collapse: collapse; }
            table tr td{border:1px solid #000;}
            table thead tr {background-color:#dbe5f1}
            table thead tr#standar{background-color:#b8cce4!important;}
            table.data tr th{border:1px solid #000;text-align:center;font-size:12px;}
            .data th, .data td { padding: 5px; }
            table.data tr td{text-align:center;}
            </style>
            <table width="100%">
            <tr>
            <td width="100">
            <table width="100%">
            <tbody>
            <tr>
            <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="100px"></td>
            </tr>
            </tbody>
            </table>
            </td>
            <td width="650">
            <table width="100%">
            <tbody>
            <tr>
            <td style="text-align:center;border-top:0;border-left:0;border-right:0;font-size: 12px;"><h2>FORM</h2></td>
            </tr>
            <tr>
            <td style="text-align:center;border:0;font-size: 12px;"><h2>PENERIMAAN BAHAN BAKU AREA PRODUKSI</h2></td>
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
            <td style="border:0;height:28px;">&nbsp;FR-Prod-36</td> 
            </tr>
            <tr>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
            <td style="border-left:0;border-right:0;height:28px;">:</td>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td> 
            </tr>
            <tr>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
            <td style="border-left:0;border-right:0;height:28px;">:</td>
            <td style="border-left:0;border-right:0;height:28px;">&nbsp;21/09/2017</td> 
            </tr>
            <tr>
            <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
            <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
            <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;4 dari 5</td> 
            </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </table><br>

            <table style="font-size: 12px;">
            <tbody>
            <tr>
            <td style="border:none;">Permintaan</td>
            <td style="border:none;"> : Sanitasi</td>
            <td style="border:none;"></u></td><td style="border:none;"  width="700px"></td><td style="border:none;">No. Reservasi</td>
            <td style="border:none;"> : '. sprintf("%04d", ($data[0]->no_reservasi)) .'</td>


            </tr>
            <tr>
            <td style="border:none;">Tanggal</td>
            <td style="border:none;"> : '. $data[0]->tgl .' </td>
            </tr>
            
            </tbody>
            </table><br>

            <table class="data" width="100%">
            <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th style="text-align:left;">Item Barang</th>
                            <th>Satuan</th>
                            <th>Quantity Reservasi</th>
                            <th>Quantity Dikirim</th>
                            <th>Waktu Kirim</th>
                            <th>Waktu Terima</th>
                            <th>Kode Produk</th>
                            <th>Exp Date Produk</th>
                        </tr>
                    </thead>
            <tbody>';
            $no = 1;
            $itemGroups = [];

            // Mengelompokkan data berdasarkan item_barang
            foreach ($data as $row) {
                $key = $row->item_barang; // Kunci pengelompokan berdasarkan item_barang
                if (!isset($itemGroups[$key])) {
                    $itemGroups[$key] = [];
                }
                $itemGroups[$key][] = $row;
            }

            // Proses penggabungan dengan sub-rowspan
            foreach ($itemGroups as $key => $group) {
                $itemRowspan = count($group); // Rowspan untuk item_barang dan satuan
                $qtyReservasiProcessed = []; // Untuk melacak qty_reservasi yang sudah diproses

                foreach ($group as $index => $row) {
                    $html.= '<tr>';
                    if ($index === 0) {
                        // Merge item_barang dan satuan pada baris pertama grup
                        $html.= '<td rowspan="' . $itemRowspan . '">' . $no . '</td>';
                        $html.= '<td style="text-align:left;" rowspan="' . $itemRowspan . '">' . $row->item_barang . '</td>';
                        $html.= '<td rowspan="' . $itemRowspan . '">' . $row->satuan . '</td>';
                        $no++;
                    }

                    // Sub-rowspan untuk qty_reservasi
                    if (!in_array($row->qty_reservasi, $qtyReservasiProcessed)) {
                        $qtyRowspan = count(array_filter($group, function ($r) use ($row) {
                            return $r->qty_reservasi === $row->qty_reservasi;
                        }));
                        $html.= '<td rowspan="' . $qtyRowspan . '">' . $row->qty_reservasi . '</td>';
                        $qtyReservasiProcessed[] = $row->qty_reservasi;
                    }

                    // Kolom lainnya tanpa merge
                    $html.= '<td>' . $row->qty_dikirim . '</td>';
                    $html.= '<td>' . $row->jam_kirim . '</td>';
                    $html.= '<td>' . $row->jam_terima . '</td>';
                    $html.= '<td>' . $row->kode_produk . '</td>';
                    $html.= '<td>' . $row->exp_date . '</td>';
                }
            }
        $html.='
        </tbody>
        </table>

<br>
<span style="font-size:12px;"><b>Note :</b><br>
    *) [&check;] Jika kondisi kemasan Produk tidak sobek, tidak berlubang, tidak berdebu <b>||</b> [x] Jika kondisi
    kemasan Produk sobek, berlubang, berdebu <br>
    **) [&check;] Tidak terdapat kontaminasi <i>Allergen</i> dan <i>Non Allergen</i> <b>||</b> [x] Terdapat kontaminasi
    <i>Allergen</i> (Bahan baku tercampur <i>Allergen</i> dan <i>Non Allergen</i>)
</span>
<br><br>

<table style="font-size: 12px;" width="100%">
    <tbody>
        <tr style="text-align:center;">
            <td style="text-align: center; background-color: #dbe5f1;">Dibuat:</td>
            <td style="border:none;" width="15%"></td>

            <td style="text-align: center; background-color: #dbe5f1;">Diketahui:</td>
            <td style="border:none;" width="15%"></td>

            <td style="text-align: center; background-color: #dbe5f1;">Dikirim:</td>
            <td style="border:none;" width="15%"></td>

            <td style="text-align: center; background-color: #dbe5f1;">Disetujui:</td>
        </tr>';

        $html .= '<tr style="text-align:center;">';
            $html .= '<td>' . $row->fullname . '</td>';
            $html .= '<td style="border:none;" width="15%"><br><br><br></td>';
            $html .= '<td>' . $row->acc_qc . '</td>';
            $html .= '<td style="border:none;" width="15%"><br><br><br></td>';
            $html .= '<td>' . $row->pengirim . '</td>';
            $html .= '<td style="border:none;" width="15%"><br><br><br></td>';
            $html .= '<td>' . $row->spv . '</td>';
            $html .= '</tr>

        <tr style="text-align:center;">
            <td>Koordinator</td>
            <td style="border:none;" width="15%"></td>
            <td>QC</td>
            <td style="border:none;" width="15%"></td>
            <td>Warehouse</td>
            <td style="border:none;" width="15%"></td>

            <td>Spv. Produksi</td>
        </tr>
    </tbody>
</table>

</html>';

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('FOLIO', 'landscape');

// Render the HTML as PDF
$dompdf->render();


$dompdf->stream("FR-Prod-36_Formulir_.pdf", array("Attachment" => false));

}
}