<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Tracking_model extends CI_Model 
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
				'field' => 'issue',
				'label' => 'issue',
				'rules' => 'required'
			],
			[
				'field' => 'pic',
				'label' => 'pic',
				'rules' => 'required'
			]	
		];
	}

	public function get_all()
	{
		$this->db->select('a.*, (SELECT b.deadline FROM t_after b WHERE b.t_issue_uuid = a.uuid ORDER BY b.deadline ASC LIMIT 1 ) as deadline', false);

    // Add the status column from t_hasil
		// $this->db->select('(SELECT c.status FROM t_hasil c WHERE c.t_after_uuid = (SELECT d.uuid FROM t_after d WHERE d.t_issue_uuid = a.uuid ORDER BY d.created_at DESC LIMIT 1) LIMIT 1) as kstatus', false);

		$this->db->from('t_issue a');
		$this->db->order_by('a.created_at', 'DESC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			// if ($val->kstatus == 1) {
			// 	$val->status = '<span class="text-success">CLSD</span>';
			// } elseif ($val->kstatus == 2) {
			// 	$val->status = '<span class="text-warning">INPG</span>';
			// } else {
			// 	$val->status = '<span class="text-light">Unknown</span>';
			// }
			$now = date('d M Y');
			$skrg = strtotime($now);

			if ($val->deadline !== null) {
            $dl = strtotime($val->deadline); //deadline
            $val->dead = date('d M Y', $dl); 
            $val->selisih = ($dl - $skrg) / (60*60*24);

            if ($val->selisih < 0) {
            	$val->pencapaian = '<span class="text-danger font-weight-bold">FAIL</span>';
            } else if ($val->selisih > 6) {
            	$val->pencapaian = '<span class="text-info">On Progress</span>';
            } else {
            	$val->pencapaian = '<span class="text-warning">Urgent</span>';
            }
        } else {
        	$val->pencapaian = '<span class="text-secondary">Belum Ada CAP</span>';
        }

    }

    return $data;
}


public function get_by_uuid($uuid)
{
	$data = $this->db->get_where('t_issue', array('uuid' => $uuid ))->row();
	return $data;
}

public function get_detail_by_issue_uuid($uuid)
{
	$this->db->order_by('created_at','ASC');
	$data = $this->db->get_where('t_detail', array('t_issue_uuid' => $uuid))->result();

	return $data;
}

public function get_before_by_issue_uuid($uuid)
{
	$this->db->order_by('created_at','ASC');
	$data = $this->db->get_where('t_before', array('t_issue_uuid' => $uuid))->result();

	return $data;
}

