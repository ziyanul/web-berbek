<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Rework extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('Rework_model');
        $this->load->model('varian_model');
        $this->load->library('form_validation');

        if(!$this->auth_model->current_user()){
         redirect('login');
     }
 }

	public function index() //stock rework
	{
		$data = array(
			'data' => $this->Rework_model->get_all(),
			'active_nav' => 'rework-data'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('rework/rework', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Rework_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Rework_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data rework berhasil di tambah.');
                redirect('rework');
            } else {
                redirect('rework');
                $this->session->set_flashdata('error_msg', 'Data rework gagal di tambah.');
            }
        }

        $data = array(
           'data' => $this->varian_model->get_all(),
           'active_nav' => 'rework'
       );

        $this->load->view('partials/head', $data);
        $this->load->view('rework/tambah', $data);
        $this->load->view('partials/footer');
    }

    public function edit($uuid)
    {
      $rules = $this->Rework_model->rules();
      $this->form_validation->set_rules($rules);

      if ($this->form_validation->run() === TRUE) {

        $update = $this->Rework_model->update($uuid);
        if ($insert) {
            $this->session->set_flashdata('success_msg', 'Data rework berhasil di ubah.');
            redirect('rework');
        } else {
            redirect('rework');
            $this->session->set_flashdata('error_msg', 'Data rework gagal di ubah.');
        }
    }

    $data = array(
       'varian' => $this->Rework_model->get_varian(),
       'data'  => $this->Rework_model->get_by_uuid($uuid),
       'active_nav' => 'rework'
   );

    $this->load->view('partials/head', $data);
    $this->load->view('rework/edit', $data);
    $this->load->view('partials/footer');
}

public function pemakaian()
{
  $data = array(
   'data'  => $this->Rework_model->get_pakai_data(),
   'active_nav' => 'rework'
);

  $this->load->view('partials/head', $data);
  $this->load->view('rework/pemakaian', $data);
  $this->load->view('partials/footer');
}

public function detail($tanggal_kode) {
    $data = array(
        'data' => $this->Rework_model->get_pakai_data_by_tanggal_kode($tanggal_kode),
        'active_nav' => 'rework'
    );

    $this->load->view('partials/head', $data);
    $this->load->view('rework/detail', $data);
    $this->load->view('partials/footer');
}

public function tambahpakai() {
    $rules = $this->Rework_model->rules_pakai();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {
        $insert = $this->Rework_model->insert_pakai();
        if ($insert) {
            $this->session->set_flashdata('success_msg', 'Data rework berhasil di tambah.');
            redirect('rework/pemakaian');
        } else {
            $this->session->set_flashdata('error_msg', 'Data rework gagal di tambah.');
            redirect('rework/pemakaian');
        }
    }

    $data = array(
            'varian' => $this->Rework_model->get_varian(), // Fetch the variants
            'active_nav' => 'rework'
        );

    $this->load->view('partials/head', $data);
    $this->load->view('rework/tambah-pemakaian', $data);
    $this->load->view('partials/footer');
}

public function editpakai($uuid) {
    $rules = $this->Rework_model->rules_edit_pakai();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {
        $tanggal = $this->input->post('kode_batch');
        $data =  substr($tanggal, 0, 4);
        $update = $this->Rework_model->update_pakai($uuid);
        if ($update) {
            $this->session->set_flashdata('success_msg', 'Data rework berhasil di ubah.');
            redirect('rework/detail/' .$data);
        } else {
            $this->session->set_flashdata('error_msg', 'Data rework gagal di ubah.');
            redirect('rework/detail/' .$data);
        }
    }

    $data = array(
            'data' => $this->Rework_model->get_pakai_by_uuid($uuid), // Fetch the variants
            'active_nav' => 'rework'
        );

    $this->load->view('partials/head', $data);
    $this->load->view('rework/edit-pemakaian', $data);
    $this->load->view('partials/footer');
}

public function get_rework_by_varian()
{
    $varian_uuid = $this->input->get('varian');
    if (!empty($varian_uuid)) {
        $rework_codes = $this->Rework_model->get_rework_by_varian($varian_uuid);
        $options = '<option selected disabled>Select Rework Code</option>';
        if (!empty($rework_codes)) {
            foreach ($rework_codes as $code) {
                $options .= '<option value="' . $code->kode_rework . '">' . $code->kode_rework . '</option>';
            }
        } else {
            $options .= '<option disabled>No Rework Codes Available</option>';
        }
        echo $options; 
    }
}

public function get_batch_by_varian()
{
    $planprod_uuid = $this->input->get('planprod_uuid'); // Ambil UUID dari t_planning

    if (!$planprod_uuid) {
        echo '<option value="">Pilih Batch</option>';
        return;
    }

    $batches = $this->Rework_model->get_batch_by_planprod($planprod_uuid);

    if (!empty($batches)) {
        echo '<option value="">Pilih Batch</option>';
        foreach ($batches as $batch) {
            echo '<option value="' . $batch->MN_BATCH . '">' . $batch->MN_BATCH . '</option>';
        }
    } else {
        echo '<option value="">Tidak ada batch tersedia</option>';
    }
}



public function get_remaining_weight() {
    $kode_rework = $this->input->get('kode_rework');

    // Fetch the total weight (berat) from r_kupas for the selected kode_rework
    $this->db->select('berat');
    $this->db->from('rwk_kupas');
    $this->db->where('kode_rework', $kode_rework);
    $kupas = $this->db->get()->row();

    // Fetch the total used weight (dipakai) from r_pakai for the selected kode_rework
    $this->db->select_sum('dipakai');
    $this->db->from('rwk_pakai');
    $this->db->where('kode_rework', $kode_rework);
    $pakai = $this->db->get()->row();

    // Calculate remaining stock
    $remaining = $kupas->berat - ($pakai->dipakai ?? 0); // Subtract used amount, assume 0 if no dipakai record

    // Return the remaining stock as JSON
    echo json_encode(['remaining' => $remaining]);
}

