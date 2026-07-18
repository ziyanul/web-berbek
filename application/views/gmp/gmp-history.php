<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Monitoring ISO/TS</h1>
		<a href="<?= base_url ('gmp/tambah') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
	</div>
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1">No.</th>
							<th>Area</th>
							<th>lokasi</th>
							<th>Kegiatan</th>
							<!-- <th>E Te eR</th> -->
							<!-- <th>Countdown</th>
							<th>Penjadwalan</th> -->
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
						<tr>
							<td width="1"><?= $no;?></td>
							<td><?= $row->nama_area;?></td>
							<td><?= $row->lokasi;?></td>
							<td><?= $row->kegiatan;?></td>
							<!-- <td><?= $row->target;?> Hari</td> -->
							<!-- <td><?= $row->cd;?> Hari</td>
							<td><?= $row->jdwl;?></td> -->
							<td><?= $row->status_gmp;?></td>
							
							<!-- <td><?= $row->pelaksana;?></td>
							<td><?= $row->keterangan;?></td>
							<td><?= $row->dokumentasi_after;?></td>
							<td><?= $row->catatan;?></td> -->
							<td>
								<a href="<?= base_url('gmp/history/detail/'.$row->uuid); ?>" class="btn btn-md btn-info shadow-sm"><i class="fa fa-info fa-sm text-white-80 mr-2"></i>Detail</a>
								<!-- <a href="<?= base_url('gmp/status/'.$row->uuid); ?>" class="btn btn-md btn-info shadow-sm"><i class="fa fa-info fa-sm text-white-80 mr-2"></i>acc</a> -->
								
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
