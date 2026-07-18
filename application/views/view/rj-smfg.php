
<div class="container-fluid">
	<div class="card shadow mt-4">
		<div class="card-header">
			<!-- Tombol untuk membuka modal -->
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h5 class="h3" id="modalTambahLabel">Data Bad Produk SMFG</h5>
				<a href="<?= base_url('view/tambah_smfg'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
			</div>
		</div>
		<?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
       
    <?php endif ?>
    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
       
    <?php endif ?>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1" class="font-weight-bold">No</th>
							<th class="font-weight-bold">Tanggal</th>
							<th class="font-weight-bold">Varian</th>

							<th class="font-weight-bold">Action</th>
						</tr> 
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= $row->tgl; ?></td>
								<td><?= $row->varian_name; ?></td>

								<td>
									<a href="<?= base_url('view/detailsmfg/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block"><i class="fas fa-info fa-sm text-white"></i> Detail</a>

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
