<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Sortasi_model extends CI_Model
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
                'field' => 'tbatch_uuid',
                'label' => 'Kode Batch',
                'rules' => 'required'
            ],
            [
                'field' => 'jumlah_sortir',
                'label' => 'Jumlah Sortir',
                'rules' => 'required'
            ],
            [
                'field' => 'release_box',
                'label' => 'Jumlah Release',
                'rules' => 'required'
            ],
            [
                'field' => 'mulai',
                'label' => 'Mulai',
                'rules' => 'required'
            ],
            [
                'field' => 'selesai',
                'label' => 'Selesai',
                'rules' => 'required'
            ],
            [
                'field' => 'jenis_sortasi_uuid',
                'label' => 'Jenis Sortasi',
                'rules' => 'required'
            ],
            [
                'field' => 'jml_mp',
                'label' => 'Jumlah MP',
                'rules' => 'required'
            ]
        ];
    }

    public function rules_jenis()
    {
        return [
            [
                'field' => 'jenis',
                'label' => 'Jenis Sortasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{label} wajib diisi !',
                ]
            ],
        ];
    }
    public function get_all()
    {
        $this->db->select('s.*, v.varian, v.keterangan, tb.kode_batch, v.box_kg, s.created_at');
        $this->db->from('Sortasi s');
        $this->db->join('tbatch tb', 'tb.uuid = s.tbatch_uuid', 'left');
        $this->db->join('t_planning tp', 'tp.uuid = tb.t_planning_uuid', 'left');
        $this->db->join('varian v', 'v.uuid=tp.varian', 'left');
        $this->db->where('s.deleted_at IS NULL', null, false);
        $this->db->order_by('s.created_at', 'DESC');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            $val->tanggal = tanggal_indo($val->created_at);
        }
        return $data;
    }
    public function get_by_uuid($uuid)
    {
        $this->db->select("
        s.*,
        js.kode AS jenis_sortasi_kode,
        js.nama AS jenis_sortasi_nama,

        tb.kode_batch,
        tb.filkar_box,
        tb.sortasi_box,
        tb.release_box,
        tb.bad_sortasi_rework_kg,
        tb.bad_sortasi_reject_kg,

        v.uuid AS varian_uuid,
        v.varian,
        v.keterangan AS varian_keterangan,
        v.box_kg,

        u.fullname
    ");

        $this->db->from('sortasi s');

        $this->db->join(
            'jenis_sortasi js',
            'js.uuid = s.jenis_sortasi_uuid',
            'left'
        );

        $this->db->join(
            'tbatch tb',
            'tb.uuid = s.tbatch_uuid',
            'left'
        );

        $this->db->join(
            't_planning tp',
            'tp.uuid = tb.t_planning_uuid',
            'left'
        );

        $this->db->join(
            'varian v',
            'v.uuid = tp.varian',
            'left'
        );

        $this->db->join(
            'users u',
            'u.uuid = s.user_uuid',
            'left'
        );

        $this->db->where('s.uuid', $uuid);
        $this->db->where('s.deleted_at IS NULL', NULL, FALSE);

        return $this->db->get()->row();
    }
    public function get_mesin_batch($tbatch_uuid)
    {
        $this->db->distinct();
        $this->db->select("
        m.uuid,
        m.nama_mesin
    ");
        $this->db->from('tcounter tc');
        $this->db->join(
            'mesin m',
            'm.uuid = tc.mesin_uuid',
            'left'
        );
        $this->db->where('tc.tbatch_uuid', $tbatch_uuid);
        $this->db->where('tc.counter >', 0);
        $this->db->where('tc.deleted_at', NULL);
        $this->db->where('m.deleted_at', NULL);
        $this->db->order_by('m.nama_mesin');
        return $this->db->get()->result();
    }
    public function insert()
    {
        $this->db->trans_begin();

        try {

            /* =====================================================
         * DATA DASAR
         * ===================================================== */

            $uuid = Uuid::uuid4()->toString();

            $tbatch_uuid =
                $this->input->post('tbatch_uuid');

            $jenis_sortasi_uuid =
                $this->input->post('jenis_sortasi_uuid');

            $proses_uuid =
                $this->Proses_model->get_uuid('SORTASI');

            $user_uuid =
                $this->Auth_model
                ->current_user()
                ->uuid;


            if (empty($tbatch_uuid)) {
                throw new Exception('Batch tidak ditemukan.');
            }

            if (empty($jenis_sortasi_uuid)) {
                throw new Exception('Jenis sortasi tidak ditemukan.');
            }


            /* =====================================================
         * DATA WIP
         * ===================================================== */

            $wip_uuid =
                $this->input->post('wip_uuid');

            $wip_jumlah =
                $this->input->post('wip_jumlah');


            $total_input = 0;


            if (
                !empty($wip_uuid) &&
                is_array($wip_uuid)
            ) {

                foreach (
                    $wip_uuid as $index => $wip
                ) {

                    $jumlah =
                        isset($wip_jumlah[$index])
                        ? (float) $wip_jumlah[$index]
                        : 0;


                    if ($jumlah <= 0) {
                        continue;
                    }


                    /*
                 * WIP hanya sebagai referensi.
                 *
                 * Sesuai alur project:
                 * input boleh lebih besar dari
                 * estimasi WIP.
                 */

                    $total_input += $jumlah;
                }
            }


            if ($total_input <= 0) {

                throw new Exception(
                    'Jumlah WIP yang digunakan harus lebih dari 0.'
                );
            }


            /* =====================================================
         * DATA OUTPUT
         * ===================================================== */

            $release =
                (float) (
                    $this->input->post('release_box')
                    ?: 0
                );

            $tampung =
                (float) (
                    $this->input->post('output_tampung')
                    ?: 0
                );

            $kasar =
                (float) (
                    $this->input->post('output_kasar')
                    ?: 0
                );

            $cuci =
                (float) (
                    $this->input->post('output_cuci')
                    ?: 0
                );


            $total_output =
                $release +
                $tampung +
                $kasar +
                $cuci;


            /*
         * Output tidak boleh melebihi input.
         */

            if ($total_output > $total_input) {

                throw new Exception(
                    'Total output melebihi WIP yang digunakan.'
                );
            }


            /* =====================================================
         * INSERT SORTASI
         * ===================================================== */

            $data_sortasi = [

                'uuid' =>
                $uuid,

                'tbatch_uuid' =>
                $tbatch_uuid,

                'proses_uuid' =>
                $proses_uuid,

                'jenis_sortasi_uuid' =>
                $jenis_sortasi_uuid,

                /*
             * Tetap diisi untuk kompatibilitas
             * dengan struktur lama.
             */

                'jml_release' =>
                $release,

                'jumlah_wip' =>
                $total_input,

                'keterangan' =>
                $this->input->post('keterangan'),

                'jam_mulai' =>
                $this->input->post('mulai'),

                'jam_selesai' =>
                $this->input->post('selesai'),

                'jml_mp' =>
                $this->input->post('jml_mp'),

                'user_uuid' =>
                $user_uuid
            ];


            $insert_sortasi =
                $this->db->insert(
                    'sortasi',
                    $data_sortasi
                );


            if (!$insert_sortasi) {

                throw new Exception(
                    'Gagal menyimpan data Sortasi.'
                );
            }


            /* =====================================================
         * SIMPAN PEMAKAIAN WIP
         * ===================================================== */

            if (
                !empty($wip_uuid) &&
                is_array($wip_uuid)
            ) {

                foreach (
                    $wip_uuid as $index => $wip
                ) {

                    $jumlah =
                        isset($wip_jumlah[$index])
                        ? (float) $wip_jumlah[$index]
                        : 0;


                    if ($jumlah <= 0) {
                        continue;
                    }


                    /*
                 * Pastikan WIP benar-benar milik batch
                 */

                    $row_wip =
                        $this->db
                        ->where(
                            'uuid',
                            $wip
                        )
                        ->where(
                            'tbatch_uuid',
                            $tbatch_uuid
                        )
                        ->where(
                            'deleted_at IS NULL',
                            NULL,
                            FALSE
                        )
                        ->get('sortasi_wip')
                        ->row();


                    if (!$row_wip) {

                        throw new Exception(
                            'Data WIP tidak valid.'
                        );
                    }


                    /* ---------------------------------------------
                 * DETAIL PEMAKAIAN WIP
                 * --------------------------------------------- */

                    $this->db->insert(
                        'sortasi_wip_detail',
                        [

                            'uuid' =>
                            Uuid::uuid4()
                                ->toString(),

                            'sortasi_uuid' =>
                            $uuid,

                            'sortasi_wip_uuid' =>
                            $wip,

                            'jumlah' =>
                            $jumlah,

                            'satuan' =>
                            'BOX',

                            'created_at' =>
                            date(
                                'Y-m-d H:i:s'
                            )
                        ]
                    );


                    /*
                 * Update jumlah terpakai.
                 *
                 * Tidak dibatasi oleh sisa WIP karena
                 * WIP merupakan estimasi/referensi.
                 */

                    $this->db
                        ->set(
                            'jumlah_terpakai',
                            'jumlah_terpakai + ' . $jumlah,
                            FALSE
                        )
                        ->where(
                            'uuid',
                            $wip
                        )
                        ->update(
                            'sortasi_wip'
                        );
                }
            }


            /* =====================================================
         * INSERT OUTPUT
         * ===================================================== */

            $outputs = [

                'RELEASE' => $release,

                'TAMPUNG' => $tampung,

                'KASAR' => $kasar,

                'CUCI' => $cuci

            ];


            foreach (
                $outputs as $jenis_output => $jumlah
            ) {

                if ($jumlah <= 0) {
                    continue;
                }


                $output_uuid =
                    Uuid::uuid4()
                    ->toString();


                /* ---------------------------------------------
             * INSERT SORTASI OUTPUT
             * --------------------------------------------- */

                $this->db->insert(
                    'sortasi_output',
                    [

                        'uuid' =>
                        $output_uuid,

                        'sortasi_uuid' =>
                        $uuid,

                        'jenis_output' =>
                        $jenis_output,

                        'jumlah' =>
                        $jumlah,

                        'satuan' =>
                        'BOX',

                        'keterangan' =>
                        NULL,

                        'created_at' =>
                        date(
                            'Y-m-d H:i:s'
                        )

                    ]
                );


                /*
             * TAMPUNG dan KASAR menjadi WIP baru.
             */

                if (
                    $jenis_output === 'TAMPUNG' ||
                    $jenis_output === 'KASAR'
                ) {

                    $this->db->insert(
                        'sortasi_wip',
                        [

                            'uuid' =>
                            Uuid::uuid4()
                                ->toString(),

                            'tbatch_uuid' =>
                            $tbatch_uuid,

                            'sortasi_output_uuid' =>
                            $output_uuid,

                            'jenis_wip' =>
                            $jenis_output,

                            'jumlah_awal' =>
                            $jumlah,

                            'jumlah_terpakai' =>
                            0,

                            'satuan' =>
                            'BOX',

                            'created_at' =>
                            date(
                                'Y-m-d H:i:s'
                            )

                        ]
                    );
                }
            }


            /* =====================================================
         * BAD PRODUK
         * ===================================================== */

            $badpro_uuid =
                $this->input->post('badpro_uuid');

            $badpro_berat =
                $this->input->post('badpro_berat');

            $mesin_uuid =
                $this->input->post('mesin_uuid');


            if (
                !empty($badpro_uuid) &&
                is_array($badpro_uuid)
            ) {

                foreach (
                    $badpro_uuid as $index => $bp_uuid
                ) {

                    if (empty($bp_uuid)) {
                        continue;
                    }


                    $berat =
                        isset($badpro_berat[$index])
                        ? (float) $badpro_berat[$index]
                        : 0;


                    if ($berat <= 0) {
                        continue;
                    }


                    /* ---------------------------------------------
                 * UUID BAD PRODUK
                 * --------------------------------------------- */

                    $t_badpro_uuid =
                        Uuid::uuid4()
                        ->toString();


                    /* ---------------------------------------------
                 * INSERT T_BADPRO
                 * --------------------------------------------- */

                    $insert_bad =
                        $this->db->insert(
                            't_badpro',
                            [

                                'uuid' =>
                                $t_badpro_uuid,

                                'tbatch_uuid' =>
                                $tbatch_uuid,

                                'proses_uuid' =>
                                $proses_uuid,

                                'ref_uuid' =>
                                $uuid,

                                'badpro_uuid' =>
                                $bp_uuid,

                                'berat' =>
                                $berat,

                                'keterangan' =>
                                '',

                                'created_by' =>
                                $user_uuid,

                                'created_at' =>
                                date(
                                    'Y-m-d H:i:s'
                                )

                            ]
                        );


                    if (!$insert_bad) {

                        throw new Exception(
                            'Gagal menyimpan Bad Produk.'
                        );
                    }


                    /* ---------------------------------------------
                 * MESIN DOMINAN
                 * --------------------------------------------- */

                    $mesin_list =
                        isset($mesin_uuid[$index])
                        ? $mesin_uuid[$index]
                        : [];


                    if (
                        !empty($mesin_list) &&
                        is_array($mesin_list)
                    ) {

                        foreach (
                            $mesin_list as $mesin
                        ) {

                            if (empty($mesin)) {
                                continue;
                            }


                            $this->db->insert(
                                't_badpro_mesin',
                                [

                                    'uuid' =>
                                    Uuid::uuid4()
                                        ->toString(),

                                    't_badpro_uuid' =>
                                    $t_badpro_uuid,

                                    'mesin_uuid' =>
                                    $mesin

                                ]
                            );
                        }
                    }
                }
            }


            /* =====================================================
         * UPDATE TOTAL BAD SORTASI
         * ===================================================== */

            $this->update_total_bad_sortasi(
                $tbatch_uuid
            );


            /* =====================================================
         * UPDATE FIELD TBATCH
         *
         * HANYA RELEASE.
         *
         * sortasi_box TIDAK LAGI DIISI
         * DENGAN SUM jumlah_wip LAMA.
         * ===================================================== */

            $this->update_total_release_batch(
                $tbatch_uuid
            );


            /* =====================================================
         * CEK TRANSAKSI
         * ===================================================== */

            if ($this->db->trans_status()) {

                $this->db->trans_commit();

                return TRUE;
            }


            $this->db->trans_rollback();

            return FALSE;
        } catch (Exception $e) {

            $this->db->trans_rollback();


            log_message(
                'error',
                'Insert Sortasi Error: ' .
                    $e->getMessage()
            );


            return FALSE;
        }
    }
    public function update($uuid)
    {
        $this->db->trans_begin();

        try {

            $old = $this->get_by_uuid($uuid);

            if (!$old) {
                throw new Exception('Data Sortasi tidak ditemukan.');
            }

            $tbatch_uuid =
                $this->input->post('tbatch_uuid');

            $jenis_sortasi_uuid =
                $this->input->post('jenis_sortasi_uuid');

            $proses_uuid =
                $this->Proses_model->get_uuid('SORTASI');

            /*
        |--------------------------------------------------------------------------
        | 1. KEMBALIKAN WIP YANG DIPAKAI TRANSAKSI LAMA
        |--------------------------------------------------------------------------
        */

            $old_details = $this->db
                ->where('sortasi_uuid', $uuid)
                ->get('sortasi_wip_detail')
                ->result();

            foreach ($old_details as $detail) {

                $this->db
                    ->set(
                        'jumlah_terpakai',
                        'jumlah_terpakai - ' . (float) $detail->jumlah,
                        FALSE
                    )
                    ->where(
                        'uuid',
                        $detail->sortasi_wip_uuid
                    )
                    ->update('sortasi_wip');
            }

            /*
        |--------------------------------------------------------------------------
        | 2. HAPUS DETAIL WIP LAMA
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('sortasi_uuid', $uuid)
                ->delete('sortasi_wip_detail');

            /*
        |--------------------------------------------------------------------------
        | 3. HAPUS OUTPUT LAMA
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('sortasi_uuid', $uuid)
                ->update('sortasi_output', [
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            /*
        |--------------------------------------------------------------------------
        | 4. UPDATE HEADER SORTASI
        |--------------------------------------------------------------------------
        */

            $wip_uuid =
                $this->input->post('wip_uuid') ?? [];

            $wip_jumlah =
                $this->input->post('wip_jumlah') ?? [];

            $total_input = 0;

            foreach ($wip_uuid as $i => $wip) {

                $jumlah =
                    isset($wip_jumlah[$i])
                    ? (float) $wip_jumlah[$i]
                    : 0;

                if ($jumlah > 0) {
                    $total_input += $jumlah;
                }
            }

            $release =
                (float) $this->input->post('release_box');

            $data = [
                'tbatch_uuid'        => $tbatch_uuid,
                'jenis_sortasi_uuid' => $jenis_sortasi_uuid,
                'jml_release'        => $release,
                'jumlah_wip'         => $total_input,
                'keterangan'         => $this->input->post('keterangan'),
                'jam_mulai'          => $this->input->post('mulai'),
                'jam_selesai'        => $this->input->post('selesai'),
                'jml_mp'             => $this->input->post('jml_mp')
            ];

            $this->db
                ->where('uuid', $uuid)
                ->where('deleted_at IS NULL', NULL, FALSE)
                ->update('sortasi', $data);

            /*
        |--------------------------------------------------------------------------
        | 5. SIMPAN PEMAKAIAN WIP BARU
        |--------------------------------------------------------------------------
        */

            foreach ($wip_uuid as $i => $wip) {

                $jumlah =
                    isset($wip_jumlah[$i])
                    ? (float) $wip_jumlah[$i]
                    : 0;

                if ($jumlah <= 0) {
                    continue;
                }

                $row = $this->db
                    ->select('jumlah_awal, jumlah_terpakai')
                    ->where('uuid', $wip)
                    ->where('deleted_at IS NULL', NULL, FALSE)
                    ->get('sortasi_wip')
                    ->row();

                if (!$row) {
                    throw new Exception('WIP tidak ditemukan.');
                }

                $sisa =
                    (float) $row->jumlah_awal
                    -
                    (float) $row->jumlah_terpakai;

                if ($jumlah > $sisa) {
                    throw new Exception(
                        'Jumlah WIP melebihi WIP tersedia.'
                    );
                }

                $this->db->insert(
                    'sortasi_wip_detail',
                    [
                        'uuid'             =>
                        Uuid::uuid4()->toString(),

                        'sortasi_uuid'     =>
                        $uuid,

                        'sortasi_wip_uuid' =>
                        $wip,

                        'jumlah'           =>
                        $jumlah,

                        'satuan'           =>
                        'BOX'
                    ]
                );

                $this->db
                    ->set(
                        'jumlah_terpakai',
                        'jumlah_terpakai + ' . $jumlah,
                        FALSE
                    )
                    ->where('uuid', $wip)
                    ->update('sortasi_wip');
            }

            /*
        |--------------------------------------------------------------------------
        | 6. SIMPAN OUTPUT
        |--------------------------------------------------------------------------
        */

            $outputs = [
                'RELEASE' => $release,

                'TAMPUNG' =>
                (float) $this->input->post('output_tampung'),

                'KASAR' =>
                (float) $this->input->post('output_kasar'),

                'CUCI' =>
                (float) $this->input->post('output_cuci')
            ];

            foreach ($outputs as $jenis => $jumlah) {

                if ($jumlah <= 0) {
                    continue;
                }

                $output_uuid =
                    Uuid::uuid4()->toString();

                $this->db->insert(
                    'sortasi_output',
                    [
                        'uuid' =>
                        $output_uuid,

                        'sortasi_uuid' =>
                        $uuid,

                        'jenis_output' =>
                        $jenis,

                        'jumlah' =>
                        $jumlah,

                        'satuan' =>
                        'BOX'
                    ]
                );

                /*
            |--------------------------------------------------------------------------
            | TAMPUNG / KASAR MENJADI WIP
            |--------------------------------------------------------------------------
            */

                if (
                    in_array(
                        $jenis,
                        ['TAMPUNG', 'KASAR']
                    )
                ) {

                    $this->db->insert(
                        'sortasi_wip',
                        [
                            'uuid' =>
                            Uuid::uuid4()->toString(),

                            'tbatch_uuid' =>
                            $tbatch_uuid,

                            'sortasi_output_uuid' =>
                            $output_uuid,

                            'jenis_wip' =>
                            $jenis,

                            'jumlah_awal' =>
                            $jumlah,

                            'jumlah_terpakai' =>
                            0,

                            'satuan' =>
                            'BOX'
                        ]
                    );
                }
            }

            /*
        |--------------------------------------------------------------------------
        | 7. BAD PRODUK
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('ref_uuid', $uuid)
                ->where('proses_uuid', $proses_uuid)
                ->update('t_badpro', [
                    'deleted_at' =>
                    date('Y-m-d H:i:s')
                ]);

            $badpro_uuid =
                $this->input->post('badpro_uuid');

            $badpro_berat =
                $this->input->post('badpro_berat');

            $mesin_uuid =
                $this->input->post('mesin_uuid');

            if (
                is_array($badpro_uuid)
                &&
                !empty($badpro_uuid)
            ) {

                foreach (
                    $badpro_uuid as $index => $bp_uuid
                ) {

                    if (empty($bp_uuid)) {
                        continue;
                    }

                    $berat =
                        isset($badpro_berat[$index])
                        ? (float) $badpro_berat[$index]
                        : 0;

                    if ($berat <= 0) {
                        continue;
                    }

                    $bad_uuid =
                        Uuid::uuid4()->toString();

                    $this->db->insert(
                        't_badpro',
                        [
                            'uuid' =>
                            $bad_uuid,

                            'tbatch_uuid' =>
                            $tbatch_uuid,

                            'proses_uuid' =>
                            $proses_uuid,

                            'ref_uuid' =>
                            $uuid,

                            'badpro_uuid' =>
                            $bp_uuid,

                            'berat' =>
                            $berat,

                            'keterangan' =>
                            '',

                            'created_by' =>
                            $this->Auth_model
                                ->current_user()
                                ->uuid,

                            'created_at' =>
                            date('Y-m-d H:i:s')
                        ]
                    );

                    $mesin_list =
                        $mesin_uuid[$index] ?? [];

                    if (is_array($mesin_list)) {

                        foreach (
                            $mesin_list as $mesin
                        ) {

                            if (empty($mesin)) {
                                continue;
                            }

                            $this->db->insert(
                                't_badpro_mesin',
                                [
                                    'uuid' =>
                                    Uuid::uuid4()
                                        ->toString(),

                                    't_badpro_uuid' =>
                                    $bad_uuid,

                                    'mesin_uuid' =>
                                    $mesin
                                ]
                            );
                        }
                    }
                }
            }

            /*
        |--------------------------------------------------------------------------
        | 8. UPDATE TOTAL BAD
        |--------------------------------------------------------------------------
        */

            $this->update_total_bad_sortasi(
                $tbatch_uuid
            );

            if ($this->db->trans_status()) {

                $this->db->trans_commit();

                return TRUE;
            }

            $this->db->trans_rollback();

            return FALSE;
        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message(
                'error',
                'Update Sortasi Error: ' .
                    $e->getMessage()
            );

            return FALSE;
        }
    }

    private function update_total_release_batch($tbatch_uuid)
    {
        $proses_uuid =
            $this->Proses_model->get_uuid('SORTASI');


        $this->db->select_sum('jml_release');

        $this->db->where(
            'tbatch_uuid',
            $tbatch_uuid
        );

        $this->db->where(
            'proses_uuid',
            $proses_uuid
        );

        $this->db->where(
            'deleted_at',
            NULL
        );


        $row =
            $this->db
            ->get('sortasi')
            ->row();


        $release =
            (float) (
                $row->jml_release ?? 0
            );


        $this->db
            ->where(
                'uuid',
                $tbatch_uuid
            )
            ->update(
                'tbatch',
                [
                    'release_box' => $release
                ]
            );
    }
    private function update_total_sortasi($tbatch_uuid)
    {
        // total box yang sudah disortasi
        $this->db->select_sum('jml_release');
        $this->db->select_sum('jumlah_wip');
        $this->db->where('tbatch_uuid', $tbatch_uuid);
        $this->db->where('deleted_at', NULL);
        $total = $this->db->get('sortasi')->row();
        // ambil box_kg dari batch
        // $batch = $this->Counter_model->get_batch_uuid($tbatch_uuid);
        $jumlah_box = $total->jml_release ?? 0;
        $wip_box = $total->jumlah_wip ?? 0;
        // $jumlah_kg  = $jumlah_box * ($batch->box_kg ?? 0);
        $this->db->where('uuid', $tbatch_uuid);
        $this->db->update('tbatch', [
            'release_box' => $jumlah_box,
            'sortasi_box' => $wip_box
        ]);
    }
    public function update_total_bad_sortasi($tbatch_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        $this->db->select("
			SUM(CASE WHEN badpro.kategori = 1 THEN t_badpro.berat ELSE 0 END) AS rework,
			SUM(CASE WHEN badpro.kategori = 2 THEN t_badpro.berat ELSE 0 END) AS reject
			");
        $this->db->from('t_badpro');
        $this->db->join('badpro', 'badpro.uuid=t_badpro.badpro_uuid');
        $this->db->where('t_badpro.tbatch_uuid', $tbatch_uuid);
        $this->db->where('t_badpro.proses_uuid', $proses_uuid);
        $this->db->where('t_badpro.deleted_at', NULL);
        $total = $this->db->get()->row();
        $this->db->where('uuid', $tbatch_uuid);
        $this->db->update('tbatch', [
            'bad_sortasi_rework_kg' => $total->rework ?? 0,
            'bad_sortasi_reject_kg' => $total->reject ?? 0,
        ]);
    }
    public function get_batch()
    {
        $this->db->select("
        b.uuid,
        b.kode_batch,
        b.adonan,
        b.filkar_box,
        (b.filkar_box - COALESCE(b.sortasi_box, 0)) AS sisa_wip,
        v.varian,
        v.keterangan,
        v.kontainer_kg,
        v.box_kg
		");
        $this->db->from('tbatch b');
        $this->db->join('t_planning p', 'p.uuid = b.t_planning_uuid', 'left');
        $this->db->join('varian v', 'v.uuid = p.varian', 'left');
        $this->db->where('b.deleted_at', NULL);
        $this->db->where('(b.filkar_box - COALESCE(b.sortasi_box, 0)) !=', 0);
        $this->db->order_by('b.created_at', 'DESC');
        $this->db->order_by('b.kode_batch', 'DESC');
        return $this->db->get()->result();
    }
    public function get_badpro($proses = null)
    {
        $this->db->select('*, badpro.uuid as uuid_badpro');
        $this->db->from('badpro');
        if ($proses != null) {
            $this->db->join('m_proses', 'm_proses.uuid = badpro.proses_uuid', 'left');
            $this->db->where('m_proses.kode', $proses);
        }
        $this->db->where('badpro.deleted_at', NULL);
        $this->db->order_by('badpro.nama_badpro');
        $data = $this->db->get()->result();
        foreach ($data as $val) {
            if ($val->kategori == 1) {
                $val->kategori_nama = 'Rework';
            } elseif ($val->kategori == 2) {
                $val->kategori_nama = 'Reject';
            }
        }
        return $data;
    }
    public function get_badpro_by_ref($ref_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        /*
     * =====================================================
     * AMBIL DATA BAD PRODUK
     * =====================================================
     */
        $this->db->select("
        t_badpro.uuid,
        t_badpro.tbatch_uuid,
        t_badpro.proses_uuid,
        t_badpro.ref_uuid,
        t_badpro.badpro_uuid,
        t_badpro.berat,
        t_badpro.keterangan,
        t_badpro.created_at,
        badpro.nama_badpro,
        badpro.kategori
    ");
        $this->db->from('t_badpro');
        $this->db->join(
            'badpro',
            'badpro.uuid = t_badpro.badpro_uuid',
            'left'
        );
        $this->db->where(
            't_badpro.ref_uuid',
            $ref_uuid
        );
        $this->db->where(
            't_badpro.proses_uuid',
            $proses_uuid
        );
        $this->db->where(
            't_badpro.deleted_at',
            NULL
        );
        $this->db->order_by(
            'badpro.nama_badpro',
            'ASC'
        );
        $rows = $this->db->get()->result();
        /*
     * =====================================================
     * AMBIL MESIN DOMINAN SETIAP BAD PRODUK
     * =====================================================
     */
        foreach ($rows as $r) {
            $this->db->select("
            mesin.uuid,
            mesin.nama_mesin
        ");
            $this->db->from('t_badpro_mesin');
            $this->db->join(
                'mesin',
                'mesin.uuid = t_badpro_mesin.mesin_uuid',
                'left'
            );
            $this->db->where(
                't_badpro_mesin.t_badpro_uuid',
                $r->uuid
            );
            $this->db->where(
                't_badpro_mesin.deleted_at',
                NULL
            );
            $this->db->where(
                'mesin.deleted_at',
                NULL
            );
            $this->db->order_by(
                'mesin.nama_mesin',
                'ASC'
            );
            $mesin = $this->db->get()->result();
            /*
         * Simpan nama mesin dalam bentuk array
         */
            $r->mesin = $mesin;
            /*
         * Untuk tampilan tabel
         */
            $nama_mesin = [];
            foreach ($mesin as $m) {
                $nama_mesin[] = $m->nama_mesin;
            }
            $r->nama_mesin = implode(', ', $nama_mesin);
        }
        return $rows;
    }
    public function get_batch_info($uuid)
    {
        $this->db->select("
			tb.uuid,
			tb.filkar_box,
			tb.sortasi_box,
			v.box_kg
			");
        $this->db->from('tbatch tb');
        $this->db->join(
            't_planning tp',
            'tp.uuid=tb.t_planning_uuid'
        );
        $this->db->join(
            'varian v',
            'v.uuid=tp.varian'
        );
        $this->db->where('tb.uuid', $uuid);
        $row = $this->db->get()->row();
        if (!$row) {
            return null;
        }
        $row->sisa_sortasi = $row->filkar_box - $row->sortasi_box;
        return $row;
    }
    public function delete($uuid)
    {
        $data = $this->get_by_uuid($uuid);

        if (!$data) {
            return false;
        }

        $this->db->trans_begin();

        try {

            $now = date('Y-m-d H:i:s');

            /*
        |--------------------------------------------------------------------------
        | 1. KEMBALIKAN WIP YANG DIPAKAI
        |--------------------------------------------------------------------------
        */

            $details = $this->db
                ->where('sortasi_uuid', $uuid)
                ->get('sortasi_wip_detail')
                ->result();

            foreach ($details as $detail) {

                $this->db
                    ->set(
                        'jumlah_terpakai',
                        'jumlah_terpakai - ' .
                            (float) $detail->jumlah,
                        FALSE
                    )
                    ->where(
                        'uuid',
                        $detail->sortasi_wip_uuid
                    )
                    ->update('sortasi_wip');
            }

            /*
        |--------------------------------------------------------------------------
        | 2. SOFT DELETE DETAIL WIP
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('sortasi_uuid', $uuid)
                ->delete('sortasi_wip_detail');

            /*
        |--------------------------------------------------------------------------
        | 3. SOFT DELETE OUTPUT
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('sortasi_uuid', $uuid)
                ->update('sortasi_output', [
                    'deleted_at' => $now
                ]);

            /*
        |--------------------------------------------------------------------------
        | 4. WIP HASIL TAMPUNG/KASAR DARI SORTASI INI
        |    JUGA DINONAKTIFKAN
        |--------------------------------------------------------------------------
        */

            $outputs = $this->db
                ->select('uuid')
                ->where('sortasi_uuid', $uuid)
                ->get('sortasi_output')
                ->result();

            foreach ($outputs as $output) {

                $this->db
                    ->where(
                        'sortasi_output_uuid',
                        $output->uuid
                    )
                    ->update('sortasi_wip', [
                        'deleted_at' => $now
                    ]);
            }

            /*
        |--------------------------------------------------------------------------
        | 5. SOFT DELETE SORTASI
        |--------------------------------------------------------------------------
        */

            $this->db
                ->where('uuid', $uuid)
                ->update('sortasi', [
                    'deleted_at' => $now
                ]);

            /*
        |--------------------------------------------------------------------------
        | 6. SOFT DELETE BAD PRODUK
        |--------------------------------------------------------------------------
        */

            $proses_uuid =
                $this->Proses_model
                ->get_uuid('SORTASI');

            $this->db
                ->where('ref_uuid', $uuid)
                ->where('proses_uuid', $proses_uuid)
                ->update('t_badpro', [
                    'deleted_at' => $now
                ]);

            /*
        |--------------------------------------------------------------------------
        | 7. UPDATE TOTAL BAD
        |--------------------------------------------------------------------------
        */

            $this->update_total_bad_sortasi(
                $data->tbatch_uuid
            );

            if ($this->db->trans_status()) {

                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message(
                'error',
                'Delete Sortasi Error: ' .
                    $e->getMessage()
            );

            return false;
        }
    }
    public function get_badpro_summary_by_ref($ref_uuid)
    {
        $proses_uuid = $this->Proses_model->get_uuid('SORTASI');
        $this->db->select("
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 1
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS rework_kg,
        COALESCE(
            SUM(
                CASE
                    WHEN badpro.kategori = 2
                    THEN t_badpro.berat
                    ELSE 0
                END
            ),
            0
        ) AS reject_kg,
        COALESCE(
            SUM(t_badpro.berat),
            0
        ) AS total_bad_kg
    ", FALSE);
        $this->db->from('t_badpro');
        $this->db->join(
            'badpro',
            'badpro.uuid = t_badpro.badpro_uuid',
            'left'
        );
        $this->db->where(
            't_badpro.ref_uuid',
            $ref_uuid
        );
        $this->db->where(
            't_badpro.proses_uuid',
            $proses_uuid
        );
        $this->db->where(
            't_badpro.deleted_at',
            NULL
        );
        return $this->db->get()->row();
    }
    public function get_batch_edit($tbatch_uuid)
    {
        $this->db->select("
        b.uuid,
        b.kode_batch,
        b.adonan,
        b.filkar_box,
        (b.filkar_box - COALESCE(b.sortasi_box, 0)) AS sisa_wip,
        v.varian,
        v.keterangan,
        v.kontainer_kg,
        v.box_kg
    ");
        $this->db->from('tbatch b');
        $this->db->join(
            't_planning p',
            'p.uuid = b.t_planning_uuid',
            'left'
        );
        $this->db->join(
            'varian v',
            'v.uuid = p.varian',
            'left'
        );
        $this->db->where('b.deleted_at', NULL);
        // Tetap tampilkan batch yang sedang diedit
        $this->db->group_start();
        $this->db->where(
            '(b.filkar_box - COALESCE(b.sortasi_box, 0)) != 0',
            NULL,
            FALSE
        );
        $this->db->or_where('b.uuid', $tbatch_uuid);
        $this->db->group_end();
        $this->db->order_by('b.created_at', 'DESC');
        $this->db->order_by('b.kode_batch', 'DESC');
        return $this->db->get()->result();
    }

    /*
*=======================================
JENIS SORTASI
*=======================================
*/
    public function get_all_jenis()
    {
        return $this->db->get('jenis_sortasi')->result();
    }

    public function get_jenis_by_uuid($uuid)
    {
        return $this->db->get_where('jenis_sortasi', array('uuid' => $uuid))->row();
    }

    public function insert_jenis()
    {
        $uuid = Uuid::uuid4()->toString();
        $jenis = $this->input->post('jenis');
        $keterangan = $this->input->post('keterangan');
        $data = array(
            'uuid' => $uuid,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'user_uuid'     => $this->auth_model->current_user()->uuid
        );

        $this->db->insert('jenis_sortasi', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function update_jenis($uuid)
    {
        $jenis = $this->input->post('jenis');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'user_uuid' => $this->auth_model->current_user()->uuid,
            'jenis' => $jenis,
            'keterangan' => $keterangan,
            'modified_at' => date('Y-m-d h:i:s')
        );

        $this->db->update('jenis_sortasi', $data, array('uuid' => $uuid)); // query update
        return ($this->db->affected_rows() > 0) ? true : false; // kondisi klu update sukses akan bernilai true dan sebaliknya
    }

    public function get_jenis_sortasi()
    {
        return $this->db
            ->where('aktif', 1)
            ->order_by('nama', 'ASC')
            ->get('jenis_sortasi')
            ->result();
    }

    public function get_wip_batch($tbatch_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);
        $this->db->select("
        sw.uuid,
        sw.tbatch_uuid,
        sw.jenis_wip,
        sw.jumlah_awal,
        sw.jumlah_terpakai,
        (
            sw.jumlah_awal - sw.jumlah_terpakai
        ) AS sisa_wip,
        sw.satuan
    ");

        $this->db->from('sortasi_wip sw');

        $this->db->where(
            'sw.tbatch_uuid',
            $tbatch_uuid
        );

        $this->db->where(
            'sw.deleted_at IS NULL',
            NULL,
            FALSE
        );

        $this->db->having('sisa_wip >', 0);

        $this->db->order_by('sw.created_at', 'ASC');

        return $this->db->get()->result();
    }

    public function get_output_by_sortasi($sortasi_uuid)
    {
        return $this->db
            ->where('sortasi_uuid', $sortasi_uuid)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->order_by('id', 'ASC')
            ->get('sortasi_output')
            ->result();
    }

    private function ensure_initial_wip($tbatch_uuid)
    {
        $exists = $this->db
            ->where('tbatch_uuid', $tbatch_uuid)
            ->where('jenis_wip', 'BELUM_SORTIR')
            ->where('sortasi_output_uuid IS NULL', NULL, FALSE)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->count_all_results('sortasi_wip');

        if ($exists > 0) {
            return;
        }

        $batch = $this->db
            ->select('filkar_box')
            ->where('uuid', $tbatch_uuid)
            ->get('tbatch')
            ->row();

        if (!$batch || $batch->filkar_box <= 0) {
            return;
        }

        $this->db->insert('sortasi_wip', [
            'uuid'          => Uuid::uuid4()->toString(),
            'tbatch_uuid'   => $tbatch_uuid,
            'jenis_wip'     => 'BELUM_SORTIR',
            'jumlah_awal'   => $batch->filkar_box,
            'jumlah_terpakai' => 0,
            'satuan'        => 'BOX'
        ]);
    }

    public function get_wip_for_edit($tbatch_uuid, $sortasi_uuid)
    {
        $this->ensure_initial_wip($tbatch_uuid);

        $this->db->select("
        sw.uuid,
        sw.jenis_wip,
        sw.jumlah_awal,
        sw.jumlah_terpakai,

        COALESCE(
            (
                SELECT SUM(swd.jumlah)
                FROM sortasi_wip_detail swd
                WHERE swd.sortasi_wip_uuid = sw.uuid
                AND swd.sortasi_uuid = " . $this->db->escape($sortasi_uuid) . "
            ), 0
        ) AS dipakai_edit
    ", FALSE);

        $this->db->from('sortasi_wip sw');

        $this->db->where(
            'sw.tbatch_uuid',
            $tbatch_uuid
        );

        $this->db->where(
            'sw.deleted_at IS NULL',
            NULL,
            FALSE
        );

        $this->db->order_by(
            'sw.created_at',
            'ASC'
        );

        $rows = $this->db->get()->result();

        foreach ($rows as $row) {

            $row->sisa_wip =
                ((float) $row->jumlah_awal
                    -
                    (float) $row->jumlah_terpakai)
                +
                (float) $row->dipakai_edit;
        }

        return $rows;
    }
}