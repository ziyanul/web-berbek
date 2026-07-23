<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Ubah Kondisi Barang Pecah Belah</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('pbelah/detail/'.$data->tanggal) ?>"><i class="fas fa-arrow-left mr-2"></i>Detail Pengecekan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('pbelah/editdetail/'. $data->uuid) ?>" method="post">
				
				<div class="row">
					<div class="col-sm-6 mt-2">
						<label class="form-label">Kondisi:</label>
						<select class="form-control <?= form_error('kondisi') ? 'is-invalid' : '' ?>" name="kondisi">
    <option disabled selected>Pilih Kondisi Barang</option>
    <option value="1" <?= $data->kondisi == 1 ? 'selected' : '' ?>>Baik</option>
    <option value="2" <?= $data->kondisi == 2 ? 'selected' : '' ?>>Tidak Baik</option>
</select>

<div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block' : ''; ?>">
    <?= form_error('kondisi') ?>
</div>

					</div>
				</div>
				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('pbelah/detail/'.$data->tanggal) ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>