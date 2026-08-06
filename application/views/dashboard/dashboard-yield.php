<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'PROD.IO Yield Dashboard' ?></title>
    <!-- ICON -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">
    <!-- FONT AWESOME -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <!-- SB ADMIN -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <!-- JQUERY -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
</head>
<style>
    /* =====================================================
   GLOBAL
===================================================== */
    body {
        background: #f4f6fb;
        font-family:
            "Segoe UI",
            Arial,
            sans-serif;
        color: #343a40;
    }
    .container-fluid {
        padding-left: 12px;
        padding-right: 12px;
    }
    #wrapper {
        min-height: 100vh;
    }
    #content-wrapper {
        background: #f4f6fb;
    }
    /* =====================================================
   HEADER
===================================================== */
    .dashboard-top {
        background: white;
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 0px;
        box-shadow:
            0 3px 12px rgba(0, 0, 0, .08);
    }
    .dashboard-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: .5px;
        color: #163d6b;
    }
    .portal-btn {
        font-size: 12px;
        font-weight: 700;
        padding:
            7px 15px;
        border-radius: 20px;
    }
    .clock-box {
        font-size: 12px;
        font-weight: 600;
        padding:
            7px 15px;
        border-radius: 20px;
    }
    /* =====================================================
   CARD
===================================================== */
    .dashboard-card {
        background: white;
        border-radius: 8px;
        border:
            1px solid #d7e5ef;
        box-shadow:
            0 2px 8px rgba(0, 0, 0, .06);
        margin-bottom: 5px;
        overflow: hidden;
    }
    .dashboard-card-header {
        background: #163d6b;
        color: white;
        padding: 4px 10px;
        border-bottom:
            2px solid #1cc0ef;
    }
    .dashboard-card-header h6 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .dashboard-card-body {
        padding: 0;
    }
    /* =====================================================
   TABLE GENERAL
===================================================== */
    .table-dashboard {
        margin-bottom: 0;
    }
    .table-dashboard th {
        background: #263746;
        vertical-align: middle !important;
        text-align: center;
        color: white;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding:
            5px 6px;
        border:
            1px solid #d5dce2;
    }
    .table-dashboard td {
        font-size: 11px;
        padding:
            3px 4px;
        border:
            1px solid #d8d8d8;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    .table-dashboard tbody td {
        font-family:
            Consolas,
            monospace;
    }
    .table-dashboard tbody td:first-child {
        text-align: left;
        font-family:
            "Segoe UI",
            sans-serif;
        font-weight: 600;
    }
    .table-dashboard tbody tr:nth-child(even) {
        background: #fafafa;
    }
    .table-dashboard tbody tr:hover {
        background: #eef8ff;
    }
    .table-dashboard tfoot td {
        background: #dcecff;
        color: #003b63;
        font-weight: 700;
        font-family:
            "Segoe UI",
            sans-serif;
    }
    /* =====================================================
   TWO COLUMN MONITORING
===================================================== */
    .monitoring-row {
        display: flex;
        gap: 10px;
        margin-bottom: 3px;
    }
    .monitoring-row .dashboard-card {
        flex: 1;
        margin-bottom: 0;
    }
    @media(max-width:992px) {
        .monitoring-row {
            flex-direction: column;
        }
    }
    /* =====================================================
   BAD PRODUK VARIAN
===================================================== */
    .bad-varian-table th:first-child,
    .bad-varian-table td:first-child {
        width: 170px;
    }
    .bad-varian-table td:not(:first-child),
    .bad-varian-table th:not(:first-child) {
        text-align: center;
        min-width: 65px;
    }
    /* =====================================================
   BAD PRODUK MESIN
===================================================== */
    .bad-mesin-table th,
    .bad-mesin-table td {
        font-size: 10px;
        padding:
            4px 5px;
    }
    .bad-mesin-table th:first-child,
    .bad-mesin-table td:first-child {
        min-width: 90px;
        text-align: left;
    }
    .bad-mesin-table td {
        font-family:
            Consolas,
            monospace;
    }
    .bad-mesin-table tfoot td {
        background: #dcecff;
        font-weight: bold;
    }
    /* =====================================================
   SHORTCUT
===================================================== */
    .shortcut-btn {
        width: 90%;
        margin:
            5px auto;
        font-size: 12px;
        padding:
            8px 5px;
        border-radius: 6px;
    }
    .shortcut-btn i {
        width: 15px;
    }
    /* =====================================================
   BADGE
===================================================== */
    .badge-dashboard {
        font-size: 11px;
        padding:
            6px 12px;
        border-radius: 20px;
    }
    /* =====================================================
   RESPONSIVE
===================================================== */
    .table-responsive {
        overflow-x: auto;
    }
    ::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }
    ::-webkit-scrollbar-thumb {
        background: #b5b5b5;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #eee;
    }
    /* =====================================================
   FOOTER
===================================================== */
    footer.sticky-footer {
        margin-top: 1px;
        padding:
            5px 0 !important;
        font-size: 10px;
        color: #888;
    }
