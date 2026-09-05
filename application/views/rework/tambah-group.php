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

            <form action="<?= site_url('rework/kupas_group'); ?>" method="post" id="form-kupas">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <!-- IDENTITAS -->
                        <label class="form-label">
                            Varian
                        </label>
                        <select name="varian_uuid" id="varian_uuid" class="form-control" required>

                            <option value="" selected disabled>
                                Pilih Varian
                            </option>

                            <?php foreach ($varian as $v) : ?>
                            <option value="<?= html_escape($v->uuid) ?>">
                                <?= html_escape($v->varian) ?>
                                (<?= html_escape($v->keterangan) ?>)
                            </option>

                            <?php endforeach; ?>

                        </select>
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

                            <input type="number" name="total_berat" id="total_berat" step="0.001" min="0.001"
                                class="form-control">

                            <span class="input-group-text">
                                Kg
                            </span>

                        </div>

                    </div>

                </div>
                <!-- INFORMASI STOCK -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <small class="form-label">
                            Detail yang di kupas berdasarkan kode batch
                        </small>
                        <table class="table table-bordered" id="table-kegiatan">
                            <thead class="table bg-info text-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Batch</th>
                                    <th>Stock Rework</th>
                                    <th>Check</th>
                                    <th>Kupas</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>

                <hr>



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

<!-- <script>
$(document).ready(function() {
    $('select[name="varian_uuid"]').change(function() {

        let varian = $(this).val();

        $.get('<?= base_url("rework/get_sisa_kupas_by_varian/") ?>' + varian, function(res) {

            let data = JSON.parse(res);
            console.log(data);
            let html = '';

            data.forEach((v, i) => {
                html += `
            <tr>
                <td>${i+1}</td>
                <td>
                    ${v.kode_batch}
                    <input type="hidden" name="items[${i}][kegiatan_uuid]" value="${v.uuid}">
                    <input type="hidden" name="items[${i}][varian_uuid]" value="${varian}">
                </td>
                <td>
                    ${parseFloat(v.sisa_kupas).toFixed(3)}
                </td>
                <td>
                    ${checkbox = v.sisa_kupas > 0 ? `<input type="checkbox" name="items[${i}][check]" value="1">` : ''}
                </td>
<td>
                    <input type="text" name="berat[${i}][kegiatan_uuid]" value="${v.total_berat}">
                </td>
                    </tr>`;
            });

            $('#table-kegiatan tbody').html(html);
        });
    });
});
</script> -->

<script>
$(document).ready(function() {
    // Fungsi untuk membagi total_berat ke baris detail secara otomatis
    function distributeBerat() {
        let totalBerat = parseFloat($('#total_berat').val()) || 0;

        $('#table-kegiatan tbody tr').each(function() {
            let $row = $(this);
            let sisaKupas = parseFloat($row.data('sisa-kupas')) || 0;
            let $inputBerat = $row.find('.input-berat');
            let $checkbox = $row.find('.check-item');

            if (totalBerat > 0 && sisaKupas > 0) {
                // Ambil nilai terkecil antara sisa total berat atau sisa kupas batch tersebut
                let allocation = Math.min(totalBerat, sisaKupas);

                $inputBerat.val(allocation.toFixed(3));
                $checkbox.prop('checked', true);

                // Kurangi total berat yang tersisa
                totalBerat -= allocation;
            } else {
                $inputBerat.val('0.000');
                $checkbox.prop('checked', false);
            }
        });
    }

    // Event ketika Varian dipilih
    $('select[name="varian_uuid"]').change(function() {
        let varian = $(this).val();

        $.get('<?= base_url("rework/get_sisa_kupas_by_varian/") ?>' + varian, function(res) {
            let data = JSON.parse(res);
            let html = '';

            data.forEach((v, i) => {
                html += `
                <tr data-sisa-kupas="${v.sisa_kupas}">
                    <td>${i+1}</td>
                    <td>
                        ${v.kode_batch}
                        <input type="hidden" name="items[${i}][tbatch_uuid]" value="${v.tbatch_uuid}">
                        <input type="hidden" name="items[${i}][varian_uuid]" value="${v.uuid_varian}">
                    </td>
                    <td>
                        ${parseFloat(v.sisa_kupas).toFixed(3)}
                    </td>
                    <td>
                        <input type="checkbox" name="items[${i}][check]" value="1" class="check-item">
                    </td>
                    <td>
                        <input type="number" step="0.001" min="0" max="${v.sisa_kupas}" name="items[${i}][berat]" class="form-control form-control-sm input-berat" value="0">
                    </td>
                </tr>`;
            });

            $('#table-kegiatan tbody').html(html);

            // Panggil fungsi pembagian otomatis setelah tabel terisi
            distributeBerat();
        });
    });

    // Event ketika nilai #total_berat diketik/diubah secara real-time
    $('#total_berat').on('input', function() {
        distributeBerat();
    });
});
</script>