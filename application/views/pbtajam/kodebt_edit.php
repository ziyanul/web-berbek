<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Edit Kode Benda Tajam</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"> <a href="<?= base_url('Pbtajam/kodebtajam');?>"> <i class="fas fa-arrow-left"></i> Data Kode Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Pbtajam/editkodebt/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Kode Benda Tajam <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Nama Benda Tajam" value="<?= $data->kode_benda; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mt-4">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Pbtajam/kodebtajam') ?>"
                            class="btn btn-md btn-danger mt-4">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>