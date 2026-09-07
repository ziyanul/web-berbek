<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pemakaian PVDC & WIRE</h1>
        <a href="<?= base_url('area/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Planning Produksi</th>
                            <th rowspan="2">Varian</th>
                            <th colspan="2">Pemakaian</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Pemakaian PVDC</th>
                            <th>Pemakaian Wire</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $row->tanggal; ?></td>
                            <td><?= $row->nama_varian; ?></td>
                            <td><?= $row->pvdc; ?></td>
                            <td><?= $row->wire; ?></td>
                            <td>
                                <a href="<?= base_url('pvdc/edit/' . $row->uuid); ?>"
                                    class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white"></i>
                                    Edit</a>
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