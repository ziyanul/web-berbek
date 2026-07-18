<div class="container-fluid">

    <h2 class="h2 mb-2 text-gray-800">Input GAP</h2>
    <h3 class="h3 mb-2 text-gray-800"></h3>
<div class="card shadow mb-4">
    <div class="card-body">

        <div class="row">
            <div class="col">
                <form class="user" action="<?= base_url('tracking/tambahbefore/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">ISSUE :</label>
                            <?= $data->issue; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">GAP Before</label>
                           <input type="text" name="fgap" class="form-control <?= form_error('fgap') ? 'invalid' : '' ?> " placeholder="Input GAP Before" value="<?= set_value('fgap'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fgap')) ? 'd-block':'';?>">
                            <?= form_error('fgap') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Dokumentasi :</label>
                           <input type="file" name="fdok_before" class="form-control <?= form_error('fdok_before') ? 'invalid' : '' ?> " placeholder="Input Hasil fdok_before" value="<?= set_value('fdok_before'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fdok_before')) ? 'd-block':'';?>">
                            <?= form_error('fdok_before') ?>
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
