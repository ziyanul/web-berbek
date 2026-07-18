    <div class="container-fluid">
        <h1 class="h1 mb-2 text-gray-800">Input Formula dan Rework</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('mpusage') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Adonan</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Update</li>
            </ol>
        </nav>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="<?= site_url('mpusage/input/' . $data->uuid_mp) ?>">
                    <input type="hidden" name="uuid" value="<?= $data->uuid_mp ?>">
                    <input type="hidden" name="kode_batch" value="<?= $data->kode_batch ?>">
                    <input type="hidden" name="varian_uuid" value="<?= $data->uuid_varian ?>">
                    <!-- PRODUKSI -->
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Kode Batch</label>
                            <input
                                type="text"
                                value="<?= $data->kode_batch ?>"
                                class="form-control"
                                readonly>
                            <input type="hidden" name="tbatch_uuid" value="<?= $data->tbatch_uuid ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Varian</label>
                            <input
                                type="text"
                                value="<?= $data->varian ?>"
                                class="form-control"
                                readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Formula</label>
                            <select
                                name="formula_uuid"
                                id="formula_uuid"
                                class="form-control">
                                <option value="">Pilih Formula</option>
                                <?php foreach ($formula as $f): ?>
                                    <option
                                        value="<?= $f->uuid ?>"
                                        data-kg="<?= $f->total ?>"
                                        <?= (!empty($data->formula_uuid) && $data->formula_uuid == $f->uuid) ? 'selected' : '' ?>>
                                        <?= $f->nama_formula ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Ukuran Batch</label>
                            <select id="batch_persen" class="form-control" name="batch_persen">
                                <option value="1" <?= $data->batch_persen == 1 ? 'selected' : '' ?>>
                                    1 Batch (100%)
                                </option>
                                <option value="0.75" <?= $data->batch_persen == 0.75 ? 'selected' : '' ?>>
                                    3/4 Batch (75%)
                                </option>
                                <option value="0.5" <?= $data->batch_persen == 0.5 ? 'selected' : '' ?>>
                                    1/2 Batch (50%)
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Formula KG</label>
                            <input
                                type="number"
                                name="formula_kg"
                                id="formula_kg"
                                class="form-control"
                                value="<?= $data->formula_kg ?>"
                                readonly>
                        </div>
                    </div>
                    <hr>
                    <!-- REWORK -->
                    <div class="form-group">
                        <label>
                            <input
                                type="checkbox"
                                id="toggleRework"
                                name="use_rework"
                                value="1"
                                <?= ($data->rework_kg > 0) ? 'checked' : '' ?>>
                            Gunakan Rework
                        </label>
                    </div>
                    <div
                        id="reworkBox"
                        style="<?= ($data->rework_kg > 0) ? '' : 'display:none;' ?>">
                        <div class="form-group">
                            <label>Jumlah Rework Dipakai (KG)</label>
                            <small class="text-info">
                                Stok tersedia :
                                <span id="stok_rework">0</span> Kg
                            </small>
                            <input
                                type="number"
                                name="rework_kg"
                                id="rework_kg"
                                class="form-control"
                                step="0.01"
                                min="0"
                                value="<?= $data->rework_kg ?>">
                            <small class="text-muted">
                                Sistem akan mengambil stok rework secara FIFO otomatis.
                            </small>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label>Temuan Plastik</label>
                                <select
                                    name="plastik"
                                    class="form-control">
                                    <option value="1" <?= $data->plastik == 1 ? 'selected' : '' ?>>
                                        YA
                                    </option>
                                    <option value="2" <?= ($data->plastik == 2 || $data->plastik === NULL) ? 'selected' : '' ?>>
                                        TIDAK
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Temuan Metal</label>
                                <select
                                    name="metal"
                                    class="form-control">
                                    <option value="1" <?= $data->metal == 1 ? 'selected' : '' ?>>
                                        YA
                                    </option>
                                    <option value="2" <?= ($data->metal == 2 || $data->metal === NULL) ? 'selected' : '' ?>>
                                        TIDAK
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('mpusage') ?>" class="btn btn-md btn-danger">
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
            let totalRework = 0;
            // formula awal
            function updateFormulaKg() {
                let kg = parseFloat($('#formula_uuid option:selected').data('kg')) || 0;
                let persen = parseFloat($('#batch_persen').val()) || 1;
                let hasil = kg * persen;
                $('#formula_kg').val(hasil.toFixed(2));
            }
            // pertama kali
            updateFormulaKg();
            // saat ganti formula
            $('#formula_uuid').on('change', function() {
                updateFormulaKg();
            });
            // saat ganti ukuran batch
            $('#batch_persen').on('change', function() {
                updateFormulaKg();
            });
            // tampilkan rework jika sudah pernah isi
            if ($('#toggleRework').is(':checked')) {
                $('#reworkBox').show();
                loadTotalRework();
            }
            $('#toggleRework').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#reworkBox').slideDown();
                    loadTotalRework();
                } else {
                    $('#reworkBox').slideUp();
                    $('#rework_kg').val('');
                }
            });

            function loadTotalRework() {
                $.ajax({
                    url: "<?= site_url('mpusage/get_total_rework') ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        varian_uuid: "<?= $data->uuid_varian ?>"
                    },
                    success: function(res) {
                        totalRework = parseFloat(res.total);
                        $('#stok_rework').text(totalRework);
                        $('#rework_kg')
                            .attr('max', totalRework)
                            .attr(
                                'placeholder',
                                'Maksimal ' + totalRework + ' Kg'
                            );
                    }
                });
            }
            $('#rework_kg').on('input', function() {
                let value = parseFloat($(this).val());
                if (isNaN(value)) {
                    return;
                }
                if (value > totalRework) {
                    alert(
                        'Jumlah rework melebihi stok tersedia (' +
                        totalRework +
                        ' Kg)'
                    );
                    $(this).val(totalRework);
                }
            });
            $('form').on('submit', function() {
                if ($('#toggleRework').is(':checked')) {
                    let value = parseFloat($('#rework_kg').val());
                    if (isNaN(value) || value <= 0) {
                        alert('Masukkan jumlah rework.');
                        $('#rework_kg').focus();
                        return false;
                    }
                    if (value > totalRework) {
                        alert(
                            'Jumlah rework melebihi stok tersedia (' +
                            totalRework +
                            ' Kg)'
                        );
                        return false;
                    }
                }
                return true;
            });
        });
    </script>