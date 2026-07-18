<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Data Jenis Barang Pecah Belah</h1>
        <a href="<?= base_url ('pbelah/tambahkode') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
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
                                <th>Sub Area</th>
                                <th>Jenis</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
        $current_sub_area = null; // Untuk melacak sub area yang sedang diproses
        $sub_area_count = []; // Menyimpan jumlah jenis barang per sub area

        // Hitung jumlah jenis barang untuk setiap sub area
        foreach ($data as $row) {
            if (!isset($sub_area_count[$row->lokasi])) {
                $sub_area_count[$row->lokasi] = 0;
            }
            $sub_area_count[$row->lokasi]++;
        }

        // Tampilkan data dengan rowspan
        foreach ($data as $row) { ?>
            <tr>
                <?php 
                // Cek apakah sub area ini sudah dicetak sebelumnya
                if ($current_sub_area != $row->lokasi) { 
                    $current_sub_area = $row->lokasi; ?>
                    <td rowspan="<?= $sub_area_count[$row->lokasi]; ?>"><?= $no++; ?></td>
                    <td rowspan="<?= $sub_area_count[$row->lokasi]; ?>"><?= $row->lokasi; ?></td>
                <?php } ?>
                <td><?= $row->jenis_barang; ?></td>
                <td>
                    <a href="<?= base_url('Pbelah/detailkodepb/'. $row->jenis_pbelah_uuid); ?>" 
                     class="btn btn-md btn-success btn-block">
                     <i class="fa fa-search mr-2"> Detail Kode Barang</i>
                 </a>
             </td>
         </tr>
     <?php } ?>
 </tbody>
</table>

</div>
</div>
</div>
