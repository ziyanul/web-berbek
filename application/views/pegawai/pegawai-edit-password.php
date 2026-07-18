<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Pegawai Password</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('home');?>"><i class="fas fa-arrow-left"></i> Pegawai</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pegawai/edit_password/'.$user->uuid);?>" method="post">
                Hey, <span class="font-weight-bold"><?= $user->fullname ?></span>. <br>Buat Sandi yang kuat untuk mengurangi resiko di sabotase.
                
         <br><br>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new-password" class="form-control <?= form_error('new-password') ? 'invalid' : '' ?>" placeholder="Masukkan Password Baru" value="<?= set_value('new-password'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('new-password')) ? 'd-block':'';?>">
                            <?= form_error('new-password') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm-password" class="form-control <?= form_error('confirm-password') ? 'invalid' : '' ?>" placeholder="Masukkan Konfirmasi Password" value="<?= set_value('confirm-password'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('confirm-password')) ? 'd-block':'';?>">
                            <?= form_error('confirm-password') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('home');?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