public function form_rework($tanggal_kode)
{
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $data = $this->Rework_model->get_pakai_data_by_tanggal_kode($tanggal_kode);

    $logo = base_url("assets/img/cpi-logo.jpg");

    $html = '
    <html>
    <head>
    <title>FR-Prod-17 PENGGUNAAN REWORK</title>
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
    <td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h3 style="font-weight:normal">FORM</h3></td>
    </tr>
    <tr>
    <td style="text-align:center;border:0;"><h3>PENGGUNAAN REWORK</h3></td>
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
    <td style="border:0;height:28px;">&nbsp;FR-Prod-17</td> 
    </tr>
    <tr>
    <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
    <td style="border-left:0;border-right:0;height:28px;">:</td>
    <td style="border-left:0;border-right:0;height:28px;">&nbsp;2</td> 
    </tr>
    <tr>
    <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
    <td style="border-left:0;border-right:0;height:28px;">:</td>
    <td style="border-left:0;border-right:0;height:28px;">&nbsp;21/09/2017</td> 
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

    <table style="margin-top: 10px; margin-bottom: 5px">
    <tbody>
    <tr>
    <td style="border:none;">Tanggal</td>
    <td style="border:none;"> : </td>
    <td style="border:none;">'.$data[0]->tanggal.'</td>
    </tr>

    </tbody>
    </table>
    <table class="data" width="100%">
    <thead class="table bg-info text-light">
    <tr>
    <td width="1" class="font-weight-bold align-middle text-center" rowspan="2">NO.</td>
    <td class="font-weight-bold align-middle text-center" rowspan="2">VARIAN REWORK</td>
    <td class="font-weight-bold align-middle text-center" rowspan="2">KODE REWORK</td>
    <td class="font-weight-bold align-middle text-center" rowspan="2">TANGGAL MASUK CS</td>
    <td class="font-weight-bold align-middle text-center" rowspan="2">QTY MASUK CS (KG)</td>
    <td class="font-weight-bold align-middle text-center" colspan="3"></td>
    <td class="font-weight-bold align-middle text-center" colspan="2">TEMUAN</td>
    <td class="font-weight-bold align-middle text-center" colspan="2">ACC</td>
    </tr>
    <tr>
    <td class="font-weight-bold align-middle text-center">QTY PEMAKAIAN(KG)</td>
    <td class="font-weight-bold align-middle text-center">KODE BATCH PRODUKSI</td>
    <td class="font-weight-bold align-middle text-center">SISA REWORK (KG)</td>
    <td class="font-weight-bold align-middle text-center">PLASTIK</td>
    <td class="font-weight-bold align-middle text-center">METAL</td>
    <td class="font-weight-bold align-middle text-center">QC</td>
    <td class="font-weight-bold align-middle text-center">OPERATOR</td>
    </tr>
    </thead>
    <tbody>';

    $no = 1;
    foreach ($data as $row) {
        $html .= '<tr>
        <td class="text-center">' . $no . '</td>
        <td>' . $row->varian . '</td>
        <td>' . $row->kode_rework . '</td>
        <td>' . $row->tanggal_masuk . '</td>
        <td class="text-center">' . $row->total_rework . '</td>
        <td class="text-center">' . $row->dipakai . '</td>
        <td>' . $row->kode_produksi . '</td>
        <td class="text-center">' . $row->sisa_stock . '</td>
        <td class="text-center">' . $row->plastik . '</td>
        <td class="text-center">' . $row->metal . '</td>
        <td>' . $row->acc_qc . '</td>
        <td>' . $row->username . '</td>
        </tr>';
        $no++;
    }

    $html .= '
    </tbody>
    </table>
    <br>
    <br>
    <br>
    <table width="100%">
    <tbody>
    <tr style="text-align:center;">
    <td>Dibuat:</td>
    <td style="border:none;" width="25%"></td>

    <td>Mengetahui:</td>
    <td style="border:none;" width="25%"></td>

    <td>Disetujui:</td>
    </tr>
    <tr>
    <td height="60px" style="text-align: center;">'.$data[0]->pembuat.'</td>
    <td style="border:none;" width="25%"></td>

    <td height="60px" style="text-align: center;">'.$data[0]->leader.' </td>
    <td style="border:none;" width="25%"></td>

    <td height="60px" style="text-align: center;">'.$data[0]->spv.' </td>
    </tr>
    <tr style="text-align:center;">
    <td>Checker MP</td>
    <td style="border:none;" width="25%"></td>

    <td>Foreman / Lady MP</td>
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


    $dompdf->stream("FR-Prod-17_Formulir_" . $data[0]->tanggal . ".pdf", array("Attachment" => false));

}


public function approval($tanggal_kode, $role)
{
    $fullname = $this->Rework_model->approval($tanggal_kode, $role);

    if ($fullname) {
        echo json_encode([
            'status' => true,
            'fullname' => $fullname, // Kirim nama user yang menyetujui
            'message' => ($role == '1' ? 'Foreman' : 'SPV') . ' berhasil di-ACC.',
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Gagal menyetujui ' . ($role == '1' ? 'Foreman' : 'SPV') . '.',
        ]);
    }
}



}