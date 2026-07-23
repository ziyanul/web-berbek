<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Tambah Stock Chemical</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('chemical') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Chemical</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('chemical/tambah/') ?>" method="post">


				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label">Nama Chemical :<span class="text-danger">*</span></label>
						<select class="form-control <?= form_error('nama_chemical') ? 'invalid' : '' ?>" name="nama_chemical">
                        <option disabled selected>Pilih Chemical</option>
                        <?php
                        foreach ($data as $row) {
                            ?>
                            <option value="<?= $row->uuid;?>" <?= set_select('area', $row->uuid);?>><?= $row->chemical_name;?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="chemical_name">
                    
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label">Kode Chemical<span class="text-danger">*</span></label>
						<input type="text" name="chemical_id" class="form-control <?= form_error('chemical_id') ? 'invalid' : '' ?>" placeholder="chemical_id" value="<?= set_value('chemical_id'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('chemical_id')) ? 'd-block':'';?>">
							<?= form_error('chemical_id') ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label">Tambah Chemical<span class="text-danger">*</span></label>
						<input type="number" name="stock" class="form-control <?= form_error('stock') ? 'invalid' : '' ?>" placeholder="stock" value="<?= set_value('stock'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('stock')) ? 'd-block':'';?>">
							<?= form_error('stock') ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label">Prosentase Pelarutan Chemical<span class="text-danger">*</span></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 mt-3">
						<input type="number" name="banding" class="form-control <?= form_error('banding') ? 'invalid' : '' ?>" placeholder="banding" value="<?= set_value('banding'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('banding')) ? 'd-block':'';?>">
							<?= form_error('banding') ?>
						</div>
					</div>
					<div class="col-sm-3 mt-3">
						<select class="form-control <?= form_error('persen') ? 'invalid' : '' ?>" name="persen">
							<option selected disabled>Pilih Satuan:</option>
							<option value="1" <?= set_select('persen', 1);?>>%</option>
							<option value="2" <?= set_select('persen', 2);?>>Ppm</option>
						</select>
						<div class="invalid-feedback <?= !empty(form_error('persen')) ? 'd-block':'';?>">
							<?= form_error('persen') ?>
						</div>
					</div>
				</div>



				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('chemical') ?>" class="btn btn-md btn-danger">
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
        $('select[name="nama_chemical"]').change(function () {
            var val = $(this).val();
            $.get('<?= base_url('chemical/get_chemical_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="chemical_name"]').val(data.chemical_name);
            })
        })
    })
</script>

