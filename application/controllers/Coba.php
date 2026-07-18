<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('coba_model');
        $this->load->model('sanitasi_model');
        $this->load->model('chemical_model');
        $this->load->library('upload');
        $this->load->library('form_validation');
    }

    public function index($tanggal)
    {
        $data = array(
            'data' => $this->Sanitasi_model->get_chemical_by_area($tanggal),
            // 'data' => $this->am_model->get_am(),
            'active_nav' => 'am'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('coba/coba', $data);
        $this->load->view('partials/footer');
    }

    public function upload() 
    { 
        $config = array(
            'upload_path' => "./upload/",
            'allowed_types' => "jpg|png|jpeg|pdf",
            'overwrite' => TRUE,
            'max_size' => "2048",
            'encrypt_name' => TRUE
        );
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('ffoto')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error_msg', $error);
            redirect('coba');
        } else {
            $data = $this->upload->data();
            $f_foto = $data['file_name'];

        // Memanggil fungsi untuk resize gambar
            $resize_success = $this->resizeImage($f_foto);

        // Cek apakah proses resize berhasil
            if ($resize_success) {
            // Jika berhasil resize, lanjutkan dengan upload dan insert ke database
                $insert_success = $this->insertToDatabase($f_foto);
                if ($insert_success) {
                    $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                } else {
                    $this->session->set_flashdata('error_msg', 'Data gagal di simpan ke database.');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Gagal meresize gambar.');
            }

            redirect('coba');
        }
    }

    public function resizeImage($filename)
    {
        $source_path = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $filename;
        $target_path = $_SERVER['DOCUMENT_ROOT'] . '/upload/thumbnail/' . $filename;

        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 150,
            'height' => 150
        );

        $this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
        // Resize operation failed
            $error = $this->image_lib->display_errors();
            $this->image_lib->clear();
        return FALSE; // Kembalikan FALSE jika proses resize gagal
    } else {
        // Resize operation successful
        $this->image_lib->clear();
        return TRUE; // Kembalikan TRUE jika proses resize berhasil
    }
}

public function insertToDatabase($f_foto)
{
    $uuid = Uuid::uuid4()->toString();

    $data = array(
        'uuid' => $uuid,
        'foto' => $f_foto
    );

    $this->db->insert('t_coba', $data);

    return ($this->db->affected_rows() > 0) ? true : false;
}

public function cekform($tanggal)
{
    $data = array(
        'data' => $this->sanitasi_model->get_chemical_by_area($tanggal),
        'active_nav' => 'sanitasi-data'
    );
   
    $this->load->view('partials/head', $data);
    $this->load->view('coba/coba', $data);
    $this->load->view('partials/footer');

}

public function said()
{
    $data = array(
        // 'data' => $this->sanitasi_model->get_chemical_by_area($tanggal),
        'active_nav' => 'sanitasi-data'
    );
    $this->load->view('partials/head', $data);
    $this->load->view('errors/coba-said');
    $this->load->view('partials/footer');
}

public function said1()
{
    // $data = array(
    //     'data' => $this->sanitasi_model->get_chemical_by_area($tanggal),
    //     'active_nav' => 'sanitasi-data'
    // );

    $this->load->view('partials/said-head');
    $this->load->view('errors/coba-said-1');
    $this->load->view('partials/said-footer');
}

public function tambah($tanggal)
{
    $data = array(
        'data' => $this->chemical_model->get_pengenceran_data($tanggal),
        'active_nav' =>''
    );

    $this->load->view('partials/head', $data);
    $this->load->view('coba/tambah', $data);
    $this->load->view('partials/footer');
}
}
