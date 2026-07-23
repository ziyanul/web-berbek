<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Gmp_model extends CI_Model 
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
    $sesuai = $this->db->select('gmp_uuid')
    ->from('status_gmp')->where('status', 1)->get_compiled_select();
    $this->db->select('a.*, b.kegiatan, c.lokasi, d.nama_area'); // Add alias for the new table
    $this->db->select('(SELECT e.status FROM status_gmp e WHERE e.gmp_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
    $this->db->from('gmp a');
    $this->db->join('kegiatan_gmp b', 'a.kegiatan_uuid = b.uuid', 'left');
    $this->db->join('lokasi_gmp c', 'b.lokasi_uuid = c.uuid', 'left');
    $this->db->join('area d', 'c.area_uuid = d.uuid', 'left');
    $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();
    
    $filtered_data = []; // To store records that meet the criteria
    
    foreach ($data as $val) {
      $status = $val->status;
      $tgl_post = new DateTime($val->created_at);
      $tgl_now = new DateTime($now);
      $selisih = $tgl_post->diff($tgl_now)->days;
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
          $val->status_gmp = '<span class="text-dark">-</span>';
        } else {
          $val->status_gmp = '<span class="text-warning">Menunggu ACC</span>';
        }
      }
      if ($status == 1) {
        $val->status_gmp = '<span class="text-success">SESUAI</span>';
      } else if ($status == 2) {
        $val->status_gmp = '<span class="text-danger">Pembersihan Ulang</span>';
      }
      if ($now >= $jadwal) {
        $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
      } else {
        $val->jdwl = '<span class="text-dark">-</span>';
      }

        // Only add to filtered_data if $now < $jadwal
      if ($now < $jadwal) {
        $filtered_data[] = $val;
      }
    }
    
    return $filtered_data;
  }

  public function get_all_data()
  {
    $sesuai = $this->db->select('gmp_uuid')
    ->from('status_gmp')
    ->where('status', 1)
    ->get_compiled_select();

    $this->db->select('a.uuid, a.username, a.created_at, a.target, a.jadwal, a.pelaksana, d.nama_area, a.dokumentasi_acc, k.kegiatan, l.uuid as lokasi_uuid, l.lokasi, a.created_at as tglpost');
    $this->db->select('(SELECT s.status FROM status_gmp s WHERE s.gmp_uuid = a.uuid ORDER BY s.created_at DESC LIMIT 1) as status', false);
    $this->db->select('(SELECT SUM(TIMESTAMPDIFF(HOUR, p.start, p.end)) - SUM(p.clean / 60) FROM t_planning p WHERE p.tanggal >= a.created_at) as rh_plan', false);
    $this->db->from('gmp a');
    $this->db->join('kegiatan_gmp k', 'a.kegiatan_uuid = k.uuid', 'left');
    $this->db->join('lokasi_gmp l', 'l.uuid = k.lokasi_uuid', 'left');
    $this->db->join('area d', 'l.area_uuid = d.uuid', 'left');
    $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();



    return $data;
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

      if ($val->jadwal == 1) {
        $val->cd = $val->target - $selisih_rh_plan;
        $limit = 49;
      } else {
        $val->cd = $val->target - $tgl_post->diff($tgl_now)->days;;
        $limit = 3;
      }

      if ($status == 0) {
        if ($val->pelaksana == NULL) {
          $val->status_gmp = '<span class="text-dark">-</span>';

        } else {
          $val->status_gmp = '<span class="text-warning">Menunggu ACC</span>';
        }
      }
      if ($status == 1) {
        $val->status_gmp = '<span class="text-success">SESUAI</span>';
      } else if ($status == 2) {
        $val->status_gmp = '<span class="text-danger">AM Ulang</span>';
      } 
      if ($val->cd < $limit) {
        $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
      } else {
        $val->jdwl = '<span class="text-dark">-</span>';
        $filtered_data[] = $val;
      }
    }
    return $filtered_data;
  }


  public function get_area()
  {
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get('area')->result();
  }

  public function get_lokasi()
  {
    $this->db->select('a.*, b.nama_area');
    $this->db->from('lokasi_gmp a');
    $this->db->join('area b', 'a.area_uuid = b.uuid');
    $data = $this->db->get()->result();
    return $data;
  }

  public function get_kegiatan()
  {
    $this->db->select('a.*, a.uuid as kegiatan_uuid, b.*, c.*');
    $this->db->select('(SELECT d.kegiatan_uuid FROM gmp d WHERE d.kegiatan_uuid = a.uuid ORDER BY c.created_at DESC LIMIT 1 ) as keterangan', false);
    $this->db->from('kegiatan_gmp a');
    $this->db->join('lokasi_gmp b', 'a.lokasi_uuid = b.uuid');
    $this->db->join('area c', 'b.area_uuid = c.uuid');
    $this->db->order_by('a.created_at', 'DESC');
    $data = $this->db->get()->result();

    foreach ($data as $val) {
     if ($val->keterangan == NULL) {
       $val->keterangan = '<span class="text-danger">Belum</span>';
     } else {
      $val->keterangan = '<span class="text-success">Sudah</span>';
    }
  }
  return $data;
}

