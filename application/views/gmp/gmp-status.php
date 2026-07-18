<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Perubahan Status ISO/TS</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('gmp') ?>"><i class="fas fa-arrow-left mr-2"></i>Monitoring ISO/TS</a></li>
        <li class="breadcrumb-item active" aria-current="page">ACC</li>
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

        <div class="row">
            <div class="col">
                <form class="user" action="<?= base_url('gmp/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'status/'.$data->uuid) ?>" method="post">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">Kegiatan :</label>
                            <?= $data->kegiatan; ?><br>
                           
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">Status <span class="text-danger">*</span></label><br>
                            <select class="form-control <?= form_error('status') ? 'invalid' : '' ?>" name="status" id="status">
                                <option selected disabled>Pilih Status</option>
                                
                                <option value="1" <?= set_select('status', 1);?>>Sesuai</option>
                                <option value="2" <?= set_select('status', 2);?>>Pembersihan Ulang</option>

                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('status')) ? 'd-block':'';?>">
                                <?= form_error('status') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Keterangan</label>
                           <input type="text" name="catatan" class="form-control <?= form_error('catatan') ? 'invalid' : '' ?> " placeholder="beri catatan penting yaaa" value="<?= set_value('catatan'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block':'';?>">
                            <?= form_error('catatan') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url($this->uri->segment(2)=='tpm'?'gmp/tpm':'gmp') ?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
                <h3 class="text-gray-800">Proses Perubahan Status "<?= $data->kegiatan; ?>"</h3>
                <table class="table table-bordered">
                    <tr class="bg-info text-light">
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                    <?php
                    foreach ($status as $value) {
                        ?>
                        <tr>
                            <td><?= $value->created_at;?></td>
                            <td><?= $value->status_gmp;?></td>
                            <td><?= $value->catatan;?></td>
                        </tr>
                    <?php } ?>

                </table>

            </div>
        </div>




    </div>
</div>
</div>
<!-- 
<script>
    $(document).ready(function () {
        $('select[name="mesin"]').change(function () {
            var val = $(this).val();
            $.get('<?= base_url('mesin/get_mesin_name/');?>'+val, function (res) {
                var data = JSON.parse(res);
                $('input[name="mesin_name"]').val(data.nama_mesin);
            })
        })
    })
</script> -->

 