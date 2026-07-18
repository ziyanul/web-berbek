<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Printing DOD</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('zanasi') ?>"><i class="fas fa-arrow-left mr-2"></i>Printing DOD</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('zanasi/tambah/') ?>" method="post">
                <div class="row mb-3 mb-sm-0">           
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Tipe : <span class="text-danger"> *</span></label><br>
                        <select class="form-control <?= form_error('rutin') ? 'is-invalid' : '' ?>" name="rutin" id="rutin">
                            <option selected disabled>- -</option>
                            <option value="1" <?= set_select('rutin', 1);?>>Rutin</option>
                            <option value="2" <?= set_select('rutin', 2);?>>Tambahan</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 mb-sm-0">           
                    <div class="col-sm-6 mt-3 mb-sm-0">
                        <label class="form-label">Varian :<span class="text-danger"> *</span></label><br>
                        <select class="form-control <?= form_error('varian') ? 'is-invalid' : '' ?>" name="varian" id="varian">
                            <option selected disabled>Pilih Varian</option>
                            <?php foreach($varian as $v): ?>
                            <option value="<?= $v->uuid ?>" <?= set_select('varian', $v->uuid);?>><?= $v->varian ?> - <?= $v->keterangan ?></option>
                        <?php endforeach; ?>
                            
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode Produksi <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'is-invalid' : '' ?>" placeholder="Isi Kode Lengkap" value="<?= set_value('kode'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode EXP <span class="text-danger">*</span></label>
                        <input type="text" name="exp" class="form-control <?= form_error('exp') ? 'is-invalid' : '' ?>" placeholder="BB tgl bln th" value="<?= set_value('exp'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('exp')) ? 'd-block':'';?>">
                            <?= form_error('exp') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Jumlah Permintaan <span class="text-danger">*</span></label>
                        <input type="number" name="permintaan" class="form-control <?= form_error('permintaan') ? 'is-invalid' : '' ?>" placeholder="0" value="<?= set_value('permintaan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('permintaan')) ? 'd-block':'';?>">
                            <?= form_error('permintaan') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('zanasi') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



