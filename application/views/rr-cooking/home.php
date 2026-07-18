<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Rejet Cooking Retort</h1>
    </div>
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?php echo $this->session->flashdata('success_msg'); ?>    </div>
            <br>
        <?php endif; ?>
        <?php if($this->session->flashdata('error_msg')): ?>
            <div class="alert alert-danger  text-center">
                <i class="fas fa-times"></i>
                <?php echo $this->session->flashdata('error_msg'); ?>    
            </div>
            <br>
        <?php endif ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th class='align-middle text-center'>No.</th>
                                <th class='align-middle text-center'>Tanggal</th>
                                <th class='align-middle text-center'>Varian</th>
                                <th class='align-middle text-center'>Batch</th>
                                <th class='align-middle text-center'>Chamber</th>
                                <th class='align-middle text-center'>Masakan ke-</th>
                                <th class='align-middle text-center' width='35%'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td class='align-middle text-center' width="1"><?= $no;?></td>
                                    <td class='align-middle text-center'><?= $row->tanggal;?></td>
                                    <td class='align-middle text-center'><?= $row->varian;?></td>
                                    <td class='align-middle text-center'><?= $row->batch;?></td>
                                    <td class='align-middle text-center'><?= $row->MR_NOCHAM;?></td>
                                    <td class='align-middle text-center'><?= $row->masak;?></td>
                                    <td class='align-middle text-center'>
                                        <?php
                                        if ($row->rc_data == NULL) {
                                            ?>
                                            <a href="<?= base_url('rr_cooking/tambah/'. $row->uuid) ?>"
                                                class="btn btn-md btn-primary shadow-sm"><i
                                                class="fa fa-plus fa-sm text-white"></i> Tambah Reject</a>
                                                <?php
                                            } else{
                                                ?>
                                                <a href="<?= base_url('rr_cooking/edit/'. $row->uuid) ?>"
                                                    class="btn btn-md btn-warning shadow-sm"><i
                                                    class="fa fa-edit fa-sm text-white"></i> Ubah Reject</a>
                                                    <?php
                                                }
                                                ?>

                                                <a href="<?= base_url('rr_cooking/detail/'. $row->MR_DATE.'/'.$row->MR_uuid_varian) ?>"
                                                    class="btn btn-md btn-success shadow-sm"><i
                                                    class="fa fa-info fa-sm text-white"></i> Detail</a>
                                                <a href="<?= base_url('rr_cooking/form/'. $row->MR_DATE.'/'.$row->MR_uuid_varian) ?>"
                                                        class="btn btn-md btn-info shadow-sm"><i
                                                        class="fa fa-print fa-sm text-white"></i> Form</a>
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