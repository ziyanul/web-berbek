<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Tambah Master Data Bad Produk</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('badpro/') ?>"><i class="fas fa-arrow-left"></i> Master Bad Produk</a></li>
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
            <form class="user" action="<?= base_url('badpro/tambahmaster') ?>" method="post">
                <div class="row-sm-6 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Bad Produk <span class="text-danger">*</span></label>
                        <input type="text" name="badpro" class="form-control <?= form_error('badpro') ? 'invalid' : '' ?>" placeholder="Masukkan Nama Badpro" value="<?= set_value('badpro'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('badpro')) ? 'd-block':'';?>">
                            <?= form_error('badpro') ?>
                        </div>
                    </div>
                </div>

                <div class="row-sm-6 mb-5">
                    <div class="col-sm-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-control" name="kategori">
                            <option disabled selected>Pilih Kategori</option>
                            <option value="1">Rework</option>
                            <option value="2">Reject</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('kategori')) ? 'd-block' : ''; ?>">
                            <?= form_error('kategori'); ?>
                        </div>
                    </div>
                </div>
                <div class="row-sm-6 mb-5">
                    <div class="col-sm-6">
                        <label class="form-label">Lokasi Proses <span class="text-danger">*</span></label>
                        <select class="form-control" name="proses">
                            <option disabled selected>Pilih Tempat</option>
                            <?php
                            foreach ($proses as $pr) { ?>
                            <option value="<?= $pr->uuid ?>"><?= $pr->kode ?></option>

                            <?php } ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('proses')) ? 'd-block' : ''; ?>">
                            <?= form_error('proses'); ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('badpro/') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>