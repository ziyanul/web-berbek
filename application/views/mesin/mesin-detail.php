<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Sparepart <?= $mesin->nama_mesin;?></h1>
 
            <a href="<?= base_url('part/tambah'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>

<nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('mesin/') ?>"><i class="fas fa-arrow-left mr-2"></i>Mesin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Part </li>
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
                            
                            <th>Nama Sparepart</th>
                            <th>Lifetime</th>
                            <th>Harga</th> 
                            <th colspan="2">Aksi</th>                                      
                        </tr>
                    </thead>

                    <tbody>
                     <?php
                     $no = 1;
                     foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                           
                            <td><?= $row->nama_part;?></td>
                            <td><?= $row->lifetime;?></td>
                            <td><?= $row->harga;?></td>
                            <td>
                                <a href="<?= base_url('part/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
                            </td>
                            <td>
                                <a href="<?= base_url('part/history/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-sm"><i class="fa fa-book fa-sm text-white mr-2"></i>History</a>
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