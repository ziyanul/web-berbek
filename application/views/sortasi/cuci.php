<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">
        Cuci
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                Cuci
            </li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">
                        Data Cuci
                    </h5>
                    <small class="text-muted">
                        Riwayat hasil proses Cuci.
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
            <div class="table-responsive">
                <table
                    class="table table-bordered"
                    id="datatables"
                    width="100%"
                    cellspacing="0"
                >
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Batch Hasil</th>
                            <th>Varian</th>
                            <th>Jumlah Cuci</th>
                            <th>Sumber</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
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
                                            <?= html_escape(
                                                $row->kode_batch_hasil
                                            ); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?= html_escape(
                                            $row->varian
                                        ); ?>
                                        <?php if (!empty($row->varian_keterangan)): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= html_escape(
                                                    $row->varian_keterangan
                                                ); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <strong>
                                            <?= number_format(
                                                $row->jumlah_box_hasil,
                                                0,
                                                ',',
                                                '.'
                                            ); ?>
                                        </strong>
                                        BOX
                                    </td>
                                    <td class="text-center">
                                        <?= number_format(
                                            $row->jumlah_sumber,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                        batch
                                    </td>
                                    <td>
                                        <?= date(
                                            'd-m-Y H:i',
                                            strtotime(
                                                $row->created_at
                                            )
                                        ); ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('sortasi/cuci_detail/' . $row->uuid); ?>" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('sortasi/cuci_edit/' . $row->uuid); ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= site_url('sortasi/cuci_hapus/' . $row->uuid); ?>"
                                        class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi Cuci ini?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted"
                                >
                                    Belum ada transaksi Cuci.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>