<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">INPUT DATA KEGIATAN ISO/TS</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('gmp/data') ?>"><i class="fas fa-arrow-left mr-2"></i>Monitoring ISO/TS</a></li>
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
            <form class="user" action="<?= base_url('gmp/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'tambah/') ?>" method="post">
                <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                        <option disabled selected>Pilih Area</option>
                        <?php
                        foreach ($area as $a) {
                            ?>
                            <option value="<?= $a->uuid;?>" <?= set_select('area', $a->uuid);?>><?= $a->nama_area;?></option>
                            <?php
                        }
                        ?>
                    </select>
                    
                </div>
            </div>
             <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('lokasi') ? 'invalid' : '' ?>" name="lokasi">
                        <option disabled selected>Pilih lokasi</option>
                    </select>
                    
                </div>
            </div>
             
             <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('kegiatan') ? 'invalid' : '' ?>" name="kegiatan">
                        <option disabled selected>Pilih Kegiatan</option>
                    </select>
                    <input type="hidden" name="kegiatan_name">
                    <div class="invalid-feedback <?= !empty(form_error('kegiatan')) ? 'd-block':'';?>">
                        <?= form_error('kegiatan') ?>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Estimasi Target Realisasi <span class="text-danger">*</span></label>
                        <input type="text" name="target" class="form-control <?= form_error('target') ? 'invalid' : '' ?>" placeholder="Masukkan kapan harus dikerjakan" value="<?= set_value('target'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('target')) ? 'd-block':'';?>">
                            <?= form_error('target') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url($this->uri->segment(2)=='tpm'?'gmp/tpm':'gmp') ?>" class="btn btn-md btn-danger">
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

        $.get('<?= base_url('gmp/get_lokasi_by_area/'); ?>' + area_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Lokasi</option>';

            result.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val.lokasi + '</option>';
            })

            $('select[name="lokasi"]').html(elem);
        })
    })

    $('select[name="lokasi"]').change(function() {
        var lokasi_uuid = $(this).val();
        $.get('<?= base_url('gmp/get_kegiatan_by_lokasi/'); ?>' + lokasi_uuid, function(res) {
            var data = JSON.parse(res);

            var elem = '<option disabled selected>Pilih Kegiatan</option>';
            
            data.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val.kegiatan + '</option>';
            })

            $('select[name="kegiatan"]').html(elem);
            $('select[name="kegiatan"]').change(function() {
            var kegiatan_uuid = $(this).val();
            $.get('<?= base_url('gmp/get_kegiatan_name/');?>'+kegiatan_uuid,function(res) {
                var data = JSON.parse(res);
            $('input[name="kegiatan_name"]').val(data.kegiatan);
        })
            })
            })
        
    })
})


 
</script>

