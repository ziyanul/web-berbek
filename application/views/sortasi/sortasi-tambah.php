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

            <form id="formSortasi" action="<?= base_url('sortasi/tambah') ?>" method="post">

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

                                        <option value="<?= html_escape($b->uuid) ?>">

                                            <?= html_escape($b->kode_batch) ?>
                                            -
                                            <?= html_escape($b->varian) ?>

                                            <?php if (!empty($b->keterangan)) : ?>
                                            (<?= html_escape($b->keterangan) ?>)
                                            <?php endif; ?>

                                        </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <small class="text-danger">
                                        <?= form_error('tbatch_uuid') ?>
                                    </small>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Jenis Sortasi
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="jenis_sortasi_uuid" id="jenis_sortasi_uuid" class="form-control"
                                        required>

                                        <option value="">
                                            Pilih Jenis Sortasi
                                        </option>

                                        <?php foreach ($jenis_sortasi as $jenis) : ?>

                                        <option value="<?= html_escape($jenis->uuid) ?>">

                                            <?= html_escape($jenis->nama) ?>

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
                     WIP YANG DIGUNAKAN
                ====================================================== -->

                <div class="card border-left-warning mb-4">

                    <div class="card-header bg-light">

                        <b>
                            <i class="fas fa-boxes mr-2"></i>
                            WIP Yang Digunakan
                        </b>

                    </div>

                    <div class="card-body">

                        <div id="wipContainer">

                            <div class="text-center text-muted">

                                Pilih batch terlebih dahulu.

                            </div>

                        </div>


                        <hr>


                        <div class="row">

                            <div class="col-md-6">

                                <b>
                                    Total WIP Digunakan
                                </b>

                            </div>

                            <div class="col-md-6 text-right">

                                <b>

                                    <span id="totalWip">
                                        0
                                    </span>

                                    Box

                                </b>

                            </div>

                        </div>


                        <!--
                            FIELD LAMA TETAP DIKIRIM
                            KE CONTROLLER / MODEL
                        -->

                        <input type="hidden" name="jumlah_sortir" id="jumlah_sortir" value="0">

                    </div>

                </div>


                <!-- =====================================================
                     OUTPUT SORTASI
                ====================================================== -->

                <div class="card border-left-success mb-4">

                    <div class="card-header bg-light">

                        <b>
                            <i class="fas fa-box-open mr-2"></i>
                            Output Sortasi
                        </b>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- RELEASE -->

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Release
                                    </label>

                                    <input type="number" name="release_box" id="release_box"
                                        class="form-control outputBox" min="0" step="1" value="0">

                                    <small class="text-muted">
                                        Output yang langsung menjadi Release.
                                    </small>

                                </div>

                            </div>


                            <!-- TAMPUNG -->

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Tampung
                                    </label>

                                    <input type="number" name="output_tampung" id="output_tampung"
                                        class="form-control outputBox" min="0" step="1" value="0">

                                    <small class="text-muted">
                                        Akan menjadi WIP dan dapat disortasi kembali.
                                    </small>

                                </div>

                            </div>


                            <!-- KASAR -->

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Kasar
                                    </label>

                                    <input type="number" name="output_kasar" id="output_kasar"
                                        class="form-control outputBox" min="0" step="1" value="0">

                                    <small class="text-muted">
                                        Akan menjadi WIP dan dapat disortasi kembali.
                                    </small>

                                </div>

                            </div>


                            <!-- CUCI -->

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Cuci
                                    </label>

                                    <input type="number" name="output_cuci" id="output_cuci"
                                        class="form-control outputBox" min="0" step="1" value="0">

                                    <small class="text-muted">
                                        Akan diteruskan ke proses Cuci.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="alert alert-info">

                            <div class="row">

                                <div class="col-md-6">

                                    Total Output

                                </div>

                                <div class="col-md-6 text-right">

                                    <b>

                                        <span id="totalOutput">
                                            0
                                        </span>

                                        Box

                                    </b>

                                </div>

                            </div>

                        </div>


                        <div class="alert alert-secondary">

                            <div class="row">

                                <div class="col-md-6">

                                    Selisih / Belum Diproses

                                </div>

                                <div class="col-md-6 text-right">

                                    <b>

                                        <span id="sisaInput">
                                            0
                                        </span>

                                        Box

                                    </b>

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

                                Belum ada bad produk dipilih.

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =====================================================
                     INFORMASI BAD
                ====================================================== -->

                <div class="alert alert-warning">

                    <div class="row text-center">

                        <div class="col-md-4">

                            <h6>
                                Total Baris Bad
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
                     INFORMASI BAD MAKSIMAL
                ====================================================== -->

                <div class="alert alert-secondary">

                    Estimasi maksimal berat Bad Produk:

                    <b>

                        <span id="maksimalBadProduk">
                            0.00
                        </span>

                        Kg

                    </b>

                </div>


                <!-- =====================================================
                     WAKTU & MP
                ====================================================== -->

                <div class="card border-left-secondary mb-4">

                    <div class="card-header bg-light">

                        <b>
                            <i class="fas fa-clock mr-2"></i>
                            Waktu & Tenaga Kerja
                        </b>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Jam Mulai
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="time" name="mulai" class="form-control"
                                        value="<?= set_value('mulai') ?>" required>

                                    <small class="text-danger">
                                        <?= form_error('mulai') ?>
                                    </small>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Jam Selesai
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="time" name="selesai" class="form-control"
                                        value="<?= set_value('selesai') ?>" required>

                                    <small class="text-danger">
                                        <?= form_error('selesai') ?>
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Jumlah Man Power
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="number" name="jml_mp" class="form-control" min="1" step="1"
                                        value="<?= set_value('jml_mp') ?>" required>

                                    <small class="text-danger">
                                        <?= form_error('jml_mp') ?>
                                    </small>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>
                                        Keterangan
                                    </label>

                                    <textarea name="keterangan" class="form-control"
                                        rows="1"><?= set_value('keterangan') ?></textarea>

                                </div>

                            </div>

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

        resetInfoBatch();
        resetWip();
        resetBadProduk();
        resetMesin();

        if (uuid === '') {
            return;
        }

        loadBatchInfo(uuid);
        loadWip(uuid);
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
       LOAD WIP
    ======================================================== */

    function loadWip(uuid) {

        $.ajax({

            url: "<?= base_url('sortasi/get_wip_batch/') ?>" +
                uuid,

            type: 'GET',

            dataType: 'json',

            success: function(data) {

                let html = '';

                if (!data || data.length === 0) {

                    html = `
                        <div class="alert alert-warning">
                            Tidak ada WIP tersedia.
                        </div>
                    `;

                } else {

                    data.forEach(function(wip, index) {

                        let sisa =
                            parseFloat(wip.sisa_wip) || 0;

                        html += `

                            <div class="card mb-3 wip-card">

                                <div class="card-body">

                                    <input
                                        type="hidden"
                                        name="wip_uuid[]"
                                        value="${escapeHtml(wip.uuid)}">

                                    <div class="row">

                                        <div class="col-md-4">

                                            <label>
                                                Jenis WIP
                                            </label>

                                            <input
                                                type="text"
                                                class="form-control"
                                                value="${escapeHtml(wip.jenis_wip)}"
                                                readonly>

                                        </div>


                                        <div class="col-md-4">

                                            <label>
                                                Tersedia
                                            </label>

                                            <div class="input-group">

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    value="${formatNumber(sisa)}"
                                                    readonly>

                                                <div class="input-group-append">

                                                    <span class="input-group-text">
                                                        Box
                                                    </span>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="col-md-4">

                                            <label>
                                                Digunakan
                                            </label>

                                            <div class="input-group">

                                                <input
                                                    type="number"
                                                    name="wip_jumlah[]"
                                                    class="form-control jumlahWip"
                                                    min="0"
                                                    step="1"
                                                    value="0">

                                                <div class="input-group-append">

                                                    <span class="input-group-text">
                                                        Box
                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        `;

                    });

                }

                $('#wipContainer')
                    .html(html);

                hitungTotalWip();

            },

            error: function() {

                $('#wipContainer').html(`

                    <div class="alert alert-danger">
                        Gagal mengambil data WIP.
                    </div>

                `);

            }

        });

    }


    /* ========================================================
       LOAD MESIN
    ======================================================== */

    function loadMesin(uuid) {

        $.ajax({

            url: "<?= base_url('sortasi/get_mesin_batch/') ?>" +
                uuid,

            type: 'GET',

            dataType: 'json',

            success: function(data) {

                daftarMesin = data || [];

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
       INPUT WIP
    ======================================================== */

    $(document).on(
        'input',
        '.jumlahWip',
        function() {

            hitungTotalWip();
            hitungOutput();
            hitungMaksimalBadProduk();

        }
    );


    /* ========================================================
       HITUNG TOTAL WIP
    ======================================================== */

    function hitungTotalWip() {

        let total = 0;

        $('.jumlahWip').each(function() {

            total +=
                parseFloat($(this).val()) || 0;

        });

        $('#totalWip')
            .text(formatNumber(total));

        $('#jumlah_sortir')
            .val(total);

        hitungOutput();

    }


    /* ========================================================
       OUTPUT BERUBAH
    ======================================================== */

    $(document).on(
        'input',
        '.outputBox',
        function() {

            hitungOutput();
            hitungMaksimalBadProduk();

        }
    );


    /* ========================================================
       HITUNG OUTPUT
    ======================================================== */

    function hitungOutput() {

        let input =
            parseFloat($('#totalWip').text()) || 0;

        let release =
            parseFloat($('#release_box').val()) || 0;

        let tampung =
            parseFloat($('#output_tampung').val()) || 0;

        let kasar =
            parseFloat($('#output_kasar').val()) || 0;

        let cuci =
            parseFloat($('#output_cuci').val()) || 0;

        let total =
            release +
            tampung +
            kasar +
            cuci;

        let sisa =
            input - total;

        $('#totalOutput')
            .text(formatNumber(total));

        $('#sisaInput')
            .text(formatNumber(sisa));

        if (sisa < 0) {

            $('#sisaInput')
                .removeClass('text-success')
                .addClass('text-danger');

        } else {

            $('#sisaInput')
                .removeClass('text-danger')
                .addClass('text-success');

        }

    }


    /* ========================================================
       TAMBAH BAD PRODUK
    ======================================================== */

    $('#btnTambahBadProduk').on(
        'click',
        function() {

            if ($('#tbatch_uuid').val() === '') {

                alert(
                    'Silakan pilih batch terlebih dahulu.'
                );

                return;

            }

            if (daftarMesin.length === 0) {

                alert(
                    'Mesin untuk batch belum tersedia.'
                );

                return;

            }

            $('#badProdukContainer .text-muted')
                .remove();

            $('#badProdukContainer').append(
                createBadProdukCard(indexBadProduk)
            );

            indexBadProduk++;

            initSelect2Mesin();

            hitungTotalBad();
            hitungTotalMesin();

        }
    );


    /* ========================================================
       CARD BAD PRODUK
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
                                            value="<?= html_escape($bp->uuid_badpro) ?>"
                                            data-kategori="<?= html_escape($bp->kategori_nama ?? '') ?>">

                                            <?= html_escape($bp->nama_badpro) ?>

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


                        <!-- MESIN -->

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
       OPTION MESIN
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

                <option value="${escapeHtml(m.uuid)}">

                    ${escapeHtml(m.nama_mesin)}

                </option>

            `;

        });

        return html;

    }


    /* ========================================================
       SELECT2 MESIN
    ======================================================== */

    function initSelect2Mesin() {

        $('.select2Mesin').each(function() {

            if (
                $(this)
                .hasClass('select2-hidden-accessible')
            ) {

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
       REFRESH MESIN
    ======================================================== */

    function refreshSemuaMesinDominan() {

        $('.mesinDominan').each(function() {

            let select = $(this);

            let nilaiLama =
                select.val() || [];

            let html =
                generateOptionMesinDominan();

            if (
                select.hasClass(
                    'select2-hidden-accessible'
                )
            ) {

                select
                    .select2('destroy');

            }

            select.html(html);

            let nilaiValid = [];

            nilaiLama.forEach(
                function(uuid) {

                    daftarMesin.forEach(
                        function(m) {

                            if (m.uuid == uuid) {

                                nilaiValid.push(
                                    uuid
                                );

                            }

                        }
                    );

                }
            );

            select.val(nilaiValid);

        });

        initSelect2Mesin();

        hitungTotalMesin();

    }


    /* ========================================================
       HAPUS BAD
    ======================================================== */

    $(document).on(
        'click',
        '.btnHapusBad',
        function() {

            $(this)
                .closest('.bad-card')
                .remove();

            if (
                $('.bad-card').length === 0
            ) {

                $('#badProdukContainer')
                    .html(`

                        <div class="text-center text-muted">

                            Belum ada bad produk dipilih.

                        </div>

                    `);

            }

            hitungTotalBad();
            hitungTotalMesin();

        }
    );


    /* ========================================================
       BAD PRODUK DIPILIH
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


            let selectedValues = [];

            let duplicate = false;


            $('.badproSelect').each(
                function() {

                    let value =
                        $(this).val();

                    if (value === '') {
                        return;
                    }

                    if (
                        selectedValues.includes(
                            value
                        )
                    ) {

                        duplicate = true;

                        return false;

                    }

                    selectedValues.push(
                        value
                    );

                }
            );


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
       BERAT BAD
    ======================================================== */

    $(document).on(
        'input',
        '.jumlahBad',
        function() {

            hitungTotalBad();

        }
    );


    /* ========================================================
       MESIN BERUBAH
    ======================================================== */

    $(document).on(
        'change',
        '.mesinDominan',
        function() {

            hitungTotalMesin();

        }
    );


    /* ========================================================
       HITUNG TOTAL BAD
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
       HITUNG MESIN
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
       MAKSIMAL BAD
    ======================================================== */

    function hitungMaksimalBadProduk() {

        let input =
            parseFloat($('#totalWip').text()) || 0;

        let boxKg =
            parseFloat($('#boxKg').text()) || 0;

        let maksimal =
            input * boxKg;

        if (maksimal < 0) {
            maksimal = 0;
        }

        $('#maksimalBadProduk')
            .text(maksimal.toFixed(2));

        return maksimal;

    }


    /* ========================================================
       RESET WIP
    ======================================================== */

    function resetWip() {

        $('#wipContainer').html(`

            <div class="text-center text-muted">

                Pilih batch terlebih dahulu.

            </div>

        `);

        $('#totalWip')
            .text('0');

        $('#jumlah_sortir')
            .val('0');

        $('#totalOutput')
            .text('0');

        $('#sisaInput')
            .text('0');

    }


    /* ========================================================
       RESET BAD
    ======================================================== */

    function resetBadProduk() {

        $('#badProdukContainer')
            .html(`

                <div class="text-center text-muted">

                    Belum ada bad produk dipilih.

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
            .text('0');

        $('#sortasiBox')
            .text('0');

        $('#sisaBox')
            .text('0');

        $('#boxKg')
            .text('0');

        $('#maksimalBadProduk')
            .text('0.00');

    }


    /* ========================================================
       ESCAPE HTML
    ======================================================== */

    function escapeHtml(text) {

        if (
            text === null ||
            text === undefined
        ) {

            return '';

        }

        return $('<div>')
            .text(text)
            .html();

    }


    /* ========================================================
       FORMAT NUMBER
    ======================================================== */

    function formatNumber(number) {

        number =
            parseFloat(number) || 0;

        return number
            .toLocaleString(
                'id-ID', {
                    maximumFractionDigits: 3
                }
            );

    }


    /* ========================================================
       SUBMIT
    ======================================================== */

    $('#formSortasi').on(
        'submit',
        function(e) {

            let input =
                parseFloat($('#totalWip').text()) || 0;


            if (
                $('#tbatch_uuid').val() === ''
            ) {

                alert(
                    'Batch belum dipilih.'
                );

                e.preventDefault();

                return false;

            }


            if (
                $('#jenis_sortasi_uuid').val() === ''
            ) {

                alert(
                    'Jenis Sortasi belum dipilih.'
                );

                e.preventDefault();

                return false;

            }


            if (input <= 0) {

                alert(
                    'Jumlah WIP yang digunakan harus lebih dari 0.'
                );

                e.preventDefault();

                return false;

            }


            let release =
                parseFloat(
                    $('#release_box').val()
                ) || 0;

            let tampung =
                parseFloat(
                    $('#output_tampung').val()
                ) || 0;

            let kasar =
                parseFloat(
                    $('#output_kasar').val()
                ) || 0;

            let cuci =
                parseFloat(
                    $('#output_cuci').val()
                ) || 0;


            let totalOutput =
                release +
                tampung +
                kasar +
                cuci;


            /*
             * Output boleh kurang dari input.
             * Selisih dianggap belum diproses.
             */

            if (totalOutput > input) {

                alert(
                    'Total output tidak boleh melebihi WIP yang digunakan.'
                );

                e.preventDefault();

                return false;

            }


            /* ====================================================
               BAD PRODUK
            ==================================================== */

            let valid = true;


            $('.bad-card').each(
                function() {

                    let card =
                        $(this);

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
                            'Berat Bad Produk harus lebih dari 0 Kg.'
                        );

                        valid = false;

                        return false;

                    }


                    if (
                        mesin.length === 0
                    ) {

                        alert(
                            'Setiap Bad Produk harus memiliki minimal satu Mesin Dominan.'
                        );

                        valid = false;

                        return false;

                    }

                }
            );


            if (!valid) {

                e.preventDefault();

                return false;

            }


            /* ====================================================
               BAD MAKSIMAL
            ==================================================== */

            let totalBad =
                parseFloat(
                    $('#totalBadKg').text()
                ) || 0;

            let maksimal =
                hitungMaksimalBadProduk();


            if (totalBad > maksimal) {

                alert(
                    'Total Bad Produk melebihi estimasi maksimal ' +
                    maksimal.toFixed(2) +
                    ' Kg.'
                );

                e.preventDefault();

                return false;

            }


            return true;

        }
    );

});
</script>