<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Ubah Jenis Pecah Belah</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pbelah/detailjenis/'.$data->sub_area_uuid) ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Data Jenis Sub Area <?= $data->lokasi ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pbelah/editjenispb/'. $data->uuid) ?>" method="post">

                <div class="row">
                    <div class="col-sm-6 mt-2">
                        <label class="form-label">Jenis Barang Pecah Belah :</label>
                        <input type="text" name="jenis_pb"
                            class="form-control <?= form_error('jenis_pb') ? 'invalid' : '' ?>"
                            value="<?= $data->jenis_barang; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('jenis_pb')) ? 'd-block':'';?>">
                            <?= form_error('jenis_pb') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pbelah/detailjenis/'. $data->sub_area_uuid) ?>"
                            class="btn btn-md btn-danger">
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
    // Event saat area di-select
    $('select[name="area"]').change(function() {
        var area_uuid = $(this).val(); // Ambil UUID Area yang dipilih

        // Panggil AJAX untuk mendapatkan sub-area berdasarkan area_uuid
        $.get('<?= base_url('pbelah/get_lokasi_by_area/'); ?>' + area_uuid, function(res) {
            var result = JSON.parse(res); // Parse JSON response

            var options = '<option disabled selected>Pilih Sub Area</option>'; // Default option

            // Loop hasil dan buat option untuk setiap sub-area
            result.forEach(function(val) {
                // Jika ada data sebelumnya, pilih sub-area yang sesuai
                var selected = (val.uuid === '<?= $data->sub_area_uuid ?? '' ?>') ? 'selected' : '';
                options += '<option value="' + val.uuid + '" ' + selected + '>' + val.lokasi + '</option>';
            });

            // Update isi dropdown sub-area
            $('select[name="sub_area"]').html(options);
        });
    });

    // Trigger change saat halaman pertama kali dimuat jika area sudah dipilih
    if ($('select[name="area"]').val()) {
        $('select[name="area"]').trigger('change');
    }
});
</script>
