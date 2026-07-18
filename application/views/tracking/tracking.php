<!-- Begin Page Content -->
<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Tracking Improvement</h1>
        <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="tracking/tambah" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
            <?php }?>
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
                            <th class="font-weight-bold">Issue</th>
                            <th class="font-weight-bold">PIC</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Pencapaian</th>
                            <th class="font-weight-bold">Action</th>
                        </tr> 
                    </thead>
                    <tbody>
    <?php
    $no = 1;

    foreach ($data as $row) {
        $row->tanggal = date("d M Y", strtotime($row->created_at));
        
       
    ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= $row->tanggal; ?></td>
            <td><?= $row->issue; ?></td>
            <td><?= $row->pic; ?></td>
            <td><?= $row->status; ?></td> <!-- Update this line -->
            <td><?= $row->pencapaian; ?></td>

            <td>
                <a href="<?= base_url('tracking/detail/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block">Detail</a>
                <?php
                            $type = $this->session->userdata('type');
                            $subrole = $this->session->userdata('subrole');
                            if ($type == 1 || $type == 2) { ?>
                <a href="<?= base_url('tracking/tambahdetail/' . $row->uuid); ?>" class="btn btn-md btn-info shadow-sm btn-block"><i class="fas fa-plus fa-sm text-white mr-2"></i>Detail</a>
                <a href="<?= base_url('tracking/edit/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block">Edit</a>
            <?php } ?>
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