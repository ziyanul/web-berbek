<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title ?? ' CPI-Berbek' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">

    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css');?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css');?>" rel="stylesheet">

    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

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

    .card-modern {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
    }

    .card-modern .card-body {
        padding: 18px;
    }

    .stat-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
    }

    .stat-number {
        font-size: 26px;
        font-weight: 700;
    }

    .alert-item {
        display: flex;
        align-items: center;
        border-radius: 12px;
        padding: 8px 12px;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .alert-warning-custom {
        background: #fff8e6;
        color: #856404;
    }

    .alert-danger-custom {
        background: #ffeaea;
        color: #b02a37;
    }

    .timeline {
        position: relative;
        padding-left: 25px;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        width: 3px;
        height: 100%;
        background: #dbe4ff;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: -22px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4e73df;
    }

    .timeline-time {
        font-size: 12px;
        color: #858796;
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

    .alert-badge {
        background: #dc3545;
        color: #fff;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .progress-modern {
        height: 10px;
        border-radius: 20px;
        background: #eaecf4;
    }

    .progress-modern .progress-bar {
        border-radius: 20px;
    }

    .timeline-item.success:before {
        background: #1cc88a;
    }

    .timeline-item.warning:before {
        background: #f6c23e;
    }

    .timeline-item.danger:before {
        background: #e74a3b;
    }

    .timeline-item.primary:before {
        background: #4e73df;
    }

    .refresh-info {
        font-size: 12px;
        opacity: .85;
    }

    .shortcut-card i {
        font-size: 18px;
    }

    .timeline-wrapper {
        max-height: 140px;
        overflow-y: auto;
    }

    .timeline-item {
        margin-bottom: 8px;
        font-size: 12px;
    }

    .timeline-time {
        font-size: 11px;
    }

    .shortcut-card {
        padding: 12px 8px;
    }

    .shortcut-card i {
        font-size: 16px;
        margin-bottom: 4px;
    }

    .row-top .card-modern {
        height: 100%;
    }

    .shortcut-card {
        border: 1px solid #edf2f9;
    }

    .shortcut-card:hover {
        border-color: #4e73df;
        background: #f8faff;
    }

    .sticky-footer {
        padding: .5rem 0 !important;
    }
    </style>

</head>

<body id="page-top">
    <div id="wrapper">
        <?php
		$subrole  = $this->session->userdata('subrole');
		?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <div class="container-fluid px-4">

                    <!-- HEADER -->
                    <div class="dashboard-header">

                        <div class="row align-items-center">

                            <div class="col-md-8">
                                <h2>Dashboard Paperless</h2>

                                <p class="mb-1">
                                    Monitoring aktivitas form produksi secara realtime
                                </p>

                                <span class="alert-badge">
                                    3 Alert Aktif
                                </span>
                            </div>

                            <div class="col-md-4 text-md-right">

                                <h5><?= date('d M Y') ?></h5>

                                <div class="refresh-info">
                                    Auto Refresh per Jam
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ROW ATAS -->
                    <div class="row row-top">

                        <!-- FORM HARI INI -->
                        <div class="col-lg-4 mb-1">

                            <div class="card-modern stat-card h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between mb-2">

                                        <div>
                                            <div class="text-success font-weight-bold">
                                                FORM HARI INI
                                            </div>

                                            <div class="stat-number text-success">
                                                45
                                            </div>

                                            <small class="text-muted">
                                                Sudah Diisi
                                            </small>
                                        </div>

                                        <div class="text-right">

                                            <div class="stat-number text-danger">
                                                7
                                            </div>

                                            <small class="text-muted">
                                                Belum Diisi
                                            </small>

                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <small>Kelengkapan Form</small>
                                        <small>86%</small>
                                    </div>

                                    <div class="progress progress-modern">
                                        <div class="progress-bar bg-success" style="width:86%">
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- NOTIFIKASI -->
                        <div class="col-lg-4 mb-2">

                            <div class="card-modern h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-2">

                                        <h5 class="font-weight-bold mb-0">
                                            <i class="fas fa-bell text-warning mr-2"></i>
                                            Notifikasi
                                        </h5>

                                        <span class="badge badge-danger">
                                            3
                                        </span>

                                    </div>

                                    <div class="alert-item alert-warning-custom">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        Cooking M3 belum diisi
                                    </div>

                                    <div class="alert-item alert-warning-custom">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        Sortir M8 belum diisi
                                    </div>

                                    <div class="alert-item alert-danger-custom mb-0">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Checklist belum lengkap
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- AKTIVITAS -->
                        <div class="col-lg-4 mb-2">

                            <div class="card-modern h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <h5 class="font-weight-bold mb-0">
                                            <i class="fas fa-history text-primary mr-2"></i>
                                            Aktivitas
                                        </h5>

                                        <small class="text-muted">
                                            Terakhir
                                        </small>

                                    </div>

                                    <div class="timeline-wrapper">

                                        <div class="timeline">

                                            <div class="timeline-item danger">
                                                <div class="timeline-time">10:35</div>
                                                Reject Filler M5
                                            </div>

                                            <div class="timeline-item success">
                                                <div class="timeline-time">10:28</div>
                                                Counter M2
                                            </div>

                                            <div class="timeline-item warning">
                                                <div class="timeline-time">10:15</div>
                                                Cooking M7
                                            </div>

                                            <div class="timeline-item primary">
                                                <div class="timeline-time">10:02</div>
                                                Batch Baru
                                            </div>

                                            <div class="timeline-item success mb-0">
                                                <div class="timeline-time">09:55</div>
                                                Checklist Approve
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- SHORTCUT -->
                    <div class="row">

                        <div class="col-12">

                            <div class="card-modern">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-1">

                                        <h5 class="font-weight-bold mb-0 text-info">
                                            <i class="fas fa-bolt text-success mr-2"></i>
                                            Shortcut Form Produksi
                                        </h5>
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="<?= base_url('counter/formcounter') ?>" class="shortcut-card">
                                                <i class="fas fa-layer-group"></i>
                                                <div class="shortcut-title">Batch</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-calculator"></i>
                                                <div class="shortcut-title">Counter</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-clipboard-check"></i>
                                                <div class="shortcut-title">Checklist</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-times-circle"></i>
                                                <div class="shortcut-title">Reject Filler</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-fire"></i>
                                                <div class="shortcut-title">Reject Cooking</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-filter"></i>
                                                <div class="shortcut-title">Reject Sortir</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-trash"></i>
                                                <div class="shortcut-title">Reject SMFG</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-tools"></i>
                                                <div class="shortcut-title">Downtime</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-exchange-alt"></i>
                                                <div class="shortcut-title">Ganti Batch</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-calendar-alt"></i>
                                                <div class="shortcut-title">Planning</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-cogs"></i>
                                                <div class="shortcut-title">Mesin</div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-6 mb-3">
                                            <a href="#" class="shortcut-card">
                                                <i class="fas fa-chart-bar"></i>
                                                <div class="shortcut-title">Laporan</div>
                                            </a>
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

    </div> <!-- Footer -->
    <footer class="sticky-footer bg-white fix">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; PT Charoen Pokphand Indonesia - Plant Berbek | 2024</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari sistem?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a class="btn btn-danger" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    setInterval(function() {
        location.reload();
    }, 3600000);
    </script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js');?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js');?>"></script>
</body>

</html>