<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Manual Books</h1>
        <a href="<?= base_url('manual_books/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">

            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                    <tr>
                        <th>No</th>
                       
                        <th>Area</th>
                        <th>Nama Mesin</th>
                        <th>Judul</th>
                        <th>Keterangan</th>
                        <th>PDF</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data as $ar): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            
                            <td><?= $ar->nama_area; ?></td>
                            <td><?= $ar->nama_mesin; ?></td>
                            <td><?= $ar->judul; ?></td>
                            <td><?= $ar->keterangan; ?></td>
                            <td><a href="<?= base_url('upload/'. $ar->pdf) ?>" target="_blank">
                                <i class="fa fa-3x fa-file-pdf"></i></i></a></td>
                         
                            <td>
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>