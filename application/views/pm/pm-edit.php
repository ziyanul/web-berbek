<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">UBAH PENGAJUAN PM</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'pm/tpm' : 'pm') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Data Preventive Maintenance
                </a>
            </li>
            <li class="breadcrumb-item active">
                Ubah
            </li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form 
                class="user"
                action="<?= base_url('pm/' . ($this->uri->segment(2) == 'tpm' ? 'tpm/' : '') . 'edit/' . $data->maintenance_uuid) ?>"
                method="post"
                enctype="multipart/form-data"
            >

                <!-- AREA -->
                <div class="form-group">
                    <label class="font-weight-bold">
                        Nama Area
                    </label>

                    <div class="form-control bg-light">
                        <?= $data->nama_area; ?>
                    </div>
                </div>

                <!-- MESIN -->
                <div class="form-group">
                    <label class="font-weight-bold">
                        Nama Mesin
                    </label>

                    <select class="form-control" name="mesin">
                        <option disabled>Pilih Mesin</option>

                        <?php foreach ($mesin as $row) : ?>

                            <option 
                                value="<?= $row->uuid; ?>"
                                <?= $data->mesin_uuid == $row->uuid ? 'selected' : ''; ?>
                            >
                                <?= $row->nama_mesin; ?>
                            </option>

                        <?php endforeach; ?>
                    </select>

                    <input 
                        type="hidden"
                        name="mesin_name"
                        value="<?= $data->nama_mesin; ?>"
                    >
                </div>

                <!-- KELUHAN -->
                <div class="form-group">
                    <label class="font-weight-bold">
                        Keluhan
                    </label>

                    <input 
                        type="text"
                        name="keluhan"
                        class="form-control"
                        placeholder="Apa keluhannya?"
                        value="<?= $data->keluhan; ?>"
                    >
                </div>

                <!-- FOTO LAMA -->
                <div class="form-group">
                    <label class="font-weight-bold d-block">
                        Dokumentasi Lama
                    </label>

                    <?php if (!empty($data->dokumentasi)) : ?>

                        <img 
                            src="<?= base_url('upload/' . $data->dokumentasi); ?>"
                            alt="Dokumentasi"
                            class="img-thumbnail mb-3"
                            style="max-width: 300px;"
                        >

                    <?php else : ?>

                        <div class="text-muted mb-3">
                            Tidak ada dokumentasi
                        </div>

                    <?php endif; ?>
                </div>

                <!-- GANTI FOTO -->
                <div class="form-group">
                    <label class="font-weight-bold">
                        Ganti Foto (Opsional)
                    </label>

                    <input 
                        type="file"
                        name="dokumentasi_before"
                        id="dok_af"
                        class="form-control <?= form_error('dokumentasi_before') ? 'is-invalid' : ''; ?>"
                    >

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti foto.
                    </small>

                    <div class="invalid-feedback <?= !empty(form_error('dokumentasi_before')) ? 'd-block' : ''; ?>">
                        <?= form_error('dokumentasi_before'); ?>
                    </div>

                    <input 
                        type="hidden"
                        name="old_dokumentasi_before"
                        value="<?= $data->dokumentasi; ?>"
                    >
                </div>

                <!-- BUTTON -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save mr-1"></i>
                        Simpan
                    </button>

                    <a 
                        href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'pm/tpm' : 'pm') ?>"
                        class="btn btn-danger"
                    >
                        <i class="fa fa-times mr-1"></i>
                        Batal
                    </a>
                </div>

            </form>

    </div>
</div>

<script>
$(document).ready(function () {

    $('select[name="mesin"]').change(function () {

        var val = $(this).val();

        $.get('<?= base_url('mesin/get_mesin_name/'); ?>' + val, function (res) {

            var data = JSON.parse(res);

            $('input[name="mesin_name"]').val(data.nama_mesin);

        });

    });

});
</script>