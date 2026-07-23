 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Detail Loses Mesin</h1>

    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables">
                    <thead class="table bg-info text-light">

                        <tr>

                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th class="font-weight-bold align-middle text-center">Nama Mesin</th>
                            <th class="font-weight-bold align-middle text-center">Start</th>
                            <th class="font-weight-bold align-middle text-center">Finish</th>
                            <th class="font-weight-bold align-middle text-center">Lama OFF</th>
                            <th class="font-weight-bold align-middle text-center" >Keterangan</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {

                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td></td>
                                <td><?= $row['start'] ?></td>
                                <td><?= $row['finish'] ?></td>
                                <td><?= $row['total'] ?> Menit</td>
                                    <!-- <td><?= $row->start; ?></td>
                                    <td><?= $row->finish; ?></td>
                                    <td><?= $row->total; ?> Menit</td> -->
                                    
                                    <td class="font-weight-bold align-middle text-center">
                                       Keterangan beban operator
                                    </td>
                                </tr>
                                <?php
                                $no ++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


            <!-- End of Main Content -->