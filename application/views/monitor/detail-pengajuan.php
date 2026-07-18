<style>
	.doc_wrapper{width: 200px;}
	.doc_wrapper img{width: 100%;}
</style>
<div class="container-fluid">
	<h3 class="h3 mb-2 text-gray-800">Detail Permintaan "<?= $data->part; ?>" </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('monitor/pengajuan') ?>"><i class="fas fa-arrow-left mr-2"></i> Pengajuan Part</a></li>
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
								<td width="200" colspan="2"><div class="doc_wrapper"><?= !empty($data->foto) ? '<img src="' . base_url('upload/'.$data->foto) . '">' : 'Belum Dokumentasi'; ?></div></td>
								
								<td></td>
							</tr>
							<?php
							foreach ($foto as $val) {
								?>
								<tr class="table-bordered">
									<td width="200" colspan="2"><div class="doc_wrapper"><?= '<img src="' . base_url('upload/'.$val->foto) . '">'; ?></div></td>
									
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
					<table class="table table-bordered">
						<thead class="table table-bordered text-light bg-success">
							<tr>
								<th>No.</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th>User</th>
								<th>Catatan</th>
							</tr>
							<tbody>
								<?php
								$no = 1;
								foreach ($status as $value) {
									?>
									<tr>
										<td><?= $no;?>.</td>
										<td><?= $value->tanggal;?></td>
										<td><?= $value->status;?></td>
										<td><?= $value->username;?></td>
										<td><?= $value->keterangan;?></td>
									</tr>
									<?php
									$no ++;
								}
								?>
							</tbody>
						</table>
					</div>

				</div>
				<a href="<?= base_url('monitor/pengajuan') ?>" class="btn btn-md btn-primary">
						<i class="fa fa-arrow-left"></i> Kembali
					</a>
			</div>
		</div>