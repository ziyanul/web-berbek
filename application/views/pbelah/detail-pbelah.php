<div class="container-fluid">
    <h2 class="h2 mb-2 text-gray-800">Detail Pengecekan Barang Pecah Belah <?= isset($hari[0]) ? $hari[0]->tgl : ''; ?> </h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pbelah/') ?>"><i class="fas fa-arrow-left mr-2"></i>Form Pengecekan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <?php if ($this->session->flashdata('success_msg')) : ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <?php if ($this->session->flashdata('error_msg')) : ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="100">Area</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="200">Sub Area</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="200">Jenis Barang</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="200">Kode Barang</th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="130">Kondisi Baik</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="150">Action</th>
                        </tr>
                        <tr>
                            <th class="font-weight-bold align-middle text-center" width="100">Ya</th>
                            <th class="font-weight-bold align-middle text-center" width="100">Tidak</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $currentArea = '';
                        $currentSubArea = '';
                        $currentJenisBarang = '';

                        foreach ($data as $row) {
                            // Rowspan untuk Area
                            if ($currentArea != $row->nama_area) {
                                $areaRowCount = count(array_filter($data, fn ($r) => $r->nama_area == $row->nama_area));
                                echo "<tr><td rowspan='$areaRowCount'>{$row->nama_area}</td>";
                                $currentArea = $row->nama_area;
                            } else {
                                echo "<tr>";
                            }


                            if ($currentSubArea != $row->lokasi) {
                                $SubareaRowCount = count(array_filter($data, fn ($r) => $r->lokasi == $row->lokasi));
                                echo "<td rowspan='$SubareaRowCount'>{$row->lokasi}</td>";
                                $currentSubArea = $row->lokasi;
                            }

                            // Rowspan untuk Jenis Barang
                            if ($currentJenisBarang != $row->jenis_barang) {
                                $jenisRowCount = count(array_filter($data, fn ($r) => $r->nama_area == $row->nama_area && $r->jenis_barang == $row->jenis_barang));
                                echo "<td rowspan='$jenisRowCount'>{$row->jenis_barang}</td>";
                                $currentJenisBarang = $row->jenis_barang;
                            }

                            // Kode Barang dan Kondisi Baik
                            echo "
                <td>{$row->kode_barang}</td>
                <td>{$row->baik}</td>
                <td>{$row->tidak}</td>
                <td>
            <a href='" . base_url('pbelah/editdetail/' . $row->uuid) . "' class='btn btn-md btn-warning'>
                <i class='fa fa-times'></i> Edit
            </a>
        </td>
            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3">
                <div class="col">

                    <a href="<?= base_url('pbelah') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>