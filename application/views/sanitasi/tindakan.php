<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Input Tindakan Kebersihan Sanitasi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('sanitasi/detail/'. $data->area_uuid) ?>"><i class="fas fa-arrow-left mr-2"></i></a>Sanitasi - <a href="<?= base_url('sanitasi/detail/'. $data->area_uuid) ?>">Detail</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('sanitasi/tindakan/'.$data->uuid) ?>" method="post">
                <table class="table">           
                    <tbody>
                        <tr>
                            <td class="border-0" width="200">Area</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0"><?= $data->area; ?></td>
                        </tr>
                        <tr>
                            <td width="200" class="border-0">Item Pemeriksaan</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0"><?= $data->nama_item; ?></td>
                        </tr>
                        <tr>
                            <td width="200" class="border-0">Waktu Pengecekan</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0"><?= $data->waktu_kondisi; ?></td>
                        </tr>
                        <tr>
                            <td width="200" class="border-0">Kondisi</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0"><?= $data->kondisi; ?></td>
                        </tr>

                        <tr>
                            <td width="200" class="border-0">Waktu Pelaksanaan</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div>
                                            <input type="radio" id="jam_06" name="jam" value="06:00">
                                            <label for="jam_06">06:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_12" name="jam" value="12:00">
                                            <label for="jam_12">12:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_18" name="jam" value="18:00">
                                            <label for="jam_18">18:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_24" name="jam" value="24:00">
                                            <label for="jam_24">24:00</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                            <input type="radio" id="jam_09" name="jam" value="09:00">
                                            <label for="jam_09">09:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_15" name="jam" value="15:00">
                                            <label for="jam_15">15:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_21" name="jam" value="21:00">
                                            <label for="jam_21">21:00</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="jam_03" name="jam" value="03:00">
                                            <label for="jam_03">03:00</label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="200" class="border-0">Tindakan</td>
                            <td width="10" class="border-0">:</td>
                            <td class="font-weight-bold border-0">
                                <select name="tindakan" id="tindakan">
                                    <option disabled selected>Pilih Tindakan</option>
                                    <?php foreach ($tindakan as $tin) { ?>
                                        <option value="<?= $tin->uuid ?>"><?= $tin->tindakan ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                    <td width="200" class="border-0">Menggunakan Larutan Chemical</td>
                    <td width="10" class="border-0">:</td>
                    <td class="font-weight-bold border-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" name="flexSwitchCheckChecked">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Ya</label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                    </table>


                    <div class="row mt-3 text-center">
                        <div class="col-md-6">
                            <div id="kondisi-area-content" class="mt-3"></div>
                        </div>
                    </div>
                    <div class="row mt-3 text-center">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('sanitasi/detail/'.$data->area_uuid .'/' . $data->tanggal) ?>" class="btn btn-md btn-danger">
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
    $('select[name="tindakan"]').change(function() {
        var tindakan_uuid = $(this).val();
        var kegiatan_uuid = '<?= $data->kegiatan_uuid ?>';

        $('#flexSwitchCheckChecked').change(function() {
            if (this.checked) {
                $.get('<?= base_url('sanitasi/get_tindakan_kegiatan/'); ?>' + tindakan_uuid + '/' + kegiatan_uuid, function(res) {
                    var result = JSON.parse(res);
                    var content = '<table class="table table-bordered"><thead class="table bg-info text-light"><tr><th>Pemakaian</th><th style="width: 200px;">Kode Chemical</th><th>Target</th></tr></thead><tbody>';
                    result.forEach(function(val) {
                        content += '<tr><td><input type="checkbox" name="selected_chemicals[]" value="' + val.uuid + '"></td><td>' + val.kode_chemical + '</td><td>' + val.target + '</td></tr>';
                    });
                    content += '</tbody></table>';
                    $('#kondisi-area-content').html(content);
                });
            } else {
                $('#kondisi-area-content').html('');
            }
        });
    });
});
</script>