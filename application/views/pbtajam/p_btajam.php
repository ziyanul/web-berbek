<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Data Area Benda Tajam</h1>
        <a href="<?= base_url('pbtajam/tambah_area'); ?>" class="btn btn-md btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
    </div>

    <?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?= $this->session->flashdata('success_msg') ?>
    </div>
    <br>
    <?php endif ?>

    <?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
    <br>
    <?php endif ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class="align-middle" width="1">No.</th>
                            <th>Area</th>
                            <th>Tanggal</th>
                            <th>Jenis Benda</th>
                            <th class="align-middle text-center" width="300px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data as $area => $dates) {
                            $rowspan_area = array_sum(array_map('count', $dates));
                            $first_area_shown = false;
                            foreach ($dates as $tanggal => $items) {
                                $rowspan_tanggal = count($items);
                                $first_tanggal_shown = false;
                                foreach ($items as $row) { ?>
                                    <tr>
                                        <?php if (!$first_area_shown): ?>
                                            <td rowspan="<?= $rowspan_area; ?>" class="text-center align-middle"><?= $no++; ?></td>
                                            <td rowspan="<?= $rowspan_area; ?>" class="align-middle"><?= $area; ?></td>
                                        <?php endif; ?>
                                        <?php if (!$first_tanggal_shown): ?>
                                            <td rowspan="<?= $rowspan_tanggal; ?>" class="align-middle"><?= $tanggal; ?></td>
                                        <?php endif; ?>
                                        <td class="align-middle"><?= $row->jenis_benda; ?></td>
                                        <td class="align-middle text-center">
                                            <a href="<?= base_url('Pbtajam/editjenis/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i
                                            class="fa fa-edit fa-sm text-white mr-2"></i> Edit Jenis Benda</a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $first_area_shown = true;
                                    $first_tanggal_shown = true;
                                } 
                            } 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
