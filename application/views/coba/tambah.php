<!-- Begin Page Content -->
<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h2 class="h2 mb-2 text-gray-800">Data Form Pelarutan Chemical</h2>
        <!-- <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="tambahmaster" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Nama Chemical</a>
            <?php }?> -->
    </div>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                         <tr>
                            <th rowspan="2" width="1" class="font-weight-bold">Nama Chemical</th>
                            <th colspan="2" class="font-weight-bold">Pengenceran (ml)</th>
                            <th rowspan="2" class="font-weight-bold">Total Larutan Chemical</th>
                            <th rowspan="2" width="1" class="font-weight-bold">Petugas</th>
                            <th rowspan="2" width="1" class="font-weight-bold">QC</th>
                            <th rowspan="2" class="font-weight-bold">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Chemical</th>
                            <th>Air</th>
                        </tr> 
                    </thead>
                    <tbody>
    <?php
    foreach ($data as $row) {
    ?>
        <tr>
            <td><?= $row->chemical_name; ?></td>
            <td><?= $row->chemical_used; ?></td>
            <td><?= $row->larutan - $row->chemical_used; ?></td>
            <td><?= $row->larutan ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php

       
    }
    ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>