public function get_gmp()
{
  $now = date('Y-m-d H:i:s');
  $sesuai = $this->db->select('gmp_uuid')
  ->from('status_gmp')->where('status', 1)->get_compiled_select();

        $this->db->select('a.*, b.kegiatan, c.lokasi, d.nama_area'); // Add alias for the new table
        $this->db->select('(SELECT e.status FROM status_gmp e WHERE e.gmp_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
        $this->db->from('gmp a');
        $this->db->join('kegiatan_gmp b', 'a.kegiatan_uuid = b.uuid', 'left');
        $this->db->join('lokasi_gmp c', 'b.lokasi_uuid = c.uuid', 'left');
        $this->db->join('area d', 'c.area_uuid = d.uuid', 'left');
        // $this->db->join('status_gmp e', 'e.gmp_uuid = a.uuid', 'left');
        $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
        $this->db->where("DATE_ADD(a.created_at, INTERVAL CASE WHEN a.target > 2 THEN a.target - 2 ELSE a.target - 0 END DAY) <= '$now'", null, false);
        $this->db->order_by('a.created_at', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
          $status = $val->status;
          $tgl_post = new DateTime($val->created_at);
          $tgl_now = new DateTime($now);
          $selisih = $tgl_post->diff($tgl_now)->days;
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
              $val->status_gmp = '<span class="text-dark">-</span>';
            } else {
              $val->status_gmp = '<span class="text-warning">Menunggu ACC</span>';
            }
          }
          if ($status == 1) {
            $val->status_gmp = '<span class="text-success">SESUAI</span>';
          } else if ($status == 2) {
            $val->status_gmp = '<span class="text-danger">Pembersihan Ulang</span>';
          } 
          if ($now >= $jadwal) {
           $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
         } else {
           $val->jdwl = '<span class="text-dark">-</span>';
         }
       }
       return $data;

     }

     public function get_gmp_data()
     {
       $data = $this->get_all_data();
       $filtered_data = array();

       foreach ($data as $val) {
        $now = date('Y-m-d H:i:s');
        $status = $val->status;
        $tgl_post = new DateTime($val->created_at);
        $tgl_now = new DateTime($now);
        $selisih_rh_plan = $val->rh_plan;

        if ($val->jadwal == 1) {
          $val->cd = $val->target - $selisih_rh_plan;
          $limit = 49;
        } else {
          $val->cd = $val->target - $tgl_post->diff($tgl_now)->days;;
          $limit = 3;
        }

        if ($status == 0) {
          if ($val->pelaksana == NULL) {
            $val->status_gmp = '<span class="text-dark">-</span>';

          } else {
            $val->status_gmp = '<span class="text-warning">Menunggu ACC</span>';

          }
        }
        if ($status == 1) {
          $val->status_gmp = '<span class="text-success">SESUAI</span>';
        } else if ($status == 2) {
          $val->status_gmp = '<span class="text-danger">AM Ulang</span>';

        }


        if ($val->cd < $limit) {
          $val->jdwl = '<span class="text-danger">Penjadwalan</span>';
          $filtered_data[] = $val;
        } else {
          $val->jdwl = '<span class="text-dark">-</span>';

        }
      }
      return $filtered_data;
    }



    public function get_history()
    {
      $now = date('Y-m-d H:i:s');
    	$this->db->select('a.*, b.kegiatan, c.lokasi, d.nama_area'); // Add alias for the new table
    	$this->db->select('(SELECT e.status FROM status_gmp e WHERE e.gmp_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
    	$this->db->from('gmp a');
    	$this->db->join('kegiatan_gmp b', 'a.kegiatan_uuid = b.uuid', 'left');
    	$this->db->join('lokasi_gmp c', 'b.lokasi_uuid = c.uuid', 'left');
    	$this->db->join('area d', 'c.area_uuid = d.uuid', 'left');
      $this->db->join('status_gmp e', 'a.uuid = e.gmp_uuid');
      $this->db->where('e.status', 1);
      $this->db->order_by('e.created_at', 'DESC');
      $data = $this->db->get()->result();
      foreach ($data as $val) {
        $status = $val->status;
        $rentang = $val->target - 1;
        $tgl_post = new DateTime($val->created_at);
        $tgl_now = new DateTime($now);
        $selisih = $tgl_post->diff($tgl_now)->days;
        $val->cd = $val->target - $selisih;
        $jadwal = date('Y-m-d H:i:s', strtotime($val->created_at . '+' . $rentang . 'days'));
        if ($status == 0) {
          if ($val->pelaksana == NULL) {
            $val->status_gmp = '<span class="text-dark">-</span>';
          } else {
            $val->status_gmp = '<span class="text-warning">Menunggu ACC</span>';
          }
        }
        if ($status == 1) {
          $val->status_gmp = '<span class="text-success">SESUAI</span>';
        } else if ($status == 2) {
          $val->status_gmp = '<span class="text-danger">Pembersihan Ulang</span>';
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
     return $this->db->get_where('gmp', array('uuid' => $uuid ))->row();
   }

   public function get_detail($uuid)
   {
       // $now = date('Y-m-d H:i:s');
       $this->db->select('a.*, b.kegiatan, c.lokasi, d.nama_area'); // Add alias for the new table
       $this->db->select('(SELECT e.status FROM status_gmp e WHERE e.gmp_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
       $this->db->from('gmp a');
       $this->db->join('kegiatan_gmp b', 'a.kegiatan_uuid = b.uuid', 'left');
       $this->db->join('lokasi_gmp c', 'b.lokasi_uuid = c.uuid', 'left');
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
       //          $val->status_gmp = '<span class="text-success">SESUAI</span>';
       //      } else if ($status == 2) {
       //          $val->status_gmp = '<span class="text-danger">Pembersihan Ulang</span>';
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
       $uuid 			= Uuid::uuid4()->toString();
       $kegiatan_uuid 	= $this->input->post('kegiatan');
       $kegiatan 		= $this->input->post('kegiatan_name');
       $target 		= $this->input->post('target');

        // Periksa apakah kegiatan_uuid sudah ada dalam tabel 'am'
       $existing_kegiatan = $this->db->get_where('gmp', array('kegiatan_uuid' => $kegiatan_uuid))->row();

    // Jika kegiatan_uuid sudah ada, maka operasi sisipan gagal
       if ($existing_kegiatan) {
        $this->session->set_flashdata('error_msg', 'Input gagal / data sudah pernah di input');
        return false;
      }


      $data = array(
        'uuid' 				=> $uuid,
        'user_uuid'           => $this->auth_model->current_user()->uuid,
        'username'          => $this->auth_model->current_user()->username,
        'kegiatan' 			=> $kegiatan,
        'kegiatan_uuid' 	=> $kegiatan_uuid,
        'target' 			=> $target
      );
    	// print_r($data);
      $this->db->insert('gmp', $data);
      return ($this->db->affected_rows() > 0) ? true : false;

    }

   public function insertlokasi()
   {
     $uuid 		= Uuid::uuid4()->toString();
     $area 		= $this->input->post('area');
     $area_name 	= $this->input->post('area_name');
     $lokasi 	= $this->input->post('lokasi');


     $data = array(
      'uuid' 			=> $uuid,
      'area_uuid' 	=> $area,
      'user_uuid'           => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'lokasi' 		=> $lokasi
    );

     $this->db->insert('lokasi_gmp', $data);
     return ($this->db->affected_rows() > 0) ? true : false;

   }

   public function insertkegiatan()
   {
     $uuid 			= Uuid::uuid4()->toString();
     $lokasi 		= $this->input->post('lokasi');
     $lokasi_name 	= $this->input->post('lokasi_name');
     $kegiatan 		= $this->input->post('kegiatan');

     $data = array(
      'uuid' 			=> $uuid,
      'lokasi_uuid' 	=> $lokasi,
      'user_uuid'           => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'kegiatan' 		=> $kegiatan
    );

     $this->db->insert('kegiatan_gmp', $data);
     return ($this->db->affected_rows() > 0) ? true : false;

   }

   public function insert_kondisi($id)
{
    $data = $this->db->get_where('kegiatan_gmp', array('id' => $id))->row();
    $kondisi = $this->input->post('kondisi');
    $tindakan = $this->input->post('tindakan');
    $larutan_used = $this->input->post('larutan_used');
    $kode = $this->input->post('kode');

    foreach ($kondisi as $index => $kondisi_val) {
        // Generate a UUID for the kondisi_area
        $kondisi_uuid = Uuid::uuid4()->toString();

        // Insert all data directly into kondisi_area
        $insert_data_kondisi = array(
            'uuid'               => $kondisi_uuid,
            'kegiatan_gmp_uuid'  => $data->uuid,
            'kondisi'            => $kondisi_val,
            'tindakan'           => isset($tindakan[$index]) ? $tindakan[$index] : null,
            'target'             => isset($larutan_used[$index]) ? $larutan_used[$index] : null,
            'kode_chemical_uuid' => isset($kode[$index]) ? $kode[$index] : null
        );
        $this->db->insert('kondisi_area', $insert_data_kondisi);
    }
    
    return ($this->db->affected_rows() > 0) ? true : false;
}



  public function update_target($uuid)
   {
    $data = $this->db->get_where('kegiatan_gmp', array('uuid' => $uuid))->row();
    $kode = $this->input->post('kode');
    $larutan_used = $this->input->post('larutan_used');
    foreach ($kode as $index => $kode_val) {
      $uuids = Uuid::uuid4()->toString();
      $pemakaian = $larutan_used[$index];
      $insert_data = array(
        'uuid' => $uuids,
        'username' => $this->auth_model->current_user()->username,
        'kegiatan_gmp_uuid' => $data->uuid,
        'chemical_id' => $kode_val,
        'pemakaian' => $pemakaian
      );
      $this->db->insert('sanitasi_chemical', $insert_data);
    }
    return ($this->db->affected_rows() > 0) ? true : false;
  }

  public function insertnew($uuid)
  {
    $data = $this->db->get_where('gmp', array('uuid' => $uuid))->row();
    $uuid       = Uuid::uuid4()->toString();

    $data = array(
      'uuid'          => $uuid,
      'user_uuid'         => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'kegiatan'      => $data->kegiatan,
      'kegiatan_uuid' => $data->kegiatan_uuid,
      'target'        => $data->target,
      'jadwal'        => $data->jadwal
    );

    $this->db->insert('gmp', $data);
    return ($this->db->affected_rows() > 0) ? true : false;

  }

  public function status($uuid)
  {
   $data   = $this->db->get_where('gmp', array('uuid' => $uuid))->row();
   $uuid 		= Uuid::uuid4()->toString();
   $status 	= $this->input->post('status');
   $catatan = $this->input->post('catatan');

   $data = array(
    'uuid' 			=> $uuid,
    'user_uuid'           => $this->auth_model->current_user()->uuid,
    'username'          => $this->auth_model->current_user()->username,
    'gmp_uuid' 		=> $data->uuid,
    'status' 		=> $status,
    'catatan' 		=> $catatan

  );

   $this->db->insert('status_gmp', $data);
   return ($this->db->affected_rows() > 0) ? true : false;

 }

 public function update($uuid, $dok_after)
 {
  $target          = $this->input->post('target');
  $jadwal          = $this->input->post('jadwal');
  $pelaksana       = $this->input->post('pelaksana');

  $data = array(

    'pelaksana'         => $pelaksana,
    'target'            => $target,
    'jadwal'            => $jadwal,
    'dokumentasi_acc'   => $dok_after,
    'modified_at'       => date('Y-m-d h:i:s')
  );  

        $this->db->update('gmp', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false;
      }



      public function get_status_by_gmp_uuid($uuid)
      {
       $this->db->order_by('created_at','DESC');
       $data = $this->db->get_where('status_gmp', array('gmp_uuid' => $uuid))->result();

       foreach ($data as $val) {
        $status = $val->status;
        if ($status == 1) {
         $val->status_gmp = '<span class="text-success">Sesuai</span>';
       } else if ($status == 2) {
         $val->status_gmp = '<span class="text-danger">Pembersihan Ulang</span>';
       }
     }
     return $data;
   }

	public function updatelokasi($uuid)
	{
		// $area = $this->input->post('area'); // mendapatkan data dari input area
		$lokasi = $this->input->post('lokasi'); // mendapatkan data dari input area
		$data = array( // inisiasi data yang di input ke database
			'user_uuid'          => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'lokasi' 	=> $lokasi,
      'modified_at' => date('Y-m-d h:i:s')
    );

		$this->db->update('lokasi_gmp', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}

	public function updatekegiatan($uuid)
	{
		// $area = $this->input->post('area'); // mendapatkan data dari input area
		$kegiatan = $this->input->post('kegiatan'); // mendapatkan data dari input area
		$data = array( // inisiasi data yang di input ke database
			'user_uuid'          => $this->auth_model->current_user()->uuid,
      'username'          => $this->auth_model->current_user()->username,
      'kegiatan' 	=> $kegiatan,
      'modified_at' => date('Y-m-d h:i:s')
    );

		$this->db->update('kegiatan_gmp', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}

	public function get_area_name($uuid) 
	{
		return $this->db->get_where('area', array('uuid' => $uuid ))->row();
	}

  public function get_all_lokasi($uuid)
  {
    return $this->db->get_where('lokasi_gmp', array('area_uuid' => $uuid))->result();
  }

  public function get_all_kegiatan($uuid)
  {
    return $this->db->get_where('kegiatan_gmp', array('lokasi_uuid' => $uuid))->result();
  }

  public function get_kegiatan_name($uuid)
  {
    return $this->db->get_where('kegiatan_gmp', array('uuid' => $uuid))->row();
  }

  public function get_lokasi_by_area($area_uuid)
  {
    return $this->db->get_where('lokasi_gmp', array('area_uuid' => $area_uuid))->result();
  }

  public function lokasi_by_area($uuid)
  {
    return $this->db->get_where('lokasi_gmp', array('uuid' => $uuid))->row();
  }

  public function get_kegiatan_by_lokasi($lokasi_uuid)
  {

    return $this->db->get_where('kegiatan_gmp', array('lokasi_uuid' => $lokasi_uuid))->result();

  }

  public function get_total_gmp()
  {
   $now = date('Y-m-d H:i:s');
   $sesuai = $this->db->select('gmp_uuid')
   ->from('status_gmp')->where('status', 1)->get_compiled_select();

        $this->db->select('a.*'); // Add alias for the new table
        $this->db->select('(SELECT e.status FROM status_gmp e WHERE e.gmp_uuid = a.uuid ORDER BY e.created_at DESC LIMIT 1 ) as status', false);
        $this->db->from('gmp a');
        
        // $this->db->join('status_gmp e', 'e.gmp_uuid = a.uuid', 'left');
        $this->db->where("a.uuid NOT IN ($sesuai)", null, false);
        $this->db->where("DATE_ADD(a.created_at, INTERVAL a.target - 2 DAY) <= '$now'", null, false);
        $this->db->order_by('a.created_at', 'DESC');
        $data = $this->db->get()->num_rows();
        return $data;
      }

      public function delete_kegiatan($uuid)
      {
        $this->db->where('uuid', $uuid);
        $this->db->delete('kegiatan_gmp');
      }

      public function delete_gmp($uuid)
      {
        $this->db->where('uuid', $uuid);
        $this->db->delete('gmp');
      }

      public function cek_area_name($uuid)
      {
        $this->db->select('nama_area');
        $this->db->from('area');
        $this->db->where('uuid', $uuid);
        $query = $this->db->get();
    return $query->row();  // Mengembalikan objek dengan properti area
  }

  public function cek_lokasi_name($uuid)
  {
    $this->db->select('lokasi');
    $this->db->from('lokasi_gmp');
    $this->db->where('uuid', $uuid);
    $query = $this->db->get();
    return $query->row();  // Mengembalikan objek dengan properti lokasi
  }

  public function cek_kegiatan_name($uuid)
  {
    $this->db->select('kegiatan');
    $this->db->from('kegiatan_gmp');
    $this->db->where('uuid', $uuid);
    $query = $this->db->get();
    return $query->row();  // Mengembalikan objek dengan properti kegiatan
  }

  public function cek_kegiatan_by_lokasi($lokasi_uuid)
  {
    $this->db->select('uuid, kegiatan');
    $this->db->from('kegiatan_gmp');
    $this->db->where('lokasi_uuid', $lokasi_uuid);
    $query = $this->db->get();
    return $query->result();  // Mengembalikan array objek kegiatan
  }



}