<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Data Issue</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('tracking') ?>"><i class="fas fa-arrow-left mr-2"></i>Tracking Improvement</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('tracking/edit/'.$data->uuid) ?>" method="post">
            

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Issue :<span class="text-danger">*</span></label>
                        <input type="text" name="issue" class="form-control <?= form_error('issue') ? 'invalid' : '' ?>" placeholder="Masukkan Issue" value="<?= $data->issue; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('issue')) ? 'd-block':'';?>">
                            <?= form_error('issue') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">PIC<span class="text-danger">*</span></label>
                        <input type="text" name="pic" class="form-control <?= form_error('pic') ? 'invalid' : '' ?>" placeholder="PIC" value="<?= $data->pic; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('pic')) ? 'd-block':'';?>">
                            <?= form_error('pic') ?>
                        </div>
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('tracking') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



