 <!-- Begin Page Content -->

 <style>
    .table td, .table th{
        vertical-align: middle;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Pengajuan Pergantian Sparepart</h1>
        <a href="<?= base_url ('monitor/tpm/tambah') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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
    <div class="card shadow mb-0">
        <div class="card-body">
            <div style="overflow-x:auto;">
                <div class="table-responsive overflow-visible">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="bg-info text-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                <th>Nama Mesin</th>
                                <th>Nama Part</th>
                                <th class="text-center">Lifetime</th>
                                <th class="text-center">Status Pengajuan</th>
                                <th class="text-center">Approval</th>
                                <th class="text-center">Action</th>    
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td width="1"><?= $no; ?></td>
                                    <td><?= $row->tanggal;?></td>
                                    <td><?= $row->nama_mesin;?></td>
                                    <td><?= $row->nama_part;?></td>
                                    <td><?= $row->lifetime;?></td>
                                    <td>
                                        <?php if ($row->status == 0 && $row->nama_pelaksana == NULL): ?>
                                            <a class="btn btn-sm btn-block btn-warning" href="<?= base_url('monitor/tpm/tindakan/'.$row->uuid); ?>">
                                                <i class="fas fa-tools text-light"></i> Tindakan
                                            </a>
                                        <?php elseif ($row->status == 0): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif ($row->status == 1): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php elseif ($row->status == 2): ?>
                                            <span class="badge badge-secondary">History</span>
                                        <?php elseif ($row->status == 3): ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row->status == 0 && $row->nama_pelaksana != NULL): ?>
                                            <a href="<?= site_url('monitor/approve/'.$row->uuid) ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('ACC pergantian part ini?')">
                                               <i class="fas fa-check"></i>
                                           </a>

                                           <a href="<?= site_url('monitor/reject/'.$row->uuid) ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Reject pergantian part ini?')">
                                               <i class="fas fa-times"></i>
                                           </a>
                                       <?php endif; ?>
                                   </td>
                                   <td class="text-center align-middle" style="white-space: nowrap;">
                                    

                                        <a href="<?= base_url('monitor/detailcek/'.$row->uuid); ?>" 
                                           class="btn btn-sm btn-success shadow-sm"
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Detail">
                                           <i class="fa fa-info-circle"></i>
                                       </a>

                                       <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')== 2) { ?>
                                        <a href="<?= base_url('monitor/ubah/'.$row->uuid); ?>" 
                                           class="btn btn-sm btn-warning shadow-sm"
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Edit">
                                           <i class="fa fa-edit text-white"></i>
                                       </a>

                                       <a href="<?= base_url('monitor/delete_kegiatan/'.$row->uuid); ?>" 
                                           class="btn btn-sm btn-danger shadow-sm"
                                           onclick="return confirm('Anda yakin ingin menghapus data ini?')"
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Hapus">
                                           <i class="fa fa-trash"></i>
                                       </a>
                                   <?php } ?>

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

</div>
<!-- /.container-fluid -->

            <!-- End of Main Content -->