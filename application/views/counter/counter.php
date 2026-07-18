<!-- Begin Page Content -->
<div class="container-fluid">
 <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Detail Counter Batch</h1>
    </div>
</div>

<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter') ?>">
                    <i class="fas fa-arrow-left"></i> Form Counter
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter/detail/' . $counter[0]->plan_uuid) ?>">
                   Data Batch
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                 <tr>
                    <th width="1" class="font-weight-bold">No</th>
                    <th class="font-weight-bold">Mesin</th>
                    <th class="font-weight-bold">Counter</th>
                    <th class="font-weight-bold">Action</th>
                </tr> 
            </thead>

            <tbody>
                <?php
                $no = 1;
                foreach ($counter as $row) {
                    ?>
                    <tr>
                     <td><?= $no;?></td>
                     <td><?= $row->nama_mesin;?></td>
                     <td><?= $row->counter;?></td>
                     <td>
                        <a href="<?= base_url('counter/editcounter/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-sm"><i class="fa fa-book fa-sm text-white mr-2"></i>Edit</a>
                        

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
