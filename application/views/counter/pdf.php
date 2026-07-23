<!-- Begin Page Content -->
<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Counter Filler</h1>
   <!--  <a href="counter/tambah" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a> -->
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

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr>
                    <th width="1" class="font-weight-bold">No</th>
                    <th class="font-weight-bold">Tanggal</th>
                    <th class="font-weight-bold">Varian</th>
                    
                    <th class="font-weight-bold">Action</th>
                </tr> 
            </thead>

            <tbody>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                       <td><?= $no;?></td>
                       <td><?= !empty($row->tanggal_produksi) ? tanggal_indo($row->tanggal_produksi) : ''; ?></td>
                       <td><?= $row->varian;?> ( <?= $row->keterangan;?> )</td>
                       <td>
                        <a href="<?= base_url('counter/document/'.$row->tanggal_produksi .'/'.$row->varian_uuid); ?>" target="_blank" class="btn btn-md btn-success shadow-sm"><i class="fa fa-print fa-sm text-white mr-2"></i>Form</a>
                        
                        
                        <!-- <a href="<?= base_url('counter/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-plus fa-sm text-white mr-2"></i>Edit</a>
                        <a href="<?= base_url('counter/delete_form/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            <i class="fa fa-plus fa-sm text-white mr-2"></i>Hapus
                        </a> -->

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
