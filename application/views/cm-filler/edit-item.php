<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Item Pengecekan Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Item</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('cekmesin_fillerbatch/edititem/'. $data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label for="mesin" class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'is-invalid' : '' ?>" name="mesin" id="mesin">
                            <option value="<?= $data->mesin_uuid ?>" selected><?= $data->mesin?></option>
                        </select>
                        <?php if (form_error('mesin')): ?>
                            <div class="invalid-feedback"><?= form_error('mesin'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <input type="hidden" name="mesin_name" value="<?= $data->mesin ?>">

                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Item: <span class="text-danger">*</span></label>
                        <input type="text" name="item" class="form-control <?= form_error('item') ? 'invalid' : '' ?>" placeholder="" value="<?= $data->item ?>">
                        <div class="invalid-feedback <?= !empty(form_error('item')) ? 'd-block':'';?>">
                            <?= form_error('item') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>" class="btn btn-md btn-danger">
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

        $('select[name="mesin"]').change(function() {
            var mesin_uuid = $(this).val();
            $.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid,function(res) {
                var data = JSON.parse(res);
                $('input[name="mesin_name"]').val(data.nama_mesin);
            })
        })

    })
</script>