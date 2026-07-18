<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Pegawai</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('pegawai');?>"><i class="fas fa-arrow-left"></i> Pegawai</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
      </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pegawai/edit/'.$user->uuid);?>" method="post">
              <!--   <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= form_error('username') ? 'invalid' : '' ?>" placeholder="Masukkan Username" value="<?= $user->username; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('username')) ? 'd-block':'';?>">
                            <?= form_error('username') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= form_error('password') ? 'invalid' : '' ?>" placeholder="Masukkan Password" value="<?= set_value('password'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('password')) ? 'd-block':'';?>">
                            <?= form_error('password') ?>
                        </div>
                    </div>
                </div> -->
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control <?= form_error('fullname') ? 'invalid' : '' ?>" placeholder="Masukkan Full Name" value="<?= $user->fullname; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('fullname')) ? 'd-block':'';?>">
                            <?= form_error('fullname') ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= form_error('username') ? 'invalid' : '' ?>" placeholder="Masukkan Username" value="<?= $user->username; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('username')) ? 'd-block':'';?>">
                            <?= form_error('username') ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Departemen</label>
                        <select class="form-control <?= form_error('departemen') ? 'invalid' : '' ?>" name="departemen">
                            <option disabled selected>Pilih Departemen</option>
                            <?php
                                foreach ($dept as $val) {
                            ?>
                                <option value="<?= $val->uuid;?>" <?= set_select('departemen', $val->uuid); ?> <?= ($val->uuid==$user->departemen)?'selected':'';?>><?= $val->departemen ;?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('departemen')) ? 'd-block':'';?>">
                            <?= form_error('departemen') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Type</label>
                        <select class="form-control <?= form_error('type') ? 'invalid' : '' ?>" name="type">
                            <option disabled selected>Pilih Type</option>
                            <option value="1" <?= set_select('type', 1); ?> <?= ($user->type==1)?'selected':'';?>>Supervisor</option>
                            <option value="2" <?= set_select('type', 2); ?> <?= ($user->type==2)?'selected':'';?>>Foreman / Forlady</option>
                            <option value="3" <?= set_select('type', 3); ?> <?= ($user->type==3)?'selected':'';?>>Koordinator</option>
                            <option value="4" <?= set_select('type', 4); ?> <?= ($user->type==4)?'selected':'';?>>Operator</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('type')) ? 'd-block':'';?>">
                            <?= form_error('type') ?>
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
