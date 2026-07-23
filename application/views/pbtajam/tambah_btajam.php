<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Pengecekan Benda Tajam</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('Pbtajam/form_pbtajam') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checklist</li>
        </ol>
    </nav>
    <!-- Breadcrumb omitted for brevity -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Pbtajam/tambah'); ?>" method="post">
                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label"> Shift:<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('shift') ? 'invalid' : '' ?>" name="shift" id="shift">
                            <option selected disabled>Pilih shift</option>
                            <option value="1" <?= set_select('shift', 1); ?>>Pagi</option>
                            <option value="2" <?= set_select('shift', 2); ?>>Sore</option>
                            <option value="3" <?= set_select('shift', 3); ?>>Malam</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('shift')) ? 'd-block' : '' ?>">
                            <?= form_error('shift') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area" disabled>
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
                        <label class="form-label">Pengecekan Berdasarkan Kode Benda: <span
                                class="text-danger">*</span></label>
                        <div id="kode-list">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Pbtajam/form_pbtajam/') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#area').prop('disabled', true);

    $('#shift').change(function() {
        var shiftSelected = $(this).val();
        $('#area').prop('disabled', false);
        $('#kode-list').empty();
        // $('#area').val('Loading...');
        $("#area option").prop("selected", function () {
            return this.defaultSelected;
        });

        if (shiftSelected) {
            $('#area').prop('disabled', false);
        } else {
            $('#area').prop('disabled', true);
        }
    });

    $('select[name="area"]').change(function() {
    var area_uuid = $(this).val();
    var shiftSelected = $('select[name="shift"]').val();  // Get selected shift
    var tanggal = '<?= date('Y-m-d') ?>';  // Get current date

    $('#kode-list').html('<div class="text-center"> <div class="spinner-border text-info" role="status"> <span class="sr-only">Loading...</span> </div>');
    $.get('<?= base_url('Pbtajam/get_kode_by_area/'); ?>' + area_uuid + '/' + shiftSelected + '/' + tanggal, function(res) {
        
        var data = JSON.parse(res);  // Parse the response
        console.log(data.data)
            if (data.status) { // Jika data status is true
                var elem = `
                    <table class="table table-bordered">
                        <thead class="bg-info text-light">
                        <tr>
                                <th class="text-center align-middle" rowspan="2">Jenis Benda</th>
                                <th class="text-center align-middle" rowspan="2">Kode Benda</th>
                                <th colspan="3" class="text-center">Kondisi</th>
                                <th rowspan="2" class="text-center align-middle">Keterangan</th>
                            </tr>

                            <tr>
                            <th class="text-center">Baik</th>
                            <th  class="text-center align-middle">Pecah</th>
                            <th  class="text-center align-middle">Hilang</th>
                        </tr>
                            
                        </thead>
                        <tbody>`;

                    data.data.forEach(function(val) {
                    elem += `
                        <tr>
                            <td>${val.jenis_benda}</td>
                            <td>${val.kode_benda}</td>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kode[${val.uuid}]" value="1" id="kode1_${val.uuid}" required>
                                </div>
                            </td>
                            <td class="text-center"> 
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kode[${val.uuid}]" value="2" id="kode2_${val.uuid}" required>
                                </div>
                            </td>
                            <td class="text-center"> 
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kode[${val.uuid}]" value="3" id="kode3_${val.uuid}" required>
                                </div>
                            </td> 
                            <td class="text-center">
                                    <input class="form-input" type="text" name="keterangan[${val.uuid}]" id="keterangan_${val.uuid}">
                            </td> 
                        </tr>`;
                    });

                elem += `</tbody></table>`;
                $('#kode-list').html(elem);
        } else {
            alert(data.message); // Menampilkan pesan berbeda berdasarkan respons
            $('#kode-list').html('');
        }
    }).fail(function() {
        console.error('Gagal mengambil data dari server.');
    });
    });

});
</script>