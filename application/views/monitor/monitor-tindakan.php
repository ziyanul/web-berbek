<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pelaksanaan Pergantian Sparepart </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : ($this->uri->segment(2) == 'history' ? 'monitor/history' : 'monitor')) ?>"><i class="fas fa-arrow-left mr-2"></i> Data Sparepart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('monitor/tindakan/'.$data->uuid) ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label">Area :</label>
                        <b><?= $data->nama_area;?></b>
                    </div>

                    <div class="col-sm-6 mb-2">
                        <label class="form-label">Mesin :</label>
                        <b><?= $data->nama_mesin;?></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label">Spare Part :</label>
                        <b><?= $data->nama_part;?></b>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label">RH Awal :</label>
                        <b><?= $data->rh_awal;?></b>
                    </div>
                </div>
                <div class="row">
                   <div class="col-sm-6 mb-3">
                    <label class="form-label">Waktu Terjadwal : </label>
                    <b><?= $data->jadwal;?></b>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Lifetime <span class="text-danger">*</span></label>
                    <input type="text" name="lifetime" class="form-control <?= form_error('lifetime') ? 'invalid' : '' ?> " placeholder="" value="<?= $data->lifetime; ?>" readonly>
                    <div class="invalid-feedback <?= !empty(form_error('lifetime')) ? 'd-block':'';?>">
                        <?= form_error('lifetime') ?>
                    </div>
                </div>



            </div>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="form-label">Pelaksana <span class="text-danger">*</span></label>
                    <input type="text" name="pelaksana" class="form-control <?= form_error('pelaksana') ? 'invalid' : '' ?> " placeholder="Masukkan yang Mengganti Part" value="<?= $data->nama_pelaksana; ?>">
                    <div class="invalid-feedback <?= !empty(form_error('pelaksana')) ? 'd-block':'';?>">
                        <?= form_error('pelaksana') ?>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <label class="form-label">Catatan <span class="text-danger">*</span></label>
                    <input type="text" name="catatan" class="form-control <?= form_error('catatan') ? 'invalid' : '' ?> " placeholder="Masukkan Catatan" value="<?= $data->catatan; ?>">
                    <div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block':'';?>">
                        <?= form_error('catatan') ?>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : ($this->uri->segment(2) == 'history' ? 'monitor/history' : 'monitor')) ?>" class="btn btn-md btn-danger">
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
        $('input[name="area_name"]').val($('select[name="area"]').val());

        $('select[name="area"]').change(function() {
            var val = $(this).val();
            $.get('<?= base_url('area/get_area_name/');?>' + val,
                function(res) {
                    var data = JSON.parse(res);
                    $('input[name="area_name"]').val(data.nama_area);
                })
        })


    // function data_part_lifetime_harga(lifetime, harga) {
    //     $('.part_lifetime').html(lifetime);
    //     $('input[name="lifetime_name"]').val(lifetime);

    //     $('.part_harga').html(harga);
    //     $('input[name="harga_name"]').val(harga);
    // }

    $('select[name="area"]').change(function () {
        var area_uuid = $(this).val(); // value yang di pilih atau selected

        $.get('<?= base_url('part/get_mesin_by_area/');?>'+area_uuid, function (res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';
            result.forEach(function (val) {
                elem += '<option value="'+val.uuid+'">'+val.nama_mesin+'</option>';
            })

            $('select[name="mesin"]').html(elem);
        })

        // data_part_lifetime_harga('', '');


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

        // data_part_lifetime_harga('', '');

    })


    $('select[name="part"]').change(function () {
        var val = $(this).val();
        $.get('<?= base_url('part/get_part_name/');?>'+val, function (res) {
            var part = JSON.parse(res);
            $('input[name="part_name"]').val(part.nama_part);

            // data_part_lifetime_harga(part.lifetime, part.harga);
        })
    })
})  
</script>