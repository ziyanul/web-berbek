<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Tambah Data Speed Mesin Filler</h1>
        
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('filler/speed') ?>"><i class="fas fa-arrow-left"></i> Data Speed Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('filler/tambahspeed') ?>" method="post">

              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Mesin</label>
                    <select class="form-control <?= form_error('mesin') ? 'is-invalid' : '' ?>" name="mesin">
                        <option disabled selected>Pilih Mesin</option>
                        <?php foreach ($mesin as $row): ?>
                            <option value="<?= $row->uuid; ?>"><?= $row->nama_mesin; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <input type="hidden" name="mesin_name">
                    <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                        <?= form_error('mesin') ?>
                    </div>
                </div>
                 <div class="col-sm-6 mb-4">
                    <label class="form-label">Sensor ID</label>
                    <input type="text" name="f_sensor_id" class="form-control <?= form_error('f_sensor_id') ? 'invalid' : '' ?>" placeholder="Masukkan ID sensor" value="<?= set_value('f_sensor_id'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('f_sensor_id')) ? 'd-block':'';?>">
                        <?= form_error('f_sensor_id') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Speed Okey</label>
                    <input type="number" name="okey" class="form-control <?= form_error('okey') ? 'invalid' : '' ?>" placeholder="Speed Okey /Menit" value="<?= set_value('okey'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('okey')) ? 'd-block':'';?>">
                        <?= form_error('okey') ?>
                    </div>
                </div>
            
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Speed Champ</label>
                    <input type="number" name="champ" class="form-control <?= form_error('champ') ? 'invalid' : '' ?>" placeholder="Speed Champ /Menit" value="<?= set_value('champ'); ?>">
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


<script>
    $(document).ready(function () {
        $('select[name="mesin"]').change(function () {
            var val = $(this).val();
            $.get('<?= base_url('mesin/get_mesin_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="mesin_name"]').val(data.nama_mesin);
            })
        })
    })
</script>