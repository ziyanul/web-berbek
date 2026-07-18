<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Bahan Baku</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php
            $url = $this->uri->segment(2);
            ?>
            <li class="breadcrumb-item"><a
                    href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_mp':'bahan_mp/'. $url) ?>"><i
                        class="fas fa-arrow-left mr-2"></i>
                    <?= ($this->uri->segment(2)=='tambah'?'Permintaan Bahan Baku MP':'Detail Permintaan Bahan Baku MP')?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"> Tambah Bahan Baku</li>
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
            <form class="user" action="<?= base_url('bahan_mp/tambah') ?>" method="post">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Nomor Reservasi</label>
                        <input type="text" name="no_reservasi"
                            class="form-control <?= form_error('no_reservasi') ? 'invalid' : '' ?>"
                            value="<?= sprintf("%04d", ( $urut)); ?>" readonly>
                        <div class="invalid-feedback <?= !empty(form_error('no_reservasi')) ? 'd-block':'';?>">
                            <?= form_error('no_reservasi') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Jenis Barang :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('jenis') ? 'invalid' : '' ?>" name="jenis"
                            id="jenis">
                            <option selected disabled>Pilih Jenis Barang</option>
                            <option value="1" <?= set_select('jenis', 1); ?>>Raw Meat</option>
                            <option value="2" <?= set_select('jenis', 2); ?>>SPRE</option>
                            <option value="3" <?= set_select('jenis', 3); ?>>MIGOR</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('jenis')) ? 'd-block':'';?>">
                            <?= form_error('jenis') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Item Barang :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('item_barang') ? 'is-invalid' : '' ?>"
                            name="item_barang" id="item_barang">
                            <option selected disabled>Pilih Item Barang</option>
                        </select>
                        <input type="hidden" name="item_name">
                        <div class="invalid-feedback <?= !empty(form_error('item_barang')) ? 'd-block':'';?>">
                            <?= form_error('item_barang') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label" for="qty_reservasi">Quantity Reservasi :<span
                                class="text-danger">*</span></label>
                        <input type="number" name="qty_reservasi"
                            class="form-control <?= form_error('qty_reservasi') ? 'invalid' : '' ?>"
                            placeholder="Jumlah yang dipesan" value="<?= set_value('qty_reservasi'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('qty_reservasi')) ? 'd-block':'';?>">
                            <?= form_error('qty_reservasi') ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan :</label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
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
                        <a href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_mp':'bahan_mp/'. $url) ?>"
                            class="btn btn-md btn-danger">
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
    $('#jenis').on('change', function() {
        var jenis_uuid = $(this).val();
        $.ajax({
            url: '<?= site_url('bahan_mp/get_item_by_jenis') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                jenis: jenis_uuid
            },
            success: function(response) {
                console.log(response);
                var options = '<option selected disabled>Pilih Item Barang</option>';
                $.each(response, function(index, item) {
                    options += '<option value="' + item.uuid +
                        '" data-satuan="' + item.satuan + '">' + item.nama +
                        '</option>';
                });
                $('#item_barang').html(options).prop('disabled', false);

                // Hapus satuan pada label jika user mengganti jenis
                $('label[for="qty_reservasi"]').html(
                    'Quantity Reservasi :<span class="text-danger">*</span>');
            }
        });
    });

    $('#item_barang').on('change', function() {
        var selectedText = $('#item_barang option:selected')
            .text(); // Ambil teks nama item dari dropdown
        $('input[name="item_name"]').val(selectedText);
        var satuan = $('#item_barang option:selected').data('satuan'); // Ambil satuan dari item barang
        if (satuan) {
            $('label[for="qty_reservasi"]').html('Quantity Reservasi (' + satuan +
                ') :<span class="text-danger">*</span>');
        } else {
            $('label[for="qty_reservasi"]').html(
                'Quantity Reservasi :<span class="text-danger">*</span>');
        }
    });

});
</script>