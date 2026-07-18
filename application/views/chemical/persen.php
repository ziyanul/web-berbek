<!-- Begin Page Content -->
<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h2 class="h2 mb-2 text-gray-800">Master Persentase Pelarutan Chemical</h2>
    <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
        <a href="tambahpersen/" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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
                    <th class="font-weight-bold">Kode Chemical</th>
                    <th class="font-weight-bold">Nama Chemical</th>
                    <th class="font-weight-bold">Pengenceran Chemical</th>
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
                        <td><?= $row->kode_chemical; ?></td>
                        <td><?= $row->chemical_name; ?></td>
                        <td><?= $row->persentase; ?><?= $row->satuan ?></td>
                        <td>
                            <a href="<?= base_url('chemical/editpersen/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fas fa-edit fa-sm text-white mr-2"></i>Edit</a>
                            <a href="<?= base_url('chemical/deletpersen/' . $row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash fa-sm text-white mr-2"></i>Hapus</a>
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