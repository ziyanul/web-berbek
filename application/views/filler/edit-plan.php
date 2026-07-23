<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Planning Produksi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('filler/planning') ?>"><i class="fas fa-arrow-left"></i> Planning Produksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('filler/editplan/'.$data->uuid) ?>" method="post">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Tanggal : <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control <?= form_error('tanggal') ? 'invalid' : '' ?>" placeholder="Pilih Planning" value="<?= $data->tanggal ?>">
                        <div class="invalid-feedback <?= !empty(form_error('tanggal')) ? 'd-block':'';?>">
                            <?= form_error('tanggal') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Varian :<span class="text-danger"> *</span></label><br>

                        <select class="form-control <?= form_error('f_varian') ? 'is-invalid' : '' ?>" name="f_varian">

                            <option value="">-- Pilih Varian --</option>

                            <?php foreach($varian as $v): ?>
                                <option value="<?= $v->uuid ?>"
                                    <?= set_select(
                                        'f_varian',
                                        $v->uuid,
                        ($data->varian == $v->uuid) // auto selected saat edit
                        ); ?>>
                        <?= $v->varian ?> - <?= $v->keterangan ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <div class="invalid-feedback <?= form_error('f_varian') ? 'd-block':'';?>">
                <?= form_error('f_varian') ?>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-6">
            <label class="form-label">Start : <span class="text-danger">*</span></label>
            <input type="datetime-local" name="f_start" class="form-control <?= form_error('f_start') ? 'invalid' : '' ?>" placeholder="Masukkan Jam Mulai" value="<?= $data->start ?>">
            <div class="invalid-feedback <?= !empty(form_error('f_start')) ? 'd-block':'';?>">
                <?= form_error('f_start') ?>
            </div>
        </div>
        <div class="col-sm-6">
            <label class="form-label">Finish : <span class="text-danger">*</span></label>
            <input type="datetime-local" name="f_end" class="form-control <?= form_error('f_end') ? 'invalid' : '' ?>" placeholder="Masukkan Jam Ngakhiri" value="<?= $data->end ?>">
            <div class="invalid-feedback <?= !empty(form_error('f_end')) ? 'd-block':'';?>">
                <?= form_error('f_end') ?>
            </div>
        </div>
    </div>
    <div class="row mb-2">


        <div class="col-sm-6">
            <label class="form-label">Cleaning Schedule : <span class="text-danger">*</span></label>
            <input type="number" name="f_clean" class="form-control <?= form_error('f_clean') ? 'invalid' : '' ?>" placeholder="Menit Cleaning" value="<?= $data->clean ?>">
            <div class="invalid-feedback <?= !empty(form_error('f_clean')) ? 'd-block':'';?>">
                <?= form_error('f_clean') ?>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-md btn-success mr-2">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('filler/planning/') ?>" class="btn btn-md btn-danger">
                <i class="fa fa-times"></i> Batal
            </a>
        </div>
    </div>
</form>
</div>
</div>
</div>
<script src="js"></script>
<script>
    $('#txt_tgl').datetimepicker({
        format: 'DD/MM/YYYY',
    });
</script>