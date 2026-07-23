<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Cekmesin_fillerbatch extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('area_model');
        $this->load->model('mesin_model');
        $this->load->model('Cekmesin_fillerbatch_model');
        $this->load->library('form_validation');
        $this->config->load('relasi_uuid');
        $this->filler = $this->config->item('filler_uuid');

        if(!$this->auth_model->current_user()){
            redirect('login');
        }
    }

    public function detail($uuid)
    {
        $data = array(
            'batch' => $this->Cekmesin_fillerbatch_model->get_batch($uuid),
            'data' => $this->Cekmesin_fillerbatch_model->get_by_uuid($uuid),
            'active_nav' => 'cekmesin_filler'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/detail', $data);
        $this->load->view('partials/footer');
    }

    public function detailcek($MN_BATCH)
    {
        $data = array(
            'data' => $this->Cekmesin_fillerbatch_model->get_cek_by_batch($MN_BATCH),
            'nav'   => $this->Cekmesin_fillerbatch_model->get_batch_uuid($MN_BATCH),
            'active_nav' => 'cekmesin_filler'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/detail-cek', $data);
        $this->load->view('partials/footer');
    }

    public function dataitem()
    {
        $data = array(
            'data' => $this->Cekmesin_fillerbatch_model->get_pm_filler(),
            'active_nav' => 'dataitem-batch'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/item', $data);
        $this->load->view('partials/footer');
    }

    public function tambahitem()
    {
        $rules = [
            [
                'field' => 'item',
                'label' => 'Item Kegiatan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Cekmesin_fillerbatch_model->insert_item();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('Cekmesin_fillerbatch/dataitem');

            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
                redirect('Cekmesin_fillerbatch/dataitem');
            }
        }

        $data = array(
            'mesin'      => $this->Cekmesin_fillerbatch_model->get_mesin_filler(),
            'active_nav' => 'dataitem-batch'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/tambah-item', $data);
        $this->load->view('partials/footer');
    }

    public function edititem($uuid)
    {
        $rules = [
            [
                'field' => 'item',
                'label' => 'Item Kegiatan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Cekmesin_fillerbatch_model->update_item($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('Cekmesin_fillerbatch/dataitem');

            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
                redirect('Cekmesin_fillerbatch/dataitem');
            }
        }

        $data = array(
            'area'      => $this->area_model->get_all(),
            'data'      => $this->Cekmesin_fillerbatch_model->get_item_by_uuid($uuid),
            'active_nav' => 'dataitem-batch'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/edit-item', $data);
        $this->load->view('partials/footer');
    }

    public function ceklist_batch($MN_BATCH) {
        $rules = [
            [
                'field' => 'mesin',
                'label' => 'Pilih Mesin',
                'rules' => 'required'
            ]
        ];

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) { 
            $insert_ceklist = $this->Cekmesin_fillerbatch_model->insert_cek_mesin($MN_BATCH);
            $t_planning = $this->Cekmesin_fillerbatch_model->get_batch_uuid($MN_BATCH);
            $t_planning_uuid = $t_planning->planprod_uuid;
            if ($insert_ceklist) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                redirect('Cekmesin_fillerbatch/detail/'.$t_planning_uuid);
            } else {
                $this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
                redirect('Cekmesin_fillerbatch/detail/'.$t_planning_uuid);
            }
        } 



        $data = array(
            'data' => $this->Cekmesin_fillerbatch_model->get_batch_uuid($MN_BATCH),
            'MN_BATCH' => $MN_BATCH,
            'area' => $this->area_model->get_all(),
            'active_nav' => 'cekmesin_filler'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cm-filler/tambah-batch', $data);
        $this->load->view('partials/footer');
    }

    public function get_item_by_mesin($mesin_uuid)
    {
        $data = $this->Cekmesin_fillerbatch_model->get_item_by_mesin($mesin_uuid);
        print_r(json_encode($data));
    }

    public function form($uuid)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $data = array(
            'data' => $this->Cekmesin_fillerbatch_model->get_by_uuid($uuid),
            'item' => $this->Cekmesin_fillerbatch_model->get_item_by_t_planning_uuid($uuid)
        );

        $logo = base_url("assets/img/cpi-logo.jpg");

        $html = '
        <html>
        <head>
        <title>FR-Prod-02 FORM PEMAKAIAN PVDC & WIRE</title>
        <meta name="author" content="Arthur Herbert Fonzarelli">
        <meta name="keywords" content="fonzie, cool, ehhhhhhh">
        </head>
        <body>

        <style>
        @page { margin: 5px; }
        body {sans-serif; font-size: 12px; }
        table {border-collapse: collapse; }
        table tr td{border:1px solid #000;}
        table thead tr {background-color:#dbe5f1}
        table thead tr#standar{background-color:#b8cce4!important;}
        table.data tr th{border:1px solid #000;text-align:center;font-weight:normal;font-size:10px;}
        table.data tr td{text-align:center;font-size:8px;}
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
        <td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3 style="font-weight:normal">FORM</h3></td>
        </tr>
        <tr>
        <td style="text-align:center;border:0;"><h3>PERGANTIAN PVDC & WIRE</h3></td>
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
        <td style="border:0;height:28px;">&nbsp;FR-Prod-02</td> 
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
        <td style="border-left:0;border-right:0;height:28px;">:</td>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;2</td> 
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
        <td style="border-left:0;border-right:0;height:28px;">:</td>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;01/04/2016</td> 
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
        <td style="border:none;">:</td>
        <td style="border:none;">'.$data['data']->tgl.'</td>
        </tr>
        <tr>
        <td style="border:none;">Varian</td>
        <td style="border:none;">:</td>
        <td style="border:none;">'.$data['data']->varian.'</u></td><td style="border:none;"  width="500px"></td><td style="border:none; font-size:10px; text-align:right;"></td>
        </tr>
        </tbody>
        </table>

        <table class="data" width="100%">
        <thead>
        <tr>
        <th rowspan="2">Mesin</th>
        <th rowspan="2">Item</th>
        <th width="5" rowspan="2">Frekuensi</th>
        <th colspan="20">Ceklist /BATCH</th>
        <th></th>

        </tr>
        <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
        <th>17</th>
        <th>18</th>
        <th>19</th>
        <th>20</th>
        <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>';   

    // Proses data untuk rowspan
        $groupedData = [];
        foreach ($data['item'] as $row) {
            $groupedData[$row->mesin][] = $row;
        }

    // Hitung jumlah total item dari semua data
        $totalItems = count($data['item']);

// Proses untuk render HTML
$firstFrekuensi = true; // Flag untuk memastikan "Frekuensi" hanya ditampilkan sekali

foreach ($groupedData as $mesin => $items) {
    $firstRow = true; // Flag untuk kolom mesin di baris pertama
    foreach ($items as $item) {
        $html .= '<tr>';

        // Kolom Mesin dengan rowspan per grup mesin
        if ($firstRow) {
            $html .= '<td rowspan="' . count($items) . '">' . $mesin . '</td>';
            $firstRow = false;
        }

        // Kolom Item
        $html .= '<td>' . $item->item . '</td>';

        // Kolom Frekuensi (setelah kolom Item) dengan rowspan total item
        if ($firstFrekuensi) {
         $html .= '<td rowspan="' . $totalItems . '" style="background-color: #d3d3d3; text-align: center; vertical-align: middle;">
         <div style="transform: rotate(270deg); white-space: nowrap;">Pergantian PVDC</div>
         </td>';

            $firstFrekuensi = false; // Matikan flag agar hanya sekali muncul
        }

        // Kolom Ceklists
        $ceklists = explode(',', $item->ceklists);
        for ($i = 0; $i < 20; $i++) {
            $html .= '<td>' . ($ceklists[$i] ?? '') . '</td>';
        }

        // Kolom Keterangan
        $html .= '<td>' . str_replace(',', '<br>', $item->keterangan_group) . '</td>';

        $html .= '</tr>';
    }
}

$html .= '
</tbody>
</table>
<br> <br><br>

<table width="100%">
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
</table>

</html>';

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('FOLIO', 'landscape');

// Render the HTML as PDF
$dompdf->render();


$dompdf->stream("FR-Prod-02_Formulir_" . $data['data']->tanggal . ".pdf", array("Attachment" => false));

}

public function get_mesin_by_area($area_uuid, $MN_BATCH)
    {
        $data = $this->Cekmesin_fillerbatch_model->get_by_area($area_uuid, $MN_BATCH);
        print_r(json_encode($data));
    }

}