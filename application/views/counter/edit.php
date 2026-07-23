<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Data Form Counter</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('counter') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Checker</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('counter/tambah') ?>" method="post">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Tanggal :</label>
                        <input type="date" name="date" class="form-control <?= form_error('date') ? 'invalid' : '' ?>" placeholder="" value="<?= $data->tanggal; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('date')) ? 'd-block':'';?>">
                            <?= form_error('date') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 mb-sm-0">           
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Varian :<span class="text-danger"> *</span></label><br>
                        <select class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" name="varian" id="varian">
                            <option disabled>Pilih Varian</option>
                            <option value="0" <?= ($data->varian == 0) ? 'selected' : ''; ?>>OKEY</option>
                            <option value="1" <?= ($data->varian == 1) ? 'selected' : ''; ?>>CHAMP</option>
                        </select>

                        <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                            <?= form_error('varian') ?>
                        </div>
                        
                    </div>
                </div>


                <div class="row mt-5">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('counter') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





