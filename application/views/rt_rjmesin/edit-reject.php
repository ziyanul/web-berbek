<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">Ubah Data Reject Permesin Di Retort</h2>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('rt_rjmesin') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('rt_rjmesin/detailreject/' . $data[0]->kode_batch) ?>">
                    Detail Reject Mesin Per Batch
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-success mb-4">
                    <tbody>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal Planning Produksi</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $data[0]->tanggal;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $data[0]->MN_PRODUK;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Kode Produk</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $data[0]->MN_BATCH;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nama Mesin</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $data[0]->nama_mesin;?></td>
                        </tr>
                    </tbody>
                </table>
            </div>



            <form class="user" action="<?= base_url('rt_rjmesin/editreject/' . $data[0]->rt_mesin_uuid) ?>" method="post">

                <input class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin" value="<?= $data[0]->rt_mesin_uuid ?>" hidden>
                <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                            <?= form_error('mesin') ?>
                        </div>

                <!-- Input Badpro dan Reject -->
                <div id="badproInputs">
                    <?php foreach ($data as $dat): ?>
                        <div class="row mb-4 badpro-row">
                            <div class="col-sm-4">
                                <label class="form-label">Pilih Badpro<span class="text-danger"> *</span></label>
                                <select class="form-control mb-2" name="badpro[]" required>
                                    <option disabled selected>Pilih Badpro</option>
                                    <?php foreach ($badpro as $row): ?>
                                        <option value="<?= $row->uuid; ?>" <?= set_select('badpro', $row->uuid, $dat->badpro_uuid == $row->uuid);?>>

                                            <?= $row->nama_badpro; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label">Jumlah Reject<span class="text-danger"> *</span></label>
                                <input type="float" class="form-control" name="reject[]" value="<?= $dat->reject ?>" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tombol Tambah Badpro -->
                <div class="row mb-4">
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-md btn-primary" id="addBadproBtn">
                            <i class="fa fa-plus"></i> Badpro
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('rt_rjmesin/detail/' . $data[0]->planprod_uuid) ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>



        </div>
    </div>



    <script>
    // Menambahkan input badpro dan reject baru
        document.getElementById('addBadproBtn').addEventListener('click', function () {
            const badproInputs = document.getElementById('badproInputs');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-4', 'badpro-row');
            newRow.innerHTML = `
            <div class="col-sm-4">
            <label class="form-label">Pilih Badpro<span class="text-danger"> *</span></label>
            <select class="form-control" name="badproadd[]" required>
            <option disabled selected>Pilih Badpro</option>
            <?php foreach ($badpro as $row): ?>
                <option value="<?= $row->uuid; ?>">
                <?= $row->nama_badpro; ?>
                </option>
            <?php endforeach; ?>
            </select>
            </div>
            <div class="col-sm-2">
            <label class="form-label">Jumlah Reject<span class="text-danger"> *</span></label>
            <input type="float" class="form-control" name="rejectadd[]" min="0" required>
            </div>
            <div class="col-sm-4">
            <button type="button" class="btn btn-md btn-danger removeBadproBtn mt-4">
            <i class="fa fa-minus"></i>
            </button>
            </div>
            `;
            badproInputs.appendChild(newRow);
        });

    // Menghapus input badpro dan reject
        document.getElementById('badproInputs').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('removeBadproBtn')) {
                const row = event.target.closest('.badpro-row');
                if (row) {
                    row.remove();
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var kode = '<?= $nav->MN_BATCH ?>'; 
            if (!kode) {
                console.error('Kode batch tidak valid:', kode);
                return;
            }
    // Ambil data mesin dari server
            if (kode) {
                $.get('<?= base_url('rt_rjmesin/get_mesin_by_counter/'); ?>' + kode, function (res) {
                    try {
                var result = JSON.parse(res); // Parsing JSON
                var elem = '<option disabled selected>Pilih Mesin</option>'; // Opsi default

                result.forEach(function (val) {
                    if (parseInt(val.is_used) > 0) {
                        elem += '<option value="' + val.device_id + '" hidden></option>';
                    } else {
                        elem += '<option value="' + val.device_id + '">' + val.nama_mesin + '</option>';
                    }
                });

                $('select[name="mesin"]').html(elem); // Isi dropdown
            } catch (error) {
                console.error('Error parsing JSON:', error);
                alert('Terjadi kesalahan pada data mesin.');
            }
        }).fail(function () {
            alert('Terjadi kesalahan saat memuat data mesin.');
        });
    } else {
        console.error('Kode batch tidak valid:', kode);
    }
});
</script>