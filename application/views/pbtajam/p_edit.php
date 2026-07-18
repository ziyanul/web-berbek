<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Jenis Benda Tajam</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"> <a href="<?= base_url('Pbtajam');?>"> <i
                        class="fas fa-arrow-left"></i> Data Area Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Jenis Benda</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Pbtajam/editjenis/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option hidden>Pilih Area</option>
                            <?php
							foreach ($area as $row) {
								$selected = ($row->uuid == $data->area_uuid) ? 'selected' : '';
								?>
                            <option value="<?= $row->uuid; ?>" <?= $selected; ?>><?= $row->nama_area; ?></option>
                            <?php
							}
							?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                            <?= form_error('area') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Benda Tajam <span class="text-danger">*</span></label>
                        <input type="text" name="pbtajam"
                            class="form-control <?= form_error('pbtajam') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Nama Benda Tajam" value="<?= $data->jenis_benda; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('pbtajam')) ? 'd-block':'';?>">
                            <?= form_error('pbtajam') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mt-4">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Pbtajam') ?>" class="btn btn-md btn-danger mt-4">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>