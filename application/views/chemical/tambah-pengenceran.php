<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Pengenceran Chemical</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('chemical') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Chemical</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('chemical/larutan/') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label">Kode Pengenceran Chemical :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('kode_chemical') ? 'is-invalid' : '' ?>" name="kode_chemical">
                            <option disabled selected>Pilih Kode Chemical</option>
                            <?php foreach ($kode as $k): ?>
                                <option value="<?= $k->uuid;?>" <?= set_select('kode_chemical', $k->uuid);?>><?= $k->kode_chemical ?> <?= $k->chemical_name ?> <?= $k->persentase ?> <?= $k->stn ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= form_error('kode_pengenceran') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Tambah Chemical Murni<span class="text-danger">*</span><span class="persentase" hidden></span><span class="satuan" hidden></span></label>
                        <input type="number" name="chemical_stock" id="chemical_used" class="form-control <?= form_error('chemical_stock') ? 'is-invalid' : '' ?>" placeholder="Chemical yang Dipakai" value="<?= set_value('chemical_stock'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('chemical_stock') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Tambah Air :</label>
                        <input type="number" name="air" id="air_used" class="form-control <?= form_error('air') ? 'is-invalid' : '' ?>" placeholder="air" value="" readonly>
                        <div class="invalid-feedback">
                            <?= form_error('air') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Hasil Larutan :</label>
                        <input type="number" name="larutan" id="total_larutan" class="form-control <?= form_error('larutan') ? 'is-invalid' : '' ?>" placeholder="larutan" value="" readonly>
                        <div class="invalid-feedback">
                            <?= form_error('larutan') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('chemical') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var inputChemicalUsed = document.getElementById('chemical_used');
    var inputJumlahLarutan = document.getElementById('total_larutan');
    var inputJumlahAir = document.getElementById('air_used');
    inputChemicalUsed.addEventListener('input', function() {
        var chemicalUsed = parseFloat(inputChemicalUsed.value);
        var perbandingan = parseFloat(document.querySelector('.persentase').innerText); 
        var satuan = document.querySelector('.satuan').innerText.trim();
        if (!isNaN(chemicalUsed) && !isNaN(perbandingan)) {
            var larutanValue;
            if (satuan === '%') {
                larutanValue = chemicalUsed / (perbandingan / 100);
            } else if (satuan === 'Ppm') {
                larutanValue = chemicalUsed / (perbandingan / 100000);
            }
            var airValue = larutanValue - chemicalUsed;
            inputJumlahLarutan.value = larutanValue;
            inputJumlahAir.value = airValue;
        }
    });
</script>

<script>
$(document).ready(function () {
    $('select[name="kode_chemical"]').change(function () {
        var val = $(this).val();
        $.get('<?= base_url('chemical/get_persentase_name/');?>' + val, function (res) {
            var persen = JSON.parse(res);
            $('.persentase').text(persen.persentase);
            $('.satuan').text(persen.satu);
        });
    });
});
</script>