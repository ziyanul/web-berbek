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
            <form class="user" action="<?= base_url('sanitasi/editcek/'.$data['cheklist_sanitasi']->uuid); ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <input class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" value="<?= $data['cheklist_sanitasi']->area ?>" readonly>
                            
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Waktu Pengecekan Kondisi <span class="text-danger">*</span></label>
                        <input class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="jam" value="<?= $data['cheklist_sanitasi']->waktu_kondisi ?>" readonly>
                            
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                        <select class="form-control mt-2" name="kondisi">
                                <option value="0" <?= ($data['cheklist_sanitasi']->kondisi == 0)? 'selected' : '' ?>>Ok Bersih</option>
                                <option value="1" <?= ($data['cheklist_sanitasi']->kondisi == 1)? 'selected' : '' ?>>Basah</option>
                                <option value="2" <?= ($data['cheklist_sanitasi']->kondisi == 2)? 'selected' : '' ?>>Berdebu</option>
                                <option value="3" <?= ($data['cheklist_sanitasi']->kondisi == 3)? 'selected' : '' ?>>Kerak</option>
                                <option value="4" <?= ($data['cheklist_sanitasi']->kondisi == 4)? 'selected' : '' ?>>Noda</option>
                                <option value="5" <?= ($data['cheklist_sanitasi']->kondisi == 5)? 'selected' : '' ?>>Karat</option>
                                <option value="6" <?= ($data['cheklist_sanitasi']->kondisi == 6)? 'selected' : '' ?>>Sampah</option>
                                <option value="7" <?= ($data['cheklist_sanitasi']->kondisi == 7)? 'selected' : '' ?>>Retak/Pecah</option>
                                <option value="8" <?= ($data['cheklist_sanitasi']->kondisi == 8)? 'selected' : '' ?>>Sisa Produk</option>
                                <option value="9" <?= ($data['cheklist_sanitasi']->kondisi == 9)? 'selected' : '' ?>>Sisa Adonan</option>
                                <option value="10" <?= ($data['cheklist_sanitasi']->kondisi == 10) ? 'selected' : '' ?>>Berjamur</option>
                                <option value="11" <?= ($data['cheklist_sanitasi']->kondisi == 11) ? 'selected' : '' ?>>Lain-lain</option>
                                
                            </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('sanitasi/detail/'. $data['cheklist_sanitasi']->area_uuid .'/'. $data['cheklist_sanitasi']->tanggal) ?>" class="btn btn-md btn-danger">
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
                });
                
                $('select[name="lokasi"]').html(elem);
            });
        });

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
                    elem += '<option value="0" selected>Ok Bersih</option>';
                    elem += '<option value="1">Basah</option>';
                    elem += '<option value="2">Berdebu</option>';
                    elem += '<option value="3">Kerak</option>';
                    elem += '<option value="4">Noda</option>';
                    elem += '<option value="5">Karat</option>';
                    elem += '<option value="6">Sampah</option>';
                    elem += '<option value="7">Retak/Pecah</option>';
                    elem += '<option value="8">Sisa Produk</option>';
                    elem += '<option value="9">Sisa Adonan</option>';
                    elem += '<option value="10">Berjamur</option>';
                    elem += '<option value="11">Lain-lain</option>';
                    elem += '</select>';
                    elem += '</div>';
                    elem += '</div>';
                });
                $('#kegiatan-list').html(elem);
            });
        }); 
    });
</script>