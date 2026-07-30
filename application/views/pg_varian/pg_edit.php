<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Pergantian Varian Retort</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"> <a
                    href="<?= base_url('pergantian_varian_retort');?>"> <i class="fas fa-arrow-left"></i> Pergantian
                    Varian</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian_retort/detail/'.$data->tanggal) ?>">
                    Detail Pergantian Varian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pergantian_varian_retort/edit/'.$data->uuid) ?>" method="post">

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kondisi :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" name="kondisi"
                            id="kondisi">
                            <option selected disabled>Pilih Kondisi</option>
                            <option value="1" <?= set_select('kondisi', 1, $data->kondisi == 1);?>>Bersih dari
                                Kontaminasi</option>
                            <option value="2" <?= set_select('kondisi', 2, $data->kondisi == 2);?>>Belum Bersih dari
                                Kontaminasi</option>
                        </select>
                        <div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block':'';?>">
                            <?= form_error('kondisi') ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Keterangan :</label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Keterangan" value="<?= $data->keterangan; ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Ubah
                        </button>
                        <a href="<?= base_url('pergantian_varian_retort/detail/'.$data->tanggal) ?>"
                            class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>