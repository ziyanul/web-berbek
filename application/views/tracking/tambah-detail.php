<div class="container-fluid">

    <h2 class="h2 mb-2 text-gray-800">Input Detail Issue</h2>
    <h3 class="h3 mb-2 text-gray-800"></h3>
<div class="card shadow mb-4">
    <div class="card-body">

        <div class="row">
            <div class="col">
                <form class="user" action="<?= base_url('tracking/tambahdetail/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">ISSUE :</label>
                            <?= $data->issue; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-8 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Detail Issue :</label>
                           <input type="text" name="fdetail" class="form-control <?= form_error('fdetail') ? 'invalid' : '' ?> " placeholder="Input detail issue" value="<?= set_value('fdetail'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fdetail')) ? 'd-block':'';?>">
                            <?= form_error('fdetail') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Dokumentasi :</label>
                           <input type="file" name="dokumentasi" class="form-control <?= form_error('dokumentasi') ? 'invalid' : '' ?> " placeholder="Input Hasil dokumentasi" value="<?= set_value('dokumentasi'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('dokumentasi')) ? 'd-block':'';?>">
                            <?= form_error('dokumentasi') ?>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('tracking/detail/' .$data->uuid) ?>" class="btn btn-md btn-danger">
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
