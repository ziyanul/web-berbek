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

    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
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
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>
                            <a href="<?= base_url('portal') ?>" class="badge badge-info px-3 py-2">
                                <i class="fa fa-lg fa-home mr-1"></i>
                                PORTAL
                            </a>
                        </div>
                        <h2 class="font-weight-bold text-dark mb-0">
                            DASHBOARD PAPERLESS</h2>

                        <div>
                            <span class="badge badge-info px-3 py-2 ml-1" id="clock">
                                <div><?= date('d F Y H:i:s'); ?></div>
                            </span>
                        </div>

                    </div>
                    <!-- HEADER -->
                    <div class="dashboard-header">

                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <p class="mb-1">
                                    Monitoring aktivitas form produksi secara realtime
                                </p>

                                <span class="alert-badge">
                                    3 Alert Aktif
                                </span>
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
                    <!-- ================= SHORTCUT ================= -->
                    <div class="row">
                        <div class="col-12">

                            <div class="card-modern">

                                <div class="card-body">

                                    <!-- HEADER -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="font-weight-bold mb-0 text-info">
                                            <i class="fas fa-bolt text-success mr-2"></i>
                                            Shortcut Form Produksi
                                        </h5>
                                    </div>


                                    <!-- ================= KATEGORI ================= -->
                                    <div class="row mb-3">

                                        <!-- MP -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category active" data-menu="mp">
                                                <i class="fas fa-cogs"></i>
                                                <span>MP</span>
                                            </button>
                                        </div>

                                        <!-- FILLER -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="filler">
                                                <i class="fas fa-industry"></i>
                                                <span>FILLER</span>
                                            </button>
                                        </div>

                                        <!-- RETORT -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="retort">
                                                <i class="fas fa-temperature-high"></i>
                                                <span>RETORT</span>
                                            </button>
                                        </div>

                                        <!-- PACKING -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="packing">
                                                <i class="fas fa-box"></i>
                                                <span>PACKING</span>
                                            </button>
                                        </div>

                                        <!-- SANITASI -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="sanitasi">
                                                <i class="fas fa-spray-can"></i>
                                                <span>SANITASI</span>
                                            </button>
                                        </div>

                                        <!-- PENDUKUNG -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="pendukung">
                                                <i class="fas fa-tools"></i>
                                                <span>PENDUKUNG</span>
                                            </button>
                                        </div>

                                        <!-- ISO / TS -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="iso">
                                                <i class="fas fa-certificate"></i>
                                                <span>ISO / TS</span>
                                            </button>
                                        </div>

                                        <!-- MASTER DATA -->
                                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                                            <button type="button" class="shortcut-category" data-menu="master">
                                                <i class="fas fa-database"></i>
                                                <span>MASTER DATA</span>
                                            </button>
                                        </div>

                                    </div>


                                    <!-- ================= PEMBATAS ================= -->
                                    <hr class="my-2">


                                    <!-- =====================================================
                     MP
                ====================================================== -->
                                    <div class="shortcut-menu" id="shortcut-mp">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-cogs text-primary mr-2"></i>
                                            MP
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin_mp') ?>" class="shortcut-card">
                                                    <i class="fas fa-cogs"></i>
                                                    <div class="shortcut-title">
                                                        Pengecekan Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('') ?>" class="shortcut-card">
                                                    <i class="fas fa-recycle"></i>
                                                    <div class="shortcut-title">
                                                        Penggunaan Rework
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     FILLER
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-filler">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-industry text-info mr-2"></i>
                                            FILLER
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('counter/formcounter') ?>" class="shortcut-card">
                                                    <i class="fas fa-exchange-alt"></i>
                                                    <div class="shortcut-title">
                                                        Pergantian PVDC & Wire
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin_filler') ?>" class="shortcut-card">
                                                    <i class="fas fa-clipboard-check"></i>
                                                    <div class="shortcut-title">
                                                        Pengecekan Mesin
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     RETORT
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-retort">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-temperature-high text-danger mr-2"></i>
                                            RETORT
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin_retort') ?>" class="shortcut-card">
                                                    <i class="fas fa-cogs"></i>
                                                    <div class="shortcut-title">
                                                        Pengecekan Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('pergantian_varian_retort') ?>" class="shortcut-card">
                                                    <i class="fas fa-sync-alt"></i>
                                                    <div class="shortcut-title">
                                                        Pergantian Varian
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('rt_rjmesin') ?>" class="shortcut-card">
                                                    <i class="fas fa-times-circle"></i>
                                                    <div class="shortcut-title">
                                                        Reject Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('rr_cooking') ?>" class="shortcut-card">
                                                    <i class="fas fa-fire"></i>
                                                    <div class="shortcut-title">
                                                        Reject Cooking
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     PACKING
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-packing">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-box text-warning mr-2"></i>
                                            PACKING
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('zanasi') ?>" class="shortcut-card">
                                                    <i class="fas fa-print"></i>
                                                    <div class="shortcut-title">
                                                        Print Karton DOD
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin') ?>" class="shortcut-card">
                                                    <i class="fas fa-clipboard-check"></i>
                                                    <div class="shortcut-title">
                                                        Pengecekan Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('pergantian_varian_packing') ?>" class="shortcut-card">
                                                    <i class="fas fa-sync-alt"></i>
                                                    <div class="shortcut-title">
                                                        Pergantian Varian
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('filkar/filkarform') ?>" class="shortcut-card">
                                                    <i class="fas fa-filter"></i>
                                                    <div class="shortcut-title">
                                                        Filling Karantina
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('sortasi/formdata') ?>" class="shortcut-card">
                                                    <i class="fas fa-sort"></i>
                                                    <div class="shortcut-title">
                                                        Sortasi
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('pemusnahan_badproduct/') ?>" class="shortcut-card">
                                                    <i class="fas fa-trash"></i>
                                                    <div class="shortcut-title">
                                                        Pemusnahan Bad Produk
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     SANITASI
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-sanitasi">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-spray-can text-success mr-2"></i>
                                            SANITASI
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('sanitasi') ?>" class="shortcut-card">
                                                    <i class="fas fa-clipboard-check"></i>
                                                    <div class="shortcut-title">
                                                        Checklist Sanitasi
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('chemical/pengenceran') ?>" class="shortcut-card">
                                                    <i class="fas fa-flask"></i>
                                                    <div class="shortcut-title">
                                                        Pelarutan Chemical
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     PENDUKUNG
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-pendukung">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-tools text-secondary mr-2"></i>
                                            PENDUKUNG
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('pbelah/') ?>" class="shortcut-card">
                                                    <i class="fas fa-wine-glass"></i>
                                                    <div class="shortcut-title">
                                                        Barang Pecah Belah
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('Pbtajam/form_pbtajam/') ?>" class="shortcut-card">
                                                    <i class="fas fa-cut"></i>
                                                    <div class="shortcut-title">
                                                        Benda Tajam
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('tools_mesin/data/') ?>" class="shortcut-card">
                                                    <i class="fas fa-tools"></i>
                                                    <div class="shortcut-title">
                                                        Tools Mesin
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     ISO / TS
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-iso">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-certificate text-primary mr-2"></i>
                                            ISO / TS
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('gmp/tpm') ?>" class="shortcut-card">
                                                    <i class="fas fa-tasks"></i>
                                                    <div class="shortcut-title">
                                                        TPM
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('gmp') ?>" class="shortcut-card">
                                                    <i class="fas fa-chart-line"></i>
                                                    <div class="shortcut-title">
                                                        Monitoring
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('gmp/history') ?>" class="shortcut-card">
                                                    <i class="fas fa-history"></i>
                                                    <div class="shortcut-title">
                                                        History
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                     MASTER DATA
                ====================================================== -->
                                    <div class="shortcut-menu d-none" id="shortcut-master">

                                        <div class="shortcut-menu-title">
                                            <i class="fas fa-database text-dark mr-2"></i>
                                            MASTER DATA
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('area') ?>" class="shortcut-card">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <div class="shortcut-title">
                                                        Area
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('mesin') ?>" class="shortcut-card">
                                                    <i class="fas fa-cogs"></i>
                                                    <div class="shortcut-title">
                                                        Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin/dataitem') ?>" class="shortcut-card">
                                                    <i class="fas fa-list"></i>
                                                    <div class="shortcut-title">
                                                        Item Pengecekan
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>" class="shortcut-card">
                                                    <i class="fas fa-list-check"></i>
                                                    <div class="shortcut-title">
                                                        Item Pengecekan / Batch
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('tools_mesin') ?>" class="shortcut-card">
                                                    <i class="fas fa-tools"></i>
                                                    <div class="shortcut-title">
                                                        Tools Mesin
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('gmp/data') ?>" class="shortcut-card">
                                                    <i class="fas fa-file-alt"></i>
                                                    <div class="shortcut-title">
                                                        Kegiatan ISO/TS
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('sanitasi/kondisi') ?>" class="shortcut-card">
                                                    <i class="fas fa-check-circle"></i>
                                                    <div class="shortcut-title">
                                                        Kondisi Sanitasi
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('sanitasi/mtindakan') ?>" class="shortcut-card">
                                                    <i class="fas fa-hand-holding-medical"></i>
                                                    <div class="shortcut-title">
                                                        Tindakan Sanitasi
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                <a href="<?= base_url('pegawai') ?>" class="shortcut-card">
                                                    <i class="fas fa-users"></i>
                                                    <div class="shortcut-title">
                                                        Pegawai
                                                    </div>
                                                </a>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>


                    <!-- ================= SHORTCUT STYLE ================= -->
                    <style>
                        .shortcut-category {
                            width: 100%;
                            border: 1px solid #edf2f9;
                            background: #fff;
                            border-radius: 12px;
                            padding: 10px 8px;
                            color: #5a5c69;
                            cursor: pointer;
                            transition: all .2s ease;
                            font-weight: 600;
                            font-size: 12px;
                            box-shadow: 0 3px 10px rgba(0, 0, 0, .04);
                        }

                        .shortcut-category i {
                            display: block;
                            font-size: 17px;
                            margin-bottom: 4px;
                        }

                        .shortcut-category:hover {
                            transform: translateY(-3px);
                            border-color: #4e73df;
                            background: #f8faff;
                            color: #4e73df;
                        }

                        .shortcut-category.active {
                            background: #4e73df;
                            border-color: #4e73df;
                            color: #fff;
                            box-shadow: 0 5px 15px rgba(78, 115, 223, .25);
                        }


                        .shortcut-menu-title {
                            font-size: 14px;
                            font-weight: 700;
                            color: #3a3b45;
                            margin-bottom: 12px;
                        }


                        .shortcut-card {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            text-align: center;

                            background: #fff;
                            border: 1px solid #edf2f9;
                            border-radius: 15px;

                            padding: 15px 10px;
                            min-height: 90px;

                            color: #5a5c69;
                            transition: all .2s ease;

                            box-shadow: 0 5px 15px rgba(0, 0, 0, .05);

                            text-decoration: none !important;
                        }

                        .shortcut-card i {
                            font-size: 20px;
                            margin-bottom: 7px;
                        }

                        .shortcut-card:hover {
                            transform: translateY(-5px);
                            color: #4e73df;
                            border-color: #4e73df;
                            background: #f8faff;
                            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
                        }

                        .shortcut-title {
                            font-size: 12px;
                            font-weight: 600;
                            line-height: 1.3;
                        }
                    </style>

                    <!-- ================= SHORTCUT SCRIPT ================= -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            const categoryButtons = document.querySelectorAll('.shortcut-category');
                            const shortcutMenus = document.querySelectorAll('.shortcut-menu');

                            categoryButtons.forEach(function(button) {

                                button.addEventListener('click', function() {

                                    const menu = this.getAttribute('data-menu');

                                    /* Hapus active dari semua kategori */
                                    categoryButtons.forEach(function(btn) {
                                        btn.classList.remove('active');
                                    });

                                    /* Aktifkan kategori yang dipilih */
                                    this.classList.add('active');

                                    /* Sembunyikan semua menu */
                                    shortcutMenus.forEach(function(item) {
                                        item.classList.add('d-none');
                                    });

                                    /* Tampilkan menu yang dipilih */
                                    const selectedMenu =
                                        document.getElementById('shortcut-' + menu);

                                    if (selectedMenu) {

                                        selectedMenu.classList.remove('d-none');

                                        /* Tunggu browser selesai menampilkan menu */
                                        setTimeout(function() {

                                            selectedMenu.scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'start'
                                            });

                                        }, 50);
                                    }

                                });

                            });

                        });
                    </script>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>


</body>

</html>