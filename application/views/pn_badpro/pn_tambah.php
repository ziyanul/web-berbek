<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Update Pemusnahan Bad Produk</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pemusnahan_badproduct') ?>"><i
                        class="fas fa-arrow-left mr-2"></i> Pemusnahan Bad Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pemusnahan_badproduct/tambah') ?>" method="post">
                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Shift:<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('shift') ? 'invalid' : '' ?>" name="shift"
                            id="shift">
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

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Varian :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" name="varian"
                            id="varian">
                            <option selected disabled>Pilih Varian</option>
                            <?php foreach ($varian as $v): ?>
                            <option value="<?= $v->uuid ?>" <?= set_select('varian', $v->uuid);?>><?= $v->varian ?> -
                                <?= $v->keterangan ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="varian_name">
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode Produk :<span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Kode Produk" value="<?= set_value('kode'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Qty (Kg) :<span class="text-danger">*</span></label>
                        <input type="text" name="qty_kg"
                            class="form-control <?= form_error('qty_kg') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Quantity" value="<?= set_value('qty_kg'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('qty_kg')) ? 'd-block':'';?>">
                            <?= form_error('qty_kg') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('pemusnahan_badproduct') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times mr-1"></i> Batal
                            </button>
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('select[name="varian"]').change(function() {
        var varian = $(this).val();
        $.get('<?= base_url('Pemusnahan_Badproduct/get_item_name/');?>' + varian, function(res) {
            var data = JSON.parse(res);
            $('input[name="varian_name"]').val(data.varian);
        });
    });
});
</script>