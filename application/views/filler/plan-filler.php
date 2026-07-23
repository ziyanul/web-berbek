 <!-- Begin Page Content -->
 <div class="container-fluid">

 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
 		<h1 class="h3 mb-2 text-gray-800">Data Planning Produksi</h1>
 		<?php if($this->session->userdata('type')==1||$this->session->userdata('type')==2) {?><a href="<?= base_url('filler/tambahplan'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a><?php } ?>
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
                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th class="font-weight-bold align-middle text-center">Tanggal Produksi</th>
                            <th class="font-weight-bold align-middle text-center">Varian</th>
                            <th class="font-weight-bold align-middle text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                       <?php
                       $no = 1;
                       foreach ($data as $row) {
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= tanggal_indo($row->tgl); ?></td>
                            <td><?= $row->varian ?> - <?= $row->keterangan ?></td>
                            <td class="text-center align-middle" style="white-space: nowrap;">
                                <div class="d-flex justify-content-center flex-wrap" style="gap:6px;">

                                    <a href="<?= base_url('filler/detailplan/'.$row->uuid); ?>" 
                                     class="btn btn-sm btn-success shadow-sm"
                                     data-toggle="tooltip" 
                                     data-placement="top" 
                                     title="Detail">
                                     <i class="fa fa-info-circle"></i>
                                 </a>

                                 <a href="<?= base_url('filler/performance/'.$row->uuid); ?>" 
                                     class="btn btn-sm btn-info shadow-sm"
                                     data-toggle="tooltip" 
                                     data-placement="top" 
                                     title="Performance">
                                     <i class="fa fa-chart-line"></i>
                                 </a>

                                 <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2) { ?>
                                    <a href="<?= base_url('filler/editplan/'.$row->uuid); ?>" 
                                     class="btn btn-sm btn-warning shadow-sm"
                                     data-toggle="tooltip" 
                                     data-placement="top" 
                                     title="Edit">
                                     <i class="fa fa-edit text-white"></i>
                                 </a>

                                 <a href="<?= base_url('filler/hapusplan/'.$row->uuid); ?>" 
                                     class="btn btn-sm btn-danger shadow-sm"
                                     onclick="return confirm('Anda yakin ingin menghapus data ini?')"
                                     data-toggle="tooltip" 
                                     data-placement="top" 
                                     title="Hapus">
                                     <i class="fa fa-trash"></i>
                                 </a>
                             <?php } ?>

                         </div>
                     </td>
                 </tr>
                 <?php
                 $no++;
             } ?>
         </tbody>
     </table>
 </div>
</div>
</div>
</div>

            <!-- End of Main Content -->