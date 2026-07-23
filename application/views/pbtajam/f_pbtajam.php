<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pengecekan Benda Tajam</h1>
        <a href="<?= base_url('Pbtajam/tambah'); ?>" class="btn btn-md btn-primary shadow-sm font-weight-bold"><i
                class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
    </div>
    <?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?php echo $this->session->flashdata('success_msg'); ?>
    </div>
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
                            <th class='align-middle text-center'>Shift</th>
                            <th class='align-middle text-center' width='20%'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    $no = 1;
                    $tanggal_shift_seen = [];
                    foreach ($data as $row) {
                        $key = $row->tanggal . '-' . $row->shift_name;
                        if (!in_array($key, $tanggal_shift_seen)) {
                            ?>
                        <tr>
                            <td class='align-middle text-center' width="1"><?= $no; ?></td>
                            <td class='align-middle text-center'><?= $row->tanggal; ?></td>
                            <td class='align-middle text-center'><?= $row->shift_name; ?></td>
                            <td>
                                <a href="<?= base_url('Pbtajam/detailform/'.$row->tgl.'/'.$row->shift); ?>"
                                    class="btn btn-block btn-md btn-success shadow-sm">
                                    <i class="fa fa-info fa-md text-white mr-2"></i> Detail
                                </a>
                                <a href="<?= base_url('Pbtajam/form/'.$row->tgl.'/'.$row->shift); ?>" target="_blank"
                                    class="btn btn-block btn-md btn-info shadow-sm">
                                    <i class="fa fa-print fa-md mr-2"></i> Form
                                </a>
                            </td>
                        </tr>
                        <?php
                            $tanggal_shift_seen[] = $key;
                            $no++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>