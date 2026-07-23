<div class="container-fluid">
    <div class="row">
        <div class="col ml-2">
            <h2 class="h2 mb-2 text-gray-800">Pengecekan Mesin Filler</h2>
        </div>
        <div class="col text-right mr-2">
            <a href="<?= base_url('cekmesin/dataitem') ?>" class="btn btn-md btn-primary shadow-sm">
                <i class="fas fa-info fa-sm text-white mr-2"></i>Data Item
            </a>
            <a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>" class="btn btn-md btn-primary shadow-sm mt-2 mb-2">
                <i class="fas fa-info fa-sm text-white mr-2"></i>Data Item / Batch
            </a>
        </div>

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
                            <th class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th class="font-weight-bold align-middle text-center" width="150">Tanggal Planning Produksi
                            </th>
                            <th class="font-weight-bold align-middle text-center" width="100">Varian</th>
                            <th class="font-weight-bold align-middle text-center" width="150">Tanggal Pengecekan</th>
                            <th class="font-weight-bold align-middle text-center" width="100">Mesin Checked</th>
                            <th class="font-weight-bold align-middle text-center" width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                         ?>
                         <tr>
                            <td class="align-middle text-center" width="1"><?= $no ?></td>
                            <td class="align-middle text-center"><?= $row->tgl ?></td>
                            <td class="align-middle text-center"><?= $row->varian ?></td>
                            <td class="align-middle text-center"><?= $row->awal_cek ?></td>
                            <td class="align-middle text-center"><?= $row->jumlah_mesin ?> dari 14</td>
                            <td>
                                <a href="<?= base_url('cekmesin_filler/'.$row->uuid); ?>"
                                    class="btn btn-md btn-warning shadow-md btn-block font-weight-bold"><i
                                    class="fa fa-check mr-2 fa-sm text-white"></i>Ceklist Mesin Awal Proses</a>
                                    <a href="<?= base_url('cekmesin_filler/detail-'.$row->uuid.'/'.$this->filler); ?>"
                                        class="btn btn-md btn-success shadow-md btn-block font-weight-bold"><i
                                        class="fa fa-info mr-2 fa-sm text-white"></i>Detail Awal Proses</a>
                                        <a href="<?= base_url('cekmesin_fillerbatch/detail-'.$row->uuid); ?>"
                                            class="btn btn-md btn-success shadow-md btn-block font-weight-bold"><i
                                            class="fa fa-check mr-1 fa-sm text-white"></i> Ceklist Mesin / Batch</a>
                                            <?php if ($row->jumlah_mesin != 0): ?>
                                                <a href="<?= base_url('cekmesin_filler/form-'.$row->uuid.'/'.$this->filler); ?>"
                                                    class="btn btn-md btn-info shadow-md btn-block font-weight-bold" target="_blank">
                                                    <i class="fa fa-print mr-2 fa-sm text-white"></i>Form Pengecekan Awal Proses
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('cekmesin_fillerbatch/form/'.$row->uuid); ?>"
                                                class="btn btn-md btn-info shadow-md btn-block font-weight-bold" target="_blank"><i
                                                class="fa fa-print mr-2 fa-sm text-white"></i>Form Pengecekan / Batch</a>
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
            </div>