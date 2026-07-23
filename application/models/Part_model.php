<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Part_model extends CI_Model 
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
				'field' => 'area',
				'label' => 'Nama Area',
				'rules' => 'required'
			],
			[
				'field' => 'mesin',
				'label' => 'Nama Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'part',
				'label' => 'Nama Part',
				'rules' => 'required'
			],
			[
				'field' => 'lifetime',
				'label' => 'Lifetime',
				'rules' => 'required'
			],
			[
				'field' => 'harga',
				'label' => 'Harga',
				'rules' => 'required'
			],
			[
				'field' => 'kondisi',
				'label' => 'Kondisi',
				'rules' => 'required'
			]
		];
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('part')->result();

	}

	public function get_mesin_name($uuid)
	{
		return $this->db->get_where('mesin', array('uuid' => $uuid))->row();
	}

	public function get_part_name($uuid)
	{
		return $this->db->get_where('part', array('uuid' => $uuid))->row();
	}

	public function get_part($mesin_uuid)
	{
		return $this->db->get_where('part', array('mesin_uuid' => $mesin_uuid))->result();
	}

	public function get_by_uuid($uuid)
	{	

		$this->db->select('a.*, b.nama_area');
		$this->db->from('part a');
		$this->db->join('mesin b', 'b.uuid=a.mesin_uuid','left');
		$this->db->where('a.uuid', $uuid);
		$data = $this->db->get()->row();
		return $data;
	}

	public function get_by_mesin($mesin_uuid) 
	{
		return $this->db->get_where('part', array('mesin_uuid' => $mesin_uuid ))->result();
	}

	public function insert()
	{
		$uuid 			= Uuid::uuid4()->toString();
		$mesin 			= $this->input->post('mesin');
		$mesin_name 	= $this->input->post('mesin_name');
		$part_name 		= $this->input->post('part');
		$lifetime 		= $this->input->post('lifetime');
		$harga 			= $this->input->post('harga');
		// $kondisi 		= $this->input->post('kondisi');

		$data = array(
			'uuid' 			=> $uuid,
			'mesin_uuid' 	=> $mesin,
			'nama_mesin' 	=> $mesin_name,
			'nama_part' 	=> $part_name,
			'lifetime'	 	=> $lifetime,
			'harga' 		=> $harga
		);

		$this->db->insert('part', $data);
		return ($this->db->affected_rows() > 0) ? true : false;

	}

	

	
	public function update($uuid)
	{
		
		$part 			= $this->input->post('part');
		$lifetime 		= $this->input->post('lifetime');
		$harga 			= $this->input->post('harga');
		
		$data = array(
			'nama_part' 	=> $part,
			
			'lifetime' 		=> $lifetime,
			'harga' 		=> $harga,
			'modified_at'	=> date('Y-m-d h:i:s')
		);

		// $data1 = array(
		// 	'uuid' 			=> $uuid_history,
		// 	'mesin_uuid' 	=> $mesin_uuid,
		// 	'nama_mesin' 	=> $mesin,
		// 	'part_uuid'		=> $part_uuid,
		// 	'nama_part' 	=> $part,
		// 	'lifetime'	 	=> $lifetime,
		// 	'harga' 		=> $harga,
		// 	'kondisi'		=> $kondisi
		// );	

		$this->db->update('part', $data, array('uuid' => $uuid)); // query update
		// $this->db->insert('history_lifetime', $data1);
		return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
	}

	public function get_all_history($uuid)
	{
		$this->db->select('a.*, a.uuid as monitor_uuid, b.*');
		$this->db->from('part a');
		$this->db->join('history_lifetime b', 'a.uuid = b.part_uuid', 'left');
		$this->db->where('a.uuid', $uuid);
		$this->db->order_by('b.created_at', 'DESC');

		return $this->db->get()->result();
	}

	public function insert_part_history($id)
	{
		$data = $this->db->get_where('part', array('id' => $id))->row();
		// print_r($data);
		$kondisi = $this->input->post('kondisi');

		$history = array(
			'part_uuid' 	=> $data->uuid,
			'nama_part' 	=> $data->nama_part,
			'lifetime' 		=> $data->lifetime,
			'harga' 		=> $data->harga,
			'kondisi' 		=> $kondisi
		);

		$this->db->insert('history_lifetime', $history);
		return ($this->db->affected_rows() > 0) ? true : false;

	}

	public function update_part_history($uuid) //edit part insert ke history
	{
		$data = $this->db->get_where('part', array('uuid' => $uuid))->row();
		$lifetime = $this->input->post('lifetime');
		$kondisi = $this->input->post('kondisi');
		$harga = $this->input->post('harga');

		$history = array(
			'part_uuid' 	=> $data->uuid,
			'nama_part' 	=> $data->nama_part,
			'lifetime' 		=> $lifetime,
			'harga' 		=> $harga,
			'kondisi' 		=> $kondisi
		);

		$this->db->insert('history_lifetime', $history);
		return ($this->db->affected_rows() > 0) ? true : false;

	}

	public function get_history_by_part($uuid)
	{
		return $this->db->get_where('history_lifetime', array('part_uuid' => $uuid))->result();
	}


	var $column_order = array(null, 'nama_mesin', 'nama_part', 'lifetime', 'harga'); //field yang ada di table user
    var $column_search = array('id', 'nama_mesin', 'nama_part', 'lifetime', 'harga'); //field yang diizin untuk pencarian 
    var $order = array('created_at' => 'asc'); // default order 

	private function _get_datatables_query()
    {

		$this->db->select('p.*');
		$this->db->from('part p');
		$this->db->order_by('p.created_at', 'DESC');


        $i = 0;
 
        foreach ($this->column_search as $item){
            if($_POST['search']['value']) {
                     
                if($i===0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i){
                    $this->db->group_end(); 
                }
            }

            $i++;

        }
         
        if(isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
                $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
{
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    $result = $query->result();
    foreach ($result as $row) {
        $row->actions = '
            <a href="' . base_url('part/edit/'.$row->uuid) . '" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
            <a href="' . base_url('part/history/'.$row->uuid) . '" class="btn btn-md btn-success shadow-sm"><i class="fa fa-book fa-sm text-white mr-2"></i> History</a>
        ';
    }

    return $result;
}


    function count_filtered()
    {
            $this->_get_datatables_query();
            $query = $this->db->get();
            return $query->num_rows();
    }

    public function count_all()
    {
            $this->db->from('part');
            return $this->db->count_all_results();
    }

	// public function insertinsert() //insert ke part dan history
	// {
	// 	$uuid 			= Uuid::uuid4()->toString();
	// 	$mesin 			= $this->input->post('mesin');
	// 	$mesin_name 	= $this->input->post('mesin_name');
	// 	$part_name 		= $this->input->post('part');
	// 	$lifetime 		= $this->input->post('lifetime');
	// 	$harga 			= $this->input->post('harga');
	// 	$uuid_part 		= Uuid::uuid4()->toString();
	// 	$kondisi 		= $this->input->post('kondisi');

	// 	$data = array(
	// 		'uuid' 			=> $uuid_part,
	// 		'mesin_uuid' 	=> $mesin,
	// 		'nama_mesin' 	=> $mesin_name,
	// 		'nama_part' 	=> $part_name,
	// 		'lifetime'	 	=> $lifetime,
	// 		'harga' 		=> $harga
	// 	);
	// 	$data1 = array(
	// 		'uuid' 			=> $uuid,
	// 		'part_uuid'		=> $uuid_part,
	// 		'mesin_uuid' 	=> $mesin,
	// 		'nama_mesin' 	=> $mesin_name,
	// 		'nama_part' 	=> $part_name,
	// 		'lifetime'	 	=> $lifetime,
	// 		'harga' 		=> $harga,
	// 		'kondisi'		=> $kondisi
	// 	);	

	// 	$this->db->insert('part', $data);
	// 	$this->db->insert('history_lifetime', $data1);
	// 	return ($this->db->affected_rows() > 0) ? true : false;
	// }



}