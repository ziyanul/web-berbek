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
   LIVE INDICATOR
===================================================== */
.live-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: #ed1c24;
    border-radius: 20px;
    color: white;
    font-family: Arial, sans-serif;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    box-shadow: 0 3px 8px rgba(237, 28, 36, .25);
}
.live-icon {
    position: relative;
    width: 25px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.live-icon .dot {
    width: 7px;
    height: 7px;
    background: greenyellow;
    border-radius: 50%;
    animation: live-blink 1s infinite;
}
.live-icon .wave {
    position: absolute;
    width: 9px;
    height: 16px;
    border: 2px solid white;
    border-top-color: transparent;
    border-bottom-color: transparent;
    border-radius: 50%;
    animation: live-blink 1s infinite;
}
.live-icon .wave-left {
    left: 0;
}
.live-icon .wave-right {
    right: 0;
}
@keyframes live-blink {
    0%,
    50% {
        opacity: 1;
    }
    51%,
    100% {
        opacity: .2;
    }
}
/* =====================================================
   HEADER LEFT
===================================================== */
.card-modern {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
        }
        .card-modern .card-body {
            padding: 18px;
        }
        .shortcut-card {
            display: block;
            text-align: center;
            background: #fff;
            border-radius: 15px;
            padding: 15px 10px;
            color: #5a5c69;
            transition: .2s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
            text-decoration: none !important;
            height: 100%;
        }
        .shortcut-card:hover {
            transform: translateY(-5px);
            color: #4e73df;
        }
        .shortcut-title {
            font-size: 13px;
            font-weight: 600;
        }
.header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
/* =====================================================
   LIVE STATUS
===================================================== */
.live-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 32px;
    padding: 0 11px;
    background: #f0fff4;
    border: 1px solid #b7ebc6;
    border-radius: 18px;
    color: #16803c;
    box-shadow: 0 2px 6px rgba(22, 128, 60, .08);
}
/* DOT */
.live-pulse {
    position: relative;
    width: 9px;
    height: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.live-dot {
    width: 7px;
    height: 7px;
    background: #22c55e;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    animation: live-blink 1s infinite;
}
/* Lingkaran pulse */
.live-pulse::before {
    content: "";
    position: absolute;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: rgba(34, 197, 94, .35);
    animation: live-pulse 1.5s infinite;
}
/* TEXT */
.live-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
}
/* SIGNAL */
.live-signal {
    height: 16px;
    display: flex;
    align-items: flex-end;
    gap: 2px;
    margin-left: 1px;
}
.live-signal i {
    display: block;
    width: 3px;
    background: #22c55e;
    border-radius: 2px;
}
.live-signal i:nth-child(1) {
    height: 5px;
    opacity: .45;
}
.live-signal i:nth-child(2) {
    height: 8px;
    opacity: .65;
}
.live-signal i:nth-child(3) {
    height: 11px;
    opacity: .8;
}
.live-signal i:nth-child(4) {
    height: 14px;
    opacity: 1;
}
/* ANIMATION */
@keyframes live-pulse {
    0% {
        transform: scale(.8);
        opacity: .8;
    }
    70% {
        transform: scale(1.8);
        opacity: 0;
    }
    100% {
        transform: scale(1.8);
        opacity: 0;
    }
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
    /* SHORTCUT */
    .shortcut-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;}
    .shortcut-link{display:flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:8px 10px;border-radius:8px;background:#f8fafc;border:1px solid #4e73df;color:#263746;font-size:12px;font-weight:700;text-decoration:none!important;transition:.2s;}
    .shortcut-link:hover{transform:translateY(-2px);background:#4e73df;color:#163d6b;border-color:#b9d8eb;}
    .shortcut-link i{width:16px;text-align:center;}
    @media(max-width:768px){.shortcut-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
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
<!-- LIVE STATUS -->
    <div class="live-status">
        <span class="live-pulse">
            <span class="live-dot"></span>
        </span>
        <span class="live-label">LIVE</span>
        <!-- <span class="live-signal">
            <i></i>
            <i></i>
            <i></i>
            <i></i>
        </span> -->
    </div>
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
                    <div class="monitoring-row mb-2">
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
                                                    Adonan (Kg)
                                                </th>
                                                <th colspan="2">
                                                    Filkar
                                                </th>
                                                <th colspan="2">
                                                    Bad Produk Filkar
                                                </th>
                                                <th rowspan="2">
                                                    Yield (%)
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
                                                    Rework (kg)
                                                </th>
                                                <th>
                                                    Reject (kg)
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
                                                    Sortasi (box)
                                                </th>
                                                <th colspan="4">
                                                    Bad Produk (kg)
                                                </th>
                                                <th rowspan="2">
                                                    Yield (%)
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Sortir (Box)
                                                </th>
                                                <th>
                                                    Release (box)
                                                </th>
                                                <th>
                                                    Sisa WIP (box)
                                                </th>
                                                <th>
                                                    Rework (kg)
                                                </th>
                                                <th>
                                                    Reject (kg)
                                                </th>
                                                <th>
                                                    Total Bad (kg)
                                                </th>
                                                <th>
                                                    Total (%)
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
                                                        <b>
                                                            <?= number_format($row->bad_persen, 2) ?> %
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
                                                    <?= number_format($total_sortasi->sortasi_box ?? 0) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->release_box ?? 0) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->blm_sortir ?? 0) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_rework ?? 0, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_reject ?? 0, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->sortasi_bad ?? 0, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->bad_persen ?? 0, 2) ?>
                                                </td>
                                                <td>
                                                    <?= number_format($total_sortasi->yield_sortasi ?? 0, 2) ?> %
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
                                        PVDC & WIRE
                                    </h6>
                                </div>
                                <div class="dashboard-card-body">
                                    <div class="table-responsive">
                                        <table class="table table-dashboard bad-varian-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">
                                                        Varian
                                                    </th>
                                                    <th colspan="2">
                                                        PVDC
                                                    </th>
                                                    <th colspan="2">
                                                        WIRE
                                                    </th>
                                                </tr>
                                                <tr>
                                                <th>
                                                            Pakai (M)
                                                        </th>
                                                   <th>
                                                            Reject (M)
                                                        </th>
                                                        <th>
                                                            Pakai (kg)
                                                        </th>
                                                   <th>
                                                            Reject (kg)
                                                        </th>
                                            </tr>
                                            </thead>
                                            <?php
