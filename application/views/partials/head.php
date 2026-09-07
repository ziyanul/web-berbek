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
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/datatables.min.css'); ?>">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
        integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/lightbox/lightbox.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css'); ?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <style>
    .dropdown-user-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eaecf4;
    }

    .dropdown-user-header .name {
        font-weight: 700;
        color: #3a3b45;
        margin-bottom: 2px;
    }

    .dropdown-user-header .meta {
        font-size: 0.8rem;
        color: #858796;
    }


    .sidebar-brand-icon {
        width: 200px;
    }

    .sidebar-brand-icon img {
        width: 200%;
    }

    @media (min-width: 768px) {
        .sidebar {
            width: 20rem !important;
        }

        .sidebar-brand-icon img {
            width: 100%;
        }

        @media (min-width: 768px) {
            .sidebar {
                width: 20rem !important;
            }

            .sidebar .nav-item .nav-link {
                width: 100% !important;
            }
        }

    }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php
        $type     = $this->session->userdata('type');
        $subrole  = $this->session->userdata('subrole');
        ?>

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- LOGO -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('home') ?>">
                <div class="sidebar-brand-icon mt-5">
                    <img src="<?= base_url('assets/img/Prod1.png'); ?>" alt="Logo"
                        style="max-width: 100%; height: auto;">
                </div>
            </a>

            <!-- DASHBOARD -->
            <li class="nav-item mt-5 <?= $active_nav == 'home' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('home') ?>">
                    <i class="fa fa-home"></i>
                    <span>DASHBOARD</span>
                </a>
            </li>


            <?php if (is_admin()) { ?>

            <!-- GRAFIK -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('view') ?>">
                    <i class="fa fa-chart-line"></i>
                    <span>DATA GRAFIK</span>
                </a>
            </li>

            <?php } ?>
            <?php if (is_admin() || is_produksi()) { ?>

            <!-- PLAN PRODUKSI -->
            <li class="nav-item <?= $active_nav == 'filler' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('filler/planning') ?>">
                    <i class="fa fa-list"></i>
                    <span>PLAN PRODUKSI</span>
                </a>
            </li>
            <li class="nav-item <?= $active_nav == 'counter' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('counter') ?>">
                    <i class="fa fa-calculator"></i> <span>COUNTER FILLER</span></a>
            </li>
            <?php } ?>

            <hr class="sidebar-divider">

            <!-- ================= MAINTENANCE ================= -->

            <li class="nav-item <?= in_array($active_nav, ['pm', 'pm-tpm', 'pm-history']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#maintenanceMenu">
                    <span>PREVENTIVE MAINTENANCE</span>
                </a>

                <div id="maintenanceMenu"
                    class="collapse <?= in_array($active_nav, ['pm', 'pm-tpm', 'pm-history']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item <?= $active_nav == 'pm-tpm' ? 'active' : '' ?>"
                            href="<?= base_url('pm/tpm') ?>">PENGAJUAN</a>

                        <a class="collapse-item <?= $active_nav == 'pm' ? 'active' : '' ?>"
                            href="<?= base_url('pm') ?>">MONITORING</a>

                        <a class="collapse-item <?= $active_nav == 'pm-history' ? 'active' : '' ?>"
                            href="<?= base_url('pm/history') ?>">HISTORY</a>

                    </div>
                </div>
            </li>

            <!-- ================= AUTONOMOUS MAINTENANCE ================= -->

            <li class="nav-item <?= in_array($active_nav, ['am', 'am-tpm', 'am-history']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#amMenu">
                    <span>AUTONOMOUS MAINTENANCE</span>
                </a>

                <div id="amMenu"
                    class="collapse <?= in_array($active_nav, ['am', 'am-tpm', 'am-history']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item <?= $active_nav == 'am-tpm' ? 'active' : '' ?>"
                            href="<?= base_url('am/tpm') ?>">PLANNING</a>

                        <a class="collapse-item <?= $active_nav == 'am' ? 'active' : '' ?>"
                            href="<?= base_url('am') ?>">TASK</a>

                        <a class="collapse-item <?= $active_nav == 'am-history' ? 'active' : '' ?>"
                            href="<?= base_url('am/history') ?>">HISTORY</a>
                    </div>
                </div>
            </li>


            <!-- ================= PART ================= -->
            <?php if (is_admin() || is_produksi() || is_engineering() || is_warehouse()) { ?>
            <li class="nav-item <?= in_array($active_nav, ['tpm-part', 'monitor', 'histori-part']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#partMenu">
                    <span>PART</span>
                </a>
                <div id="partMenu"
                    class="collapse <?= in_array($active_nav, ['tpm-part', 'monitor', 'histori-part']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'tpm-part' ? 'active' : '' ?>"
                            href="<?= base_url('monitor/tpm') ?>">PENGAJUAN</a>
                        <a class="collapse-item <?= $active_nav == 'monitor' ? 'active' : '' ?>"
                            href="<?= base_url('monitor') ?>">MONITORING</a>
                        <a class="collapse-item <?= $active_nav == 'histori-part' ? 'active' : '' ?>"
                            href="<?= base_url('monitor/history') ?>">HISTORY</a>
                    </div>
                </div>
            </li>


            <?php if ($type == 1 || $type == 2) { ?>
            <!-- ================= REPAIR PART ================= -->

            <li
                class="nav-item <?= $active_nav == 'pengajuan-part' || $active_nav == 'pengajuan-history' ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#repairPart">
                    <span>REPAIR & NEW PART</span>
                </a>
                <div id="repairPart"
                    class="collapse <?= $active_nav == 'pengajuan-part' || $active_nav == 'pengajuan-history' ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'pengajuan-part' ? 'active' : '' ?>"
                            href="<?= base_url('partrequest') ?>">PENGAJUAN</a>
                        <a class="collapse-item <?= $active_nav == 'pengajuan-history' ? 'active' : '' ?>"
                            href="<?= base_url('partrequest/history') ?>">HISTORY</a>
                    </div>
                </div>
            </li>
            <?php } ?>
            <?php } ?>
            <!-- ================= MANUAL BOOK ================= -->
            <?php if (is_admin() || is_engineering()) { ?>
            <li class="nav-item <?= $active_nav == 'manual_books' ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#manualBook">
                    <span>MANUAL BOOKS</span>
                </a>
                <div id="manualBook" class="collapse <?= $active_nav == 'manual_books' ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'manual_books' ? 'active' : '' ?>"
                            href="<?= base_url('manual_books') ?>">MANUAL BOOKS</a>
                    </div>
                </div>
            </li>
            <?php } ?>

            <?php if (is_admin() || is_produksi()) { ?>
            <!-- ================= FORM ================= -->

            <li
                class="nav-item <?= in_array($active_nav, ['formcounter', 'zanasi', 'cekmesin', 'sanitasi-data', 'rework', 'pergantian_varian', 'pengenceran']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenu">
                    <span>FORM</span>
                </a>
                <div id="formMenu"
                    class="collapse <?= in_array($active_nav, ['formcounter', 'zanasi', 'cekmesin', 'sanitasi-data', 'rework', 'pergantian_varian', 'pengenceran']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'counter' ? 'active' : '' ?>"
                            href="<?= base_url('counter/') ?>">counter</a>

                        <a class="collapse-item <?= $active_nav == 'formcounter' ? 'active' : '' ?>"
                            href="<?= base_url('counter/formcounter') ?>">Pergantian PVDC dan Wire</a>

                        <a class="collapse-item <?= $active_nav == 'zanasi' ? 'active' : '' ?>"
                            href="<?= base_url('zanasi') ?>">Print Karton DOD</a>
                        <!-- <a class="collapse-item <?= $active_nav == 'cekmesin' ? 'active' : '' ?>" href="<?= base_url('cekmesin') ?>">Pengecekan Mesin</a>
            <a class="collapse-item <?= $active_nav == 'sanitasi-data' ? 'active' : '' ?>" href="<?= base_url('sanitasi') ?>">Checklist Sanitasi</a> -->
                        <a class="collapse-item <?= $active_nav == 'rework' ? 'active' : '' ?>"
                            href="<?= base_url('mpusage/') ?>">Formula & Rework</a>
                        <a class="collapse-item <?= $active_nav == 'pergantian_varian' ? 'active' : '' ?>"
                            href="<?= base_url('pergantian_varian') ?>">Pergantian Varian</a>
                        <a class="collapse-item <?= $active_nav == 'pengenceran' ? 'active' : '' ?>"
                            href="<?= base_url('chemical/pengenceran') ?>">Pelarutan Chemical</a>
                    </div>
                </div>
            </li>
            <?php } ?>


            <?php
            $type = $this->session->userdata('type');
            $subrole = $this->session->userdata('subrole');
            if (is_admin() || is_produksi()) { ?>
            <li
                class="nav-item <?= $active_nav == 'gmp-tpm' || $active_nav == 'gmp-history' || $active_nav == 'gmp' ? 'active' : ''; ?>">

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsehis"
                    aria-expanded="true" aria-controls="collapsehis">
                    <span>ISO/TS</span>
                </a>
                <div id="collapsehis"
                    class="collapse <?= $active_nav == 'gmp-tpm' || $active_nav == 'gmp' || $active_nav == 'gmp-history' ? 'show' : ''; ?>"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item <?= $active_nav == 'gmp-tpm' ? 'active' : ''; ?>"
                            href="<?= base_url('gmp/tpm') ?>">TPM</a>
                        <a class="collapse-item <?= $active_nav == 'gmp' ? 'active' : ''; ?>"
                            href="<?= base_url('gmp') ?>">MONITORING</a>


                        <a class="collapse-item <?= $active_nav == 'gmp-history' ? 'active' : ''; ?>"
                            href="<?= base_url('gmp/history') ?>">HISTORY</a>

                    </div>
                </div>
            </li>
            <?php } ?>

            <hr class="sidebar-divider">
            <?php if ($type == 1 || $type == 2) { ?>

            <li class="nav-item <?= in_array($active_nav, [
                                        'area',
                                        'mesin',
                                        'bahan',
                                        'sparepart',
                                        'am-data',
                                        'kegiatan-am',
                                        'masterspeed',
                                        'gmp-area',
                                        'gmp-data',
                                        'm_kondisi',
                                        'varian',
                                        'm_tindakan'
                                    ]) ? 'active' : ''; ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseMaster">
                    <span>MASTER DATA</span>
                </a>
                <div id="collapseMaster" class="collapse <?= in_array($active_nav, [
                                            'area',
                                            'mesin',
                                            'bahan',
                                            'sparepart',
                                            'am-data',
                                            'kegiatan-am',
                                            'masterspeed',
                                            'gmp-area',
                                            'gmp-data',
                                            'm_kondisi',
                                            'varian',
                                            'm_tindakan'
                                        ]) ? 'show' : ''; ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!-- MESIN & PART -->
                        <h6 class="collapse-header">MESIN & PART</h6>
                        <a class="collapse-item <?= $active_nav == 'area' ? 'active' : ''; ?>"
                            href="<?= base_url('area') ?>">AREA</a>
                        <a class="collapse-item <?= $active_nav == 'mesin' ? 'active' : ''; ?>"
                            href="<?= base_url('mesin') ?>">MESIN</a>
                        <a class="collapse-item <?= $active_nav == 'sparepart' ? 'active' : ''; ?>"
                            href="<?= base_url('part') ?>">SPAREPART</a>


                        <!-- DATA AM -->
                        <h6 class="collapse-header">DATA AM</h6>

                        <a class="collapse-item <?= $active_nav == 'am-data' ? 'active' : ''; ?>"
                            href="<?= base_url('am/data') ?>">KEGIATAN AM</a>


                        <!-- DATA ISO -->
                        <h6 class="collapse-header">DATA ISO/TS</h6>

                        <a class="collapse-item <?= $active_nav == 'gmp-data' ? 'active' : ''; ?>"
                            href="<?= base_url('gmp/data') ?>">KEGIATAN ISO/TS</a>

                        <a class="collapse-item <?= $active_nav == 'm_kondisi' ? 'active' : ''; ?>"
                            href="<?= base_url('sanitasi/kondisi') ?>">KONDISI SANITASI</a>
                        <a class="collapse-item <?= $active_nav == 'm_tindakan' ? 'active' : ''; ?>"
                            href="<?= base_url('sanitasi/mtindakan') ?>">TINDAKAN SANITASI</a>

                        <!-- PENGECEKAN MESIN -->
                        <h6 class="collapse-header">PENGECEKAN MESIN</h6>

                        <a class="collapse-item <?= $active_nav == 'kegiatan-am' ? 'active' : ''; ?>"
                            href="<?= base_url('cekmesin/dataitem') ?>">ITEM PENGECEKAN</a>

                        <a class="collapse-item <?= $active_nav == 'masterspeed' ? 'active' : ''; ?>"
                            href="<?= base_url('speed') ?>">SPEED FILLER</a>

                        <!-- LAIN-LAIN -->
                        <h6 class="collapse-header">LAIN-LAIN</h6>

                        <a class="collapse-item <?= $active_nav == 'varian' ? 'active' : ''; ?>"
                            href="<?= base_url('varian') ?>">VARIAN SOSIS</a>
                        <a class="collapse-item <?= $active_nav == 'bahan' ? 'active' : ''; ?>"
                            href="<?= base_url('bahan') ?>">BAHAN BAKU</a>

                    </div>
                </div>
            </li>

            <?php } ?>

            <?php if (is_admin() || is_produksi() || is_warehouse() || is_engineering() && $type == 1 || $type == 2) { ?>
            <li class="nav-item <?= in_array($active_nav, ['pegawai']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#pegawai">
                    <span>PEGAWAI</span>
                </a>
                <div id="pegawai" class="collapse <?= in_array($active_nav, ['pegawai']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'pegawai' ? 'active' : '' ?>"
                            href="<?= base_url('pegawai') ?>">PEGAWAI</a>
                    </div>
                </div>
            </li>
            <?php } ?>

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- ================= TOPBAR ================= -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Info User -->
                    <div class="d-none d-sm-flex flex-column">
                        <span class="h6 text-primary">
                            <?= $this->session->userdata('fullname'); ?> |
                            <?= $this->session->userdata('departemen'); ?>
                        </span>
                    </div>

                    <!-- Right Menu -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-dark small font-weight-bold">
                                    Hi, <?= ucfirst($this->session->userdata('username')); ?>
                                </span>
                                <img src="<?= base_url('assets/img/Oooo.jpeg'); ?>" alt="Profile"
                                    class="rounded-circle border shadow-sm"
                                    style="width:38px; height:38px; object-fit:cover;">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown" style="min-width: 260px;">

                                <div class="dropdown-user-header">
                                    <div class="name"><?= $this->session->userdata('fullname'); ?></div>
                                    <div class="meta">
                                        <?= $this->session->userdata('username'); ?> •
                                        <?= $this->session->userdata('departemen'); ?>
                                    </div>
                                </div>

                                <a class="dropdown-item"
                                    href="<?= base_url('pegawai/edit_password/' . $this->session->userdata('user_uuid')); ?>">
                                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-primary"></i>
                                    Ganti Password
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-danger" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>