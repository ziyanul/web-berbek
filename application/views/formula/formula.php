<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Data Formula</h1>

		<a href="<?= base_url('formula/tambah'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>

	</div>
	<!-- DataTales Example -->
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
				<table class="table table-bordered table-hover" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th>No.</th>
							<th>Nama Formula</th>
							<th>Total Berat</th>
							<th>Keterangan</th>
							<th>Aksi</th>                                      
						</tr>
					</thead>

					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= $row->nama_formula; ?></td>
								<td><?= $row->total; ?></td>
								<td><?= $row->keterangan; ?></td>
								<td>
									<a href="<?= base_url('formula/detail/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block"><i class="fas fa-info fa-sm text-white"></i> Detail</a>
									<a href="<?= base_url('formula/edit/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fas fa-edit fa-sm text-white"></i> Edit</a>
									<a href="<?= base_url('formula/delete/' . $row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block"><i class="fas fa-trash fa-sm text-white"></i> Hapus</a>
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

<script>
	function addRow(bahan = '', qty = '') {
		let row = `
    <tr>
        <td>
            <select name="bahan_uuid[]">
                <?php foreach($bahan_list as $b): ?>
                    <option value="<?= $b->uuid ?>"><?= $b->nama_bahan ?></option>
                <?php endforeach; ?>
            </select>
        </td>

        <td>
            <input type="number" name="qty[]" value="${qty}">
        </td>

        <td>
            <button type="button" onclick="this.closest('tr').remove()">X</button>
        </td>
    </tr>
            `;

            document.querySelector('#table-bahan tbody').insertAdjacentHTML('beforeend', row);
        }
    </script>