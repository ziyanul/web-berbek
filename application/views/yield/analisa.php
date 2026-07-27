<div class="container-fluid">
    <!-- Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">
            Analisa Yield Produksi
        </h1>
    </div>
    <!-- ================= FILTER ================= -->
    <div class="card shadow mb-3">
        <div class="card-header py-2">
            <b>Filter Analisa</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Dari Tanggal</label>
                    <input
                        type="date"
                        class="form-control"
                        id="tanggal_awal"
                        value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Sampai Tanggal</label>
                    <input
                        type="date"
                        class="form-control"
                        id="tanggal_akhir"
                        value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Varian</label>
                    <select class="form-control" id="varian">
                        <option value="">
                            Semua Varian
                        </option>
                        <?php foreach ($varian as $v) { ?>
                            <option value="<?= $v->uuid ?>">
                                <?= $v->varian ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Mesin</label>
                    <select class="form-control" id="mesin">
                        <option value="">
                            Semua Mesin
                        </option>
                        <?php foreach ($mesin as $m) { ?>
                            <option value="<?= $m->uuid ?>">
                                <?= $m->nama_mesin ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Bad Produk</label>
                    <select class="form-control" id="badpro">
                        <option value="">
                            Semua Bad Produk
                        </option>
                        <?php foreach ($badpro as $b) { ?>
                            <option value="<?= $b->uuid ?>">
                                <?= $b->nama_badpro ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
                    <button
                        class="btn btn-primary btn-block mr-2"
                        id="btnTampilkan">
                        <i class="fa fa-search mr-1"></i>
                        Tampilkan
                    </button>
                    <button
                        class="btn btn-secondary btn-block"
                        id="btnReset">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ================= HASIL ANALISA ================= -->
    <div class="card shadow">
        <div class="card-header py-2">
            <ul class="nav nav-tabs card-header-tabs" id="analisaTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active"
                        id="ringkasan-tab"
                        data-toggle="tab"
                        href="#ringkasan"
                        role="tab">
                        Ringkasan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        id="monitoring-tab"
                        data-toggle="tab"
                        href="#monitoring"
                        role="tab">
                        Monitoring Produksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        id="badproduk-tab"
                        data-toggle="tab"
                        href="#badproduk"
                        role="tab">
                        Bad Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        id="batch-tab"
                        data-toggle="tab"
                        href="#batch"
                        role="tab">
                        Detail Batch
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- ================= RINGKASAN ================= -->
                <div class="tab-pane fade show active"
                    id="ringkasan"
                    role="tabpanel">
                    <div id="ringkasan-container">
                        <div class="text-center text-muted p-5">
                            Silakan pilih filter kemudian klik
                            <b>Tampilkan</b>
                        </div>
                    </div>
                </div>
                <!-- ================= MONITORING ================= -->
                <div class="tab-pane fade"
                    id="monitoring"
                    role="tabpanel">
                    <div id="monitoring-container">
                    </div>
                </div>
                <!-- ================= BAD PRODUK ================= -->
                <div class="tab-pane fade"
                    id="badproduk"
                    role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header py-2">
                                    <b>Bad Produk per Varian</b>
                                </div>
                                <div class="card-body p-0">
                                    <div id="badproduk-varian-container">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header py-2">
                                    <b>Bad Produk per Mesin</b>
                                </div>
                                <div class="card-body p-0">
                                    <div id="badproduk-mesin-container">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ================= DETAIL BATCH ================= -->
                <div class="tab-pane fade"
                    id="batch"
                    role="tabpanel">
                    <div id="detail-batch-container">
                    </div>
                </div>
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