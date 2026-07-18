<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Printing Karton</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('zanasi') ?>"><i class="fas fa-arrow-left mr-2"></i>Printing Zanasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('zanasi/print/'.$data->uuid) ?>" method="post">
            <div class="row mb-3 mb-sm-0">           
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <table class="table table-success mb-2" width="100%">
                                <tr>
                                    <td width="140px" class="font-weight-bold border-top-0">Varian</td>
                                    <td width="5" class="border-top-0">:</td>
                                    <td class="font-weight-bold border-top-0"><?= $data->nama_varian;?></td>
                                </tr>
                                <tr>
                                    <td width="140px" class="font-weight-bold">Kode Produksi</td>
                                    <td width="5">:</td>
                                    <td class="font-weight-bold"><?= $data->kode; ?></td>
                                </tr>
                                <tr>
                                    <td width="140px" class="font-weight-bold">Kode Exp</td>
                                    <td width="5">:</td>
                                    <td class="font-weight-bold"><?= $data->exp; ?></td>
                                </tr>
                            </table>
                    <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Jumlah :<span class="text-danger">*</span></label>
                        <input type="number" name="print" class="form-control <?= form_error('print') ? 'is-invalid' : '' ?>" placeholder="0" value="<?= set_value('print'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('print')) ? 'd-block':'';?>">
                            <?= form_error('print') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Catatan :</label>
                        <input type="text" name="catatan" class="form-control <?= form_error('catatan') ? 'is-invalid' : '' ?>" placeholder="" value="<?= set_value('catatan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block':'';?>">
                            <?= form_error('catatan') ?>
                        </div>
                    </div>
                </div>

                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('zanasi') ?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                    
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <h3 class="text-gray-800">Jumlah Karton yang Sudah Di Print</h3>
                    <table class="table table-bordered">
                        <tr class="bg-info text-light">
                            <th>Print Ke-</th>
                            <th>Operator</th>
                            <th>Catatan</th>
                            <th>Jumlah</th>
                        </tr>
                        <?php
                        $no = 1;
                        foreach ($print as $value) {
                            ?>
                            <tr>
                                <td width="1"><?= $no ;?></td>
                                <td><?= $value->username;?></td>
                                <td><?= $value->catatan;?></td>
                                <td><?= $value->print;?></td>
                            </tr>
                        <?php 
                        $no ++;
                    } ?>
                        <tr>
                            <td colspan="3">Jumlah</td>
                            <td><?= $total->totalPrint; ?></td>
                            
                        </tr>
                        <tr>
                            <td colspan="3">Target</td>
                            <td><?= $data->permintaan; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Sisa</td>
                            <td><?= $data->permintaan - $total->totalPrint ; ?></td>
                        </tr>
                    </table>

                </div>
            </div>


            
        </form>

    </div>
</div>
</div>
