<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Tambah Data Persentase Pelarutan Chemical</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('chemical/persen') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Chemical</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('chemical/tambahpersen/') ?>" method="post">
				<div class="row">
					<div class="col-sm-6 mt-2">
						<label class="form-label">Kode Jenis Pelarutan Chemical :<span class="text-danger">*</span></label>
						<input type="text" name="kode_chemical" class="form-control <?= form_error('kode_chemical') ? 'invalid' : '' ?>" placeholder="Masukkan kode chemical" value="<?= set_value('kode_chemical'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('kode_chemical')) ? 'd-block':'';?>">
							<?= form_error('kode_chemical') ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt-2">
						<label class="form-label">Chemical :<span class="text-danger">*</span></label>
						<select class="form-control <?= form_error('chemical') ? 'invalid' : '' ?>" name="chemical">
                        <option hidden>Pilih Chemical</option>
                        <?php
                        foreach ($data as $row) {
                            ?>
                            <option value="<?= $row->uuid;?>" <?= set_select('chemical', $row->uuid);?>><?= $row->chemical_name;?></option>
                            <?php
                        }
                        ?>
                    </select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 mt-2">
						<label class="form-label">Persentase Pelarutan :<span class="text-danger">*</span></label>

					</div>
				</div>
				<div class="row">
					<div class="col-sm-4 mt-2">
						<input type="text" name="persentase" class="form-control <?= form_error('persentase') ? 'invalid' : '' ?>" placeholder="Masukkan persentase pelarutan" value="<?= set_value('persentase'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('persentase')) ? 'd-block':'';?>">
							<?= form_error('persentase') ?>
						</div>
					</div>
					<div class="col-sm-2 mt-2">
						<select class="form-control <?= form_error('satuan') ? 'invalid' : '' ?>" name="satuan" id="satuan">
							<option selected disabled>Pilih Satuan</option>
							<option value="1" <?= set_select('satuan', 1);?>>%</option>
							<option value="2" <?= set_select('satuan', 2);?>>Ppm</option>
						</select>
						<div class="invalid-feedback <?= !empty(form_error('satuan')) ? 'd-block':'';?>">
							<?= form_error('satuan') ?>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
  
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('chemical/persen') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>