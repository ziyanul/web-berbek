<div class="container-fluid">

    <h1 class="h3 mb-3 text-gray-800">
        Detail Sortasi
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('sortasi') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Sortasi
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail
            </li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-primary text-white">

                    Informasi Batch

                </div>

                <div class="card-body">

                    <table class="table table-borderless table-sm">

                        <tr>
                            <th width="35%">Kode Batch</th>
                            <td><?= $data->kode_batch ?></td>
                        </tr>

                        <tr>
                            <th>Varian</th>
                            <td><?= $data->varian ?></td>
                        </tr>

                        <tr>
                            <th>Box / Kg</th>
                            <td><?= number_format($data->box_kg,2) ?></td>
                        </tr>

                        <tr>
                            <th>Keterangan</th>
                            <td><?= $data->varian_keterangan ?></td>
                        </tr>

                        <tr>
                            <th>User</th>
                            <td><?= $data->fullname ?></td>
                        </tr>

                        <tr>
                            <th>Tanggal</th>
                            <td><?= date('d-m-Y H:i',strtotime($data->created_at)) ?></td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <?php

        $rework = $data->bad_sortasi_rework_kg ?? 0;
        $reject = $data->bad_sortasi_reject_kg ?? 0;

        $total_bad = $rework + $reject;

        $total_sortasi_kg = ($data->jumlah_wip ?? 0) * ($data->box_kg ?? 0);

        $persen_bad = ($total_sortasi_kg > 0)
        ? ($total_bad / $total_sortasi_kg) * 100
        : 0;

        $sisa_wip = ($data->jumlah_wip ?? 0) - ($data->jml_release ?? 0);

        ?>

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-success text-white">
                    <b>Ringkasan Sortasi</b>
                </div>

                <div class="card-body">

                    <table class="table table-borderless table-sm">

                        <tr>
                            <th width="45%">Jumlah Sortasi</th>
                            <td class="text-right">
                                <b><?= $data->jumlah_wip ?> Box</b>
                            </td>
                        </tr>

                        <tr>
                            <th>Release</th>
                            <td class="text-right">
                                <?= $data->jml_release ?> Box
                            </td>
                        </tr>
                        <tr>
                            <th>Sisa Sortasi</th>
                            <td class="text-right">
                                <?=
                                $sisa_sortasi =
                                $data->filkar_box -
                                $data->sortasi_box;
                                ?>
                                <tr>
                                    <td colspan="2"><hr class="my-2"></td>
                                </tr>

                                <tr>
                                    <th>Bad Rework</th>
                                    <td class="text-right">
                                        <?= number_format($rework,2) ?> Kg
                                    </td>
                                </tr>

                                <tr>
                                    <th>Bad Reject</th>
                                    <td class="text-right">
                                        <?= number_format($reject,2) ?> Kg
                                    </td>
                                </tr>

                                <tr class="font-weight-bold">
                                    <th>Total Bad</th>
                                    <td class="text-right text-danger">
                                        <?= number_format($total_bad,2) ?> Kg
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"><hr class="my-2"></td>
                                </tr>

                                <tr class="font-weight-bold">
                                    <th>Persentase Bad</th>
                                    <td class="text-right <?= ($persen_bad > 5 ? 'text-danger' : 'text-success') ?>">
                                        <?= number_format($persen_bad,2) ?> %
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow">

                <div class="card-header bg-info">

                    <b>Data Bad Produk</b>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead class="bg-secondary">

                                <tr>

                                    <th width="5%">No</th>

                                    <th>Bad Produk</th>

                                    <th width="20%">Kategori</th>

                                    <th width="20%">Berat</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if(empty($badpro)): ?>

                                    <tr>

                                        <td colspan="4" class="text-center">

                                            Tidak ada data.

                                        </td>

                                    </tr>

                                <?php else: ?>

                                    <?php $no=1; ?>

                                    <?php foreach($badpro as $bp): ?>

                                        <tr>

                                            <td class="text-center"><?= $no++ ?></td>

                                            <td><?= $bp->nama_badpro ?></td>

                                            <td><?= $bp->kategori_nama ?></td>

                                            <td class="text-right">

                                                <?= number_format($bp->berat,2) ?> Kg

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="mt-3">
               <a href="<?= base_url('sortasi') ?>"
                class="btn btn-danger">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
