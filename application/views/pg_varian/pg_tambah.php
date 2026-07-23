<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Pergantian Varian</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian') ?>"><i class="fas fa-arrow-left mr-2"></i> Pergantian Varian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol> 
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
        <form class="user" action="<?= base_url('pergantian_varian/tambah') ?>" method="post">

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Area:<span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area">
                <option selected disabled>Pilih Area</option>
                <option value="1" <?= set_select('area', 1);?>>Retort</option>
                <option value="2" <?= set_select('area', 2);?>>Packing</option>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                <?= form_error('area') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
    <div class="col-sm-6 mt-3">
        <label class="form-label">Shift:<span class="text-danger">*</span></label>
        <select class="form-control <?= form_error('shift') ? 'invalid' : '' ?>" name="shift">
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
            <label class="form-label">Varian dari Proses Sortasi:<span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('varian_sortasi') ? 'invalid' : '' ?>" name="varian_sortasi" id="varian_sortasi">
                <option selected disabled>Pilih Varian</option>
                <?php foreach ($varian as $v): ?>
                    <option value="<?= $v->uuid ?>" <?= set_select('varian_sortasi', $v->uuid);?>><?= $v->varian ?>- <?= $v->keterangan ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('varian_sortasi')) ? 'd-block':'';?>">
                <?= form_error('varian_sortasi') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Kode Batch dari Proses Sortasi:<span class="text-danger">*</span></label>
            <input type="text" name="kode_batch_sortasi" class="form-control <?= form_error('kode_batch_sortasi') ? 'invalid' : '' ?>" placeholder="Masukkan Kode Batch" value="<?= set_value('kode_batch_sortasi'); ?>">
            <div class="invalid-feedback <?= !empty(form_error('kode_batch_sortasi')) ? 'd-block':'';?>">
                <?= form_error('kode_batch_sortasi') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Varian ke Proses Sortasi:<span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('varian_ke_sortasi') ? 'invalid' : '' ?>" name="varian_ke_sortasi" id="varian_ke_sortasi">
                <option selected disabled>Pilih Varian</option>
                <?php foreach ($varian as $v): ?>
                    <option value="<?= $v->uuid ?>" <?= set_select('varian_ke_sortasi', $v->uuid);?>><?= $v->varian ?> - <?= $v->keterangan ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('varian_ke_sortasi')) ? 'd-block':'';?>">
                <?= form_error('varian_ke_sortasi') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Kode Batch ke Proses Sortasi:<span class="text-danger">*</span></label>
            <input type="text" name="kode_batch_ke_sortasi" class="form-control <?= form_error('kode_batch_ke_sortasi') ? 'invalid' : '' ?>" placeholder="Masukkan Kode Batch" value="<?= set_value('kode_batch_ke_sortasi'); ?>">
            <div class="invalid-feedback <?= !empty(form_error('kode_batch_ke_sortasi')) ? 'd-block':'';?>">
                <?= form_error('kode_batch_ke_sortasi') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Kondisi:<span class="text-danger">*</span></label>
            <select class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" name="kondisi" id="kondisi">
                <option selected disabled>Pilih Kondisi</option>
                <option value="1" <?= set_select('kondisi', 1);?>>Bersih dari Kontaminasi</option>
                <option value="2" <?= set_select('kondisi', 2);?>>Belum Bersih dari Kontaminasi</option>
            </select>
            <div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block':'';?>">
                <?= form_error('kondisi') ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 mt-3">
            <label class="form-label">Keterangan:<span class="text-danger">*</span></label>
            <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
            <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                <?= form_error('keterangan') ?>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-md btn-success mr-2">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('pergantian_varian') ?>" class="btn btn-md btn-danger">
                <i class="fa fa-times"></i> Batal
            </a>
        </div>
    </div>
</form>

        </div>
    </div>
</div>