<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Pergantian Varian Retort</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian_retort') ?>"><i
                        class="fas fa-arrow-left mr-2"></i> Pergantian Varian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pergantian_varian_retort/tambah') ?>" method="post">
                <div class="row">
                    <!-- Varian dari Proses Produksi (Data Lama) -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Varian dari Proses Produksi :<span
                                    class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('uuid_varian_1') ? 'invalid' : '' ?>"
                                name="uuid_varian_1" id="uuid_varian_1" onchange="updateVarianName(1)">
                                <option selected disabled>Pilih Varian</option>
                                <?php if (!empty($data['previous']) && !empty($data['previous']->ST_nmproduk)): ?>
                                <option value="<?= $data['previous']->ST_uuidproduk ?>"
                                    data-nama="<?= $data['previous']->ST_nmproduk ?>"
                                    <?= set_select('uuid_varian_1', $data['previous']->ST_uuidproduk);?>>
                                    <?= $data['previous']->ST_nmproduk ?>
                                </option>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="varian_name_1" id="varian_name_1"
                                value="<?= $data['previous']->ST_nmproduk ?? '' ?>">
                        </div>
                    </div>

                    <!-- Kode dari Proses Produksi (Data Lama) -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Kode dari Proses Produksi :<span
                                    class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('uuid_kode_prod_1') ? 'invalid' : '' ?>"
                                name="uuid_kode_prod_1" id="uuid_kode_prod_1">
                                <option selected disabled>Pilih Kode</option>
                                <?php if (!empty($data['previous']) && !empty($data['previous']->ST_kodebatch)): ?>
                                <option value="<?= $data['previous']->ST_kodebatch ?>"
                                    <?= set_select('uuid_kode_prod_1', $data['previous']->ST_kodebatch);?>>
                                    <?= $data['previous']->ST_kodebatch ?>
                                </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Varian ke Proses Produksi (Data Terbaru) -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Varian ke Proses Produksi :<span
                                    class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('uuid_varian_2') ? 'invalid' : '' ?>"
                                name="uuid_varian_2" id="uuid_varian_2" onchange="updateVarianName(2)">
                                <option selected disabled>Pilih Varian</option>
                                <?php if (!empty($data['latest']) && !empty($data['latest']->ST_nmproduk)): ?>
                                <option value="<?= $data['latest']->ST_uuidproduk ?>"
                                    data-nama="<?= $data['latest']->ST_nmproduk ?>"
                                    <?= set_select('uuid_varian_2', $data['latest']->ST_uuidproduk);?>>
                                    <?= $data['latest']->ST_nmproduk ?>
                                </option>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="varian_name_2" id="varian_name_2"
                                value="<?= $data['latest']->ST_nmproduk ?? '' ?>">
                        </div>
                    </div>

                    <!-- Kode ke Proses Produksi (Data Terbaru) -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Kode ke Proses Produksi :<span
                                    class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('uuid_kode_prod_2') ? 'invalid' : '' ?>"
                                name="uuid_kode_prod_2" id="uuid_kode_prod_2">
                                <option selected disabled>Pilih Kode</option>
                                <?php if (!empty($data['latest']) && !empty($data['latest']->ST_kodebatch)): ?>
                                <option value="<?= $data['latest']->ST_kodebatch ?>"
                                    <?= set_select('uuid_kode_prod_2', $data['latest']->ST_kodebatch);?>>
                                    <?= $data['latest']->ST_kodebatch ?>
                                </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Kondisi :<span class="text-danger">*</span></label>
                            <select class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" name="kondisi"
                                id="kondisi">
                                <option selected disabled>Pilih Kondisi</option>
                                <option value="1" <?= set_select('kondisi', 1);?>>Bersih dari Kontaminasi</option>
                                <option value="2" <?= set_select('kondisi', 2);?>>Belum Bersih dari Kontaminasi</option>
                            </select>
                            <div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block':'';?>">
                                <?= form_error('kondisi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Keterangan :</label>
                            <input type="text" name="keterangan"
                                class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                                placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
                            <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                                <?= form_error('keterangan') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pergantian_varian_retort') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function updateVarianName(index) {
    let select = document.getElementById('uuid_varian_' + index);
    let selectedOption = select.options[select.selectedIndex];
    if (selectedOption.value !== "") {
        document.getElementById('varian_name_' + index).value = selectedOption.getAttribute('data-nama');
    }
}
</script>