// Initialize totals
$total_pvdc = 0;
$total_reject_pvdc = 0;
$total_wire = 0;
$total_reject_wire = 0;
?>
<tbody>
    <?php foreach ($pvdc as $v) : ?>
        <?php
        // Accumulate values
        $total_pvdc += (float)$v->pvdc;
        $total_reject_pvdc += (float)$v->reject_pvdc;
        $total_wire += (float)$v->wire;
        $total_reject_wire += (float)$v->reject_wire;
        ?>
        <tr>
            <td> <?= $v->nama_varian ?> </td>
            <td> <?= $v->pvdc ?> </td>
            <td> <?= $v->reject_pvdc ?> </td>
            <td> <?= $v->wire ?> </td>
            <td> <?= $v->reject_wire ?> </td>
        </tr>
    <?php endforeach; ?>
</tbody>
<tfoot>
    <tr>
        <td> TOTAL </td>
        <td> <b> <?= $total_pvdc ?> </b> </td>
        <td> <b> <?= $total_reject_pvdc ?> </b> </td>
        <td> <b> <?= $total_wire ?> </b> </td>
        <td> <b> <?= $total_reject_wire ?> </b> </td>
    </tr>
</tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BAD PRODUK BERDASARKAN MESIN DOMINAN -->
                        <div class="col-9 px-lg-1">
    <div class="dashboard-card h-100">
        <div class="dashboard-card-header">
            <h6>Bad Produk berdasarkan Mesin Dominan</h6>
        </div>
        <div class="dashboard-card-body">
            <div class="table-responsive">
                <table class="table table-dashboard bad-mesin-table">
                    <thead>
                        <tr>
                            <th>Bad Produk</th>
                            <?php if (!empty($bad_produk_mesin)) : ?>
                                <?php foreach ($bad_produk_mesin as $row) : ?>
                                    <th>
                                        <?= htmlspecialchars(
                                            $row->mesin,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <th>TOTAL BAD</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (
        !empty($bad_produk_mesin)
        &&
        !empty($badproduk)
    ) : ?>

        <?php
        $badproduk_sorted = [];

        foreach ($badproduk as $bp) {
            $badTotal = 0;

            foreach ($bad_produk_mesin as $row) {
                $badTotal += (float) (
                    $row->{$bp->nama_badpro} ?? 0
                );
            }

            $badproduk_sorted[] = [
                'bp' => $bp,
                'total' => $badTotal
            ];
        }

        // Urutkan TOTAL BAD terbesar ke terkecil
        usort($badproduk_sorted, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        $badproduk_sorted = array_slice($badproduk_sorted, 0, 10);

        ?>

        <?php foreach ($badproduk_sorted as $item) : ?>

            <?php
            $bp = $item['bp'];
            $badTotal = $item['total'];
            ?>

            <tr>
                <td>
                    <?= htmlspecialchars(
                        $bp->nama_badpro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </td>

                <?php foreach ($bad_produk_mesin as $row) : ?>

                    <?php
                    $nilaiBad = (float) (
                        $row->{$bp->nama_badpro} ?? 0
                    );
                    ?>

                    <td>
                        <?= number_format($nilaiBad, 2) ?>
                    </td>

                <?php endforeach; ?>

                <td>
                    <b>
                        <?= number_format($badTotal, 2) ?>
                    </b>
                </td>

                <td>
                    <b>
                        <?php
                        if ($total_sortasi_kg > 0) {
                            $persenBad =
                                ($badTotal / $total_sortasi_kg) * 100;
                        } else {
                            $persenBad = 0;
                        }
                        ?>

                        <?= number_format(
                            $persenBad,
                            2
                        ) ?>%
                    </b>
                </td>
            </tr>

        <?php endforeach; ?>

    <?php else : ?>

        <tr>
            <td
                colspan="<?= max(
                    2,
                    count($bad_produk_mesin ?? []) + 2
                ) ?>"
                class="text-center text-muted"
            >
                Belum ada data Bad Produk Sortasi
            </td>
        </tr>

    <?php endif; ?>
</tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td>
                                <b>TOTAL BAD PRODUK</b>
                            </td>
                            <?php
                            $grandTotalBad = 0;
                            ?>
                            <?php if (
                                !empty(
                                    $bad_produk_mesin
                                )
                            ) : ?>
                                <?php foreach (
                                    $bad_produk_mesin
                                    as $row
                                ) : ?>
                                    <?php
                                    $totalMesin =
                                        (float) (
                                            $row->total
                                            ?? 0
                                        );
                                    $grandTotalBad +=
                                        $totalMesin;
                                    ?>
                                    <td>
                                        <b>
                                            <?= number_format(
                                                $totalMesin,
                                                2
                                            ) ?>
                                        </b>
                                    </td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <td>
                                <b>
                                    <?= number_format(
                                        $grandTotalBad,
                                        2
                                    ) ?>
                                </b>
                            </td>
                            <td>
    <b>
        <?php
        if ($total_sortasi_kg > 0) {
            $grandPersen =
                ($grandTotalBad / $total_sortasi_kg) * 100;
        } else {
            $grandPersen = 0;
        }
        ?>
        <?= number_format(
            $grandPersen,
            2
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
                    </div>
      <div class="row">
    <div class="col-12">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h6>
                    Performa Mesin
                    <?= date('F') ?> <?= date('Y') ?>
                </h6>
            </div>
            <div class="dashboard-card-body">
                <div class="table-responsive">
                    <table class="table table-dashboard text-center">
                        <thead>
                            <tr>
                                <th class="text-left">
                                    METRIC
                                </th>
                                <?php if (!empty($dashboard_mesin)) : ?>
                                    <?php foreach ($dashboard_mesin as $mesin) : ?>
                                        <th>
                                            <?= htmlspecialchars(
                                                $mesin->nama_mesin
                                            ) ?>
                                        </th>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <!-- KOLOM PALING KANAN -->
                                <th>
                                    RATA-RATA / TOTAL
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- =================================================
                                 PERFORMA
                            ================================================== -->
                            <tr>
                                <td class="text-left">
                                    <strong>PERFORMA</strong>
                                </td>
                                <?php
                                $total_performa = 0;
                                $jumlah_mesin = count($dashboard_mesin);
                                ?>
                                <?php foreach ($dashboard_mesin as $mesin) : ?>
                                    <?php
                                    $total_performa += (float) $mesin->performa;
                                    ?>
                                    <td>
                                        <strong>
                                            <?= number_format(
                                                $mesin->performa,
                                                1
                                            ) ?>%
                                        </strong>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <strong>
                                        <?= number_format(
                                            $jumlah_mesin > 0
                                                ? $total_performa / $jumlah_mesin
                                                : 0,
                                            1
                                        ) ?>%
                                    </strong>
                                </td>
                            </tr>
                            <!-- =================================================
                                 DOWNTIME
                            ================================================== -->
                            <tr>
                                <td class="text-left">
                                    <strong>DOWNTIME</strong>
                                </td>
                                <?php
                                $total_downtime = 0;
                                ?>
                                <?php foreach ($dashboard_mesin as $mesin) : ?>
                                    <?php
                                    $total_downtime += (float) $mesin->downtime;
                                    ?>
                                    <td>
                                        <?= number_format(
                                            $mesin->downtime,
                                            0
                                        ) ?> menit
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <strong>
                                        <?= number_format(
                                            $total_downtime,
                                            0
                                        ) ?> menit
                                    </strong>
                                </td>
                            </tr>
                            <!-- =================================================
                                 LOST TIME
                            ================================================== -->
                            <tr>
                                <td class="text-left">
                                    <strong>LOST TIME</strong>
                                </td>
                                <?php
                                $total_losttime = 0;
                                ?>
                                <?php foreach ($dashboard_mesin as $mesin) : ?>
                                    <?php
                                    $total_losttime += (float) $mesin->losttime;
                                    ?>
                                    <td>
                                        <?= number_format(
                                            $mesin->losttime,
                                            0
                                        ) ?> menit
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <strong>
                                        <?= number_format(
                                            $total_losttime,
                                            0
                                        ) ?> menit
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-modern"><div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="font-weight-bold mb-0 text-info"><i class="fas fa-bolt text-success mr-2"></i>Shortcut</h5>
                                </div>
                                <div class="shortcut-grid">
                                <a href="<?= base_url('filler/planning') ?>" class="shortcut-link"><i class="fas fa-route"></i><span>PLAN PRODUKSI</span></a>
                                    <a href="<?= base_url('mpusage') ?>" class="shortcut-link"><i class="fas fa-cogs"></i><span>MP</span></a>
                                    <a href="<?= base_url('counter') ?>" class="shortcut-link"><i class="fas fa-industry"></i><span>FILLER</span></a>
                                    <a href="<?= base_url('filkar') ?>" class="shortcut-link"><i class="fas fa-temperature-high"></i><span>FILKAR</span></a>
                                    <a href="<?= base_url('sortasi') ?>" class="shortcut-link"><i class="fas fa-box"></i><span>SORTASI</span></a>
                                </div>
                            </div></div>
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