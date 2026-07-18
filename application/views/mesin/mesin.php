<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Mesin</h1>
        <a href="<?= base_url('mesin/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Area</th>
                            <th>Nama Mesin</th>
                            <th>Update RH</th>
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
                                    <?= $row->nama_area; ?>
                                </td>
                                <td>
                                    <?= $row->nama_mesin; ?>
                                </td>
                                <td>
                                    <?= $row->rh_update; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('mesin/edit/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white"></i> Edit</a>
                                    <a href="<?= base_url('mesin/detail/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm"><i class="fa fa-book fa-sm text-white mr-2"></i>Data Sparepart</a>
                                    <a href="<?= base_url('am/kegiatan/' . $row->uuid); ?>" class="btn btn-md btn-info shadow-sm"><i class="fa fa-book fa-sm text-white mr-2"></i>Kegiatan AM</a>
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