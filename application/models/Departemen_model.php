<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;

class Departemen_model extends CI_Model
{

	public function rules()
	{
		return [
			[
				'field' => 'departemen',
				'label' => 'Nama Departemen',
				'rules' => 'required'
			]
		];
	}

	public function get_all()
	{	
		$this->db->order_by('created_at','DESC');
		$data = $this->db->get('departemen')->result();

		foreach ($data as $val) {
			$val->ditambahkan = date('d F Y - H:i:s', strtotime($val->created_at));
			$val->diupdate = date('d F Y - H:i:s', strtotime($val->modified_at));
		}

		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('departemen', array('uuid' => $uuid ))->row();
		return $data;
	}

	public function insert()
    {	

    	$uuid = Uuid::uuid4()->toString();

    	$departemen = $this->input->post('departemen');

    	$data = array(
    		'uuid' => $uuid,
    		'departemen' => $departemen
    	);

    	$this->db->insert('departemen', $data);
    	return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function update($uuid)
    {
    	$departemen = $this->input->post('departemen');

    	$data = array(
    		'departemen' => $departemen,
    		'modified_at' => date("Y-m-d H:i:s")
    	);

    	$this->db->update('departemen', $data, array('uuid' => $uuid));
    	return ($this->db->affected_rows() > 0) ? true : false;
    }
}