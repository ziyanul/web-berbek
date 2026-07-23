<!-- Begin Page Content -->
<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Pengecekan Tools Mesin</h1>
        
            <a href="<?= base_url('tools_mesin/tambahdata/'); ?>" class="btn btn-md btn-primary shadow-sm font-weight-bold"><i class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
            
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
                            <th class="font-weight-bold">Bulan</th>
                            <th class="font-weight-bold">Area</th>
                            <th class="font-weight-bold text-center align-middle">Action</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php
                     $no = 1;
                     foreach ($data as $row) {
                        ?>
                     <tr>
                        <td><?= $no ?></td>
                        <td><?= $row->tgl ?></td>
                        <td><?= $row->nama_area ?></td>
                         <td class="text-center align-middle">
                         <a href="<?= base_url('tools_mesin/formdetail/'.$row->area_uuid.'/'.$row->bln); ?>" class="btn btn-md btn-success shadow-sm btn-block font-weight-bold"><i class="fas fa-info fa-sm text-white mr-2"></i> Detail</a>
                         <a href="<?= base_url('tools_mesin/printform/'.$row->bln); ?> " target='_blank' class="btn btn-md btn-info shadow-sm btn-block font-weight-bold"><i class="fas fa-print fa-sm text-white mr-2"></i> From</a>
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