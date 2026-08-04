<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Data Stock Rework</h1>
    </div>
    <?php if ($this->session->flashdata('success_msg')) : ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <?php if ($this->session->flashdata('error_msg')) : ?>
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
                            <th width="1" class="font-weight-bold">No</th>
                            <th class="font-weight-bold">Varian</th>
                            <th class="font-weight-bold">Kode Rework</th>

                            <th class="font-weight-bold">Total Rework</th>
                            <th class="font-weight-bold">Sudah Kupas</th>
                            <th class="font-weight-bold">Belum Kupas</th>
                            <th class="font-weight-bold" width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($rows as $row) {
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $row->nama_varian; ?></td>
                                <td><?= $row->kode_batch; ?></td>

                                <td><?= $row->total_rework; ?></td>
                                <td><?= $row->total_kupas; ?></td>
                                <td><?= $row->sisa_kupas; ?></td>
                                <td>
                                    <a href="<?= base_url('rework/detail_kupas/' . $row->tbatch_uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block"><i class="fa fa-md fa-info"></i> Detail</a>

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