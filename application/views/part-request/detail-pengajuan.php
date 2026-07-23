<style>
	.doc_wrapper{width: 200px;}
	.doc_wrapper img{width: 100%;}
</style>
<div class="container-fluid">
	<h3 class="h3 mb-2 text-gray-800">Detail Permintaan "<?= $data->part; ?>" </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2) == 'history' ? 'partrequest/history' : 'partrequest') ?>"><i class="fas fa-arrow-left mr-2"></i> Repair & New Part</a></li>
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
								<td width="200" class="border-top-0">SparePart</td>
								<td width="10" class="border-top-0">:</td>
								<td class="font-weight-bold border-top-0"><?= $data->part; ?></td>
							</tr>
							<tr>
								<td width="200">Jenis Part</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->jns; ?></td>
							</tr>
							<tr>
								<td width="200">Pengaju</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->username; ?></td>
							</tr>
							<tr class="mb-5">
								<td width="200" class="border-bottom">Keterangan</td>
								<td width="10" class="border-bottom">:</td>
								<td class="font-weight-bold border-bottom"><?= $data->keterangan; ?></td>
							</tr>
							<tr class="bg-info text-light mt-5">
								<td width="200">Foto Part</td>
								<td width="10"></td>
								<td class="border-left">Keterangan</td>
							</tr>
							<tr class="table-bordered">
								<td width="200" colspan="2"><div class="doc_wrapper"><?= !empty($data->foto) ? '<img src="' . base_url('upload/'.$data->foto) . '" class="preview-image">' : 'Belum Dokumentasi'; ?></div></td>
								
								<td></td>
							</tr>
							<?php
							foreach ($foto as $val) {
								?>
								<tr class="table-bordered">
									<td width="200" colspan="2"><div class="doc_wrapper"><?= '<img src="' . base_url('upload/'.$val->foto) . '" class="preview-image">'; ?></div></td>
									
									<td class="border-0"><?= $val->keterangan ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					
				</div>
				<div class="col-md-7">
					<h5 class="font-weight-bold">Proses Perjalanan Part "<?= $data->part; ?>"</h5>
					<hr>
					<div class="card border-left-info shadow-sm mb-4">
						<div class="card-body">

							<h6 class="font-weight-bold text-info mb-3">
								Progress Approval
							</h6>

							<div class="row">

								<?php $is_history = ($this->uri->segment(2) == 'history'); ?>

								<div class="col-md-4 mb-2">

									<?php if (!$is_history): ?>
										<a href="<?= base_url('partrequest/status_part/'.$data->uuid); ?>" class="text-decoration-none">
										<?php endif; ?>

										<div class="border rounded p-2 bg-light">

											<div class="font-weight-bold">
												Produksi
											</div>

											<?php if ($approval['Produksi']) : ?>

												<span class="text-success">
													<i class="fas fa-check-circle"></i>
													Approved
												</span>

											<?php else : ?>

												<span class="text-secondary">
													<i class="far fa-circle"></i>
													Waiting
												</span>

											<?php endif; ?>

										</div>

										<?php if (!$is_history): ?>
										</a>
									<?php endif; ?>

								</div>

								<div class="col-md-4 mb-2">

									<?php if (!$is_history): ?>
										<a href="<?= base_url('partrequest/status_part/'.$data->uuid); ?>" class="text-decoration-none">
										<?php endif; ?>

										<div class="border rounded p-2 bg-light">

											<div class="font-weight-bold">
												Engineering
											</div>

											<?php if ($approval['Engineering']) : ?>

												<span class="text-success">
													<i class="fas fa-check-circle"></i>
													Approved
												</span>

											<?php else : ?>

												<span class="text-secondary">
													<i class="far fa-circle"></i>
													Waiting
												</span>

											<?php endif; ?>

										</div>

										<?php if (!$is_history): ?>
										</a>
									<?php endif; ?>

								</div>

								<div class="col-md-4 mb-2">

									<?php if (!$is_history): ?>
										<a href="<?= base_url('partrequest/status_part/'.$data->uuid); ?>" class="text-decoration-none">
										<?php endif; ?>

										<div class="border rounded p-2 bg-light">

											<div class="font-weight-bold">
												Warehouse
											</div>

											<?php if ($approval['Warehouse']) : ?>

												<span class="text-success">
													<i class="fas fa-check-circle"></i>
													Approved
												</span>

											<?php else : ?>

												<span class="text-secondary">
													<i class="far fa-circle"></i>
													Waiting
												</span>

											<?php endif; ?>

										</div>

										<?php if (!$is_history): ?>
										</a>
									<?php endif; ?>

								</div>

							</div>

						</div>
					</div>
					<table class="table table-bordered">
						<thead class="text-light bg-success">
							<tr>
								<th>No.</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th>User</th>
								<th>Catatan</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($status)) : ?>
								<?php $no = 1; ?>
								<?php foreach ($status as $value) : ?>
									<tr>
										<td><?= $no++; ?>.</td>
										<td><?= date('d-m-Y H:i', strtotime($value->tanggal)); ?></td>
										<td>

											<?php
											$badge = 'secondary';

											switch ($value->status) {

												case 'diSetujui':
												$badge = 'info';
												break;

												case 'Release Komdif':
												$badge = 'primary';
												break;

												case 'Proses Pengiriman':
												case 'Proses Pembuatan':
												$badge = 'warning';
												break;

												case 'ACC':
												$badge = 'success';
												break;

												case 'diTolak':
												$badge = 'danger';
												break;
											}
											?>

											<span class="badge badge-<?= $badge; ?>">
												<?= $value->status; ?>
											</span>

										</td>
										<td><?= $value->username; ?></td>
										<td><?= $value->keterangan; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="5" class="text-center text-muted">Belum ada riwayat perjalanan part</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<a href="<?= base_url($this->uri->segment(2) == 'history' ? 'partrequest/history' : 'partrequest') ?>" class="btn btn-md btn-danger mt-3">
					<i class="fa fa-arrow-left"></i> Kembali
				</a>
			</div>
			<!-- Modal Preview Gambar -->
			<div class="modal fade" id="imageModal" tabindex="-1">
				<div class="modal-dialog modal-xl modal-dialog-centered">
					<div class="modal-content border-0">

						<div class="modal-body text-center">
							<img 
							src=""
							id="modalImage"
							class="img-fluid rounded"
							style="max-height: 80vh;"
							>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){

		$('.preview-image').click(function(){

			var img = $(this).attr('src');

			$('#modalImage').attr('src', img);

			$('#imageModal').modal('show');

		});

	});
</script>