<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Downtime Mesin Filler</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('filler/performance/'. $quality->t_planning_uuid) ?>"><i class="fas fa-arrow-left mr-2"></i>Performa Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Downtime</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('filler/tambahdowntime/'. $quality->uuid) ?>" method="post">
                <div class="row mb-3 mb-sm-0">           
                    <div class="col-sm-6 mb-3 mb-sm-0">


                        <label class="form-label font-weight-bold">Mesin : <?= $quality->mesin ?></label>

                        <br>
                        <label class="form-label">Mesin Off (Menit) :</label>
                        <input type="text" id="berat_kg" name="jumlah" class="form-control <?= form_error('jumlah') ? 'is-invalid' : '' ?>" placeholder="" value="<?= set_value('jumlah'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('jumlah') ?>
                        </div><br>
                        <label class="form-label">Keterangan :</label>
                        <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'is-invalid' : '' ?>" placeholder="" value="<?= set_value('keterangan'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('keterangan') ?>
                        </div>
                        <div class="row mt-5">
                            <div class="col">
                                <button type="submit" class="btn btn-md btn-success mr-2">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                                <a href="<?= base_url('filler/performance/' . $quality->t_planning_uuid) ?>" class="btn btn-md btn-danger">
                                    <i class="fa fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <table class="table">
                            <thead class='bg-info text-light text-center'>
                                <tr>
                                    <th>Mesin Off</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($rincian as $row) {

                                    ?>
                                    <tr>
                                        <td><?= $row->downtime ?></td>
                                        <td><?= $row->keterangan ?></td>
                                        <td><a href="<?= base_url('filler/hapusdowntime/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash mr-2 fa-sm text-white"></i>Hapus</a></td>
                                    </tr>

                                    <?php

                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
