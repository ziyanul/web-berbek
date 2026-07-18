<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Status Pergantian Part </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('monitor') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Part</a></li>
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
        <form class="user" action="<?= base_url('monitor/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'status/'.$data->monitor_uuid) ?>" method="post">
            <div class="row mb-3 mb-sm-0">           
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label class="form-label">Mesin :</label>
                    <?= $data->nama_mesin; ?><br>
                    
                    <label class="form-label">Sparepart :</label>
                    <?= $data->nama_part; ?><br>
                    
                    <label class="form-label">Status</label><br>
                    <select class="form-control <?= form_error('status') ? 'invalid' : '' ?>" name="status">
                        <option selected disabled>Pilih Status</option>
                        <!-- <option value="0">Pengajuan</option>
                        <option value="1">Setuju</option>
                        <option value="2">Tolak</option> -->
                        <option value="3">Sesuai</option>
                        <option value="4">Tidak Sesuai</option>
                    </select>
                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : ($this->uri->segment(2) == 'history' ? 'monitor/history' : 'monitor')) ?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                    <div class="invalid-feedback <?= !empty(form_error('status')) ? 'd-block':'';?>">
                        <?= form_error('status') ?>
                    </div>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <h3 class="text-gray-800">Proses Perubahan Status "<?= $data->nama_part; ?>"</h3>
                    <table class="table table-bordered">
                        <tr class="bg-info text-light">
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                        <?php
                        foreach ($status as $value) {
                            ?>
                            <tr>
                                <td><?= $value->created_at;?></td>
                                <td><?= $value->status_part;?></td>
                            </tr>
                        <?php } ?>
                        
                    </table>

                </div>
            </div>


            
        </form>

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