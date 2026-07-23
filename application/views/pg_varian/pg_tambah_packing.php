<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Pergantian Varian Packing</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian_packing') ?>"><i
                        class="fas fa-arrow-left"></i> Pergantian Varian</a></li>
            <li class="breadcrumb-item"><a
                    href="<?= base_url('pergantian_varian_packing/detail/'.$varian->tanggal.'/'.$varian->shift) ?>">Detail Pergantian Varian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pergantian_varian_packing/tambah/'.$varian->uuid) ?>"
                method="post">

                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Kondisi :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" name="kondisi"
                            id="kondisi">
                            <option selected disabled>Pilih Kondisi</option>
                            <option value="1" <?= set_select('kondisi', 1);?>>Bersih dari Kontaminasi</option>
                            <option value="2" <?= set_select('kondisi', 2);?>>Belum Bersih dari Kontaminasi</option>
                        </select>
                        <input type="hidden" name="area_uuid" value="<?= isset($varian->uuid) ? $varian->uuid : ''; ?>">
                        <input type="hidden" name="varian_uuid"
                            value="<?= isset($varian->varian_uuid) ? $varian->varian_uuid : ''; ?>">
                        <input type="hidden" name="kode_prod"
                            value="<?= isset($varian->kode_prod) ? $varian->kode_prod : ''; ?>">
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
                            placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pergantian_varian_packing/detail/'.$varian->tanggal.'/'.$varian->shift) ?>"
                            class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>