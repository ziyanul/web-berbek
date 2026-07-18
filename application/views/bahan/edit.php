<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Data Varian</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Varian') ?>"><i class="fas fa-arrow-left"></i>  Fokus Area</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

  <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Varian/edit/'.$data->uuid) ?>" method="post">
           
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Varian <span class="text-danger">*</span></label>
                        <input type="text" name="varian" class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" placeholder="Masukkan Nama varian" value="<?= $data->varian; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" placeholder="Masukkan Nama keterangan" value="<?= $data->keterangan; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" >
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('varian') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>









</div>