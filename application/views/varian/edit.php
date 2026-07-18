<div class="container-fluid">

    <h1 class="h3 mb-3 text-gray-800">Edit Data Varian</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('Varian') ?>">
                    <i class="fas fa-arrow-left"></i> Varian
                </a>
            </li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card shadow">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Form Edit Varian
            </h6>
        </div>

        <div class="card-body">

            <form action="<?= base_url('Varian/edit/'.$data->uuid) ?>" method="post">

                <!-- Data Varian -->
                <h5 class="border-bottom pb-2 mb-3">Data Varian</h5>

                <div class="form-group">
                    <label>Nama Varian <span class="text-danger">*</span></label>
                    <input type="text"
                           name="varian"
                           class="form-control <?= form_error('varian') ? 'is-invalid' : '' ?>"
                           value="<?= $data->varian ?>">

                    <div class="invalid-feedback">
                        <?= form_error('varian') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text"
                           name="keterangan"
                           class="form-control <?= form_error('keterangan') ? 'is-invalid' : '' ?>"
                           value="<?= $data->keterangan ?>">

                    <div class="invalid-feedback">
                        <?= form_error('keterangan') ?>
                    </div>
                </div>


                <!-- Size Produk -->
                <h5 class="border-bottom pb-2 mt-4 mb-3">Size Produk</h5>

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label>Berat (Gram)</label>
                        <input type="number"
                               class="form-control"
                               name="berat"
                               step="0.01" placeholder="0.000"
                               value="<?= $data->berat ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Panjang (Cm)</label>
                        <input type="number"
                               class="form-control"
                               name="panjang"
                               step="0.01" placeholder="0.000"
                               value="<?= $data->panjang ?>">
                    </div>

                </div>


                <!-- Filkar -->
                <h5 class="border-bottom pb-2 mt-4 mb-3">Filkar</h5>

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label>Berat Produk / Kontainer (Kg)</label>
                        <input type="number"
                               class="form-control"
                               name="kontainer_kg"
                               step="0.01" placeholder="0.000"
                               value="<?= $data->kontainer_kg ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Berat Produk / Box (Kg)</label>
                        <input type="number"
                               class="form-control"
                               name="box_kg"
                               step="0.001" placeholder="0.000"
                               value="<?= $data->box_kg ?>">
                    </div>

                </div>

                <hr>

                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>

                <a href="<?= base_url('Varian') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>

            </form>

        </div>

    </div>

</div>