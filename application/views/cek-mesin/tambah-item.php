<div class="container-fluid">
<style>
.btn-icon-round{
    width:38px;
    height:38px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:0;
    box-shadow:0 2px 6px rgba(0,0,0,.12);
}

.action-btn{
    display:flex;
    gap:6px;
    margin-left:8px;
}

.btn-icon-round{
    transition:.2s ease;
}
.btn-icon-round:hover{
    transform:scale(1.08);
}
</style>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Item Pengecekan Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin/dataitem') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Item</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
            <form class="user" action="<?= base_url('cekmesin/tambahitem') ?>" method="post">
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

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                            <option disabled selected>Pilih mesin</option>
                        </select>
                        <input type="hidden" name="mesin_name">
                        <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block':'';?>">
                            <?= form_error('mesin') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <label class="form-label">
                            Jenis Kegiatan <span class="text-danger">*</span>
                        </label>

                        <div id="kegiatan-wrapper">
                            <div class="input-group mb-2 kegiatan-row">
                                <input type="text"
                                name="kegiatan[]"
                                class="form-control"
                                placeholder="Masukkan kegiatan">

                                <div class="input-group-append action-btn"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('cekmesin/dataitem') ?>" class="btn btn-md btn-danger">
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

          $.get('<?= base_url('am/get_mesin_by_area/');?>' + area_uuid, function(res) {
            var result = JSON.parse(res);
            var elem = '<option disabled selected>Pilih Mesin</option>';
            result.forEach(function(val) {
                elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
            })
            $('select[name="mesin"]').html(elem);
        })
      })
    })
</script>


<script>
$(document).ready(function () {

    function refreshButtons() {
        let total = $('.kegiatan-row').length;

        $('.kegiatan-row').each(function(index) {
            let isLast = index === total - 1;
            let btnHtml = '';

            if (isLast) {
                btnHtml += `
                    <button type="button"
                            class="btn btn-success btn-icon-round btn-add"
                            title="Tambah kegiatan">
                        <i class="fa fa-plus"></i>
                    </button>
                `;
            }

            if (total > 1) {
                btnHtml += `
                    <button type="button"
                            class="btn btn-danger btn-icon-round btn-remove"
                            title="Hapus kegiatan">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
            }

            $(this).find('.action-btn').html(btnHtml);
        });
    }

    // Tambah
    $(document).on('click', '.btn-add', function () {
        $('#kegiatan-wrapper').append(`
            <div class="input-group mb-2 kegiatan-row">
                <input type="text"
                       name="kegiatan[]"
                       class="form-control"
                       placeholder="Masukkan kegiatan">
                <div class="input-group-append action-btn"></div>
            </div>
        `);

        refreshButtons();

        $('#kegiatan-wrapper .kegiatan-row:last input').focus();
    });

    // Hapus
    $(document).on('click', '.btn-remove', function () {
        $(this).closest('.kegiatan-row').remove();
        refreshButtons();
    });

    refreshButtons();

});
</script>