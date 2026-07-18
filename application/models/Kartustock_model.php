<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Area_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function rules()
	{
		return [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			]
		];
	}
}