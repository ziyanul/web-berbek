 <!-- Begin Page Content -->
 <div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <!-- Page Heading -->
         <h3 class="h3 mb-2 text-gray-800">Data Penggunaan Formula dan Rework</h3>
     </div>
     <?php if ($this->session->flashdata('success_msg')): ?>
         <div class="alert alert-success text-center">
             <i class="fas fa-check"></i>
             <?= $this->session->flashdata('success_msg') ?>
         </div>
         <br>
     <?php endif ?>
     <?php if ($this->session->flashdata('error_msg')): ?>
         <div class="alert alert-danger  text-center">
             <i class="fas fa-times"></i>
             <?= $this->session->flashdata('error_msg') ?>
         </div>
         <br>
     <?php endif ?>
     <!-- DataTales Example -->
     <div class="card shadow mb-0">
         <div class="card-body">
             <div class="table-responsive">
                 <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                     <thead class="table bg-info text-light">
                         <tr>
                             <th>No.</th>
                             <th>Tanggal Produksi</th>
                             <th>Varian</th>
                             <th>Formula (KG)</th>
                             <th>Rework (KG)</th>
                             <th>Total (KG)</th>
                             <th>Action</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $no = 1;
                            foreach ($data as $row) {
                            ?>
                             <tr>
                                 <td width="1"><?= $no; ?></td>
                                 <td><?= tanggal_indo($row->tanggal); ?></td>
                                 <td><?= $row->varian; ?></td>
                                 <td><?= number_format(($row->formula_kg ?? 0), 2, ',', '.'); ?></td>
                                 <td><?= number_format(($row->rework_kg ?? 0), 2, ',', '.'); ?></td>
                                 <td><?= number_format(($row->formula_kg ?? 0) + ($row->rework_kg ?? 0), 2, ',', '.'); ?></td>
                                 <td>
                                     <a href="<?= base_url('mpusage/input/' . $row->uuid_mp); ?>" class="btn btn-md btn-primary btn-block shadow-sm mr-2" style="flex: 1;"><i class="fas fa-plus text-light"></i> Formula / Rework</a>
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