</style>
<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <!-- =====================================================
HEADER
===================================================== -->
                    <div class="dashboard-top d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?= base_url('portal') ?>" class="btn btn-info portal-btn">
                                <i class="fa fa-home mr-1"></i>
                                PORTAL
                            </a>
                        </div>
                        <div class="dashboard-title">
                            DASHBOARD YIELD
                        </div>
                        <div>
                            <span class="badge badge-info clock-box" id="clock">
                                <?= date('d F Y H:i:s'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="monitoring-row">
                        <!-- =====================================================
                        MONITORING FILKAR
                        ===================================================== -->
                        <div class="dashboard-card">
                            <div class="dashboard-card-header">
                                <h6>
                                    Monitoring Filkar
                                </h6>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="table-responsive">
                                    <table class="table table-dashboard">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">
                                                    Varian
                                                </th>
                                                <th rowspan="2">
                                                    Adonan Kg
                                                </th>
                                                <th colspan="2">
                                                    Filkar
                                                </th>
                                                <th colspan="2">
                                                    Bad Produk Filkar
                                                </th>
                                                <th rowspan="2">
                                                    Yield %
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Box
                                                </th>
                                                <th>
                                                    Kg
                                                </th>
                                                <th>
                                                    Rework
                                                </th>
                                                <th>
                                                    Reject
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($monitoring_filkar)) : ?>
                                                <?php foreach ($monitoring_filkar as $row) : ?>
                                                    <tr>
                                                        <td>
                                                            <?= $row->nama_varian ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->adonan_formula, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->filkar_box, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->filkar_kg, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->filkar_rework, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->filkar_reject, 2) ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format((float)$row->yield_formula, 2) ?> %
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="7">
                                                        Belum ada data Filkar bulan ini
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>TOTAL</td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->adonan, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->filkar_box) ?>
                                                </td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->filkar_kg, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->filkar_rework, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->filkar_reject, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format((float)$total_filkar->yield_formula, 2) ?> %
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- =====================================================
MONITORING SORTASI
===================================================== -->
                        <div class="dashboard-card">
                            <div class="dashboard-card-header">
                                <h6>
                                    Monitoring Sortasi
                                </h6>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="table-responsive">
                                    <table class="table table-dashboard">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">
                                                    Varian
                                                </th>
                                                <th colspan="3">
                                                    Sortasi
                                                </th>
                                                <th colspan="3">
                                                    Bad Produk
                                                </th>
                                                <th rowspan="2">
                                                    Yield %
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Sortir Box
                                                </th>
                                                <th>
                                                    Release
                                                </th>
                                                <th>
                                                    Belum
                                                </th>
                                                <th>
                                                    Rework
                                                </th>
                                                <th>
                                                    Reject
                                                </th>
                                                <th>
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($monitoring_sortasi as $row) : ?>
                                                <tr>
                                                    <td>
                                                        <?= $row->nama_varian ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->sortasi_box) ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->release_box) ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->blm_sortir) ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->sortasi_rework, 2) ?>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->sortasi_reject, 2) ?>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            <?= number_format($row->sortasi_bad, 2) ?>
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <?= number_format($row->yield_sortasi, 2) ?> %
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>TOTAL</td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_box) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->release_box) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->blm_sortir) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_rework, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_reject, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_bad, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->yield_sortasi, 2) ?> %
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =====================================================
BAGIAN BAD PRODUK
===================================================== -->
                    <div class="row">
                        <!-- =====================================================
