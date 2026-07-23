<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Detail Pengecekan Tools Mesin Area</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('tools_mesin/data') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Tools Mesin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col mb-3">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light text-center">
                            <tr>
                                <th class="align-middle" rowspan="3">Hari / Tanggal</th>
                                <?php foreach ($data['tools'] as $tool): ?>
                                <th class="align-middle text-center" colspan="2">Kondisi (&#x2713;)</th>
                                <?php endforeach; ?>
                                <th class="align-middle text-center" rowspan="3">Keterangan</th>
                            </tr>
                            <tr>
                                <?php foreach ($data['tools'] as $tool): ?>
                                <th class="align-middle text-center">Bersih</th>
                                <th class="align-middle text-center">Kelengkapan</th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php foreach ($data['tools'] as $tool): ?>
                                <th class="align-middle text-center" colspan="2"><?= $tool ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $tanggal => $toolsData): ?>
                            <tr>
                                <td><?= $tanggal ?></td>
                                <?php foreach ($data['tools'] as $tool): ?>
                                <td class="text-center align-middle"><?= $toolsData[$tool]['kondisi'] ?></td>
                                <td class="text-center align-middle"><?= $toolsData[$tool]['kelengkapan'] ?></td>
                                <?php endforeach; ?>
                                <td class="text-center align-middle">
                                    <?= $toolsData[array_key_first($toolsData)]['keterangan'] ?? '-' ?>
                                    <!-- Ambil keterangan pertama untuk setiap tanggal -->
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-2 mb-4">
                <div class="col">
                    <a href="<?= base_url('tools_mesin/data') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>