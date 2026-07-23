<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migrates extends CI_Controller { 

    public function index() 
    { 
        $this->load->library('migration');

        if ($this->migration->latest()) {
            echo 'Migration completed successfully!';
        } else {
            echo $this->migration->error_string();
        }
    }

}