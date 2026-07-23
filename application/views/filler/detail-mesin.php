 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail OFF Mesin <?= $mesin ?></h1>
</div>
<!-- <div>
    Total Losses: <?php echo $data['totalA']; ?> minutes
</div>
<div>
    Total Downtime: <?php echo $data['totalB']; ?> minutes
</div> -->
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
                <table class="table table-bordered" id="datatables">
                    <thead class="table bg-info text-light">

                        <tr>

                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
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
                                <td><?= $row['start'] ?></td>
                                <td><?= $row['finish'] ?></td>
                                <td><?= $row['total'] ?> Menit</td>
                                    <!-- <td><?= $row->start; ?></td>
                                    <td><?= $row->finish; ?></td>
                                    <td><?= $row->total; ?> Menit</td> -->
                                    
                                    <td class="font-weight-bold align-middle text-center">
                                       
                                        <form class="user d-flex justify-content-between" action="<?= base_url('filler/updateketerangan') ?>" method="post">
                                            
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="t_planning_uuid" value="<?= $t_planning_uuid ?>">
                                            <input type="hidden" name="device_id" value="<?= $device_id ?>">
                                            <select class="form-control mr-3" name="f_keterangan">
                                                <option value="0" <?php echo ($row['keterangan'] == 0) ? 'selected' : ''; ?> disabled class="text-center">-------</option>
                                                <option value="1" <?php echo ($row['keterangan'] == 1) ? 'selected' : ''; ?>>Persiapan Awal Produksi</option>
                                                <option value="2" <?php echo ($row['keterangan'] == 2) ? 'selected' : ''; ?>>Setup/Adjustment Loss</option>
                                                <option value="3" <?php echo ($row['keterangan'] == 3) ? 'selected' : ''; ?>>Line Suport</option>
                                                <option value="4" <?php echo ($row['keterangan'] == 4) ? 'selected' : ''; ?>>Breakdown Loss</option>
                                                <option value="5" <?php echo ($row['keterangan'] == 5) ? 'selected' : ''; ?>>Material Loss</option>
                                            </select>
                                            <button type="submit" class="btn btn-md btn-success" title="Simpan!">
                                                <i class="fa fa-save"></i>
                                            </button>
                                        </form>

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