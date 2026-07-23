<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Item Pengecekan Mesin Filler</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Item</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('cekmesin_fillerbatch/tambahitem') ?>" method="post">

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                            <option disabled selected>Pilih mesin</option>
                            <?php foreach ($mesin as $row): ?>
                                 <option value="<?= $row->uuid; ?>">
                                    <?= $row->nama_mesin; ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <input type="hidden" name="mesin_name">
                        <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                            <?= form_error('mesin') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="item" class="form-control <?= form_error('item') ? 'invalid' : '' ?>" placeholder="Masukkan apa yang harus dikerjakan" value="<?= set_value('item'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('item')) ? 'd-block':'';?>">
                            <?= form_error('item') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>" class="btn btn-md btn-danger">
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

          $.get('<?= base_url('mesin/get_mesin_by_area/');?>' + area_uuid, function(res) {
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

