<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Lokasi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('sub_area') ?>"><i class="fas fa-arrow-left mr-2"></i>Sub Area</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('sub_area/tambah') ?>" method="post">
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
                    <div class="col-sm-6">
                        <label class="form-label">Sub Area <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control <?= form_error('lokasi') ? 'invalid' : '' ?>" placeholder="Masukkan Lokasi" value="<?= set_value('lokasi'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('lokasi')) ? 'd-block':'';?>">
                            <?= form_error('lokasi') ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('area') ?>" class="btn btn-md btn-danger">
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
            $.get('<?= base_url('gmp/get_area_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="area_name"]').val(data.nama_area);
            })
        })
    })
</script>