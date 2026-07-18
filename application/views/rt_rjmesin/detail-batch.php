<div class="container-fluid">
	<h3 class="h3 mb-2 text-gray-800">Detail Reject Mesin Per Batch</h3>
	<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('rt_rjmesin') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Reject Mesin Di Retort
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('rt_rjmesin/detail/' . $nav->planprod_uuid) ?>">
                    Reject per Batch
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>


    <div class="card shadow mb-4">
      <div class="card-body">
         <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="table-responsive">
                <h5 class="font-weight-bold">Informasi :</h5>
                <table class="table table-success mb-4">
                    <tbody>

                        <tr>
                            <td width="200" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $nav->tanggal;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->MN_PRODUK; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Kode Batch</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0'><?= $nav->MN_BATCH ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <table class="table table-bordered">
            <thead class="table bg-info text-light text-center">
                <tr>
                    <th>Mesin Filler</th>
                    <?php foreach ($badpro_headers as $header): ?>
                        <th><?= $header->nama_badpro ?></th>
                    <?php endforeach; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_mesin as $mesin_uuid => $mesin): ?>
                    <tr>
                        <td><?= $mesin['nama_mesin'] ?></td>
                        <?php foreach ($badpro_headers as $header): ?>
                            <td>
                                <?= isset($mesin['badpro'][$header->nama_badpro]) ? $mesin['badpro'][$header->nama_badpro] : '-' ?>
                            </td>
                        <?php endforeach; ?>
                        <td>
                            <a href="<?= base_url('rt_rjmesin/editreject/'.$mesin['rm_uuid']); ?>"
                                class="btn btn-sm btn-warning btn-block shadow-sm"><i
                                class="fa fa-edit fa-sm text-white mr-1"></i>
                            Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="<?= base_url('rt_rjmesin/detail/'. $nav->planprod_uuid) ?>" class="btn btn-md btn-primary mt-3">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>


    </div>
</div>

