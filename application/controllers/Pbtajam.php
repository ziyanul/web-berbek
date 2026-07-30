<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pbtajam extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Pbtajam_model');
        $this->load->model('auth_model');
        $this->load->model('area_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
            'data' => $this->Pbtajam_model->get_all(),
            'active_nav' => 'pbtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/p_btajam', $data);
        $this->load->view('partials/footer');
    }

    public function tambah_area()
    {
        $rules = $this->Pbtajam_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Pbtajam_model->insert_jenis();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Benda Tajam berhasil di tambah.');
                redirect('Pbtajam');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Benda Tajam gagal di tambah.');
                redirect('Pbtajam');
            }
        }
        $data = array(
            'area' => $this->area_model->get_all(),
            'active_nav' => 'pbtajam'
        );
        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/p_tambah', $data);
        $this->load->view('partials/footer');
    }


    public function editjenis($uuid)
    {
        $rules = $this->Pbtajam_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $are = $this->input->post('area');
            $update = $this->Pbtajam_model->update($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data Benda Tajam berhasil di ubah.');
                redirect('Pbtajam/');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Benda Tajam gagal di ubah.');
                redirect('Pbtajam/');
            }
        }

        $data = array(
            'data' => $this->Pbtajam_model->get_by_uuid($uuid),
            'area' => $this->area_model->get_all(),
            'active_nav' => 'pbtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('Pbtajam/p_edit', $data);
        $this->load->view('partials/footer');
    }

    public function kodebtajam()
    {
        $data = array(
            'data' => $this->Pbtajam_model->get_all_kode(),
            'active_nav' => 'kodebtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/kodebtajam', $data);
        $this->load->view('partials/footer');
    }

    public function tambah_kode()
    {
        $rules = $this->Pbtajam_model->rules1();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Pbtajam_model->insert_kode();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Benda Tajam berhasil di tambah.');
                redirect('Pbtajam/kodebtajam/');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Benda Tajam gagal di tambah.');
                redirect('Pbtajam/kodebtajam/');
            }
        }
        $data = array(
            'area' => $this->area_model->get_all(),
            'active_nav' => 'kodebtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/kodebt_tambah', $data);
        $this->load->view('partials/footer');
    }

    public function get_by_jenis($area_uuid)
    {
        $data = $this->Pbtajam_model->get_by_jenis($area_uuid);
        print_r(json_encode($data));
    }

    public function editkodebt($uuid)
    {
        $rules = $this->Pbtajam_model->rules3();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $are = $this->Pbtajam_model->get_by_nav_kode($uuid);
            $update = $this->Pbtajam_model->update_kode($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data Benda Tajam berhasil di ubah.');
                redirect('Pbtajam/kodebtajam/');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Benda Tajam gagal di ubah.');
                redirect('Pbtajam/kodebtajam/');
            }
        }

        $data = array(
            'data' => $this->Pbtajam_model->get_kode_by_uuid($uuid),
            'jenis' => $this->Pbtajam_model->get_by_nav_kode($uuid),
            'active_nav' => 'kodebtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('Pbtajam/kodebt_edit', $data);
        $this->load->view('partials/footer');
    }

    public function form_pbtajam()
    {
        $data = array(
            'data' => $this->Pbtajam_model->get_all_form(),
            'active_nav' => 'Pbtajam/form_pbtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/f_pbtajam', $data);
        $this->load->view('partials/footer');
    }

    public function tambah()
    {
        $rules = $this->Pbtajam_model->rules2();
        $this->form_validation->set_rules($rules);

        // $shift = $this->input->post('shift');
        // $area_uuid = $this->input->post('area');


        if ($this->form_validation->run() === TRUE) {
            $insert = $this->Pbtajam_model->insert_form();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Pengecekan Benda Tajam berhasil ditambah.');
            } else {
                $this->session->set_flashdata('error_msg', 'Pengecekan Benda Tajam gagal ditambah.');
            }
            redirect('Pbtajam/form_pbtajam');
        };

        $data = array(
            'data' => $this->Pbtajam_model->get_all_form(),
            'area' => $this->area_model->get_all(),
            'active_nav' => 'Pbtajam/form_pbtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('Pbtajam/tambah_btajam', $data);
        $this->load->view('partials/footer');
    }

    public function get_kode_by_area($area_uuid, $shift, $tanggal)
    {
        $kode_data = $this->Pbtajam_model->get_kode_by_area($area_uuid, $shift, $tanggal);
        $response = [];

        if (!empty($kode_data['existing_data'])) {
            $response = array(
                'status' => false,
                'message' => 'Kode Sudah di Input'
            );
        } elseif (!empty($kode_data['data'])) {
            $response = array(
                'status' => true,
                'data' => $kode_data['data']
            );
        } else {
            $response = array(
                'status' => false,
                'message' => 'Tidak ada kode yang ditemukan untuk area ini.'
            );
        }

        echo json_encode($response);
    }



    public function detailform($tanggal, $shift)
    {
        $data = array(
            'data' => $this->Pbtajam_model->get_by_tanggal($tanggal, $shift),
            'active_nav' => 'Pbtajam/form_pbtajam',

        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('pbtajam/f_detail', $data);
        $this->load->view('partials/footer');
    }

    public function editform($uuid)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $data = $this->Pbtajam_model->get_form_by_uuid($uuid);
            $update = $this->Pbtajam_model->update_form($uuid);

            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data Benda Tajam berhasil diubah.');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Benda Tajam gagal diubah.');
            }

            redirect('Pbtajam/detailform/' . $data->tgl . '/' . $data->shift);
        }

        $data = array(
            'data' => $this->Pbtajam_model->get_form_by_uuid($uuid),
            'active_nav' => 'Pbtajam/form_pbtajam'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('Pbtajam/f_edit', $data);
        $this->load->view('partials/footer');
    }

    public function approval($tanggal, $shift, $role)
    {
        $update = $this->Pbtajam_model->approval($tanggal, $shift, $role);

        if ($update) {
            $current_user = $this->auth_model->current_user();

            echo json_encode([
                'status' => true,
                'fullname' => $current_user->fullname, // Nama pengguna yang menyetujui
                'message' => ucfirst($role) . ' berhasil di-ACC.',
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menyetujui ' . $role . '.',
            ]);
        }
    }


    public function form($tanggal, $shift)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        $v = $this->Pbtajam_model->get_by_tanggal($tanggal, $shift);
        $logo = base_url("assets/img/cpi-logo.jpg");

        $html = '
            <html>
            <head>
            <title>FR-Prod-10 BENDA TAJAM</title>
            <meta name="author" content="Arthur Herbert Fonzarelli">
            <meta name="keywords" content="fonzie, cool, ehhhhhhh">
            </head>
            <body>

            <style>
            @page { margin: 5px; }
            body {sans-serif; font-size: 12px; }
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
            <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="100px"></td>
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
                <td style="text-align:center;border:0;font-size: 12px;"><h2>PENGECEKAN BENDA TAJAM</h2></td>
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
                <td style="border:0;height:28px;">&nbsp;FR-Prod-10</td>
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

            <table style="padding-top:15px; padding-bottom:15px;">
            <tbody>
                <tr>
                    <td style="border:none; width:90px; text-align:left;">Tanggal </td>
                    <td style="border:none; width:10px; text-align:left;">: </td>
                    <td style="border:none; text-align:left;">' . $v[0]->tgl . '</td>
                </tr>
                <tr>
                    <td style="border:none; width:90px; text-align:left;">Shift </td>
                    <td style="border:none; width:10px; text-align:left;">: </td>
                    <td style="border:none; text-align:left;">' . $v[0]->shift_name . '</td>
                </tr>
            </table>

            <table class="table table-bordered" width: 100%;">
            <thead class="text-light">
                <tr>
                <th style="border: 1px solid black; text-align: left; vertical-align: middle;" rowspan="2">&nbsp;Area</th>
                <th style="border: 1px solid black; text-align: left; vertical-align: middle;" rowspan="2">&nbsp;Jenis Benda Tajam</th>
                <th style="border: 1px solid black; text-align: left; vertical-align: middle;" rowspan="2">&nbsp;Kode Benda Tajam</th>
                    <th style="border: 1px solid black;" colspan="3" >Kondisi</th>
                    <th style="border: 1px solid black;" rowspan="2" >Keterangan</th>
                </tr>
                <tr>
                    <th class="text-center align-middle" style="border: 1px solid black;">Baik</th>
                    <th class="text-center align-middle" style="border: 1px solid black;">Pecah</th>
                    <th class="text-center align-middle" style="border: 1px solid black;">Hilang</th>
                </tr>
            </thead>
            <tbody>';
        $currentArea = '';
        $currentJenisBenda = '';

        foreach ($v as $row) {
            $html .= '<tr>';

            // Kolom Area dengan rowspan
            if ($currentArea != $row->nama_area) {
                // Hitung jumlah baris untuk area yang sama
                $areaRowCount = count(array_filter($v, fn ($r) => $r->nama_area == $row->nama_area));
                $html .= '<td class="text-center align-middle" rowspan="' . $areaRowCount . '">&nbsp;' . $row->nama_area . '</td>';
                $currentArea = $row->nama_area;
                // Reset currentJenisBenda setiap kali ada area baru
                $currentJenisBenda = '';
            }

            // Kolom Jenis Benda Tajam dengan rowspan
            if ($currentJenisBenda != $row->jenis_benda) {
                // Hitung jumlah baris untuk jenis benda tajam yang sama dalam area yang sama
                $jenisRowCount = count(array_filter($v, fn ($r) => $r->nama_area == $row->nama_area && $r->jenis_benda == $row->jenis_benda));
                $html .= '<td class="text-center align-middle" rowspan="' . $jenisRowCount . '">&nbsp;' . $row->jenis_benda . '</td>';
                $currentJenisBenda = $row->jenis_benda;
            }

            // Kolom Kode Benda Tajam
            $html .= '<td style="text-align;">&nbsp;' . $row->kode_benda . '</td>';

            // Kolom Kondisi (Baik, Pecah, Hilang)
            $html .= '<td style="text-align:center; font-size: 16px;">' . ($row->kondisi_1 ?: '') . '</td>';
            $html .= '<td style="text-align:center; font-size: 16px;">' . ($row->kondisi_2 ?: '') . '</td>';
            $html .= '<td style="text-align:center; font-size: 16px;">' . ($row->kondisi_3 ?: '') . '</td>';

            // Kolom Keterangan
            $html .= '<td style="text-align:center;">' . $row->keterangan . '</td>';

            // Tutup baris
            $html .= '</tr>';
        }


        $html .= '</tbody></table>';
        $html .= '<br><table width="100%" style="padding-top:15px; padding-bottom:15px;">
                    <tr>

                    <td style="width: 100px; text-align: center; background-color: #dbe5f1;">Dibuat</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 100px; text-align: center; background-color: #dbe5f1;">Mengetahui</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 100px; text-align: center; background-color: #dbe5f1;">Disetujui</td>
                    </tr>';

        $html .= '<tr>';
        $html .= '<td style="height: 105px; width: 100px; text-align: center;">' . $row->fullname . '</td>';
        $html .= '<td style="height: 105px; border: none; width: 200px;"></td>';
        $html .= '<td style="height: 105px; width: 100px; text-align: center;">' . $row->leader . '</td>';
        $html .= '<td style="height: 105px; border: none; width: 200px;"></td>';
        $html .= '<td style="height: 105px; width: 100px; text-align: center;">' . $row->spv . '</td>';
        $html .= '</tr>

                    <tr>
                    <td style="width: 100px; text-align: center;">Koordinator</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 100px; text-align: center;">Foreman/Lady</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 100px; text-align: center;">Spv. Produksi</td>
                    </tr>
                    </table>';

        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('FOLIO', 'landscape');
        $dompdf->render();
        $dompdf->stream("FR-Prod-10_Formulir_.pdf", array("Attachment" => false));
    }
}
