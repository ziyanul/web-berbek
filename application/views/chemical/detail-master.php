<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h3 class="h3 mb-2 text-gray-800">Detail Data Chemical <?= $data->chemical_name ?></h3>
		<?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
			<a href="chemical/tambah" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
		<?php }?>
	</div>

	<!-- DataTales Example -->
	<div class="card shadow">
		<div class="card-body">
			<div class="row">
				<div class="col-6 mt-3">
					<h3>Penambahan Stock <?= $data->chemical_name ?></h3>
					<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
						<thead class="table bg-info text-light">
							<tr>
								<th width="1" class="font-weight-bold">No</th>
								<th class="font-weight-bold">Tanggal</th>
								<th class="font-weight-bold">Tambah Stock</th>
								<th class="font-weight-bold">Action</th>
							</tr> 
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($chemical as $row) {
								?>
								<tr>
									<td><?= $no; ?></td>
									<td><?= $row->tgl; ?></td>
									<td><?= $row->stock; ?></td>
									<td>
										action
									</td>
								</tr>
								<?php
								$no++;
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="col-6 mt-3">
					<h3 class="h3 mb-3">Penggunaan Stock <?= $data->chemical_name ?></h3>
					<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
						<thead class="table bg-info text-light">
							<tr>
								<th width="1" class="font-weight-bold">No</th>
								<th class="font-weight-bold">Tanggal</th>
								<th class="font-weight-bold">Penggunaan Stock</th>
								<th class="font-weight-bold">Action</th>
							</tr> 
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($chemical as $row) {
								?>
								<tr>
									<td><?= $no; ?></td>
									<td><?= $row->tgl; ?></td>
									<td><?= $row->stock; ?></td>
									<td>
										action
									</td>
								</tr>
								<?php
								$no++;
							}
							?>
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#datatables').DataTable({
			searching: false,
			paging: false,
			info: false,
			ordering: false
		});
	});
</script>