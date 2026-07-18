<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Chek List Kebersihan Sanitasi </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('sanitasi') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Pengecekan Kebersihan Sanitasi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Check List</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('sanitasi/tambahchek/'); ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php
                            foreach ($area as $a) {
                                ?>
                                <option value="<?= $a->uuid;?>" <?= set_select('area', $a->uuid);?>><?= $a->area;?></option>
                                <?php
                            }
                            ?>
                        </select>  
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Waktu Cek <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="06:00" id="waktu_06:00">
                                    <label class="form-check-label" for="waktu_06:00">06.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="09:00" id="waktu_09:00">
                                    <label class="form-check-label" for="waktu_09:00">09.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="12:00" id="waktu_12:00">
                                    <label class="form-check-label" for="waktu_12:00">12.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="15:00" id="waktu_15:00">
                                    <label class="form-check-label" for="waktu_15:00">15.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="18:00" id="waktu_18:00">
                                    <label class="form-check-label" for="waktu_18:00">18.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="21:00" id="waktu_21:00">
                                    <label class="form-check-label" for="waktu_21:00">21.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="00:00" id="waktu_00:00">
                                    <label class="form-check-label" for="waktu_00:00">00.00</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="waktu" value="03:00" id="waktu_03:00">
                                    <label class="form-check-label" for="waktu_03:00">03.00</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <select class="form-control <?= form_error('lokasi') ? 'invalid' : '' ?>" name="lokasi">
                            <option disabled selected>Pilih cek kebersihan</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <div id="kegiatan-list">
                            <!-- Daftar kegiatan akan dimuat di sini -->
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('sanitasi') ?>" class="btn btn-md btn-danger">
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
        // Load Lokasi Berdasarkan Area
        $('select[name="area"]').change(function() {
            var area_uuid = $(this).val();
            
            $.get('<?= base_url('gmp/get_lokasi_by_area/'); ?>' + area_uuid, function(res) {
                var result = JSON.parse(res);
                var elem = '<option disabled selected>Pilih Lokasi</option>';
                
                result.forEach(function(val) {
                    elem += '<option value="' + val.uuid + '">' + val.lokasi + '</option>';
                });
                
                $('select[name="lokasi"]').html(elem);
            });
        });

        // Load Kegiatan Berdasarkan Lokasi
        $('select[name="lokasi"]').change(function() {
    var lokasi_uuid = $(this).val();
    $.get('<?= base_url('gmp/get_kegiatan_by_lokasi/'); ?>' + lokasi_uuid, function(res) {
        var data = JSON.parse(res);
        var elem = '';

        data.forEach(function(val) {
            elem += '<div class="form-check">';
            elem += '<label class="form-check-label mt-2" for="kegiatan_' + val.uuid + '">' + val.kegiatan + '</label>';
            elem += '<div class="keterangan-container">';
            elem += '<select class="form-control mt-2" name="keterangan[' + val.uuid + ']">';

            // Perform AJAX request inside the loop
            $.ajax({
                url: '<?= base_url('sanitasi/get_kondisi_data/'); ?>',
                method: 'GET',
                async: false, // Ensure the request is synchronous
                success: function(res) {
                    var kondisi = JSON.parse(res);
                    kondisi.forEach(function(kon) {
                        elem += '<option value="' + kon.uuid + '">' + kon.kondisi + '</option>';
                    });
                }
            });

            elem += '</select>';
            elem += '</div>';
            elem += '</div>';
        });

        $('#kegiatan-list').html(elem);
    });
});

    });
</script>