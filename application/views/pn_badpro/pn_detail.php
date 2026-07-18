<div class="container-fluid">

    <!-- Page Heading -->
    <h3 class="h3 mb-2 text-gray-800">Detail Pemusnahan Bad Produk</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pemusnahan_badproduct') ?>"><i
                class="fas fa-arrow-left mr-2"></i> Pemusnahan Bad Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>

        <?php if($this->session->flashdata('success_msg')): ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check"></i>
                <?= $this->session->flashdata('success_msg') ?>
            </div>
            <br>
        <?php endif ?>
        <?php if($this->session->flashdata('error_msg')): ?>
            <div class="alert alert-danger  text-center">
                <i class="fas fa-times"></i>
                <?= $this->session->flashdata('error_msg') ?>
            </div>
            <br>
        <?php endif ?>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <h5 class="font-weight-bold">Informasi Pemusnahan Bad Produk</h5>
                    <table class="table table-success mb-4">
                        <tbody>
                            <?
                            foreach ($data as $row)
                                ?>
                            <tr>
                                <td width="200" class="font-weight-bold border-top-0">Tanggal</td>
                                <td width="10" class="border-top-0">:</td>
                                <td class="font-weight-bold border-top-0"><?= $row->tgl;?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Shift</td>
                                <td width="15">:</td>
                                <td class="font-weight-bold"><?= $row->shift_name; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">User</td>
                                <td width="15">:</td>
                                <td class="font-weight-bold"><?= $row->username; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Koordinator</td>
                                <td width="15">:</td>
                                <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                    <?php if (empty($row->kr_uuid)): ?>
                                        <!-- Tombol ACC untuk persetujuan -->
                                        <a href="#" data-uuid="<?= $row->tanggal; ?>" data-shift="<?= $row->shift; ?>"
                                            class="btn btn-approve1 btn-success shadow-sm" data-toggle="tooltip"
                                            data-placement="top" title="Approval1">
                                            <i class="fa fa-check-circle mr-2"></i> ACC
                                        </a>

                                    <?php else: ?>
                                        <!-- Tampilkan fullname jika sudah di-ACC -->
                                        <?= $row->kr_uuid ? $row->kr_name : 'Sudah Disetujui'; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if (!empty($row->kr_uuid)): ?>
                                <tr>
                                    <td class="font-weight-bold">Approval SPV</td>
                                    <td width="15">:</td>
                                    <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                        <?php if (empty($row->spv_uuid)): ?>
                                            <!-- Tombol ACC untuk persetujuan -->
                                            <a href="#" data-uuid="<?= $row->tanggal; ?>" data-shift="<?= $row->shift; ?>"
                                                class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                                data-placement="top" title="Approval">
                                                <i class="fa fa-check-circle mr-2"></i> ACC
                                            </a>

                                        <?php else: ?>
                                            <!-- Tampilkan fullname jika sudah di-ACC -->
                                            <?= $row->spv ? $row->spv : 'Sudah Disetujui'; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th class='align-middle text-center' rowspan="2">No.</th>
                                <th class='align-middle text-center' rowspan="2">Kode Produk</th>
                                <th class='align-middle text-center' rowspan="2">Varian</th>
                                <th class='align-middle text-center' rowspan="2">Qty (Kg)</th>
                                <th class='align-middle text-center' colspan="2">Paraf</th>
                                <th class='align-middle text-center' rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th class='align-middle text-center' width='100px'>Checker</th>
                                <th class='align-middle text-center' width='100px' >QC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td class='align-middle text-center' width="1"><?= $no;?></td>
                                    <td class='align-middle text-center'><?= $row->kode_produksi;?></td>
                                    <td class='align-middle text-center'><?= $row->varian;?></td>
                                    <td class='align-middle text-center'><?= $row->qty_kg;?></td>
                                    <td class='align-middle text-center'><?= $row->username;?></td>
                                    <td class='align-middle text-center'><?= $row->acc_qc;?></td>
                                    <td class='align-middle text-center'><a
                                        href="<?= base_url('pemusnahan_badproduct/edit/'.$row->uuid); ?>"
                                        class=" btn btn-md btn-warning shadow-sm font-weight-bold"><i
                                        class=" fa fa-edit fa-sm text-white mr-1"></i> Edit</a>

                                    </tr>
                                    <?php
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col mt-3">
                        <a href="<?= base_url('pemusnahan_badproduct') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
        // Fungsi umum untuk approval
                function approve(url, button) {
            var uuid = button.data('uuid'); // Ambil UUID dari tombol yang diklik
            var shift = button.data('shift');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyetujui?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request approval via AJAX
                    $.post(url + '/' + uuid + '/' + shift, function(res) {
                        var response = JSON.parse(res);

                        if (response.status) {
                            // Ganti tombol dengan nama fullname
                            button.closest('td').html(response.fullname);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).fail(function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memproses data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }

        // Event handler untuk tombol approve KR
        $(document).on('click', '.btn-approve1', function(event) {
            event.preventDefault();
            approve('<?= base_url('pemusnahan_badproduct/approval_kr'); ?>', $(this));
        });

        // Event handler untuk tombol approve SPV
        $(document).on('click', '.btn-approve', function(event) {
            event.preventDefault();
            approve('<?= base_url('pemusnahan_badproduct/approval_spv'); ?>', $(this));
        });
    });
</script>
