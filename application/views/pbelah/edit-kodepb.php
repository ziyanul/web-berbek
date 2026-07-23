<div class="container-fluid">
	<h1 class="h3 mb-2 text-gray-800">Ubah Kode Barang Pecah Belah</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('pbelah/detailkodepb/'.$data->jenis_pbelah_uuid) ?>"><i class="fas fa-arrow-left mr-2"></i>Kode Barang</a></li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<form class="user" action="<?= base_url('pbelah/editkodepb/'. $data->uuid) ?>" method="post">
				
				<div class="row">
					<div class="col-sm-6 mt-2">
						<label class="form-label">Kode Barang :</label>
						<input type="text" name="kode_pb" class="form-control <?= form_error('kode_pb') ? 'invalid' : '' ?>" value="<?= $data->kode_barang; ?>">
						<div class="invalid-feedback <?= !empty(form_error('kode_pb')) ? 'd-block':'';?>">
							<?= form_error('kode_pb') ?>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>
						<a href="<?= base_url('pbelah/detailkodepb/'.$data->jenis_pbelah_uuid) ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>