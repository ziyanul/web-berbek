<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 text-gray-800">Pengecekan Mesin/Batch Filler <h3 class="h3 text-gray-700"><?= $data->tgl ?> - <?= $data->varian ?></h3></h1>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Batch</li>
        </ol>
</nav>
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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th width="1" class="font-weight-bold">No</th>
                                <th class="font-weight-bold">Batch Ke-</th>
                                <th class="font-weight-bold">Kode</th>
                                <th class="align-middle text-center" width="350">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                    $no = 1;
                    foreach ($batch as $row) {
                        ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= substr($row->MN_BATCH, 5, 2);?></td>
                                <td ><?= $row->MN_BATCH;?></td>

                                <td class="align-middle text-center">
                                    <a href="<?= base_url('cekmesin_fillerbatch/ceklist_batch/'.$row->MN_BATCH); ?>"
                                        class="btn btn-md btn-warning shadow-sm mr-2"><i
                                            class="fa fa-check fa-sm text-white mr-2"></i> Ceklist</a>
                                    <a href="<?= base_url('cekmesin_fillerbatch/detailcek/'.$row->MN_BATCH); ?>"
                                        class="btn btn-md btn-success shadow-sm ml-2"><i
                                            class="fa fa-info fa-sm text-white mr-2"></i>Detail Cek Mesin</a>
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