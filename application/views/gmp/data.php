<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data ISO/TS</h1>
 
            <a href="<?= base_url('gmp/tambahkegiatan'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>
    <!-- DataTales Example -->
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
                            <th width="1">No.</th>
                           
                            <th>Area</th>
                            <th>Lokasi</th>
                             <th>Jenis</th>
                             <th>Terjadwal?</th>
                              <th>action</th>
                        </tr>
                       
                      </thead>

                    <tbody>

                     <?php
                     $no = 1;
                     foreach ($kegiatan as $k) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                           
                            <td><?= $k->nama_area;?></td>
                            <td><?= $k->lokasi;?></td>
                             <td><?= $k->kegiatan;?></td>
                             <td><?= $k->keterangan;?></td>
                            
                            <td>
                                <a href="<?= base_url('gmp/editkegiatan/'.$k->kegiatan_uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
                                <a href="<?= base_url('gmp/delete_kegiatan/'.$k->kegiatan_uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
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
<!-- 
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
       
        <h1 class="h3 mb-2 text-gray-800">Data Area ISO/TS</h1>
 
            <a href="<?= base_url('gmp/tambaharea'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>
    

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th width="1">No.</th>
                            <th>Area</th>
                           
                            <th>action</th>
                        </tr>
                      </thead>
                    <tbody>
                     <?php
                     $no = 1;
                     foreach ($area as $a) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $a->area;?></td>
                            <td>
                                <a href="<?= base_url('gmp/editarea/'.$a->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
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
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-5">
      
        <h1 class="h3 mb-2 text-gray-800">Data Lokasi ISO/TS</h1>
 
            <a href="<?= base_url('gmp/tambahlokasi'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>
    

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th width="1">No.</th>
                            <th>Area</th>
                            <th>Lokasi</th>
                            <th>action</th>
                        </tr>
                       
                      </thead>

                    <tbody>
                     <?php
                     $no = 1;
                     foreach ($lokasi as $l) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $l->area;?></td>
                            <td><?= $l->lokasi;?></td>
                           
                            <td>
                                <a href="<?= base_url('gmp/editlokasi/'.$l->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
                                
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
</div> -->