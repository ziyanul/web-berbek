<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Input Pelaksana AM</h1>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= base_url($this->uri->segment(2)=='tpm'?'am/tpm':'am') ?>">
          <i class="fas fa-arrow-left mr-2"></i>Task AM
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Task</li>
    </ol>
  </nav>

  <div class="card shadow mb-4">
    <div class="card-body">
      <form class="user" action="<?= base_url('am/tindakan/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">

        <!-- INFORMASI AM -->
        <div class="mb-4">
          <h5 class="text-primary font-weight-bold mb-3">Informasi Autonomous Maintenance</h5>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Area</label>
              <input type="text" class="form-control" value="<?= $data->nama_area; ?>" readonly>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Mesin</label>
              <input type="text" class="form-control" value="<?= $data->nama_mesin; ?>" readonly>
            </div>
          </div>

          

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Metode Jadwal</label>
              <input type="text" class="form-control" 
              value="<?= $data->jadwal == 0 ? 'RH Harian' : ($data->jadwal == 1 ? 'Plan Produksi' : 'Counter Filler'); ?>" 
              readonly>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Target Jadwal</label>
              <input type="text" class="form-control" value="<?= $data->target; ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <label class="form-label font-weight-bold">Kegiatan</label>
            <textarea class="form-control" rows="2" readonly><?= $data->kegiatan; ?></textarea>
          </div>
        </div>
        <hr class="mb-4">

        <!-- INPUT PELAKSANA -->
        <div class="mb-3">
          <h5 class="text-success font-weight-bold mb-3">Input Hasil AM</h5>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Pelaksana</label>
              <input type="text" 
              name="pelaksana" 
              class="form-control <?= form_error('pelaksana') ? 'is-invalid' : '' ?>" 
              placeholder="Dikerjakan oleh?"
              value="<?= set_value('pelaksana', $data->pelaksana); ?>">
              <div class="invalid-feedback">
                <?= form_error('pelaksana') ?>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Catatan <span class="text-danger">*</span></label>
              <input type="text" 
              name="catatan" 
              class="form-control <?= form_error('catatan') ? 'is-invalid' : '' ?>" 
              placeholder="Hasil AM?"
              value="<?= set_value('catatan', $data->catatan); ?>">
              <div class="invalid-feedback">
                <?= form_error('catatan') ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label font-weight-bold">Dokumentasi Hasil AM</label>
              <input type="file" 
              name="dokumentasi_after" 
              id="dok_af" 
              class="form-control <?= form_error('dokumentasi_after') ? 'is-invalid' : '' ?>">
              <div class="invalid-feedback d-block">
                <?= form_error('dokumentasi_after') ?>
              </div>

              <?php if (!empty($data->dokumentasi_after)) : ?>
                <small class="text-muted d-block mt-2">
                  File saat ini: 
                  <a href="<?= base_url('uploads/am/'.$data->dokumentasi_after); ?>" target="_blank">
                    Lihat Dokumentasi
                  </a>
                </small>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="row mt-4">
          <div class="col">
            <button type="submit" class="btn btn-success px-4 mr-2">
              <i class="fa fa-save mr-1"></i> Simpan
            </button>
            <a href="<?= base_url($this->uri->segment(2)=='tpm'?'am/tpm':'am') ?>" class="btn btn-danger px-4">
              <i class="fa fa-times mr-1"></i> Batal
            </a>
          </div>
        </div>

      </form>
    </div>
</div></div>