BAD PRODUK PER VARIAN
===================================================== -->
                        <div class="col-lg-3 pr-lg-1">
                            <div class="dashboard-card h-100">
                                <div class="dashboard-card-header">
                                    <h6>
                                        Bad Produk per Varian (Kg)
                                    </h6>
                                </div>
                                <div class="dashboard-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-dashboard bad-varian-table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Bad Produk
                                                    </th>
                                                    <?php foreach ($varian as $v) : ?>
                                                        <th>
                                                            <?= $v->varian ?>
                                                        </th>
                                                    <?php endforeach; ?>
                                                    <th>
                                                        Total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($bad_produk_varian as $row) : ?>
                                                    <tr>
                                                        <td>
                                                            <?= $row->nama_badpro ?>
                                                        </td>
                                                        <?php foreach ($varian as $v) : ?>
                                                            <td>
                                                                <?= number_format(
                                                                    $row->{$v->varian} ?? 0,
                                                                    0
                                                                ) ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                        <td>
                                                            <b>
                                                                <?= number_format(
                                                                    $row->total ?? 0,
                                                                    0
                                                                ) ?>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        TOTAL
                                                    </td>
                                                    <?php
                                                    $grandTotal = 0;
                                                    foreach ($varian as $v) :
                                                        $totalVarian = 0;
                                                        foreach ($bad_produk_varian as $row) {
                                                            $totalVarian +=
                                                                $row->{$v->varian} ?? 0;
                                                        }
                                                        $grandTotal += $totalVarian;
                                                    ?>
                                                        <td>
                                                            <b>
                                                                <?= number_format(
                                                                    $totalVarian,
                                                                    0
                                                                ) ?>
                                                            </b>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td>
                                                        <b>
                                                            <?= number_format(
                                                                $grandTotal,
                                                                0
                                                            ) ?>
                                                        </b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- =====================================================
BAD PRODUK MESIN DOMINAN
===================================================== -->
                        <div class="col-lg-7 px-lg-1">
                            <div class="dashboard-card h-100">
                                <div class="dashboard-card-header">
                                    <h6>
                                        Bad Produk berdasarkan Mesin Filler
                                    </h6>
                                </div>
                                <div class="dashboard-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-dashboard bad-mesin-table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Mesin
                                                    </th>
                                                    <?php foreach ($badproduk as $bp) : ?>
                                                        <th>
                                                            <?= $bp->nama_badpro ?>
                                                        </th>
                                                    <?php endforeach; ?>
                                                    <th>
                                                        TOTAL BAD
                                                    </th>
                                                    <th>
                                                        OUTPUT PCS
                                                    </th>
                                                    <th>
                                                        KONTRIBUSI OUTPUT
                                                    </th>
                                                    <th>
                                                        BAD / OUTPUT
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($bad_produk_mesin)) : ?>
                                                    <?php foreach ($bad_produk_mesin as $row) : ?>
                                                        <tr>
                                                            <td>
                                                                <?= $row->mesin ?>
                                                            </td>
                                                            <?php foreach ($badproduk as $bp) : ?>
                                                                <td>
                                                                    <?= number_format(
                                                                        $row->{$bp->nama_badpro} ?? 0,
                                                                        2
                                                                    ) ?>
                                                                </td>
                                                            <?php endforeach; ?>
                                                            <td>
                                                                <b>
                                                                    <?= number_format(
                                                                        $row->total ?? 0,
                                                                        2
                                                                    ) ?>
                                                                </b>
                                                            </td>
                                                            <td>
                                                                <?= number_format(
                                                                    $row->output_mesin ?? 0,
                                                                    0
                                                                ) ?>
                                                            </td>
                                                            <td>
                                                                <?= number_format(
                                                                    $row->kontribusi_output ?? 0,
                                                                    2
                                                                ) ?>%
                                                            </td>
                                                            <td>
                                                                <?= number_format(
                                                                    $row->bad_per_output ?? 0,
                                                                    4
                                                                ) ?>%
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="<?= count($badproduk) + 5 ?>" class="text-center text-muted">
                                                            Belum ada data mesin dominan
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        TOTAL
                                                    </td>
                                                    <?php foreach ($badproduk as $bp) : ?>
                                                        <td>
                                                            <?php
                                                            $totalBad = 0;
                                                            if (!empty($bad_produk_mesin)) {
                                                                foreach ($bad_produk_mesin as $row) {
                                                                    $totalBad +=
                                                                        $row->{$bp->nama_badpro} ?? 0;
                                                                }
                                                            }
                                                            ?>
                                                            <?= number_format(
                                                                $totalBad,
                                                                2
                                                            ) ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td>
                                                        <?php
                                                        $grandTotalMesin = 0;
                                                        if (!empty($bad_produk_mesin)) {
                                                            foreach ($bad_produk_mesin as $row) {
                                                                $grandTotalMesin +=
                                                                    $row->total ?? 0;
                                                            }
                                                        }
                                                        ?>
                                                        <b>
                                                            <?= number_format(
                                                                $grandTotalMesin,
                                                                2
                                                            ) ?>
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $totalOutput = 0;
                                                        if (!empty($bad_produk_mesin)) {
                                                            foreach ($bad_produk_mesin as $row) {
                                                                $totalOutput +=
                                                                    $row->output_mesin ?? 0;
                                                            }
                                                        }
                                                        ?>
                                                        <b>
                                                            <?= number_format(
                                                                $totalOutput,
                                                                0
                                                            ) ?>
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $totalKontribusi = 0;
                                                        if (!empty($bad_produk_mesin)) {
                                                            foreach ($bad_produk_mesin as $row) {
                                                                $totalKontribusi +=
                                                                    $row->kontribusi_output ?? 0;
                                                            }
                                                        }
                                                        ?>
                                                        <b>
                                                            <?= number_format(
                                                                $totalKontribusi,
                                                                2
                                                            ) ?>%
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            <?= number_format(
                                                                $totalBadOutput ?? 0,
                                                                4
                                                            ) ?>%
                                                        </b>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- =====================================================
