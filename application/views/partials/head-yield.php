<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title ?? ' Yield | CPI-Berbek' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/datatables.min.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/lightbox/lightbox.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css'); ?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js">
    </script>
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('portal') ?>">
                <div class="sidebar-brand-icon mt-5">
                    <img src="<?= base_url('assets/img/Prod1.png'); ?>" alt="Logo" style="max-width: 100%; height: auto;">
                </div>
            </a>
            <!-- DASHBOARD -->
            <li class="nav-item mt-5 <?= $active_nav == 'home' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('yieldportal/dashboard') ?>">
                    <i class="fa fa-home"></i>
                    <span>DASHBOARD</span>
                </a>
            </li>
            <li class="nav-item <?= $active_nav == 'filler' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('yieldportal/analisa') ?>">
                    <i class="fa fa-list"></i>
                    <span>ANALISA</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <?php if (is_admin()) { ?>
            <?php } ?>
            <?php if (is_admin() || is_produksi()) { ?>
                <!-- PLAN PRODUKSI -->
                <li class="nav-item <?= $active_nav == 'filler' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('filler/planning') ?>">
                        <i class="fa fa-list"></i>
                        <span>PLAN PRODUKSI</span>
                    </a>
                </li>
                <li class="nav-item <?= $active_nav == 'mpusage' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('mpusage') ?>">
                        <i class="fa fa-blender"></i> <span>FORMULA & REWORK</span></a>
                </li>
                <li class="nav-item <?= $active_nav == 'counter' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('counter') ?>">
                        <i class="fa fa-calculator"></i> <span>COUNTER FILLER</span></a>
                </li>
                <li class="nav-item <?= $active_nav == 'filkar' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('filkar') ?>">
                        <i class="fa fa-box"></i> <span>FILLING KARANTINA</span></a>
                </li>
                <li class="nav-item <?= $active_nav == 'sortasi' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('sortasi') ?>">
                        <i class="fa fa-check"></i> <span>SORTASI</span></a>
                </li>

                <li class="nav-item <?= $active_nav == 'rework' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('rework/kupas') ?>">
                        <i class="fa fa-recycle"></i> <span>REWORK</span></a>
                </li>

            <?php } ?>
            <hr class="sidebar-divider">
            <hr class="sidebar-divider">
            <li class="nav-item <?= $active_nav == 'formula' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('formula') ?>">
                    <i class="fa fa-calculator"></i> <span>FORMULA</span></a>
            </li>
            <?php if ($type == 1 || $type == 2) { ?>
                <li class="nav-item <?= in_array($active_nav, [
                                        'area',
                                        'bahan',
                                        'sparepart',
                                        'am-data',
                                        'kegiatan-am',
                                        'masterspeed',
                                        'gmp-area',
                                        'gmp-data',
                                        'm_kondisi',
                                        'varian',
                                        'm-badpro'
                                    ]) ? 'active' : ''; ?>">
                    <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseMaster">
                        <span><i class="fa fa-table"></i> MASTER DATA</span>
                    </a>
                    <div id="collapseMaster" class="collapse <?= in_array($active_nav, [
                                                                    'area',
                                                                    'bahan',
                                                                    'sparepart',
                                                                    'am-data',
                                                                    'kegiatan-am',
                                                                    'masterspeed',
                                                                    'gmp-area',
                                                                    'gmp-data',
                                                                    'm_kondisi',
                                                                    'varian',
                                                                    'm-badpro'
                                                                ]) ? 'show' : ''; ?>">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item <?= $active_nav == 'area' ? 'active' : ''; ?>" href="<?= base_url('area_proses') ?>">AREA PROSES</a>
                            <a class="collapse-item <?= $active_nav == 'varian' ? 'active' : ''; ?>" href="<?= base_url('varian') ?>">VARIAN SOSIS</a>
                            <a class="collapse-item <?= $active_nav == 'bahan' ? 'active' : ''; ?>" href="<?= base_url('bahan') ?>">BAHAN BAKU</a>

                            <a class="collapse-item <?= $active_nav == 'm-badpro' ? 'active' : ''; ?>" href="<?= base_url('badpro') ?>">BAD PRODUK</a>
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
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-dark small font-weight-bold">
                                    Hi, <?= ucfirst($this->session->userdata('username')); ?>
                                </span>
                                <img src="<?= base_url('assets/img/Oooo.jpeg'); ?>" alt="Profile" class="rounded-circle border shadow-sm" style="width:38px; height:38px; object-fit:cover;">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="min-width: 260px;">
                                <div class="dropdown-user-header">
                                    <div class="name"><?= $this->session->userdata('fullname'); ?></div>
                                    <div class="meta">
                                        <?= $this->session->userdata('username'); ?> •
                                        <?= $this->session->userdata('departemen'); ?>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="<?= base_url('pegawai/edit_password/' . $this->session->userdata('user_uuid')); ?>">
                                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-primary"></i>
                                    Ganti Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>