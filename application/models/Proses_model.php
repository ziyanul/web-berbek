<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Proses_model extends CI_Model
{
    public function get_uuid($kode)
    {
        return $this->db
            ->select('uuid')
            ->where('kode', strtoupper($kode))
            ->where('deleted_at', NULL)
            ->get('m_proses')
            ->row()
            ->uuid ?? null;
    }
    public function get_by_kode($kode)
    {
        return $this->db
            ->where('kode', strtoupper($kode))
            ->where('deleted_at', NULL)
            ->get('m_proses')
            ->row();
    }
    public function get_all()
    {
        return $this->db
            ->where('deleted_at', NULL)
            ->order_by('urutan')
            ->get('m_proses')
            ->result();
    }
}