SHORTCUT
===================================================== -->
                        <div class="col-lg-2 pl-lg-1">
                            <div class="dashboard-card h-100">
                                <div class="dashboard-card-header">
                                    <h6>
                                        Shortcut
                                    </h6>
                                </div>
                                <div class="dashboard-card-body text-center">
                                    <a href="<?= base_url('filler/planning') ?>" class="btn btn-success shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Planning Produksi
                                    </a>
                                    <a href="<?= base_url('mpusage/') ?>" class="btn btn-info shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Data MP
                                    </a>
                                    <a href="<?= base_url('counter/') ?>" class="btn btn-warning shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Data Filler
                                    </a>
                                    <a href="<?= base_url('filkar/') ?>" class="btn btn-primary shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Data Filkar
                                    </a>
                                    <a href="<?= base_url('sortasi/') ?>" class="btn btn-secondary shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Data Sortasi
                                    </a>
                                    <a href="<?= base_url('varian/') ?>" class="btn btn-primary shortcut-btn">
                                        <i class="fa fa-list"></i>
                                        Master Varian
                                    </a>
                                    <a href="<?= base_url('yieldportal/analisa') ?>" class="btn btn-danger shortcut-btn">
                                        <i class="fa fa-chart-line"></i>
                                        Analisa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =====================================================
END CONTENT
===================================================== -->
                </div> <!-- container-fluid -->
            </div> <!-- content -->
        </div> <!-- content-wrapper -->
    </div> <!-- wrapper -->
    <!-- =====================================================
FOOTER
===================================================== -->
    <footer class="sticky-footer bg-white fix">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; PT Charoen Pokphand Indonesia - Plant Berbek | 2026</span>
            </div>
        </div>
    </footer>
    <!-- =====================================================
LOGOUT MODAL
===================================================== -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Konfirmasi Logout
                    </h5>
                    <button class="close" data-dismiss="modal">
                        <span>
                            &times;
                        </span>
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
    <!-- =====================================================
JAVASCRIPT
===================================================== -->
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
    <script>
        /*
=====================================================
AUTO REFRESH
Dashboard refresh setiap 1 jam
=====================================================
*/
        setInterval(function() {
            location.reload();
        }, 3600000);
        /*
        =====================================================
        DIGITAL CLOCK
        =====================================================
        */
        function updateClock() {
            const now = new Date();
            const date =
                now.toLocaleDateString(
                    'id-ID', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }
                );
            const time =
                now.toLocaleTimeString(
                    'id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    }
                ).replace(/\./g, ':');
            document.getElementById('clock').innerHTML =
                date + " " + time;
        }
        updateClock();
        setInterval(
            updateClock,
            1000
        );
    </script>
</body>
</html>