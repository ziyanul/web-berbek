<?php
use Ramsey\Uuid\Uuid;

class Pegawai_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Departemen_model');
		$this->load->model('auth_model');

	}

	public function rules()
	{
		return [
			[
				'field' => 'fullname',
				'label' => 'Full Name',
				'rules' => 'required'
			],
			[
				'field' => 'departemen',
				'label' => 'Departemen',
				'rules' => 'required'
			],
			[
				'field' => 'type',
				'label' => 'Type',
				'rules' => 'required'
			]
		];
	}

	public function get_departemen()
	{
		return $this->db->get('departemen')->result();
	}

	public function get_all()
	{

		$departemen = $this->get_departemen();
		$this->db->select('a.*, b.subrole');
		$this->db->from('users a');
		$this->db->join('sub_role b', 'a.uuid = b.users_uuid', 'left');
		$this->db->order_by('created_at', 'DESC');
		$this->db->where('a.deleted_at IS NULL', null, false);
		$data = $this->db->get()->result();

		foreach ($data as $row1) {

			if ($row1->type == 1) {
				$row1->type = 'Supervisor';
			} else if ($row1->type == 2) {
				$row1->type = 'Foreman / Forelady';
			} else if ($row1->type == 3) {
				$row1->type = 'Koordinator';
			} else if ($row1->type == 4) {
				$row1->type = 'Operator';
			} else {
				$row1->type = '-';
			}
			if ($row1->subrole == 1) {
				$row1->subrole = 'Operator MP';
			} elseif ($row1->subrole == 2) {
				$row1->subrole = 'Sanitasi';
			} elseif ($row1->subrole == 3) {
				$row1->subrole = 'Enginering';
			} elseif ($row1->subrole == 4) {
				$row1->subrole = 'Operator Packing';
			} else {
				$row1->subrole ='-';
			}

			foreach ($departemen as $row2) {
				if ($row1->departemen == $row2->uuid) {
					$row1->departemen = $row2->departemen;
				}
			}
		}

		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$this->db->select('a.*, b.users_uuid as user_uuid, b.subrole');
		$this->db->from('users a');
		$this->db->join('sub_role b', 'a.uuid = b.users_uuid', 'left');
		$this->db->where('a.uuid', $uuid);
		$data = $this->db->get()->row();

		$data->departemen_name = $this->Departemen_model->get_by_uuid($data->departemen);

		if ($data->type == 1) {
			$data->tipe = 'Admin';
		} else if ($data->type == 2) {
			$data->tipe = 'Head';
		} else if ($data->type == 3) {
			$data->tipe = 'Staff';
		}

		if ($data->status == 1) {
			$data->status_pegawai = 'Charoen Pokphand Indonesia';
		} else if ($data->status == 2) {
			$data->status_pegawai = 'Outsourcing';
		}
		else
			$data->status_pegawai = '';

		$data->birth_date = date('d/m/Y', strtotime(str_replace('-', '/', $data->birth_date)));
		$data->join_date = date('d/m/Y', strtotime(str_replace('-', '/', $data->join_date)));

		$data->tgl_lahir = date('d/m/Y', strtotime(str_replace('/', '-', $data->birth_date)));
		$data->tgl_bergabung = date('d/m/Y', strtotime($data->join_date));

		if ($data->resign_date !== NULL) {
			$data->resign_date = date('d/m/Y', strtotime(str_replace('-', '/', $data->resign_date)));
		} else {
			$data->resign_date = '';
		}

		return $data;
		
		// $data = $this->db->get_where('users', array('uuid' => $uuid ))->row();
		// $data->tgl_lahir = date('d/m/Y', strtotime(str_replace('/', '-', $data->birth_date)));
		// $data->tgl_bergabung = date('d/m/Y', strtotime($data->join_date));

		// return $data;
	}
	public function insert_role($uuid)
	{
		$data = $this->db->get_where('users', array('uuid' => $uuid))->row();
		$new_uuid = Uuid::uuid4()->toString();
		$sub_role = $this->input->post('sub_role');

		$data = array(
			'uuid' => $new_uuid,
			'created_by' => $this->auth_model->current_user()->username,
			'subrole' => $sub_role,
			'users_uuid' => $data->uuid
		);
		$this->db->insert('sub_role', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function tambah_role($id)
	{
		$data = $this->db->get_where('users', array('id' => $id))->row();
		$sub_role = $this->input->post('sub_role');
		$new_uuid = Uuid::uuid4()->toString();
		$history = array(
			'uuid' 			=> $new_uuid,
			'users_uuid' 	=> $data->uuid,
			'subrole' 		=> $sub_role
		);

		$this->db->insert('sub_role', $history);
		return ($this->db->affected_rows() > 0) ? true : false;

	}

	public function hapus_data($uuid)
	{
		$data = array(
			'deleted_at' => date('Y-m-d h:i:s')
		);
    $this->db->update('users', $data, array('uuid' => $uuid)); // query update
    return ($this->db->affected_rows() > 0) ? true : false;
}

public function get_sub_role($users_uuid)
{
	$data = $this->db->get_where('sub_role', array('users_uuid' => $users_uuid))->row();
	return $data;
}

public function generate_username($fullname)
{
	$base = strtolower(trim($fullname));
	$base = preg_replace('/[^a-z0-9\s]/', '', $base);
	$base = preg_replace('/\s+/', '.', $base);

	$username = $base;
	$no = 1;

	while (
		$this->db->where('username', $username)
		->count_all_results('users') > 0
	) {
		$username = $base . $no;
		$no++;
	}

	return $username;
}

public function insert()
{
	$uuid = Uuid::uuid4()->toString();

	$fullname = $this->input->post('fullname');
	$departemen_uuid = $this->input->post('departemen');
	$username = $this->generate_username($fullname);

	$dept = $this->db
	->where('uuid', $departemen_uuid)
	->get('departemen')
	->row();

	$hak_akses = $dept ? $dept->departemen : null;

	$data = [
		'uuid'       => $uuid,
		'username'   => $username,
		'password'   => password_hash('berbek', PASSWORD_DEFAULT),
		'fullname'   => $fullname,
		'departemen' => $departemen_uuid,
		'hak_akses'  => $hak_akses,
		'type'       => $this->input->post('type')
	];

	$this->db->insert('users', $data);

	return $this->db->affected_rows() > 0;
}

public function update($uuid)
{
	$fullname = $this->input->post('fullname');
	$departemen = $this->input->post('departemen');
	$type = $this->input->post('type');
	$username = $this->input->post('username');
	$dept = $this->db->where('uuid', $departemen)->get('departemen')->row();

	$hak_akses = $dept ? $dept->departemen : null;

	$data = array(
		'fullname' => $fullname,
		'departemen' => $departemen,
		'username' => $username,
		'hak_akses' => $hak_akses,
		'type' => $type
	);

	$this->db->update('users', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

    // public function update_username($uuid)
    // {
    // 	$username = $this->input->post('username');

    // 	$data = array(
    // 		'username' => $username
    // 	);

    // 	$this->db->update('users', $data, array('uuid' => $uuid ));
    // 	return ($this->db->affected_rows() > 0) ? true : false;
    // }

    // public function update_nik($uuid)
    // {
    // 	$nik = $this->input->post('nik');

    // 	$data = array(
    // 		'nik' => $nik
    // 	);

    // 	$this->db->update('users', $data, array('uuid' => $uuid ));
    // 	return ($this->db->affected_rows() > 0) ? true : false;
    // }

public function update_password($uuid)
{
	$password = $this->input->post('new-password');


	$data = array(
		'password' => password_hash($password, PASSWORD_DEFAULT)
	);

	$this->db->update('users', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}

public function reset($uuid)
{
	$password = 'berbek';

	$data = array(
		'password' => password_hash($password, PASSWORD_DEFAULT)
	);

	$this->db->update('users', $data, array('uuid' => $uuid ));
	return ($this->db->affected_rows() > 0) ? true : false;
}


}