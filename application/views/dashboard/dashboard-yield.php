<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'CPI-Berbek' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
</head>
<style>
    body {
        background: #f4f6fb;
    }


    .dashboard-header {
        background: linear-gradient(135deg, #224abe, #4e73df);
        color: #fff;
        border-radius: 20px;
        padding: 18px 25px;
        margin-bottom: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .1);
    }

    .dashboard-header h2 {
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 5px;
    }

    /* ========================= */
    /* CARD */
    /* ========================= */
    .card {
        margin-bottom: 8px;
        border: 1px solid #18b8e8;
        border-radius: 3px;
        box-shadow: none !important;
    }

    .card-header {
        background: #163d6b;
        color: #fff;
        padding: 6px 10px;
        border-bottom: 2px solid #1cc0ef;
    }

    .card-header h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .card-body {
        padding: 0;
    }

    /* ========================= */
    /* TABLE */
    /* ========================= */
    .table {
        margin-bottom: 0;
    }

    .table th,
    .table td {
        padding: 4px 6px;
        font-size: 11px;
        border: 1px solid #d7d7d7;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
    }

    .table td {
        font-family: Consolas, monospace;
    }

    .table thead th {
        background: #1e2d3b;
        color: #fff;
        border-color: #10212f;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: .4px;
    }

    .table tbody td:first-child {
        text-align: left;
        font-weight: bold;
        font-family: "Segoe UI", sans-serif;
    }

    .table tbody tr:nth-child(even) {
        background: #fafafa;
    }

    .table-hover tbody tr:hover {
        background: inherit;
    }

    /* ========================= */
    /* TOTAL */
    /* ========================= */
    .table tfoot td {
        background: #dfefff;
        color: #003b63;
        font-weight: bold;
        font-family: "Segoe UI", sans-serif;
    }

    /* ========================= */
    /* STATUS WARNA */
    /* ========================= */
    .text-danger {
        font-weight: bold !important;
    }

    .text-success {
        font-weight: bold !important;
    }

    .text-warning {
        font-weight: bold !important;
    }

    /* ========================= */
    /* BADGE */
    /* ========================= */
    .badge {
        font-size: 11px;
        padding: 5px 10px;
    }

    /* ========================= */
    /* FOOTER */
    /* ========================= */
    footer.sticky-footer {
        padding: 3px 0 !important;
        font-size: 10px;
        color: #999;
        border-top: 1px solid #dcdcdc;
    }

    /* ========================= */
    /* RESPONSIVE */
    /* ========================= */
    .table-responsive {
        overflow-x: auto;
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: #b8b8b8;
    }

    ::-webkit-scrollbar-track {
        background: #efefef;
    }

    .bad-table th:first-child,
    .bad-table td:first-child {
        text-align: left;
        width: 170px;
        font-weight: 600;
    }

    .bad-table th:not(:first-child),
    .bad-table td:not(:first-child) {
        width: 70px;
        text-align: center;
    }

    .bad-table tbody tr:hover {
        background: #f5fbff;
    }

    .bad-table tfoot {
        background: #dfefff;
        font-weight: bold;
    }

    .bad-table td:last-child,
    .bad-table th:last-child {
        background: #f7f7f7;
        font-weight: bold;
    }
</style>

