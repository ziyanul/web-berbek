<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">
        Tambah Sortasi
    </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('sortasi') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Sortasi
                </a>
            </li>
            <li class="breadcrumb-item active">
                Tambah Data
            </li>
        </ol>
    </nav>
    <div class="card shadow">
        <div class="card-header">
            <b>
                <i class="fas fa-sort-amount-down mr-2"></i>
                Input Data Sortasi
            </b>
        </div>
        <div class="card-body">
            <form action="<?= base_url('sortasi/tambah') ?>" method="post">
                <!-- =====================================================
                     DATA BATCH
                ====================================================== -->
                <div class="card border-left-primary mb-4">
                    <div class="card-header bg-light">
                        <b>
                            <i class="fas fa-layer-group mr-2"></i>
                            Data Batch
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Kode Batch
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="tbatch_uuid" id="tbatch_uuid" class="form-control" required>
                                        <option value="">
                                            Pilih Batch
                                        </option>
                                        <?php foreach ($batch as $b) : ?>
                                            <option value="<?= $b->uuid ?>">
                                                <?= $b->kode_batch ?>
                                                -
                                                <?= $b->varian ?>
                                                (<?= $b->keterangan ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     INFORMASI BATCH
                ====================================================== -->
                <div class="card border-left-info mb-4">
                    <div class="card-header bg-light">
                        <b>
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Batch
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h6>
                                    Filkar
                                </h6>
                                <h4>
                                    <span id="filkarBox">
                                        0
                                    </span>
                                    Box
                                </h4>
                            </div>
                            <div class="col-md-3">
                                <h6>
                                    Sudah Sortasi
                                </h6>
                                <h4>
                                    <span id="sortasiBox">
                                        0
                                    </span>
                                    Box
                                </h4>
                            </div>
                            <div class="col-md-3">
                                <h6>
                                    Sisa Sortasi
                                </h6>
                                <h4 class="text-danger">
                                    <span id="sisaBox">
                                        0
                                    </span>
                                    Box
                                </h4>
                            </div>
                            <div class="col-md-3">
                                <h6>
                                    Berat / Box
                                </h6>
                                <h4>
                                    <span id="boxKg">
                                        0
                                    </span>
                                    Kg
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     DATA SORTASI
                ====================================================== -->
                <div class="card border-left-success mb-4">
                    <div class="card-header bg-light">
                        <b>
                            <i class="fas fa-box mr-2"></i>
                            Data Sortasi
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Jumlah Sortir (Box)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="jumlah_sortir" id="jumlah_sortir" class="form-control" min="1" step="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Release Box
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="release_box" id="release_box" class="form-control" min="0" step="1" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            Maksimal Bad Produk :
                            <b>
                                <span id="maksimalBadProduk">
                                    0.00
                                </span>
                                Kg
                            </b>
                        </div>
                        <div class="row">
                <div class="col-md-6">
                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <input type="time" name="mulai" class="form-control" value="<?= set_value('mulai') ?>">
                            <small class="text-danger">
                                <?= form_error('mulai') ?>
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>jam Selesai</label>
                            <input type="time" name="selesai" class="form-control" value="<?= set_value('selesai') ?>">
                            <small class="text-danger">
                                <?= form_error('selesai') ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                <div class="form-group">
                    <label>Jumlah Man Power</label>
                    <input type=number step="1" name="jml_mp" class="form-control" value="<?= set_value('jml_mp') ?>">
                </div>
                    </div>
                    <div class="col-6">
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="1"><?= set_value('keterangan') ?></textarea>
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
                            <b>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Bad Produk
                            </b>
                            <button type="button" id="btnTambahBadProduk" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i>
                                Tambah Bad Produk
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="badProdukContainer">
                            <div class="text-center text-muted">
                                Belum ada bad produk dipilih
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     SUMMARY
                ====================================================== -->
                <div class="alert alert-danger">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6>
                                Total Baris Bad Produk
                            </h6>
                            <h4 id="totalBarisBad">
                                0
                            </h4>
                        </div>
                        <div class="col-md-4">
                            <h6>
                                Total Mesin Dominan
                            </h6>
                            <h4 id="totalMesin">
                                0
                            </h4>
                        </div>
                        <div class="col-md-4">
                            <h6>
                                Total Bad Produk
                            </h6>
                            <h4>
                                <span id="totalBadKg">
                                    0.00
                                </span>
                                Kg
                            </h4>
                        </div>
                    </div>
                </div>
                <!-- =====================================================
                     BUTTON
                ====================================================== -->
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i>
                    Simpan
                </button>
                <a href="<?= base_url('sortasi') ?>" class="btn btn-danger">
                    <i class="fa fa-times"></i>
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
<script>
    let daftarMesin = [];
    let indexBadProduk = 0;
    /* ============================================================
       DOCUMENT READY
    ============================================================ */
    $(document).ready(function() {
        /* ========================================================
           PILIH BATCH
        ======================================================== */
        $('#tbatch_uuid').on('change', function() {
            let uuid = $(this).val();
            resetBadProduk();
            resetInfoBatch();
            resetMesin();
            if (uuid === '') {
                return;
            }
            loadBatchInfo(uuid);
            loadMesin(uuid);
        });
        /* ========================================================
           LOAD INFORMASI BATCH
        ======================================================== */
        function loadBatchInfo(uuid) {
            $.ajax({
                url: "<?= base_url('sortasi/get_batch_info/') ?>" +
                    uuid,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (!data) {
                        return;
                    }
                    $('#filkarBox')
                        .text(data.filkar_box || 0);
                    $('#sortasiBox')
                        .text(data.sortasi_box || 0);
                    $('#sisaBox')
                        .text(data.sisa_sortasi || 0);
                    $('#boxKg')
                        .text(data.box_kg || 0);
                    hitungMaksimalBadProduk();
                },
                error: function() {
                    resetInfoBatch();
                    alert(
                        'Gagal mengambil informasi batch.'
                    );
                }
            });
        }
        /* ========================================================
           LOAD MESIN DOMINAN BERDASARKAN BATCH
        ======================================================== */
        function loadMesin(uuid) {
            $.ajax({
                url: "<?= base_url('sortasi/get_mesin_batch/') ?>" +
                    uuid,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    daftarMesin = data || [];
                    /*
                     * Jika sebelumnya sudah ada bad produk,
                     * refresh pilihan mesin dominan.
                     */
                    refreshSemuaMesinDominan();
                },
                error: function() {
                    daftarMesin = [];
                    refreshSemuaMesinDominan();
                    alert(
                        'Gagal mengambil daftar mesin batch.'
                    );
                }
            });
        }
        /* ========================================================
           TAMBAH BAD PRODUK
        ======================================================== */
        $('#btnTambahBadProduk').on('click', function() {
            if ($('#tbatch_uuid').val() === '') {
                alert('Silakan pilih batch terlebih dahulu.');
                return;
            }
            if (daftarMesin.length === 0) {
                alert('Mesin untuk batch belum tersedia.');
                return;
            }
            $('#badProdukContainer .text-muted').remove();
            $('#badProdukContainer').append(
                createBadProdukCard(indexBadProduk)
            );
            indexBadProduk++;
            initSelect2Mesin();
            hitungTotalBad();
            hitungTotalMesin();
        });
        /* ========================================================
           BUAT CARD BAD PRODUK
        ======================================================== */
        function createBadProdukCard(index) {
            return `
        <div
            class="card border-left-secondary mb-3 bad-card"
            data-index="${index}">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <b>
                        <i class="fas fa-box-open mr-2"></i>
                        Bad Produk
                    </b>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm btnHapusBad">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- BAD PRODUK -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>
                                Bad Produk
                                <span class="text-danger">*</span>
                            </label>
                            <select
                                name="badpro_uuid[]"
                                class="form-control badproSelect"
                                required>
                                <option value="">
                                    Pilih Bad Produk
                                </option>
                                <?php foreach ($badpro as $bp) : ?>
                                    <option
                                        value="<?= $bp->uuid_badpro ?>"
                                        data-kategori="<?= htmlspecialchars($bp->kategori_nama ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($bp->nama_badpro, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- KATEGORI -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                Kategori
                            </label>
                            <input
                                type="text"
                                class="form-control kategoriBad"
                                readonly>
                        </div>
                    </div>
                    <!-- BERAT -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                Berat Bad Produk (Kg)
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="badpro_berat[]"
                                class="form-control jumlahBad"
                                required>
                        </div>
                    </div>
                    <!-- MESIN DOMINAN -->
<div class="col-md-12">
    <div class="form-group">
        <label>
            Mesin Dominan
            <span class="text-danger">*</span>
        </label>
        <select
            name="mesin_uuid[${index}][]"
            class="form-control mesinDominan select2Mesin"
            multiple
            required>
            ${generateOptionMesinDominan()}
        </select>
        <small class="text-muted">
            Pilih satu atau lebih mesin dominan.
        </small>
    </div>
</div>
                </div>
            </div>
        </div>
        `;
        }
        /* ========================================================
           OPTION MESIN DOMINAN
        ======================================================== */
        function generateOptionMesinDominan() {
            let html = '';
            if (daftarMesin.length === 0) {
                return `
            <option value="">
                Mesin tidak tersedia
            </option>
        `;
            }
            daftarMesin.forEach(function(m) {
                html += `
            <option value="${m.uuid}">
                ${escapeHtml(m.nama_mesin)}
            </option>
        `;
            });
            return html;
        }
        /* ========================================================
                   Inisialisasi Select2
        ======================================================== */
        function initSelect2Mesin() {
            $('.select2Mesin').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    return;
                }
                $(this).select2({
                    placeholder: 'Pilih Mesin Dominan',
                    width: '100%',
                    allowClear: true,
                    closeOnSelect: false
                });
            });
        }
        /* ========================================================
           REFRESH SEMUA DROPDOWN MESIN DOMINAN
        ======================================================== */
        function refreshSemuaMesinDominan() {
            $('.mesinDominan').each(function() {
                let select = $(this);
                let nilaiLama = select.val() || [];
                let html = generateOptionMesinDominan();
                select.html(html);
                /*
                 * Kembalikan pilihan sebelumnya jika
                 * mesin tersebut masih tersedia.
                 */
                let nilaiValid = [];
                nilaiLama.forEach(function(uuid) {
                    let ditemukan = false;
                    daftarMesin.forEach(function(m) {
                        if (m.uuid == uuid) {
                            ditemukan = true;
                        }
                    });
                    if (ditemukan) {
                        nilaiValid.push(uuid);
                    }
                });
                select.val(nilaiValid);
            });
            hitungTotalMesin();
        }
        /* ========================================================
           HAPUS BAD PRODUK
        ======================================================== */
        $(document).on(
            'click',
            '.btnHapusBad',
            function() {
                $(this)
                    .closest('.bad-card')
                    .remove();
                if ($('.bad-card').length === 0) {
                    $('#badProdukContainer')
                        .html(`
                        <div class="text-center text-muted">
                            Belum ada bad produk dipilih
                        </div>
                    `);
                }
                hitungTotalBad();
                hitungTotalMesin();
            }
        );
        /* ========================================================
           PILIH BAD PRODUK
           SET KATEGORI
        ======================================================== */
        $(document).on(
            'change',
            '.badproSelect',
            function() {
                let select = $(this);
                let kategori =
                    select
                    .find(':selected')
                    .data('kategori');
                select
                    .closest('.bad-card')
                    .find('.kategoriBad')
                    .val(kategori || '');
                /*
                 * Cegah bad produk yang sama dipilih
                 * lebih dari satu kali dalam satu form.
                 */
                let selectedValues = [];
                let duplicate = false;
                $('.badproSelect').each(function() {
                    let value = $(this).val();
                    if (value === '') {
                        return;
                    }
                    if (selectedValues.includes(value)) {
                        duplicate = true;
                        return false;
                    }
                    selectedValues.push(value);
                });
                if (duplicate) {
                    alert(
                        'Bad Produk yang sama tidak boleh dipilih lebih dari satu kali.'
                    );
                    select.val('');
                    select
                        .closest('.bad-card')
                        .find('.kategoriBad')
                        .val('');
                }
            }
        );
        /* ========================================================
           INPUT BERAT BAD PRODUK
        ======================================================== */
        $(document).on(
            'input',
            '.jumlahBad',
            function() {
                hitungTotalBad();
            }
        );
        /* ========================================================
           MESIN DOMINAN BERUBAH
        ======================================================== */
        $(document).on(
            'change',
            '.mesinDominan',
            function() {
                hitungTotalMesin();
            }
        );
        /* ========================================================
           HITUNG TOTAL BAD PRODUK
        ======================================================== */
        function hitungTotalBad() {
            let total = 0;
            let baris = 0;
            $('.jumlahBad').each(function() {
                let value =
                    parseFloat($(this).val()) || 0;
                total += value;
                if ($(this).val() !== '') {
                    baris++;
                }
            });
            $('#totalBarisBad')
                .text(baris);
            $('#totalBadKg')
                .text(total.toFixed(2));
            let maksimal =
                hitungMaksimalBadProduk();
            if (total > maksimal) {
                $('#totalBadKg')
                    .removeClass('text-success')
                    .addClass('text-danger');
            } else {
                $('#totalBadKg')
                    .removeClass('text-danger')
                    .addClass('text-success');
            }
        }
        /* ========================================================
           HITUNG TOTAL MESIN DOMINAN
        ======================================================== */
        function hitungTotalMesin() {
            let total = 0;
            $('.mesinDominan').each(function() {
                let selected =
                    $(this).val() || [];
                total += selected.length;
            });
            $('#totalMesin')
                .text(total);
        }
        /* ========================================================
           HITUNG MAKSIMAL BAD PRODUK
        ======================================================== */
        function hitungMaksimalBadProduk() {
            let sortir =
                parseFloat($('#jumlah_sortir').val()) || 0;
            let release =
                parseFloat($('#release_box').val()) || 0;
            let beratBox =
                parseFloat($('#boxKg').text()) || 0;
            let maksimal =
                (sortir - release) * beratBox;
            if (maksimal < 0) {
                maksimal = 0;
            }
            $('#maksimalBadProduk')
                .text(maksimal.toFixed(2));
            return maksimal;
        }
        /* ========================================================
           JUMLAH SORTIR
        ======================================================== */
        $('#jumlah_sortir').on(
            'input',
            function() {
                let jumlah =
                    parseFloat($(this).val()) || 0;
                let sisa =
                    parseFloat($('#sisaBox').text()) || 0;
                if (jumlah > sisa) {
                    alert(
                        'Jumlah sortir melebihi sisa batch.'
                    );
                    $(this).val('');
                }
                hitungMaksimalBadProduk();
                hitungTotalBad();
            }
        );
        /* ========================================================
           RELEASE BOX
        ======================================================== */
        $('#release_box').on(
            'input',
            function() {
                let sortir =
                    parseFloat($('#jumlah_sortir').val()) || 0;
                let release =
                    parseFloat($(this).val()) || 0;
                if (release > sortir) {
                    alert(
                        'Release tidak boleh melebihi jumlah sortir.'
                    );
                    $(this).val('');
                }
                hitungMaksimalBadProduk();
                hitungTotalBad();
            }
        );
        /* ========================================================
           RESET BAD PRODUK
        ======================================================== */
        function resetBadProduk() {
            $('#badProdukContainer')
                .html(`
                <div class="text-center text-muted">
                    Belum ada bad produk dipilih
                </div>
            `);
            indexBadProduk = 0;
            hitungTotalBad();
            hitungTotalMesin();
        }
        /* ========================================================
           RESET MESIN
        ======================================================== */
        function resetMesin() {
            daftarMesin = [];
            $('.mesinDominan').each(function() {
                $(this).html('');
            });
            hitungTotalMesin();
        }
        /* ========================================================
           RESET INFO BATCH
        ======================================================== */
        function resetInfoBatch() {
            $('#filkarBox')
                .text(0);
            $('#sortasiBox')
                .text(0);
            $('#sisaBox')
                .text(0);
            $('#boxKg')
                .text(0);
            $('#maksimalBadProduk')
                .text('0.00');
        }
        /* ========================================================
           ESCAPE HTML
        ======================================================== */
        function escapeHtml(text) {
            if (text === null || text === undefined) {
                return '';
            }
            return $('<div>')
                .text(text)
                .html();
        }
        /* ========================================================
           VALIDASI SUBMIT
        ======================================================== */
        $('form').on(
            'submit',
            function(e) {
                let valid = true;
                /* ------------------------------------------------
                   BATCH
                ------------------------------------------------ */
                if ($('#tbatch_uuid').val() === '') {
                    alert(
                        'Batch belum dipilih.'
                    );
                    e.preventDefault();
                    return false;
                }
                /* ------------------------------------------------
                   JUMLAH SORTIR
                ------------------------------------------------ */
                let jumlahSortir =
                    parseFloat($('#jumlah_sortir').val()) || 0;
                if (jumlahSortir <= 0) {
                    alert(
                        'Jumlah sortir harus lebih dari 0.'
                    );
                    e.preventDefault();
                    return false;
                }
                /* ------------------------------------------------
                   RELEASE
                ------------------------------------------------ */
                let releaseBox =
                    parseFloat($('#release_box').val()) || 0;
                if (releaseBox > jumlahSortir) {
                    alert(
                        'Release tidak boleh melebihi jumlah sortir.'
                    );
                    e.preventDefault();
                    return false;
                }
                /* ------------------------------------------------
                   BAD PRODUK HARUS ADA
                ------------------------------------------------ */
                // if ($('.bad-card').length === 0) {
                //     alert(
                //         'Minimal harus ada satu Bad Produk.'
                //     );
                //     e.preventDefault();
                //     return false;
                // }
                /* ------------------------------------------------
                   VALIDASI SETIAP BAD PRODUK
                ------------------------------------------------ */
                $('.bad-card').each(function() {
                    let card = $(this);
                    let badpro =
                        card
                        .find('.badproSelect')
                        .val();
                    let berat =
                        parseFloat(
                            card
                            .find('.jumlahBad')
                            .val()
                        ) || 0;
                    let mesin =
                        card
                        .find('.mesinDominan')
                        .val() || [];
                    if (!badpro) {
                        alert(
                            'Bad Produk belum dipilih.'
                        );
                        valid = false;
                        return false;
                    }
                    if (berat <= 0) {
                        alert(
                            'Berat Bad Produk harus lebih dari 0.'
                        );
                        valid = false;
                        return false;
                    }
                    if (mesin.length === 0) {
                        alert(
                            'Setiap Bad Produk harus memiliki minimal satu Mesin Dominan.'
                        );
                        valid = false;
                        return false;
                    }
                });
                if (!valid) {
                    e.preventDefault();
                    return false;
                }
                /* ------------------------------------------------
                   TOTAL BAD PRODUK
                ------------------------------------------------ */
                let totalBad =
                    parseFloat(
                        $('#totalBadKg').text()
                    ) || 0;
                let maksimal =
                    hitungMaksimalBadProduk();
                // if (totalBad <= 0) {
                //     alert(
                //         'Total Bad Produk harus lebih dari 0 Kg.'
                //     );
                //     e.preventDefault();
                //     return false;
                // }
                if (totalBad > maksimal) {
                    alert(
                        'Total Bad Produk melebihi maksimal ' +
                        maksimal.toFixed(2) +
                        ' Kg.'
                    );
                    e.preventDefault();
                    return false;
                }
                /* ------------------------------------------------
                   SEMUA VALID
                ------------------------------------------------ */
                return true;
            }
        );
    });
</script>