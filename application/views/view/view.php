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

		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}

		#wrapper,
		#content-wrapper,
		#content {
			height: 100%;
		}


		.chart-container-smfg {
			display: flex;
			flex-wrap: wrap;
			gap: 5px;
		}

		.chart-box-smfg {
			flex: 0 0 calc(50% - 50px);
			width: 100%;
		}

		.chart-container {
			display: flex;
			flex-wrap: wrap;
			position: relative;
			width: 100%;

		}

		.chart-box {
			width: 100%;
			height: calc(100vh - 150px); /* Atur sesuai kebutuhan, misalnya header/footer */
			position: relative;
		}


		canvas {
			width: 100% !important;
			height: 80% !important;
			display: block;
		}


		.chart-select-topright {
			position: absolute;
			top: 0;
			right: 150px;
			width: auto;
			max-width: 320px;
		}

		.chart-select-topright1 {
			position: absolute;
			top: 0;
			right: 0px;
			width: auto;
			max-width: 320px;
		}

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
			z-index: 1030; /* Agar berada di atas konten lain */
		}


		@media (min-width: 768px) {
			.sidebar .nav-item .nav-link {
				width: 100% !important;
			}
		}

		.carousel-bottom-nav {
			width: 100%;
			text-align: center;
			margin-top: 0px;
		}

		.carousel-indicators-custom {
			display: inline-flex;
			justify-content: center;
			color: #33ffff;
			padding: 0;
			margin: 0 15px;
			list-style: none;
		}

		.carousel-indicators-custom li {
			width: 10px;
			height: 10px;
			margin: 0 5px;
			background-color: #d6dbdf;
			border-radius: 50%;
			cursor: pointer;
		}

		.carousel-indicators-custom .active {
			background-color: #d6dbdf;
		}

		.carousel-control-custom {
			background-color: #d6dbdf;
			color: #33ffff;
			border: none;
			padding: 5px 12px;
			border-radius: 50%;
			cursor: pointer;
		}
	</style>
</head>


