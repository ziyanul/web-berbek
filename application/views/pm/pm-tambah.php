<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Form Pengajuan Maintenance Mesin </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tpm'?'pm/tpm':'pm') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Preventive Maintenance</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
    </ol></nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pm/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'tambah/') ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php
                            foreach ($area as $row) {
                                ?>
                                <option value="<?= $row->uuid;?>" <?= set_select('area', $row->uuid);?>><?= $row->nama_area;?></option>
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
                        <label class="form-label font-weight-bold">Nama Mesin <span class="text-danger">*</span></label>
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
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label mt-2 font-weight-bold">Keluhan <span class="text-danger">*</span></label>
                        <input type="text" name="keluhan" class="form-control <?= form_error('keluhan') ? 'invalid' : '' ?> " placeholder="Apa yang terjadi pada mesin??" value="<?= set_value('keluhan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keluhan')) ? 'd-block':'';?>">
                            <?= form_error('keluhan') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                     <label class="form-label mr-3 font-weight-bold">Dokumentasi <span class="text-danger">*</span></label>
                     <input type="file" class="form-control <?= form_error('dokumentasi_before') ? 'invalid' : '' ?>" name="dokumentasi_before" placeholder="" value="">
                     <div class="invalid-feedback <?= !empty(form_error('dokumentasi_before')) ? 'd-block':'';?>">
                        <?= form_error('dokumentasi_before') ?>
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url($this->uri->segment(2)=='tpm'?'pm/tpm':'pm') ?>" class="btn btn-md btn-danger">
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
        $('form').submit(function(e){
            var file = $('input[name="dokumentasi_before"]').val();
            if(file == ''){
                alert('Sertakan Dokumentasi Foto!');
                e.preventDefault();
            }
        });
    })
</script>