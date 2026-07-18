<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Input Pergantian Batch</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter/') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Form Counter
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter/detail/'.$plan) ?>">
                    Data Batch
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <?php
    // mapping mesin + index speed
    $mesin_list = [
        ['kode' => 'z2', 'label' => 'ZAP 2'],
        ['kode' => 'k1', 'label' => 'KAP 1'],
        ['kode' => 'c2', 'label' => 'CAP 2'],
        ['kode' => 'c3', 'label' => 'CAP 3'],
        ['kode' => 'c4', 'label' => 'CAP 4'],
        ['kode' => 'z7', 'label' => 'ZAP 7'],
        ['kode' => 'z6', 'label' => 'ZAP 6'],
        ['kode' => 'z5', 'label' => 'ZAP 5'],
        ['kode' => 'z4', 'label' => 'ZAP 4'],
        ['kode' => 'z3', 'label' => 'ZAP 3'],
        ['kode' => 'c5', 'label' => 'CAP 5'],
        ['kode' => 'c6', 'label' => 'CAP 6'],
        ['kode' => 'z1', 'label' => 'ZAP 1'],
        ['kode' => 'c1', 'label' => 'CAP 1'],
    ];
    ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('counter/tambahbatch/'.$plan) ?>" method="post">

                <!-- Header Batch -->
                <div class="form-group row align-items-center">
                    <div class="col-md-2">
                        <label class="form-label font-weight-bold">Batch</label>
                    </div>
                    <div class="col-md-4">
                        <input
                        type="text"
                        class="form-control"
                        name="kode_batch"
                        value="<?= $next_batch['kode_batch']; ?>"
                        >
                    </div>
                    <div class="col-md-4">
                        <label id="result" class="form-label font-weight-bold text-primary mb-0">
                            Total Counter: 0 Pcs
                        </label>
                        <input type="hidden" name="total_counter" id="total_counter">
                    </div>
                </div>

                <hr>

                <!-- Input Mesin -->
                <?php foreach ($mesin_list as $mesin): ?>
                    <?php
                    $kode = $mesin['kode'];
                    $label = $mesin['label'];

                    $mesinUuid = isset($speed[$kode]['mesin_uuid']) ? $speed[$kode]['mesin_uuid'] : '';
                    $deviceId  = isset($speed[$kode]['device_id']) ? $speed[$kode]['device_id'] : $kode;
                    $speedValue = isset($speed[$kode]['speed']) ? $speed[$kode]['speed'] : 0;
                    ?>
                    <div class="form-group row align-items-center">
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold"><?= $label; ?> :</label>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="mesin_uuid[]" value="<?= $mesinUuid; ?>">
                            <input type="hidden" name="device_id[]" value="<?= $deviceId; ?>">
                            <input type="hidden" name="speed[]" value="<?= $speedValue; ?>">

                            <input 
                            type="number" 
                            class="form-control counter-input" 
                            placeholder="0" 
                            name="counter[]" 
                            id="<?= $kode; ?>"
                            min="0"
                            value="<?= set_value('counter[]'); ?>"
                            >
                        </div>

                        <div class="col-md-2">
                            <small class="text-muted">Speed: <?= number_format($speedValue); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Tombol -->
                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('counter/detail/'.$plan) ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function updateResult() {
        let total = 0;

        document.querySelectorAll('.counter-input').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });

        // update label
        document.getElementById('result').innerHTML =
            'Total Counter: ' + total.toLocaleString() + ' Pcs';

        // ⬇️ ini yang kamu kurang
        document.getElementById('total_counter').value = total;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.counter-input').forEach(function(input) {
            input.addEventListener('input', updateResult);
        });

        updateResult();
    });
</script>