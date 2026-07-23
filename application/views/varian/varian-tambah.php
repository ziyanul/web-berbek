<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Varian</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('varian') ?>"><i class="fas fa-arrow-left"></i> Data Varian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
            <form class="user" action="<?= base_url('varian/tambah') ?>" method="post">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Varian <span class="text-danger">*</span></label>
                        <input type="text" name="varian" class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" placeholder="mis: SROA" value="<?= set_value('varian'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" placeholder="mis: Sosis Retort Okey Ayam" value="<?= set_value('keterangan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="form-label">Panjang Produk (cm)<span class="text-danger">*</span></label>
                        <input type="text" name="panjang" class="form-control <?= form_error('panjang') ? 'invalid' : '' ?>" placeholder="Standar panjang Produk" value="<?= set_value('panjang'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('panjang')) ? 'd-block':'';?>">
                            <?= form_error('panjang') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="form-label">Berat Produk (gram) <span class="text-danger">*</span></label>
                        <input type="text" name="berat" class="form-control <?= form_error('berat') ? 'invalid' : '' ?>" placeholder="Standar Berat Produk" value="<?= set_value('berat'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('berat')) ? 'd-block':'';?>">
                            <?= form_error('berat') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('varian') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>