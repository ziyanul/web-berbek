<?php
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;
class Drystore_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('Proses_model');
		$this->load->model('Counter_model');
		//$this->dberetort = $this->load->database('e-retort', TRUE);
	}
	public function rules()
	{
		return [
			[
				'field' => 'varian_uuid',
				'label' => 'Varian',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} wajib diisi !',
				]
			],
			[
				'field' => 'jumlah_badpro[]',
				'label' => 'Jumlah Bad Produk',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} wajib diisi !',
					'numeric' => '{field} harus berupa angka !',
				]
			]
		];
	}

    public function rules_type()
	{
		return [
			[
				'field' => 'nama',
				'label' => 'Type',
				'rules' => 'required',
				'errors' => [
					'required' => '{label} wajib diisi !',
				]
			],
			[
				'field' => 'std_waste',
				'label' => 'Standar %',
				'rules' => 'required',
				'errors' => [
					'required' => '{label} wajib diisi !',
				]
			],
            [
				'field' => 'satuan',
				'label' => 'Satuan',
				'rules' => 'required',
				'errors' => [
					'required' => '{label} wajib diisi !',
				]
			]
		];
	}

    public function rules_waste()
	{
		return [
			[
				'field' => 'nama',
				'label' => 'Type',
				'rules' => 'required',
				'errors' => [
					'required' => '{label} wajib diisi !',
				]
			]
		];
	}

    /**
     * Generate UUID
     */


    /* =========================================================
     * DRYSTORE
     * ========================================================= */

    public function get_by_tanggal($tanggal)
    {
        return $this->db
            ->where('tanggal', $tanggal)
            ->get('drystore')
            ->row();
    }

    public function get_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore')
            ->row();
    }

    public function get_all()
    {
        return $this->db
            ->order_by('tanggal', 'DESC')
            ->get('drystore')
            ->result();
    }

    public function get_type()
    {
        return $this->db
            ->order_by('nama', 'DESC')
            ->get('drystore_type')
            ->result();
    }

    public function get_waste()
    {
        return $this->db
            ->order_by('nama', 'DESC')
            ->get('drystore_waste')
            ->result();
    }

    /**
     * Ambil semua Type Packaging aktif
     */
    public function get_all_type()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('drystore_type')
            ->result();
    }

    /**
     * Ambil semua Waste aktif
     */
    public function get_all_waste()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('drystore_waste')
            ->result();
    }

    /**
     * Ambil transaksi berdasarkan drystore
     */
    public function get_transaksi($drystore_uuid)
    {
        return $this->db
            ->select('
                t.*,
                dt.nama AS type_nama,
                dw.nama AS waste_nama
            ')
            ->from('drystore_waste_transaksi t')
            ->join(
                'drystore_type dt',
                'dt.uuid = t.type_uuid',
                'left'
            )
            ->join(
                'drystore_waste dw',
                'dw.uuid = t.waste_uuid',
                'left'
            )
            ->where('t.drystore_uuid', $drystore_uuid)
            ->order_by('dt.nama', 'ASC')
            ->order_by('dw.nama', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Bentuk data transaksi menjadi:
     *
     * $data[type_uuid][waste_uuid] = berat
     */
    public function get_transaksi_matrix($drystore_uuid)
    {
        $rows = $this->get_transaksi($drystore_uuid);

        $matrix = [];

        foreach ($rows as $row) {
            $matrix[$row->type_uuid][$row->waste_uuid] = $row->berat;
        }

        return $matrix;
    }

    /**
     * SIMPAN DRYSTORE HARIAN
     */
    public function insert_harian($tanggal, $post, $user_uuid = null)
    {
        $this->db->trans_begin();

        try {

            // Cek apakah tanggal sudah pernah dibuat
            $drystore = $this->get_by_tanggal($tanggal);

            if ($drystore) {
                throw new Exception(
                    'Data Drystore untuk tanggal tersebut sudah ada.'
                );
            }

            $drystore_uuid = Uuid::uuid4()->toString();

            $header = [
                'uuid'       => $drystore_uuid,
                'tanggal'    => $tanggal,
                'user_uuid'  => $user_uuid,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('drystore',
                $header
            );

            if ($this->db->affected_rows() <= 0) {
                throw new Exception(
                    'Gagal membuat transaksi Drystore.'
                );
            }

            /*
             * POST:
             *
             * waste[type_uuid][waste_uuid]
             */

            if (!empty($post['waste'])) {

                foreach ($post['waste'] as $type_uuid => $wastes) {

                    foreach ($wastes as $waste_uuid => $berat) {

                        $berat = trim($berat);

                        // Kosong dianggap 0
                        if ($berat === '') {
                            $berat = 0;
                        }

                        $berat = (float) $berat;

                        // Tidak perlu menyimpan nilai 0
                        if ($berat <= 0) {
                            continue;
                        }

                        $data = [
                            'uuid'          => Uuid::uuid4()->toString(),
                            'drystore_uuid' => $drystore_uuid,
                            'type_uuid'     => $type_uuid,
                            'waste_uuid'    => $waste_uuid,
                            'berat'         => $berat,
                            'user_uuid'     => $user_uuid,
                            'created_at'    => date('Y-m-d H:i:s')
                        ];

                        $this->db->insert(
                            'drystore_waste_transaksi',
                            $data
                        );

                        if ($this->db->affected_rows() <= 0) {
                            throw new Exception(
                                'Gagal menyimpan detail waste.'
                            );
                        }
                    }
                }
            }

            if ($this->db->trans_status() === false) {
                throw new Exception(
                    'Transaksi database gagal.'
                );
            }

            $this->db->trans_commit();

            return $drystore_uuid;

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * UPDATE DRYSTORE
     */
    public function update_harian(
        $drystore_uuid,
        $post,
        $user_uuid = null
    ) {
        $this->db->trans_begin();

        try {

            $drystore = $this->get_by_uuid($drystore_uuid);

            if (!$drystore) {
                throw new Exception(
                    'Data Drystore tidak ditemukan.'
                );
            }

            /*
             * Cara update paling aman:
             *
             * hapus semua detail lama
             * lalu insert ulang berdasarkan form
             */

            $this->db
                ->where('drystore_uuid', $drystore_uuid)
                ->delete('drystore_waste_transaksi');

            if (!empty($post['waste'])) {

                foreach ($post['waste'] as $type_uuid => $wastes) {

                    foreach ($wastes as $waste_uuid => $berat) {

                        $berat = trim($berat);

                        if ($berat === '') {
                            $berat = 0;
                        }

                        $berat = (float) $berat;

                        if ($berat <= 0) {
                            continue;
                        }

                        $data = [
                            'uuid'          => Uuid::uuid4()->toString(),
                            'drystore_uuid' => $drystore_uuid,
                            'type_uuid'     => $type_uuid,
                            'waste_uuid'    => $waste_uuid,
                            'berat'         => $berat,
                            'user_uuid'     => $user_uuid,
                            'created_at'    => date('Y-m-d H:i:s')
                        ];

                        $this->db->insert(
                            'drystore_waste_transaksi',
                            $data
                        );

                        if ($this->db->affected_rows() <= 0) {
                            throw new Exception(
                                'Gagal memperbarui detail waste.'
                            );
                        }
                    }
                }
            }

            $this->db
                ->where('uuid', $drystore_uuid)
                ->update(
                    'drystore',
                    [
                        'user_uuid' => $user_uuid,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );

            if ($this->db->trans_status() === false) {
                throw new Exception(
                    'Update database gagal.'
                );
            }

            $this->db->trans_commit();

            return true;

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
     * MASTER TYPE
     * ========================================================= */

    public function insert_type()
	{
		$uuid = Uuid::uuid4()->toString();

		$nama = $this->input->post('nama');
		$std_waste = $this->input->post('std_waste');
        $satuan = $this->input->post('satuan');

		$data = array(
			'uuid' => $uuid,
			'nama' => $nama,
            'satuan' => $satuan,
            'aktif' => 1,
			'std_waste' => $std_waste,
			'user_uuid'     => $this->auth_model->current_user()->uuid

		);

		$this->db->insert('drystore_type', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

    public function insert_waste()
	{
		$uuid = Uuid::uuid4()->toString();

		$nama = $this->input->post('nama');

		$data = array(
			'uuid' => $uuid,
			'nama' => $nama,
            'aktif' => 1,
			'user_uuid'     => $this->auth_model->current_user()->uuid
		);

		$this->db->insert('drystore_waste', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

    public function get_type_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore_type')
            ->row();
    }

    public function update_type($uuid)
	{
		$nama = $this->input->post('nama');
		$std_waste = $this->input->post('std_waste');
		$satuan = $this->input->post('satuan');

		$data = array(
			'nama' => $nama,
			'std_waste' => $std_waste,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'updated_at'  => date('Y-m-d h:i:s'),
			'satuan' => $satuan

		);

		$this->db->update('drystore_type', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

    /* =========================================================
     * MASTER WASTE
     * ========================================================= */

    public function update_waste($uuid)
	{
		$nama = $this->input->post('nama');

		$data = array(
			'nama' => $nama,
			'user_uuid'     => $this->auth_model->current_user()->uuid,
			'updated_at'  => date('Y-m-d h:i:s')


		);

		$this->db->update('drystore_waste', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

    public function get_waste_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->get('drystore_waste')
            ->row();
    }
}