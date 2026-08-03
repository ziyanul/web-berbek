<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Detail Pengecekan Tools Mesin Area</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('tools_mesin/data') ?>"><i class="fas fa-arrow-left mr-2"></i>Pengecekan Tools Mesin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Area
                        </div>
                        <h5 class="font-weight-bold text-gray-800">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <?= $data['area'] ?>
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Periode Pengecekan
                        </div>
                        <h5 class="font-weight-bold text-gray-800">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <?= bulan_indo('F Y', strtotime($data['bulan'] . '-01')) ?>
                        </h5>
                    </div>
                </div>
                <div class="row mb-4">

                    <!-- APPROVAL FOREMAN -->
                    <div class="col-md-6">
                        <div class="card border-left-primary shadow-sm">
                            <div class="card-body">

                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Approval Foreman
                                </div>

                                <?php if (empty($data['fr_uuid'])) : ?>

                                    <button type="button" class="btn btn-primary btn-sm" id="btnAccFr" onclick="accFr()">
                                        <i class="fa fa-check mr-1"></i>
                                        ACC Foreman
                                    </button>

                                <?php else : ?>

                                    <span class="text-success font-weight-bold">
                                        <i class="fa fa-check-circle mr-1"></i>
                                        <?= $data['fr_name'] ?>
                                    </span>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>


                    <!-- APPROVAL SPV -->
                    <?php if (!empty($data['fr_uuid'])) : ?>

                        <div class="col-md-6">
                            <div class="card border-left-success shadow-sm">
                                <div class="card-body">

                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Approval SPV
                                    </div>

                                    <?php if (empty($data['spv_uuid'])) : ?>

                                        <button type="button" class="btn btn-success btn-sm" id="btnAccSpv" onclick="accSpv()">
                                            <i class="fa fa-check mr-1"></i>
                                            ACC SPV
                                        </button>

                                    <?php else : ?>

                                        <span class="text-success font-weight-bold">
                                            <i class="fa fa-check-circle mr-1"></i>
                                            <?= $data['spv_name'] ?>
                                        </span>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <div class="row mt-3">
                    <div class="col mb-3">
                        <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                            <thead class="table bg-info text-light text-center">
                                <tr>
                                    <th class="align-middle" rowspan="3">Hari / Tanggal</th>
                                    <?php foreach ($data['tools'] as $tool) : ?>
                                        <th class="align-middle text-center" colspan="2">Kondisi (&#x2713;)</th>
                                    <?php endforeach; ?>
                                    <th class="align-middle text-center" rowspan="3">Keterangan</th>
                                </tr>
                                <tr>
                                    <?php foreach ($data['tools'] as $tool) : ?>
                                        <th class="align-middle text-center">Bersih</th>
                                        <th class="align-middle text-center">Kelengkapan</th>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <?php foreach ($data['tools'] as $tool) : ?>
                                        <th class="align-middle text-center" colspan="2"><?= $tool ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['data'] as $tanggal => $toolsData) : ?>
                                    <tr>
                                        <td><?= $tanggal ?></td>
                                        <?php foreach ($data['tools'] as $tool) : ?>
                                            <td class="text-center align-middle"><?= $toolsData[$tool]['kondisi'] ?></td>
                                            <td class="text-center align-middle"><?= $toolsData[$tool]['kelengkapan'] ?></td>
                                        <?php endforeach; ?>
                                        <td class="text-center align-middle">
                                            <?= $toolsData[array_key_first($toolsData)]['keterangan'] ?? '-' ?>
                                            <!-- Ambil keterangan pertama untuk setiap tanggal -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-2 mb-4">
                    <div class="col">
                        <a href="<?= base_url('tools_mesin/data') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function accFr() {

            if (!confirm('Apakah data ini yakin akan di-ACC oleh Foreman?')) {
                return;
            }

            $('#btnAccFr').prop('disabled', true);
            $('#btnAccFr').html('<i class="fa fa-spinner fa-spin mr-1"></i> Proses...');

            $.ajax({
                url: '<?= base_url("tools_mesin/acc_fr/" . $data["area_uuid"] . "/" . $data["bulan"]) ?>',
                type: 'POST',
                dataType: 'json',

                success: function(response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil di-ACC Foreman',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Data gagal di-ACC'
                        });

                        $('#btnAccFr').prop('disabled', false);
                        $('#btnAccFr').html(
                            '<i class="fa fa-check mr-1"></i> ACC Foreman'
                        );
                    }
                },

                error: function() {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat melakukan approval'
                    });

                    $('#btnAccFr').prop('disabled', false);
                    $('#btnAccFr').html(
                        '<i class="fa fa-check mr-1"></i> ACC Foreman'
                    );
                }
            });
        }
    </script>

    <script>
        function accSpv() {

            if (!confirm('Apakah data ini yakin akan di-ACC oleh SPV?')) {
                return;
            }

            $('#btnAccSpv').prop('disabled', true);
            $('#btnAccSpv').html('<i class="fa fa-spinner fa-spin mr-1"></i> Proses...');

            $.ajax({
                url: '<?= base_url("tools_mesin/acc_spv/" . $data["area_uuid"] . "/" . $data["bulan"]) ?>',
                type: 'POST',
                dataType: 'json',

                success: function(response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil di-ACC SPV',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Data gagal di-ACC'
                        });

                        $('#btnAccSpv').prop('disabled', false);
                        $('#btnAccSpv').html(
                            '<i class="fa fa-check mr-1"></i> ACC SPV'
                        );
                    }
                },

                error: function() {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat melakukan approval'
                    });

                    $('#btnAccSpv').prop('disabled', false);
                    $('#btnAccSpv').html(
                        '<i class="fa fa-check mr-1"></i> ACC SPV'
                    );
                }
            });
        }
    </script>