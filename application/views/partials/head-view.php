<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Monitoring Produksi</title>
	<link rel="icon" href="<?= base_url('assets/img/Prod-title.png'); ?>" type="image/x-icon">
	<link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
	<link
	href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
	rel="stylesheet">
	<link href="<?= base_url('assets/css/sb-admin-2.min.css');?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/custom.css');?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
	integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"
	integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css');?>">
	<script src="<?= base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
	<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

	<style>
		.sidebar-brand-icon {
			width: 125px;
		}

		.sidebar-brand-icon img {
			width: 100%;
		}

		.sidebar {
			position: fixed;
			top: 0;
			left: 0;
			bottom: 0;
			width: 14rem; /* Ganti dari 20rem ke 14rem biar lebih ramping */
			overflow-y: auto;
			z-index: 730; /* Agar berada di atas konten lain */
		}

		#content-wrapper {
			margin-left: 14rem;
		}


		@media (min-width: 768px) {
			.sidebar .nav-item .nav-link {
				width: 100% !important;
				padding-top: 12px;
				padding-bottom: 12px;
			}
		}

		/* Pusatkan kontrol panah di bawah */
		/* Letakkan indikator dan panah di bawah halaman */
		.carousel-bottom-nav {
			width: 100%;
			text-align: center;
			margin-top: 30px;
		}

		.carousel-indicators-custom {
			display: inline-flex;
			justify-content: center;
			padding: 0;
			margin: 0 15px;
			list-style: none;
		}

		.carousel-indicators-custom li {
			width: 10px;
			height: 10px;
			margin: 0 5px;
			background-color: #007bff;
			border-radius: 50%;
			cursor: pointer;
		}

		.carousel-indicators-custom .active {
			background-color: #0056b3;
		}

		.carousel-control-custom {
			background-color: #007bff;
			color: #fff;
			border: none;
			padding: 5px 12px;
			border-radius: 50%;
			cursor: pointer;
		}
	</style>
</head>

<body id="page-top">
	<div id="wrapper">
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('view') ?>">
				<i class="fa fa-chart-line"></i>
				<div class="sidebar-brand-text mx-3">DATA GRAFIK</div>
			</a>
			<hr class="sidebar-divider my-1">
			<li class="nav-item active">
				<a class="nav-link" href="<?= base_url('view/formula') ?>">

					<span style="font-size: 12px;">FORMULA & FILKAR</span></a>
				</li>
				<hr class="sidebar-divider my-0">
				<li class="nav-item active">
					<a class="nav-link" href="<?= base_url('view/performamesin') ?>">
						
						<span style="font-size: 12px;">PERFORMA PER MESIN</span></a>
					</li>
					<hr class="sidebar-divider my-0">
					<li class="nav-item active">
						<a class="nav-link" href="<?= base_url('rj_filler/') ?>">
							
							<span style="font-size: 12px;">REJECT FILLER PER MESIN & OPERATOR</span></a>
						</li>
						
							<hr class="sidebar-divider my-0">
							<li class="nav-item active">
								<a class="nav-link" href="<?= base_url('view/rj_cooking') ?>">
									
									<span style="font-size: 12px;">REJECT COOKING</span></a>
								</li>
								<hr class="sidebar-divider my-0">
								<li class="nav-item active">
									<a class="nav-link" href="<?= base_url('view/rj_mesin') ?>">
										
										<span style="font-size: 12px;">REJECT COOKING PER MESIN</span></a>
									</li>
									<hr class="sidebar-divider my-0">
									<li class="nav-item active">
										<a class="nav-link" href="<?= base_url('view/sortasi') ?>">
											
											<span style="font-size: 12px;">REJECT SORTASI</span></a>
										</li>
										<hr class="sidebar-divider my-0">
										<li class="nav-item active">
											<a class="nav-link" href="<?= base_url('view/srbadpro') ?>">
												
												<span style="font-size: 12px;">REJECT PER BAD PRODUK</span></a>
											</li>
											<hr class="sidebar-divider my-0">
											<li class="nav-item active">
												<a class="nav-link" href="<?= base_url('view/smfg') ?>">
													
													<span style="font-size: 12px;">REJECT SMFG</span></a>
												</li>
												<hr class="sidebar-divider my-0">
												<li class="nav-item active">
													<a class="nav-link" href="<?= base_url('view/smfgmsn') ?>">

														<span style="font-size: 12px;">REJECT SMFG per MESIN</span></a>
													</li>
													<hr class="sidebar-divider my-0">
													<li class="nav-item active">
													<a class="nav-link" href="<?= base_url('track') ?>">

														<span style="font-size: 12px;">TRACKING MESIN FILLER</span></a>
													</li>
													<hr class="sidebar-divider my-0">
												</ul>
												<div id="content-wrapper" class="d-flex flex-column">
													<div id="content">
														<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
															<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
																<i class="fa fa-bars"></i>
															</button>
														</nav>