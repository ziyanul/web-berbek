<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Item Pengecekan Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin/dataitem') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Item</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
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
            <form class="user" action="<?= base_url('cekmesin/edititem/'. $data->uuid) ?>" method="post">
    <div class="row">
        <div class="col-sm-6 mb-4">
            <label class="form-label">Nama Area <span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area">
                <option disabled selected>Pilih Area</option>
                <?php
                foreach ($area as $row) {
                    ?>
                    <option value="<?= $row->uuid;?>" <?= $row->uuid == $data->area_uuid ? 'selected' : '' ;?>><?= $row->nama_area;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 mb-4">
            <label class="form-label">Mesin <span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin" id="mesin">
                <option disabled selected>Pilih mesin</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
            <input type="text" name="kegiatan" class="form-control <?= form_error('kegiatan') ? 'invalid' : '' ?>" placeholder="Masukkan apa yang harus dikerjakan" value="<?= $data->kegiatan ?>">
            <div class="invalid-feedback <?= !empty(form_error('kegiatan')) ? 'd-block':'';?>">
                <?= form_error('kegiatan') ?>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-md btn-success mr-2">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('cekmesin/dataitem') ?>" class="btn btn-md btn-danger">
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
    var selectedArea = $('#area').val();
    if (selectedArea) {
        loadMesin(selectedArea, '<?= $data->mesin_uuid ?>');
    }

    $('#area').change(function() {
        var area_uuid = $(this).val();
        loadMesin(area_uuid);
    });

    function loadMesin(area_uuid, selectedMesin = null) {
        $.get('<?= base_url('am/get_mesin_by_area/');?>' + area_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';
            result.forEach(function(val) {
                var selected = val.uuid == selectedMesin ? 'selected' : '';
                elem += '<option value="' + val.uuid + '" ' + selected + '>' + val.nama_mesin + '</option>';
            });
            $('#mesin').html(elem);
        });
    }
});
</script>
