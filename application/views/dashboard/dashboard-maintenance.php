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
            background: #f8f9fc;
        }

        .portal-header {
            background: linear-gradient(135deg,
                    #1e3c72,
                    #2a5298);
            color: white;
            border-radius: 15px;
        }

        .portal-card {
            transition: .2s;
        }

        .portal-card:hover {
            transform: translateY(-4px);
        }

        .portal-menu {
            transition: .2s;
            border: 1px solid #eaecf4;
        }

        .portal-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        }

        .portal-icon {
            font-size: 42px;
        }

        .dropdown-user-header {
            padding: .75rem 1rem;
            border-bottom: 1px solid #eaecf4;
        }

        .dropdown-user-header .name {
            font-weight: 700;
        }

        .dropdown-user-header .meta {
            font-size: .8rem;
            color: #858796;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php
        $type     = $this->session->userdata('type');
        $subrole  = $this->session->userdata('subrole');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>
                            <a href="<?= base_url('portal') ?>" class="badge badge-info px-3 py-2">
                                <i class="fa fa-lg fa-home mr-1"></i>
                                PORTAL
                            </a>
                        </div>
                        <h2 class="font-weight-bold text-dark mb-0">
                            DASHBOARD MAINTENANCE</h2>

                        <div>
                            <span class="badge badge-info px-3 py-2 ml-1" id="clock">
                                <div><?= date('d F Y H:i:s'); ?></div>
                            </span>
                        </div>

                    </div>
                    <!-- PAGE TITLE -->
                    <div class="portal-header p-4 mb-4">

                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <p class="mb-0">
                                    Preventive Maintenance, Autonomous Maintenance,
                                    Sparepart Management dan Repair System
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- KPI -->
                    <div class="row">

                        <!-- PM -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="<?= base_url('pm') ?>" class="text-decoration-none">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center">

                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    PM Urgent
                                                </div>

                                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                    <?= $maintenance ?? 0 ?>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <i class="fas fa-tools fa-2x text-gray-300"></i>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- AM -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="<?= base_url('am') ?>" class="text-decoration-none">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    AM Hari Ini
                                                </div>

                                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                    <?= $auto; ?>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- SPAREPART -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="<?= base_url('monitor/') ?>" class="text-decoration-none">
                                <div class="card border-left-info shadow h-100 py-2">

                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Lifetime Alert
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-warning font-weight-bold">
                                                        <i class="fas fa-exclamation-triangle"></i> Warning
                                                    </span>
                                                    <span class="font-weight-bold text-gray-800">
                                                        <?= $monitor['warning']; ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-danger font-weight-bold">
                                                        <i class="fas fa-times-circle"></i> Over Lifetime
                                                    </span>
                                                    <span class="font-weight-bold text-gray-800">
                                                        <?= $monitor['over_lifetime']; ?>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- REPAIR -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <a href="<?= base_url('partrequest/') ?>" class="text-decoration-none">
                                <div class="card border-left-success shadow h-100 py-2">

                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col mr-2">

                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-3">
                                                    New & Repair Sparepart
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-primary font-weight-bold">
                                                        <i class="fas fa-plus-circle"></i> New
                                                    </span>

                                                    <span class="font-weight-bold text-gray-800">
                                                        <?= $pengajuan['new']; ?>
                                                    </span>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <span class="text-warning font-weight-bold">
                                                        <i class="fas fa-tools"></i> Repair
                                                    </span>

                                                    <span class="font-weight-bold text-gray-800">
                                                        <?= $pengajuan['repair']; ?>
                                                    </span>
                                                </div>

                                            </div>

                                            <div class="col-auto">
                                                <i class="fas fa-cogs fa-2x text-gray-300"></i>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </a>
                        </div>

                    </div>

                    <!--  -->
                    <!-- ANALYTICS -->
                    <div class="row">

                        <!-- TOP PM -->
                        <div class="col-lg-6 mb-4">

                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger">
                                        Top Mesin PM
                                    </h6>
                                </div>

                                <div class="card-body">

                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mesin</th>
                                                <th>Total PM</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($top_pm as $row) { ?>
                                                <tr>
                                                    <td><?= $row->nama_mesin ?></td>
                                                    <td><?= number_format($row->total) ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>

                        </div>

                        <!-- TOP PART -->
                        <div class="col-lg-6 mb-4">

                            <div class="card shadow">

                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        Part Paling Sering Diganti
                                    </h6>
                                </div>

                                <div class="card-body">

                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Part</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php if (!empty($top_part)) { ?>

                                                <?php foreach ($top_part as $row) { ?>

                                                    <tr>
                                                        <td><?= $row->part ?></td>
                                                        <td><?= $row->total ?></td>
                                                    </tr>

                                                <?php } ?>

                                            <?php } ?>

                                        </tbody>

                                    </table>

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
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
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
</body>

</html>