<style>
	.doc_wrapper img {
		width: 100%;
	}

	.detail-label {
		font-weight: 600;
		color: #5a5c69;
	}

	.detail-value {
		font-weight: 700;
		color: #2e2f37;
	}

	.detail-box {
		background: #f8f9fc;
		border: 1px solid #e3e6f0;
		border-radius: 10px;
		padding: 20px;
	}

	.detail-table td {
		vertical-align: middle !important;
		padding: 12px 10px;
	}

	.info-title {
		font-size: 18px;
		font-weight: 700;
		color: #4e73df;
	}
</style>

<div class="container-fluid">

	<!-- Page Heading -->
	<h3 class="h3 mb-2 text-gray-800">Detail Planning Produksi</h3>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('filler/planning') ?>">
					<i class="fas fa-arrow-left mr-2"></i>Planning Produksi
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>

	<div class="card shadow mb-4 border-left-primary">
		<div class="card-body">

			<div class="row">
				<div class="col-lg">
					<div class="detail-box">
						<table class="table table-borderless detail-table mb-0">
							<tbody>
								<tr>
									<td width="220" class="detail-label">Tanggal Produksi</td>
									<td width="10">:</td>
									<td class="detail-value"><?= tanggal_indo($data->date); ?></td>
								</tr>
								<tr>
									<td class="detail-label">Varian Produk</td>
									<td>:</td>
									<td class="detail-value"><?= $data->nama_varian ?></td>
								</tr>
								<tr>
									<td class="detail-label">Target Counter</td>
									<td>:</td>
									<td class="detail-value"><?= number_format($data->total, 0, ',', '.'); ?> pcs</td>
								</tr>
								<tr>
									<td class="detail-label">Jam Start</td>
									<td>:</td>
									<td class="detail-value"><?= $data->start_time ?></td>
								</tr>
								<tr>
									<td class="detail-label">Jam Akhir</td>
									<td>:</td>
									<td class="detail-value"><?= $data->end_time ?></td>
								</tr>
								<tr>
									<td class="detail-label">Cleaning Schedule</td>
									<td>:</td>
									<td class="detail-value"><?= $data->clean ?> Menit</td>
								</tr>
								<tr>
									<td class="detail-label">Total Waktu Produksi</td>
									<td>:</td>
									<td>
										<span class="badge badge-primary p-2" style="font-size: 14px;">
											<?= $data->total_waktu ?> Jam
										</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				
			</div>

			<div class="row mt-4">
				<div class="col">
					<a href="<?= base_url('filler/planning') ?>" class="btn btn-danger px-4 shadow-sm">
						<i class="fa fa-arrow-left mr-1"></i> Kembali
					</a>
				</div>
			</div>

		</div>
	</div>
</div>