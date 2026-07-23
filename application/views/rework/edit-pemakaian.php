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
            <form class="user" action="<?= site_url('rework/editpakai/'. $data->uuid) ?>" method="post">
    <div class="row mb-3">
        <div class="col-sm-6">
            <label class="form-label">Varian<span class="text-danger"> *</span></label>
            <input type="text" name="varian" class="form-control" 
                   value="<?= $data->varian ?>" readonly>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-sm-6">
            <label class="form-label">Kode Rework<span class="text-danger"> *</span></label>
            <input type="text" name="kode_rework" id="kode_rework" class="form-control" 
                   value="<?= $data->kode_rework ?>" readonly>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-sm-6">
            <label class="form-label">Qty Pemakaian<span class="text-danger"> *</span></label>
            <input type="number" name="qty_pemakaian" id="qty_pemakaian" step="0.01"
                   class="form-control <?= form_error('qty_pemakaian') ? 'invalid' : '' ?>"
                   value="<?= $data->dipakai ?>" placeholder="Max: 0">
            <input type="hidden" id="remaining_stock" value="<?= $data->dipakai; ?>">
            <div class="invalid-feedback <?= !empty(form_error('qty_pemakaian')) ? 'd-block' : ''; ?>">
                <?= form_error('qty_pemakaian') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6">
            <label class="form-label">Kode Produksi Pemakaian<span class="text-danger"> *</span></label>
            <input type="text" name="kode_batch" placeholder="Input Batch Code"
                   class="form-control <?= form_error('kode_batch') ? 'invalid' : '' ?>"
                   value="<?= $data->kode_produksi ?>">
            <div class="invalid-feedback <?= !empty(form_error('kode_batch')) ? 'd-block' : ''; ?>">
                <?= form_error('kode_batch') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6">
            <label class="form-label">Temuan Plastik<span class="text-danger"> *</span></label>
            <select class="form-control <?= form_error('plastik') ? 'invalid' : '' ?>" name="plastik">
                <option selected disabled>Pilih Ya / Tidak:</option>
                <option value="1" <?= ($data->plastik == 1) ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= ($data->plastik == 2) ? 'selected' : ''; ?>>Tidak</option>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('plastik')) ? 'd-block' : ''; ?>">
                <?= form_error('plastik') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6">
            <label class="form-label">Temuan Metal<span class="text-danger"> *</span></label>
            <select class="form-control <?= form_error('metal') ? 'invalid' : '' ?>" name="metal">
                <option selected disabled>Pilih Ya / Tidak:</option>
                <option value="1" <?= ($data->metal == 1) ? 'selected' : ''; ?>>Ya</option>
                <option value="2" <?= ($data->metal == 2) ? 'selected' : ''; ?>>Tidak</option>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('metal')) ? 'd-block' : ''; ?>">
                <?= form_error('metal') ?>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-md btn-success mr-2">
                <i class="fa fa-save"></i> Edit Rework
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
$(document).ready(function() {
    var remainingStock = parseFloat($('#remaining_stock').val());
    $('#qty_pemakaian').attr('max', remainingStock); // Set the max attribute

    // Validate qty_pemakaian on input change
    $('#qty_pemakaian').on('input', function () {
        var max = parseFloat($(this).attr('max'));
        var value = parseFloat($(this).val());

        // If the current value exceeds the max, reset it and alert the user
        if (value > max) {
            alert('Input tidak boleh melebihi sisa stock: ' + max);
            $(this).val(''); // Optionally clear the input
        }
    });
});
</script>