<body id="page-top">

	<div id="wrapper">
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">

				<div class="container-responsive">
					<div class="card shadow">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<!-- <div id="kalender"></div> -->
									<div class="card shadow ml-1">
										<div class="card-body">
											<div class="form-group">
												<h5>Pilih Varian :</h5>
												<select id="varianSelect" class="form-control" name="varian">
													<option value="">Pilih Varian</option>
													<option value="1" <?= (isset($selected_varian) && $selected_varian == 1) ? 'selected' : '' ?>>OKEY</option>
													<option value="2" <?= (isset($selected_varian) && $selected_varian == 2) ? 'selected' : '' ?>>CHAMP AYAM</option>
													<option value="3" <?= (isset($selected_varian) && $selected_varian == 3) ? 'selected' : '' ?>>CHAMP SAPI</option>
													<option value="4" <?= (isset($selected_varian) && $selected_varian == 4) ? 'selected' : '' ?>>CHAMP OTAK-OTAK</option>
												</select>

											</div>

											<div class="dropdown mb-3">
												<?php if (in_array($this->session->userdata('type'), [1, 2])) { ?>

													<button class="btn btn-primary mb-3 btn-block" data-toggle="modal" data-target="#menuModal">
														<i class="fa fa-plus"></i> Tambah
													</button>
												<?php } ?>
												<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
															<div class="modal-header bg-info">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true" class="text-danger">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<div class="list-group">
																	<a href="<?= base_url('view/formula') ?>" class="list-group-item list-group-item-action">Formula & Filkar</a>
																	<a href="<?= base_url('view/performamesin') ?>" class="list-group-item list-group-item-action">Performa / Mesin</a>
																	<a href="<?= base_url('rj_filler/') ?>" class="list-group-item list-group-item-action">Reject Filler per Mesin & per Operator</a>

																	<a href="<?= base_url('view/rj_cooking') ?>" class="list-group-item list-group-item-action">Reject Cooking</a>
																	<a href="<?= base_url('view/rj_mesin') ?>" class="list-group-item list-group-item-action">Reject Cooking / Mesin</a>
																	<a href="<?= base_url('view/sortasi') ?>" class="list-group-item list-group-item-action">Reject Sortasi</a>
																	<a href="<?= base_url('view/srbadpro') ?>" class="list-group-item list-group-item-action">Reject / Bad Produk</a>
																	<a href="<?= base_url('view/smfg') ?>" class="list-group-item list-group-item-action">Reject Bad Produk SMFG</a>
																	<a href="<?= base_url('view/smfgmsn') ?>" class="list-group-item list-group-item-action">Reject Bad Produk SMFG per Mesin</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<a class="nav-link btn-primary text-center btn-block" href="<?= base_url('home') ?>">
												<i class="fa fa-home"></i>
												DASHBOARD
											</a>
										</div>

									</div>
								</div>
								<div class="col-md-9">
									<div id="chartCarousel" class="carousel slide" data-ride="carousel" data-interval="60000">
										<div class="carousel-inner">
											<!-- Slide 1 -->
											<div class="carousel-item active">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Performa Produksi (%)</h6>
														<canvas id="performa1"></canvas>
													</div>
												</div>
											</div>

											<!-- Slide 1 -->
											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Performa Produksi per Mesin (%)</h6>
														<canvas id="performaChart"></canvas>
													</div>
												</div>
											</div>


											<!-- Slide 2 -->


											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Filler per Mesin (%)</h6>
														<canvas id="beratChart"></canvas>
													</div>
												</div>

											</div>

											<!-- Slide 2 -->


											<div class="carousel-item">

												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Filler per Operator (%)</h6>
														<canvas id="beratOperator"></canvas>
													</div>
												</div>
											</div>

											<!-- Slide 1 -->
											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Cooking (%)</h6>
														<canvas id="reject_cooking"></canvas>
													</div>
												</div>
											</div>

											<!-- Slide 1 -->
											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Cooking / Mesin (%)</h6>
														<canvas id="beratckmesin"></canvas>
													</div>
												</div>
											</div>

											<!-- Slide 3 -->
											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Sortasi (%)</h6>
														<canvas id="chartSortasi"></canvas>
													</div>
												</div>

											</div>
											<div class="carousel-item">

												<div class="chart-container">
													<div class="chart-box">
														<h6 class="chart-title">Reject Sortasi per Bad Produk (%)</h6>

														<!-- Select di pojok kanan atas -->
														<select class="form-control chart-select-topright1" name="uuid" id="uuidDropdown"><option value="0">Bulan Ini</option>
															<?php foreach ($plan as $item): ?>

																<option value="<?= $item->uuid ?>">
																	<?= $item->tgl ?> - <?= $item->varian_name ?>
																</option>
															<?php endforeach; ?>
														</select>

														<canvas id="chartBadpro"></canvas>
													</div>
												</div>
											</div>

											<div class="carousel-item">
												<div class="chart-container">
													<div class="chart-box">
														<div class="row align-items-center mb-2">
															<!-- Kolom 1: Judul -->
															<div class="col-md-6 col-sm-12 text-md-left mb-2">
																<h6 class="chart-title m-0">Reject Bad Produk di SMFG (%)</h6>
															</div>

															<!-- Kolom 2: Dropdown -->
															<div class="col-md-6 col-sm-12 text-md-right mb-2">
																<select class="form-control form-control-sm d-inline-block w-auto" name="uuid" id="uuidDropdownSmfg">
																	<option value="0">Bulan Ini</option>
																	<?php foreach ($plan as $item): ?>
																		<option value="<?= $item->uuid ?>">
																			<?= $item->tgl ?> - <?= $item->varian_name ?>
																		</option>
																	<?php endforeach; ?>
																</select>
															</div>
														</div>

														<!-- Chart Canvas -->
														<canvas id="chartSMFG"></canvas>
													</div>
												</div>
											</div>


											<div class="carousel-item">

												<div class="container-fluid">
													<div class="row align-items-center">
														<div class="col-md-4 col-sm-12 mb-2">
															<h5 class="chart-title m-0">Reject Bad Produk di SMFG per Mesin (%)</h5>
														</div>
														<div class="col-md-4 col-sm-12 mb-2 text-md-center">
															<?php if (in_array($this->session->userdata('type'), [1, 2])) { ?>
																<button class="btn btn-primary mb-3 btn-sm" data-toggle="modal" data-target="#smfg_chart">
																	<i class="fa fa-plus"></i>Pilih Badpro
																</button>
															<?php } ?>
															<!-- modal smfg -->
															<!-- Modal Bad Produk -->
															<div class="modal fade" id="smfg_chart" tabindex="-1" role="dialog" aria-labelledby="smfg_chartLabel" aria-hidden="true">
																<div class="modal-dialog modal-dialog-centered" role="document">
																	<div class="modal-content">

																		<!-- Modal Header -->
																		<div class="modal-header bg-info">
																			<h5 class="modal-title text-light" id="smfg_chartLabel">Bad Produk Ditampilkan</h5>
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true" class="text-danger">&times;</span>
																			</button>
																		</div>

																		<!-- Modal Body -->
																		<div class="modal-body">
																			<form method="post" action="<?= base_url('view/pilih_tampil_badpro') ?>">
																				<table class="table table-bordered">
																					<thead>
																						<tr>
																							<th>Before</th>
																							<th>After</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php foreach ($chart_config as $cfg): ?>
																							<tr>
																								<td>
																									<?= $cfg->title ?>
																									<input type="hidden" name="chart_id[]" value="<?= $cfg->chart_id ?>">
																								</td>
																								<td>
																									<select name="badpro_uuid[]" class="form-control">
																										<option value="">-- Pilih Bad Produk --</option>
																										<?php foreach ($uuid_options as $opt): ?>
																											<option value="<?= $opt->badpro_uuid ?>" <?= ($opt->badpro_uuid == $cfg->badpro_uuid) ? 'selected' : '' ?>>
																												<?= $opt->badpro ?>
																											</option>
																										<?php endforeach; ?>
																									</select>
																								</td>
																							</tr>
																						<?php endforeach; ?>
																					</tbody>
																				</table>
																				<div class="text-right">
																					<button type="submit" class="btn btn-primary">Simpan</button>
																				</div>
																			</form>
																		</div>

																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-4 col-sm-12 mb-2 text-md-right">
															<select id="uuidDropdownSmfgmsn">
																<option value="0">Bulan Ini</option>
																<?php foreach ($plan as $item): ?>
																	<option value="<?= $item->uuid ?>"><?= $item->tgl ?> - <?= $item->varian_name ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
												</div>
												<div class="chart-container-smfg">
													<?php foreach ($chart_config as $index => $cfg): ?>
														<div class="chart-box-smfg" style="height: 280px;">
															<h6 class="chart-title"><?= $cfg->title ?></h6>
															<canvas id="chartSMFG<?= $index ?>"></canvas>
														</div>
													<?php endforeach; ?>
												</div>


											</div>

										</div>
									</div>

									<!-- Kontrol di bawah halaman -->
									<div class="carousel-bottom-nav mt-2">
										<button class="carousel-control-custom" href="#chartCarousel" role="button" data-slide="prev">
											&laquo;
										</button>

										<ol class="carousel-indicators-custom">
											<li data-target="#chartCarousel" data-slide-to="0" class="active"></li>
											<li data-target="#chartCarousel" data-slide-to="1"></li>
											<li data-target="#chartCarousel" data-slide-to="2"></li>
											<li data-target="#chartCarousel" data-slide-to="3"></li>
										</ol>

										<button class="carousel-control-custom" href="#chartCarousel" role="button" data-slide="next">
											&raquo;
										</button>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>

			<!-- modal -->


			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
			<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0"></script>
			<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

			<script>
				document.addEventListener("DOMContentLoaded", function() {
					let ctx = document.getElementById("performa1").getContext("2d");
					let chart;

					function fetchPerformaData(varian_uuid) {
						fetch("<?= base_url('view/get_data_by_varian/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							if (chart) {
					chart.destroy(); // Hapus chart lama jika ada
				}

				let labels = data.map(item => item.date);
				let values = data.map(item => parseFloat(item.rata_performa));
				let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.9)");   // bagian atas (lebih terang)
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.3)");   // bagian bawah (lebih gelap)

				chart = new Chart(ctx, {
					type: "bar",
					data: {
						labels: labels,
						datasets: [{
							label: "Performa",
							data: values,
            backgroundColor: gradient, // efek 3D tipis
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1
        }]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								display: false
							},
							annotation: {
								annotations: {
									line1: {
										type: 'line',
										yMin: 85,
										yMax: 85,
										borderColor: 'red',
										borderWidth: 2,
									}
								}
							},
			// AKTIFKAN DATA LABELS
							datalabels: {
								anchor: 'end',
								align: 'start',
								color: 'black',
								font: {
									weight: 'bold'
								},
								formatter: function(value) {
									return value.toFixed(2) + " %";
								}
							}
						},
						scales: {
							x: {
								ticks: {
									maxRotation: 45,
									minRotation: 30,
									autoSkip: false
								}
							},
							y: {
								beginAtZero: true
							}
						}
					},
	plugins: [ChartDataLabels] // <== aktifkan plugin di instance
});


			})
						.catch(error => console.error("Error fetching data:", error));
					}

	// Ambil varian terpilih saat halaman dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchPerformaData(initialVarian);

	// Event listener untuk dropdown varian
					document.getElementById("varianSelect").addEventListener("change", function() {
						let selectedVarian = this.value;
						fetchPerformaData(selectedVarian);
					});
				});
			</script>

			<script>
				document.addEventListener("DOMContentLoaded", function() {
					let ctx = document.getElementById("performaChart").getContext("2d");
					let chart;

					function fetchPerformaData(varian_uuid) {
						fetch("<?= base_url('view/get_performa_chart/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							if (chart) {
                    chart.destroy(); // Hapus chart lama jika ada
                }

                let labels = data.map(item => item.mesin_uuid); // Ambil sebagian UUID
                let values = data.map(item => parseFloat(item.rata_performa));
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.1)"); 

                chart = new Chart(ctx, {
                	type: "bar",
                	data: {
                		labels: labels,
                		datasets: [{
                			label: "Performa",
                			data: values,
                			backgroundColor: gradient,
                			borderColor: "rgba(54, 162, 235, 1)",
                			borderWidth: 1
                		}]
                	},
                	options: {
                		responsive: true,
                		plugins: {
                			legend: {
                				display: false
                			},
                			annotation: {
                				annotations: {
                					line1: {
                						type: 'line',
                						yMin: 85,
                						yMax: 85,
                						borderColor: 'red',
                						borderWidth: 2,
                					}
                				}
                			},
			// AKTIFKAN DATA LABELS
                			datalabels: {
                				anchor: 'end',
                				align: 'start',
                				color: 'black',
                				font: {
                					weight: 'bold'
                				},
                				formatter: function(value) {
                					return value.toFixed(2) + " %";
                				}
                			}
                		},
                		scales: {
                			x: {
                				ticks: {
                					maxRotation: 45,
                					minRotation: 30,
                					autoSkip: false
                				}
                			},
                			y: {
                				beginAtZero: true
                			}
                		}
                	},
	plugins: [ChartDataLabels] // <== aktifkan plugin di instance
});


            })
						.catch(error => console.error("Error fetching data:", error));
					}

    // Ambil varian terpilih saat halaman dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchPerformaData(initialVarian);

    // Event listener untuk dropdown varian
					document.getElementById("varianSelect").addEventListener("change", function() {
						let selectedVarian = this.value;
						fetchPerformaData(selectedVarian);
					});
				});
			</script>

			<script>
				document.addEventListener("DOMContentLoaded", function() {
					let ctx = document.getElementById("reject_cooking").getContext("2d");
					let chart;

					function fetchPerformaData(varian_uuid) {
						fetch("<?= base_url('view/get_cooking_by_varian/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							// console.log("📊 [Reject Filler per Mesin] Data fetched:", data);
							if (chart) {
                    chart.destroy(); // Hapus chart lama jika ada
                }

                let labels = data.map(item => item.tgl); // Ambil sebagian UUID
                let values = data.map(item => parseFloat(item.total_berat));
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
                chart = new Chart(ctx, {
                	type: "bar",
                	data: {
                		labels: labels,
                		datasets: [{
                			label: "Reject Cooking",
                			data: values,
                			backgroundColor: gradient,
                			borderColor: "rgba(54, 162, 235, 1)",
                			borderWidth: 1
                		}]
                	},
                	options: {
                		responsive: true,
                		plugins: {
                			legend: {
                				display: false
                			},
                			annotation: {
                				annotations: {
                					line1: {
                						type: 'line',
                						yMin: 0.2,
                						yMax: 0.2,
                						borderColor: 'red',
                						borderWidth: 1
                					}
                				}
                			},
			// ➕ TAMBAHKAN BAGIAN INI UNTUK DATA LABEL
                			datalabels: {
                				anchor: 'end',
                				align: 'start',
                				color: 'black',
                				font: {
                					weight: 'bold'
                				},
                				formatter: function(value) {
					return (value).toFixed(2) + ' %'; // Kalau value 0.02 → tampilkan 2.0 %
				}
			}
		},
		scales: {
			y: {
				beginAtZero: true
			}
		}
	},
	plugins: [ChartDataLabels] // Aktifkan plugin
});

            })
						.catch(error => console.error("Error fetching data:", error));
					}

    // Ambil varian terpilih saat halaman dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchPerformaData(initialVarian);

    // Event listener untuk dropdown varian
					document.getElementById("varianSelect").addEventListener("change", function() {
						let selectedVarian = this.value;
						fetchPerformaData(selectedVarian);
					});
				});
			</script>




			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let ctx = document.getElementById("beratckmesin").getContext("2d");
					let chart;

					function fetchCookingMesinData(varian_uuid) {
						fetch("<?= base_url('view/get_cooking_mesin_by_varian/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							if (chart) {
                    chart.destroy(); // Hapus chart lama jika ada
                }

                let labels = data.map(item => item.mesin_uuid);
                let values = data.map(item => parseFloat(item.rata_ckmesin));
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
                chart = new Chart(ctx, {
                	type: "bar",
                	data: {
                		labels: labels,
                		datasets: [{
                			label: "Reject Cooking per Mesin",
                			data: values,
                			backgroundColor: gradient,
                			borderColor: "rgba(54, 162, 235, 1)",
                			borderWidth: 1
                		}]
                	},
                	options: {
                		responsive: true,
                		plugins: {
                			legend: {
                				display: false
                			},
                			annotation: {
                				annotations: {
                					line1: {
                						type: 'line',
                						yMin: 0.2,
                						yMax: 0.2,
                						borderColor: 'red',
                						borderWidth: 1
                					}
                				}
                			},
                			datalabels: {
                				anchor: 'end',
                				align: 'start',
                				color: 'black',
                				font: {
                					weight: 'bold'
                				},
                				formatter: function(value) {
                					return (value).toFixed(2) + ' %';
                				}
                			}
                		},
                		scales: {
                			y: {
                				beginAtZero: true
                			}
                		}
                	},
	plugins: [ChartDataLabels] // Aktifkan plugin di chart ini
});

            })
						.catch(error => console.error("Error fetching data:", error));
					}

    // Ambil varian saat halaman pertama kali dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchCookingMesinData(initialVarian);

    // Update chart saat varian diubah
					document.getElementById("varianSelect").addEventListener("change", function () {
						let selectedVarian = this.value;
						fetchCookingMesinData(selectedVarian);
					});
				});
			</script>




			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let ctx = document.getElementById("beratChart").getContext("2d");
					let chart;

					function fetchFillerMesinData(varian_uuid) {
						fetch("<?= base_url('view/get_total_berat_per_mesin/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							

							if (chart) {
					chart.destroy(); // Hapus chart lama jika ada
				}

				let labels = data.map(item => item.mesin_uuid);
				let values = data.map(item => parseFloat(item.rata_reject));
				let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
				chart = new Chart(ctx, {
					type: "bar",
					data: {
						labels: labels,
						datasets: [{
							label: "Reject Filler per Mesin",
							data: values,
							backgroundColor: gradient,
							borderColor: "rgba(54, 162, 235, 1)",
							borderWidth: 1
						}]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								display: false
							},
							annotation: {
								annotations: {
									line1: {
										type: 'line',
										yMin: 0.2,
										yMax: 0.2,
										borderColor: 'red',
										borderWidth: 1
									}
								}
							},
			// Tambahkan datalabels
							datalabels: {
								anchor: 'end',
								align: 'start',
								color: 'black',
								font: {
									weight: 'bold'
								},
								formatter: function(value) {
					// Jika value desimal (misal 0.0125), tampilkan sebagai persen
									return (value).toFixed(2) + ' %';
								}
							}
						},
						scales: {
							y: {
								beginAtZero: true
							}
						}
					},
	plugins: [ChartDataLabels] // Aktifkan plugin datalabels
});

			})
						.catch(error => console.error("Error fetching data:", error));
					}

	// Ambil varian saat halaman pertama kali dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchFillerMesinData(initialVarian);

	// Update chart saat varian diubah
					document.getElementById("varianSelect").addEventListener("change", function () {
						let selectedVarian = this.value;
						fetchFillerMesinData(selectedVarian);
					});
				});
			</script>


			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let ctx = document.getElementById("beratOperator").getContext("2d");
					let chart;

					function fetchFillerOperatorData(varian_uuid) {
						fetch("<?= base_url('view/get_total_berat_per_operator/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {
							// console.log(data);
							if (chart) {
					chart.destroy(); // Hapus chart lama jika ada
				}

				let labels = data.map(item => item.fullname);
				let values = data.map(item => parseFloat(item.rata_persen));
				let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
				chart = new Chart(ctx, {
					type: "bar",
					data: {
						labels: labels,
						datasets: [{
							label: "Reject Filler per Operator",
							data: values,
							backgroundColor: gradient,
							borderColor: "rgba(54, 162, 235, 1)",
							borderWidth: 1
						}]
					},
					options: {
						responsive: true,
						plugins: {
							legend: {
								display: false
							},
							annotation: {
								annotations: {
									line1: {
										type: 'line',
										yMin: 0.2,
										yMax: 0.2,
										borderColor: 'red',
										borderWidth: 1
									}
								}
							},
							datalabels: {
								anchor: 'end',
								align: 'start',
								color: 'black',
								font: {
									weight: 'bold'
								},
								formatter: function(value) {
					// Ubah 0.0125 → 1.3 %
									return (value).toFixed(2) + ' %';
								}
							}
						},
						scales: {
							y: {
								beginAtZero: true
							}
						}
					},
	plugins: [ChartDataLabels] // Aktifkan plugin datalabels
});

			})
						.catch(error => console.error("Error fetching data:", error));
					}

	// Ambil varian saat halaman pertama kali dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchFillerOperatorData(initialVarian);

	// Update chart saat varian diubah
					document.getElementById("varianSelect").addEventListener("change", function () {
						let selectedVarian = this.value;
						fetchFillerOperatorData(selectedVarian);
					});
				});
			</script>

			<script>
				document.addEventListener("DOMContentLoaded", function() {
					let ctx = document.getElementById("chartSortasi").getContext("2d");
					let chart;

					function fetchSortasiData(varian_uuid) {
						fetch("<?= base_url('view/get_sortasi_persen_by_plan/'); ?>" + varian_uuid)
						.then(response => response.json())
						.then(data => {

							if (chart) {
                    chart.destroy(); // Hapus chart lama jika ada
                }

                let labels = data.map(item => item.tgl); // Ambil sebagian UUID
                let values = data.map(item => parseFloat(item.persen_data));
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
                chart = new Chart(ctx, {
                	type: "bar",
                	data: {
                		labels: labels,
                		datasets: [{
                			label: "Reject Sortasi",
                			data: values,
                			backgroundColor: gradient,
                			borderColor: "rgba(54, 162, 235, 1)",
                			borderWidth: 1
                		}]
                	},
                	options: {
                		responsive: true,
                		plugins: {
                			legend: {
                				display: false
                			},
                			annotation: {
                				annotations: {
                					line1: {
                						type: 'line',
                						yMin: 0.8,
                						yMax: 0.8,
                						borderColor: 'red',
                						borderWidth: 1
                					}
                				}
                			},
                			datalabels: {
                				anchor: 'end',
                				align: 'start',
                				color: 'black',
                				font: {
                					weight: 'bold'
                				},
                				formatter: function(value) {
					return (value).toFixed(2) + ' %'; // Contoh: 0.021 → 2.1 %
				}
			}
		},
		scales: {
			y: {
				beginAtZero: true
			}
		}
	},
	plugins: [ChartDataLabels]
});

            })
						.catch(error => console.error("Error fetching data:", error));
					}

    // Ambil varian terpilih saat halaman dimuat
					let initialVarian = document.getElementById("varianSelect").value;
					fetchSortasiData(initialVarian);

    // Event listener untuk dropdown varian
					document.getElementById("varianSelect").addEventListener("change", function() {
						let selectedVarian = this.value;
						fetchSortasiData(selectedVarian);
					});
				});
			</script>
			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let ctx = document.getElementById("chartBadpro").getContext("2d");
					let chart;

					function fetchSortasiBadproData() {
						const uuid = document.getElementById("uuidDropdown").value;
						const varian_uuid = document.getElementById("varianSelect").value;

						fetch("<?= base_url('view/get_badpro_chart'); ?>", {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded',
							},
							body: `uuid=${encodeURIComponent(uuid)}&varian_uuid=${encodeURIComponent(varian_uuid)}`
						})
						.then(response => response.json())
						.then(data => {

							if (chart) {
								chart.destroy();
							}

							let labels = data.map(item => item.badpro);
							let values = data.map(item => parseFloat(item.persen_bad_sortasi));
let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 
							chart = new Chart(ctx, {
								type: "bar",
								data: {
									labels: labels,
									datasets: [{
										label: "Reject Sortasi per Bad Produk",
										data: values,
										backgroundColor: gradient,
										borderColor: "rgba(54, 162, 235, 1)",
										borderWidth: 1
									}]
								},
								options: {
									responsive: true,
									plugins: {
										legend: { display: false },
										annotation: {
											annotations: {
												line1: {
													type: 'line',
													yMin: 0.2,
													yMax: 0.2,
													borderColor: 'red',
													borderWidth: 1,
												}
											}
										},
										datalabels: {
											anchor: 'end',
											align: 'start',
											color: 'black',
											font: {
												weight: 'bold'
											},
											formatter: function(value) {
					return (value).toFixed(2) + ' %'; // Contoh: 0.15 → 15.0 %
				}
			}
		},
		scales: {
			y: { beginAtZero: true }
		}
	},
	plugins: [ChartDataLabels]
});

						})
						.catch(error => console.error("Error fetching data:", error));
					}

	// Jalankan awal saat halaman dimuat
					fetchSortasiBadproData();

	// Trigger saat UUID dropdown berubah
					document.getElementById("uuidDropdown").addEventListener("change", fetchSortasiBadproData);

	// Trigger saat varian berubah (kalau uuid masih 0)
					document.getElementById("varianSelect").addEventListener("change", fetchSortasiBadproData);
				});
			</script>



			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let ctx = document.getElementById("chartSMFG").getContext("2d");
					let chart;

					function fetchSortasiBadproData(uuid) {
						fetch("<?= base_url('view/get_smfg_chart'); ?>", {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded',
							},
							body: 'uuid=' + encodeURIComponent(uuid)
						})
						.then(response => response.json())
						.then(data => {
							if (chart) {
								chart.destroy();
							}

							let labels = data.map(item => item.badpro);
							let values = data.map(item => parseFloat(item.rata_jumlah));
							let gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(1, "rgba(54, 162, 235, 0.9)");
				gradient.addColorStop(0, "rgba(54, 162, 235, 0.3)"); 

							chart = new Chart(ctx, {
								type: "bar",
								data: {
									labels: labels,
									datasets: [{
										label: "Reject Sortasi per Bad Produk",
										data: values,
										backgroundColor: gradient,
										borderColor: "rgba(54, 162, 235, 1)",
										borderWidth: 1
									}]
								},
								options: {
									layout: {
										padding: {
											bottom: 40
										}
									},
									responsive: true,
									maintainAspectRatio: false,
									plugins: {
										legend: {
											display: false
										},
										annotation: {
											annotations: {
												line1: {
													type: 'line',
													yMin: 0.8,
													yMax: 0.8,
													borderColor: 'red',
													borderWidth: 1,
												}
											}
										},
										datalabels: {
											anchor: 'end',
											align: 'start',
											color: 'black',
											font: {
												weight: 'bold'
											},
											formatter: function(value) {
					return (value).toFixed(2) + ' %';  // ubah sesuai format data
				}
			}
		},
		scales: {
			y: {
				beginAtZero: true
			}
		}
	},
	plugins: [ChartDataLabels]
});

						})
						.catch(error => console.error("Error fetching data:", error));
					}

					let initialUuid = document.getElementById("uuidDropdownSmfg").value;
					fetchSortasiBadproData(initialUuid);

					document.getElementById("uuidDropdownSmfg").addEventListener("change", function() {
						fetchSortasiBadproData(this.value);
					});
				});

			</script>

			<script>
				document.addEventListener("DOMContentLoaded", function () {
					let chartInstances = {};
					let planSelect = document.getElementById("uuidDropdownSmfgmsn");

					function fetchChart(canvasId, badpro_uuid, plan_uuid) {
						let ctx = document.getElementById(canvasId).getContext("2d");

						fetch("<?= base_url('view/get_smfgmsn_chart') ?>", {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: `uuid=${encodeURIComponent(plan_uuid)}&badpro_uuid=${encodeURIComponent(badpro_uuid)}`
						})
						.then(res => res.json())
						.then(result => {
							if (chartInstances[canvasId]) {
								chartInstances[canvasId].destroy();
							}

							chartInstances[canvasId] = new Chart(ctx, {
								type: 'bar',
								data: {
									labels: result.labels,
									datasets: [{
										label: 'Reject',
										data: result.data,
										backgroundColor: "rgba(54, 162, 235, 0.6)"
									}]
								},
								options: {
									responsive: true,
									maintainAspectRatio: false,
									plugins: {
										legend: { display: false },
										annotation: {
											annotations: {
												line1: {
													type: 'line',
													yMin: 0.2,
													yMax: 0.2,
													borderColor: 'red',
													borderWidth: 1,
												}
											}
										},
										datalabels: {
											anchor: 'end',
											align: 'start',
											color: 'black',
											font: { weight: 'bold' },
											formatter: function(value) {
					return (value).toFixed(2) + ' %'; // ubah sesuai kebutuhan
				}
			}
		},
		scales: { y: { beginAtZero: true } }
	},
	plugins: [ChartDataLabels]
});

						});
					}

					function renderAllCharts() {
						let plan_uuid = planSelect.value;

						<?php foreach ($chart_config as $index => $cfg): ?>
							fetchChart("chartSMFG<?= $index ?>", "<?= $cfg->badpro_uuid ?>", plan_uuid);
						<?php endforeach; ?>
					}

					renderAllCharts();
					planSelect.addEventListener("change", renderAllCharts);
				});
			</script>

			<script>
				document.addEventListener("DOMContentLoaded", function() {
					const picker = new Litepicker({
						element: document.getElementById('kalender'),
						singleMode: false,
						inlineMode: true,
						format: 'YYYY-MM-DD',
						setup: (picker) => {
							picker.on('render', () => {
								document.querySelector('.litepicker').style.width = "100%";
								document.querySelector('.litepicker').style.maxWidth = "250px";
							});
						},
						onSelect: function(start, end) {
							fetch("<?= base_url('data/filter') ?>", {
								method: "POST",
								headers: { "Content-Type": "application/x-www-form-urlencoded" },
								body: `start_date=${start}&end_date=${end}`
							})
							.then(response => response.text())
							.then(data => document.getElementById("result").innerHTML = data);
						}
					});
				});
				setTimeout(function() {
					location.reload();
				}, 1800000);
			</script>

			<style>
				#kalender {
					width: 100% !important;  /* Menyesuaikan dengan card */
					max-width: 250px; /* Sesuaikan dengan card */
					margin: 0 auto; /* Supaya tetap di tengah */
				}
				.litepicker {
					width: 100% !important;
					max-width: 250px;
				}
				#performa1 {
					width: 100% !important;
					height: 90% !important;
				}

				#performaChart {
					width: 100% !important;
					height: 95% !important;
				}

				#reject_cooking {
					width: 100% !important;
					height: 97% !important;
				}

				#beratckmesin {
					width: 100% !important;
					height: 95% !important;
				}

				#beratChart {
					width: 100% !important;
					height: 95% !important;
				}

				#beratOperator {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 95% !important; /* Tetap kasih tinggi tetap */
				}

				#chartSortasi {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 95% !important; /* Tetap kasih tinggi tetap */
				}

				#chartBadpro {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 95% !important; /* Tetap kasih tinggi tetap */
				}

				#chartSMFG {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 95% !important; /* Tetap kasih tinggi tetap */
				}

				#chartSMFG0 {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 220px !important; /* Tetap kasih tinggi tetap */
				}

				#chartSMFG1 {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 220px !important; /* Tetap kasih tinggi tetap */
				}

				#chartSMFG2 {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 220px !important; /* Tetap kasih tinggi tetap */
				}

				#chartSMFG3 {
					width: 100% !important;  /* Biar penuh lebar parent */
					height: 220px !important; /* Tetap kasih tinggi tetap */
				}

			</style>





