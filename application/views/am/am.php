<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Monitoring Autonomous Maintenance</h1>
		
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
			<div class="row mb-3">

				<div class="col-md-3">
					<label>Area</label>
					<select id="filterArea" class="form-control">
						<option value="">Semua Area</option>
						<?php 
						$areas = [];
						foreach($data as $row){
							$areas[$row->nama_area] = true;
						}
						foreach(array_keys($areas) as $area){ ?>
							<option value="<?= $area ?>"><?= $area ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<label>Mesin</label>
					<select id="filterMesin" class="form-control">
						<option value="">Semua Mesin</option>
						<?php 
						$mesins = [];
						foreach($data as $row){
							$mesins[$row->nama_mesin] = $row->nama_area;
						}

						foreach($mesins as $mesin => $area){ ?>
							<option value="<?= $mesin ?>" data-area="<?= $area ?>">
								<?= $mesin ?>
							</option>
						<?php } ?>
					</select>
				</div>

			</div>
			<div class="table-responsive">
				<table class="table table-bordered" id="datatable_am" width="100%" cellspacing="0">
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
								<td><?= $tanggal;?></td>
								<td><?= $row->nama_area;?></td>
								<td><?= $row->nama_mesin;?></td>
								<td><?= $row->kegiatan;?></td>
								<!-- <td><?= $row->target;?> Hari</td> -->
								<td>
									<?php if((float)$row->cd < 0): ?>
										<span class="text-danger font-weight-bold">
											Telat <?= number_format(abs((float)$row->cd), 0); ?> <?= $row->satuan_cd; ?>
										</span>
									<?php else: ?>
										<?= number_format((float)$row->cd, 0); ?> <?= $row->satuan_cd; ?>
									<?php endif; ?>
								</td>
								<td><?= $row->jdwl;?></td>
								<td><?= $row->status_am;?></td>

							<!-- <td><?= $row->pelaksana;?></td>
							<td><?= $row->keterangan;?></td>
							<td><?= $row->dokumentasi_after;?></td>
							<td><?= $row->catatan;?></td> -->
							<td>
								<a href="<?= base_url('am/detail/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm mt-2 mr-2" style="flex: 1;">
									<i class="fas fa-info-circle mr-1"></i> Detail
								</a>

								<a href="<?= base_url('am/tindakan/'.$row->uuid); ?>" class="btn btn-md btn-warning btn-block shadow-sm" style="flex: 1;">
									<i class="fas fa-tools mr-1"></i> Tindakan
								</a>

								<?php if(($this->session->userdata('type')==1 || $this->session->userdata('type')==2) && ($row->pelaksana != NULL)){?>
									<a href="<?= base_url('am/status/'.$row->uuid); ?>" class="btn btn-md btn-info btn-block shadow-sm mr-2 mt-2" style="flex: 2;">
										<i class="fas fa-check-circle mr-1"></i> ACC
									</a>
								<?php }?>

								<?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
									<a href="<?= base_url('am/tpm/delete_am/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" style="flex: 2;" onclick="return confirm('Anda yakin ingin menghapus data ini?')">
										<i class="fas fa-trash-alt mr-1"></i> Hapus
									</a>
								<?php }?>
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

<script>
	$(document).ready(function(){

		var table = $('#datatable_am').DataTable({
			destroy:true,
			searching:true,
			lengthChange:false,
			autoWidth:false,
			dom:'rtip'
		});

    // FILTER AREA
		$('#filterArea').on('change', function(){

			var area = $(this).val();

			table.column(2).search(area).draw();

        // reset mesin
			$('#filterMesin').val('');

        // hide mesin yang tidak sesuai area
			$('#filterMesin option').each(function(){

				var mesinArea = $(this).data('area');

				if($(this).val() == ""){
					$(this).show();
					return;
				}

				if(area == "" || mesinArea == area){
					$(this).show();
				}else{
					$(this).hide();
				}

			});

		});

    // FILTER MESIN
		$('#filterMesin').on('change', function(){

			var mesin = $(this).val();

			table.column(3).search(mesin).draw();

		});

    // FILTER STATUS
		$('#filterStatus').on('change', function(){

			var status = $(this).val();

			table.column(7)
			.search(status ? '^'+status+'$' : '', true, false)
			.draw();

		});


	});
</script>