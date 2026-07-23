<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Checklist Pengecekan Mesin Awal Proses</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checklist</li>
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
            <form class="user" action="<?= base_url('cekmesin_filler/checklist_awalproses/'. $data->uuid); ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="area_name" value="Filler" readonly>
                        <input type="hidden" name="area_uuid" value="<?php 
                            foreach ($area as $a) {
                                if ($a->uuid === $this->filler) { 
                                    echo $a->uuid; 
                                }
                            }
                        ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <div id="kegiatan-list" name="kegiatan">
                            <!-- Daftar kegiatan akan dimuat di sini -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?php if (!empty(validation_errors())): ?>
                        <div class="text-danger" role="alert">
                            <?= validation_errors() ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-5">
                        <label class="form-label"> <b>NOTE :</b><br>
                            ● Jika Kegiatan Ya maka centang (✓).<br>
                            ● Jika Kegiatan Tidak maka isi keterangan dan tidak perlu centang.
                        </label><br><br>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('cekmesin_filler') ?>" class="btn btn-md btn-danger">
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
    var area_uuid = $('input[name="area_uuid"]').val();
    var t_planning_uuid = '<?= $data->uuid ?>';

    // Ambil data mesin dari server
    $.get('<?= base_url('cekmesin_filler/get_mesin_by_area/'); ?>' + area_uuid + '/' + t_planning_uuid,
        function(res) {
            console.log('Respons dari server:', res); // Debug respons
            try {
                var result = JSON.parse(res); // Parsing JSON
                var elem = '<option disabled selected>Pilih Mesin</option>'; // Opsi default

                // Loop untuk setiap mesin
                result.forEach(function(val) {
                    if (parseInt(val.is_used) > 0) { // Jika mesin sudah digunakan
                        elem += '<option value="' + val.uuid + '" disabled>' + val.nama_mesin +
                            ' ⚠️ (Sudah di Input)</option>';
                    } else { // Jika mesin belum digunakan
                        elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
                    }
                });

                // Render opsi ke dropdown
                $('select[name="mesin"]').html(elem);
            } catch (error) {
                console.error('Error parsing JSON:', error);
                alert('Terjadi kesalahan pada data mesin.');
            }
        }).fail(function() {
        alert('Terjadi kesalahan saat memuat data mesin.');
    });
});
$('select[name="mesin"]').change(function() {
    var mesin_uuid = $(this).val();
    $.get('<?= base_url('cekmesin_filler/get_kegiatan_by_mesin/'); ?>' + mesin_uuid, function(res) {
        var data = JSON.parse(res);
        var elem = '';
        data.forEach(function(val) {
            elem += '<div class="form-check">';
            elem +=
                '<input class="form-check-input kegiatan-checkbox" type="checkbox" name="kegiatan[' +
                val.uuid + ']" value="2" id="kegiatan_' + val.uuid + '">';
            elem += '<label class="form-check-label" for="kegiatan_' + val.uuid +
                '">' + val.kegiatan + '</label>';
            elem += '<div class="keterangan-container">';
            elem +=
                '<input type="text" class="form-control mt-2" name="keterangan[' +
                val.uuid + ']" placeholder="Keterangan">';
            elem += '</div>';
            elem += '</div>';
        });
        $('#kegiatan-list').html(elem);
    });
});
$('#kegiatan-list').on('change', '.kegiatan-checkbox', function() {
    var isChecked = $(this).is(':checked');
    var keteranganInput = $(this).closest('.form-check').find('.keterangan-container input');

    if (isChecked) {
        // Jika dicentang, sembunyikan keterangan dan kosongkan nilai
        keteranganInput.val('');
        keteranganInput.closest('.keterangan-container').hide();
    } else {
        // Jika tidak dicentang, tampilkan keterangan
        keteranganInput.closest('.keterangan-container').show();
    }
});

$('form').on('submit', function(e) {
    var isValid = true;

    $('#kegiatan-list .form-check').each(function() {
        var isChecked = $(this).find('.kegiatan-checkbox').is(':checked');
        var keteranganInput = $(this).find('.keterangan-container input');

        if (!isChecked && keteranganInput.val().trim() === '') {
            isValid = false;
            keteranganInput.addClass('is-invalid');
        } else {
            keteranganInput.removeClass('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Keterangan wajib di isi untuk Kegiatan yang tidak dipilih.');
    }
});
</script>