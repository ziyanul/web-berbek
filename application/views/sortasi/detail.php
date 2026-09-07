<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Sortasi Batch</h1>
            <small class="text-muted">Seluruh riwayat dan neraca material batch</small>
        </div>
        <a href="<?= base_url('sortasi') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <!-- IDENTITAS BATCH -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-layer-group mr-1"></i> Informasi Batch
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Kode Batch</small>
                    <strong class="h5"><?= html_escape($batch->kode_batch ?: '-') ?></strong>
                </div>
                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Varian</small>
                    <strong><?= html_escape($batch->varian ?: '-') ?></strong>
                </div>
                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Berat / Box</small>
                    <strong><?= number_format((float)$batch->box_kg, 3, ',', '.') ?> Kg</strong>
                </div>
                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Adonan</small>
                    <strong><?= number_format((float)$batch->adonan, 3, ',', '.') ?> Kg</strong>
                </div>
            </div>
            <?php if (!empty($batch->varian_keterangan)) : ?>
                <div class="border-top pt-3">
                    <small class="text-muted">Keterangan Varian</small><br>
                    <?= html_escape($batch->varian_keterangan) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RINGKASAN -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">WIP Awal Filkar</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format((float)$batch->filkar_box, 3, ',', '.') ?> Box
                    </div>
                    <small class="text-muted"><?= number_format((float)$batch->wip_awal_kg, 3, ',', '.') ?> Kg</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Diproses Sortasi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format((float)$batch->total_sortasi_box, 3, ',', '.') ?> Box
                    </div>
                    <small class="text-muted"><?= number_format((float)$batch->total_sortasi_kg, 3, ',', '.') ?> Kg</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Release</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format((float)$batch->release_box, 3, ',', '.') ?> Box
                    </div>
                    <small class="text-muted"><?= number_format((float)$batch->release_kg, 3, ',', '.') ?> Kg</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sisa WIP</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format((float)$batch->sisa_wip_box, 3, ',', '.') ?> Box
                    </div>
                    <small class="text-muted"><?= number_format((float)$batch->sisa_wip_kg, 3, ',', '.') ?> Kg</small>
                </div>
            </div>
        </div>
    </div>

    <!-- OUTPUT SORTASI -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-pie mr-1"></i> Ringkasan Hasil Sortasi
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Komponen</th>
                            <th class="text-right">Box</th>
                            <th class="text-right">Kg</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>WIP Awal Filkar</strong></td>
                            <td class="text-right"><?= number_format((float)$batch->filkar_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->wip_awal_kg, 3, ',', '.') ?></td>
                            <td>Material awal yang masuk ke Sortasi dari Filkar.</td>
                        </tr>
                        <tr>
                            <td>Release</td>
                            <td class="text-right"><?= number_format((float)$batch->release_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->release_kg, 3, ',', '.') ?></td>
                            <td>Produk release.</td>
                        </tr>
                        <tr>
                            <td>Tampung</td>
                            <td class="text-right"><?= number_format((float)$batch->tampung_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->tampung_kg, 3, ',', '.') ?></td>
                            <td>Menjadi WIP Tampung dan dapat disortasi kembali.</td>
                        </tr>
                        <tr>
                            <td>Kasar</td>
                            <td class="text-right"><?= number_format((float)$batch->kasar_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->kasar_kg, 3, ',', '.') ?></td>
                            <td>Menjadi WIP Kasar dan dapat disortasi kembali.</td>
                        </tr>
                        <tr>
                            <td>Cuci</td>
                            <td class="text-right"><?= number_format((float)$batch->cuci_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->cuci_kg, 3, ',', '.') ?></td>
                            <td>Output Cuci.</td>
                        </tr>
                        <tr>
                            <td>Bad Produk</td>
                            <td class="text-right">-</td>
                            <td class="text-right"><?= number_format((float)$batch->bad_kg, 3, ',', '.') ?></td>
                            <td>Dicatat dalam Kg.</td>
                        </tr>
                        <tr class="table-warning font-weight-bold">
                            <td>Sisa WIP</td>
                            <td class="text-right"><?= number_format((float)$batch->sisa_wip_box, 3, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)$batch->sisa_wip_kg, 3, ',', '.') ?></td>
                            <td>Masih tersedia untuk proses Sortasi berikutnya.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- NERACA -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-balance-scale mr-1"></i> Neraca Material
            </h6>
        </div>
        <div class="card-body">
            <div class="alert <?= abs((float)$batch->selisih_neraca_kg) < 0.01 ? 'alert-success' : 'alert-danger' ?> mb-0">
                <div class="font-weight-bold mb-2">
                    WIP Awal = Release + Tampung + Kasar + Cuci + Bad + Sisa WIP
                </div>
                <div>
                    <?= number_format((float)$batch->wip_awal_kg, 3, ',', '.') ?> Kg
                    =
                    <?= number_format((float)$batch->release_kg, 3, ',', '.') ?> +
                    <?= number_format((float)$batch->tampung_kg, 3, ',', '.') ?> +
                    <?= number_format((float)$batch->kasar_kg, 3, ',', '.') ?> +
                    <?= number_format((float)$batch->cuci_kg, 3, ',', '.') ?> +
                    <?= number_format((float)$batch->bad_kg, 3, ',', '.') ?> +
                    <?= number_format((float)$batch->sisa_wip_kg, 3, ',', '.') ?>
                </div>
                <div class="mt-2">
                    <strong>Selisih: <?= number_format((float)$batch->selisih_neraca_kg, 3, ',', '.') ?> Kg</strong>
                </div>
            </div>
            <small class="text-muted d-block mt-2">
                Total Diproses Sortasi dapat lebih besar dari WIP awal apabila ada WIP Tampung/Kasar yang disortasi kembali.
            </small>
        </div>
    </div>

    <!-- WIP AKTIF -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-boxes mr-1"></i> WIP yang Masih Tersedia
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Jenis WIP</th>
                            <th>Sumber</th>
                            <th class="text-right">Awal</th>
                            <th class="text-right">Terpakai</th>
                            <th class="text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($wip_ledger)) : ?>
                            <?php foreach ($wip_ledger as $wip) : ?>
                                <tr>
                                    <td>
                                        <?php if ($wip->jenis_wip === 'BELUM_SORTIR') : ?>
                                            <span class="badge badge-secondary">BELUM SORTIR</span>
                                        <?php elseif ($wip->jenis_wip === 'TAMPUNG') : ?>
                                            <span class="badge badge-info">TAMPUNG</span>
                                        <?php else : ?>
                                            <span class="badge badge-warning">KASAR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($wip->source_sortasi_uuid)) : ?>
                                            Hasil Sortasi<br>
                                            <small class="text-muted">
                                                <?= !empty($wip->source_created_at) ? tanggal_indo($wip->source_created_at) : '-' ?>
                                            </small>
                                        <?php else : ?>
                                            Filkar / WIP Awal
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$wip->jumlah_awal, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$wip->jumlah_awal_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$wip->jumlah_terpakai, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$wip->jumlah_terpakai_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        <?= number_format((float)$wip->sisa_wip, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$wip->sisa_wip_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Tidak ada WIP tersisa.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIWAYAT TRANSAKSI -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Riwayat Transaksi Sortasi
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th class="text-right">WIP</th>
                            <th class="text-right">Release</th>
                            <th class="text-right">Tampung</th>
                            <th class="text-right">Kasar</th>
                            <th class="text-right">Cuci</th>
                            <th class="text-right">Bad</th>
                            <th>MP</th>
                            <th>Operator</th>
                            <th width="110">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($history)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($history as $h) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= !empty($h->created_at) ? tanggal_indo($h->created_at) : '-' ?><br>
                                        <small class="text-muted">
                                            <?= html_escape($h->jam_mulai ?: '-') ?> - <?= html_escape($h->jam_selesai ?: '-') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <strong><?= html_escape($h->jenis_sortasi_kode) ?></strong><br>
                                        <small class="text-muted"><?= html_escape($h->jenis_sortasi_nama) ?></small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->jumlah_wip, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$h->jumlah_wip_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->release_box, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$h->release_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->tampung_box, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$h->tampung_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->kasar_box, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$h->kasar_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->cuci_box, 3, ',', '.') ?> Box<br>
                                        <small class="text-muted"><?= number_format((float)$h->cuci_kg, 3, ',', '.') ?> Kg</small>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format((float)$h->bad_kg, 3, ',', '.') ?> Kg
                                    </td>
                                    <td class="text-center"><?= (int)$h->jml_mp ?></td>
                                    <td><?= html_escape($h->fullname ?: '-') ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('sortasi/edit/' . $h->uuid) ?>"
                                            class="btn btn-warning btn-sm"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('sortasi/hapus/' . $h->uuid) ?>"
                                            class="btn btn-danger btn-sm btn-hapus-sortasi"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php if (!empty($h->keterangan)) : ?>
                                    <tr class="bg-light">
                                        <td></td>
                                        <td colspan="11">
                                            <small><strong>Keterangan:</strong> <?= html_escape($h->keterangan) ?></small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">Belum ada transaksi Sortasi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BAD PRODUK -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-exclamation-triangle mr-1"></i> Detail Bad Produk Batch
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Bad Produk</th>
                            <th>Kategori</th>
                            <th class="text-right">Berat</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($badpro)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($badpro as $bp) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= !empty($bp->created_at) ? tanggal_indo($bp->created_at) : '-' ?></td>
                                    <td><?= html_escape($bp->nama_badpro ?: '-') ?></td>
                                    <td>
                                        <?php if ((int)$bp->kategori === 1) : ?>
                                            <span class="badge badge-info">Rework</span>
                                        <?php elseif ((int)$bp->kategori === 2) : ?>
                                            <span class="badge badge-danger">Reject</span>
                                        <?php else : ?>
                                            <span class="badge badge-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right"><?= number_format((float)$bp->berat, 3, ',', '.') ?> Kg</td>
                                    <td><?= html_escape($bp->keterangan ?: '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada Bad Produk pada batch ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('.btn-hapus-sortasi').on('click', function(e) {
        if (!confirm('Yakin ingin menghapus transaksi Sortasi ini?')) {
            e.preventDefault();
        }
    });
});
</script>
