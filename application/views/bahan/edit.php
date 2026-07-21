<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Master Bahan Baku</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('bahan') ?>"><i class="fas fa-arrow-left"></i> Fokus
                    Master Bahan Baku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('bahan/edit/'.$data->uuid) ?>" method="post">

                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Kode Bahan Baku<span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Kode" value="<?= $data->kode_bahan; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Bahan Baku<span class="text-danger">*</span></label>
                        <input type="text" name="bahan" class="form-control <?= form_error('bahan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Nama" value="<?= $data->nama_bahan; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('bahan')) ? 'd-block':'';?>">
                            <?= form_error('bahan') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Nama keterangan" value="<?= $data->keterangan; ?>">
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
                        <a href="<?= base_url('bahan') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>









</div>