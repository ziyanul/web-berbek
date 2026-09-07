<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Sortasi</h1>
            <small class="text-muted">Ringkasan Sortasi per Batch</small>
        </div>
        <a href="<?= base_url('sortasi/tambah') ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Sortasi
        </a>
    </div>
    <?php if ($this->session->flashdata('success_msg')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('success_msg')) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_msg')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('error_msg')) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Batch</th>
                            <th>Varian</th>
                            <th class="text-right">WIP</th>
                            <th class="text-right">Release</th>
                            <th class="text-right">Sisa WIP</th>
                            <th width="110" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <a href="<?= base_url('sortasi/detail/' . $row->uuid) ?>" class="font-weight-bold">
                                            <?= html_escape($row->kode_batch ?: '-') ?>
                                        </a>
                                    </td>
                                    <td><?= html_escape($row->varian ?: '-') ?></td>
                                    <td class="text-right">
                                        <strong><?= number_format((float)$row->wip_awal_box, 0, ',', '.') ?></strong> Box<br>
                                        <small class="text-muted"><?= number_format((float)$row->wip_awal_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <strong><?= number_format((float)$row->release_box, 0, ',', '.') ?></strong> Box<br>
                                        <small class="text-muted"><?= number_format((float)$row->release_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?php if ((float)$row->sisa_wip_box > 0) : ?>
                                            <strong class="text-warning"><?= number_format((float)$row->sisa_wip_box, 0, ',', '.') ?></strong> Box<br>
                                            <small class="text-muted"><?= number_format((float)$row->sisa_wip_kg, 3, ',', '.') ?> Kg</small>
                                        <?php else : ?>
                                            <strong class="text-success">0</strong> Box<br>
                                            <small class="text-muted">0,000 Kg</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('sortasi/detail/' . $row->uuid) ?>"
                                            class="btn btn-info btn-sm btn-block"
                                            title="Detail Batch">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada batch yang masuk ke proses Sortasi.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
