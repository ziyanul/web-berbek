<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Status Permintaan SparePart </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('monitor/pengajuan') ?>"><i class="fas fa-arrow-left mr-2"></i>Pengajuan Part</a></li>
        <li class="breadcrumb-item active" aria-current="page">Status</li>
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
        <form class="user" action="<?= base_url('monitor/status_part/'.$data->uuid) ?>" method="post">
            <div class="row mb-3 mb-sm-0">           
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold">Sparepart :</label>
                            <?= $data->part; ?><br>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold">Status</label><br>
                            <select class="form-control <?= form_error('status') ? 'invalid' : '' ?>" name="status">
                                <option selected disabled>Pilih Status</option>
                                <option value="6">diTolak</option>
                                <option value="1">diSetujui</option>
                                <?php if ($data->jenis == 1): ?>
                                <option value="2">Release Komdif</option>
                                <option value="3">Proses Pengiriman</option>
                                <?php elseif ($data->jenis == 2): ?>
                                   <option value="4">Proses Pembuatan</option>
                                   <?php endif; ?> 
                                <option value="5">diTerima</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold mt-3">Keterangan :</label>
                            <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" value="<?= set_value('keterangan'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                                <?= form_error('keterangan') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('monitor/pengajuan')?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                   
                </div>
                <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                    <h3 class="text-gray-800">Proses Perubahan Status "<?= $data->part; ?>"</h3>
                    <table class="table table-bordered">
                        <tr class="bg-info text-light">
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                        <?php
                        foreach ($status as $value) {
                            ?>
                            <tr>
                                <td><?= $value->tanggal;?></td>
                                <td><?= $value->username;?></td>
                                <td><?= $value->status;?></td>
                                <td><?= $value->keterangan;?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
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
</script>