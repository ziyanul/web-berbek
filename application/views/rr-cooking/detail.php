<div class="container-fluid">

    <h1 class="h1 mb-2 text-gray-800">Rejet Cooking Retort</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rr_cooking') ?>"><i class="fas fa-arrow-left"></i> Data Reject Cooking</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-success mb-4">
                    <tbody>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $info->tgl;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $info->varian;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $info->user;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Koordinator</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($info->kr_uuid)): ?>
                                    <!-- Tampilkan tombol jika spv_uuid kosong -->
                                    <a href="" data-tanggal="<?= $info->MR_DATE; ?>" data-varian="<?= $info->MR_uuid_varian; ?>"
                                        class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                        data-placement="top" title="Approval">
                                        <i class="fa fa-check-circle mr-2"></i> ACC
                                    </a>
                                <?php else: ?>
                                    <!-- Tampilkan fullname jika sudah di-ACC -->
                                    <?= $info->kr_uuid ? $info->kr_name : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if (!empty($info->kr_uuid)): ?>
                            <tr>
                                <td class="font-weight-bold">Approval SPV</td>
                                <td width="15">:</td>
                                <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                    <?php if (empty($info->spv_uuid)): ?>
                                        <!-- Tampilkan tombol jika spv_uuid kosong -->
                                        <a href="#" data-tanggal="<?= $info->MR_DATE; ?>" data-varian="<?= $info->MR_uuid_varian; ?>"
                                            class="btn btn-approve-spv btn-success shadow-sm" data-toggle="tooltip"
                                            data-placement="top" title="Approval">
                                            <i class="fa fa-check-circle mr-2"></i> ACC
                                        </a>
                                    <?php else: ?>
                                        <!-- Tampilkan fullname jika sudah di-ACC -->
                                        <?= $info->spv_uuid ? $info->spv_name : 'Sudah Disetujui'; ?>
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
                <?php 
                $batch_chunks = array_chunk($masak_data, 5, true);
                $tableIndex = 1;
                foreach ($batch_chunks as $batchDataChunk) : ?>
                    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th style="border-color: white;" rowspan="2">KETERANGAN</th>
                                <th style="border-color: white;" rowspan="2">Satuan</th>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <th style="border-color: white;" colspan="<?= count($batchData); ?>">Batch <?= $batch; ?></th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <?php foreach ($batchData as $data): ?>
                                        <th style="border-color: white;">B <?= $data['batch']; ?> (<?= $data['masak']; ?>)</th>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="border-color: white;" class="bg-info text-light">Jumlah Reject Per Cooking</th>
                                <th style="border-color: white;" class="bg-info text-light">(Kg)</th>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <?php foreach ($batchData as $data): ?>
                                        <td><?= $data['rj_cooking']; ?></td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <th style="border-color: white;" class="bg-info text-light">Total Reject</th>
                                <th style="border-color: white;" class="bg-info text-light">(Kg)</th>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <td colspan="<?= count($batchData); ?>">
                                        <?= array_sum(array_column($batchData, 'rj_cooking')); ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <th style="border-color: white;" class="bg-info text-light">Jumlah Tray</th>
                                <th style="border-color: white;" class="bg-info text-light">(Pcs)</th>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <?php foreach ($batchData as $data): ?>
                                        <td><?= $data['jml_tray']; ?></td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <th style="border-color: white;" colspan="2" class="bg-info text-light">Dimasak di Chamber</th>
                                <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                                    <?php foreach ($batchData as $data): ?>
                                        <td>Chamber <?= $data['MR_NOCHAM']; ?></td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <?php 
                    $tableIndex++;
                endforeach; ?>


            </div>
            <a href="<?= base_url('rr_cooking/') ?>" class="btn btn-md btn-danger mt-5">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>


    <script>
        $(document).ready(function() {
    // Handler untuk btn-approve
            $(document).on('click', '.btn-approve', function(event) {
                event.preventDefault();

                var tanggal = $(this).data('tanggal');
                var varian = $(this).data('varian');
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
                        $.post('<?= base_url('Rr_cooking/approve_kr'); ?>/' + tanggal + '/' + varian, function(res) {
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

                var tanggal = $(this).data('tanggal');
                var varian = $(this).data('varian');
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
                        $.post('<?= base_url('Rr_cooking/approve_spv'); ?>/' + tanggal + '/' + varian, function(res) {
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