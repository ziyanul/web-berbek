<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('View_model');
		$this->load->model('Varian_model');
		$this->load->model('Home_model');
		$this->load->model('Filler_model');
		$this->load->model('Rj_filler_model');
		$this->load->library('form_validation');
		$this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');
	}

	public function index()
	{
		$selected_varian = $this->input->get('varian');

		if (empty($selected_varian)) {
			$last = $this->View_model->get_last_plan_uuid();
			if ($last) {
				$selected_varian = $last->varian;
			}
		}

		$data = array(
			'performa' => $this->View_model->get_performa_data_by_varian($selected_varian),
			'plan' => $this->View_model->get_plan_data(),
			'chart_config' => $this->View_model->getChartConfig(),
			'uuid_options' => $this->View_model->getDistinctUUID(),
			'config' => $this->View_model->getChartConfig(),
			'chart_config' => $this->View_model->getChartConfig(),
			'selected_varian' => $selected_varian
		);

		// $this->load->view('partials/head-view');
		$this->load->view('view/view', $data);
		$this->load->view('partials/footer');
	}

	public function formula()
	{
		$uuid = $this->input->post('uuid');
		$rules = [
			[
				'field' => 'formula',
				'label' => 'Formula',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->View_model->update_formula($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('view/formula');
			} else {
				redirect('view/formula');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		
		$data = array(
			'data' => $this->View_model->get_formula_plan_data(),			
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/formula', $data);
		$this->load->view('partials/footer');
	}

	public function get_data_by_varian($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->View_model->get_performa_data_by_varian($varian_uuid);
		echo json_encode($data);
	}

	public function get_cooking_by_varian($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->View_model->get_cooking_by_varian($varian_uuid);
		echo json_encode($data);
	}

	public function get_cooking_mesin_by_varian($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		
		$data = $this->View_model->get_cooking_mesin_by_varian($varian_uuid);
		echo json_encode($data);
	}

	public function get_total_mesin_cooking($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->View_model->get_total_mesin_cooking($varian_uuid);
		echo json_encode($data);
	}

	public function get_total_berat_per_mesin($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->Rj_filler_model->get_total_berat_per_mesin($varian_uuid);
		echo json_encode($data);
	}

	public function get_total_berat_per_operator($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->Rj_filler_model->get_total_berat_per_operator($varian_uuid);
		echo json_encode($data);
	}

	public function get_badpro_chart()
{
	$uuid = $this->input->post('uuid');
	$varian_uuid = $this->input->post('varian_uuid');

	if ($uuid != '0') {
		// Jika UUID dipilih, abaikan varian, pakai per plan
		$data = $this->View_model->get_rata_bp_perplan($uuid);
	} else {
		// Jika UUID tidak dipilih, pakai varian
		$data = $this->View_model->get_rata_badpro($varian_uuid);
	}

	echo json_encode($data);
}

	public function get_smfg_chart()
	{
		$uuid = $this->input->post('uuid');

		if ($uuid == '0') {
			$data = $this->View_model->get_rata_smfg();
		} else {
			$data = $this->View_model->get_rata_smfg_perplan($uuid);
		}

		echo json_encode($data);
	}


	public function get_performa_chart($varian_uuid = null) {
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}

		$data = $this->View_model->get_performa_by_varian($varian_uuid);
		echo json_encode($data);
	}

	public function get_sortasi_persen_by_plan($varian_uuid = null)
	{
		if (!$varian_uuid) {
			echo json_encode([]);
			return;
		}
		$data = $this->View_model->get_sortasi_persen_by_plan($varian_uuid);
		echo json_encode($data);
	}

	public function performamesin()
	{
		$rules = [
			[
				'field' => 'mesin[]',
				'label' => 'Mesin',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_counter = $this->View_model->insert_performamesin();
			if ($insert_counter) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/performamesin');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/performamesin');
			}
		}

		$uuid = $this->input->post('uuid');

		$data = array(
			'data' => $this->View_model->get_performa_mesin(),
			'mesin' => $this->View_model->get_mesin_filler(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/performamesin', $data);
		$this->load->view('partials/footer');

	}

	public function get_plan_data_by_varian()
	{
		$varian_uuid = $this->input->post('varian');
		$data = $this->View_model->getPlanningDataByVarian($varian_uuid);
		echo json_encode($data);
	}

	public function rj_mesin()
	{
		$rules = [
			[
				'field' => 'mesin[]',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 't_planning',
				'label' => 'Mesin',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_rj_mesin = $this->View_model->insert_rj_mesin();
			if ($insert_rj_mesin) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/rj_mesin');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/rj_mesin');
			}
		}

		$data = array(
			'data' => $this->View_model->get_reject_mesin(),
			'mesin' => $this->View_model->get_mesin_filler(),
			'varian' => $this->Varian_model->get_all()
		);
		
		$this->load->view('partials/head-view');
		$this->load->view('view/rj-mesin', $data);
		$this->load->view('partials/footer');

	}

	public function rj_cooking()
	{
		$rules = [
			
			[
				'field' => 't_planning',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_rj_mesin = $this->View_model->insert_rj_cooking();
			if ($insert_rj_mesin) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/rj_cooking');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/rj_cooking');
			}
		}

		$data = array(
			'data' => $this->View_model->get_reject_cooking(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/rj-cooking', $data);
		$this->load->view('partials/footer');

	}

	public function sortasi()
	{
		$rules = [
			
			[
				'field' => 't_planning',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'persen',
				'label' => 'Persen dari Reject',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_sortasi = $this->View_model->insert_sortasi();
			if ($insert_sortasi) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/sortasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/sortasi');
			}
		}

		$data = array(
			'data' => $this->View_model->get_sortasi_by_plan()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/rj-sortasi', $data);
		$this->load->view('partials/footer');
	}

	public function tambah_srbadpro()
	{
		$rules = [
			
			[
				'field' => 't_planning',
				'label' => 'Tanggal',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_sortasi_bp = $this->View_model->insert_sortasi_badpro();
			if ($insert_sortasi_bp) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/srbadpro');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/srbadpro');
			}
		}

		$data = array(
			'data' => $this->View_model->get_sortasi_data(),
			'badpro' => $this->View_model->get_badpro(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/tambah-srbadpro', $data);
		$this->load->view('partials/footer');
	}

	public function srbadpro()
	{
		$data = array(
			'data' => $this->View_model->get_sortasi_data(),
			'badpro' => $this->View_model->get_badpro(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/sr-badpro', $data);
		$this->load->view('partials/footer');
	}

	public function detailsrbp($uuid)
	{
		$data = array(
			'data' => $this->View_model->get_detail_srbp($uuid),
			'badpro' => $this->View_model->get_badpro()			
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/detail-srbp', $data);
		$this->load->view('partials/footer');
	}

	public function get_used_t_planning()
	{
		$this->db->select('t_planning_uuid');
		$this->db->from('ch_rj_cooking');
		$this->db->where('deleted_at', null);
		$data = $this->db->get()->result();

		$used = array_map(function($item) {
			return $item->t_planning_uuid;
		}, $data);

		echo json_encode($used);
	}

	public function get_used_rj_filler_mesin()
	{
		$this->db->select('planning_uuid');
		$this->db->from('rj_filler');
		$this->db->where('deleted_at', null);
		$this->db->where('mesin_uuid IS NOT NULL', null, false);

		$data = $this->db->get()->result();

		$used = array_map(function($item) {
			return $item->planning_uuid;
		}, $data);

		echo json_encode($used);
	}

	public function editperforma()
	{
		$uuid = $this->input->post('uuid');
		$rules = [
			[
				'field' => 'performa',
				'label' => 'Performa',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->View_model->update_performa($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('view/performamesin');
			} else {
				redirect('view/performamesin');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
	}

	public function edit_detailsb()
	{
		$uuid = $this->input->post('uuid');

		$retur = $this->db->get_where('v_sr_badpro', ['uuid' => $uuid])->row();

		$rules = [
			[
				'field' => 'jumlah',
				'label' => 'Berat %',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->View_model->update_srbadpro($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}

			redirect('view/detailsrbp/' . $retur->t_planning_uuid);
		} else {
        // Jika validasi gagal, kamu bisa redirect balik atau tampilkan form lagi
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('view/detailsrbp/' . $retur->t_planning_uuid);
		}
	}

	public function editrjcooking()
	{
		$uuid = $this->input->post('uuid');
		$rules = [
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->View_model->update_rjcooking($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('view/rj_cooking');
			} else {
				redirect('view/rj_cooking');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
	}

	public function editrcmesin()
	{
		$uuid = $this->input->post('uuid');
		$rules = [
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->View_model->update_rcmesin($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('view/rj_mesin');
			} else {
				redirect('view/rj_mesin');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
	}

	public function badpro_tambah()
	{
		$rules = [
			[
				'field' => 'badpro',
				'label' => 'Bad Produk',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$insert = $this->View_model->insert_badpro();
			if ($insert) {
				echo json_encode([
					'status' => 'success',
					'data' => [
						'uuid' => $insert['uuid'],
						'badpro' => $insert['badpro']
					]
				]);
				exit;
			} else {
				echo json_encode(['status' => 'error']);
				exit;
			}
		} else {
			echo json_encode(['status' => 'error']);
			exit;
		}
	}

	public function get_data_by_uuid()
	{
		$uuid = $this->input->post('uuid');
		$data = $this->View_model->getByUuid($uuid);
		echo json_encode($data);
	}

	public function get_plandata_by_uuid()
	{
		$uuid = $this->input->post('uuid');
		$data = $this->View_model->get_plan_data_by_uuid($uuid);
		echo json_encode($data);
	}

	public function get_data_cooking()
	{
		$uuid = $this->input->post('uuid');
		$data = $this->View_model->get_data_cooking($uuid);
		echo json_encode($data);
	}

	public function get_data_sortasi()
	{
		$uuid = $this->input->post('uuid');
		$data = $this->View_model->get_data_sortasi($uuid);
		echo json_encode($data);
	}

	public function get_cooking_per_mesin()
	{
		$uuid = $this->input->post('uuid');
		$data = $this->View_model->get_cooking_per_mesin($uuid);
		echo json_encode($data);
	}	

	public function hapus_performa($uuid)
	{
		$update = $this->View_model->delete_performa($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function hapus_rjcooking($uuid)
	{
		$update = $this->View_model->delete_rjcooking($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function hapus_rcmesin($uuid)
	{
		$update = $this->View_model->delete_rcmesin($uuid);
 
		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function hapus_srbadpro($uuid)
	{
		$update = $this->View_model->delete_srbadpro($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function smfg()
	{
		$data = array(
			'data' => $this->View_model->get_smfg_data()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/rj-smfg', $data);
		$this->load->view('partials/footer');
	}

	public function tambah_smfg()
	{
		$rules = [
			
			[
				'field' => 't_planning',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'badpro_uuid[]',
				'label' => 'Bad Produk',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah[]',
				'label' => 'Persen dari Reject',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_smfg = $this->View_model->insert_smfg();
			if ($insert_smfg) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/smfg');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/smfg');
			}
		}

		$data = array(
			'data' => $this->View_model->get_smfg_data(),
			'badpro' => $this->View_model->get_badpro(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/tambah-smfg', $data);
		$this->load->view('partials/footer');
	}

	public function detailsmfg($uuid)
	{
		$data = array(
			'data' => $this->View_model->get_detail_smfg($uuid),
			'badpro' => $this->View_model->get_badpro()			
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/detail-smfg', $data);
		$this->load->view('partials/footer');
	}

	public function edit_sortasi()
	{
		$uuid = $this->input->post('uuid');

		$retur = $this->db->get_where('v_sortasi', ['uuid' => $uuid])->row();

		$rules = [
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->View_model->update_sortasi($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
			redirect('view/sortasi/');
		} else {
        // Jika validasi gagal, kamu bisa redirect balik atau tampilkan form lagi
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('view/sortasi/');
		}
	}

	public function edit_smfg()
	{
		$uuid = $this->input->post('uuid');

		$retur = $this->db->get_where('v_smfg', ['uuid' => $uuid])->row();

		$rules = [
			[
				'field' => 'jumlah',
				'label' => 'Berat %',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->View_model->update_smfg($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
			redirect('view/detailsmfg/'. $retur->t_planning_uuid);
		} else {
        // Jika validasi gagal, kamu bisa redirect balik atau tampilkan form lagi
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('view/detailsmfg/' . $retur->t_planning_uuid);
		}
	}

	public function hapus_smfg($uuid)
	{
		$update = $this->View_model->delete_smfg($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function smfgmsn()
	{
		$data = array(
			'data' => $this->View_model->get_smfgmsn_data()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/rj-smfgmsn', $data);
		$this->load->view('partials/footer');
	}

	public function tambah_smfgmsn()
	{
		$rules = [
			
			[
				'field' => 't_planning',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'badpro_uuid[]',
				'label' => 'Bad Produk',
				'rules' => 'required'
			],
			[
				'field' => 'mesin_uuid[]',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah[]',
				'label' => 'Persen dari Reject',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert_smfgmsn = $this->View_model->insert_smfgmsn();
			if ($insert_smfgmsn) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('view/smfgmsn');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('view/smfgmsn');
			}
		}

		$data = array(
			'data' => $this->View_model->get_smfgmsn_data(),
			'badpro' => $this->View_model->get_badpro(),
			'mesin' => $this->View_model->get_mesin_filler(),
			'varian' => $this->Varian_model->get_all()
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/tambah-smfgmsn', $data);
		$this->load->view('partials/footer');
	}

	public function detailsmfgmsn($uuid)
	{
		$data = array(
			'data' => $this->View_model->get_detail_smfgmsn($uuid),
			'badpro' => $this->View_model->get_badpro()			
		);

		$this->load->view('partials/head-view');
		$this->load->view('view/detail-smfgmsn', $data);
		$this->load->view('partials/footer');
	}

	public function edit_smfgmsn()
	{
		$uuid = $this->input->post('uuid');

		$retur2 = $this->db->get_where('v_smfgmsn', ['uuid' => $uuid])->row();

		$rules = [
			[
				'field' => 'jumlah',
				'label' => 'Berat %',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->View_model->update_smfgmsn($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
			redirect('view/detailsmfgmsn/'. $retur2->t_planning_uuid);
		} else {
        // Jika validasi gagal, kamu bisa redirect balik atau tampilkan form lagi
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('view/detailsmfgmsn/' . $retur2->t_planning_uuid);
		}
	}

	public function hapus_smfgmsn($uuid)
	{
		$update = $this->View_model->delete_smfgmsn($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function hapus_sortasi($uuid)
	{
		$update = $this->View_model->delete_sortasi($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function pilih_tampil_badpro()
	{
		$chart_ids = $this->input->post('chart_id');
		$uuids = $this->input->post('badpro_uuid');

		foreach ($chart_ids as $i => $chart_id) {
			$this->View_model->updateChartConfig($chart_id, $uuids[$i]);
		}

		redirect('view/');
	}

	public function get_smfgmsn_chart()
	{
		$plan_uuid = $this->input->post('uuid');
		$badpro_uuid = $this->input->post('badpro_uuid');

		if ($plan_uuid == '0') {
			$data = $this->View_model->getChartDataByUUID($badpro_uuid);
		} else {
			$data = $this->View_model->getChartDataByUUID_perplan($plan_uuid, $badpro_uuid);
		}

		echo json_encode($data);
	}




}