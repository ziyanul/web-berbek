<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Export ke Excel</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Dropdown Area -->
                <div class="form-group col-sm-4 mb-3">
                    <select name="area" id="area" class="form-control">
                        <option value="">Pilih Area</option>
                        <?php foreach ($data as $are): ?>
                            <option value="<?= $are->uuid; ?>"><?= $are->nama_area; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Dropdown Mesin -->
                <div class="form-group col-sm-4 mb-3">
                    <select name="mesin" id="mesin" class="form-control">
                        <option value="">Pilih Mesin</option>
                    </select>
                </div>
                <!-- Dropdown Part -->
                <div class="form-group col-sm-4 mb-3">
                    <select name="part" id="part" class="form-control">
                        <option value="">Pilih Part</option>
                    </select>
                </div>
            </div>
            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered" id="monitorTable" width="100%">
                    <thead class="bg-info text-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Mesin</th>
                            <th>Nama Part</th>
                            <th>Lifetime</th>
                            <th>Waktu Penggunaan</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Ganti</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="text-left mt-3">
                    <a href="#" id="downloadExcel" class="btn btn-success">
                        <i class="fa fa-download"></i> Download Excel
                    </a>
                <a href="<?= base_url('monitor/history') ?>" class="btn btn-danger">
                    <i class="fa fa-times"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Fungsi untuk mengosongkan tabel
        function resetTable(message = "Silakan pilih Mesin dan Part terlebih dahulu") {
            $('#monitorTable tbody').html('<tr><td colspan="7" class="text-center">' + message + '</td></tr>');
        }
        // Fungsi untuk memperbarui link tombol download
        function updateDownloadLink() {
            var mesin_uuid = $('select[name="mesin"]').val();
            var part_uuid = $('select[name="part"]').val();
            var baseUrl = '<?= base_url('monitor/download_excel'); ?>';
            var downloadUrl = baseUrl + '?mesin_uuid=' + mesin_uuid + '&part_uuid=' + (part_uuid || '');
            $('#downloadExcel').attr('href', downloadUrl);
        }
        // Fungsi untuk menampilkan data di tabel
        function populateTable(data) {
            var rows = '';
            if (data.length > 0) {
                data.forEach(function(row, index) {
                    rows += '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + row.nama_mesin + '</td>' +
                    '<td>' + row.nama_part + '</td>' +
                    '<td>' + row.lifetime + '</td>' +
                    '<td>' + row.rh_end + '</td>' +
                    '<td>' + row.tanggal1 + '</td>' +
                    '<td>' + row.tanggal2 + '</td>' +
                    '</tr>';
                });
            } else {
                rows = '<tr><td colspan="7" class="text-center">Data tidak ditemukan</td></tr>';
            }
            $('#monitorTable tbody').html(rows);
        }

        // Event ketika area dipilih
        $('select[name="area"]').change(function() {
            var area_uuid = $(this).val();

            // Kosongkan tabel, mesin, dan part
            resetTable();
            $('select[name="mesin"]').html('<option disabled selected>Pilih Mesin</option>');
            $('select[name="part"]').html('<option disabled selected>Pilih Part</option>');
            $('#downloadExcel').attr('href', '#'); // Reset tombol download

            // Ambil data mesin berdasarkan area
            if (area_uuid) {
                $.get('<?= base_url('mesin/get_mesin_by_area/'); ?>' + area_uuid, function(res) {
                    var result = JSON.parse(res);
                    var mesinOptions = '<option disabled selected>Pilih Mesin</option>';
                    result.forEach(function(val) {
                        mesinOptions += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
                    });
                    $('select[name="mesin"]').html(mesinOptions);
                });
            }
        });
        // Event ketika mesin dipilih
        $('select[name="mesin"]').change(function() {
            var mesin_uuid = $(this).val();
            // Update link download
            updateDownloadLink();
            // Kosongkan dropdown part dan reset tabel
            $('select[name="part"]').html('<option disabled selected>Pilih Part</option>');
            resetTable();
            // Ambil data part berdasarkan mesin
            $.get('<?= base_url('part/get_part_by_mesin/'); ?>' + mesin_uuid, function(res) {
                var result = JSON.parse(res);
                var partOptions = '<option disabled selected>Pilih Part</option>';
                result.forEach(function(val) {
                    partOptions += '<option value="' + val.uuid + '">' + val.nama_part + '</option>';
                });
                $('select[name="part"]').html(partOptions);
            });
            // Ambil data tabel tanpa part
            $.get('<?= base_url('monitor/get_data_by_mesin_part'); ?>', { mesin_uuid: mesin_uuid }, function(res) {
                var data = JSON.parse(res);
                console.log("Data setelah mesin:", data);
                populateTable(data);
            });
        });
        // Event ketika part dipilih
        $('select[name="part"]').change(function() {
            var mesin_uuid = $('select[name="mesin"]').val();
            var part_uuid = $(this).val();
            // Update link download
            updateDownloadLink();
            // Ambil data tabel berdasarkan mesin dan part
            if (mesin_uuid) {
                $.get('<?= base_url('monitor/get_data_by_mesin_part'); ?>', { mesin_uuid: mesin_uuid, part_uuid: part_uuid }, function(res) {
                    var data = JSON.parse(res);
                    console.log("Data setelah part:", data);
                    populateTable(data);
                });
            } else {
                resetTable("Silakan pilih Mesin terlebih dahulu");
            }
        });
    });
</script>
