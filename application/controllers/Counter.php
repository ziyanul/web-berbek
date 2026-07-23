<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Counter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mesin_model');
        $this->load->model('Counter_model');
        $this->load->library('form_validation');
        // $this->load->model('pdf_model');
        $this->load->model('User_model');
        if (!$this->auth_model->current_user()) {
            redirect('login');
        }
    }
    public function index()
    {
        $data = array(
            // 'sensor' => $this->Counter_model->get_sensor_count(),
            'data' => $this->Counter_model->get_all(),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/form', $data);
        $this->load->view('partials/footer');
    }
    public function detail($uuid)
    {
        $data = array(
            'batch' => $this->Counter_model->get_batch($uuid),
            'data' => $this->Counter_model->get_by_uuid($uuid),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/detail', $data);
        $this->load->view('partials/footer');
    }
    public function tambahbatch($t_planning_uuid)
    {
        $rules = [
            [
                'field' => 'kode_batch',
                'label' => 'Kode Batch',
                'rules' => 'required|trim'
            ]
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->db->trans_start();
            $batch_uuid = $this->Counter_model->tambah_batch($t_planning_uuid);
            if ($batch_uuid) {
                $insert_counter = $this->Counter_model->insert_counter($batch_uuid);
                if (!$insert_counter) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_msg', 'Counter tidak ada yang tersimpan.');
                    redirect('counter/detail/' . $t_planning_uuid);
                    return;
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error_msg', 'Gagal menyimpan batch.');
                redirect('counter/detail/' . $t_planning_uuid);
                return;
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
            } else {
                $this->session->set_flashdata('success_msg', 'Data batch berhasil disimpan.');
            }
            redirect('counter/detail/' . $t_planning_uuid);
        }
        $data = array(
            'speed' => $this->Counter_model->get_speed_by_plan($t_planning_uuid),
            'plan' => $t_planning_uuid,
            'next_batch' => $this->Counter_model->get_next_batch_data($t_planning_uuid),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/tambah-batch', $data);
        $this->load->view('partials/footer');
    }
    public function editbatch($uuid)
    {
        $rules = [
            [
                'field' => 'kode_batch',
                'label' => 'Kode Batch',
                'rules' => 'required|trim'
            ]
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $updateb = $this->Counter_model->updatebatch($uuid);
            if ($updateb['status']) {
                $this->session->set_flashdata(
                    'success_msg',
                    $updateb['msg']
                );
            } else {
                $this->session->set_flashdata(
                    'error_msg',
                    $updateb['msg']
                );
            }
            $t_form = $this->Counter_model->get_batch_uuid($uuid);
            redirect('counter/detail/' . $t_form->t_planning_uuid);
        }
        $data = array(
            'data' => $this->Counter_model->get_batch_counter($uuid),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/edit-batch', $data);
        $this->load->view('partials/footer');
    }
    public function deletebatch($batch_uuid)
    {
        $delete = $this->Counter_model->delete_by_uuid($batch_uuid);
        if ($delete) {
            $this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
            redirect('counter');
        } else {
            $this->session->set_flashdata('error_msg', 'Data gagal dihapus.');
            redirect('counter');
        }
    }
    public function detailcounter($uuid) //berisi batch2
    {
        $data = array(
            'counter' => $this->Counter_model->get_counter($uuid),
            // 'data' => $this->Counter_model->get_by_uuid($uuid),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/counter', $data);
        $this->load->view('partials/footer');
    }
    public function editcounter($uuid)
    {
        $rules = [
            [
                'field' => 'counter',
                'label' => 'Counter',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $updatec = $this->Counter_model->updatecounter($uuid);
            if ($updatec) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                $tbatch = $this->Counter_model->get_counter_uuid($uuid);
                redirect('counter/detailcounter/' . $tbatch->tbatch_uuid);
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
                $tbatch = $this->Counter_model->get_counter_uuid($uuid);
                redirect('counter/detailcounter/' . $tbatch->tbatch_uuid);
            }
        }
        $data = array(
            'data' => $this->Counter_model->get_counter_uuid($uuid),
            'active_nav' => 'counter'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('counter/edit-counter', $data);
        $this->load->view('partials/footer');
    }
    public function formcounter()
    {
        $data = array(
            'data' => $this->Counter_model->get_data_form(),
            'active_nav' => 'formcounter'
        );
        $this->load->view('partials/head-form', $data);
        $this->load->view('counter/pdf', $data);
        $this->load->view('partials/footer');
    }
    public function document($tanggal_produksi, $varian_uuid)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $data = [
            'data'  => $this->Counter_model->get_form_header($tanggal_produksi, $varian_uuid),
            'counter' => $this->Counter_model->get_form_counter($tanggal_produksi, $varian_uuid)
        ];
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
        <td style="border:none;">' . $data['data']->tgl . '</td>
        </tr>
        <tr>
        <td style="border:none;">Varian</td>
        <td style="border:none;">:</td>
        <td style="border:none;">' . $data['data']->varian . ' ( ' . $data['data']->keterangan . ' )</td><td style="border:none;"  width="500px"></td><td style="border:none; font-size:10px; text-align:right;">*Hasil Counter</td>
        </tr>
        </tbody>
        </table>
        <table class="data" width="100%">
        <thead>
        <tr>
        <th rowspan="2" colspan="2">Retort Sausage</th>
        <th colspan="20">Counter BATCH Ke-</th>
        <th></th>
        <th rowspan="2">Total</th>
        <th></th>
        <th rowspan="2" colspan="2">KONVERSI</th>
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
        <th width="0.115%"></th>
        <th width="0.115%"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        <tr>
        <td colspan="2">Mesin Filler</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>PVDC</td><td>WIRE</td>
        </tr>';
        $no = 1;
        $total_batch = array_fill(0, 20, 0); // Array untuk menyimpan total dari setiap kolom batch
        $kolomke = 21 - $data['counter'][0]->max_batch_ke;
        foreach ($data['counter'] as $row) {
            $html .= '<tr>';
            $html .= '<td width="3%">' . $no . '</td>';
            $html .= '<td width="7%">U. ' . $row->nama_mesin . '</td>';
            $counters = explode(',', $row->counters); // Pisahkan counters menjadi array
            // Iterasi untuk setiap counter
            foreach ($counters as $key => $coun) {
                $nilai_counter = (int) $coun;
                $html .= '<td>' . number_format($nilai_counter, 0, ',', '.') . '</td>';
                $total_batch[$key] += $nilai_counter;
            }
            // Tambahkan kolom kosong pada posisi 15 dan 16
            for ($i = 0; $i < $kolomke; $i++) {
                $html .= '<td></td>';
            }
            $html .= '<td>' . number_format($row->total, 0, ',', '.') . '</td>';
            $html .= '<td></td>';
            $html .= '<td>' . number_format($row->pvdc, 2, ',', '.') . '</td>';
            $html .= '<td>' . number_format($row->wire, 3, ',', '.') . '</td>';
            $html .= '</tr>';
            $no++;
        }
        $total_semua_counter = array_sum($total_batch);
        // Menambahkan baris TOTAL MEAT PREP
        $html .= '<tr><td colspan="2">TOTAL MEAT PREP</td>';
        foreach ($total_batch as $total) {
            $html .= '<td>' . number_format($total, 0, ',', '.') . '</td>';
        }
        $total_pvdc = $total_semua_counter * $data['data']->film;
        $total_wire = $total_semua_counter * 0.000302;
        $html .= '<td></td>';
        $html .= '<td>' . number_format($total_semua_counter, 0, ',', '.') . '</td>';
        $html .= '<td></td>';
        $html .= '<td>' . number_format($total_pvdc, 2, ',', '.') . '</td>';
        $html .= '<td>' . number_format($total_wire, 3, ',', '.') . '</td>';
        $html .= '</tr>';
        $html .= '
</tr>
</tbody>
</table>
<span style="float: right; margin-right: 350px; font-size: 10px;">*Satuan PVDC : Meter<br>*Satuan Wire : KG</span>
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
        $dompdf->setPaper('A4', 'landscape');
        // Render the HTML as PDF
        $dompdf->render();
        $dompdf->stream("FR-Prod-02_Formulir_" . $data['data']->tanggal_produksi . ".pdf", array("Attachment" => false));
    }
}