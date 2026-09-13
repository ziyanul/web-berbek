<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">Detail Cuci</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= site_url('sortasi/cuci'); ?>">Cuci</a>
            </li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
    <!-- Informasi Cuci -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Informasi Cuci
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Kode Batch Hasil</th>
                            <td>
                                <strong>
                                    <?= html_escape($data->kode_batch_hasil); ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Varian</th>
                            <td>
                                <?= html_escape($data->varian); ?>
                                <?php if (!empty($data->varian_keterangan)): ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= html_escape($data->varian_keterangan); ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Cuci</th>
                            <td>
                                <strong>
                                    <?= number_format($data->jumlah_box_hasil, 0, ',', '.'); ?>
                                </strong>
                                BOX
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Tanggal</th>
                            <td>
                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime($data->created_at)
                                ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ((int)$data->status === 1): ?>
                                    <span class="badge badge-success">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">
                                        Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>
                                <?= !empty($data->keterangan)
                                    ? html_escape($data->keterangan)
                                    : '-'; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Sumber Cuci -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Sumber Cuci
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Kode Batch Sumber</th>
                            <th>Jumlah Cuci</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($detail as $item): ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <?= html_escape($item->kode_batch); ?>
                                        </strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>
                                            <?= number_format(
                                                $item->jumlah_box,
                                                0,
                                                ',',
                                                '.'
                                            ); ?>
                                        </strong>
                                        BOX
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3"
                                    class="text-center text-muted">
                                    Tidak ada detail sumber Cuci.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <a href="<?= site_url('sortasi/cuci'); ?>"
           class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i>
            Kembali
        </a>
        <a href="<?= site_url('sortasi/cuci_edit/' . $data->uuid); ?>"
           class="btn btn-warning">
            <i class="fa fa-edit"></i>
            Edit
        </a>
    </div>
</div>