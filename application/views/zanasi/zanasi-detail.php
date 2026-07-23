<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Detail Kontrol Printing DOD</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('zanasi') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Printing DOD</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col mr-2">
                    <table class="table table-success">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold border-top-0" width="20">Tipe</td>
                                <td width="10" class="border-top-0">:</td>
                                <td class="font-weight-bold border-top-0"><?= ($data->rutin == 1) ? 'Rutin' : 'Tambahan'; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" width="200">Varian</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->nama_varian; ?> (<?= $data->keterangan; ?>)</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold" width="200">Kode Produksi</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->kode; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" width="200">Kode Exp</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->exp; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" width="200">KR / Checker</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $data->username; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" width="200" class="border-bottom">Catatan</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="font-weight-bold border-bottom"><?= $data->catatan; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col ml-2">
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
                            <td class="font-weight-bold" colspan="3">Realisasi</td>
                            <td class="font-weight-bold"><?= $total->totalPrint; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" colspan="3">Target</td>
                            <td class="font-weight-bold"><?= $data->permintaan; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" colspan="3">Sisa</td>
                            <td class="font-weight-bold"><?= $data->permintaan - $total->totalPrint ; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col">
                <a href="<?= base_url('zanasi') ?>" class="btn btn-md btn-danger">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>