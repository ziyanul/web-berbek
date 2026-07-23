<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">Tambah Penggunaan Rework</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rework/pemakaian'); ?>"><i class="fas fa-arrow-left"></i>
            Penggunaan Rework</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= site_url('rework/tambahpakai') ?>" method="post">
                <div class="row mb-3 mb-sm-0">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Varian<span class="text-danger"> *</span></label>
                        <select class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" name="varian" id="varian">
                            <option selected disabled>Pilih Varian</option>
                            <?php foreach ($varian as $variant): ?>
                                <option value="<?= $variant->varian_uuid ?>" 
                                    data-planprod_uuid="<?= $variant->uuid ?>" 
                                    <?= set_select('varian', $variant->uuid); ?>>
                                    <?= $variant->varian ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block' : ''; ?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>

                </div>

                <div class="row mt-3 mb-3 mb-sm-0">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Kode Rework<span class="text-danger"> *</span></label>
                        <select class="form-control <?= form_error('kode_rework') ? 'invalid' : '' ?>"
                            name="kode_rework" id="kode_rework">
                            <!-- Akan diisi melalui JS/AJAX -->
                            <option selected disabled>Select Rework Code</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('kode_rework')) ? 'd-block' : ''; ?>">
                            <?= form_error('kode_rework') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mb-3 mb-sm-0">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Qty Pemakaian<span class="text-danger">*</span></label>
                        <input type="number" name="qty_pemakaian" id="qty_pemakaian" step="0.01" placeholder="Masukkan Quantity"
                        class="form-control <?= form_error('qty_pemakaian') ? 'invalid' : '' ?>"
                        value="<?= set_value('qty_pemakaian'); ?>" max="">

                        <div class="invalid-feedback <?= !empty(form_error('qty_pemakaian')) ? 'd-block' : ''; ?>">
                            <?= form_error('qty_pemakaian') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 mt-3 mb-sm-0">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Kode Produksi Pemakaian<span class="text-danger">*</span></label>
                        <select name="kode_batch" id="kode_batch"
                        class="form-control <?= form_error('kode_batch') ? 'invalid' : '' ?>">
                        <option selected disabled>Pilih Kode Batch</option>
                        <!-- Opsi akan diisi melalui AJAX -->
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('kode_batch')) ? 'd-block' : ''; ?>">
                        <?= form_error('kode_batch') ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3 mt-3 mb-sm-0">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label">Temuan Plastik<span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('plastik') ? 'invalid' : '' ?>" name="plastik">
                        <option selected disabled>Pilih Ya / Tidak</option>
                        <option value="1" <?= set_select('plastik', 1);?>>Ya</option>
                        <option value="2" <?= set_select('plastik', 2);?>>Tidak</option>
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('plastik')) ? 'd-block':'';?>">
                        <?= form_error('plastik') ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3 mt-3 mb-sm-0">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label">Temuan Metal<span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('metal') ? 'invalid' : '' ?>" name="metal">
                        <option selected disabled>Pilih Ya / Tidak</option>
                        <option value="1" <?= set_select('metal', 1);?>>Ya</option>
                        <option value="2" <?= set_select('metal', 2);?>>Tidak</option>
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('metal')) ? 'd-block':'';?>">
                        <?= form_error('metal') ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Use Rework
                    </button>
                    <a href="<?= base_url('rework/pemakaian') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>

</div>



<script>
    $(document).ready(function () {
        $('#varian').on('change', function () {
        var varian_uuid = $(this).val(); // Ambil varian_uuid
        var planprod_uuid = $(this).find(':selected').data('planprod_uuid'); // Ambil planprod_uuid

        // Ambil data kode_rework berdasarkan varian_uuid
        $.ajax({
            url: '<?= site_url('rework/get_rework_by_varian') ?>',
            type: 'GET',
            data: { varian: varian_uuid },
            success: function (data) {
                $('#kode_rework').html(data);
            }
        });

        // Ambil data kode_batch dari mincing berdasarkan t_planning.uuid
        $.ajax({
            url: '<?= site_url('rework/get_batch_by_varian') ?>',
            type: 'GET',
            data: { planprod_uuid: planprod_uuid },
            success: function (data) {
                $('#kode_batch').html(data);
            }
        });
    });

    // Saat kode_rework dipilih, ambil sisa berat
        $('#kode_rework').on('change', function () {
            var kode_rework = $(this).val();
            $.ajax({
                url: '<?= site_url('rework/get_remaining_weight') ?>',
                type: 'GET',
                data: { kode_rework: kode_rework },
                success: function (data) {
                    var result = JSON.parse(data);
                    var remaining = result.remaining;
                $('#qty_pemakaian').attr('max', remaining); // Set atribut max
                $('#qty_pemakaian').attr('placeholder', 'Max: ' + remaining); // Update placeholder

                // Hapus input jika melebihi batas maksimal
                var currentValue = parseFloat($('#qty_pemakaian').val());
                if (currentValue > remaining) {
                    $('#qty_pemakaian').val('');
                    alert('Input tidak boleh melebihi stok tersisa: ' + remaining);
                }
            }
        });
        });

    // Validasi qty_pemakaian saat input berubah
        $('#qty_pemakaian').on('input', function () {
            var max = parseFloat($(this).attr('max'));
            var value = parseFloat($(this).val());

        // Jika nilai input melebihi max, reset dan beri peringatan
            if (value > max) {
                alert('Input tidak boleh melebihi stok tersisa: ' + max);
                $(this).val('');
            }
        });
    });
</script>
