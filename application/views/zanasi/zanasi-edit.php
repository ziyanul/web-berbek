<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Data Printing DOD</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('zanasi') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Printing DOD
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('zanasi/edit/'.$data->uuid) ?>" method="post">

                <!-- Rutin / Tambahan -->
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Tipe : <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('rutin') ? 'is-invalid' : '' ?>" name="rutin" id="rutin">
                            <option value="">- - Pilih Tipe - -</option>
                            <option value="1" <?= set_select('rutin', '1', $data->rutin == 1); ?>>Rutin</option>
                            <option value="2" <?= set_select('rutin', '2', $data->rutin == 2); ?>>Tambahan</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('rutin')) ? 'd-block' : ''; ?>">
                            <?= form_error('rutin') ?>
                        </div>
                    </div>
                </div>

                <!-- Varian -->
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Varian <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('varian') ? 'is-invalid' : '' ?>" name="varian" id="varian">
                            <option value="">Pilih Varian</option>
                            <?php foreach ($varian as $v): ?>
                                <option value="<?= $v->uuid; ?>" <?= set_select('varian', $v->uuid, $data->varian == $v->uuid); ?>>
                                    <?= $v->varian; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block' : ''; ?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>

                <!-- Kode Produksi -->
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode Produksi <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'is-invalid' : '' ?>" placeholder="Isi Kode Lengkap" value="<?= set_value('kode', $data->kode); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block' : ''; ?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>

                <!-- Kode EXP -->
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode EXP <span class="text-danger">*</span></label>
                        <input type="text" name="exp" class="form-control <?= form_error('exp') ? 'is-invalid' : '' ?>" placeholder="BB tgl bln th" value="<?= set_value('exp', $data->exp); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('exp')) ? 'd-block' : ''; ?>">
                            <?= form_error('exp') ?>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Permintaan -->
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Jumlah Permintaan <span class="text-danger">*</span></label>
                        <input type="number" name="permintaan" id="permintaan" class="form-control <?= form_error('permintaan') ? 'is-invalid' : '' ?>" placeholder="0" value="<?= set_value('permintaan', $data->permintaan); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('permintaan')) ? 'd-block' : ''; ?>">
                            <?= form_error('permintaan') ?>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="row" id="catatanRow" style="display:none;">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label" id="catatanLabel">Catatan <span class="text-danger">*</span></label>
                        <input type="text" name="catatan" id="catatanInput" class="form-control <?= form_error('catatan') ? 'is-invalid' : '' ?>" value="<?= set_value('catatan', $data->catatan); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block' : ''; ?>">
                            <?= form_error('catatan') ?>
                        </div>
                        <small class="text-muted">Catatan wajib jika permintaan lebih kecil dari total print.</small>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('zanasi') ?>" class="btn btn-md btn-danger">
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
        var totalPrint = <?= (int) ($total->totalPrint ?? 0); ?>;

        function toggleCatatan() {
            var permintaan = parseInt($('#permintaan').val()) || 0;

            if (permintaan < totalPrint) {
                $('#catatanRow').show();
                $('#catatanInput').attr('required', true);
            } else {
                $('#catatanRow').hide();
                $('#catatanInput').removeAttr('required');
            }
        }

        // jalankan saat load
        toggleCatatan();

        // jalankan saat permintaan diubah
        $('#permintaan').on('input keyup change', function () {
            toggleCatatan();
        });
    });
</script>