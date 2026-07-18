<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Pegawai NIK</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('pegawai');?>"><i class="fas fa-arrow-left"></i> Pegawai NIK</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pegawai/edit/nik/'.$user->uuid);?>" method="post">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control <?= form_error('nik') ? 'invalid' : '' ?>" placeholder="Masukkan Username" value="<?= $user->nik; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('nik')) ? 'd-block':'';?>">
                            <?= form_error('nik') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pegawai');?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
