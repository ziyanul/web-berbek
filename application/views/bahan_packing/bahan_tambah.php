<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Bahan Baku</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <?php
            $url = $this->uri->segment(2);
            ?>
            <li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_packing':'bahan_packing/'. $url) ?>"><i class="fas fa-arrow-left mr-2"></i>
            <?= ($this->uri->segment(2)=='tambah'?'Permintaan Bahan Baku Packing':'Detail Permintaan Bahan Baku Packing')?></a></li>
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
            <form class="user" action="<?= base_url('bahan_packing/tambah') ?>" method="post">
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <label class="form-label">Item Bahan :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('item_barang') ? 'invalid' : '' ?>"
                            name="item_barang">
                            <option disabled selected>Pilih Jenis</option>
                            <?php foreach ($jenis as $j): ?>
                            <option value="<?= $j->uuid; ?>" <?= set_select('item_barang', $j->uuid); ?>>
                                <?= $j->nama; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="item_name">
                        <div class="invalid-feedback <?= !empty(form_error('item_barang')) ? 'd-block' : ''; ?>">
                            <?= form_error('item_barang') ?>
                        </div>

                    </div>
                </div>

                <div class="row mb-2">
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
                            <a href="<?= base_url($this->uri->segment(2)=='tambah'?'bahan_packing':'bahan_packing/'. $url) ?>" class="btn btn-md btn-danger">
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
    // Ketika item bahan dipilih
    $('select[name="item_barang"]').change(function() {
        var uuid = $(this).val();
        if (uuid) {
            $.get('<?= base_url('bahan_packing/get_item_packing/'); ?>' + uuid, function(res) {
                var data = JSON.parse(res);
                $('input[name="item_name"]').val(data.nama);

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
</script>