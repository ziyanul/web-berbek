<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Pemusnahan Badproduk</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"> <a href="<?= base_url('pemusnahan_badproduct');?>">
                    <i class="fas fa-arrow-left"></i> Pemusnahan Bad Produk</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('pemusnahan_badproduct/detail/'.$data->tanggal.'/'.$data->shift) ?>">
                    Detail</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pemusnahan_badproduct/edit/'.$data->uuid) ?>" method="post">

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kode Produk:<span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control <?= form_error('kode') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Kode Produk" value="<?= $data->kode_produksi ; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kode')) ? 'd-block':'';?>">
                            <?= form_error('kode') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Varian:<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" name="varian"
                            id="varian">
                            <option selected disabled>Pilih Varian</option>
                            <?php foreach ($varian as $v): ?>
                            <option value="<?= $v->uuid ?>"
                                <?= set_select('varian', $v->uuid, $data->varian_uuid == $v->uuid);?>><?= $v->varian ?>
                                - <?= $v->keterangan ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Qty (Kg):<span class="text-danger">*</span></label>
                        <input type="text" name="qty_kg"
                            class="form-control <?= form_error('qty_kg') ? 'invalid' : '' ?>"
                            placeholder="Masukkan qty_kg" value="<?= $data->qty_kg; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('qty_kg')) ? 'd-block':'';?>">
                            <?= form_error('qty_kg') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Ubah
                        </button>
                        <a href="<?= base_url('pemusnahan_badproduct/detail/'.$data->tanggal.'/'.$data->shift) ?>"
                            class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>