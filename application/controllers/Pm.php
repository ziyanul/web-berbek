<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Pm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Part_model');
		$this->load->model('Pm_model');
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
		$this->load->library('spreadsheet_lib');
		$this->load->model('Auth_model');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			// 'data3' => $this->Pm_model->get_total_main(),
			'data' => $this->Pm_model->get_urgent(),
			'active_nav' => 'pm'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('pm/pm', $data);
		$this->load->view('partials/footer');
	}

	public function tpm()
	{
		$data = array(
			'data' => $this->Pm_model->get_pengajuan(),
			'active_nav' => 'pm-tpm'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('pm/pm-tpm', $data);
		$this->load->view('partials/footer');
	}

	public function history()
	{
		
		$data = array(
			'data' => $this->Pm_model->get_history(),
			'active_nav' => 'pm-history'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('pm/pm-history', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = [
			[
				'field' => 'mesin',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'keluhan',
				'label' => 'Keluhan',
				'rules' => 'required'
			],
		];
		$this->form_validation->set_rules($rules);
		$this->form_validation->set_rules(
			'dokumentasi_before',
			'Dokumentasi',
			'callback_file_check_doc_before'
		);
		if (empty($_FILES['dokumentasi_before']['name'])) {
			$this->form_validation->set_rules('dokumentasi_before', 'Dokumentasi', 'required', [
				'required' => 'Sertakan Dokumentasi Foto!'
			]);
		}

		if ($this->form_validation->run() === TRUE) {
        // Configuration for image upload and resize
			$config = array(
				'upload_path'   => './upload/',
				'allowed_types' => 'jpg|jpeg|png|pdf',
				// 'max_size'      => 2048,
				'encrypt_name'  => TRUE,
				'overwrite'     => TRUE
			);

			$this->upload->initialize($config);

			if (!$this->upload->do_upload('dokumentasi_before')) {

				$error = strip_tags($this->upload->display_errors());

				$this->session->set_flashdata('error_msg', $error);

			} else {

				$uploadData = $this->upload->data();

    /*
    |--------------------------------------------------------------------------
    | Resize hanya jika file gambar
    |--------------------------------------------------------------------------
    */
    $ext = strtolower($uploadData['file_ext']);

    if (in_array($ext, ['.jpg', '.jpeg', '.png'])) {

    	$resize_config = array(
    		'image_library'  => 'gd2',
    		'source_image'   => $uploadData['full_path'],
    		'maintain_ratio' => TRUE,
    		'width'          => 500,
    		'height'         => 500
    	);

    	$this->image_lib->clear();
    	$this->image_lib->initialize($resize_config);

    	if (!$this->image_lib->resize()) {

    		$error = strip_tags($this->image_lib->display_errors());

    		$this->session->set_flashdata('error_msg', $error);

    		redirect(
    			($this->uri->segment(2) == 'tpm')
    			? 'pm/tpm/tambah'
    			: 'pm/tambah'
    		);
    	}
    }

    $dok_before = $uploadData['file_name'];

    $insert = $this->Pm_model->insert($dok_before);

    if ($insert) {

    	$this->session->set_flashdata(
    		'success_msg',
    		'Data maintenance berhasil disimpan.'
    	);

    } else {

    	$this->session->set_flashdata(
    		'error_msg',
    		'Data maintenance gagal disimpan.'
    	);
    }
 }

 $redirect_url = ($this->uri->segment(2) == 'tpm')
 ? 'pm/tpm'
 : 'pm';

 redirect($redirect_url);
}

    // Load necessary data for the view
$data = array(
	'area' => $this->Area_model->get_all(),
	'mesin' => $this->Mesin_model->get_all(),
	'part' => $this->Part_model->get_all(),
	'active_nav' => ($this->uri->segment(2) == 'tpm') ? 'pm-tpm' : 'pm'
);

    // Load views
$this->load->view('partials/head-maintenance', $data);
$this->load->view('pm/pm-tambah', $data);
$this->load->view('partials/footer');
}


public function file_check_doc_before($str)
{
	if (empty($_FILES['dokumentasi_before']['name'])) {
		return true;
	}

	$allowed_mime_types = [
		'image/jpeg',
		'image/png',
		'application/pdf'
	];

	$mime_type = $_FILES['dokumentasi_before']['type'];

	if (!in_array($mime_type, $allowed_mime_types)) {

		$this->form_validation->set_message(
			'file_check_doc_before',
			'File harus JPG, PNG, atau PDF'
		);

		return false;
	}

	return true;
}

public function edit($uuid)
{
	$rules = [
		[
			'field' => 'mesin',
			'label' => 'Mesin',
			'rules' => 'required'
		],
		[
			'field' => 'keluhan',
			'label' => 'Keluhan',
			'rules' => 'required'
		]
	];

	$this->form_validation->set_rules($rules);

    /*
    |--------------------------------------------------------------------------
    | Validasi file jika upload file baru
    |--------------------------------------------------------------------------
    */
    if (!empty($_FILES['dokumentasi_before']['name'])) {

    	$this->form_validation->set_rules(
    		'dokumentasi_before',
    		'Dokumentasi',
    		'callback_file_check_doc_before'
    	);
    }

    if ($this->form_validation->run() === TRUE) {

    	$dok_before = '';

        /*
        |--------------------------------------------------------------------------
        | Upload file jika ada file baru
        |--------------------------------------------------------------------------
        */
        if (!empty($_FILES['dokumentasi_before']['name'])) {

        	$config = array(
        		'upload_path'   => './upload/',
        		'allowed_types' => 'jpg|jpeg|png|pdf',
        		// 'max_size'      => 2048,
        		'encrypt_name'  => TRUE,
        		'overwrite'     => TRUE
        	);

        	$this->upload->initialize($config);

        	if (!$this->upload->do_upload('dokumentasi_before')) {

        		$error = strip_tags($this->upload->display_errors());

        		$this->session->set_flashdata('error_msg', $error);

        		redirect(
        			($this->uri->segment(2) == 'tpm')
        			? 'pm/tpm/edit/'.$uuid
        			: 'pm/edit/'.$uuid
        		);

        		return;

        	} else {

        		$uploadData = $this->upload->data();

                /*
                |--------------------------------------------------------------------------
                | Resize jika gambar
                |--------------------------------------------------------------------------
                */
                $ext = strtolower($uploadData['file_ext']);

                if (in_array($ext, ['.jpg', '.jpeg', '.png'])) {

                	$resize_config = array(
                		'image_library'  => 'gd2',
                		'source_image'   => $uploadData['full_path'],
                		'maintain_ratio' => TRUE,
                		'width'          => 500,
                		'height'         => 500
                	);

                	$this->image_lib->clear();
                	$this->image_lib->initialize($resize_config);

                	if (!$this->image_lib->resize()) {

                		$error = strip_tags($this->image_lib->display_errors());

                		$this->session->set_flashdata('error_msg', $error);

                		redirect(
                			($this->uri->segment(2) == 'tpm')
                			? 'pm/tpm/edit/'.$uuid
                			: 'pm/edit/'.$uuid
                		);

                		return;
                	}
                }

                $dok_before = $uploadData['file_name'];

                /*
                |--------------------------------------------------------------------------
                | Hapus file lama
                |--------------------------------------------------------------------------
                */
                $old = $this->Pm_model->get_by_uuid($uuid);

                if (!empty($old->dokumentasi)) {

                	$old_file = './upload/' . $old->dokumentasi;

                	if (file_exists($old_file)) {
                		unlink($old_file);
                	}
                }
             }
          }

        /*
        |--------------------------------------------------------------------------
        | Update database
        |--------------------------------------------------------------------------
        */
        $update = $this->Pm_model->update($uuid, $dok_before);

        if ($update) {

        	$this->session->set_flashdata(
        		'success_msg',
        		'Data berhasil disimpan.'
        	);

        } else {

        	$this->session->set_flashdata(
        		'error_msg',
        		'Tidak ada perubahan data.'
        	);
        }

        redirect(
        	($this->uri->segment(2) == 'tpm')
        	? 'pm/tpm'
        	: 'pm'
        );

        return;
     }

     $data = array(
     	'data' => $this->Pm_model->get_by_uuid($uuid),
     	'mesin' => $this->Mesin_model->get_all(),
     	'active_nav' => (
     		$this->uri->segment(2) == 'tpm'
     		? 'pm-tpm'
     		: 'pm'
     	)
     );

     $this->load->view('partials/head-maintenance', $data);
     $this->load->view('pm/pm-edit', $data);
     $this->load->view('partials/footer');
  }

  public function tindakan($uuid)
  {
  	$rules = [
  		[
  			'field' => 'tindakan',
  			'label' => 'Tindakan Perbaikan',
  			'rules' => 'required'
  		]
  	];

  	$this->form_validation->set_rules($rules);

    /*
    |--------------------------------------------------------------------------
    | Validasi file jika upload baru
    |--------------------------------------------------------------------------
    */
    if (!empty($_FILES['dokumentasi_after']['name'])) {

    	$this->form_validation->set_rules(
    		'dokumentasi_after',
    		'Dokumentasi',
    		'callback_file_check_doc_after'
    	);
    }

    if ($this->form_validation->run() === TRUE) {

    	$dok_after = '';

        /*
        |--------------------------------------------------------------------------
        | Upload file jika ada file baru
        |--------------------------------------------------------------------------
        */
        if (!empty($_FILES['dokumentasi_after']['name'])) {

        	$config = array(
        		'upload_path'   => './upload/',
        		'allowed_types' => 'jpg|jpeg|png|pdf',
        		// 'max_size'      => 2048,
        		'encrypt_name'  => TRUE,
        		'overwrite'     => TRUE
        	);

        	$this->upload->initialize($config);

        	if (!$this->upload->do_upload('dokumentasi_after')) {

        		$error = strip_tags($this->upload->display_errors());

        		$this->session->set_flashdata('error_msg', $error);

        		redirect(
        			($this->uri->segment(2) == 'tpm')
        			? 'pm/tpm/tindakan/'.$uuid
        			: 'pm/tindakan/'.$uuid
        		);

        		return;

        	} else {

        		$uploadData = $this->upload->data();

                /*
                |--------------------------------------------------------------------------
                | Resize jika gambar
                |--------------------------------------------------------------------------
                */
                $ext = strtolower($uploadData['file_ext']);

                if (in_array($ext, ['.jpg', '.jpeg', '.png'])) {

                	$resize_config = array(
                		'image_library'  => 'gd2',
                		'source_image'   => $uploadData['full_path'],
                		'maintain_ratio' => TRUE,
                		'width'          => 500,
                		'height'         => 500
                	);

                	$this->image_lib->clear();
                	$this->image_lib->initialize($resize_config);

                	if (!$this->image_lib->resize()) {

                		$error = strip_tags($this->image_lib->display_errors());

                		$this->session->set_flashdata('error_msg', $error);

                		redirect(
                			($this->uri->segment(2) == 'tpm')
                			? 'pm/tpm/tindakan/'.$uuid
                			: 'pm/tindakan/'.$uuid
                		);

                		return;
                	}
                }

                $dok_after = $uploadData['file_name'];

                /*
                |--------------------------------------------------------------------------
                | Hapus file lama
                |--------------------------------------------------------------------------
                */
                $old = $this->Pm_model->get_by_uuid($uuid);

                if (!empty($old->dokumentasi_acc)) {

                	$old_file = './upload/' . $old->dokumentasi_acc;

                	if (file_exists($old_file)) {
                		unlink($old_file);
                	}
                }
             }
          }

        /*
        |--------------------------------------------------------------------------
        | Update database
        |--------------------------------------------------------------------------
        */
        $update = $this->Pm_model->tindakan($uuid, $dok_after);

        if ($update) {

        	$this->session->set_flashdata(
        		'success_msg',
        		'Tindakan berhasil disimpan.'
        	);

        } else {

        	$this->session->set_flashdata(
        		'error_msg',
        		'Tidak ada perubahan data.'
        	);
        }

        redirect(
        	($this->uri->segment(2) == 'tpm')
        	? 'pm/tpm'
        	: 'pm'
        );

        return;
     }

     $data = array(
     	'data' => $this->Pm_model->get_by_uuid($uuid),
     	'active_nav' => (
     		$this->uri->segment(2) == 'tpm'
     		? 'pm-tpm'
     		: 'pm'
     	)
     );

     $this->load->view('partials/head-maintenance', $data);
     $this->load->view('pm/pm-tindakan', $data);
     $this->load->view('partials/footer');
  }


	public function detail($uuid)
	{
		$data = array(
			'data' => $this->Pm_model->get_by_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'pm-tpm':($this->uri->segment(2)=='history'?'pm-history':'pm')),
			// 'catat' => $this->Pm_model->get_catat($uuid),
			// 'status' => $this->Pm_model->get_status_by_maintenance_uuid($uuid)
		);
		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('pm/pm-detail', $data);
		$this->load->view('partials/footer');
	}

	public function status($uuid)
	{
		$this->Pm_model->status($uuid);

		if ($this->uri->segment(2) == 'tpm') {
			redirect(base_url('pm/tpm'));
		} else {
			redirect(base_url('pm'));
		}
		// $rules = [
		// 	[
		// 		'field' => 'status',
		// 		'label' => 'Status',
		// 		'rules' => 'required'
		// 	],
		// 	[
		// 		'field' => 'keterangan',
		// 		'label' => 'Keterangan',
		// 		'rules' => 'required'
		// 	]
		// ];
		// $this->form_validation->set_rules($rules);
		// if ($this->form_validation->run() === TRUE) {
		// 	$insertStatus = $this->Pm_model->status($uuid);

		// 	if ($insertStatus) {
		// 		$this->session->set_flashdata('success_msg', 'Pengajuan berhasil di ACC');
		// 		redirect(($this->uri->segment(2) == 'tpm' ? 'maintenance/tpm' : 'maintenance'));
		// 	} else {
		// 		$this->session->set_flashdata('error_msg', 'Invalid status value');
		// 	}
		// }
		// $data = array (
		// 	'data' => $this->Pm_model->get_by_uuid($uuid),
		// 	'status' => $this->Pm_model->get_status_by_maintenance_uuid($uuid),
		// 	'active_nav' => ($this->uri->segment(2)=='tpm'?'maintenance-tpm':'maintenance')
		// );
		
		// $this->load->view('partials/head-maintenance', $data);
		// $this->load->view('pm/pm-status', $data);
		// $this->load->view('partials/footer');
	// }
			// $insert = $this->Pm_model->status($uuid);

		// 	if ($insert) {
		// 		$updatedoc = $this->Pm_model->updatedoc($uuid, $dok_after);

		// 		if ($updatedoc) {
		// 			$this->session->set_flashdata('success_msg', 'Pengajuan berhasil di ACC');
		// 		redirect(($this->uri->segment(2)=='tpm'?'maintenance-tpm':'maintenance'));
		// 		}

		// 	} else {
		// 		redirect(($this->uri->segment(2)=='tpm'?'maintenance-tpm':'maintenance'));
		// 		$this->session->set_flashdata('error_msg', 'Pengajuan gagal di ACC');
		// 	}
	}

	public function file_check_doc_after($str)
  {
  	if (empty($_FILES['dokumentasi_after']['name'])) {
  		return true;
  	}

  	$allowed_mime_types = [
  		'image/jpeg',
  		'image/png',
  		'application/pdf'
  	];

  	$mime_type = $_FILES['dokumentasi_after']['type'];

  	if (!in_array($mime_type, $allowed_mime_types)) {

  		$this->form_validation->set_message(
  			'file_check_doc_after',
  			'File harus JPG, PNG, atau PDF'
  		);

  		return false;
  	}

  	return true;
  }

	public function delete_kegiatan($uuid) 
	{
		$this->Pm_model->delete_kegiatan($uuid);
		redirect($this->uri->segment(2) == 'tpm' ? 'pm/tpm' : 'pm');
	}

	public function export_excel()
	{

		$area   = $this->input->get('area');
		$mesin  = $this->input->get('mesin');
		$status = $this->input->get('status');
		$start  = $this->input->get('start');
		$end    = $this->input->get('end');
		$pending = $this->input->get('pending');

		$data = $this->Pm_model->get_export(
			$area,$mesin,$status,$start,$end,$pending
		);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

    /* =========================
       JUDUL LAPORAN
       ========================= */

       $sheet->setCellValue('A1','LAPORAN HISTORY PREVENTETIVE MAINTENANCE');
       $sheet->mergeCells('A1:H1');

       $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
       $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

       if(!empty($start) && !empty($end)){
       	$sheet->setCellValue('A2','Periode : '.$start.' s/d '.$end);
       }else{
       	$sheet->setCellValue('A2','Semua Periode');
       }
       $sheet->mergeCells('A2:H2');

       $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /* =========================
       HEADER TABEL
       ========================= */

       $sheet->setCellValue('A4','No');
       $sheet->setCellValue('B4','Tanggal');
       $sheet->setCellValue('C4','Area');
       $sheet->setCellValue('D4','Mesin');
       $sheet->setCellValue('E4','Keluhan');
       $sheet->setCellValue('F4','Pending (Hari)');
       $sheet->setCellValue('G4','Status');
       $sheet->setCellValue('H4','Tindakan');

       $sheet->getStyle('A4:H4')->getFont()->setBold(true);

       $sheet->getStyle('A4:H4')->getFill()->setFillType(Fill::FILL_SOLID);
       $sheet->getStyle('A4:H4')->getFill()->getStartColor()->setARGB('FFE7E6E6');

       $sheet->getStyle('A4:H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /* =========================
       DATA
       ========================= */

       $row = 5;
       $no  = 1;

       foreach($data as $d){

       	$sheet->setCellValue('A'.$row,$no++);
       	$sheet->setCellValue('B'.$row,date('d-m-Y',strtotime($d->created_at)));
       	$sheet->setCellValue('C'.$row,$d->nama_area);
       	$sheet->setCellValue('D'.$row,$d->nama_mesin);
       	$sheet->setCellValue('E'.$row,$d->keluhan);
       	$sheet->setCellValue('F'.$row,$d->selisih);
       	$sheet->setCellValue('G'.$row,$d->kondisi);
       	$sheet->setCellValue('H'.$row,$d->tindakan);

       	$row++;
       }

    /* =========================
       BORDER TABEL
       ========================= */

       $lastRow = $row - 1;

       $sheet->getStyle('A4:H'.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    /* =========================
       AUTO WIDTH
       ========================= */

       foreach(range('A','H') as $col){
       	$sheet->getColumnDimension($col)->setAutoSize(true);
       }

    /* =========================
       FREEZE HEADER
       ========================= */

       $sheet->freezePane('A5');

    /* =========================
       AUTO FILTER
       ========================= */

       $sheet->setAutoFilter('A4:H'.$lastRow);

    /* =========================
       DOWNLOAD
       ========================= */

       $filename = "laporan_preventetive_maintenance_".date('Ymd_His').".xlsx";

       if (ob_get_length()) ob_end_clean();

       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       header('Content-Disposition: attachment; filename="'.$filename.'"');
       header('Cache-Control: max-age=0');

       $writer = new Xlsx($spreadsheet);
       $writer->save('php://output');

       exit;
    }
 }