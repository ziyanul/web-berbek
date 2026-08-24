
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">
            Detail Data Varian
        </h1>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('Varian') ?>">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Varian
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Detail
            </li>
        </ol>
    </nav>

    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- Data Varian -->
            <h5 class="font-weight-bold text-gray-800 border-bottom pb-2 mb-4">
                Data Varian
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Nama Varian
                    </label>
                    <div class="text-muted">
                        <?= $data->varian ?>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Keterangan
                    </label>
                    <div class="text-muted">
                        <?= $data->keterangan ?>
                    </div>
                </div>
            </div>


            <!-- Size Produk -->
            <h5 class="font-weight-bold text-gray-800 border-bottom pb-2 mt-4 mb-4">
                Size Produk
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Berat
                    </label>
                    <div class="text-muted">
                        <?= $data->berat ?> Gram
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Panjang
                    </label>
                    <div class="text-muted">
                        <?= $data->panjang ?> Cm
                    </div>
                </div>
            </div>


            <!-- Filkar -->
            <h5 class="font-weight-bold text-gray-800 border-bottom pb-2 mt-4 mb-4">
                Filkar
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Berat Produk / Kontainer
                    </label>
                    <div class="text-muted">
                        <?= $data->kontainer_kg ?> Kg
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold mb-1">
                        Berat Produk / Box
                    </label>
                    <div class="text-muted">
                        <?= $data->box_kg ?> Kg
                    </div>
                </div>
            </div>


            <!-- Action -->
            <hr class="mt-4 mb-3">

            <a href="<?= base_url('Varian') ?>" class="btn btn-danger">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>

        </div>
    </div>

</div>
