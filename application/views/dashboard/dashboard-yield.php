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
html,
body {
    height: 100%;
}

body {
    overflow-x: hidden;
    overflow-y: hidden;
    background: #eef2f6;
    font-size: 12px;
    font-family: "Segoe UI", Tahoma, sans-serif;
    color: #2d3436;
}

/* ========================= */
/* LAYOUT */
/* ========================= */
.container-fluid {
    padding: 8px 10px;
}

.dashboard-title {
    font-size: 22px;
    font-weight: 700;
    color: #163d6b;
    margin-bottom: 8px;
    letter-spacing: .5px;
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
                        <h4 class="font-weight-bold text-dark mb-0">
                            DASHBOARD YIELD PRODUKSI
                        </h4>
                        <div>
                            <span class="badge badge-success px-3 py-2">
                                JULI 2026
                            </span>
                            <span class="badge badge-primary px-3 py-2 ml-1">
                                Update :
                                09:25
                            </span>
                        </div>
                    </div>
                    <!-- ========================= -->
                    <!-- MONITORING YIELD -->
                    <!-- ========================= -->
                    <div class="card shadow border-0">
                        <div class="card-header text-white">
                            <h6>
                                Monitoring Produksi Bulan Berjalan
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
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="180">Bad Produk</th>
                                                    <th width="80">SROA</th>
                                                    <th width="80">BRCH</th>
                                                    <th width="80">SRCH</th>
                                                    <th width="80">SRCO</th>
                                                    <th width="90">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Bengkok</td>
                                                    <td>12</td>
                                                    <td>5</td>
                                                    <td>9</td>
                                                    <td>4</td>
                                                    <td class="font-weight-bold">30</td>
                                                </tr>
                                                <tr>
                                                    <td>Klip</td>
                                                    <td>6</td>
                                                    <td>2</td>
                                                    <td>5</td>
                                                    <td>1</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td>Seal</td>
                                                    <td>3</td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>2</td>
                                                    <td class="font-weight-bold">8</td>
                                                </tr>
                                                <tr>
                                                    <td>Bocor</td>
                                                    <td>4</td>
                                                    <td>3</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td class="font-weight-bold">12</td>
                                                </tr>
                                                <tr>
                                                    <td>Bubble</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">5</td>
                                                </tr>
                                                <tr>
                                                    <td>Air Trap</td>
                                                    <td>8</td>
                                                    <td>4</td>
                                                    <td>7</td>
                                                    <td>2</td>
                                                    <td class="font-weight-bold">21</td>
                                                </tr>
                                                <tr>
                                                    <td>Kurang Isi</td>
                                                    <td>5</td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">8</td>
                                                </tr>
                                                <tr>
                                                    <td>Overweight</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>1</td>
                                                    <td>1</td>
                                                    <td class="font-weight-bold">4</td>
                                                </tr>
                                                <tr>
                                                    <td>Underweight</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>1</td>
                                                    <td class="font-weight-bold">10</td>
                                                </tr>
                                                <tr>
                                                    <td>Penyok</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">6</td>
                                                </tr>
                                                <tr>
                                                    <td>Kotor</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">3</td>
                                                </tr>
                                                <tr>
                                                    <td>Lain-lain</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">4</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td><strong>TOTAL</strong></td>
                                                    <td><strong>52</strong></td>
                                                    <td><strong>21</strong></td>
                                                    <td><strong>40</strong></td>
                                                    <td><strong>12</strong></td>
                                                    <td><strong>125</strong></td>
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
                                                    <th width="90">Mesin</th>
                                                    <th>Bengkok</th>
                                                    <th>Klip</th>
                                                    <th>Seal</th>
                                                    <th>Air Trap</th>
                                                    <th>Bubble</th>
                                                    <th>Bocor</th>
                                                    <th>Bengkok</th>
                                                    <th>Klip</th>
                                                    <th>Seal</th>
                                                    <th>Air Trap</th>
                                                    <th>Bubble</th>
                                                    <th>Bocor</th>
                                                    <th width="90">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">11</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 2</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">11</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 3</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">13</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 4</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">11</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 5</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">11</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">ZAP 6</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">11</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 1</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 2</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 3</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 4</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>1</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">4</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">CAP 6</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left font-weight-bold">KAP 1</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>0</td>
                                                    <td>4</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                    <td>1</td>
                                                    <td>4</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td class="font-weight-bold">14</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 pl-1">
                            <div class="card shadow h-100">
                                <div class="card-header py-2">
                                    <h6 class="m-0 font-weight-bold text-secondary">
                                        Shortcut fitur
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <a href="<?= base_url('filler/planning') ?>"
                                        class="btn btn-md btn-block btn-success shadow-sm mb-2 mt-3"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Planning Produksi</a>
                                    <a href="<?= base_url('mpusage/') ?>"
                                        class="btn btn-md btn-block btn-info shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Data MP</a>
                                    <a href="<?= base_url('counter/') ?>"
                                        class="btn btn-md btn-block btn-warning shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Data Filler</a>
                                    <a href="<?= base_url('filkar/') ?>"
                                        class="btn btn-md btn-block btn-primary shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Data Filkar</a>
                                    <a href="<?= base_url('sortasi/') ?>"
                                        class="btn btn-md btn-block btn-secondary shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Data Packing </a>
                                    <a href="<?= base_url('varian/') ?>"
                                        class="btn btn-md btn-block btn-primary shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Master Data Varian</a>
                                    <a href="<?= base_url('yield/#') ?>"
                                        class="btn btn-md btn-block btn-danger shadow-sm mb-2"><i
                                            class="fa fa-list fa-sm text-white mr-2"></i> Tracking</a>
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
</body>

</html>