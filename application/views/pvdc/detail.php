 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Mesin Area <?= $area->nama_area; ?>  </h1>
        <a href="<?= base_url('mesin/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('area/') ?>"><i class="fas fa-arrow-left mr-2"></i>Fokus Area</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Mesin </li>
    </ol>
</nav>           <!-- DataTales Example -->


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
                        <th>Update RH</th>
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
                            
                            <td><?= $row->nama_mesin;?></td>
                            <td><?= $row->rh_update;?></td>
                            <td>
                                <a href="<?= base_url('mesin/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white"></i> Edit</a>
                            </td>
                            <td>
                                <a href="<?= base_url('mesin/detail/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-sm"><i class="fa fa-book mr-2 fa-sm text-white"></i>Detail part</a>
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