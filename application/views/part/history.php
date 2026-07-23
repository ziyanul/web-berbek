<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data History Perubahan Sparepart</h1>
        
    </div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('part/') ?>"><i class="fas fa-arrow-left"></i>Data Part</a></li>
        <li class="breadcrumb-item active" aria-current="page">Historis</li>
      </ol>
    </nav>
    <!-- DataTales Example -->


    <div class="card shadow mb-4">
        <div class="card-header py-3">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Mesin</th>
                            <th>Nama Sparepart</th>
                            <th>Lifetime</th>
                            <th>Harga</th>
                            <th>Alasan</th> 
                                                                  
                        </tr>
                    </thead>

                    <tbody>
                       <?php
                       $no = 1;
                       foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $row->nama_mesin;?></td>
                            <td><?= $row->nama_part;?></td>
                            <td><?= $row->lifetime;?></td>
                            <td><?= $row->harga;?></td>
                            <td><?= $row->kondisi;?></td>
                            
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
