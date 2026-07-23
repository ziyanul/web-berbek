<div class="container-fluid">

  <h1 class="h3 mb-3 text-gray-800">Input Pelaksana Maintenance</h1>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= base_url($this->uri->segment(2)=='tpm'?'pm/tpm':'pm') ?>">
          <i class="fas fa-arrow-left mr-2"></i>Data Preventive Maintenance
        </a>
      </li>
      <li class="breadcrumb-item active">Tindakan</li>
    </ol>
  </nav>

  <div class="card shadow mb-4">
    <div class="card-body">

      <form action="<?= base_url('pm/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'tindakan/'.$data->maintenance_uuid)?>" method="post" enctype="multipart/form-data">

        <!-- INFORMASI -->
        <h6 class="text-primary font-weight-bold mb-3">Informasi Mesin</h6>

        <div class="form-group row">

          <!-- KIRI: INFORMASI -->
          <div class="col-sm-6">

            <div class="row mb-2">
              <label class="col-sm-4 font-weight-bold">Area</label>
              <div class="col-sm-8">
                <p class="form-control-plaintext"><?= $data->nama_area; ?></p>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-sm-4 font-weight-bold">Mesin</label>
              <div class="col-sm-8">
                <p class="form-control-plaintext"><?= $data->nama_mesin; ?></p>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 font-weight-bold">Keluhan</label>
              <div class="col-sm-8">
                <p class="form-control-plaintext"><?= $data->keluhan; ?></p>
              </div>
            </div>

          </div>

          <!-- KANAN: GAMBAR -->
          <div class="col-sm-6">
            <?php if(!empty($data->dokumentasi)) : ?>
              <a href="#" data-toggle="modal" data-target="#modalFoto">
                <img src="<?= base_url('upload/'.$data->dokumentasi) ?>" 
                class="img-fluid rounded shadow" 
                style="max-height:250px; object-fit:cover; cursor:pointer;">
              </a>
            <?php else: ?>
              <p class="text-muted">Tidak ada dokumentasi</p>
            <?php endif; ?>
          </div>

        </div>

        <hr>

        <!-- PELAKSANA -->
        <h6 class="text-primary font-weight-bold mb-3">Pelaksanaan</h6>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label font-weight-bold">Pelaksana</label>
          <div class="col-sm-10">
            <p class="form-control-plaintext"><?= $this->auth_model->current_user()->fullname; ?></p>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label font-weight-bold">
            Tindakan <span class="text-danger">*</span>
          </label>
          <div class="col-sm-9">
            <input type="text" name="tindakan" class="form-control" value="<?= set_value('tindakan', $data->tindakan); ?>">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label font-weight-bold">
            Dokumentasi <span10class="text-danger">*</span>
            </label>
            <div class="col-sm-9">
              <input type="file" name="dokumentasi_after" class="form-control <?= form_error('dokumentasi_after') ? 'is-invalid' : '' ?>">

              <div class="invalid-feedback">
                <?= form_error('dokumentasi_after') ?>
              </div>
            </div>
          </div>

          <hr>

          <!-- BUTTON -->
          <div class="row">
            <div class="col mt-3">
              <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Simpan
              </button>

              <a href="<?= base_url($this->uri->segment(2)=='tpm'?'pm/tpm':'pm') ?>" class="btn btn-danger">
                <i class="fa fa-times"></i> Batal
              </a>
            </div>
          </div>

        </form>
        <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Preview Dokumentasi</h5>
                <button type="button" class="close" data-dismiss="modal">
                  <span>&times;</span>
                </button>
              </div>

              <div class="modal-body text-center">
                <img src="<?= base_url('upload/'.$data->dokumentasi) ?>" 
                class="img-fluid rounded" style="max-height:80vh;">
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>