<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Permintaan Sparepart</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <?php
            $url = $this->uri->segment(2);
            ?>
            <li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_sparepart':'bahan_sparepart/'. $url) ?>"><i class="fas fa-arrow-left mr-2"></i>
            <?= ($this->uri->segment(2)=='tambah'?'Permintaan Sparepart':'Detail Permintaan Sparepart')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"> Tambah Permintaan Sparepart</li>
        </ol>
    </nav>
    <?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?= $this->session->flashdata('success_msg') ?>
    </div>
    <br>
    <?php endif ?>
    <?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
    <br>
    <?php endif ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('bahan_sparepart/tambah') ?>" method="post">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Nomor Reservasi</label>
                        <input type="text" name="no_reservasi"
                            class="form-control <?= form_error('no_reservasi') ? 'invalid' : '' ?>"
                            placeholder="Jumlah yang dipesan" value="<?= sprintf("%04d", ($urut)); ?>" readonly>
                        <div class="invalid-feedback <?= !empty(form_error('no_reservasi')) ? 'd-block' : ''; ?>">
                            <?= form_error('no_reservasi') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Area :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php foreach ($area as $row): ?>
                            <option value="<?= $row->uuid; ?>" <?= set_select('area', $row->uuid); ?>>
                                <?= $row->nama_area; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block' : ''; ?>">
                            <?= form_error('area') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Mesin :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                            <option disabled selected>Pilih Mesin</option>
                        </select>
                        <input type="hidden" name="mesin_name">
                        <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block' : ''; ?>">
                            <?= form_error('mesin') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Nama Sparepart :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('item') ? 'invalid' : '' ?>" name="item">
                            <option disabled selected>Pilih Sparepart</option>
                        </select>
                        <input type="hidden" name="item_name">
                        <div class="invalid-feedback <?= !empty(form_error('item')) ? 'd-block' : ''; ?>">
                            <?= form_error('item') ?>
                        </div>
                    </div>
                </div>

                <div class="row  mb-2">
                    <div class="col-sm-6">
                        <label class="form-label" for="qty_reservasi" id="qty-label"> Quantity Reservasi :<span
                                class="text-danger">*</span>
                        </label>
                        <input type="number" name="qty_reservasi"
                            class="form-control <?= form_error('qty_reservasi') ? 'invalid' : '' ?>"
                            placeholder="Jumlah yang dipesan" value="<?= set_value('qty_reservasi'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('qty_reservasi')) ? 'd-block' : ''; ?>">
                            <?= form_error('qty_reservasi') ?>
                        </div>
                    </div>
                </div>

                <div class="row  mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan :</label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block' : ''; ?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <?php
                        $url = $this->uri->segment(2);
                        ?>
                            <a href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_sparepart':'bahan_sparepart/'. $url) ?>" class="btn btn-md btn-danger">
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
    $('select[name="area"]').change(function() {
        var area_uuid = $(this).val();
        $.get('<?= base_url('part/get_mesin_by_area/');?>' + area_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';
            result.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val
                    .nama_mesin + '</option>';
            })

            $('select[name="mesin"]').html(elem);
        })
    })

    $('select[name="mesin"]').change(function() {
        var mesin_uuid = $(this).val();
        $.get('<?= base_url('mesin/get_mesin_name/');?>' + mesin_uuid, function(res) {
            var data = JSON.parse(res);
            $('input[name="mesin_name"]').val(data.nama_mesin);
        })
        $.get('<?= base_url('monitor/get_part_by_mesin/');?>' + mesin_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Sparepart</option>';
            result.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val
                    .nama_part + '</option>';
            })
            $('select[name="item"]').html(elem);
        })
        $('select[name="item"]').change(function() {
            var uuid = $(this).val();
            if (uuid) {
                $.get('<?= base_url('bahan_baku/get_part_name/'); ?>' + uuid,
                    function(res) {
                        var data = JSON.parse(res);
                        $('input[name="item_name"]').val(data.nama_part);

                        // Tambahkan satuan ke label Quantity Reservasi
                        var satuan = data.satuan || '-';
                        $('#qty-label').html(
                            'Quantity Reservasi (' + satuan +
                            ') :<span class="text-danger">*</span>'
                        );
                    });
            } else {
                // Reset ke tampilan awal jika tidak ada item yang dipilih
                resetQuantityLabel();
            }
        });

        // Fungsi untuk reset label Quantity Reservasi ke default
        function resetQuantityLabel() {
            $('#qty-label').html(
                'Quantity Reservasi :<span class="text-danger">*</span>'
            );
        }

        // Reset ke tampilan awal saat halaman di-reload
        $(window).on('load', function() {
            resetQuantityLabel();
        });
    });
});
</script>