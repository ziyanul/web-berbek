<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Zanasi_model extends CI_Model
{
	var $table = 'zanasi z';
	var $column_order = array(
		null,
		'z.created_at',
		'z.rutin',
		'v.varian',
		'z.kode',
		'z.permintaan',
		'z.total_print',
		null,
		null
	);
	var $column_search = array(
		'z.kode',
		'v.varian',
		'z.permintaan',
		'z.total_print'
	);
	var $order = array('z.id' => 'DESC');


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
	}

	private function _get_datatables_query()
    {
        $this->db->select("
            z.id,
            z.uuid,
            z.rutin,
            z.varian,
            z.kode,
            z.exp,
            z.permintaan,
            z.total_print,
            z.catatan,
            z.created_at,
            v.varian AS nama_varian,
            v.keterangan,

            DATE_FORMAT(z.created_at, '%d %b %Y') AS tanggal,
            (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) AS sisa,

            CASE
                WHEN z.rutin = 1 THEN '<span class=\"font-weight-bold\">Rutin</span>'
                WHEN z.rutin = 2 THEN '<span class=\"font-weight-bold\">Tambahan</span>'
                ELSE '-'
            END AS rutin_label,

            CASE
                WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) = 0 THEN '<span class=\"font-weight-bold text-success\">CLOSED</span>'
                WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) > 0 THEN '<span class=\"font-weight-bold text-warning\">OPEN</span>'
                WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) < 0 AND z.catatan IS NOT NULL AND z.catatan != '' THEN '<span class=\"font-weight-bold text-success\">CLOSED</span>'
                ELSE '<span class=\"font-weight-bold text-danger\">OVER</span>'
            END AS status
        ", false);

        $this->db->from($this->table);
        $this->db->join('varian v', 'v.uuid = z.varian', 'left');

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->join('varian v', 'v.uuid = z.varian', 'left');
        return $this->db->count_all_results();
    }

	public function rules()
	{
		return [
			[
				'field' => 'rutin',
				'label' => 'Rutin/Tambahan',
				'rules' => 'required'
			],
			[
				'field' => 'varian',
				'label' => 'Varian',
				'rules' => 'required'
			],
			[
				'field' => 'kode',
				'label' => 'Kode Produksi',
				'rules' => 'required'
			],
			[
				'field' => 'exp',
				'label' => 'Kode Exp',
				'rules' => 'required'
			],
			[
				'field' => 'permintaan',
				'label' => 'Jumlah Permintaan',
				'rules' => 'required|numeric|greater_than[0]'
			]
		];
	}

	public function get_all()
	{
		$this->db->select("
			z.id,
			z.uuid,
			z.rutin,
			z.varian,
			z.kode,
			z.exp,
			z.permintaan,
			z.total_print,
			z.catatan,
			z.created_at,
			v.varian AS nama_varian,
			v.keterangan,

			DATE_FORMAT(z.created_at, '%d %b %Y') AS tanggal,
			(IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) AS sisa,

			CASE
			WHEN z.rutin = 1 THEN '<span class=\"font-weight-bold\">Rutin</span>'
			WHEN z.rutin = 2 THEN '<span class=\"font-weight-bold\">Tambahan</span>'
			ELSE '-'
			END AS rutin_label,

			CASE
			WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) = 0 THEN '<span class=\"font-weight-bold text-success\">CLOSED</span>'
			WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) > 0 THEN '<span class=\"font-weight-bold text-warning\">OPEN</span>'
			WHEN (IFNULL(z.permintaan,0) - IFNULL(z.total_print,0)) < 0 AND z.catatan IS NOT NULL AND z.catatan != '' THEN '<span class=\"font-weight-bold text-success\">CLOSED</span>'
			ELSE '<span class=\"font-weight-bold text-danger\">OVER</span>'
			END AS status
			", false);

		$this->db->from('zanasi z');
		$this->db->join('varian v', 'v.uuid = z.varian', 'left');
		$this->db->order_by('z.id', 'DESC');

		return $this->db->get()->result();
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$data = array(
			'uuid' => $uuid,
			'user_uuid' => $this->Auth_model->current_user()->uuid,
			'username' => $this->Auth_model->current_user()->username,
			'rutin' => $this->input->post('rutin', true),
			'varian' => $this->input->post('varian', true),
			'kode' => $this->input->post('kode', true),
			'exp' => $this->input->post('exp', true),
			'permintaan' => $this->input->post('permintaan', true),
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('zanasi', $data);
		return ($this->db->error()['code'] == 0);
	}

	public function update($uuid)
	{
		$data = array(
			'rutin' => $this->input->post('rutin', true),
			'varian' => $this->input->post('varian', true),
			'kode' => $this->input->post('kode', true),
			'exp' => $this->input->post('exp', true),
			'permintaan' => $this->input->post('permintaan', true),
			'catatan' => $this->input->post('catatan', true),
			'modified_at' => date('Y-m-d H:i:s')
		);

		$this->db->where('uuid', $uuid);
		$this->db->update('zanasi', $data);

		return ($this->db->error()['code'] == 0);
	}

	public function print($uuid)
	{
		$zanasi = $this->db->get_where('zanasi', array('uuid' => $uuid))->row();

		if (!$zanasi) {
			return false;
		}

		$jumlah_print = (int)$this->input->post('print', true);

		$data = array(
			'uuid' => Uuid::uuid4()->toString(),
			'user_uuid' => $this->Auth_model->current_user()->uuid,
			'username' => $this->Auth_model->current_user()->username,
			'zanasi_uuid' => $zanasi->uuid,
			'print' => $jumlah_print,
			'catatan' => $this->input->post('catatan', true),
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->trans_start();

		$this->db->insert('printing', $data);

		$this->db->set('total_print', 'total_print + '.$jumlah_print, false);
		$this->db->where('uuid', $uuid);
		$this->db->update('zanasi');

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function get_by_uuid($uuid)
	{
		$this->db->select('z.*, v.varian as nama_varian, v.keterangan');
		$this->db->from('zanasi z');
		$this->db->join('varian v', 'v.uuid = z.varian', 'left');
		$this->db->where('z.uuid', $uuid);
		$data = $this->db->get()->row();

		if ($data) {
			if ($data->rutin == 1) {
				$data->rutin_label = '<span class="font-weight-bold">Rutin</span>';
			} else if ($data->rutin == 2) {
				$data->rutin_label = '<span class="font-weight-bold">Tambahan</span>';
			} else {
				$data->rutin_label = '-';
			}
		}

		return $data;
	}

	public function get_print_by_zanasi_uuid($uuid)
	{
		$this->db->order_by('created_at', 'ASC');
		return $this->db->get_where('printing', array('zanasi_uuid' => $uuid))->result();
	}

	public function get_total_print($uuid)
	{
		$this->db->select('COALESCE(SUM(print),0) as totalPrint', false);
		$this->db->where('zanasi_uuid', $uuid);
		return $this->db->get('printing')->row();
	}
}