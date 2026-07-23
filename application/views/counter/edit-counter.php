<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Counter <?= $data->nama_mesin; ?></h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('counter') ?>"><i class="fas fa-arrow-left"></i>Form Counter</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

  <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('Counter/editcounter/'.$data->uuid) ?>" method="post">
           
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Jumlah Counter<span class="text-danger">*</span></label>
                        <input type="text" name="counter" class="form-control <?= form_error('counter') ? 'invalid' : '' ?>" placeholder="Masukkan counter" value="<?= $data->counter; ?>">
                         <div class="invalid-feedback <?= !empty(form_error('counter')) ? 'd-block':'';?>">
                            <?= form_error('counter') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3" >
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('counter/detailcounter/'.$data->tbatch_uuid) ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>





</div>
