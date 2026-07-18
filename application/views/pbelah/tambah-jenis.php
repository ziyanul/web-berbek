<div class="container-fluid">
	<h3 class="h3 mb-2 text-gray-800">Form Permintaan Sparepart </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('pbelah/jenis') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Area - Sub Area</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>
	<?php if($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div><br>
	<?php endif ?>	
	<?php if($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger  text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div><br>
	<?php endif ?>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('pbelah/tambahjenis') ?>" method="post">
				<div class="row">
					<div class="col-sm-6 mt-3 mb-2">
						<label class="form-label font-weight-bold">Nama Area :</label>
						<select class="form-control <?= form_error('area') ? 'invalid' : '' ?>"
							name="area">
							<option disabled selected>Pilih Area</option>
							<?php
							foreach ($area as $a) {
								?>
								<option value="<?= $a->uuid;?>" <?= set_select('area', $a->uuid);?>>
									<?= $a->nama_area;?></option>
									<?php
								}?>
							</select>
							<div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
								<?= form_error('area') ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 mt-3 mb-2">
							<label class="form-label font-weight-bold">Nama Sub Area :</label>
							<select class="form-control <?= form_error('sub_area') ? 'invalid' : '' ?>"
								name="sub_area">
								<option disabled selected>Pilih Sub Area</option>
							</select>
							<div class="invalid-feedback <?= !empty(form_error('sub_area')) ? 'd-block':'';?>"><?= form_error('sub_area') ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt-3 mb-2">
						<label class="form-label font-weight-bold">Jenis Pecah Belah :</label>
						<input type="text" name="jenis_pb"
						class="form-control <?= form_error('jenis_pb') ? 'invalid' : '' ?>"
						placeholder="Masukkan Nama jenis" value="<?= set_value('jenis_pb'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('jenis_pb')) ? 'd-block':'';?>"><?= form_error('jenis_pb') ?>
					</div>
				</div>
			</div>
			<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('pbelah/jenis') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
		</form>
	</div>
</div>
</div>

<script>
	$(document).ready(function() {
		$('select[name="area"]').change(function() {
			var area_uuid = $(this).val();

			$.get('<?= base_url('pbelah/get_lokasi_by_area/'); ?>' + area_uuid, function(res) {
				var result = JSON.parse(res);
				var elem = '<option disabled selected>Pilih Sub Area</option>';

				result.forEach(function(val) {
					elem += '<option value="' + val.uuid + '">' + val.lokasi +
					'</option>';
				})

				$('select[name="sub_area"]').html(elem);
			})
		})


	})
</script>


