<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h1 text-gray-800">Detail Cek Mesin/Batch</h1>
    
</div>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin Filler</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_fillerbatch/detail-'.$nav->planprod_uuid) ?>"> Pengecekan Mesin/Batch Filler</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Cek Mesin</li>
</ol>

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

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                    <tr>
                        <th rowspan="2" class="align-middle">Mesin</th>
                        <th rowspan="2" class="align-middle">Item</th>
                        <th colspan="2" class="align-middle text-center">CHECKLIST (✓)/BATCH</th>
                        <th rowspan="2" class="align-middle text-center">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="align-middle text-center">Ya</th>
                        <th class="align-middle text-center">Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $row) {
                        ?>
                        <tr>
                            <td><?= $row->mesin;?></td>
                            <td><?= $row->item;?></td>
                            <td class="align-middle text-center"><?= ($row->ceklist == 2) ? '<i class="fa fa-check fa-lg text-success"></i>': '-';?></td>
                            <td class="align-middle text-center"><?= ($row->ceklist == 0) ? '<i class="fa fa-times fa-lg text-danger"></i>': '-';?></td>
                            <td class="align-middle text-center"><?= $row->keterangan;?></td>
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