<body id="page-top">
    <div id="wrapper">
        <?php
        $subrole = $this->session->userdata('subrole');
        ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <a href="<?= base_url('portal') ?>" class="badge badge-info px-3 py-2">
                                <i class="fa fa-lg fa-home mr-1"></i>
                                PORTAL
                            </a>
                        </div>
                        <h2 class="font-weight-bold text-dark mb-0">
                            DASHBOARD YIELD</h2>

                        <div>
                            <span class="badge badge-info px-3 py-2 ml-1" id="clock">
                                <div><?= date('d F Y H:i:s'); ?></div>
                            </span>
                        </div>
                    </div>
                    <!-- ========================= -->
                    <!-- MONITORING YIELD -->
                    <!-- ========================= -->
                    <div class="card shadow border-0">
                        <div class="card-header text-white">
                            <h6>
                                Monitoring Produksi <?= bulan_indo(strtoupper(date('F Y'))); ?>
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2">Varian</th>
                                            <th rowspan="2">
                                                Adonan
                                            </th>
                                            <th colspan="2">
                                                Filling Karantina
                                            </th>
                                            <th colspan="3">
                                                Sortasi (Box)
                                            </th>
                                            <th colspan="2">
                                                Bad Produk Filkar (Kg)
                                            </th>
                                            <th colspan="2">
                                                Bad Produk Sortasi (Kg)
                                            </th>
                                            <th colspan="2">
                                                Yield (%)
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Box</th>
                                            <th>Kg</th>
                                            <th>Sortir</th>
                                            <th>Release</th>
                                            <th>Belum</th>
                                            <th>Rework</th>
                                            <th>Reject</th>
                                            <th>Rework</th>
                                            <th>Reject</th>
                                            <th>Filkar</th>
                                            <th>Release</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($monitoring as $row) {
                                        ?>
                                            <tr>
                                                <td><?= $row->nama_varian ?></td>
                                                <td><?= number_format((float)$row->adonan_formula, 2, '.', ',') ?></td>
                                                <td><?= $row->filkar_box ?></td>
                                                <td><?= number_format((float)$row->filkar_kg, 2, '.', ',') ?></td>
                                                <td><?= $row->sortasi_box ?></td>
                                                <td><?= $row->release_box ?></td>
                                                <td><?= $row->blm_sortir ?></td>
                                                <td><?= number_format((float)$row->filkar_rework, 2, '.', ',') ?></td>
                                                <td><?= number_format((float)$row->filkar_reject, 2, '.', ',') ?></td>
                                                <td><?= number_format((float)$row->sortasi_rework, 2, '.', ',') ?></td>
                                                <td><?= number_format((float)$row->sortasi_reject, 2, '.', ',') ?></td>
                                                <td><?= $row->yield_formula ?></td>
                                                <td><?= number_format($row->yield_release, 2, ".", ",") ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot class="bg-light text-center">
                                        <tr>
                                            <td>TOTAL</td>
                                            <td><?= number_format((float)$total->adonan_formula, 3, '.', ',') ?></td>
                                            <td><?= $total->filkar_box ?></td>
                                            <td><?= number_format((float)$total->filkar_kg, 3, '.', ',') ?></td>
                                            <td><?= $total->sortasi_box ?></td>
                                            <td><?= $total->release_box ?></td>
                                            <td><?= $total->blm_sortir ?></td>
                                            <td><?= number_format((float)$total->filkar_rework, 3, '.', ',') ?></td>
                                            <td><?= number_format((float)$total->filkar_reject, 3, '.', ',') ?></td>
                                            <td><?= number_format((float)$total->sortasi_rework, 3, '.', ',') ?></td>
                                            <td><?= number_format((float)$total->sortasi_reject, 3, '.', ',') ?></td>
                                            <td>26</td>
                                            <td>
                                                98.94%
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- BAGIAN 2 DIMULAI DARI SINI -->
                    <div class="row no-gutters">
                        <div class="col-lg-3 pr-1">
                            <!-- ========================= -->
                            <!-- BAD PRODUK PER VARIAN -->
                            <!-- ========================= -->
                            <div class="card shadow h-100">
                                <div class="card-header py-2">
                                    <h6 class="m-0 font-weight-bold text-danger">
                                        Bad Produk per Varian (Kg)
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover mb-0">
                                            <thead>

                                                <tr>

                                                    <th>Bad Produk</th>

                                                    <?php foreach ($varian as $v) { ?>

                                                        <th><?= $v->varian ?></th>

                                                    <?php } ?>

                                                    <th>Total</th>

                                                </tr>

                                            </thead>
                                            <tbody>

                                                <?php foreach ($bad_produk_varian as $row) { ?>

                                                    <tr>

                                                        <td><?= $row->nama_badpro ?></td>

                                                        <?php foreach ($varian as $v) { ?>

                                                            <td>

                                                                <?= number_format($row->{$v->varian}, 0) ?>

                                                            </td>

                                                        <?php } ?>

                                                        <td>

                                                            <b><?= number_format($row->total, 0) ?></b>

                                                        </td>

                                                    </tr>

                                                <?php } ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>

                                                    <td><strong>TOTAL</strong></td>

                                                    <?php

                                                    $grandTotal = 0;

                                                    foreach ($varian as $v) {

                                                        $totalVarian = 0;

                                                        foreach ($bad_produk_varian as $row) {
                                                            $totalVarian += $row->{$v->varian};
                                                        }

                                                        $grandTotal += $totalVarian;
                                                    ?>

                                                        <td class="text-center">
                                                            <strong><?= number_format($totalVarian, 0) ?></strong>
                                                        </td>

                                                    <?php } ?>

                                                    <td class="text-center">
                                                        <strong><?= number_format($grandTotal, 0) ?></strong>
                                                    </td>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 pl-1">
                            <!-- ========================= -->
                            <!-- BAD PRODUK PER MESIN -->
                            <!-- ========================= -->
                            <div class="card shadow h-100">
                                <div class="card-header py-2">
                                    <h6 class="m-0 font-weight-bold text-warning">
                                        Bad Produk per Mesin Filler (Kg)
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover mb-0 text-center">

                                            <thead class="thead-dark">
                                                <tr>

                                                    <th width="100">Mesin</th>

                                                    <?php foreach ($badproduk as $bp) { ?>
                                                        <th><?= $bp->nama_badpro ?></th>
                                                    <?php } ?>

                                                    <th width="80">TOTAL</th>

                                                </tr>
                                            </thead>

                                            <tbody>

                                                <?php foreach ($bad_produk_mesin as $row) { ?>

                                                    <tr>

                                                        <td class="text-left font-weight-bold">
                                                            <?= $row->mesin ?>
                                                        </td>

                                                        <?php foreach ($badproduk as $bp) { ?>

                                                            <td>
                                                                <?= number_format($row->{$bp->nama_badpro}, 0) ?>
                                                            </td>

                                                        <?php } ?>

                                                        <td class="font-weight-bold">
                                                            <?= number_format($row->total, 0) ?>
                                                        </td>

                                                    </tr>

                                                <?php } ?>

                                            </tbody>

                                            <tfoot>

                                                <tr>

                                                    <td class="font-weight-bold">
                                                        TOTAL
                                                    </td>

                                                    <?php

                                                    $grandTotal = 0;

                                                    foreach ($badproduk as $bp) {

                                                        $total = 0;

                                                        foreach ($bad_produk_mesin as $row) {
                                                            $total += $row->{$bp->nama_badpro};
                                                        }

                                                        $grandTotal += $total;

                                                    ?>

                                                        <td class="font-weight-bold">
                                                            <?= number_format($total, 0) ?>
                                                        </td>

                                                    <?php } ?>

                                                    <td class="font-weight-bold">
                                                        <?= number_format($grandTotal, 0) ?>
                                                    </td>

                                                </tr>

                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 pl-1">
                            <div class="card shadow h-100">
                                <div class="card-header py-2">
                                    <h6 class="m-0 font-weight-bold text-light">
                                        Shortcut fitur
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <a href="<?= base_url('filler/planning') ?>" class="btn btn-md btn-block btn-success shadow-sm mb-2 mt-3"><i class="fa fa-list fa-sm text-white mr-2"></i> Planning Produksi</a>
                                    <a href="<?= base_url('mpusage/') ?>" class="btn btn-md btn-block btn-info shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Data MP</a>
                                    <a href="<?= base_url('counter/') ?>" class="btn btn-md btn-block btn-warning shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Data Filler</a>
                                    <a href="<?= base_url('filkar/') ?>" class="btn btn-md btn-block btn-primary shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Data Filkar</a>
                                    <a href="<?= base_url('sortasi/') ?>" class="btn btn-md btn-block btn-secondary shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Data Packing </a>
                                    <a href="<?= base_url('varian/') ?>" class="btn btn-md btn-block btn-primary shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Master Data Varian</a>
                                    <a href="<?= base_url('yieldportal/analisa') ?>" class="btn btn-md btn-block btn-danger shadow-sm mb-2"><i class="fa fa-list fa-sm text-white mr-2"></i> Analisa</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>
                    Copyright &copy;
                    PT Charoen Pokphand Indonesia - Plant Berbek | 2024
                </span>
            </div>
        </div>
    </footer>
    </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Konfirmasi Logout
                    </h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari sistem?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
    <script>
        setInterval(function() {
            location.reload();
        }, 3600000);
    </script>
    <script>
        function updateClock() {
            const now = new Date();

            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            const time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');;

            document.getElementById('clock').innerHTML =
                `<div>${date} ${time}</div>`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>