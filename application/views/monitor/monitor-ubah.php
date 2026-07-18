<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Data Pengajuan Pergantian Sparepart </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('monitor/tpm') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Pengajuan Sparepart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('monitor/ubah/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label">Area :</label>
                        <b><?= $data->nama_area;?></b>
                        <input type="hidden" name="area" value="<?= $data->area_uuid ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">

                        <label class="form-label font-weight-bold">Nama Mesin</label>
                        <select class="form-control" name="mesin">
                            <option selected disabled>Pilih Mesin</option>
                            <?php
                            foreach ($mesin as $row) {
                              ?>
                              <option value="<?= $row->uuid;?>" <?=$data->mesin_uuid==$row->uuid?'selected':'';?>>
                                <?= $row->nama_mesin;?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="mesin_name">

                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-3 mb-sm-0">

                    <label class="form-label font-weight-bold mt-2">Nama Sparepart :</label>

                    <select class="form-control <?= form_error('part') ? 'invalid' : '' ?>" name="part">
                        <option value="<?= $data->part_uuid ?>" selected>
                            <?= $data->nama_part ?>
                        </option>
                    </select>

                    <input type="hidden" name="part_name" value="<?= $data->nama_part ?>">

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
            <div class="row">
               <div class="col-sm-6 mb-3">
                <label class="form-label">Pilih Waktu Terjadwal: </label>
                <select class="form-control <?= form_error('jadwal') ? 'invalid' : '' ?>" name="jadwal">
                    <option selected disabled>berdasarkan RH, plan produksi atau counter?</option>
                    <option value="0" <?= ($data->jadwal == 0) ? 'selected' : ''; ?>>RH Harian</option>
                    <option value="1" <?= ($data->jadwal == 1) ? 'selected' : ''; ?>>Plan Produksi</option>
                    <option value="2" <?= ($data->jadwal == 2) ? 'selected' : ''; ?>>Counter Filler</option>
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
$(document).ready(function() {

    var first_part_uuid = "<?= $data->part_uuid ?>";

    function data_part_lifetime_harga(lifetime, harga) {
        $('.part_lifetime').html(lifetime);
        $('input[name="lifetime_name"]').val(lifetime);

        $('.part_harga').html(harga);
        $('input[name="harga_name"]').val(harga);
    }

    // tampilkan detail part saat halaman pertama
    load_part_detail(first_part_uuid);

    // set area name
    $('input[name="area_name"]').val($('select[name="area"]').val());

    $('select[name="area"]').change(function() {

        var area_uuid = $(this).val();

        $.get('<?= base_url('area/get_area_name/');?>'+area_uuid, function(res){
            var data = JSON.parse(res);
            $('input[name="area_name"]').val(data.nama_area);
        });

        load_mesin(area_uuid);

        data_part_lifetime_harga('', '');

    });

    function load_mesin(area_uuid){

        $.get('<?= base_url('part/get_mesin_by_area/');?>'+area_uuid, function(res){

            var result = JSON.parse(res);
            var elem = '<option disabled>Pilih Mesin</option>';

            result.forEach(function(val){

                var selected = val.uuid == "<?= $data->mesin_uuid ?>" ? 'selected' : '';

                elem += '<option value="'+val.uuid+'" '+selected+'>'+val.nama_mesin+'</option>';

            });

            $('select[name="mesin"]').html(elem);

            load_part("<?= $data->mesin_uuid ?>");

        });

    }

    function load_part(mesin_uuid){

        $.get('<?= base_url('monitor/get_part_by_mesin/');?>'+mesin_uuid, function(res){

            var result = JSON.parse(res);
            var elem = '<option disabled>Pilih Sparepart</option>';

            result.forEach(function(val){

                var selected = val.uuid == "<?= $data->part_uuid ?>" ? 'selected' : '';

                elem += '<option value="'+val.uuid+'" '+selected+'>'+val.nama_part+'</option>';

            });

            $('select[name="part"]').html(elem);

        });

    }

    function load_part_detail(part_uuid){

        $.get('<?= base_url('part/get_part_name/');?>'+part_uuid, function(res){

            var part = JSON.parse(res);

            $('input[name="part_name"]').val(part.nama_part);

            data_part_lifetime_harga(part.lifetime, part.harga);

        });

    }

    // mesin change
    $('select[name="mesin"]').change(function(){

        var mesin_uuid = $(this).val();

        $.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid, function(res){

            var data = JSON.parse(res);

            $('input[name="mesin_name"]').val(data.nama_mesin);

        });

        $.get('<?= base_url('monitor/get_part_by_mesin/');?>'+mesin_uuid, function(res){

            var result = JSON.parse(res);

            var elem = '<option disabled selected>Pilih Sparepart</option>';

            result.forEach(function(val){

                elem += '<option value="'+val.uuid+'">'+val.nama_part+'</option>';

            });

            $('select[name="part"]').html(elem);

        });

        data_part_lifetime_harga('', '');

    });

    // part change
    $('select[name="part"]').change(function(){

        var part_uuid = $(this).val();

        load_part_detail(part_uuid);

    });

    // load pertama
    var area_uuid = $('input[name="area"]').val();
    load_mesin(area_uuid);

});
</script>