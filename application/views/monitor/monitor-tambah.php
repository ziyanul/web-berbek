<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Pengajuan Pergantian Sparepart </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('monitor/tpm') ?>"><i class="fas fa-arrow-left mr-2"></i> Pengajuan Sparepart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
   
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('monitor/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'tambah/') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label font-weight-bold">Nama Area :</label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php
                            foreach ($area as $row) { ?>
                                <option value="<?= $row->uuid;?>" <?= set_select('area', $row->uuid);?>><?= $row->nama_area;?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                        <?= form_error('area') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label font-weight-bold">Nama Mesin :</label>
                    <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                        <option disabled selected>Pilih Mesin</option>
                    </select>
                    <input type="hidden" name="mesin_name">
                    <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                        <?= form_error('mesin') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label font-weight-bold mt-2">Nama Sparepart :</label>
                    <select class="form-control <?= form_error('part') ? 'invalid' : '' ?>" name="part">
                        <option disabled selected>Pilih Sparepart</option>
                    </select>
                    <input type="hidden" name="part_name">
                    <div class="invalid-feedback <?= !empty(form_error('part')) ? 'd-block':'';?>">
                        <?= form_error('part') ?>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <label class="form-label font-weight-bold mt-2">Lifetime</label> : <span class="part_lifetime">-</span>
                    <input type="hidden" name="lifetime_name">
                </div>   
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <label class="form-label font-weight-bold mt-2">Harga</label> : <span class="part_harga">-</span>
                    <input type="hidden" name="harga_name">
                </div>  
            </div>

            <div class="row mt-3">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label font-weight-bold">Pilih Waktu Terjadwal: </label>
                    <select class="form-control <?= form_error('jadwal') ? 'invalid' : '' ?>" name="jadwal">
                        <option selected disabled>berdasarkan tanggal / plan produksi / counter?</option>
                        <option value="0" <?= set_select('jadwal', 0);?>>Harian</option>
                        <option value="1" <?= set_select('jadwal', 1);?>>Plan Produksi</option>
                        <option value="2" <?= set_select('jadwal', 2);?>>Counter Filler</option>
                    </select>
                    <div class="invalid-feedback <?= !empty(form_error('jadwal')) ? 'd-block':'';?>">
                        <?= form_error('jadwal') ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('monitor/tpm') ?>" class="btn btn-md btn-danger">
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

        function data_part_lifetime_harga(lifetime, harga) {
         $('.part_lifetime').html(lifetime);
         $('input[name="lifetime_name"]').val(lifetime);

         $('.part_harga').html(harga);
         $('input[name="harga_name"]').val(harga);
     }

     $('select[name="area"]').change(function () {
        var area_uuid = $(this).val();
        $.get('<?= base_url('part/get_mesin_by_area/');?>'+area_uuid, function (res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';
            result.forEach(function (val) {
                elem += '<option value="'+val.uuid+'">'+val.nama_mesin+'</option>';
            })

            $('select[name="mesin"]').html(elem);
        })

        data_part_lifetime_harga('', '');


    })

     $('select[name="mesin"]').change(function () {
        var mesin_uuid = $(this).val();
        $.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid, function (res) {
            var data = JSON.parse(res);
            $('input[name="mesin_name"]').val(data.nama_mesin);
        })


        $.get('<?= base_url('monitor/get_part_by_mesin/');?>'+mesin_uuid, function (res){
            var result = JSON.parse(res);

            var elem = '<option disabled selected>Pilih Sparepart</option>';
            result.forEach(function (val) {
                elem += '<option value="'+val.uuid+'">'+val.nama_part+'</option>';
            })

            $('select[name="part"]').html(elem);
        })

        data_part_lifetime_harga('', '');

    })


     $('select[name="part"]').change(function () {
        var val = $(this).val();
        $.get('<?= base_url('part/get_part_name/');?>'+val, function (res) {
            var part = JSON.parse(res);
            $('input[name="part_name"]').val(part.nama_part);

            data_part_lifetime_harga(part.lifetime, part.harga);
        })
    })

 })
</script>


