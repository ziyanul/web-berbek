<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Monitoring ISO/TS</h1>
		<a href="<?= base_url ('gmp/tambah') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1">No.</th>
							<th>Tanggal</th>
							<th>Area</th>
							<th>lokasi</th>
							<th>Kegiatan</th>
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
                        	$tanggal = date('d-m-Y', $tgl);
                            ?>
						<tr>
							<td width="1"><?= $no;?></td>
							<td><?= $tanggal;?></td>
							<td><?= $row->nama_area;?></td>
							<td><?= $row->lokasi;?></td>
							<td><?= $row->kegiatan;?></td>
							<td><?= number_format($row->cd);?><?= $row->jadwal == 0 ? ' Hari' : ' Jam'; ?></td>
							<td><?= $row->jdwl;?></td>
							<td><?= $row->status_gmp;?></td>
							<td>
                                    <div style="display: flex;">
                                        <a href="<?= base_url('gmp/detail/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm mt-2 mr-2" style="flex: 1;">Detail</a>
                                        <a href="<?= base_url('gmp/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning btn-block shadow-sm" style="flex: 1;">Tindakan</a>
                                    </div>
                                    <div style="display: flex;">
                                        <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                                            <a href="<?= base_url('gmp/status/'.$row->uuid); ?>" class="btn btn-md btn-info btn-block shadow-sm mr-2 mt-2" style="flex: 2;">ACC</a>
                                            <a href="<?= base_url('gmp/delete_gmp/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" style="flex: 2;" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                        <?php }?>
                                    </div>
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
