<div class="container-fluid">
    <!-- Page Heading -->

    <h1 class="h1 mb-2 text-gray-800">
        Detail Kode <?= $nav->jenis_barang ?> Area <?= $nav->nama_area ?>
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pbelah/kode');?>"><i class="fas fa-arrow-left"></i>
                    Data Jenis</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kode Barang</li>
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
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm">
                    <table class="table table-bordered" width="100%">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th width="1">No</th>
                                <th>Kode Barang</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $no = 1;
                        foreach ($data as $row)

                         { ?>
                            <tr>
                                <td><?= $no ; ?></td>
                                <td><?= $row->kode_barang ; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('Pbelah/editkodepb/'.$row->uuid); ?>"
                                        class="btn btn-md btn-warning btn-block">
                                        <i class="fa fa-search mr-2"> Edit Kode</i>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                            $no ++ ;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        