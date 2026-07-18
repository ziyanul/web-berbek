<style>
    #chemical-inputs-container {
        display: flex;
        flex-direction: column;
    }

    #chemical-inputs-container .row {
        margin-bottom: 1rem;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Jenis Kegiatan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('gmp/data') ?>"><i class="fas fa-arrow-left mr-2"></i>Data ISO/TS</a></li>
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
            <form class="user" action="<?= base_url('gmp/tambahkegiatan') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php foreach ($area as $row): ?>
                                <option value="<?= $row->uuid;?>" <?= set_select('area', $row->uuid);?>><?= $row->nama_area;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('lokasi') ? 'invalid' : '' ?>" name="lokasi">
                            <option disabled selected>Pilih lokasi</option>
                        </select>
                        <input type="hidden" name="lokasi_name">
                        <div class="invalid-feedback <?= !empty(form_error('lokasi')) ? 'd-block':'';?>">
                            <?= form_error('lokasi') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="kegiatan" class="form-control <?= form_error('kegiatan') ? 'invalid' : '' ?>" placeholder="Masukkan apa yang harus dikerjakan" value="<?= set_value('kegiatan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kegiatan')) ? 'd-block':'';?>">
                            <?= form_error('kegiatan') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <i class="fa fa-plus-circle mt-3" style="color: #07e203; cursor: pointer;" onclick="addChemicalInputs()"></i> Kondisi Yang Menggunakan Chemical
                    </div>
                </div>
                <div id="chemical-inputs-container" class="row mt-3"></div>
                <div class="row mt-3">
                    <div class="col">

                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('gmp/data') ?>" class="btn btn-md btn-danger">
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
            $.get('<?= base_url('gmp/get_lokasi_by_area/');?>' + area_uuid, function(res) {
                var result = JSON.parse(res);
                var elem = '<option disabled selected>Pilih Lokasi</option>';
                result.forEach(function(val) {
                    elem += '<option value="' + val.uuid + '">' + val.lokasi + '</option>';
                });
                $('select[name="lokasi"]').html(elem);
            });
        });
    });
    function addChemicalInputs() {
        var newInputHtml = `
        <div class="row chemical-input-row">
        <div class="col-sm-2">
        <label class="form-label">Kondisi : </label>
        <select class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" name="kondisi[]">
        <option disabled selected>Pilih Kondisi Kebersihan</option>
        <?php foreach ($kondisi as $kon): ?>
            <option value="<?= $kon->uuid;?>" <?= set_select('kondisi', $kon->uuid);?>><?= $kon->kondisi;?></option>
        <?php endforeach; ?>
        </select>
        </div>
        <div class="col-sm-2">
        <label class="form-label">Tindakan : </label>
        <select class="form-control <?= form_error('tindakan') ? 'invalid' : '' ?>" name="tindakan[]">
        <option disabled selected>Pilih tindakan Kebersihan</option>
        <?php foreach ($tindakan as $tin): ?>
            <option value="<?= $tin->uuid;?>" <?= set_select('tindakan', $tin->uuid);?>><?= $tin->tindakan;?></option>
        <?php endforeach; ?>
        </select>
        </div>
        <div class="col-sm-2">
        <label class="form-label">Kode Chemical : </label>
        <select class="form-control <?= form_error('kode') ? 'invalid' : '' ?>" name="kode[]">
        <option disabled selected>Pilih Kode Chemical</option>
        <?php foreach ($persen as $per): ?>
            <option value="<?= $per->uuid;?>" <?= set_select('kode', $per->uuid);?>><?= $per->kode_chemical;?> - <?= $per->persentase;?> <?= $per->satuan;?> - <?= $per->chemical_name;?> </option>
        <?php endforeach; ?>
        </select>
        </div>
        <div class="col-sm-2">
        <label class="form-label">Target Larutan:</label>
        <div class="input-group">
        <input type="number" name="larutan_used[]" class="form-control" placeholder="">
        <button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeChemicalInput(this)">
        <i class="fa fa-trash"></i>
        </button>
        </div>
        </div>
        </div>
        `;
        $('#chemical-inputs-container').append(newInputHtml);
    }

    function removeChemicalInput(button) {
        $(button).closest('.chemical-input-row').remove();
    }
</script>