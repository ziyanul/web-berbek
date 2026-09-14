<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-2 text-gray-800">
            Tambah Drystore
        </h1>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('drystore') ?>"><i class="fas fa-arrow-left"></i>
                    Drystore</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>




    <?php if ($this->session->flashdata('error_msg')): ?>

        <div class="alert alert-danger text-center">

            <i class="fas fa-times mr-2"></i>

            <?= $this->session->flashdata('error_msg'); ?>

        </div>

        <br>

    <?php endif; ?>


    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                Input Drystore
            </h6>

        </div>


        <div class="card-body">
            <form action="<?= base_url('drystore/simpan'); ?>" method="post">

                <!-- TANGGAL -->

                <div class="row mb-4">
                    <div class="col-md-4">

                        <label class="font-weight-bold">
                            Tanggal
                        </label>

                        <div class="input-group bg-light">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-0 bg-transparent">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                            </div>

                            <input type="date" id="tanggal" name="tanggal" class="form-control text-right"
                                value="<?= date('Y-m-d', strtotime($tanggal)) ?>">
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Varian</th>
                                <th class="text-right">Total Release</th>
                            </tr>
                        </thead>

                        <tbody id="releaseTable">
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Loading...
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr class="font-weight-bold">
                                <td>Total</td>
                                <td id="totalRelease" class="text-right">
                                    0
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row">

                    <?php foreach ($types as $type): ?>

                        <div class="col-lg-6 mb-4">

                            <div class="card border-left-primary shadow-sm h-100">

                                <div class="card-header py-2">

                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-box mr-2"></i>
                                        <?= htmlspecialchars($type->nama); ?>
                                    </h6>

                                </div>

                                <div class="card-body py-2">

                                    <?php foreach ($wastes as $waste): ?>

                                        <div class="form-group row align-items-center mb-2">
                                            <label class="col-sm-7 col-form-label py-1">
                                                <?= htmlspecialchars($waste->nama); ?>
                                            </label>
                                            <div class="col-sm-5">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="waste[<?= $type->uuid; ?>][<?= $waste->uuid; ?>]"
                                                        class="form-control text-right" step="0.001" min="0" placeholder="0">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><?= $type->satuan ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


                <!-- BUTTON -->

                <div class="text-left mt-4">
                    <button type="submit" class="btn btn-success shadow-sm mr-2">

                        <i class="fas fa-save mr-2"></i>
                        Simpan

                    </button>
                    <a href="<?= base_url('drystore'); ?>" class="btn btn-danger shadow-sm mr-2">

                        <i class="fas fa-times mr-2"></i>
                        Batal

                    </a>




                </div>


            </form>

        </div>

    </div>

</div>
<script>
    $(document).ready(function() {

        function loadRelease(tanggal) {

            if (!tanggal) {
                return;
            }

            $('#releaseTable').html(`
                <tr>
                    <td colspan="2" class="text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading...
                    </td>
                </tr>
            `);

            $('#totalRelease').text('0');

            $.ajax({
                url: "<?= base_url('Drystore/get_release'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    tanggal: tanggal
                },

                success: function(response) {

                    if (!response.status) {

                        $('#releaseTable').html(`
                            <tr>
                                <td colspan="2" class="text-center text-danger">
                                    ${response.message}
                                </td>
                            </tr>
                        `);

                        return;
                    }

                    let html = '';
                    let total = 0;

                    if (response.data.length === 0) {

                        html = `
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Tidak ada data release pada tanggal tersebut.
                                </td>
                            </tr>
                        `;

                    } else {

                        $.each(response.data, function(index, row) {

                            const release = parseFloat(row.total_release) || 0;

                            total += release;

                            html += `
                                <tr>
                                    <td>
                                        ${row.varian ?? '-'}
                                    </td>

                                    <td class="text-right">
                                        ${release.toLocaleString('id-ID')}
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    $('#releaseTable').html(html);

                    $('#totalRelease').text(
                        total.toLocaleString('id-ID')
                    );
                },

                error: function(xhr) {

                    console.error(xhr.responseText);

                    $('#releaseTable').html(`
                        <tr>
                            <td colspan="2" class="text-center text-danger">
                                Terjadi kesalahan saat mengambil data.
                            </td>
                        </tr>
                    `);

                    $('#totalRelease').text('0');
                }
            });
        }


        // Ketika tanggal diubah
        $('#tanggal').on('change', function() {

            const tanggal = $(this).val();

            loadRelease(tanggal);

        });


        // Ketika halaman pertama kali dibuka
        const tanggalAwal = $('#tanggal').val();

        loadRelease(tanggalAwal);

    });
</script>