<style>
	.doc_wrapper img{width: 100%;}
	.timeline {
		list-style: none;
		padding: 0;
		position: relative;
	}

	.timeline:before {
		content: '';
		position: absolute;
		left: 20px;
		top: 0;
		bottom: 0;
		width: 2px;
		background: #ddd;
	}

	.timeline li {
		position: relative;
		margin-bottom: 20px;
		padding-left: 50px;
	}

	.timeline-badge {
		position: absolute;
		left: 0;
		top: 0;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		text-align: center;
		line-height: 40px;
	}

	.timeline-panel {
		background: #f8f9fc;
		padding: 10px 15px;
		border-radius: 6px;
	}
</style>
<div class="container-fluid">

	<!-- Page Heading -->
	<h3 class="h3 mb-2 text-gray-800">Detail PM "<?= $data->nama_mesin; ?>" </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tpm'?'pm/tpm':'pm') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Preventive Maintenance</a></li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="row">
				<div class="col-md-5">
					<table class="table">
						<tbody>
							<tr>
								<td width="200" class="border-top-0">Area</td>
								<td width="10" class="border-top-0">:</td>
								<td class="font-weight-bold border-top-0"><?= $data->nama_area; ?></td>
							</tr>
							<tr>
								<td width="200">Mesin</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->nama_mesin; ?></td>
							</tr>
							<tr>
								<td width="200">Keluhan</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->keluhan; ?></td>
							</tr>
							<tr>
								<td width="200" class="border-bottom">Tindakan</td>
								<td width="10" class="border-bottom">:</td>
								<td class="font-weight-bold border-bottom"><?= !empty($data->tindakan) ? $data->tindakan : '-'; ?></td>
							</tr>   
							<tr>
								<td>Dokumentasi Pengajuan</td>
								<td>:</td>
								<td><?php if (!empty($data->dokumentasi)) : ?>
								<div class="doc_wrapper">
									<a href="#" data-toggle="modal" data-target="#modalFoto">
										<img src="<?= base_url('upload/'.$data->dokumentasi) ?>" 
										class="img-fluid rounded shadow" 
										style="max-height:250px; object-fit:cover; cursor:pointer;">
									</a>
								</div>
							<?php else : ?>
								<div class="text-center text-muted">
									-
								</div>
								<?php endif; ?></td>
							</tr>
							<tr>
								<td>Dokumentasi Tindakan</td>
								<td>:</td>
								<td><?php if (!empty($data->dokumentasi_acc)) : ?>
								<div class="doc_wrapper">
									<a href="#" data-toggle="modal" data-target="#modalFotoAcc">
										<img src="<?= base_url('upload/'.$data->dokumentasi_acc) ?>" 
										class="img-fluid rounded shadow" 
										style="max-height:250px; object-fit:cover; cursor:pointer;">
									</a>
								</div>
							<?php else : ?>
								<div class="font-weight-bold">
									-
								</div>
								<?php endif; ?></td>
							</tr> 
						</tbody>

					</table>


				</div>

				<div class="col-sm-6 mb-3 mb-sm-0">
					<h5 class="text-gray-800 mb-4">
						Proses Status "<?= $data->nama_mesin; ?>"
					</h5>
					<?php
					function durasi($start, $end){
						if(!$start || !$end) return '-';

						$selisih = strtotime($end) - strtotime($start);

						$jam = floor($selisih / 3600);
						$menit = floor(($selisih % 3600) / 60);

						return $jam.' jam '.$menit.' menit';
					}
					?>
					<div class="card shadow">
						<div class="card-body">

							<ul class="timeline">

								<!-- PENGAJUAN -->
								<li>
									<div class="timeline-badge bg-primary">
										<i class="fas fa-file-alt text-white"></i>
									</div>
									<div class="timeline-panel">
										<h6 class="font-weight-bold mb-1">Pengajuan</h6>

										<small class="text-muted d-block">
											<?= date('d M Y',strtotime($data->tgl)); ?>
										</small>

										<small class="text-dark">
											Oleh: <?= $data->nama_operator ?? '-' ?>
										</small>
									</div>
								</li>

								<!-- TINDAKAN -->
								<li>
									<div class="timeline-badge bg-warning">
										<i class="fas fa-tools text-white"></i>
									</div>
									<div class="timeline-panel">
										<h6 class="font-weight-bold mb-1">Tindakan</h6>

										<small class="text-muted d-block">
											<?= !empty($data->tindakan_at) ? date('d M Y', strtotime($data->tindakan_at)) : '-'; ?>
										</small>

										<small class="text-dark d-block">
											Oleh: <?= !empty($data->pelaksana) ? $data->pelaksana : '-' ?>
										</small>

										
									</div>
								</li>

								<!-- ACC -->
								<li>
									<div class="timeline-badge bg-success">
										<i class="fas fa-check text-white"></i>
									</div>
									<div class="timeline-panel">
										<h6 class="font-weight-bold mb-1">ACC / Selesai</h6>

										<small class="text-muted d-block">
											<?= !empty($data->acc_at) ? date('d M Y', strtotime($data->acc_at)) : '-'; ?>
										</small>

										<small class="text-dark d-block">
											Oleh: <?= $data->acc_name ?? '-' ?>
										</small>

									</div>
								</li>

							</ul>

						</div>
					</div>


				</div>
			</div>


			<a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'pm/tpm' : ($this->uri->segment(2) == 'history' ? 'pm/history' : 'pm')) ?>" class="btn btn-md btn-danger mt-3">
				<i class="fa fa-arrow-left"></i> Kembali
			</a>
		</div>
		<div class="modal fade" id="modalFoto" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-body text-center">

						<img src="<?= base_url('upload/'.$data->dokumentasi) ?>" 
						class="img-fluid rounded" style="max-height:80vh;">
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" id="modalFotoAcc" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-body text-center">

						<img src="<?= base_url('upload/'.$data->dokumentasi_acc) ?>" 
						class="img-fluid rounded" style="max-height:80vh;">
					</div>

				</div>
			</div>
		</div>
	</div>
</div>