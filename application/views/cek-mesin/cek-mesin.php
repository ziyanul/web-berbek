<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4 mr-2">
		<h2 class="h2 mb-2 text-gray-800">Data Pengecekan Mesin Awal Produksi</h2>
		<a href="cekmesin/dataitem" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-info fa-sm text-white mr-2"></i>Data Item</a>
	</div>
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th class="font-weight-bold align-middle text-center" width="1">No</th>
							<th class="font-weight-bold align-middle text-center" width="100">Tanggal Pengecekan</th>
							<th class="font-weight-bold align-middle text-center" width="150">Tanggal Planning Produksi</th>
							<th class="font-weight-bold align-middle text-center" width="200">Varian</th>
							<th class="font-weight-bold align-middle text-center" width="100">Area Checked</th>
							<th class="font-weight-bold align-middle text-center" width="130">Form</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							?>
							<tr>
								<td width="1"><?= $no ?></td>
								<td><?= $row->tgl_cek ?></td>
								<td><?= $row->tgl ?></td>
								<td class="text-center"><?= $row->varian ?></td>
								<td class="text-center"><?= $row->jumlah_area ?></td>
								<td>
									<a href="<?= base_url('cekmesin/checklist/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-md btn-block font-weight-bold"><i class="fa fa-check mr-2 fa-sm text-white"></i>Cek List</a>
									<a href="<?= base_url('cekmesin/formcekmesin/'.$row->uuid); ?>" class="btn btn-md btn-info shadow-md btn-block font-weight-bold"><i class="fa fa-info mr-2 fa-sm text-white"></i>Detail</a>
									<a href="<?= base_url('cekmesin/print/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-md btn-block font-weight-bold" target="blank"><i class="fa fa-print mr-2 fa-sm text-white"></i>Form</a>
								</td>
							</tr>
							<?php
							$no++;
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>