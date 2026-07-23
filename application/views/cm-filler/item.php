<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Data Item Pengecekan Mesin</h1>
        <a href="<?= base_url('cekmesin_fillerbatch/tambahitem'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i
                class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i class="fa fa-arrow-left"></i>
            Pengecekan Mesin Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Item Pengecekan Mesin/Batch</li>
        </ol>
    </nav>
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
                            <th>Mesin</th>
                            <th>Item Pengecekan</th>

                            <th>action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php
                     $no = 1;
                     foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $row->mesin;?></td>
                            <td><?= $row->item;?></td>


                            <td>
                                <a href="<?= base_url('cekmesin_fillerbatch/edititem/'.$row->uuid); ?>"
                                    class="btn btn-md btn-warning btn-block shadow-sm"><i
                                        class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
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