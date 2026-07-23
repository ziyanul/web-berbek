<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Update Reject Cooking</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rr_cooking') ?>"><i class="fas fa-arrow-left mr-2"></i> Reject Cooking Retort</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol> 
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Rr_cooking/edit/'.$data[0]->masak_retort_uuid) ?>" method="post">
                <?php foreach ($data as $index => $item): ?>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Varian :</label>
                        <input type="text" class="form-control" value="<?= $item->varian ?>" readonly>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Chamber :</label>
                        <input type="text" class="form-control" value="<?= $item->MR_NOCHAM ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Batch ke- :</label>
                        <input type="number" class="form-control" name="batch_ke[<?= $index ?>]" value="<?= $item->batch ?>" required>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Masakan ke- :</label>
                        <input type="number" class="form-control" name="masak[<?= $index ?>]" value="<?= $item->masak ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Jumlah Tray :</label>
                        <input type="number" class="form-control" name="jmltray[<?= $index ?>]" value="<?= $item->jml_tray ?>" required>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Jumlah Reject per Cooking (Kg) :</label>
                        <input type="text" class="form-control" name="rj_cooking[<?= $index ?>]" value="<?= $item->rj_cooking ?>" step="0.1" required>
                    </div>
                </div>
                <hr>
                <?php endforeach; ?>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Update
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
