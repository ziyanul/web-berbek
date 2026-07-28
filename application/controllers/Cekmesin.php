<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Cekmesin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Am_model');
        $this->load->model('Area_model');
        $this->load->model('Cek_mesin_model');
        $this->load->model('Auth_model');
        $this->load->library('form_validation');

        if (!$this->Auth_model->current_user()) {
            redirect('login');
        }
    }

    public function index()
    {

        $data = array(
            'data' => $this->Cek_mesin_model->get_plan(),

            'active_nav' => 'cekmesin'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/cek-mesin', $data);
        $this->load->view('partials/footer');
    }

    public function checklist($t_planning_uuid)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $insert = $this->Cek_mesin_model->insert_cek_mesin($t_planning_uuid);
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                redirect('cekmesin');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
                redirect('cekmesin/');
            }
        }
        $data = array(
            'data' => $this->Cek_mesin_model->get_plan_uuid($t_planning_uuid),
            'area' => $this->Am_model->get_area(),
            'mesin' => $this->Am_model->get_mesin(),
            'kegiatan' => $this->Am_model->get_kegiatan(),
            'active_nav' => 'cekmesin'
        );


        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/cek-list', $data);
        $this->load->view('partials/footer');
    }

    public function formcekmesin($t_planning_uuid)
    {

        $data = array(
            'data' => $this->Cek_mesin_model->get_area_data($t_planning_uuid),
            'active_nav' => 'cekmesin'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/data-cek-mesin', $data);
        $this->load->view('partials/footer');
    }

    public function paraf_prod($uuid)
    {
        $update = $this->Cek_mesin_model->update_paraf_prod($uuid);
        if ($update) {
            $this->session->set_flashdata('success_msg', 'Paraf Berhasil di Tambahkan');
            echo json_encode(array('status' => TRUE));
        } else {
            $this->session->set_flashdata('error_msg', 'Paraf Gagal di Tambahkan');
            echo json_encode(array('status' => FALSE));
        }
    }

    public function print($t_planning_uuid)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $cek_mesin = $this->Cek_mesin_model->get_data($t_planning_uuid);
        $logo = base_url("assets/img/cpi-logo.jpg");
        $total_area = count(array_unique(array_column($cek_mesin, 'area')));
        $html = '<html>
      <head>
      <title>FR-Prod-02 FORM CEK MESIN</title>
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
      <body>';
        $current_area = '';
        $area_counter = 1;
        $group_counter = 'A';
        $item_counter = 1;
        foreach ($cek_mesin as $index => $row) {
            if ($current_area != $row['area']) {
                if ($current_area != '') {
                    $html .= "</tbody></table>";
                    $html .= '<br><table width="100%">
               <tr>
               <td style="width: 100px; text-align: center;">Dilaksanakan Oleh</td>
               <td style="border: none; width: 30px;"></td>
               <td style="width: 100px; text-align: center;">Diverifikasi Oleh</td>
               <td style="border: none; width: 30px;"></td>
               <td style="width: 100px; text-align: center;">Disetujui Oleh</td>
               </tr>
               <tr>
               <td style="height: 60px; width: 100px;"></td>
               <td style="height: 60px; border: none; width: 30px;"></td>
               <td style="height: 60px; width: 100px;"></td>
               <td style="height: 60px; border: none; width: 30px;"></td>
               <td style="height: 60px; width: 100px;"></td>
               </tr>
               <tr>
               <td style="width: 100px; text-align: center;">Cheker Area</td>
               <td style="border: none; width: 30px;"></td>
               <td style="width: 100px; text-align: center;">Foreman/Lady Produksi</td>
               <td style="border: none; width: 30px;"></td>
               <td style="width: 100px; text-align: center;">Spv. Produksi</td>
               </tr>
               </table>';
                    if ($index < count($cek_mesin) - 1) {
                        $html .= '<div style="page-break-before: always;"></div>';
                    }
                }
                $current_area = $row['area'];
                $html .= '
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
        <td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3 style="font-weight:normal">FORM</h3></td>
        </tr>
        <tr>
        <td style="text-align:center;border:0;"><h3>PENGECEKAN MESIN</h3></td>
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
        <td style="border:0;height:28px;">&nbsp;FR-Prod-14</td>
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
        <td style="border-left:0;border-right:0;height:28px;">:</td>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td>
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
        <td style="border-left:0;border-right:0;height:28px;">:</td>
        <td style="border-left:0;border-right:0;height:28px;">&nbsp;01-01-2020</td>
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
        <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
        <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;' . $area_counter . ' dari ' . $total_area . '</td>
        </tr>
        </tbody>
        </table>
        </table>
        <table style="margin-top: 10px; margin-bottom: 10px;">
        <tbody>
        <tr>
        <td width="200px" style="text-align: left;">Area</td><td width="250px">: ' . $current_area . '</td>
        </tr>
        </tbody>
        </table>
        <table>
        <thead>
        <tr>
        <th rowspan="2" style="width: 30px; border: 1px solid #000;">No</th>
        <th rowspan="2" style="width: 300px; border: 1px solid #000;">Item</th>
        <th style="width: 100px; border: 1px solid #000;" rowspan="2">Frekuensi</th>
        <th colspan="2" style="width: 100px; border: 1px solid #000;">Checklist (V)</th>
        <th rowspan="2" style="width: 150px; border: 1px solid #000;">Keterangan</th>
        <th style="width: 100px; border: 1px solid #000;" colspan="2">Paraf</th>
        </tr>
        <tr>
        <th style="width: 30px; border: 1px solid #000;">YA</th>
        <th style="width: 30px; border: 1px solid #000;">TIDAK</th>
        <th style="width: 50px; border: 1px solid #000;">Prod</th>
        <th style="width: 50px; border: 1px solid #000;">QC</th>
        </tr>
        </thead>
        <tbody>';
                $group_counter = 'A';
                $item_counter = 1;
                $area_counter++;
            }
            if ($index > 0 && $current_area == $row['area'] && $cek_mesin[$index - 1]['group'] != $row['group']) {
                $html .= '<tr><td colspan="8" style="text-align: left;"><b> ' . $group_counter . '  ' . $row['group'] . '</b></td></tr>';
                $group_counter++;
                $item_counter = 1;
            }
            $html .= '<tr>
   <td>' . $item_counter . '</td>
   <td>' . $row['item'] . '</td>
   <td></td>
   <td>' . $row['cek_ya'] . '</td>
   <td>' . $row['cek_tdk'] . '</td>
   <td>' . $row['keterangan'] . '</td>
   <td>' . $row['prf_prod'] . '</td>
   <td>' . $row['prf_qc'] . '</td>
   </tr>';
            $item_counter++;
        }
        $html .= "</tbody></table>";
        $html .= '<br><table width="100%">
<tr>
<td style="width: 100px; text-align: center;">Dilaksanakan Oleh</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center;">Diverifikasi Oleh</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center;">Disetujui Oleh</td>
</tr>
<tr>
<td style="height: 60px; width: 100px;"></td>
<td style="height: 60px; border: none; width: 30px;"></td>
<td style="height: 60px; width: 100px;"></td>
<td style="height: 60px; border: none; width: 30px;"></td>
<td style="height: 60px; width: 100px;"></td>
</tr>
<tr>
<td style="width: 100px; text-align: center;">Cheker Area</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center;">Foreman/Lady Produksi</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center;">Spv. Produksi</td>
</tr>
</table>';
        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("FR-Prod-02_Formulir_.pdf", array("Attachment" => false));
    }

    public function dataitem()
    {
        $data = array(
            'data'      => $this->Cek_mesin_model->get_item(),
            'active_nav' => 'item-cm'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('cek-mesin/item-item', $data);
        $this->load->view('partials/footer');
    }

    public function tambahitem()
    {
        $kegiatan = $this->input->post('kegiatan');

        if ($this->input->method() === 'post') {

            if (empty($kegiatan) || count(array_filter($kegiatan)) == 0) {
                $this->session->set_flashdata('error_msg', 'Minimal 1 kegiatan harus diisi.');
                redirect('cekmesin/tambahitem');
            }

            $insert = $this->Cek_mesin_model->insert_item_batch();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
            }

            redirect('cekmesin/dataitem');
        }

        $data = [
            'area' => $this->Area_model->get_all(),
            'active_nav' => 'item-cm'
        ];

        $this->load->view('partials/head-form', $data);
        $this->load->view('cek-mesin/tambah-item', $data);
        $this->load->view('partials/footer');
    }

    public function edititem($uuid)
    {
        $rules = [
            [
                'field' => 'kegiatan',
                'label' => 'Kegiatan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Cek_mesin_model->update_item($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('cekmesin/dataitem');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
                redirect('cekmesin/dataitem');
            }
        }

        $data = array(
            'area'      => $this->Area_model->get_all(),
            'data'      => $this->Cek_mesin_model->get_by_uuid($uuid),
            'active_nav' => 'item-cm'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('cek-mesin/edit-item', $data);
        $this->load->view('partials/footer');
    }

    public function delete_item($uuid)
    {

        $this->Cek_mesin_model->delete_item($uuid);
        redirect('cekmesin/dataitem/');
    }

    public function get_kegiatan_by_mesin($mesin_uuid)
    {
        $data = $this->Cek_mesin_model->get_kegiatan_by_mesin($mesin_uuid);
        print_r(json_encode($data));
    }
}
