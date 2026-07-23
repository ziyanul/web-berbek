<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800"> Data Kode Benda Tajam</h1>
        <a href="<?= base_url('pbtajam/tambah_kode'); ?>" class="btn btn-md btn-primary shadow-sm mr-2"><i
                class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
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
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class="align-middle" width="1">No.</th>
                            <th>Area</th>
                            <th>Jenis Benda Tajam</th>
                            <th>Tanggal</th>
                            <th>Kode Benda Tajam</th>
                            <th class="align-middle text-center" width="300px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
        $no = 1;
        $currentArea = '';
        $currentJenis = '';
        $areaCount = [];
        $jenisCount = [];
        $jenisTanggalCount = [];
        
        // Hitung jumlah baris per area, jenis benda tajam, dan kombinasi jenis-tanggal
        foreach ($data as $row) {
            $areaCount[$row->nama_area] = ($areaCount[$row->nama_area] ?? 0) + 1;
            $jenisCount[$row->jenis_btajam_uuid] = ($jenisCount[$row->jenis_btajam_uuid] ?? 0) + 1;
            
            // Kunci unik untuk jenis dan tanggal
            $jenisTanggalKey = $row->jenis_btajam_uuid . '_' . $row->tgl;
            $jenisTanggalCount[$jenisTanggalKey] = ($jenisTanggalCount[$jenisTanggalKey] ?? 0) + 1;
        }

        $displayedArea = [];
        $displayedJenis = [];
        $displayedJenisTanggal = [];

        foreach ($data as $row) {
            ?>
                        <tr>
                            <!-- Kolom Area dengan Rowspan -->
                            <?php if (!isset($displayedArea[$row->nama_area])) { ?>
                            <td class='align-middle text-center' rowspan="<?= $areaCount[$row->nama_area]; ?>">
                                <?= $no++; ?>
                            </td>
                            <td class='align-middle' rowspan="<?= $areaCount[$row->nama_area]; ?>">
                                <?= $row->nama_area; ?>
                            </td>
                            <?php 
                    $displayedArea[$row->nama_area] = true; 
                } ?>

                            <!-- Kolom Jenis Benda Tajam dengan Rowspan -->
                            <?php if (!isset($displayedJenis[$row->jenis_btajam_uuid])) { ?>
                            <td class='align-middle' rowspan="<?= $jenisCount[$row->jenis_btajam_uuid]; ?>">
                                <?= $row->jenis_benda; ?>
                            </td>
                            <?php 
                    $displayedJenis[$row->jenis_btajam_uuid] = true; 
                } ?>

                            <!-- Kolom Tanggal dengan Rowspan, dipisah per Jenis Benda -->
                            <?php 
                $jenisTanggalKey = $row->jenis_btajam_uuid . '_' . $row->tgl;
                if (!isset($displayedJenisTanggal[$jenisTanggalKey])) { ?>
                            <td class='align-middle' rowspan="<?= $jenisTanggalCount[$jenisTanggalKey]; ?>">
                                <?= $row->tgl; ?>
                            </td>
                            <?php 
                    $displayedJenisTanggal[$jenisTanggalKey] = true; 
                } ?>

                            <!-- Kolom Kode Benda Tajam -->
                            <td class='align-middle'><?= $row->kode_benda; ?></td>

                            <!-- Kolom Action -->
                            <td class='align-middle text-center'>
                                <a href="<?= base_url('Pbtajam/editkodebt/'.$row->uuid); ?>"
                                    class="btn btn-md btn-warning mb-2 mt-2">
                                    <i class="fa fa-edit mr-2"></i> Edit Kode Benda</a>
                                </a>
                            </td>
                        </tr>
                        <?php 
        } 
        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>