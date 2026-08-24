<div class="container-fluid">

    <h3 class="h3 mb-2 text-gray-800">
        Kupas Rework
    </h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('rework'); ?>">
                    <i class="fas fa-arrow-left"></i>
                    Rework
                </a>
            </li>
            <li class="breadcrumb-item active">
                Kupas
            </li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="<?= site_url('rework/simpan_kupas'); ?>" method="post" id="form-kupas">

                <!-- IDENTITAS -->
                <input type="hidden" name="tbatch_uuid" value="<?= $stock->tbatch_uuid ?>">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Varian
                        </label>

                        <input type="text" class="form-control" value="<?= $stock->nama_varian ?> (<?= $stock->keterangan ?>)" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kode Batch
                        </label>

                        <input type="text" class="form-control" value="<?= $stock->kode_batch ?>" readonly>
                    </div>

                </div>

                <hr>

                <!-- INFORMASI STOCK -->
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Total Rework
                        </label>

                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= number_format($stock->total_rework, 2, '.', '') ?>" readonly>

                            <span class="input-group-text">
                                Kg
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Sudah Dikupas
                        </label>

                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= number_format($stock->total_kupas, 2, '.', '') ?>" readonly>

                            <span class="input-group-text">
                                Kg
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Belum Dikupas
                        </label>

                        <div class="input-group">
                            <input type="text" class="form-control font-weight-bold" value="<?= number_format($stock->sisa_kupas, 2, '.', '') ?>" readonly>

                            <span class="input-group-text">
                                Kg
                            </span>
                        </div>
                    </div>

                </div>

                <hr>

                <!-- INPUT KUPAS -->
                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Jumlah Kupas
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input type="number" name="berat" id="berat" step="0.01" min="0.01" max="<?= $stock->sisa_kupas ?>" class="form-control" placeholder="Maksimal <?= number_format($stock->sisa_kupas, 2, '.', '') ?>" required>

                            <span class="input-group-text">
                                Kg
                            </span>

                        </div>

                        <small class="form-text text-muted">
                            Maksimal dapat dikupas
                            <?= number_format($stock->sisa_kupas, 2, '.', '') ?> Kg.
                        </small>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col">

                        <button type="submit" class="btn btn-success mr-2">

                            <i class="fa fa-save"></i>
                            Simpan Kupas

                        </button>

                        <a href="<?= base_url('rework/kupas'); ?>" class="btn btn-danger">

                            <i class="fa fa-times"></i>
                            Batal

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

</div>

<script>
    $(document).ready(function() {

        $('#form-kupas').on('submit', function(e) {

            var input = parseFloat($('#berat').val());
            var max = parseFloat($('#berat').attr('max'));

            if (!input || input <= 0) {
                e.preventDefault();

                alert('Jumlah kupas harus lebih dari 0.');
                return;
            }

            if (input > max) {
                e.preventDefault();

                alert(
                    'Jumlah kupas tidak boleh melebihi sisa rework: ' +
                    max.toFixed(2) +
                    ' Kg.'
                );

                $('#berat').val('').focus();
            }

        });

    });
</script>