<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Pengajuan Preventive Maintenance</h1>
		<?php if (is_warehouse() || is_produksi() || is_admin()): ?>
		<a href="<?= base_url ('pm/tpm/tambah') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
	<?php endif; ?>
</div>

<?php if($this->session->flashdata('success_msg')): ?>
	<div class="alert alert-success text-center">
		<i class="fas fa-check"></i>
		<?= $this->session->flashdata('success_msg') ?>
	</div>
	<br>
<?php endif ?>

<?php if($this->session->flashdata('error_msg')): ?>
	<div class="alert alert-danger  text-center">
		<i class="fas fa-times"></i>
		<?= $this->session->flashdata('error_msg') ?>
	</div>
	<br>
<?php endif ?>
<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
				<thead class="table bg-info text-light">
					<tr>
						<th class="font-weight-bold align-middle text-center" width="1">No</th>
						<th width="80" class="font-weight-bold align-middle text-center">Tanggal</th>
						<th class="font-weight-bold align-middle text-center">Nama Mesin</th>
						<th class="font-weight-bold align-middle text-center">Keluhan</th>
						<th width="100" class="font-weight-bold align-middle text-center">Pengaju</th>
						<th width="30" class="font-weight-bold align-middle text-center">Total Pending</th>
						<th class="font-weight-bold align-middle text-center">Tindakan</th>
						<th class="font-weight-bold align-middle text-center">Status ACC</th>

						<th class="font-weight-bold align-middle text-center">Action</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$no = 1;
					foreach ($data as $row) {

						$tgl = strtotime(str_replace('-', '/', $row->created_at));
						$tanggal = date('d M Y', $tgl);
						?>
						<tr>
							<td width="1"><?= $no;?></td>
							<td><?= $tanggal;?></td>
							<td><?= $row->nama_mesin;?></td>
							<td><?= $row->keluhan;?></td>
							<td><?= $row->username;?></td>
							<td><?= $row->selisih;?></td>
							<td><?= $row->tindakan;?></td>
							<td><?= $row->status_mesin;?></td>
							<!-- <td><?= $row->nama_operator;?></td> -->
							<td>
								<a href="<?= base_url('pm/tpm/detail/'.$row->uuid); ?>" class="btn btn-md btn-info btn-block">Detail</a>
								
								<?php if (is_engineering() || is_admin()): ?>
								<a href="<?= base_url('pm/tpm/tindakan/'.$row->uuid); ?>" class="btn btn-md btn-warning btn-block">Tindakan</a>
							<?php endif; ?>
							<?php if(is_warehouse() || is_produksi() || is_admin()) :?>
							<?php if (empty($row->tindakan) || empty($row->nama_pelaksana)) : ?>

							<a href="<?= base_url('pm/tpm/edit/'.$row->uuid); ?>" 
								class="btn btn-md btn-warning btn-block">
								Edit
							</a>
						<?php endif; ?>
						<?php
						$type = $this->session->userdata('type');
						if ((is_produksi() || is_warehouse() || is_admin()) && ($type == 1 || $type == 2) && $row->nama_pelaksana != NULL) { ?>
							<a href="<?= site_url('pm/tpm/status/'.$row->uuid) ?>" 
								class="btn btn-sm btn-success btn-block"
								onclick="return confirm('Acc Tindakan PM ini?')">
								ACC
							</a>

						<?php } ?>
						<a href="<?= base_url('pm/tpm/delete_kegiatan/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
					<?php endif; ?>
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
