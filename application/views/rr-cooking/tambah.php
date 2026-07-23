<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Reject Cooking <?= $data->MR_KOPROD ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rr_cooking') ?>"><i class="fas fa-arrow-left mr-2"></i> Reject Cooking Retort</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol> 
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('rr_cooking/tambah/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Varian :</label>
                        <input type="text" class="form-control" value="<?= $data->MR_NMPRODUK ?>" name="varian" readonly>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Chamber :</label>
                        <input type="text" class="form-control" value="<?= $data->MR_NOCHAM ?>" name="mr_nocham" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Batch ke- :</label>
                        <input type="number" class="form-control" name="batch_ke" placeholder="Contoh : 12">
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Masakan Ke- :</label>
                        <input type="number" class="form-control" name="masak" placeholder="Contoh: 1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Jumlah Tray :</label>
                        <input type="number" class="form-control" name="jmltray" placeholder="maximal 28">
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Jumlah Reject per Cooking (Kg) :</label>
                        <input type="text" class="form-control" name="rj_cooking" step="0.1" placeholder="Contoh: 0.54">
                    </div>
                </div>
                <?php if (strpos($data->MR_KOPROD, '/') !== false) : ?>
                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <label class="form-label font-weight-bold">Batch ke- (Tambahan) :</label>
                            <input type="number" class="form-control" name="batch_ke_tambahan" placeholder="Contoh : 13">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="form-label font-weight-bold">Masakan Ke- (Tambahan) :</label>
                            <input type="number" class="form-control" name="masak_ke_tambahan" placeholder="Contoh: 2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <label class="form-label font-weight-bold">Jumlah Tray :</label>
                            <input type="number" class="form-control" name="jmltray_tambahan1" placeholder="Contoh: 5">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label class="form-label font-weight-bold">Jumlah Reject (Kg) :</label>
                            <input type="text" class="form-control" name="rj_tambahan1" step="0.1" placeholder="Contoh: 0.12">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('rr_cooking') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
