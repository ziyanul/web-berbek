<div class="container-fluid">

    <h2 class="h2 mb-2 text-gray-800">Input CAP</h2>
    <h3 class="h3 mb-2 text-gray-800"></h3>
<div class="card shadow mb-4">
    <div class="card-body">

        <div class="row">
            <div class="col">
                <form class="user" action="<?= base_url('tracking/tambahafter/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">ISSUE :</label>
                            <?= $data->issue; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">CAP After</label>
                           <input type="text" name="fcap" class="form-control <?= form_error('fcap') ? 'invalid' : '' ?> " placeholder="Input CAP After" value="<?= set_value('fcap'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fcap')) ? 'd-block':'';?>">
                            <?= form_error('fcap') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Dead Line:</label>
                           <input type="date" name="fdeadline" class="form-control <?= form_error('fdeadline') ? 'invalid' : '' ?> " placeholder="Input DeadLine" value="<?= set_value('fdeadline'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fdeadline')) ? 'd-block':'';?>">
                            <?= form_error('fdeadline') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Dokumentasi :</label>
                           <input type="file" name="fdok_after" class="form-control <?= form_error('fdok_after') ? 'invalid' : '' ?> " placeholder="" value="<?= set_value('fdok_after'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fdok_after')) ? 'd-block':'';?>">
                            <?= form_error('fdok_after') ?>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('tracking/beforeafter/' .$data->uuid) ?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>




    </div>
</div>
</div>
