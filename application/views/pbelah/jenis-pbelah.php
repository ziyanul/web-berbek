<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Data Jenis Barang Pecah Belah</h1>
        <a href="<?= base_url ('pbelah/tambahjenis') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>
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
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
        $current_area = null; // Untuk melacak area saat ini
        $area_count = []; // Untuk menghitung jumlah sub-area per area

        // Hitung jumlah sub-area untuk setiap area_uuid
        foreach ($data as $row) {
            if (!isset($area_count[$row->area_uuid])) {
                $area_count[$row->area_uuid] = 0;
            }
            $area_count[$row->area_uuid]++;
        }

        // Tampilkan data dengan rowspan
        foreach ($data as $row) { ?>
            <tr>
                <?php 
                // Cek apakah area ini sudah ditampilkan sebelumnya
                if ($current_area != $row->area_uuid) { 
                    $current_area = $row->area_uuid; ?>
                    <td rowspan="<?= $area_count[$row->area_uuid]; ?>"><?= $no++; ?></td>
                    <td rowspan="<?= $area_count[$row->area_uuid]; ?>"><?= $row->nama_area; ?></td>
                <?php } ?>
                <td><?= $row->lokasi; ?></td>
                <td>
                    <a href="<?= base_url('Pbelah/detailjenis/' . $row->sub_area_uuid); ?>" 
                     class="btn btn-md btn-success btn-block">
                     <i class="fa fa-search mr-2"> Detail Jenis Barang</i>
                 </a>
             </td>
         </tr>
     <?php } ?>
 </tbody>
</table>

</div>
</div>
</div>
