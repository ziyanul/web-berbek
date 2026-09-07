<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title ?? ' Packing | CPI-Berbek' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/Prod-title.png'); ?>">
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/vendor/fontawesome-free/webfonts/font-googleapis.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/datatables.min.css'); ?>">
    <link href="<?= base_url('assets/vendor/css/select2.min.css'); ?>" rel="stylesheet" />


    <link rel="stylesheet" href="<?= base_url('assets/vendor/daterangepicker/bootstrap-datepicker.min.css') ?>" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/lightbox/lightbox.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css'); ?>">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/js/select2.min.js'); ?>">
    </script>
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

    .dashboard-title {
        font-size: 16px;
        font-weight: 800;
        letter-spacing: .5px;
        color: #00ff80;
        border-radius: 10px;
        padding: 8px 20px;
        margin-bottom: 0px;
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
        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- LOGO -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('') ?>">
                <div class="sidebar-brand-icon mt-5">
                    <img src="<?= base_url('assets/img/Prod1.png'); ?>" alt="Logo"
                        style="max-width: 100%; height: auto;">
                </div>
            </a>
            <!-- DASHBOARD -->
            <li class="nav-item mt-5 <?= $active_nav == 'home' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('portal/maintenance') ?>">
                    <i class="fa fa-home"></i>
                    <span>DASHBOARD</span>
                </a>
            </li>

            <!-- Drystore -->
            <li class="nav-item mt-5 <?= $active_nav == 'Drystore' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('drystore') ?>">
                    <i class="fa fa-home"></i>
                    <span>DRYSTORE</span>
                </a>
            </li>

            <!-- ================= MASTER DATA ================= -->

                        <hr class="sidebar-divider">

            <?php if ($type == 1 || $type == 2) { ?>
            <li class="nav-item <?= in_array($active_nav, [
                                        'type-ds',
                                        'waste-ds',
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
                    <span>MASTER DATA</span>
                </a>
                <div id="collapseMaster" class="collapse <?= in_array($active_nav, [
                                            'type-ds',
                                            'waste-ds',
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
                        <h6 class="collapse-header">DRYSTORE</h6>
                        <a class="collapse-item <?= $active_nav=='type-ds'?'active':'';?>"
                            href="<?= base_url('drystore/type') ?>">TYPE</a>
                        <a class="collapse-item <?= $active_nav=='waste-ds'?'active':'';?>"
                            href="<?= base_url('drystore/waste') ?>">JENIS REJECT</a>

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
                    <div class="dashboard-title">
                        <?= $this->session->userdata('fullname'); ?> |
                            <?= $this->session->userdata('departemen'); ?>  |
                            PACKING
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