public function get_after_by_issue_uuid($uuid)
{
	$this->db->order_by('created_at','ASC');
	$data = $this->db->get_where('t_after', array('t_issue_uuid' => $uuid))->result();

	foreach ($data as $val) 
	{
		$now = date('d M Y');
		$skrg = strtotime($now);

		$val->tanggal = date("d M Y", strtotime($val->created_at));
    		$dl = strtotime($val->deadline); //deadline
    		$val->dead = date('d M Y', $dl); 
    		$val->selisih = ($dl - $skrg) / (60*60*24);

    		if ($val->selisih < 0) {
    			$val->pencapaian = '<span class="text-danger font-weight-bold">FAIL</span>';
    		} else if ($val->selisih > 6) {
    			$val->pencapaian = '<span class="text-info">On Progress</span>';
    		} else $val->pencapaian = '<span class="text-warning">Urgent</span>';


    	}

    	return $data;
    }

    public function get_hasil_by_after_uuid($uuid)
    {
    	$this->db->order_by('created_at','ASC');
    	$data = $this->db->get_where('t_hasil', array('t_after_uuid' => $uuid))->result();

    	foreach ($data as $val) {
    		if ($val->status == 1) {
    			$val->status = '<span class="text-success font-weight-bold">CLSD</span>';
    		} else if ($val->status == 2) {
    			$val->status = '<span class="text-danger font-weight-bold">Belum CLSD</span>';
    		}
    	}

    	return $data;
    }
    

    public function insert()
    {
    	$uuid 			= Uuid::uuid4()->toString();
    	$issue 			= $this->input->post('issue');
    	$pic			= $this->input->post('pic');


    	$data = array(
    		'uuid' 			=> $uuid,
    		'user_uuid'		=> $this->auth_model->current_user()->uuid,
    		'username' 		=> $this->auth_model->current_user()->username,
    		'issue' 		=> $issue,
    		'pic' 			=> $pic

    	);	
    	$this->db->insert('t_issue', $data);
    	return ($this->db->affected_rows() > 0) ? true : false;

    }

    public function update($uuid)
    {
    	$issue 			= $this->input->post('issue');
    	$pic			= $this->input->post('pic');
    	$data = array( 

    		'issue' 	=> $issue,
    		'pic' 		=> $pic,
    		'modified_at' => date('Y-m-d h:i:s')
    	);	

		$this->db->update('t_issue', $data, array('uuid' => $uuid)); // query update
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function hasil($uuid, $dok_hasil)
	{
		$data 		= $this->db->get_where('t_after', array('uuid' => $uuid))->row();
		$uuid 		= Uuid::uuid4()->toString();
		$evaluasi 	= $this->input->post('evaluasi');
		$status 	= $this->input->post('status');
		
		$data 		= array(
			'uuid' 			=> $uuid,
			'user_uuid'		=> $this->auth_model->current_user()->uuid,
			'username' 		=> $this->auth_model->current_user()->username,
			'evaluasi' 		=> $evaluasi,
			'status' 		=> $status,
			't_after_uuid' 	=> $data->uuid,
			'dok_hasil' 	=> $dok_hasil
		);
		$this->db->insert('t_hasil', $data);		
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_t_issue_uuid_by_after_uuid($after_uuid)
	{
		$data = $this->db->select('t_issue_uuid')->get_where('t_after', array('uuid' => $after_uuid))->row();
		return $data;
	}

	public function get_issue($uuid)
	{
		$this->db->select('t_issue.issue');
		$this->db->from('t_after');
		$this->db->join('t_issue', 't_after.t_issue_uuid = t_issue.uuid');
		$this->db->where('t_after.uuid', $uuid);
		$data = $this->db->get()->row();
		return $data;
	}

	public function get_last_hasil($uuid)
	{
		$this->db->select('a.*');
		$this->db->select('(SELECT b.evaluasi FROM t_hasil b WHERE b.t_after_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as f_hasil', false);
		$this->db->select('(SELECT b.created_at FROM t_hasil b WHERE b.t_after_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as f_tanggal', false);
		$this->db->select('(SELECT b.dok_hasil FROM t_hasil b WHERE b.t_after_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as f_dok_hasil', false);
		$this->db->select('(SELECT b.status FROM t_hasil b WHERE b.t_after_uuid = a.uuid ORDER BY b.created_at DESC LIMIT 1 ) as f_status', false);
		$this->db->from('t_after a');
    $this->db->where('t_issue_uuid', $uuid); // Filter based on t_issue_uuid
    $this->db->order_by('a.created_at', 'ASC');
    
    $data = $this->db->get()->result();


    foreach ($data as $val) {

    	$val->u_tanggal = !empty($val->f_tanggal) ? date("d M Y", strtotime($val->f_tanggal)) : '';
    	$val->u_deadline = date("d M Y", strtotime($val->deadline));

    	if ($val->f_status == 1) {
    		$val->status = '<span class="text-success font-weight-bold">CLSD</span>';
    	} else if ($val->f_status == 2) {
    		$val->status = '<span class="text-danger font-weight-bold">INPG</span>';
    	} else {
    		$val->status = '<span class="text-dark">Belum Ada Evaluasi</span>';
    	}
    }
    return $data;
}

public function insertdetail($uuid, $dokumentasi)
{
	$data   = $this->db->get_where('t_issue', array('uuid' => $uuid))->row();
	$uuid 		= Uuid::uuid4()->toString();
	$detail 	= $this->input->post('fdetail');



	$data = array(
		'uuid' 			=> $uuid,
		'user_uuid'     => $this->auth_model->current_user()->uuid,
		'username'      => $this->auth_model->current_user()->username,
		't_issue_uuid' 	=> $data->uuid,
		'detail' 		=> $detail,
		'dokumentasi' 	=> $dokumentasi

	);

	$this->db->insert('t_detail', $data);
	return ($this->db->affected_rows() > 0) ? true : false;

}

public function insertbefore($uuid, $dok_before)
{
	$data   = $this->db->get_where('t_issue', array('uuid' => $uuid))->row();
	$uuid 		= Uuid::uuid4()->toString();
	$gap 	= $this->input->post('fgap');


	$data = array(
		'uuid' 			=> $uuid,
		'user_uuid'     => $this->auth_model->current_user()->uuid,
		'username'      => $this->auth_model->current_user()->username,
		't_issue_uuid' 	=> $data->uuid,
		'gap' 			=> $gap,
		'dok_before' 	=> $dok_before

	);

	$this->db->insert('t_before', $data);
	return ($this->db->affected_rows() > 0) ? true : false;

}

public function insertafter($uuid, $dok_after)
{
	$data   = $this->db->get_where('t_issue', array('uuid' => $uuid))->row();
	$uuid 		= Uuid::uuid4()->toString();
	$cap 	= $this->input->post('fcap');
	$deadline 	= $this->input->post('fdeadline');



	$data = array(
		'uuid' 			=> $uuid,
		'user_uuid'     => $this->auth_model->current_user()->uuid,
		'username'      => $this->auth_model->current_user()->username,
		't_issue_uuid' 	=> $data->uuid,
		'cap' 			=> $cap,
		'deadline' 		=> $deadline,
		'dok_after' 	=> $dok_after

	);

	$this->db->insert('t_after', $data);
	return ($this->db->affected_rows() > 0) ? true : false;

}

public function delete_detail($uuid)
{
	$this->db->where('uuid', $uuid);
	$this->db->delete('t_detail');
}

public function delete_before($uuid)
{
	$this->db->where('uuid', $uuid);
	$this->db->delete('t_before');
}

public function delete_after($uuid)
{
	$this->db->where('uuid', $uuid);
	$this->db->delete('t_after');
}

public function get_after($uuid)
{
	$this->db->select('t_after.*, t_issue.issue, t_issue.pic');
	$this->db->from('t_after');
	$this->db->join('t_issue', 't_issue.uuid = t_after.t_issue_uuid', 'left');
	$this->db->where('t_after.uuid', $uuid);
	$data = $this->db->get()->row();
	$data->f_deadline = date("d M Y", strtotime($data->deadline));
	return $data;
}

public function get_after_by_uuid($uuid)
{
	$data = $this->db->get_where('t_after', array('uuid' => $uuid ))->row();
	return $data;
}

public function get_issue_data($uuid) 
{
	$query = $this->db->get_where('t_issue', array('uuid' => $uuid));
	return $query->row();
}
public function get_detail_data($uuid) 
{
	$query = $this->db->get_where('t_detail', array('t_issue_uuid' => $uuid));
	return $query->result();
}

public function get_coba()
{
	$this->db->select('a.*, 
		(SELECT b.deadline FROM t_after b WHERE b.t_issue_uuid = a.uuid ORDER BY b.deadline ASC LIMIT 1) as deadline,
		COALESCE((SELECT c.status FROM t_after d 
		LEFT JOIN t_hasil c ON d.uuid = c.t_after_uuid
		WHERE d.t_issue_uuid = a.uuid ORDER BY d.created_at DESC LIMIT 1), 2) as fstatus', false);

	$this->db->from('t_issue a');
	$this->db->order_by('a.created_at', 'DESC');
	$data = $this->db->get()->result();

	foreach ($data as $val) {
		$now = date('d M Y');
		$skrg = strtotime($now);

		if ($val->deadline !== null) {
        $dl = strtotime($val->deadline); //deadline
        $val->dead = date('d M Y', $dl); 
        $val->selisih = ($dl - $skrg) / (60*60*24);

        if ($val->selisih < 0) {
        	$val->pencapaian = '<span class="text-danger font-weight-bold">FAIL</span>';
        } else if ($val->selisih > 6) {
        	$val->pencapaian = '<span class="text-info">On Progress</span>';
        } else {
        	$val->pencapaian = '<span class="text-warning">Urgent</span>';
        }
    } else {
    	$val->pencapaian = '<span class="text-secondary">Belum Ada CAP</span>';
    }
    if ($val->fstatus == 1) {
    	$val->status = '<span class="text-success font-weight-bold">CLSD</span>';
    } else {
    	$val->status = '<span class="text-warning font-weight-bold">INPG</span>';
    }
}

return $data;

}








	// public function coba()
	// {
	// 	$this->db->select('a.*');
	// 	$this->db->select('(SELECT b.deadline FROM t_after b WHERE b.t_issue_uuid = a.uuid ORDER BY b.deadline ASC LIMIT 1 ) as f_deadline', false);
	// 	$this->db->select('(SELECT c.status FROM t_hasil c WHERE c.t_after_uuid = (SELECT b.uuid FROM t_after b WHERE b.t_issue_uuid = a.uuid ORDER BY b.deadline ASC LIMIT 1) ORDER BY c.created_at DESC LIMIT 1 ) as f_status', false);
	// 	$this->db->from('t_issue a');
	// 	$this->db->order_by('a.created_at', 'DESC'); // Assuming created_at belongs to t_issue table
	// 	$data =  $this->db->get()->result();
	// 	return $data;

	// }



}