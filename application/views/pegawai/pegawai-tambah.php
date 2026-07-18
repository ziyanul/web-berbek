    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Tambah Pegawai</h1>

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pegawai');?>"><i class="fas fa-arrow-left"></i> Pegawai</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pegawai/tambah');?>" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control <?= form_error('fullname') ? 'invalid' : '' ?>" placeholder="Masukkan Full Name" value="<?= set_value('fullname'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('fullname')) ? 'd-block':'';?>">
                            <?= form_error('fullname') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Username</label>
                        <input type="text" id="username_preview" class="form-control" readonly>
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="text" name="tgl_lahir" class="form-control <?= form_error('tgl_lahir') ? 'invalid' : '' ?> datepickers" placeholder="Masukkan Tanggal Lahir" value="<?= set_value('tgl_lahir'); ?>" >
                        <div class="invalid-feedback <?= !empty(form_error('tgl_lahir')) ? 'd-block':'';?>">
                            <?= form_error('tgl_lahir') ?>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="text" name="tgl_bergabung" class="form-control <?= form_error('tgl_bergabung') ? 'invalid' : '' ?> datepickers" placeholder="Masukkan Tanggal Bergabung" value="<?= set_value('tgl_bergabung'); ?>" >
                        <div class="invalid-feedback <?= !empty(form_error('tgl_bergabung')) ? 'd-block':'';?>">
                            <?= form_error('tgl_bergabung') ?>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control <?= form_error('nik') ? 'invalid' : '' ?>" placeholder="Masukkan NIK" value="<?= set_value('nik'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('nik')) ? 'd-block':'';?>">
                            <?= form_error('nik') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" value="<?= set_value('foto'); ?>">
                    </div>
                </div> -->
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="form-label">Departemen</label>
                        <select class="form-control <?= form_error('dept') ? 'invalid' : '' ?>" name="departemen">
                            <option disabled selected>Pilih Departemen</option>
                            <?php
                            foreach ($dept as $val) {
                                ?>
                                <option value="<?= $val->uuid;?>" <?= set_select('dept', $val->uuid); ?>><?= $val->departemen;?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('dept')) ? 'd-block':'';?>">
                            <?= form_error('dept') ?>
                        </div>
                    </div>
                    
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="form-label">Type</label>
                        <select class="form-control <?= form_error('type') ? 'invalid' : '' ?>" name="type">
                            <option disabled selected>Pilih Type</option>
                            <option value="1" <?= set_select('type', 1); ?>>Supervisor</option>
                            <option value="2" <?= set_select('type', 2); ?>>Foreman / Forelady</option>
                            <option value="3" <?= set_select('type', 3); ?>>Koordinator</option>
                            <option value="4" <?= set_select('type', 4); ?>>Operator</option>
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


<script>
$(document).ready(function () {

    $('input[name="fullname"]').on('keyup', function () {

        let fullname = $(this).val();

        if(fullname.length < 2){
            $('#username_preview').val('');
            return;
        }

        $.ajax({
            url: "<?= base_url('pegawai/generate_username_ajax'); ?>",
            type: "POST",
            data: {
                fullname: fullname
            },
            dataType: "json",
            success: function(res){
                $('#username_preview').val(res.username);
            }
        });

    });

});
</script>