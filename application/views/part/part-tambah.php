<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Tambah Data Sparepart</h1>
        <a href="<?= base_url('part/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('part/') ?>"><i class="fas fa-arrow-left"></i> Data Sparepart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('part/tambah') ?>" method="post">
              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Area</label>
                    <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                        <option disabled selected>Pilih Area</option>
                        <?php
                          foreach ($area as $row) {
                            ?>
                        <option value="<?= $row->uuid;?>" <?=set_select('area', $row->uuid);?>>
                            <?= $row->nama_area;?>
                        </option>
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
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Mesin</label>
                    <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                        <option disabled selected>Pilih Mesin</option>
                    </select>
                    <input type="hidden" name="mesin_name">
                    <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                        <?= form_error('mesin') ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Sparepart</label>
                    <input type="text" name="part" class="form-control <?= form_error('part') ? 'invalid' : '' ?>" placeholder="Masukkan Nama Part" value="<?= set_value('part'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('part')) ? 'd-block':'';?>">
                        <?= form_error('part') ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Lifetime Part</label>
                    <input type="text" name="lifetime" class="form-control <?= form_error('lifetime') ? 'invalid' : '' ?>" placeholder="Masukkan Lifetime / Hari" value="<?= set_value('lifetime'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('lifetime')) ? 'd-block':'';?>">
                        <?= form_error('lifetime') ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Harga Sparepart</label>
                    <input type="text" name="harga" class="form-control <?= form_error('harga') ? 'invalid' : '' ?>" placeholder="Masukkan Harga Part" value="<?= set_value('harga'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('harga')) ? 'd-block':'';?>">
                        <?= form_error('harga') ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Kondisi</label>
                    <input type="text" name="kondisi" class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" placeholder="Baru ?" value="<?= set_value('kondisi'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block':'';?>">
                        <?= form_error('kondisi') ?>
                    </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-sm-12">
                      <button type="submit" class="btn btn-md btn-success mr-2">
                          <i class="fa fa-save"></i> Simpan
                      </button>
                      <a href="<?= base_url('part') ?>" class="btn btn-md btn-danger">
                          <i class="fa fa-times"></i> Batal
                      </a>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('select[name="area"]').change(function() {
      var area_uuid = $(this).val();

      $.get('<?= base_url('part/get_mesin_by_area/');?>' + area_uuid, function(res) {
        var result = JSON.parse(res);
        var elem = '<option disabled selected>Pilih Mesin</option>';
        result.forEach(function(val) {
            elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
        })
        $('select[name="mesin"]').html(elem);
        $('select[name="mesin"]').change(function() {
            var mesin_uuid = $(this).val();
            $.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid,function(res) {
                var data = JSON.parse(res);
                $('input[name="mesin_name"]').val(data.nama_mesin);
            })
        })
      })
    })
})
</script>