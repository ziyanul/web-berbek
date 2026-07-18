 <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <!-- Page Heading -->
                    <h2 class="h2 mb-2 text-gray-800">Monitoring Mesin Filler</h2>
                    <a href="<?= base_url('filler/tambah'); ?>" class="btn btn-md btn-primary font-weight-bold shadow-sm"><i class="fas fa-plus fa-sm text-white mr-2"></i>Tambah Plan 1</a>

                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                                    <thead class="table bg-info text-light">
                                        <tr>
                                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
                                            <th class="font-weight-bold align-middle text-center" width="100">Tanggal Produksi</th>
                                            <th class="font-weight-bold align-middle text-center" width="200">Planning Produksi</th>
                                            <th class="font-weight-bold align-middle text-center" width="130">Detail</th>
                                            <th class="font-weight-bold align-middle text-center" width="100">Action</th>
                                        

                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                       <?php
                                            $no = 1;
                                            foreach ($data as $row) {
                                        ?>
                                            <tr>
                                                <td width="1"><?= $no ?></td>
                                                <td><?= $row->tanggal ?></td>
                                                <td>
                                                	<a href="<?= base_url('filler/Planning/' .$row->uuid); ?>" class="btn btn-md btn-success font-weight-bold shadow-sm btn-block"><i class="fa fa-info mr-2 fa-sm text-white"></i>Planning</a>
                                                	<a href="<?= base_url('filler/tambahplan/' .$row->uuid); ?>" class="btn btn-md btn-primary font-weight-bold shadow-sm btn-block"><i class="fa fa-plus mr-2 fa-sm text-white"></i>Plan 2</a>
                                                </td>
                                                <td>
                                                	<a href="<?= base_url('filler/performance/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-md btn-block font-weight-bold"><i class="fa fa-info mr-2 fa-sm text-white"></i>Performance</a>
                                                <a href="#" class="btn btn-md btn-success font-weight-bold shadow-sm btn-block"><i class="fa fa-info mr-2 fa-sm text-white"></i>Loses</a>
                                                <a href="#" class="btn btn-md btn-success font-weight-bold shadow-sm btn-block"><i class="fa fa-info mr-2 fa-sm text-white"></i>DownTime</a>
                                                </td>
                                                <td>
                                                	<a href="#" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fa fa-edit mr-2 fa-sm text-white"></i>Edit</a>
                                                </td>
                                            </tr>
                                        <?php
                                        $no++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

           
            <!-- End of Main Content -->