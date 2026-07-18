<?php 
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Partrequest_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
	}

	public function rules1()
	{
		return [
			
			[
				'field' => 'part',
				'label' => 'Part',
				'rules' => 'required'
			]
		];
	}

	private function get_pengajuan_by_mode($mode = 'active')
	{
		$this->db->select("
			p.*,
			ls.last_status,
			ac.acc_count
			", false);

		$this->db->from('pengajuan p');

    // JOIN status terakhir
		$this->db->join("
			(
			SELECT sp1.pengajuan_uuid, sp1.status as last_status
			FROM status_pengajuan sp1
			INNER JOIN (
			SELECT pengajuan_uuid, MAX(created_at) as max_created
			FROM status_pengajuan
			GROUP BY pengajuan_uuid
			) sp2
			ON sp1.pengajuan_uuid = sp2.pengajuan_uuid
			AND sp1.created_at = sp2.max_created
			) ls
			", 'ls.pengajuan_uuid = p.uuid', 'left');

    // JOIN jumlah ACC
		$this->db->join("
			(
			SELECT pengajuan_uuid, COUNT(*) as acc_count
			FROM status_pengajuan
			WHERE status = 5
			GROUP BY pengajuan_uuid
			) ac
			", 'ac.pengajuan_uuid = p.uuid', 'left');

		$this->db->order_by('p.created_at', 'DESC');

		$rows = $this->db->get()->result();

		$result = [];

		foreach ($rows as $row) {

			$row->tgl = date('d M Y', strtotime($row->created_at));
			$row->jns = $this->map_jenis_pengajuan($row->jenis);

			$approval = $this->get_approval_status($row->uuid);

			$row->approval = $approval;

			$row->status_data = $this->map_status_pengajuan(
				$row->last_status,
				$approval
			);

			$is_history = in_array($row->status_data['label'], ['Closed', 'Ditolak']);

			if (
				($mode === 'active' && !$is_history) ||
				($mode === 'history' && $is_history)
			) {
				$result[] = $row;
			}
		}

		return $result;
	}

	public function get_pengajuan()
	{
		return $this->get_pengajuan_by_mode('active');
	}

	public function get_history()
	{
		return $this->get_pengajuan_by_mode('history');
	}

	public function insert_pengajuan($foto)
	{
		$uuid = Uuid::uuid4()->toString();
		$part = $this->input->post('part');
		$jenis = $this->input->post('jenis');
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'uuid' => $uuid,
			'user_uuid' => $this->auth_model->current_user()->uuid,	
			'part' => $part,
			'keterangan' => $keterangan,
			'jenis' => $jenis,
			'foto' => $foto
		);

		$this->db->insert('pengajuan', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_dokumen($dokumen, $uuid)
	{
		$data = $this->db->get_where('pengajuan', array('uuid' => $uuid))->row();
		$uid = Uuid::uuid4()->toString();
		$keterangan = $this->input->post('keterangan');

		$data = array(
			'uuid' => $uid,
			'user_uuid' => $this->auth_model->current_user()->uuid,
			'pengajuan_uuid' => $data->uuid,
			'keterangan' => $keterangan,
			'foto' => $dokumen
		);

		$this->db->insert('foto_pengajuan', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update_mengetahui($uuid)
	{

		$data = array(
			'mengetahui' => $this->auth_model->current_user()->username
		);

		$this->db->update('pengajuan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function insert_status_part($pengajuan_uuid)
	{
    // Ambil data pengajuan
		$pengajuan = $this->db
		->get_where('pengajuan', ['uuid' => $pengajuan_uuid])
		->row();

		if (!$pengajuan) {
			return false;
		}

    // Cek apakah departemen ini sudah pernah ACC
		$this->db->select('sp.uuid');
		$this->db->from('status_pengajuan sp');
		$this->db->join('users u', 'u.uuid = sp.user_uuid');
		$this->db->where('sp.pengajuan_uuid', $pengajuan->uuid);
		$this->db->where('sp.status', 5);
		$this->db->where('u.hak_akses', current_departemen());

		$cek = $this->db->get()->row();

		if ($cek) {
			return false;
		}

    // Insert status baru
		$data = array(
			'uuid'            => Uuid::uuid4()->toString(),
			'user_uuid'       => $this->auth_model->current_user()->uuid,
			'status'          => $this->input->post('status'),
			'pengajuan_uuid'  => $pengajuan->uuid,
			'keterangan'      => $this->input->post('keterangan')
		);

		$this->db->insert('status_pengajuan', $data);

		return ($this->db->affected_rows() > 0);
	}

	public function get_pengajuan_uuid($uuid)
	{
		$this->db->select('p.*, u.username');
		$this->db->select('(SELECT b.status FROM status_pengajuan b WHERE b.pengajuan_uuid = p.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
		$this->db->from('pengajuan p');
		$this->db->join('users u', 'u.uuid = p.user_uuid', 'left');
		$this->db->where('p.uuid', $uuid);
		$data = $this->db->get()->row();
		if ($data->jenis == 1) {
			$data->jns = 'Stock WareHouse';
		} elseif ($data->jenis == 2) {
			$data->jns = 'Repair Engineering';
		}

		return $data;
	}

	public function get_status_pengajuan_data($uuid)
	{
		$this->db->select('sp.*, u.username, u.hak_akses');
		$this->db->from('status_pengajuan sp');
		$this->db->join('users u', 'u.uuid = sp.user_uuid', 'left');
		$this->db->where('sp.pengajuan_uuid', $uuid);
		$this->db->order_by('sp.created_at', 'ASC');
		$data = $this->db->get()->result();
		foreach ($data as $val) {

			if ($val->status == 1) {
				$val->status = 'diSetujui';
			} elseif ($val->status == 2) {
				$val->status = 'Release Komdif';
			} elseif ($val->status == 3) {
				$val->status = 'Proses Pengiriman';
			} elseif ($val->status == 4) {
				$val->status = 'Proses Pembuatan';
			} elseif ($val->status == 5) {
				$val->status = 'ACC';
			} elseif ($val->status == 6) {
				$val->status = 'diTolak';
			}
			$val->tanggal = date('d M Y',strtotime($val->created_at));
		}
		return $data;
	}
	public function get_foto_pengajuan($uuid)
	{
		$this->db->where('pengajuan_uuid', $uuid);
		$this->db->order_by('created_at', 'ASC');
		$data = $this->db->get('foto_pengajuan')->result();
		return $data;
	}

	public function get_total_pengajuan()
	{
		$this->db->select('p.*');
		$this->db->select('(SELECT b.status FROM status_pengajuan b WHERE b.pengajuan_uuid = p.uuid ORDER BY b.created_at DESC LIMIT 1 ) as status', false);
		$this->db->from('pengajuan p');
		$this->db->order_by('p.created_at', 'DESC');
		$data = $this->db->get()->result();

		$filtered_data = array_filter($data, function($item) {
			return $item->status != 5 && $item->status != 6;
		});
		return count($filtered_data);

	}

	public function get_pengajuan_count()
	{
		$this->db->select('p.*');

		$this->db->select("
			(
			SELECT b.status
			FROM status_pengajuan b
			WHERE b.pengajuan_uuid = p.uuid
			ORDER BY b.created_at DESC
			LIMIT 1
			) as status
			", false);

		$this->db->select("
			(
			SELECT COUNT(*)
			FROM status_pengajuan c
			WHERE c.pengajuan_uuid = p.uuid
			AND c.status = 5
			) as total_status_5
			", false);

		$this->db->from('pengajuan p');

		$data = $this->db->get()->result();

		$new = 0;
		$repair = 0;

		foreach ($data as $row) {

        // CLOSED
			if ($row->status == 6) {
				continue;
			}

        // REJECT 3x
			if ($row->status == 5 && $row->total_status_5 >= 3) {
				continue;
			}

			if ((int)$row->jenis === 1) {
				$new++;
			}

			if ((int)$row->jenis === 2) {
				$repair++;
			}
		}

		return [
			'new'    => $new,
			'repair' => $repair
		];
	}

	private function map_status_pengajuan($last_status, $approval = [])
	{
		if (is_null($last_status)) {
			return [
				'label' => 'PBBJ',
				'badge' => 'secondary'
			];
		}

		switch ((int)$last_status) {

			case 1:
			return [
				'label' => 'Disetujui',
				'badge' => 'info'
			];

			case 2:
			return [
				'label' => 'Release Komdif',
				'badge' => 'primary'
			];

			case 3:
			return [
				'label' => 'Proses Pengiriman',
				'badge' => 'warning'
			];

			case 4:
			return [
				'label' => 'Proses Pembuatan',
				'badge' => 'warning'
			];

			case 5:

			$total_acc = count(array_filter($approval));

			if ($total_acc >= 3) {
				return [
					'label' => 'Closed',
					'badge' => 'success'
				];
			}

			return [
				'label' => 'Approval ' . $total_acc . '/3',
				'badge' => 'info'
			];

			case 6:
			return [
				'label' => 'Ditolak',
				'badge' => 'danger'
			];

			default:
			return [
				'label' => '-',
				'badge' => 'secondary'
			];
		}
	}

	private function map_jenis_pengajuan($jenis)
	{
		switch ((int)$jenis) {
			case 1: return 'New Part';
			case 2: return 'Repair Engineering';
			default: return '-';
		}
	}

	public function get_approval_status($pengajuan_uuid)
	{
		$approval = [
			'Produksi'   => false,
			'Engineering'=> false,
			'Warehouse'  => false
		];

		$this->db->select('u.hak_akses');
		$this->db->from('status_pengajuan sp');
		$this->db->join('users u', 'u.uuid = sp.user_uuid');
		$this->db->where('sp.pengajuan_uuid', $pengajuan_uuid);
		$this->db->where('sp.status', 5);

		$data = $this->db->get()->result();

		foreach ($data as $row) {
			if (isset($approval[$row->hak_akses])) {
				$approval[$row->hak_akses] = true;
			}
		}

		return $approval;
	}
}