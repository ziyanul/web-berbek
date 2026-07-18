<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Tambah Data Chemical</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('chemical/master') ?>"><i class="fas fa-arrow-left mr-2"></i>Master Chemical</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('chemical/tambahmaster/') ?>" method="post">


				<div class="row">
					<div class="col-sm-6 mt-3">
						<label class="form-label">Nama Chemical :<span class="text-danger">*</span></label>
						<input type="text" name="nama_chemical" class="form-control <?= form_error('nama_chemical') ? 'invalid' : '' ?>" placeholder="Masukkan nama_chemical" value="<?= set_value('nama_chemical'); ?>">
						<div class="invalid-feedback <?= !empty(form_error('nama_chemical')) ? 'd-block':'';?>">
							<?= form_error('nama_chemical') ?>
						</div>
					</div>
				</div>

				

				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('chemical/master') ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>



