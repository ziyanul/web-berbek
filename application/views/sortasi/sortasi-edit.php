<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-edit mr-2"></i>Edit Sortasi
            </h1>
        </div>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?= base_url('sortasi') ?>">
                    <i class="fas fa-arrow-left mr-1"></i>Sortasi
                </a>
            </li>
            <li class="breadcrumb-item active">
                Edit Data
            </li>
        </ol>
    </nav>
    <?php
    /*
     * Batch yang dipilih:
     * - jika validasi gagal, gunakan POST
     * - jika halaman pertama kali dibuka, gunakan batch transaksi lama
     */
    $selectedBatchUuid = set_value(
        'tbatch_uuid',
        $data->tbatch_uuid
    );
    /*
     * Output lama dari transaksi.
     */
    $oldOutput = [
        'RELEASE' => 0,
        'TAMPUNG' => 0,
        'KASAR'   => 0,
        'CUCI'    => 0
    ];
    foreach ($output as $o) {
        if (isset($oldOutput[$o->jenis_output])) {
            $oldOutput[$o->jenis_output] = (float) $o->jumlah;
        }
    }
    /*
     * WIP lama dari transaksi.
     */
    $oldInput = (float) $data->jumlah_wip;
    /*
     * Box yang digunakan dikonversi ke KG.
     */
    $oldKg = $oldInput * (float) $data->box_kg;
    /*
     * Data WIP untuk JavaScript.
     */
    $wipJson = json_encode(
        $wip ?? [],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    /*
     * Data Bad Produk untuk JavaScript.
     */
    $badproJson = json_encode(
        $badpro ?? [],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="formSortasi" action="<?= base_url('sortasi/edit/' . $data->uuid) ?>" method="post">
                <!-- =====================================================
                     BATCH & JENIS SORTASI
                ====================================================== -->
                <div class="card border-left-primary mb-4">
                    <div class="card-header bg-light">
                        <strong>
                            <i class="fas fa-boxes mr-2"></i>
                            Informasi Sortasi
                        </strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- BATCH -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="tbatch_uuid">
                                        Kode Batch
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="tbatch_uuid" id="tbatch_uuid" class="form-control" required>
                                        <option value="">
                                            -- Pilih Batch --
                                        </option>
                                        <?php foreach ($batch as $b): ?>
                                            <?php
                                            $boxKgBatch = (float) $b->box_kg;
                                            $sisaBox    = (float) $b->sisa_wip;
                                            $sisaKg     = $sisaBox * $boxKgBatch;
                                            ?>
                                            <option value="<?= html_escape($b->uuid) ?>" data-box-kg="<?= $boxKgBatch ?>"
                                                data-sisa-box="<?= $sisaBox ?>" data-sisa-kg="<?= $sisaKg ?>"
                                                <?= $b->uuid == $selectedBatchUuid ? 'selected' : '' ?>>
                                                <?= html_escape($b->kode_batch) ?>
                                                -
                                                <?= html_escape($b->varian) ?>
                                                |
                                                Sisa WIP:
                                                <?= number_format($sisaBox, 3, ',', '.') ?>
                                                Box
                                                |
                                                <?= number_format($sisaKg, 3, ',', '.') ?>
                                                Kg
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-danger">
                                        <?= form_error('tbatch_uuid') ?>
                                    </small>
                                </div>
                            </div>
                            <!-- JENIS SORTASI -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenis_sortasi_uuid">
                                        Jenis Sortasi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="jenis_sortasi_uuid" id="jenis_sortasi_uuid" class="form-control"
                                        required>
                                        <option value="">
                                            -- Pilih Jenis Sortasi --
                                        </option>
                                        <?php foreach ($jenis_sortasi as $j): ?>
                                            <option value="<?= html_escape($j->uuid) ?>" <?= $j->uuid == set_value(
                                                                                                'jenis_sortasi_uuid',
                                                                                                $data->jenis_sortasi_uuid
                                                                                            ) ? 'selected' : '' ?>>
                                                <?= html_escape($j->nama) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-danger">
                                        <?= form_error('jenis_sortasi_uuid') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     INFORMASI WIP
                ====================================================== -->
                <div id="wipInfo" class="alert alert-info border-left-info shadow-sm">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi WIP Batch</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Sisa WIP
                            </div>
                            <div class="font-weight-bold">
                                <span id="sisaWipBox">0.000</span>
                                Box
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Sisa WIP
                            </div>
                            <div class="font-weight-bold">
                                <span id="sisaWipKg">0.000</span>
                                Kg
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     WIP YANG DIGUNAKAN
                ====================================================== -->
                <div class="card border-left-warning mb-4">
                    <div class="card-header bg-light">
                        <strong>
                            <i class="fas fa-weight-hanging mr-2"></i>
                            WIP yang Digunakan
                        </strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- KG -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="wip_kg">
                                        WIP (Kg)
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="wip_kg" class="form-control" min="0" step="0.001">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                Kg
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- BOX -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="wip_box">
                                        WIP (Box)
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="wip_box" class="form-control" min="0" step="0.001">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                Box
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="small text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Isi salah satu nilai. Nilai lainnya akan dihitung otomatis.
                        </div>
                        <input type="hidden" name="jumlah_sortir" id="jumlah_sortir" value="<?= $oldInput ?>">
                        <div id="wipHidden"></div>
                        <small class="text-danger">
                            <?= form_error('jumlah_sortir') ?>
                        </small>
                    </div>
                </div>
                <!-- =====================================================
                     HASIL SORTASI
                ====================================================== -->
                <div class="card border-left-success mb-4">
                    <div class="card-header bg-light">
                        <strong>
                            <i class="fas fa-chart-pie mr-2"></i>
                            Hasil Sortasi
                        </strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $outputFields = [
                                'release_box' => [
                                    'label' => 'Release',
                                    'key'   => 'RELEASE'
                                ],
                                'output_tampung' => [
                                    'label' => 'Tampung',
                                    'key'   => 'TAMPUNG'
                                ],
                                'output_kasar' => [
                                    'label' => 'Kasar',
                                    'key'   => 'KASAR'
                                ],
                                'output_cuci' => [
                                    'label' => 'Cuci',
                                    'key'   => 'CUCI'
                                ]
                            ];
                            ?>
                            <?php foreach ($outputFields as $name => $cfg): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="<?= $name ?>">
                                            <?= $cfg['label'] ?>
                                            (Box)
                                        </label>
                                        <input type="number" name="<?= $name ?>" id="<?= $name ?>"
                                            class="form-control outputBox" min="0" step="0.001" value="<?= set_value(
                                                                                                            $name,
                                                                                                            $oldOutput[$cfg['key']]
                                                                                                        ) ?>">
                                        <small class="text-danger">
                                            <?= form_error($name) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="alert alert-secondary mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <span>
                                        <i class="fas fa-question-circle mr-1"></i>
                                        Belum teridentifikasi
                                    </span>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <strong>
                                        <span id="sisaKg">0.000</span>
                                        Kg
                                        /
                                        <span id="sisaBox">0.000</span>
                                        Box
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     BAD PRODUK
                ====================================================== -->
                <div class="card border-left-danger mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Bad Produk
                            </strong>
                            <button type="button" id="btnTambahBad" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="badContainer">
                            <?php if (empty($badpro_input)): ?>
                                <div class="text-center text-muted py-3" id="emptyBadMessage">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Tidak ada Bad Produk.
                                </div>
                            <?php else: ?>
                                <?php foreach ($badpro_input as $i => $bp): ?>
                                    <div class="card border mb-3 bad-card" data-i="<?= $i ?>">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- BAD PRODUK -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-md-0">
                                                        <label>
                                                            Bad Produk
                                                        </label>
                                                        <select name="badpro_uuid[]" class="form-control badSelect" required>
                                                            <option value="">
                                                                Pilih Bad Produk
                                                            </option>
                                                            <?php foreach ($badpro as $b): ?>
                                                                <option value="<?= html_escape($b->uuid_badpro) ?>"
                                                                    data-kategori="<?= html_escape($b->kategori_nama) ?>"
                                                                    <?= $b->uuid_badpro == $bp->badpro_uuid ? 'selected' : '' ?>>
                                                                    <?= html_escape($b->nama_badpro) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- KATEGORI -->
                                                <div class="col-md-2">
                                                    <div class="form-group mb-md-0">
                                                        <label>
                                                            Kategori
                                                        </label>
                                                        <input type="text" class="form-control kategori"
                                                            value="<?= html_escape($b->kategori_nama) ?>" readonly>
                                                    </div>
                                                </div>
                                                <!-- BERAT -->
                                                <div class="col-md-2">
                                                    <div class="form-group mb-md-0">
                                                        <label>
                                                            Berat (Kg)
                                                        </label>
                                                        <input type="number" name="badpro_berat[]"
                                                            class="form-control badWeight" min="0" step="0.001" value="<?= set_value(
                                                                                                                            'badpro_berat[]',
                                                                                                                            (float) $bp->berat
                                                                                                                        ) ?>" required>
                                                    </div>
                                                </div>
                                                <!-- MESIN -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-md-0">
                                                        <label>
                                                            Mesin Dominan
                                                            <small class="text-muted">
                                                                (opsional)
                                                            </small>
                                                        </label>
                                                        <select name="mesin_uuid[<?= $i ?>][]" class="form-control mesin"
                                                            multiple select2>
                                                            <?php foreach ($mesin as $m): ?>
                                                                <?php
                                                                $machineSelected = false;
                                                                if (!empty($bp->mesin)) {
                                                                    foreach ($bp->mesin as $sm) {
                                                                        if ($sm->uuid == $m->uuid) {
                                                                            $machineSelected = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <option value="<?= html_escape($m->uuid) ?>"
                                                                    <?= $machineSelected ? 'selected' : '' ?>>
                                                                    <?= html_escape($m->nama_mesin) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right mt-3">
                                                <button type="button" class="btn btn-outline-danger btn-sm removeBad">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="text-right border-top pt-3 mt-3">
                            <strong>
                                Total Bad:
                                <span id="totalBad">0.000</span>
                                Kg
                            </strong>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     WAKTU & TENAGA KERJA
                ====================================================== -->
                <div class="card border-left-secondary mb-4">
                    <div class="card-header bg-light">
                        <strong>
                            <i class="fas fa-clock mr-2"></i>
                            Waktu & Tenaga Kerja
                        </strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- MULAI -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mulai">
                                        Jam Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="mulai" id="mulai" class="form-control" value="<?= set_value(
                                                                                                                'mulai',
                                                                                                                $data->jam_mulai
                                                                                                            ) ?>" required>
                                    <small class="text-danger">
                                        <?= form_error('mulai') ?>
                                    </small>
                                </div>
                            </div>
                            <!-- SELESAI -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selesai">
                                        Jam Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="selesai" id="selesai" class="form-control" value="<?= set_value(
                                                                                                                    'selesai',
                                                                                                                    $data->jam_selesai
                                                                                                                ) ?>" required>
                                    <small class="text-danger">
                                        <?= form_error('selesai') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- MP -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jml_mp">
                                        Jumlah Man Power
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="jml_mp" id="jml_mp" class="form-control" min="1" value="<?= set_value(
                                                                                                                            'jml_mp',
                                                                                                                            $data->jml_mp
                                                                                                                        ) ?>" required>
                                    <small class="text-danger">
                                        <?= form_error('jml_mp') ?>
                                    </small>
                                </div>
                            </div>
                            <!-- KETERANGAN -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan">
                                        Keterangan
                                    </label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= set_value(
                                                                                                                    'keterangan',
                                                                                                                    $data->keterangan
                                                                                                                ) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     BUTTON
                ====================================================== -->
                <div class="row">
                    <div class="col-md-6 text-left ml-3">
                        <a href="<?= base_url('sortasi/detail/' . $data->tbatch_uuid) ?>" class="btn btn-danger mr-2">
                            <i class="fas fa-times mr-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {
            /* ============================================================
             * DATA AWAL
             * ============================================================ */
            let boxKg = 0;
            let wipRows = <?= $wipJson ?>;
            let badIndex = <?= count($badpro_input ?? []) ?>;
            const oldBatch = <?= json_encode($data->tbatch_uuid) ?>;
            const oldWipKg = <?= json_encode($oldKg) ?>;
            const oldWipBox = <?= json_encode($oldInput) ?>;
            const badproData = <?= $badproJson ?>;
            /* ============================================================
             * HELPER
             * ============================================================ */
            function n(value) {
                const number = parseFloat(value);
                return isNaN(number) ?
                    0 :
                    number;
            }

            function fmt(value) {
                return n(value).toLocaleString('id-ID', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
            }
            /* ============================================================
             * ESCAPE HTML
             * ============================================================ */
            function escapeHtml(value) {
                return $('<div>')
                    .text(value == null ? '' : value)
                    .html();
            }
            /* ============================================================
             * SELECT2 - MESIN
             * ============================================================ */
            function initMachineSelect(select) {
                if (!select || !select.length) {
                    return;
                }
                /*
                 * Jika sudah pernah diinisialisasi Select2,
                 * jangan initialize dua kali.
                 */
                if (select.hasClass('select2-hidden-accessible')) {
                    return;
                }
                select.select2({
                    width: '100%',
                    placeholder: 'Pilih Mesin',
                    allowClear: true
                });
            }
            /*
             * Inisialisasi semua mesin yang sudah ada
             * ketika halaman edit pertama kali dibuka.
             */
            $('.mesin').each(function() {
                initMachineSelect($(this));
            });
            /* ============================================================
             * INFORMASI BATCH
             * ============================================================ */
            function setBatchInfo(option) {
                boxKg = n(
                    option.data('box-kg')
                );
                const sisaBox = n(
                    option.data('sisa-box')
                );
                const sisaKg = n(
                    option.data('sisa-kg')
                );
                $('#sisaWipBox').text(
                    fmt(sisaBox)
                );
                $('#sisaWipKg').text(
                    fmt(sisaKg)
                );
            }
            /* ============================================================
             * HITUNG TOTAL WIP
             * ============================================================ */
            function getAvailableWip() {
                let total = 0;
                (wipRows || []).forEach(function(row) {
                    total += n(
                        row.sisa_wip
                    );
                });
                return total;
            }
            /* ============================================================
             * LOAD WIP BATCH LAIN
             * ============================================================ */
            function loadWip(uuid) {
                if (!uuid) {
                    wipRows = [];
                    $('#sisaWipBox').text('0.000');
                    $('#sisaWipKg').text('0.000');
                    updateWip();
                    return;
                }
                $.getJSON(
                        "<?= base_url('sortasi/get_wip_batch/') ?>" + uuid,
                        function(rows) {
                            wipRows = rows || [];
                            const totalBox =
                                getAvailableWip();
                            $('#sisaWipBox').text(
                                fmt(totalBox)
                            );
                            $('#sisaWipKg').text(
                                fmt(totalBox * boxKg)
                            );
                            updateWip();
                        }
                    )
                    .fail(function() {
                        wipRows = [];
                        $('#sisaWipBox').text('0.000');
                        $('#sisaWipKg').text('0.000');
                        updateWip();
                        alert(
                            'Gagal mengambil data WIP batch.'
                        );
                    });
            }
            /* ============================================================
             * LOAD MESIN BATCH
             * ============================================================ */
            function loadMachines(index, selectedMachines = []) {
                const batchUuid =
                    $('#tbatch_uuid').val();
                if (!batchUuid) {
                    return;
                }
                const select =
                    $('.bad-card[data-i="' + index + '"] .mesin');
                if (!select.length) {
                    return;
                }
                /*
                 * Jika Select2 sudah aktif,
                 * destroy terlebih dahulu sebelum
                 * mengganti option.
                 */
                if (
                    select.hasClass(
                        'select2-hidden-accessible'
                    )
                ) {
                    select.select2('destroy');
                }
                $.getJSON(
                        "<?= base_url('sortasi/get_mesin_batch/') ?>" + batchUuid,
                        function(rows) {
                            select.empty();
                            /*
                             * Tambahkan option mesin.
                             */
                            (rows || []).forEach(function(machine) {
                                const option =
                                    $('<option>', {
                                        value: machine.uuid,
                                        text: machine.nama_mesin
                                    });
                                /*
                                 * Pertahankan mesin yang sebelumnya
                                 * sudah dipilih.
                                 */
                                if (
                                    selectedMachines.indexOf(
                                        machine.uuid
                                    ) !== -1
                                ) {
                                    option.prop(
                                        'selected',
                                        true
                                    );
                                }
                                select.append(option);
                            });
                            /*
                             * Aktifkan kembali Select2
                             * setelah option selesai dibuat.
                             */
                            initMachineSelect(select);
                            /*
                             * Sinkronisasi nilai Select2.
                             */
                            select.trigger('change');
                        }
                    )
                    .fail(function() {
                        select.empty();
                        initMachineSelect(select);
                        alert(
                            'Gagal mengambil data mesin batch.'
                        );
                    });
            }
            /* ============================================================
             * BATCH CHANGE
             * ============================================================ */
            $('#tbatch_uuid').on(
                'change',
                function() {
                    const uuid =
                        $(this).val();
                    const option =
                        $(this).find(':selected');
                    /*
                     * Ambil box_kg dari batch.
                     */
                    boxKg = n(
                        option.data('box-kg')
                    );
                    /*
                     * Jika kembali ke batch lama,
                     * gunakan WIP transaksi lama.
                     */
                    if (
                        uuid === oldBatch
                    ) {
                        wipRows =
                            <?= $wipJson ?>;
                        setBatchInfo(
                            option
                        );
                    } else {
                        /*
                         * Batch baru.
                         */
                        $('#wip_kg').val('');
                        $('#wip_box').val('');
                        loadWip(uuid);
                    }
                    /*
                     * Reload mesin semua bad product
                     * berdasarkan batch baru.
                     */
                    $('.bad-card').each(function() {
                        const card =
                            $(this);
                        const index =
                            card.data('i');
                        /*
                         * Ambil mesin yang sedang dipilih.
                         */
                        const selectedMachines =
                            card.find('.mesin')
                            .val() || [];
                        loadMachines(
                            index,
                            selectedMachines
                        );
                    });
                    updateWip();
                }
            );
            /* ============================================================
             * INPUT WIP KG
             * ============================================================ */
            $('#wip_kg').on(
                'input',
                function() {
                    if (boxKg <= 0) {
                        $('#wip_box').val('');
                        updateWip();
                        return;
                    }
                    const kg =
                        n(this.value);
                    $('#wip_box').val(
                        kg > 0 ?
                        (
                            kg / boxKg
                        ).toFixed(3) :
                        ''
                    );
                    updateWip();
                }
            );
            /* ============================================================
             * INPUT WIP BOX
             * ============================================================ */
            $('#wip_box').on(
                'input',
                function() {
                    if (boxKg <= 0) {
                        $('#wip_kg').val('');
                        updateWip();
                        return;
                    }
                    const box =
                        n(this.value);
                    $('#wip_kg').val(
                        box > 0 ?
                        (
                            box * boxKg
                        ).toFixed(3) :
                        ''
                    );
                    updateWip();
                }
            );
            /* ============================================================
             * ALOKASI WIP
             * ============================================================ */
            function allocate(totalBox) {
                let remain =
                    n(totalBox);
                let html = '';
                $('#wipHidden').empty();
                for (
                    let i = 0; i < wipRows.length && remain > 0; i++
                ) {
                    const available =
                        n(
                            wipRows[i].sisa_wip
                        );
                    if (
                        available <= 0
                    ) {
                        continue;
                    }
                    const take =
                        Math.min(
                            remain,
                            available
                        );
                    if (take > 0) {
                        html +=
                            '<input type="hidden" ' +
                            'name="wip_uuid[]" ' +
                            'value="' +
                            wipRows[i].uuid +
                            '">' +
                            '<input type="hidden" ' +
                            'name="wip_jumlah[]" ' +
                            'value="' +
                            take.toFixed(3) +
                            '">';
                        remain -= take;
                    }
                }
                $('#wipHidden').html(
                    html
                );
            }
            /* ============================================================
             * UPDATE WIP
             * ============================================================ */
            function updateWip() {
                const box =
                    n(
                        $('#wip_box').val()
                    );
                $('#jumlah_sortir').val(
                    box
                );
                allocate(
                    box
                );
                calculate();
            }
            /* ============================================================
             * OUTPUT CHANGE
             * ============================================================ */
            $('.outputBox').on(
                'input',
                function() {
                    calculate();
                }
            );
            /* ============================================================
             * TOTAL BAD
             * ============================================================ */
            function totalBad() {
                let total = 0;
                $('.badWeight').each(
                    function() {
                        total += n(
                            $(this).val()
                        );
                    }
                );
                $('#totalBad').text(
                    total.toFixed(3)
                );
                return total;
            }
            /* ============================================================
             * HITUNG HASIL SORTASI
             * ============================================================ */
            function calculate() {
                const inputKg =
                    n(
                        $('#wip_kg').val()
                    );
                const outputBox =
                    n(
                        $('#release_box').val()
                    ) +
                    n(
                        $('#output_tampung').val()
                    ) +
                    n(
                        $('#output_kasar').val()
                    ) +
                    n(
                        $('#output_cuci').val()
                    );
                const outputKg =
                    outputBox * boxKg;
                const badKg =
                    totalBad();
                const sisaKg =
                    inputKg -
                    outputKg -
                    badKg;
                $('#sisaKg').text(
                    fmt(sisaKg)
                );
                $('#sisaBox').text(
                    boxKg > 0 ?
                    fmt(
                        sisaKg / boxKg
                    ) :
                    '0.000'
                );
            }
            /* ============================================================
             * BAD PRODUCT ROW
             * ============================================================ */
            function badRow(index) {
                let options =
                    '<option value="">Pilih Bad Produk</option>';
                badproData.forEach(
                    function(bad) {
                        options +=
                            '<option ' +
                            'value="' +
                            escapeHtml(
                                bad.uuid_badpro
                            ) +
                            '" ' +
                            'data-kategori="' +
                            escapeHtml(
                                bad.kategori_nama || ''
                            ) +
                            '">' +
                            escapeHtml(
                                bad.nama_badpro
                            ) +
                            '</option>';
                    }
                );
                return `
            <div
                class="card border mb-3 bad-card"
                data-i="${index}"
            >
                <div class="card-body">
                    <div class="row">
                        <!-- BAD PRODUK -->
                        <div class="col-md-5">
                            <div class="form-group mb-md-0">
                                <label>
                                    Bad Produk
                                </label>
                                <select
                                    name="badpro_uuid[]"
                                    class="form-control badSelect"
                                    required
                                >
                                    ${options}
                                </select>
                            </div>
                        </div>
                        <!-- KATEGORI -->
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label>
                                    Kategori
                                </label>
                                <input
                                    type="text"
                                    class="form-control kategori"
                                    readonly
                                >
                            </div>
                        </div>
                        <!-- BERAT -->
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label>
                                    Berat (Kg)
                                </label>
                                <input
                                    type="number"
                                    name="badpro_berat[]"
                                    class="form-control badWeight"
                                    min="0"
                                    step="0.001"
                                    required
                                >
                            </div>
                        </div>
                        <!-- MESIN -->
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label>
                                    Mesin Dominan
                                    <small class="text-muted">
                                        (opsional)
                                    </small>
                                </label>
                                <select
                                    name="mesin_uuid[${index}][]"
                                    class="form-control mesin"
                                    multiple select2
                                >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-3">
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm removeBad"
                        >
                            <i class="fas fa-trash mr-1"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
            }
            /* ============================================================
             * TAMBAH BAD PRODUCT
             * ============================================================ */
            $('#btnTambahBad').on(
                'click',
                function() {
                    $('#emptyBadMessage').remove();
                    const index =
                        badIndex++;
                    $('#badContainer').append(
                        badRow(index)
                    );
                    /*
                     * Load mesin untuk row baru.
                     */
                    loadMachines(
                        index,
                        []
                    );
                }
            );
            /* ============================================================
             * BAD PRODUCT CHANGE
             * ============================================================ */
            $(document).on(
                'change',
                '.badSelect',
                function() {
                    const kategori =
                        $(this)
                        .find(':selected')
                        .data('kategori') || '';
                    $(this)
                        .closest('.bad-card')
                        .find('.kategori')
                        .val(kategori);
                }
            );
            /* ============================================================
             * BAD PRODUCT WEIGHT
             * ============================================================ */
            $(document).on(
                'input',
                '.badWeight',
                function() {
                    totalBad();
                    calculate();
                }
            );
            /* ============================================================
             * HAPUS BAD PRODUCT
             * ============================================================ */
            $(document).on(
                'click',
                '.removeBad',
                function() {
                    $(this)
                        .closest('.bad-card')
                        .remove();
                    if (
                        !$('.bad-card').length
                    ) {
                        $('#badContainer').html(`
                    <div
                        class="text-center text-muted py-3"
                        id="emptyBadMessage"
                    >
                        <i class="fas fa-info-circle mr-1"></i>
                        Tidak ada Bad Produk.
                    </div>
                `);
                    }
                    totalBad();
                    calculate();
                }
            );
            /* ============================================================
             * VALIDASI SUBMIT
             * ============================================================ */
            $('#formSortasi').on(
                'submit',
                function(e) {
                    const inputKg =
                        n(
                            $('#wip_kg').val()
                        );
                    const inputBox =
                        n(
                            $('#wip_box').val()
                        );
                    const availableBox =
                        getAvailableWip();
                    const outputBox =
                        n(
                            $('#release_box').val()
                        ) +
                        n(
                            $('#output_tampung').val()
                        ) +
                        n(
                            $('#output_kasar').val()
                        ) +
                        n(
                            $('#output_cuci').val()
                        );
                    const outputKg =
                        outputBox * boxKg;
                    const badKg =
                        totalBad();
                    const totalUsed =
                        outputKg +
                        badKg;
                    /*
                     * WIP harus lebih dari 0.
                     */
                    if (
                        inputBox <= 0
                    ) {
                        alert(
                            'WIP yang digunakan harus diisi.'
                        );
                        e.preventDefault();
                        return false;
                    }
                    /*
                     * Tidak boleh melebihi WIP tersedia.
                     */
                    if (
                        inputBox >
                        availableBox + 0.000001
                    ) {
                        alert(
                            'WIP yang digunakan melebihi WIP tersedia.'
                        );
                        e.preventDefault();
                        return false;
                    }
                    return true;
                }
            );
            /* ============================================================
             * INITIAL STATE
             * ============================================================ */
            /*
             * Ambil informasi batch lama.
             */
            $('#tbatch_uuid').trigger(
                'change'
            );
            /*
             * Kembalikan WIP transaksi lama.
             */
            $('#wip_kg').val(
                oldWipKg > 0 ?
                oldWipKg.toFixed(3) :
                ''
            );
            $('#wip_box').val(
                oldWipBox > 0 ?
                oldWipBox.toFixed(3) :
                ''
            );
            /*
             * Set kategori berdasarkan Bad Produk
             * yang sudah tersimpan.
             */
            $('.bad-card').each(
                function() {
                    const select =
                        $(this).find('.badSelect');
                    if (!select.length) {
                        return;
                    }
                    const kategori =
                        select
                        .find(':selected')
                        .data('kategori') || '';
                    $(this)
                        .find('.kategori')
                        .val(kategori);
                }
            );
            /*
             * Hitung ulang.
             */
            totalBad();
            calculate();
        });
    </script>