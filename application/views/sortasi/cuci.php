<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                Data Cuci
            </h4>
            <small class="text-muted">
                Daftar output Sortasi yang belum selesai dicuci.
            </small>
        </div>
        <a
            href="<?= site_url('sortasi/cuci_tambah'); ?>"
            class="btn btn-primary"
        >
            <i class="fa fa-plus"></i>
            Tambah Cuci
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Batch</th>
                            <th>Varian</th>
                            <th>Output CUCI</th>
                            <th>Sudah Dicuci</th>
                            <th>Sisa Belum Dicuci</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td>
                                        <?= $no++; ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <?= html_escape($row->kode_batch); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?= html_escape($row->varian); ?>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format(
                                            $row->jumlah_output,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                        BOX
                                    </td>
                                    <td class="text-end">
                                        <?= number_format(
                                            $row->sudah_dicuci,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                        BOX
                                    </td>
                                    <td class="text-end">
                                        <strong>
                                            <?= number_format(
                                                $row->sisa_belum_dicuci,
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
                                <td
                                    colspan="6"
                                    class="text-center text-muted"
                                >
                                    Tidak ada output yang perlu dicuci.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>