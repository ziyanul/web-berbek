<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Kode Benda Tajam</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('Pbtajam/kodebtajam') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Kode Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Benda</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pbtajam/tambah_kode') ?>" method="post">

            <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Area:<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area">
                            <option selected disabled>Pilih Area</option>
                            <?php foreach ($area as $a): ?>
                            <option value="<?= $a->uuid ?>" <?= set_select('area', $a->uuid);?>>
                                <?= $a->nama_area ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                            <?= form_error('area') ?>
                        </div>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Jenis Benda:<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('jenis') ? 'invalid' : '' ?>" name="jenis">
                            <option selected disabled>Pilih Jenis Benda</option>
                            
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('jenis')) ? 'd-block':'';?>">
                            <?= form_error('jenis') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode Benda:<span class="text-danger">*</span></label>
                        <input type="text" name="kode"
                            class="form-control <?= form_error('kode') ? 'invalid' : '' ?>"
                            value="<?= set_value('kode'); ?>" placeholder="">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mt-4">
                            <i class="fa fa-save text mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Pbtajam/kodebtajam') ?>" class="btn btn-md btn-danger mt-4">
                            <i class="fa fa-times mr-1"></i> Batal
                            </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Event saat area berubah
        $('select[name="area"]').change(function () {
            var area_uuid = $(this).val();

            $.get('<?= base_url('pbtajam/get_by_jenis/'); ?>' + area_uuid, function (res) {
                var result = JSON.parse(res);
                var elem = '<option disabled selected>Pilih Jenis Benda</option>';

                result.forEach(function (val) {
                    elem += '<option value="' + val.uuid + '">' + val.jenis_benda + '</option>';
                });

                $('select[name="jenis"]').html(elem);
            });
        });
});
</script>