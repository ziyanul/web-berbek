<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ubah Data Speed Mesin Filler</h1>
        <a href="<?= base_url('filler/speedtambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('filler/speed') ?>"><i class="fas fa-arrow-left"></i> Data Speed Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('filler/editspeed/'.$data->uuid) ?>" method="post">

              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Mesin (ID) :</label>
                    <span class="font-weight-bold"><?= $data->mesin ?>  (<?= $data->t_sensor_device_id ?>)</span>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 mb-4">
                    <label class="form-label">Speed Okey :</label>
                    <input type="number" name="okey" class="form-control <?= form_error('okey') ? 'invalid' : '' ?>" placeholder="Speed Okey /Menit" value="<?= $data->okey ?>">
                    <div class="invalid-feedback <?= !empty(form_error('okey')) ? 'd-block':'';?>">
                        <?= form_error('okey') ?>
                    </div>
                </div>
            
                <div class="col-sm-3 mb-4">
                    <label class="form-label">Speed Champ :</label>
                    <input type="number" name="champ" class="form-control <?= form_error('champ') ? 'invalid' : '' ?>" placeholder="Speed Champ /Menit" value="<?= $data->champ ?>">
                    <div class="invalid-feedback <?= !empty(form_error('champ')) ? 'd-block':'';?>">
                        <?= form_error('champ') ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-md btn-success mr-2">
                      <i class="fa fa-save"></i> Simpan
                  </button>
                  <a href="<?= base_url('filler/masterspeed') ?>" class="btn btn-md btn-danger">
                      <i class="fa fa-times"></i> Batal
                  </a>
              </div>
          </div>
      </form>
  </div>
</div>
</div>

