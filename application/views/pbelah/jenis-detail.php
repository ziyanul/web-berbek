<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Jenis Barang Pecah Belah Area <?= $data[0]->lokasi ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pbelah/jenis');?>"><i class="fas fa-arrow-left"></i> Data
            Jenis Barang Pecah Belah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jenis Barang</li>
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
                                <th class="align-middle" width="1">No.</th>
                                <th>Sub Area</th>
                                <th>Jenis Benda</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
        $current_sub_area = null; // Untuk melacak area saat ini
        $sub_area_count = []; // Untuk menghitung jumlah sub-area per area

        // Hitung jumlah sub-area untuk setiap area_uuid
        foreach ($data as $row) {
            if (!isset($sub_area_count[$row->sub_area_uuid])) {
                $sub_area_count[$row->sub_area_uuid] = 0;
            }
            $sub_area_count[$row->sub_area_uuid]++;
        }

        // Tampilkan data dengan rowspan
        foreach ($data as $row) { ?>
            <tr>
                <?php 
                // Cek apakah area ini sudah ditampilkan sebelumnya
                if ($current_sub_area != $row->sub_area_uuid) { 
                    $current_sub_area = $row->sub_area_uuid; ?>
                    <td rowspan="<?= $sub_area_count[$row->sub_area_uuid]; ?>"><?= $no++; ?></td>
                    <td rowspan="<?= $sub_area_count[$row->sub_area_uuid]; ?>"><?= $row->lokasi; ?></td>
                <?php } ?>
                <td><?= $row->jenis_barang; ?></td>
                <td>
                    <a href="<?= base_url('Pbelah/editjenispb/' . $row->uuid); ?>" 
                       class="btn btn-md btn-warning btn-block">
                       <i class="fa fa-edit mr-2"> Edit</i>
                   </a>
               </td>
           </tr>
       <?php } ?>
   </tbody>
</table>

<div class="row mt-3">
                    <div class="col">
                       
                        <a href="<?= base_url('pbelah/jenis') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                </div>
</div>
</div>

