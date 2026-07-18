<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Check List Pengecekan Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Pengecekan Mesin Awal Produksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Check List</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('cekmesin/checklist/'. $data->uuid); ?>" method="post">
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
                        <label class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                            <option disabled selected>Pilih mesin</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">
                            Kegiatan <span class="text-danger">*</span>
                        </label>

                        <small class="form-text text-muted mb-2">
                            Jika kegiatan tidak dicek, wajib mengisi keterangan.
                        </small>

                        <div class="card border-left-info shadow-sm">
                            <div class="card-body py-3">
                                <div id="kegiatan-list">
                                    <div class="text-muted small">
                                        Pilih area dan mesin untuk menampilkan daftar kegiatan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('cekmesin') ?>" class="btn btn-md btn-danger">
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

        $.get('<?= base_url('am/get_mesin_by_area/'); ?>' + area_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';

            result.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
            })

            $('select[name="mesin"]').html(elem);
        })
    })
       $('select[name="mesin"]').change(function() {
        var mesin_uuid = $(this).val();
        $.get('<?= base_url('cekmesin/get_kegiatan_by_mesin/'); ?>' + mesin_uuid, function(res) {
            var data = JSON.parse(res);
            var elem = '';
            data.forEach(function(val) {
                elem += '<div class="form-check">';
                elem += '<input class="form-check-input kegiatan-checkbox" type="checkbox" name="kegiatan[' + val.uuid + ']" value="2" id="kegiatan_' + val.uuid + '">';
                elem += '<label class="form-check-label" for="kegiatan_' + val.uuid + '">' + val.kegiatan + '</label>';
                elem += '<div class="keterangan-container">';
                elem += '<input type="text" class="form-control mt-2" name="keterangan[' + val.uuid + ']" placeholder="Keterangan">';
                elem += '</div>';
                elem += '</div>';
            });
            $('#kegiatan-list').html(elem);
        });
    });
       $('#kegiatan-list').on('change', '.kegiatan-checkbox', function() {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            $(this).closest('.form-check').find('.keterangan-container').hide();
        } else {
            $(this).closest('.form-check').find('.keterangan-container').show();
        }
    });
   });
</script>
