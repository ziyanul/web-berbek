<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Data Mesin</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('mesin') ?>"><i class="fas fa-arrow-left"></i> Data Mesin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah</li>
      </ol>
    </nav>

  <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('mesin/edit/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area</label><br>
                        <?= $data->nama_area;?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Mesin <span class="text-danger">*</span></label>
                        <input type="text" name="mesin" class="form-control <?= form_error('mesin') ? 'invalid' : '' ?> " placeholder="" value="<?= $data->nama_mesin; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                            <?= form_error('mesin') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">ID Mesin <span class="text-danger">*</span></label>
                        <input type="text" name="mesin_id" class="form-control <?= form_error('mesin_id') ? 'invalid' : '' ?> " placeholder="" value="<?= $data->device_id; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('mesin_id')) ? 'd-block':'';?>">
                            <?= form_error('mesin_id') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">RH Terbaru <span class="text-danger">*</span></label>
                        <input type="text" name="rhupdate" class="form-control <?= form_error('rhupdate') ? 'invalid' : '' ?>" placeholder="" value="<?= $data->rh_update; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('rhupdate')) ? 'd-block':'';?>">
                            <?= form_error('rhupdate') ?>
                        </div>
                    </div>
           
                </div>
                <div class="row" >
                    <div class="col-sm-12">
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
        $('input[name="area_name"]').val($('select[name="area"]').val());
        $('select[name="area"]').change(function () {
            var val = $(this).val();
            $.get('<?= base_url('area/get_area_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="area_name"]').val(data.nama_area);
            })
        })
    })
</script>