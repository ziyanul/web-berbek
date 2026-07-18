<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Speed Mesin Filler</h1>
        <a href="<?= base_url('filler/tambahspeed'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th class="font-weight-bold align-middle text-center">Nama Mesin</th>
                            <th class="font-weight-bold align-middle text-center">Okey</th>
                            <th class="font-weight-bold align-middle text-center">Champ</th>
                            <th class="font-weight-bold align-middle text-center">Action</th>
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
                                <?= $row->mesin;?>
                            </td>
                            <td class="text-center"><?= $row->okey;?></td>
                            <td class="text-center"><?= $row->champ;?></td>
                            <td class="text-center">
                                <a href="<?= base_url('filler/editspeed/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fa fa-edit fa-sm text-white-50"></i> Edit</a>
                     
                                
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