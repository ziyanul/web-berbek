<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Edit Batch</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter') ?>">
                    <i class="fas fa-arrow-left"></i> Form Counter
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter/detail/' . $data[0]->t_planning_uuid) ?>">
                 Data Batch
             </a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Edit</li>
     </ol>
 </nav>

 <div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('Counter/editbatch/' . $data[0]->tbatch_uuid) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-2">
                    <label class="form-label font-weight-bold">Batch</label>
                </div>
                <div class="col-md-4">
                    <input type="text"
                    class="form-control"
                    value="<?= $data[0]->kode_batch; ?>"
                    name="kode_batch"
                    required>
                </div>
                <div class="col-md-3">
                    <label id="result" class="form-label font-weight-bold text-primary">Total : 0</label>
                    <input type="hidden" name="total_counter" id="total_counter">
                </div>
            </div>

            <hr>

            <?php foreach ($data as $val): ?>
                <div class="form-group row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold mb-0"><?= $val->nama_mesin; ?></label>
                    </div>

                    <div class="col-md-3">
                        <input 
                        type="number" 
                        class="form-control counter-input" 
                        value="<?= $val->counter; ?>" 
                        name="counter[]" 
                        min="0"
                        required
                        >
                    </div>

                    <!-- hidden data -->
                    <input type="hidden" name="device_id[]" value="<?= $val->device_id; ?>">
                    <input type="hidden" name="mesin_uuid[]" value="<?= $val->mesin_uuid; ?>">
                    <input type="hidden" name="speed[]" value="<?= $val->speed; ?>">
                </div>
            <?php endforeach; ?>

            <div class="row mt-4">
                <div class="col">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>

                    <a href="<?= base_url('counter/detail/' . $data[0]->t_planning_uuid) ?>" class="btn btn-danger">
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

        // tampil di label
        document.getElementById('result').innerHTML =
            'Total : ' + total.toLocaleString('id-ID');

        // SIMPAN ke hidden input (ini yang penting)
        document.getElementById('total_counter').value = total;
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateResult();

        document.querySelectorAll('.counter-input').forEach(function(input) {
            input.addEventListener('input', updateResult);
        });
    });
</script>