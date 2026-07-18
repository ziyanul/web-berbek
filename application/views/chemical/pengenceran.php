<!-- Begin Page Content -->
<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h2 class="h2 mb-2 text-gray-800">Data Form Pelarutan Chemical</h2>
        <!-- <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="tambahmaster" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah Nama Chemical</a>
            <?php }?> -->
    </div>
    
    <!-- DataTales Example -->
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
                <a href="<?= base_url('chemical/formpengenceran/' . $row->tanggal); ?>" class="btn btn-md btn-success shadow-sm btn-block" target="_blank"><i class="fa fa-info fa-sm text-white mr-2"></i>Form</a>
                
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