<div class="container-fluid">
		<h1 class="h3 mb-2 text-gray-800">Penerimaan <?= $data[0]->item_barang ?></h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url('stock/') ?>"><i class="fas fa-arrow-left"></i> Data Bahan</a></li>
				<li class="breadcrumb-item active" aria-current="page">Detail</li>
			</ol>
		</nav>
	
	<?php if($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?php echo $this->session->flashdata('success_msg'); ?>
		</div>
		<br>
	<?php endif; ?>
	<?php if($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger  text-center">
			<i class="fas fa-times"></i>
			<?php echo $this->session->flashdata('error_msg'); ?>
		</div>
		<br>
	<?php endif ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th class='align-middle text-center'>No. Reservasi</th>
							<th class='align-middle text-center'>Kode Produk</th>
							<th class='align-middle text-center'>Exp Date</th>
							<th class='align-middle text-center'>Qty Diterima</th>
							<th class='align-middle text-center'>Waktu Diterima</th>                            
						</tr>
					</thead>
					<tbody>
						<?php
						$no= 1;
						foreach ($data as $row) {
							?>
							<tr>
								<td class='align-middle text-center'><?= sprintf("%04d", ( $row->no_reservasi)); ?></td>
								<td class='align-middle text-center'><?= $row->kode_produk;?></td>
								<td class='align-middle text-center'><?= $row->exp_date;?></td>
								<td class='align-middle text-center'><?= $row->qty_dikirim;?></td>
								<td class='align-middle text-center'><?= $row->jam_terima;?></td>
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