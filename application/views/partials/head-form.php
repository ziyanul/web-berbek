<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $title ?? ' Paperless | CPI-Berbek' ?></title>
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
                <a class="nav-link" href="<?= base_url('paperless') ?>">
                    <i class="fa fa-home"></i>
                    <span>DASHBOARD</span>
                </a>
            </li>



            <!-- ================= FORM MP ================= -->

            <li class="nav-item <?= in_array($active_nav, ['cekmesin-mp', 'rework']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuMp">
                    <span>MP</span>
                </a>
                <div id="formMenuMp" class="collapse <?= in_array($active_nav, ['cekmesin-mp', 'rework']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'cekmesin-mp' ? 'active' : '' ?>" href="<?= base_url('cekmesin_mp') ?>">Pengecekan Mesin</a>
                        <a class="collapse-item <?= $active_nav == 'rework' ? 'active' : '' ?>" href="<?= base_url('') ?>">Penggunaan Rework</a>

                    </div>
                </div>
            </li>
            <!-- ================= FORM FILLER ================= -->
            <li class="nav-item <?= in_array($active_nav, ['formcounter', 'cekmesin', 'cekmesin_filler']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuFil">
                    <span>FILLER</span>
                </a>
                <div id="formMenuFil" class="collapse <?= in_array($active_nav, ['formcounter', 'cekmesin', 'cekmesin_filler']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'formcounter' ? 'active' : '' ?>" href="<?= base_url('counter/formcounter') ?>">Pergantian PVDC dan Wire</a>
                        <a class="collapse-item <?= $active_nav == 'cekmesin_filler' ? 'active' : '' ?>" href="<?= base_url('cekmesin_filler') ?>">Pengecekan Mesin</a>
                    </div>
                </div>
            </li>

            <!-- ================= FORM SUSUN ================= -->
            <li class="nav-item <?= in_array($active_nav, ['formcounter', 'cekmesin_susun']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuSus">
                    <span>SUSUN</span>
                </a>
                <div id="formMenuSus" class="collapse <?= in_array($active_nav, ['formcounter', 'cekmesin', 'cekmesin_susun']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'cekmesin_susun' ? 'active' : '' ?>" href="<?= base_url('cekmesin_susun') ?>">Pengecekan Mesin</a>
                    </div>
                </div>
            </li>

            <!-- ================= FORM RETORT ================= -->
            <li class="nav-item <?= in_array($active_nav, ['cekmesin_retort', 'pergantian_varian_retort', 'rt_rjmesin']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuRt">
                    <span>RETORT</span>
                </a>
                <div id="formMenuRt" class="collapse <?= in_array($active_nav, ['cekmesin_retort', 'pergantian_varian_retort', 'rt_rjmesin']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'cekmesin_retort' ? 'active' : '' ?>" href="<?= base_url('cekmesin_retort') ?>">Pengecekan Mesin</a>
                        <a class="collapse-item <?= $active_nav == 'pergantian_varian_retort' ? 'active' : '' ?>" href="<?= base_url('pergantian_varian_retort') ?>">Pergantian Varian</a>
                        <a class="collapse-item <?= $active_nav == 'rt_rjmesin' ? 'active' : '' ?>" href="<?= base_url('rt_rjmesin') ?>">Reject Mesin di Retort</a>
                        <a class="collapse-item <?= $active_nav == 'rr_cooking' ? 'active' : '' ?>" href="<?= base_url('rr_cooking') ?>">Reject Cooking di Retort</a>
                    </div>
                </div>
            </li>

            <!-- ================= FORM PACKING ================= -->
            <li class="nav-item <?= in_array($active_nav, ['zanasi', 'cekmesin', 'pergantian_varian_packing', 'filkar', 'pemusnahan_badproduct']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuPc">
                    <span>PACKING</span>
                </a>
                <div id="formMenuPc" class="collapse <?= in_array($active_nav, ['zanasi', 'cekmesin', 'pergantian_varian_packing', 'filkar', 'pemusnahan_badproduct']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'zanasi' ? 'active' : '' ?>" href="<?= base_url('zanasi') ?>">Print Karton DOD</a>
                        <a class="collapse-item <?= $active_nav == 'cekmesin' ? 'active' : '' ?>" href="<?= base_url('cekmesin') ?>">Pengecekan Mesin</a>
                        <a class="collapse-item <?= $active_nav == 'pergantian_varian_packing' ? 'active' : '' ?>" href="<?= base_url('pergantian_varian_packing') ?>">Pergantian Varian</a>
                        <a class="collapse-item <?= $active_nav == 'filkar' ? 'active' : ''; ?>" href="<?= base_url('filkar/filkarform') ?>">Filling karantina (Filkar)</a>
                        <a class="collapse-item <?= $active_nav == 'sortasi' ? 'active' : ''; ?>" href="<?= base_url('sortasi/formdata') ?>">Sortasi</a>
                        <a class="collapse-item <?= $active_nav == 'pemusnahan_badproduct' ? 'active' : ''; ?>" href="<?= base_url('pemusnahan_badproduct/') ?>">Pemusnahan Bad Produk (Reject)</a>
                    </div>
                </div>
            </li>

            <!-- ================= FORM SANITASI ================= -->
            <!-- <li class="nav-item <?= in_array($active_nav, ['sanitasi-data', 'pengenceran']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuSan">
                    <span>SANITASI</span>
                </a>
                <div id="formMenuSan" class="collapse <?= in_array($active_nav, ['sanitasi-data', 'pengenceran']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'sanitasi-data' ? 'active' : '' ?>" href="<?= base_url('sanitasi') ?>">Checklist Sanitasi</a>
                        <a class="collapse-item <?= $active_nav == 'pengenceran' ? 'active' : '' ?>" href="<?= base_url('chemical/pengenceran') ?>">Pelarutan Chemical</a>
                    </div>
                </div>
            </li> -->
            <!-- ================= FORM PENDUKUNG ================= -->
            <li class="nav-item <?= in_array($active_nav, ['f-tl', 'Pbtajam/form_pbtajam', 'pbelah']) ? 'active' : '' ?>">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#formMenuPEN">
                    <span>PENDUKUNG</span>
                </a>
                <div id="formMenuPEN" class="collapse <?= in_array($active_nav, ['f-tl', 'Pbtajam/form_pbtajam', 'pbelah']) ? 'show' : '' ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?= $active_nav == 'pbelah' ? 'active' : ''; ?>" href="<?= base_url('pbelah/') ?>">Barang Pecah Belah</a>
                        <a class="collapse-item <?= $active_nav == 'Pbtajam/form_pbtajam' ? 'active' : ''; ?>" href="<?= base_url('Pbtajam/form_pbtajam/') ?>">Benda Tajam</a>
                        <a class="collapse-item <?= $active_nav == 'f-tl' ? 'active' : ''; ?>" href="<?= base_url('tools_mesin/data/') ?>">Tools Mesin</a>
                    </div>
                </div>
            </li>


            <!-- <li class="nav-item <?= $active_nav == 'gmp-tpm' || $active_nav == 'gmp-history' || $active_nav == 'gmp' ? 'active' : ''; ?>">

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsehis" aria-expanded="true" aria-controls="collapsehis">
                    <span>ISO/TS</span>
                </a>
                <div id="collapsehis" class="collapse <?= $active_nav == 'gmp-tpm' || $active_nav == 'gmp' || $active_nav == 'gmp-history' ? 'show' : ''; ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item <?= $active_nav == 'gmp-tpm' ? 'active' : ''; ?>" href="<?= base_url('gmp/tpm') ?>">TPM</a>
                        <a class="collapse-item <?= $active_nav == 'gmp' ? 'active' : ''; ?>" href="<?= base_url('gmp') ?>">MONITORING</a>


                        <a class="collapse-item <?= $active_nav == 'gmp-history' ? 'active' : ''; ?>" href="<?= base_url('gmp/history') ?>">HISTORY</a>

                    </div>
                </div>
            </li> -->

            <hr class="sidebar-divider">
            <?php if ($type == 1 || $type == 2) { ?>

                <li class="nav-item <?= in_array($active_nav, [
                                        'area', 'mesin', 'tl_mesin', 'sparepart', 'am-data', 'item-cm', 'masterspeed',
                                        'gmp-area', 'gmp-data', 'm_kondisi', 'varian', 'm_tindakan', 'pegawai', 'jenis-pbelah'
                                    ]) ? 'active' : ''; ?>">
                    <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseMaster">
                        <span>MASTER DATA</span>
                    </a>
                    <div id="collapseMaster" class="collapse <?= in_array($active_nav, [
                                                                    'area', 'mesin', 'tl_mesin', 'sparepart', 'am-data', 'item-cm', 'masterspeed',
                                                                    'gmp-area', 'gmp-data', 'm_kondisi', 'varian', 'm_tindakan', 'pegawai', 'jenis-pbelah'
                                                                ]) ? 'show' : ''; ?>">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!-- MESIN & PART -->
                            <a class="collapse-item <?= $active_nav == 'area' ? 'active' : ''; ?>" href="<?= base_url('area') ?>">AREA</a>
                            <a class="collapse-item <?= $active_nav == 'mesin' ? 'active' : ''; ?>" href="<?= base_url('mesin') ?>">MESIN</a>

                            <h6 class="collapse-header">PENGECEKAN MESIN</h6>
                            <a class="collapse-item <?= $active_nav == 'item-cm' ? 'active' : ''; ?>" href="<?= base_url('cekmesin/dataitem') ?>">ITEM PENGECEKAN</a>
                            <a class="collapse-item <?= $active_nav == 'dataitem-batch' ? 'active' : ''; ?>" href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>">ITEM PENGECEKAN / BATCH</a>
                            <a class="collapse-item <?= $active_nav == 'tl_mesin' ? 'active' : ''; ?>" href="<?= base_url('tools_mesin') ?>">TOOLS MESIN</a>
                            <h6 class="collapse-header">MASTER PECAH BELAH</h6>
                            <a class="collapse-item <?= $active_nav == 'jenis-pbelah' ? 'active' : ''; ?>" href="<?= base_url('pbelah/jenis') ?>">DATA PECAH BELAH</a>
                            <!-- DATA LAIN-LAIN -->
                            <h6 class="collapse-header">DATA LAIN-LAIN</h6>
                            <a class="collapse-item <?= $active_nav == 'pegawai' ? 'active' : '' ?>" href="<?= base_url('pegawai') ?>">PEGAWAI</a>




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