<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Analisa Yield Produksi</h1>
        <button type="button" class="btn btn-success" id="btnExcel" disabled>
            <i class="fas fa-file-excel mr-1"></i> Download Excel
        </button>
    </div>

    <div class="card shadow mb-3">
        <div class="card-header py-2"><b>Filter Analisa</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_awal" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Plan Produksi</label>
                    <select class="form-control" id="plan">
                        <option value="">Semua Plan</option>
                        <?php foreach (($filter_options['plans'] ?? []) as $p): ?>
                            <option value="<?= html_escape($p['uuid']) ?>"><?= html_escape($p['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Kode Batch</label>
                    <select class="form-control" id="batch">
                        <option value="">Semua Batch</option>
                        <?php foreach (($filter_options['plans'] ?? []) as $p): ?>
                            <optgroup label="<?= html_escape($p['label']) ?>">
                                <?php foreach ($p['batches'] as $b): ?>
                                    <option value="<?= html_escape($b['uuid']) ?>"><?= html_escape($b['kode_batch']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Varian</label>
                    <select class="form-control" id="varian">
                        <option value="">Semua Varian</option>
                        <?php foreach (($filter_options['varian'] ?? []) as $v): ?>
                            <option value="<?= html_escape($v->uuid) ?>"><?= html_escape($v->varian) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Mesin</label>
                    <select class="form-control" id="mesin">
                        <option value="">Semua Mesin</option>
                        <?php foreach (($filter_options['mesin'] ?? []) as $m): ?>
                            <option value="<?= html_escape($m->uuid) ?>"><?= html_escape($m->nama_mesin) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Bad Produk</label>
                    <select class="form-control" id="badpro">
                        <option value="">Semua Bad Produk</option>
                        <?php foreach (($filter_options['badpro'] ?? []) as $b): ?>
                            <option value="<?= html_escape($b->uuid) ?>"><?= html_escape($b->nama_badpro) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary mr-2" id="btnTampilkan">
                        <i class="fa fa-search mr-1"></i> Tampilkan
                    </button>
                    <button class="btn btn-secondary" id="btnReset">
                        <i class="fa fa-sync-alt"></i> Reset
                    </button>
                </div>
            </div>
            <div id="filterHint" class="small text-muted mt-1"></div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <ul class="nav nav-tabs card-header-tabs" id="analisaTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="ringkasan-tab" data-toggle="tab" href="#ringkasan" role="tab">Ringkasan</a></li>
                    <li class="nav-item"><a class="nav-link" id="monitoring-tab" data-toggle="tab" href="#monitoring" role="tab">Monitoring Produksi</a></li>
                    <li class="nav-item" id="navBadProduk"><a class="nav-link" id="badproduk-tab" data-toggle="tab" href="#badproduk" role="tab">Bad Produk</a></li>
                    <li class="nav-item"><a class="nav-link" id="batch-tab" data-toggle="tab" href="#batch" role="tab">Detail Batch</a></li>
                </ul>
                <span class="badge badge-light ml-2" id="scopeBadge">Belum ditampilkan</span>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="ringkasan" role="tabpanel"><div id="ringkasan-container"><div class="text-center text-muted p-5">Silakan pilih filter kemudian klik <b>Tampilkan</b>.</div></div></div>
                <div class="tab-pane fade" id="monitoring" role="tabpanel"><div id="monitoring-container"></div></div>
                <div class="tab-pane fade" id="badproduk" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12"><div class="card shadow-sm mb-3"><div class="card-header py-2"><b>Bad Produk per Varian</b></div><div class="card-body p-0"><div id="badproduk-varian-container"></div></div></div></div>
                        <div class="col-lg-12"><div class="card shadow-sm"><div class="card-header py-2"><b>Bad Produk per Mesin</b></div><div class="card-body p-0"><div id="badproduk-mesin-container"></div></div></div></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="batch" role="tabpanel"><div id="detail-batch-container"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
const base_url = '<?= base_url() ?>';
const defaultAwal = '<?= date('Y-m-01') ?>';
const defaultAkhir = '<?= date('Y-m-d') ?>';
</script>
<script src="<?= base_url('assets/js/analisa-yield.js') ?>"></script>
