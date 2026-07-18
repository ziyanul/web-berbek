<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Departemen</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('departemen');?>"><i class="fas fa-arrow-left"></i> Departemen</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
      </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('departemen/tambah');?>" method="post">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Departemen</label>
                        <input type="text" name="dept" class="form-control <?= form_error('dept') ? 'invalid' : '' ?>" placeholder="Masukkan Nama Departemen" value="<?= set_value('dept'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('dept')) ? 'd-block':'';?>">
                            <?= form_error('dept') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('departemen');?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->