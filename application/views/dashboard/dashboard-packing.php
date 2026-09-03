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
                            <h1>Dashboard Drystore</h1>
                        </div>
                        <div>
                            <span class="badge badge-info clock-box" id="clock">
                                <?= date('d F Y H:i:s'); ?>
                            </span>
                        </div>
                    </div>

    <!-- ========================= -->
    <!-- SUMMARY CARD -->
    <!-- ========================= -->
<a href="<?= base_url('drystore/'); ?>" class="open-btn mt-auto rounded-lg py-2 flex items-center justify-center gap-2 text-xs text-white font-bold bg-gradient-to-r from-sky-500 to-green-500 shadow-lg">
    <div class="row">

        <!-- On Product -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                On Product
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                (jumlah release)
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Reject -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Reject / Waste
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                (total reject)
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-trash-alt fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Use -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Use
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                (total use)
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-weight-hanging fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Yield -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Yield
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                (yield drystore)
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</a>



    <!-- ========================= -->
    <!-- TABEL REPORT -->
    <!-- ========================= -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i>
                Report Waste Drystore
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover"
                    width="100%"
                    cellspacing="0">

                    <thead class="table bg-info text-light">

                        <tr>
                            <th class="align-middle text-center">
                                Std Waste
                            </th>

                            <th class="align-middle text-center">
                                Type Packaging
                            </th>

                            <th class="align-middle text-center">
                                No
                            </th>

                            <th class="align-middle text-center">
                                Jenis Waste
                            </th>

                            <th class="align-middle text-center">
                                Reject
                            </th>

                            <th class="align-middle text-center">
                                On Product
                            </th>

                            <th class="align-middle text-center">
                                Use
                            </th>

                            <th class="align-middle text-center">
                                % Waste
                            </th>

                            <th class="align-middle text-center">
                                % Yield
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        <!-- MC Okey -->
<tr><td colspan="9" class="text-center">
                        belum ada data
                        </td>
</tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ========================= -->
    <!-- WASTE SUMMARY -->
    <!-- ========================= -->

    <div class="row">

        <div class="col-lg-6 mb-4">

            <div class="card shadow h-100">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Waste per Type Packaging
                    </h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>MC Okey</span>
                            <strong>1.62%</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar"
                                role="progressbar"
                                style="width: 81%;">
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>MC Champ</span>
                            <strong>0.83%</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar"
                                role="progressbar"
                                style="width: 42%;">
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Toples</span>
                            <strong>0.93%</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar"
                                role="progressbar"
                                style="width: 47%;">
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Plastik Okey</span>
                            <strong>0.80%</strong>
                        </div>

                        <div class="progress">
                            <div class="progress-bar"
                                role="progressbar"
                                style="width: 40%;">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6 mb-4">

            <div class="card shadow h-100">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Waste Terbesar
                    </h6>
                </div>

                <div class="card-body">

                    <div class="list-group">

                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Rusak Produksi Mesin Error
                            <span class="badge badge-danger badge-pill">
                                27.000 Kg
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Bad Produk
                            <span class="badge badge-danger badge-pill">
                                24.500 Kg
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Rusak Produksi Human Error
                            <span class="badge badge-warning badge-pill">
                                21.000 Kg
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Rusak Supplier
                            <span class="badge badge-warning badge-pill">
                                10.000 Kg
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
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