<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Input Hasil Evaluasi</h1>
    
<div class="card shadow mb-4">
    <div class="card-body">

        <div class="row">
            <div class="col">
                <form class="user" action="<?= base_url('tracking/tambahhasil/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label">CAP :</label>
                            <?= $data->cap; ?><br>
                        </div>
                    </div>
                    
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-8 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Evaluasi :</label>
                           <input type="text" name="evaluasi" class="form-control <?= form_error('evaluasi') ? 'invalid' : '' ?> " placeholder="Input Hasil Evaluasi" value="<?= set_value('evaluasi'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('evaluasi')) ? 'd-block':'';?>">
                            <?= form_error('evaluasi') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-8 mb-3 mt-3 mb-sm-0">
                            <label class="form-label">Status <span class="text-danger">*</span></label><br>
                            <select class="form-control <?= form_error('status') ? 'invalid' : '' ?>" name="status" id="status">
                                <option selected disabled>Pilih Status</option>
                                
                                <option value="1" <?= set_select('status', 1);?>>CLOSED</option>
                                <option value="2" <?= set_select('status', 2);?>>BELUM</option>

                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('status')) ? 'd-block':'';?>">
                                <?= form_error('status') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">
                        <div class="col-sm-4 mb-3 mt-3 mb-sm-0">
                           <label class="form-label">Dokumentasi :</label>
                           <input type="file" name="fdok_hasil" class="form-control <?= form_error('fdok_hasil') ? 'invalid' : '' ?> " placeholder="Input Hasil dok_hasil" value="<?= set_value('fdok_hasil'); ?>">
                           <div class="invalid-feedback <?= !empty(form_error('fdok_hasil')) ? 'd-block':'';?>">
                            <?= form_error('fdok_hasil') ?>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('tracking/afterhasil/' .$data->t_issue_uuid) ?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
                <h3 class="text-gray-800">Evaluasi-evaluasi dari <?= $data->cap ?></h3>
                <table class="table table-bordered">
                    <tr class="bg-info text-light">
                        <th>Tanggal</th>
                        <th>Evaluasi</th>
                        <th>Dokumentasi</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    foreach ($hasil as $value) {
                        $tgl = strtotime($value->created_at);
                        $value->tanggal = date('H M Y', $tgl);
                        ?>
                        <tr>
                            <td><?= $value->tanggal;?></td>
                            <td><?= $value->evaluasi;?></td>
                            <td class="text-center">
    <?php
    $imagePath = 'upload/' . $value->dok_hasil;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
                            <td><?= $value->status;?></td>
                        </tr>
                    <?php } ?>

                </table>

            </div>
        </div>




    </div>
</div>
</div>
