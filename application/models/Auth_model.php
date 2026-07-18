<?php

class Auth_model extends CI_Model
{
	private $_table = 'users';
	const SESSION_KEY = 'user_uuid';

	public function rules()
	{
		return [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required'
			]
		];
	}
	
	public function login($username, $password)
	{
		$this->db->select('users.*, sub_role.subrole, d.departemen as nama_departemen');
        $this->db->from($this->_table);
        $this->db->join('sub_role', 'users.uuid = sub_role.users_uuid','left');
        $this->db->join('departemen d', 'users.departemen = d.uuid','left');
        $this->db->where('users.username', $username);
        $query = $this->db->get();
        $user = $query->row();
		if (!$user) {
			return FALSE;
		}

		if (!password_verify($password, $user->password)) {
			return FALSE;
		}

		$this->session->set_userdata([self::SESSION_KEY => $user->uuid, 'departemen' => $user->nama_departemen, 'username' => $user->username  , 'fullname' => $user->fullname, 'type' => $user->type, 'subrole' => $user->subrole , 'hak_akses' => $user->hak_akses ]);

		return $this->session->has_userdata(self::SESSION_KEY);
	}

	public function current_user()
	{
		if (!$this->session->has_userdata(self::SESSION_KEY)) {
			return null;
		}

		$user_uuid = $this->session->userdata(self::SESSION_KEY);

		$query = $this->db->get_where($this->_table, ['uuid' => $user_uuid]);
		return $query->row();
	}

	public function logout()
	{
		$this->session->unset_userdata(self::SESSION_KEY);
		return !$this->session->has_userdata(self::SESSION_KEY);
	}
}