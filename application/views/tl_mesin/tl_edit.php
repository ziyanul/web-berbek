<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Tools Mesin</h1>

    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"> <a href="<?= base_url('Tools_Mesin');?>"> <i class="fas fa-arrow-left"></i> Data Tools Mesin</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Tools_Mesin/detail/'.$data->area_uuid) ?>"> Detail</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    </nav>

<div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Tools_Mesin/edit/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-3">
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
                        <label class="form-label">Tools Mesin <span class="text-danger">*</span></label>
                        <input type="text" name="tl_mesin" class="form-control <?= form_error('tl_mesin') ? 'invalid' : '' ?>" placeholder="Masukkan Nama tl_mesin" value="<?= $data->nama_tools; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('tl_mesin')) ? 'd-block':'';?>">
                            <?= form_error('tl_mesin') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" >
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mt-4">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Tools_Mesin/detail/'.$data->area_uuid) ?>" class="btn btn-md btn-danger mt-4">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>