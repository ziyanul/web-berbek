<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Data Pegawai</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('pegawai')?>">
                <i class="fas fa-arrow-left">
                </i> Daftar Pegawai</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="200" class="border-top-0">Nama Lengkap</td>
                                <td width="10" class="border-top-0">:</td>
                                <td class="font-weight-bold border-top-0"><?= $pegawai->fullname;?></td>
                            </tr>
                            <tr>
                                <td width="200">NIK</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->nik;?></td>
                            </tr>
                            <tr>
                                <td width="200">Email</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= empty($pegawai->email)?'-':$pegawai->email;?></td>
                            </tr>
                            <tr>
                                <td width="200">Username</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->username;?></td>
                            </tr>
                            <tr>
                                <td width="200">Tanggal Lahir</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->birth_date;?></td>
                            </tr>
                            <tr>
                                <td width="200">Tanggal Bergabung</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->join_date;?></td>
                            </tr>
                            <tr>
                                <td width="200">Tanggal Resign</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= empty($pegawai->resign_date)?'-':$pegawai->resign_date;?></td>
                            </tr>
                            <tr>
                                <td width="200">Status Pegawai</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->status_pegawai;?></td>
                            </tr>
                            <tr>
                                <td width="200">Departemen</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->departemen_name->departemen;?></td>
                            </tr>
                            <tr>
                                <td width="200">Asal Perusahaan</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->asal;?></td>
                            </tr>
                            <tr>
                                <td width="200">Pendidikan</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $pegawai->pendidikan;?></td>
                            </tr>
                            <tr>
                                <td width="200" class="border-bottom">Tipe</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="font-weight-bold border-bottom"><?= $pegawai->tipe;?></td>
                            </tr>
                            <tr>
                                <td width="200" class="border-bottom">Hak Akses</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="font-weight-bold border-bottom">
                                    <?php 
                                        if($pegawai->hak_akses == 'engineering'){
                                            echo "Engineering";
                                        } else if($pegawai->hak_akses == 'pga'){
                                            echo "P&GA";
                                        } else if($pegawai->hak_akses == 'produksi'){
                                            echo "Produksi";
                                        } else if($pegawai->hak_akses == "qc"){
                                            echo "Quality Control";
                                        } else if($pegawai->hak_akses == 'warehouse'){
                                            echo "Warehouse";
                                        } 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="200" class="border-bottom">Jenis Kelamin</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="font-weight-bold border-bottom"><?= $pegawai->jenis_kelamin== 1?'Laki-Laki':'Perempuan';?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?= base_url('pegawai')?>" class="btn btn-danger mt-3"><i class="fa fa-arrow-left"> Kembali</i></a>
                </div>
                <!-- <div class="col-md-7">
                    <h5 class="font-weight-bold">Training List</h5>
                    <hr>
                    <table class="table table-bordered table-success">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Nama Training</th>
                                <th rowspan="2" class="align-middle text-center">Tanggal Pelaksanaan</th>
                                <th colspan="2" class="text-center">Nilai</th>
                                <th colspan="2" class="text-center">File</th>
                            </tr>
                            <tr>
                                <th>Pre</th>
                                <th>Post</th>
                                <th>Evaluasi</th>
                                <th>Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // if(sizeof($training) <= 0){
                                //     echo "<tr><td colspan='7' class='text-center'>Belum ada data</td></tr>";
                                // }
                                // $no=1;
                                // foreach($training as $row){
                            ?>
                                <tr>
                                    <td><?= $no;?></td>
                                    <td><?= $row->materi;?></td>
                                    <td><?= $row->jadwal;?></td>
                                    <td><?= $row->pre_test;?></td>
                                    <td><?= $row->post_test;?></td>
                                    <td><a href="<?= base_url('uploads/'.$row->file_eval);?>" target="_blank" class="btn btn-sm btn-info">Lihat</a></td>
                                    <td><a href="<?= base_url('uploads/'.$row->file_sertif);?>" target="_blank" class="btn btn-sm btn-info">Lihat</a></td>
                                </tr>
                            <?php // $no++; } ?>
                        </tbody>
                    </table>
                </div> -->
            </div>
    </div>
</div>
</div>