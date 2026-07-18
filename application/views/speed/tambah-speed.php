<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Tambah Master Speed Mesin Filler</h1>
        
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('speed') ?>"><i class="fas fa-arrow-left"></i> Master Speed Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('speed/tambah') ?>" method="post">

              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Mesin : <span class="text-danger">*</span></label>
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
            </div>
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Varian : <span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('varian') ? 'is-invalid' : '' ?>" name="varian">
                        <option disabled selected>Pilih Varian</option>
                        <?php foreach ($varian as $v): ?>
                            <option value="<?= $v->uuid; ?>"><?= $v->varian; ?> - <?= $v->keterangan; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                        <?= form_error('varian') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Speed Mesin : <span class="text-danger">*</span></label>
                    <input type="number" name="speed" class="form-control <?= form_error('speed') ? 'is-invalid' : '' ?>" placeholder="Speed Mesin / Menit" value="<?= set_value('speed'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('speed')) ? 'd-block':'';?>">
                        <?= form_error('speed') ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-md btn-success mr-2">
                      <i class="fa fa-save"></i> Simpan
                  </button>
                  <a href="<?= base_url('speed') ?>" class="btn btn-md btn-danger">
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