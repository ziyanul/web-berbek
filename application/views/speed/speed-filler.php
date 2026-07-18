<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Master Speed Mesin Filler</h1>
        <a href="<?= base_url('speed/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No</th>
                            <th>Mesin</th>

                            <?php foreach ($varian_list as $varian) : ?>
                                <th><?= $varian->varian; ?></th>
                            <?php endforeach; ?>

                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($speed_list as $row) : ?>
                            <tr>
                                <td width="1" class="text-center"><?= $no++; ?></td>
                                <td class="text-center"><?= $row['nama_mesin']; ?></td>

                                <?php foreach ($varian_list as $varian) : ?>
                                    <td class="text-center">
                                        <?= isset($row['speeds'][$varian->uuid]) ? $row['speeds'][$varian->uuid] : '-'; ?>
                                    </td>
                                <?php endforeach; ?>

                                <td class="text-center">
                                    <a href="<?= base_url('speed/edit/' . $row['mesin_uuid']); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->