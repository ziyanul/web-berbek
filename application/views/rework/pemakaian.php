<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h1 mb-2 text-gray-800">Data Pemakaian Rework</h1>
    <a href="<?= base_url('rework/tambahpakai/'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
</div>
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
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr>
                    <th width="1" class="font-weight-bold">No</th>
                    <th class="font-weight-bold">Tanggal</th>
                    <th class="font-weight-bold">Action</th>
                </tr> 
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row->tanggal; ?></td>
                        <td>                           
                            <a href="<?= base_url('rework/detail/' . $row->tanggal_kode); ?>" class="btn btn-md btn-success shadow-sm btn-block">Detail</a>
                            <a href="<?= base_url('rework/form_rework/' . $row->tanggal_kode); ?>" class="btn btn-md btn-info shadow-sm btn-block" target="_blank">Form</a>
                        </td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>