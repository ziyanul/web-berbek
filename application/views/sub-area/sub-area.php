<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Sub Area</h1>
        <a href="<?= base_url('sub_area/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
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
                            <th>No.</th>
                            <th>Nama Area</th>
                            <th>Nama Sub Area</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1">
                                <?= $no; ?>
                            </td>
                            <td>
                                <?= $row->nama_area;?>
                            </td>
                            <td>
                                <?= $row->lokasi;?>
                            </td>
                            
                            <td>
                                <a href="<?= base_url('sub_area/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white-50"></i> Edit</a>
                                <a href="<?= base_url('sub_area/hapus/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm" onclick="return confirm('Apakah yakin ingin menghapus data ini?')"><i class="fa fa-trash fa-sm text-white-50"></i> Hapus</a>
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
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->