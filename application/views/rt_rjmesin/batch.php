<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h2 class="h2 text-gray-800">Reject Mesin / Batch</h2>
        </div>
    </div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('rt_rjmesin') ?>"><i class="fas fa-arrow-left mr-2"></i>Reject
                Mesin Di Retort</a></li>
        <li class="breadcrumb-item active" aria-current="page">Batch</li>
    </ol>
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
                <table class="table table-success mb-4">
                    <tbody>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $data[0]->tgl;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $data[0]->MN_PRODUK;?></td>
                        </tr>
                        
                        <tr>
                            <td class="font-weight-bold">Foreman / Forelady</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($data[0]->fr_rt_rjmesin)): ?>
                                    <!-- Tampilkan tombol jika spv_uuid kosong -->
                                    <a href="" data-uuid="<?= $data[0]->uuid; ?>" class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                        data-placement="top" title="Approval">
                                        <i class="fa fa-check-circle mr-2"></i> ACC
                                    </a>
                                <?php else: ?>
                                    <!-- Tampilkan fullname jika sudah di-ACC -->
                                    <?= $data[0]->fr_rt_rjmesin ? $data[0]->foreman_name : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if (!empty($data[0]->fr_rt_rjmesin)): ?>
                            <tr>
                                <td class="font-weight-bold">Approval SPV</td>
                                <td width="15">:</td>
                                <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                    <?php if (empty($data[0]->spv_name)): ?>
                                        <!-- Tampilkan tombol jika spv_uuid kosong -->
                                        <a href="#" data-uuid="<?= $data[0]->uuid; ?>" class="btn btn-approve-spv btn-success shadow-sm" data-toggle="tooltip"
                                            data-placement="top" title="Approval">
                                            <i class="fa fa-check-circle mr-2"></i> ACC
                                        </a>
                                    <?php else: ?>
                                        <!-- Tampilkan fullname jika sudah di-ACC -->
                                        <?= $data[0]->spv_name ? $data[0]->spv_name : 'Sudah Disetujui'; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif ?>




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
                            <th class="font-weight-bold">Batch Ke-</th>.
                            <th class="font-weight-bold">Code</th>
                            <th class="font-weight-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    $no = 1;
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td><?= $row->batch_ke;?></td>
                            <td><?= $row->MN_BATCH;?></td>
                            <td>
                                <a href="<?= base_url('rt_rjmesin/tambahcek/'.$row->MN_BATCH); ?>"
                                    class="btn btn-md btn-primary shadow-sm"><i
                                        class="fas fa-plus fa-sm text-white mr-2"></i> Reject Mesin</a>
                                <a href="<?= base_url('rt_rjmesin/detailreject/'.$row->MN_BATCH); ?>"
                                    class="btn btn-md btn-success shadow-sm"><i
                                        class="fa fa-book fa-sm text-white mr-2"></i>Detail Reject Per Batch</a>
                            </td>
                        </tr>
                        <?php
                    $no++;
                }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    <script>
        $(document).ready(function() {
    // Handler untuk btn-approve
            $(document).on('click', '.btn-approve', function(event) {
                event.preventDefault();

                var uuid = $(this).data('uuid');
                var $button = $(this);
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
                        $.post('<?= base_url('rt_rjmesin/approve_kr'); ?>/' + uuid, function(res) {
                            var response = JSON.parse(res);
                            console.log(response);

                            if (response.status) {
                                $button.closest('td').html(response.fullname);
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
    }); // ← Perbaikan di sini
            });


    // Handler untuk btn-approve-spv (perbaiki dengan memisahkan event handler)
            $(document).on('click', '.btn-approve-spv', function(event) {
                event.preventDefault();

                var uuid = $(this).data('uuid');
                var $button = $(this);
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
                        $.post('<?= base_url('rt_rjmesin/approve_spv'); ?>/' + uuid, function(res) {
                            var response = JSON.parse(res);
                            console.log(response);

                            if (response.status) {
                                $button.closest('td').html(response.fullname);
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
            });
            });

        </script>