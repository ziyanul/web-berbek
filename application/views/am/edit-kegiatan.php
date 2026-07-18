<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Kegiatan AM</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('am/data') ?>"><i class="fas fa-arrow-left"></i> Data Iso/Ts</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah</li>
      </ol>
    </nav>

  <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('am/editkegiatan/'.$kegiatan->uuid) ?>" method="post">
               <!--  <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area</label><br>
                        <?= $area->area;?>
                    </div>
                </div> -->
                <!-- <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Mesin</label>
                        <input type="text" name="mesin" class="form-control <?= form_error('mesin') ? 'invalid' : '' ?> " placeholder="" value="<?= $mesin->mesin; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                            <?= form_error('mesin') ?>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="kegiatan" class="form-control <?= form_error('kegiatan') ? 'invalid' : '' ?> " placeholder="" value="<?= $kegiatan->kegiatan; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('kegiatan')) ? 'd-block':'';?>">
                            <?= form_error('kegiatan') ?>
                        </div>
                    </div>
                </div>
                
                <div class="row" >
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('am/data') ?>" class="btn btn-md btn-danger">
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