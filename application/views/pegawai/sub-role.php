<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ganti Sub Role Pegawai</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('pegawai');?>"><i class="fas fa-arrow-left"></i> Pegawai</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ganti</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('pegawai/subrole/'.$data->uuid);?>" method="post">
            <div class="form-group row">
                <div class="col-sm-6">
                    <table class="table">           
                        <tbody>
                            <tr>
                                <td class="border-top-0" width="20">Nama</td>
                                <td width="10" class="border-top-0">:</td>
                                <td class="font-weight-bold border-top-0"><?= $data->fullname; ?></td>
                            </tr>
                            <tr>
                                <td width="200">Departemen</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->departemen_name->departemen; ?></td>
                            </tr>
                            
                            <tr>
                                <td width="200">Tipe</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->tipe; ?></td>
                            </tr>
                            <!-- <tr>
                                <td width="200">Sub Role</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->tipe; ?></td>
                            </tr> -->
                            
                            <tr>
                                <td width="200" class="border-bottom">Sub Role</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="font-weight-bold border-bottom">
                                    <select class="form-control <?= form_error('type') ? 'invalid' : '' ?>" name="sub_role">
                                        <option disabled selected>Pilih Sub Role</option>
                                        <option value="1" <?= ($data->subrole == 1) ? 'selected' : ''; ?>>Operator MP</option>
                                        <option value="2" <?= ($data->subrole == 2) ? 'selected' : ''; ?>>Sanitasi</option>
                                        <option value="3" <?= ($data->subrole == 3) ? 'selected' : ''; ?>>Enginering</option>
                                        <option value="4" <?= ($data->subrole == 4) ? 'selected' : ''; ?>>Operator Packing</option>
                                    </select>
                                </td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
                   <!--  <div class="col-sm-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= form_error('password') ? 'invalid' : '' ?>" placeholder="Masukkan Password" value="<?= set_value('password'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('password')) ? 'd-block':'';?>">
                            <?= form_error('password') ?>
                        </div>
                    </div> -->
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
