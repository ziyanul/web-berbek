<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Data Type Drystore</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('drystore/type') ?>"><i class="fas fa-arrow-left"></i>  Type Drystore</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

  <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('drystore/edit_type/'.$data->uuid) ?>" method="post">

                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Type <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control <?= form_error('nama') ? 'invalid' : '' ?>" value="<?= $data->nama; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('nama')) ? 'd-block':'';?>">
                            <?= form_error('nama') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <label class="form-label">Standar Waste <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="std_waste" class="form-control <?= form_error('std_waste') ? 'invalid' : '' ?>" value="<?= $data->std_waste; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('std_waste')) ? 'd-block':'';?>">
                            <?= form_error('std_waste') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control <?= form_error('satuan') ? 'invalid' : '' ?>" value="<?= $data->satuan; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('satuan')) ? 'd-block':'';?>">
                            <?= form_error('satuan') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" >
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('drystore/waste') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>









</div>