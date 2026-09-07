<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Am_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth_model');
  }

  public function rules()
  {
    return [

      [
        'field' => 'kegiatan',
        'label' => 'Kegiatan',
        'rules' => 'required'
      ],
      [
        'field' => 'target',
        'label' => 'Target',
        'rules' => 'required'
      ]
    ];
  }

  public function get_all()
  {
    $now = date('Y-m-d H:i:s');
    $sesuai = $this->db->select('am_uuid')
      ->from('status_am')->where('status', 1)->get_compiled_select();

    $this->db->select('a.*, b.kegiatan, c.nama_mesin, c.device_id, c.rh_update, d.nama_area'); // Add alias for the new table
    $this->db->select('(SELECT e.status FROM status_am e WHERE e.am_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
    $this->db->from('am a');
    $this->db->join('kegiatan_am b', 'a.kegiatan_uuid = b.uuid', 'left');
    $this->db->join('mesin c', 'b.mesin_uuid = c.uuid', 'left');
    $this->db->join('area d', 'c.area_uuid = d.uuid', 'left');

    $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();
    foreach ($data as $val) {
      $status = $val->status;
      $tgl_post = new DateTime($val->created_at);
      $tgl_now = new DateTime($now);
      $val->actual_rh = 0;

      if ($val->jadwal == 2) {
        $selisih = $val->rh_update + $val->actual_rh;
      } else {
        $selisih = $tgl_post->diff($tgl_now)->days;
      }
      $val->cd = $val->target - $selisih;
      $rentang = $val->target;
      $lebih = $val->target - 2;
      if ($val->target > 2) {
        $jadwal = date('Y-m-d H:i:s', strtotime($val->created_at . '+' . $lebih . ' days'));
      } else {
        $jadwal = date('Y-m-d H:i:s', strtotime($val->created_at . '+' . $rentang . ' days'));
      }
      if ($status == 0) {
        if ($val->pelaksana == NULL) {
          $val->status_am = '<span class="text-dark">-</span>';
        } else {
          $val->status_am = '<span class="text-warning">Menunggu ACC</span>';
        }
      }
      if ($status == 1) {
        $val->status_am = '<span class="text-success">SESUAI</span>';
      } else if ($status == 2) {
        $val->status_am = '<span class="text-danger">AM Ulang</span>';
      }
      if ($now >= $jadwal) {
        $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
      } else {
        $val->jdwl = '<span class="text-dark">-</span>';
      }
    }
    return $data;
  }

  public function get_area()
  {
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get('area')->result();
  }

  public function get_mesin()
  {
    $this->db->select('a.*, b.nama_area');
    $this->db->from('mesin a');
    $this->db->join('area b', 'a.area_uuid = b.uuid');
    $data = $this->db->get()->result();
    return $data;
  }

  public function get_kegiatan()
  {
    return $this->db
      ->select('
        b.uuid as mesin_uuid,
        b.nama_mesin,
        c.nama_area,
        COUNT(a.uuid) as total_kegiatan
        ')
      ->from('kegiatan_am a')
      ->join('mesin b', 'a.mesin_uuid = b.uuid')
      ->join('area c', 'b.area_uuid = c.uuid')
      ->where('a.deleted_at IS NULL', null, false)
      ->group_by([
        'b.uuid',
        'b.nama_mesin',
        'c.nama_area'
      ])
      ->order_by('c.nama_area', 'ASC')
      ->order_by('b.nama_mesin', 'ASC')
      ->get()
      ->result();
  }

  public function get_am()
  {
    $data = $this->get_all_data();
    $filtered_data = array();

    foreach ($data as $val) {
      $now = date('Y-m-d H:i:s');
      $status = $val->status;
      $tgl_post = new DateTime($val->created_at);
      $tgl_now = new DateTime($now);

      $selisih_rh_plan = (float) $val->rh_plan;
      $selisih_rh_counter = (float) $val->rh_counter;

      // =========================
      // HITUNG COUNTDOWN
      // =========================
      if ($val->jadwal == 1) {
        // RH PLAN
        $val->cd = $val->target - $selisih_rh_plan;
        $batas_muncul = 48; // tampil jika sisa <= 48 jam
        $satuan = 'Jam';
      } elseif ($val->jadwal == 2) {
        // RH COUNTER
        $val->cd = $val->target - $selisih_rh_counter;
        $batas_muncul = 48; // tampil jika sisa <= 48 jam
        $satuan = 'Jam';
      } else {
        // HARIAN
        $val->cd = $val->target - $tgl_post->diff($tgl_now)->days;
        $batas_muncul = 3; // tampil jika sisa <= 3 hari
        $satuan = 'Hari';
      }

      $val->satuan_cd = $satuan;

      // =========================
      // STATUS AM
      // =========================
      if ($status == 0) {
        if ($val->pelaksana == NULL) {
          $val->status_am = '<span class="text-dark">-</span>';
        } else {
          $val->status_am = '<span class="text-warning">Menunggu ACC</span>';
        }
      } elseif ($status == 1) {
        $val->status_am = '<span class="text-success">SESUAI</span>';
      } elseif ($status == 2) {
        $val->status_am = '<span class="text-danger">AM Ulang</span>';
      } else {
        $val->status_am = '<span class="text-dark">-</span>';
      }

      // =========================
      // STATUS PENJADWALAN
      // =========================
      if ($val->cd < 0) {
        $val->jdwl = '<span class="badge badge-danger">Terlambat</span>';
      } elseif ($val->cd == 0) {
        $val->jdwl = '<span class="badge badge-warning">Hari Ini</span>';
      } elseif ($val->cd <= $batas_muncul) {
        $val->jdwl = '<span class="badge badge-info">Segera</span>';
      } else {
        $val->jdwl = '<span class="text-dark">-</span>';
      }

      // =========================
      // FILTER YANG MASUK INDEX
      // =========================
      if ($val->cd <= $batas_muncul) {
        $filtered_data[] = $val;
      }
    }
    usort($filtered_data, function ($a, $b) {
      return $a->cd <=> $b->cd;
    });
    return $filtered_data;
  }

  public function get_tpm()
  {
    $data = $this->get_all_data();

    $filtered_data = array();

    foreach ($data as $val) {
      $now = date('Y-m-d H:i:s');
      $status = $val->status;
      $tgl_post = new DateTime($val->created_at);
      $tgl_now = new DateTime($now);
      $selisih_rh_plan = $val->rh_plan;
      $selisih_rh_counter = $val->rh_counter;
      if ($val->jadwal == 1) {
        $val->cd = $val->target - $selisih_rh_plan;
      } elseif ($val->jadwal == 2) {
        $val->cd = $val->target - $selisih_rh_counter;
      } else {
        $val->cd = $val->target - $tgl_post->diff($tgl_now)->days;;
      }

      if ($status == 0) {
        if ($val->pelaksana == NULL) {
          $val->status_am = '<span class="text-dark">-</span>';
        } else {
          $val->status_am = '<span class="text-warning">Menunggu ACC</span>';
        }
      }
      if ($status == 1) {
        $val->status_am = '<span class="text-success">SESUAI</span>';
      } else if ($status == 2) {
        $val->status_am = '<span class="text-danger">AM Ulang</span>';
      }
      if ($val->cd < 49) {
        $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
      } else {
        $val->jdwl = '<span class="text-dark">-</span>';
        $filtered_data[] = $val;
      }
    }
    return $filtered_data;
  }

  public function get_history()
  {
    $now = date('Y-m-d H:i:s');
    $this->db->select('a.*, b.kegiatan, c.nama_mesin, d.nama_area'); // Add alias for the new table
    $this->db->select('(SELECT e.status FROM status_am e WHERE e.am_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
    $this->db->from('am a');
    $this->db->join('kegiatan_am b', 'a.kegiatan_uuid = b.uuid', 'left');
    $this->db->join('mesin c', 'b.mesin_uuid = c.uuid', 'left');
    $this->db->join('area d', 'c.area_uuid = d.uuid', 'left');
    $this->db->join('status_am e', 'a.uuid = e.am_uuid');
    $this->db->where('e.status', 1);
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();
    foreach ($data as $val) {
      $status = $val->status;
      $rentang = $val->target - 2;
      $tgl_post = new DateTime($val->created_at);
      $tgl_now = new DateTime($now);
      $selisih = $tgl_post->diff($tgl_now)->days;
      $val->cd = $val->target - $selisih;
      $jadwal = date('Y-m-d H:i:s', strtotime($val->created_at . '+' . $rentang . 'days'));
      if ($status == 0) {
        if ($val->pelaksana == NULL) {
          $val->status_am = '<span class="text-dark">-</span>';
        } else {
          $val->status_am = '<span class="text-warning">Menunggu ACC</span>';
        }
      }
      if ($status == 1) {
        $val->status_am = '<span class="text-success">SESUAI</span>';
      } else if ($status == 2) {
        $val->status_am = '<span class="text-danger">AM Ulang</span>';
      }
      if ($now >= $jadwal) {
        $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
      } else {
        $val->jdwl = '<span class="text-light">-</span>';
      }
    }
    return $data;
  }

  public function get_by_uuid($uuid)
  {
    return $this->db->get_where('am', array('uuid' => $uuid))->row();
  }

  public function get_detail($uuid)
  {
    // $now = date('Y-m-d H:i:s');
    $this->db->select('a.*, b.kegiatan, c.nama_mesin, d.nama_area, d.uuid as area_uuid, c.uuid as mesin_uuid, b.uuid as kegiatan_uuid'); // Add alias for the new table
    $this->db->select('(SELECT e.status FROM status_am e WHERE e.am_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
    $this->db->from('am a');
    $this->db->join('kegiatan_am b', 'a.kegiatan_uuid = b.uuid', 'left');
    $this->db->join('mesin c', 'b.mesin_uuid = c.uuid', 'left');
    $this->db->join('area d', 'c.area_uuid = d.uuid', 'left');
    $this->db->order_by('created_at', 'DESC');
    $this->db->where('a.uuid', $uuid);
    $data = $this->db->get()->row();
    // foreach ($data as $val) {
    //      $status = $val->status;
    //      $rentang = $val->target - 2;
    //      $tgl_post = new DateTime($val->created_at);
    //      $tgl_now = new DateTime($now);
    //      $selisih = $tgl_post->diff($tgl_now)->days;
    //      $val->cd = $val->target - $selisih;
    //      $jadwal = date('Y-m-d H:i:s', strtotime($val->created_at . '+' . $rentang . 'days'));
    //      if ($status == 1) {
    //          $val->status_am = '<span class="text-success">SESUAI</span>';
    //      } else if ($status == 2) {
    //          $val->status_am = '<span class="text-danger">Pembersihan Ulang</span>';
    //      }
    //      if ($now >= $jadwal) {
    //          $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
    //      } else {
    //          $val->jdwl = '<span class="text-light">-</span>';
    //      }
    //  }
    return $data;
  }

  public function insert()
  {
    $uuid = Uuid::uuid4()->toString();
    $kegiatan_uuid = $this->input->post('kegiatan');
    $kegiatan = $this->input->post('kegiatan_name');
    $target = $this->input->post('target');
    $jadwal = $this->input->post('jadwal');

    $existing_kegiatan = $this->db->get_where('am', array('kegiatan_uuid' => $kegiatan_uuid))->row();

    if ($existing_kegiatan) {
      $this->session->set_flashdata('error_msg', 'Input gagal / data sudah pernah di input');
      return false;
    }

    $data = array(
      'uuid'          => $uuid,
      'user_uuid'     => $this->auth_model->current_user()->uuid,
      'username'      => $this->auth_model->current_user()->username,
      'kegiatan'      => $kegiatan,
      'kegiatan_uuid' => $kegiatan_uuid,
      'jadwal'        => $jadwal,
      'target'        => $target
    );

    $this->db->insert('am', $data);
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function insert_massal($items)
  {
    $this->db->trans_start();

    $data = [];

    foreach ($items as $i) {

      // skip kalau kegiatan kosong
      if (empty($i['kegiatan_uuid'])) continue;

      // skip kalau target kosong / 0
      if (empty($i['target']) || $i['target'] == 0) continue;

      $data[] = [
        'uuid' => Uuid::uuid4()->toString(),
        'kegiatan_uuid' => $i['kegiatan_uuid'],
        'jadwal' => $i['jadwal'],
        'target' => $i['target'],

        'user_uuid' => $this->auth_model->current_user()->uuid,

        // dikerjakan -> masuk ke created_at
        'created_at' => !empty($i['dikerjakan'])
          ? $i['dikerjakan'] . ' 00:00:00'
          : date('Y-m-d H:i:s'),
      ];
    }

    if (!empty($data)) {
      $this->db->insert_batch('am', $data);
    }

    $this->db->trans_complete();

    return $this->db->trans_status();
  }
  public function insertarea()
  {
    $uuid     = Uuid::uuid4()->toString();
    $area     = $this->input->post('area');


    $data = array(
      'uuid'       => $uuid,
      'area'       => $area,
      'user_uuid'           => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,

    );

    $this->db->insert('area', $data);
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  // public function insertmesin()
  // {
  // 	$uuid 		= Uuid::uuid4()->toString();
  // 	$area 		= $this->input->post('area');
  // 	$area_name 	= $this->input->post('area_name');
  // 	$mesin 	= $this->input->post('mesin');


  // 	$data = array(
  // 		'uuid' 			=> $uuid,
  // 		'area_uuid' 	=> $area,
  // 		'user_uuid'           => $this->auth_model->current_user()->uuid,
  //         'username'          => $this->auth_model->current_user()->username,
  // 		'mesin' 		=> $mesin,

  // 	);

  // 	$this->db->insert('mesin_am', $data);
  // 	return ($this->db->affected_rows() > 0) ? true : false;

  // }

  public function insertkegiatan()
  {
    $mesin_uuid    = $this->input->post('mesin');
    $kegiatan_list = $this->input->post('kegiatan');

    $batch = [];

    foreach ($kegiatan_list as $kegiatan) {

      $kegiatan = trim($kegiatan);

      if ($kegiatan == '') continue;

      $exists = $this->db
        ->where('mesin_uuid', $mesin_uuid)
        ->where('kegiatan', $kegiatan)
        ->get('kegiatan_am')
        ->row();

      if ($exists) continue;

      $batch[] = [
        'uuid'       => Uuid::uuid4()->toString(),
        'mesin_uuid' => $mesin_uuid,
        'kegiatan'   => trim($kegiatan),
        'user_uuid'  => $this->auth_model->current_user()->uuid,
        'username'   => $this->auth_model->current_user()->username,
      ];
    }

    if (empty($batch)) {
      return false;
    }

    $this->db->insert_batch('kegiatan_am', $batch);

    return ($this->db->affected_rows() > 0);
  }

  public function insertnew($uuid)
  {
    $data = $this->db->get_where('am', array('uuid' => $uuid))->row();
    $uuid       = Uuid::uuid4()->toString();

    $data = array(
      'uuid'          => $uuid,
      'user_uuid'         => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'kegiatan'      => $data->kegiatan,
      'kegiatan_uuid' => $data->kegiatan_uuid,
      'jadwal'        => $data->jadwal,
      'target'        => $data->target
    );

    $this->db->insert('am', $data);
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function insertstatus($uuid)
  {
    $data = $this->db->get_where('am', array('uuid' => $uuid))->row();
    $uuid     = Uuid::uuid4()->toString();
    $status   = $this->input->post('status');
    $catatan = $this->input->post('catatan');

    $data = array(
      'uuid'       => $uuid,
      'user_uuid'           => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'am_uuid'     => $data->uuid,
      'status'     => $status,
      'catatan'     => $catatan

    );

    $this->db->insert('status_am', $data);
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function updatedoc($uuid, $dok_after)
  {
    $data = $this->db->get_where('am', array('uuid' => $uuid))->row();
    $uuid       = Uuid::uuid4()->toString();
    $status     = $this->input->post('status');
    $catatan = $this->input->post('catatan');
    $data = array(
      'uuid'          => $uuid,
      'user_uuid'         => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'am_uuid'      => $data->uuid,
      'status'        => $status,
      'catatan'       => $catatan,
    );

    $this->db->where('uuid', $uuid);
    $this->db->insert('status_am', $data, array('uuid' => $uuid));
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function get_status_by_am_uuid($uuid)
  {
    $this->db->order_by('created_at', 'DESC');
    $data = $this->db->get_where('status_am', array('am_uuid' => $uuid))->result();

    foreach ($data as $val) {
      $status = $val->status;
      if ($status == 1) {
        $val->status_am = '<span class="text-success">Sesuai</span>';
      } else if ($status == 2) {
        $val->status_am = '<span class="text-danger">AM Ulang</span>';
      }
    }
    return $data;
  }


  public function tindakan($uuid, $dok_after)
  {

    $pelaksana    = $this->input->post('pelaksana');
    $catatan          = $this->input->post('catatan');

    $data = array(

      'pelaksana'     => $pelaksana,
      'catatan'           => $catatan,
      'dokumentasi_acc'   => $dok_after,
      'modified_at' => date('Y-m-d h:i:s')
    );

    $this->db->update('am', $data, array('uuid' => $uuid)); // query update
    return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
  }

  public function update($uuid)
  {
    $target          = $this->input->post('target');
    $jadwal          = $this->input->post('jadwal');
    $kegiatan          = $this->input->post('kegiatan_name');
    $kegiatan_uuid          = $this->input->post('kegiatan');

    $data = array(
      'target'            => $target,
      'jadwal'           => $jadwal,
      'kegiatan'           => $kegiatan,
      'kegiatan_uuid'           => $kegiatan_uuid,
      'modified_at' => date('Y-m-d h:i:s')
    );

    $this->db->update('am', $data, array('uuid' => $uuid)); // query update
    return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
  }

  public function updatearea($uuid)
  {
    $area = $this->input->post('area'); // mendapatkan data dari input area

    $data = array( // inisiasi data yang di input ke database
      'user_uuid'          => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'area' => $area,
      'modified_at' => date('Y-m-d h:i:s')
    );

    $this->db->update('area', $data, array('uuid' => $uuid)); // query update
    return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
  }

  // public function updatemesin($uuid)
  // {
  // 	// $area = $this->input->post('area'); // mendapatkan data dari input area
  // 	$mesin = $this->input->post('mesin'); // mendapatkan data dari input area
  // 	$data = array( // inisiasi data yang di input ke database
  // 		'user_uuid'          => $this->auth_model->current_user()->uuid,
  //            'username'          => $this->auth_model->current_user()->username,
  // 		'mesin' 	=> $mesin,
  // 		'modified_at' => date('Y-m-d h:i:s')
  // 	);

  // 	$this->db->update('mesin_am', $data, array('uuid' => $uuid)); // query update
  // 	return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
  // }

  public function updatekegiatan($uuid)
  {
    // $area = $this->input->post('area'); // mendapatkan data dari input area
    $kegiatan = $this->input->post('kegiatan'); // mendapatkan data dari input area
    $data = array( // inisiasi data yang di input ke database
      'user_uuid'          => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'kegiatan'   => $kegiatan,
      'modified_at' => date('Y-m-d h:i:s')
    );

    $this->db->update('kegiatan_am', $data, array('uuid' => $uuid)); // query update
    return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
  }

  public function update_kegiatan_ajax($uuid, $kegiatan)
  {
    $this->db->where('uuid', $uuid);

    $update = $this->db->update('kegiatan_am', [
      'kegiatan'    => $kegiatan,
      'modified_at' => date('Y-m-d H:i:s')
    ]);

    $row = $this->db->get_where('kegiatan_am', [
      'uuid' => $uuid
    ])->row();

    return [
      'status' => $update,
      'mesin_uuid' => $row ? $row->mesin_uuid : null
    ];
  }

  public function get_area_name($uuid)
  {
    return $this->db->get_where('area', array('uuid' => $uuid))->row();
  }

  // public function get_all_mesin($uuid)
  // {
  //     return $this->db->get_where('mesin_am', array('area_uuid' => $uuid))->result();
  // }

  public function get_all_kegiatan($uuid)
  {
    $this->db->select('a.*, a.uuid as kegiatan_uuid, b.*, c.*');
    $this->db->from('kegiatan_am a');
    $this->db->join('mesin b', 'a.mesin_uuid = b.uuid');
    $this->db->join('area c', 'b.area_uuid = c.uuid');
    $this->db->where('a.mesin_uuid', $uuid); // Specify the table alias for 'mesin_uuid'
    $data = $this->db->get()->result();
    return $data;
  }

  public function get_kegiatan_name($uuid)
  {
    return $this->db->get_where('kegiatan_am', array('uuid' => $uuid))->row();
  }

  public function get_mesin_by_area($area_uuid)
  {
    return $this->db->where('area_uuid', $area_uuid)
      ->order_by('created_at', 'ASC')
      ->get('mesin')
      ->result();
  }

  // public function mesin_by_area($uuid)
  // {
  //     return $this->db->get_where('mesin_am', array('uuid' => $uuid))->row();
  // }

  public function get_kegiatan_by_mesin($mesin_uuid)
  {
    return $this->db
      ->where('mesin_uuid', $mesin_uuid)
      ->where('deleted_at IS NULL', null, false)
      ->get('kegiatan_am')
      ->result();
  }

  public function get_kegiatan_available($mesin_uuid)
  {
    $this->db->select('mk.uuid, mk.kegiatan');
    $this->db->from('kegiatan_am mk');

    $this->db->where('mk.mesin_uuid', $mesin_uuid);
    $this->db->where('deleted_at is null', null, false);
    $this->db->where("mk.uuid NOT IN (
        SELECT kegiatan_uuid FROM am
    )", null, false);

    return $this->db->get()->result();
  }

  public function get_total_am()
  {
    $now = date('Y-m-d H:i:s');

    // Subquery to get 'am_uuid' values where status is 1
    $sesuai = $this->db->select('am_uuid')
      ->from('status_am')
      ->where('status', 1)
      ->get_compiled_select();

    // Main query
    $this->db->select('a.target, a.created_at as tglpost, a.jadwal');
    $this->db->select('(SELECT s.status FROM status_am s WHERE s.am_uuid = a.uuid ORDER BY s.created_at DESC LIMIT 1) as status', false);
    $this->db->select('(SELECT SUM(t.counter / t.speed / 60) FROM tcounter t WHERE t.device_id = m.device_id AND t.created_at >= a.created_at) as rh_counter', false);
    $this->db->select('(SELECT SUM(TIMESTAMPDIFF(HOUR, p.start, p.end)) - SUM(p.clean / 60) FROM t_planning p WHERE p.tanggal >= a.created_at) as rh_plan', false);
    $this->db->select('
        CASE
        WHEN a.jadwal = 1 THEN (a.target - (SELECT SUM(TIMESTAMPDIFF(HOUR, p.start, p.end)) - SUM(p.clean / 60) FROM t_planning p WHERE p.tanggal >= a.created_at))
        WHEN a.jadwal = 2 THEN (a.target - (SELECT SUM(t.counter / t.speed / 60) FROM tcounter t WHERE t.device_id = m.device_id AND t.created_at >= a.created_at))
        ELSE 0
        END as cd', false);
    $this->db->from('am a');
    $this->db->join('kegiatan_am k', 'a.kegiatan_uuid = k.uuid', 'left');
    $this->db->join('mesin m', 'm.uuid = k.mesin_uuid', 'left');
    $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
    $this->db->having('cd < 49');
    $this->db->order_by('a.created_at', 'DESC');

    $data = $this->db->get()->num_rows();

    return $data;
  }




  public function delete_kegiatan($uuid)
  {
    $this->db->update('kegiatan_am', [
      'deleted_at' => date('Y-m-d H:i:s')
    ], ['uuid' => $uuid]);
  }

  public function delete_am($uuid)
  {
    $this->db->where('uuid', $uuid);
    $this->db->delete('am');
  }

  public function get_all_data()
  {
    $CI = &get_instance();
    $hak_akses = $CI->session->userdata('hak_akses');

    $sesuai = $this->db->select('am_uuid')
      ->from('status_am')
      ->where('status', 1)
      ->get_compiled_select();

    $this->db->select('a.uuid, a.username, a.created_at, a.target, a.jadwal, a.pelaksana, a.catatan, a.dokumentasi_acc, k.kegiatan, m.uuid as mesin_uuid, m.nama_area, m.nama_mesin, a.created_at as tglpost');
    $this->db->select('(SELECT s.status FROM status_am s WHERE s.am_uuid = a.uuid ORDER BY s.created_at DESC LIMIT 1) as status', false);
    $this->db->select('(SELECT SUM(t.counter / t.speed / 60) FROM tcounter t WHERE t.device_id = m.device_id AND t.created_at >= a.created_at) as rh_counter', false);
    $this->db->select('(SELECT SUM(TIMESTAMPDIFF(HOUR, p.start, p.end)) - SUM(p.clean / 60) FROM t_planning p WHERE p.tanggal >= a.created_at) as rh_plan', false);
    $this->db->from('am a');
    $this->db->join('kegiatan_am k', 'a.kegiatan_uuid = k.uuid', 'left');
    $this->db->join('mesin m', 'm.uuid = k.mesin_uuid', 'left');
    $this->db->join('users u', 'u.uuid = a.user_uuid', 'left');
    if ($hak_akses != 'engineering' && $hak_akses != 'superadmin') {
      $this->db->where('u.hak_akses', $hak_akses);
    }
    $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
    $this->db->where('a.deleted_at IS NULL', null, false);
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();

    return $data;
  }

  public function count_am()
  {
    $filtered_data = $this->get_am();
    $jumlah_data = count($filtered_data);
    return $jumlah_data;
  }
}