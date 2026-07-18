<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Planning Autonomous Maintenance</h1>
		<a href="<?= base_url ('am/tpm/tambah') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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
							<th width="1">No.</th>
							<th>Tanggal</th>
							<th>Area</th>
							<th>mesin</th>
							<th>Kegiatan</th>
							<!-- <th>E Te eR</th> -->
							<th>Countdown</th>
							<th>Penjadwalan</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							$tgl = strtotime($row->created_at);
							$tanggal = date('d M Y', $tgl);
							?>
							<tr>
								<td width="1"><?= $no;?></td>
								<td><?= $tanggal; ?></td>
								<td><?= $row->nama_area;?></td>
								<td><?= $row->nama_mesin;?></td>
								<td><?= $row->kegiatan;?></td>
								<!-- <td><?= $row->target;?> Hari</td> -->
								<td><?= number_format($row->cd);?><?= $row->jadwal == 0 ? ' Hari' : ' Jam'; ?></td>
								<td><?= $row->jdwl;?></td>
								<td><?= $row->status_am;?></td>

							<!-- <td><?= $row->pelaksana;?></td>
							<td><?= $row->keterangan;?></td>
							<td><?= $row->dokumentasi_after;?></td>
							<td><?= $row->catatan;?></td> -->
							<td>
									<a href="<?= base_url('am/tpm/detail/'.$row->uuid); ?>" 
										class="btn btn-sm btn-block btn-success shadow-sm mr-1">
										<i class="fas fa-info-circle mr-1"></i> Detail
									</a>

									<a href="<?= base_url('am/tpm/edit/'.$row->uuid); ?>" 
										class="btn btn-sm btn-block btn-warning shadow-sm mr-1 text-dark">
										<i class="fas fa-edit mr-1"></i> Edit
									</a>

									<?php if(($this->session->userdata('type')==1 || $this->session->userdata('type')==2) && ($row->pelaksana != NULL)){ ?>
										<a href="<?= base_url('am/tpm/status/'.$row->uuid); ?>" 
											class="btn btn-sm btn-block btn-info shadow-sm mr-1 text-white">
											<i class="fas fa-check-circle mr-1"></i> ACC
										</a>
									<?php } ?>

									<?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){ ?>
										<a href="<?= base_url('am/tpm/delete_am/'.$row->uuid); ?>" 
											class="btn btn-sm btn-block btn-danger shadow-sm mr-1" 
											onclick="return confirm('Anda yakin ingin menghapus data ini?')">
											<i class="fas fa-trash mr-1"></i> Hapus
										</a>
									<?php } ?>

								
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
