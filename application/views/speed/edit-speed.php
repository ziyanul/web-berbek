<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ubah Data Speed Mesin Filler</h1>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('speed') ?>"><i class="fas fa-arrow-left"></i>  Master Speed Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">
                Nama Mesin : <?= $data[0]->nama_mesin; ?>
            </h6>
        </div>

        <div class="card-body">
            <form action="<?= base_url('speed/edit/' . $data[0]->mesin_uuid); ?>" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Varian</th>
                                <th width="200">Speed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $row) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                        <td><?= $row->nama_varian ?> - <?= $row->keterangan ?></td>
                                        <td>
                                            <input type="number" 
                                            name="speed[<?= $row->varian_uuid ?>]" 
                                            value="<?= $row->speed ?>" 
                                            class="form-control" 
                                            required>
                                        </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('speed'); ?>" class="btn btn-danger">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

