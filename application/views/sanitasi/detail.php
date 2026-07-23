<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Detail Cheklist Kebersihan Sanitasi</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('sanitasi') ?>"><i class="fas fa-arrow-left mr-2"></i>Sanitasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
       <table class="table table-bordered">
        <tr class="bg-info text-light">
            <th>No.</th>
            <th>Item Pemeriksaan</th>

            <th>Kondisi</th>
            <th>Waktu Pengecekan</th>
            <th>Tindakan</th>
            <th>Waktu Tindakan</th>
            <th>Petugas</th>
            <th>Action</th>
        </tr>
        <?php
        $no = 1;
        foreach ($data as $value) {
            ?>
            <tr>
                <td width="1"><?= $no ;?></td>
                <td><?= $value->nama_item;?></td>
                <td><?= $value->kondisi;?></td>
                <td><?= $value->jam_cek;?></td>
                <td><?= $value->tindakan_name;?></td>
                <td><?= $value->waktu_tindakan;?></td>
                <td><?= $value->petugas;?></td>
                <td>
                    <?php if ($value->kondisi != 0): ?>
                        <a href="<?= base_url('sanitasi/tindakan/' . $value->uuid); ?>" class="btn btn-md btn-info shadow-sm btn-block">Tindakan</a>
                    <?php endif; ?>
                    <!-- <a href="<?= base_url('sanitasi/editcek/' . $value->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block">Edit</a> -->
                </td>

            </tr>
            <?php 
            $no ++;

        } ?>
    </table>
</div>
</div>
<div class="row mt-2 mb-4">
    <div class="col">
        <a href="<?= base_url('sanitasi') ?>" class="btn btn-md btn-success">
            <i class="fa fa-times"></i> Kembali
        </a>
    </div>
</div>            
</div>