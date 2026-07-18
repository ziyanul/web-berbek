<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h1 mb-2 text-gray-800">Data CheckList Kebersihan Sanitasi</h1>
    <a href="sanitasi/tambahchek" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Pengecheckan</a>
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
                    <th class="font-weight-bold">Area</th>
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
                        
                        <td><?= $row->tgl; ?></td>
                        <td><?= $row->area; ?></td>
                      
                        <td>
                            <a href="<?= base_url('sanitasi/detail/' . $row->area_uuid . '/' . $row->tanggal); ?>" class="btn btn-md btn-success shadow-sm btn-block"><i class="fas fa-info mr-2"></i>Detail</a>
                           
                            <a href="<?= base_url('sanitasi/form/' . $row->area_uuid . '/' . $row->tanggal); ?>" target ="blank" class="btn btn-md btn-info shadow-sm btn-block"><i class="fas fa-print mr-2"></i>Form Checklist Sanitasi</a>
                            <a href="<?= base_url('sanitasi/formchemical/' . $row->tanggal); ?>" target ="blank" class="btn btn-md btn-info shadow-sm btn-block"><i class="fas fa-print mr-2"></i>Form Pemakaian Chemical</a>
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