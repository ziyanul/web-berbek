<div class="container-fluid">
    <!-- Page Heading -->
    <h3 class="h3 mb-2 text-gray-800">Tambah Reject Filler</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rj_filler') ?>"><i class="fas fa-arrow-left mr-2"></i>
            Data Reject Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Tambah </li>
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
            <form class="user" action="<?= base_url('rj_filler/tambah') ?>" method="post">

                <!-- Input Jam Mulai -->
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Varian :<span class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('planning_uuid') ? 'invalid' : '' ?>"
                                name="planning_uuid" id="varian_uuid">
                                <?php
                                foreach ($plan as $tp) { ?>
                                <option value="<?= $tp->uuid ?>"><?= $tp->varian_name ?> ( <?= $tp->format_tanggal ?> )</option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('planning_uuid')) ? 'd-block':'';?>">
                                <?= form_error('planning_uuid') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Input Jam Selesai -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Batch Ke :<span class="text-danger">*</span></label>
                            <!-- <select class="form-control" id="kode_batch" name="kode_batch" required>
                                <option disabled selected>Pilih Kode Batch</option>
                            </select> -->
                            <input type="number" name="kode_batch"
                            class="form-control <?= form_error('kode_batch') ? 'invalid' : '' ?>"
                            placeholder="Misal: 1, 2, 3, dst." value="<?= set_value('kode_batch'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('kode_batch')) ? 'd-block':'';?>">
                                <?= form_error('kode_batch') ?>
                            </div>
                            <div class="invalid-feedback <?= !empty(form_error('kode_batch')) ? 'd-block':'';?>">
                                <?= form_error('kode_batch') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Input Jumlah Kg -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Mesin :<span class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('mesin_uuid') ? 'invalid' : '' ?>"
                                name="mesin_uuid" id="mesin_uuid">
                                <option disabled selected>Pilih Mesin</option>
                                <?php foreach ($mesin as $row): ?>
                                    <option value="<?= $row->uuid; ?>"><?= $row->nama_mesin; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('mesin_uuid')) ? 'd-block':'';?>">
                                <?= form_error('mesin_uuid') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Input Jumlah Box -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Operator :<span class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('operator_uuid') ? 'invalid' : '' ?>"
                                name="operator_uuid" id="operator_uuid">
                                <option disabled selected>Pilih Operator</option>
                                <?php foreach ($operator as $row): ?>
                                    <option value="<?= $row->uuid; ?>"><?= $row->fullname; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('operator_uuid')) ? 'd-block':'';?>">
                                <?= form_error('operator_uuid') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Berat (Kg):<span class="text-danger">*</span></label>
                            <input type="number" name="berat" step="0.001"
                            class="form-control <?= form_error('berat') ? 'invalid' : '' ?>"
                            placeholder="berat KG" value="<?= set_value('berat'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('berat')) ? 'd-block':'';?>">
                                <?= form_error('berat') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Input Keterangan -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Keterangan :</label>
                            <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                                <?= form_error('keterangan') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan dan Batal -->
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mt-4 mb-4">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('rj_filler') ?>" class="btn btn-md btn-danger mt-4 mb-4">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#varian_uuid').change(function() {
            var varian_uuid = $(this).val();
            $('#kode_batch').html('<option disabled selected>Loading...</option>');

            $.ajax({
                url: "<?= base_url('rj_filler/get_kode_batch/') ?>" + varian_uuid,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#kode_batch').empty().append('<option disabled selected>Pilih Kode Batch</option>');
                    if (response.length > 0) {
                        $.each(response, function(index, item) {
                            $('#kode_batch').append('<option value="' + item.batch_ke + '">' + item.batch_ke + '</option>');
                        });
                    } else {
                        $('#kode_batch').append('<option disabled>Tidak ada data</option>');
                    }
                },
                error: function() {
                    $('#kode_batch').html('<option disabled>Terjadi kesalahan</option>');
                }
            });
        });
    });

</script>
