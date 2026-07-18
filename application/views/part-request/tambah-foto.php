<style>
	.doc_wrapper{width: 200px;}
	.doc_wrapper img{width: 100%;}
	.style {margin: 0 auto;}
</style>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Foto  Repair & New Part</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('partrequest') ?>"><i class="fas fa-arrow-left mr-2"></i> Repair & New Part</a></li>
        <li class="breadcrumb-item active" aria-current="page">Foto</li>
    </ol>
</nav>
<div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('partrequest/tambahfoto/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
            <div class="row mb-3 mb-sm-0">           
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold">Sparepart :</label>
                            <?= $data->part; ?><br>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold">Foto</label><br>
                            <input type="file" name="dokumen" class="form-control <?= form_error('dokumen') ? 'invalid' : '' ?>" value="<?= set_value('dokumen'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('dokumen')) ? 'd-block':'';?>">
                                <?= form_error('dokumen') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mb-sm-0">           
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <label class="form-label font-weight-bold mt-3">Keterangan :</label>
                            <input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" value="<?= set_value('keterangan'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                                <?= form_error('keterangan') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <button type="submit" class="btn btn-md btn-success mr-2">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                            <a href="<?= base_url('partrequest')?>" class="btn btn-md btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                    
                </div>
                <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                    <h3 class="text-gray-800">Foto Dokumentasi "<?= $data->part; ?>"</h3>
                    <table class="table table-bordered">
                        <tr class="bg-info text-light">
                            
                            <th>Foto</th>
                            <th>Keterangan</th>
                        </tr>
                        <?php
                        foreach ($foto as $value) {
                            ?>
                            <tr>
                                
                                <td><div class="doc_wrapper style"><?= !empty($value->foto) ? '<img src="' . base_url('upload/'.$value->foto) . '">' : 'Belum Dokumentasi'; ?></div></td>
                                <td><?= $value->keterangan;?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
