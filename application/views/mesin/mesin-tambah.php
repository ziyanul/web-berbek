<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Mesin</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('mesin') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Mesin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('mesin/tambah') ?>" method="post">
         
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                        <option disabled selected>Pilih Area</option>
                        <?php
                        foreach ($area as $row) {
                            ?>
                            <option value="<?= $row->uuid;?>" <?= set_select('area', $row->uuid);?>><?= $row->nama_area;?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="area_name">
                    <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                        <?= form_error('area') ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Mesin <span class="text-danger">*</span></label>
                    <input type="text" name="mesin" class="form-control <?= form_error('mesin') ? 'invalid' : '' ?> " placeholder="Masukkan Nama Mesin" value="<?= set_value('mesin'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                        <?= form_error('mesin') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">RH Terbaru <span class="text-danger">*</span></label>
                    <input type="text" name="rhupdate" class="form-control <?= form_error('rhupdate') ? 'invalid' : '' ?>" placeholder="Masukkan RH Terbaru" value="<?= set_value('rhupdate'); ?>">
                    <div class="invalid-feedback <?= !empty(form_error('rhupdate')) ? 'd-block':'';?>">
                        <?= form_error('rhupdate') ?>
                    </div>
                </div>
            </div>
            
            
            
            <div class="row" >
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('mesin') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


</div>

<script>
    $(document).ready(function () {
        $('select[name="area"]').change(function () {
            var val = $(this).val();
            $.get('<?= base_url('area/get_area_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="area_name"]').val(data.nama_area);
            })
        })
    })
</script>