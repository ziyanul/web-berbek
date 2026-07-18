<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Pengajuan Repair & New Part</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('partrequest') ?>"><i class="fas fa-arrow-left mr-2"></i> Repair & New Part</a></li>
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
			<form class="user" action="<?= base_url('partrequest/tambah') ?>" method="post" enctype="multipart/form-data">
				
				<div class="row">
					<div class="col-sm-6 mt-3 mb-2">
						<label class="form-label font-weight-bold">Nama SparePart :</label>
						<input type="text" name="part" class="form-control <?= form_error('part') ? 'invalid' : '' ?>" value="<?= set_value('part'); ?>" placeholder="Nama SparePart yang Diminta">
						<div class="invalid-feedback <?= !empty(form_error('part')) ? 'd-block':'';?>">
							<?= form_error('part') ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt-3 mb-2">
						<label class="form-label font-weight-bold">Jenis :</label>
						<select class="form-control <?= form_error('status') ? 'invalid' : '' ?>" name="jenis">
							<option selected disabled>Pilih Jenis</option>
							<option value="1">Stock</option>
							<option value="2">Repair</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label font-weight-bold">Dokumentasi Part :</label>
						<input type="file" name="foto" class="form-control <?= form_error('foto') ? 'invalid' : '' ?>" placeholder="0" value="<?= set_value('foto'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('foto')) ? 'd-block':'';?>">
							<?= form_error('foto') ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 mt-3 mb-2">
						<label class="form-label font-weight-bold">Keterangan :</label>
						<input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" value="<?= set_value('keterangan'); ?>" placeholder="">
						<div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
							<?= form_error('keterangan') ?>
						</div>
					</div>
				</div>
				
				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('partrequest') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>
<script>
	$(document).ready(function () {

		function data_part_lifetime_harga(lifetime, harga) {
			$('.part_lifetime').html(lifetime);
			$('input[name="lifetime_name"]').val(lifetime);

			$('.part_harga').html(harga);
			$('input[name="harga_name"]').val(harga);
		}

		$('select[name="area"]').change(function () {
            var area_uuid = $(this).val(); // value yang di pilih atau selected

            $.get('<?= base_url('part/get_mesin_by_area/');?>'+area_uuid, function (res) {
            	var result = JSON.parse(res);
            	var elem = '<option disabled selected>Pilih Mesin</option>';
            	result.forEach(function (val) {
            		elem += '<option value="'+val.uuid+'">'+val.nama_mesin+'</option>';
            	})

            	$('select[name="mesin"]').html(elem);
            })

            data_part_lifetime_harga('', '');


        })

		$('select[name="mesin"]').change(function () {
			var mesin_uuid = $(this).val();
			$.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid, function (res) {
				var data = JSON.parse(res);
				$('input[name="mesin_name"]').val(data.nama_mesin);
			})


			$.get('<?= base_url('monitor/get_part_by_mesin/');?>'+mesin_uuid, function (res){
				var result = JSON.parse(res);

				var elem = '<option disabled selected>Pilih Sparepart</option>';
				result.forEach(function (val) {
					elem += '<option value="'+val.uuid+'">'+val.nama_part+'</option>';
				})

				$('select[name="part"]').html(elem);
			})

			data_part_lifetime_harga('', '');

		})


		$('select[name="part"]').change(function () {
			var val = $(this).val();
			$.get('<?= base_url('part/get_part_name/');?>'+val, function (res) {
				var part = JSON.parse(res);
				$('input[name="part_name"]').val(part.nama_part);

				data_part_lifetime_harga(part.lifetime, part.harga);
			})
		})

	})
</script>


