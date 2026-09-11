<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">
        Cuci
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('sortasi/cuci'); ?>">
                    <i class="fas fa-arrow-left"></i>
                    Cuci
                </a>
            </li>
            <li class="breadcrumb-item active">
                Tambah
            </li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form
                action="<?= site_url('sortasi/cuci_simpan'); ?>"
                method="post"
                id="form-cuci"
            >
                <!-- =====================================================
                     VARIAN
                ====================================================== -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Varian
                        </label>
                        <select
                            name="varian_uuid"
                            id="varian_uuid"
                            class="form-control"
                            required
                        >
                            <option
                                value=""
                                selected
                                disabled
                            >
                                Pilih Varian
                            </option>
                            <?php foreach ($varian as $v) : ?>
                                <option
                                    value="<?= html_escape($v->uuid); ?>"
                                >
                                    <?= html_escape($v->varian); ?>
                                    (<?= html_escape($v->keterangan); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>
                <!-- =====================================================
                     JUMLAH CUCI
                ====================================================== -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Jumlah Cuci
                            <span class="text-danger">
                                *
                            </span>
                        </label>
                        <div class="input-group">
                            <input
                                type="number"
                                name="total_box"
                                id="total_box"
                                step="1"
                                min="1"
                                class="form-control"
                                required
                            >
                            <span class="input-group-text">
                                BOX
                            </span>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     DETAIL STOCK CUCI
                ====================================================== -->
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <small class="form-label">
                            Detail yang dicuci berdasarkan kode batch
                        </small>
                        <table
                            class="table table-bordered"
                            id="table-cuci"
                        >
                            <thead class="table bg-info text-light">
                                <tr>
                                    <th width="50">
                                        No
                                    </th>
                                    <th>
                                        Kode Batch
                                    </th>
                                    <th>
                                        Output Cuci
                                    </th>
                                    <th>
                                        Sudah Dicuci
                                    </th>
                                    <th>
                                        Sisa Cuci
                                    </th>
                                    <th width="150">
                                        Check
                                    </th>
                                    <th width="180">
                                        Cuci
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th
                                        colspan="6"
                                        class="text-end"
                                    >
                                        Total
                                    </th>
                                    <th>
                                        <input
                                            type="text"
                                            id="total-detail"
                                            class="form-control"
                                            value="0"
                                            readonly
                                        >
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <hr>
                <!-- =====================================================
                     BATCH HASIL
                ====================================================== -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kode Batch Hasil Cuci
                            <span class="text-danger">
                                *
                            </span>
                        </label>
                        <input
                            type="text"
                            name="kode_batch_hasil"
                            id="kode_batch_hasil"
                            class="form-control"
                            placeholder="Contoh: CCI-001"
                            required
                        >
                        <small class="text-muted">
                            Kode batch baru hasil proses Cuci.
                            Batch ini nantinya akan masuk ke
                            pilihan Kode Batch pada Sortasi.
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Keterangan
                        </label>
                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>
                </div>
                <hr>
                <!-- =====================================================
                     BUTTON
                ====================================================== -->
                <div class="row mt-3">
                    <div class="col">
                        <button
                            type="submit"
                            class="btn btn-success mr-2"
                        >
                            <i class="fa fa-save"></i>
                            Simpan Cuci
                        </button>
                        <a
                            href="<?= base_url('sortasi/cuci'); ?>"
                            class="btn btn-danger"
                        >
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
    /*
    |--------------------------------------------------------------------------
    | BAGI JUMLAH CUCI OTOMATIS
    |--------------------------------------------------------------------------
    */
    function distributeCuci() {
        let totalCuci =
            parseInt($('#total_box').val()) || 0;
        $('#table-cuci tbody tr').each(function() {
            let $row = $(this);
            let sisaCuci =
                parseInt(
                    $row.data('sisa-cuci')
                ) || 0;
            let $inputCuci =
                $row.find('.input-cuci');
            let $checkbox =
                $row.find('.check-item');
            if (
                totalCuci > 0 &&
                sisaCuci > 0
            ) {
                /*
                 * Ambil nilai terkecil
                 * antara total yang diminta
                 * dengan sisa batch.
                 */
                let allocation =
                    Math.min(
                        totalCuci,
                        sisaCuci
                    );
                $inputCuci.val(
                    allocation
                );
                $checkbox.prop(
                    'checked',
                    true
                );
                totalCuci -= allocation;
            } else {
                $inputCuci.val(0);
                $checkbox.prop(
                    'checked',
                    false
                );
            }
        });
        hitungTotalDetail();
    }
    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL DETAIL
    |--------------------------------------------------------------------------
    */
    function hitungTotalDetail() {
        let total = 0;
        $('#table-cuci tbody .input-cuci').each(
            function() {
                total +=
                    parseInt(
                        $(this).val()
                    ) || 0;
            }
        );
        $('#total-detail').val(total);
    }
    /*
    |--------------------------------------------------------------------------
    | PILIH VARIAN
    |--------------------------------------------------------------------------
    */
    $('select[name="varian_uuid"]').change(
        function() {
            let varian =
                $(this).val();
            if (!varian) {
                return;
            }
            /*
             * Ambil output CUCI
             * yang masih mempunyai sisa.
             */
            $.get(
                '<?= base_url("sortasi/get_cuci_by_varian/"); ?>' + varian,
                function(res) {
                    let data;
                    try {
                        data =
                            typeof res === 'string'
                                ? JSON.parse(res)
                                : res;
                    } catch (e) {
                        console.error(e);
                        return;
                    }
                    let html = '';
                    if (
                        !data ||
                        data.length === 0
                    ) {
                        html += `
                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted"
                                >
                                    Tidak ada output Cuci
                                    yang tersedia.
                                </td>
                            </tr>
                        `;
                    } else {
                        data.forEach(
                            function(v, i) {
                                html += `
                                    <tr
                                        data-sisa-cuci="${v.sisa_belum_dicuci}"
                                    >
                                        <td>
                                            ${i + 1}
                                        </td>
                                        <td>
                                            ${v.kode_batch}
                                            <input
                                                type="hidden"
                                                name="items[${i}][sortasi_uuid]"
                                                value="${v.sortasi_uuid}"
                                            >
                                            <input
                                                type="hidden"
                                                name="items[${i}][tbatch_uuid]"
                                                value="${v.tbatch_uuid}"
                                            >
                                        </td>
                                        <td>
                                            ${parseFloat(
                                                v.jumlah_output
                                            ).toFixed(0)}
                                        </td>
                                        <td>
                                            ${parseFloat(
                                                v.sudah_dicuci
                                            ).toFixed(0)}
                                        </td>
                                        <td>
                                            <strong>
                                                ${parseFloat(
                                                    v.sisa_belum_dicuci
                                                ).toFixed(0)}
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <input
                                                type="checkbox"
                                                name="items[${i}][check]"
                                                value="1"
                                                class="check-item"
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                step="1"
                                                min="0"
                                                max="${v.sisa_belum_dicuci}"
                                                name="items[${i}][jumlah]"
                                                class="form-control form-control-sm input-cuci"
                                                value="0"
                                            >
                                        </td>
                                    </tr>
                                `;
                            }
                        );
                    }
                    $('#table-cuci tbody').html(
                        html
                    );
                    /*
                     * Setelah data masuk,
                     * langsung distribusikan
                     * jumlah Cuci.
                     */
                    distributeCuci();
                }
            );
        }
    );
    /*
    |--------------------------------------------------------------------------
    | JUMLAH CUCI BERUBAH
    |--------------------------------------------------------------------------
    */
    $('#total_box').on(
        'input',
        function() {
            distributeCuci();
        }
    );
    /*
    |--------------------------------------------------------------------------
    | INPUT DETAIL BERUBAH
    |--------------------------------------------------------------------------
    */
    $(document).on(
        'input',
        '.input-cuci',
        function() {
            let $row =
                $(this).closest('tr');
            let max =
                parseInt(
                    $row.data('sisa-cuci')
                ) || 0;
            let value =
                parseInt(
                    $(this).val()
                ) || 0;
            if (value > max) {
                value = max;
            }
            if (value < 0) {
                value = 0;
            }
            $(this).val(value);
            /*
             * Jika jumlah > 0,
             * otomatis checklist.
             */
            $row.find('.check-item')
                .prop(
                    'checked',
                    value > 0
                );
            hitungTotalDetail();
        }
    );
    /*
    |--------------------------------------------------------------------------
    | CHECKBOX
    |--------------------------------------------------------------------------
    */
    $(document).on(
        'change',
        '.check-item',
        function() {
            let $row =
                $(this).closest('tr');
            let $input =
                $row.find('.input-cuci');
            if (!$(this).is(':checked')) {
                $input.val(0);
            } else {
                /*
                 * Jika dicentang tetapi
                 * jumlah masih 0,
                 * gunakan sisa batch.
                 */
                if (
                    parseInt(
                        $input.val()
                    ) <= 0
                ) {
                    $input.val(
                        $row.data('sisa-cuci')
                    );
                }
            }
            hitungTotalDetail();
        }
    );
    /*
    |--------------------------------------------------------------------------
    | VALIDASI SEBELUM SUBMIT
    |--------------------------------------------------------------------------
    */
    $('#form-cuci').submit(
        function(e) {
            let totalInput =
                parseInt(
                    $('#total_box').val()
                ) || 0;
            let totalDetail =
                parseInt(
                    $('#total-detail').val()
                ) || 0;
            if (totalInput <= 0) {
                e.preventDefault();
                alert(
                    'Jumlah Cuci harus lebih dari 0.'
                );
                return false;
            }
            if (totalDetail <= 0) {
                e.preventDefault();
                alert(
                    'Belum ada sumber batch yang dipilih.'
                );
                return false;
            }
            if (
                totalInput !==
                totalDetail
            ) {
                e.preventDefault();
                alert(
                    'Total Cuci tidak sama dengan total detail.'
                );
                return false;
            }
        }
    );
});
</script>
