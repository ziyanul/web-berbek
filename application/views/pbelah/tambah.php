<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Check List Pengecekan Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pbelah') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Pengecekan Barang Pecah Belah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Check List</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pbelah/tambah/'); ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area" required>
                            <option disabled selected>Pilih Area</option>
                            <?php foreach ($area as $a) { ?>
                                <option value="<?= $a->uuid; ?>" <?= set_select('area', $a->uuid); ?>>
                                    <?= $a->nama_area; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Sub Area <span class="text-danger">*</span></label>
                        <select class="form-control" name="sub_area" id="sub-area" required>
                            <option disabled selected>Pilih Sub Area</option>
                            <!-- Sub area akan dimuat di sini -->
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label class="form-label">Pengecekan Berdasarkan Kode Barang:</label>
                        <div id="kode-list">
                            <!-- Daftar kode barang akan dimuat di sini -->
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pbelah/') ?>" class="btn btn-md btn-danger">
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
        // Muat Sub Area Berdasarkan Area Terpilih
        $('#area').change(function() {
            var area_uuid = $(this).val();
            $.get('<?= base_url('pbelah/get_lokasi_by_area/'); ?>' + area_uuid, function(res) {
                var data = JSON.parse(res);
                var options = '<option disabled selected>Pilih Sub Area</option>';

                data.forEach(function(subArea) {
                    options += `<option value="${subArea.uuid}">${subArea.lokasi}</option>`;
                });

                $('#sub-area').html(options);
            });
        });

        // Muat Kode Barang Berdasarkan Sub Area Terpilih
        $('#sub-area').change(function() {
            var sub_area_uuid = $(this).val();

            $.get('<?= base_url('pbelah/get_kode_by_sub_area/'); ?>' + sub_area_uuid, function(res) {
                var data = JSON.parse(res);
                var elem = `
                <table class="table table-bordered">
                    <thead class="bg-info text-light">
                        <tr>
                            <th class="text-center align-middle">Kode Barang</th>
                            <th colspan="2" class="text-center">Kondisi Baik</th>
                        </tr>
                            
                        
                    </thead>
                    <tbody>`;

                data.forEach(function(val) {
                    elem += `
                    <tr>
                        <td>${val.kode_barang}</td>
                        <td class="text-center">
                        <input class="form-check-input kode-checkbox" type="checkbox" name="kode[${val.uuid}]" value="1" id="kode_${val.uuid}">
                        </td>
                        
                    </tr>`;
                });

                elem += `</tbody></table>`;
                $('#kode-list').html(elem);
            });
        });
    });
</script>