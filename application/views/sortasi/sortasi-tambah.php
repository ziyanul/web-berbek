<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Tambah Sortasi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('sortasi') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Sortasi</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </nav>
    <div class="card shadow">
        <div class="card-header"><b><i class="fas fa-sort-amount-down mr-2"></i>Input Data Sortasi</b></div>
        <div class="card-body">
            <form id="formSortasi" action="<?= base_url('sortasi/tambah') ?>" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Kode Batch <span class="text-danger">*</span></label>
                            <select name="tbatch_uuid" id="tbatch_uuid" class="form-control" required>
                                <option value="">Pilih Batch</option>
                                <?php foreach ($batch as $b): ?>
                                    <?php $sisaKg = (float)$b->sisa_wip * (float)$b->box_kg; ?>
                                    <option value="<?= html_escape($b->uuid) ?>" data-box-kg="<?= (float)$b->box_kg ?>"
                                        data-sisa-box="<?= (float)$b->sisa_wip ?>" data-sisa-kg="<?= $sisaKg ?>"
                                        <?= set_select('tbatch_uuid', $b->uuid) ?>>
                                        <?= html_escape($b->kode_batch) ?> - <?= html_escape($b->varian) ?> |
                                        Sisa WIP: <?= number_format($b->sisa_wip, 3, ',', '.') ?> Box |
                                        <?= number_format($sisaKg, 3, ',', '.') ?> Kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('tbatch_uuid') ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis Sortasi <span class="text-danger">*</span></label>
                            <select name="jenis_sortasi_uuid" class="form-control" required>
                                <option value="">Pilih Jenis Sortasi</option>
                                <?php foreach ($jenis_sortasi as $j): ?>
                                    <option value="<?= html_escape($j->uuid) ?>"
                                        <?= set_select('jenis_sortasi_uuid', $j->uuid) ?>>
                                        <?= html_escape($j->nama) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('jenis_sortasi_uuid') ?></small>
                        </div>
                    </div>
                </div>
                <div id="wipInfo" class="alert alert-info d-none">
                    <div class="row">
                        <div class="col-md-6"><b>Sisa WIP</b><br><span id="sisaWipBox">0</span> Box</div>
                        <div class="col-md-6"><b>Sisa WIP</b><br><span id="sisaWipKg">0.000</span> Kg</div>
                    </div>
                </div>
                <div class="card border-left-warning mb-4">
                    <div class="card-header bg-light"><b>WIP yang Digunakan</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>WIP (Kg)</label>
                                <div class="input-group">
                                    <input type="number" id="wip_kg" class="form-control" min="0" step="0.001"
                                        placeholder="Masukkan Kg">
                                    <div class="input-group-append"><span class="input-group-text">Kg</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>WIP (Box)</label>
                                <div class="input-group">
                                    <input type="number" id="wip_box" class="form-control" min="0" step="0.001"
                                        placeholder="Masukkan Box">
                                    <div class="input-group-append"><span class="input-group-text">Box</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-muted">Isi salah satu. Nilai yang lain dihitung otomatis.</div>
                        <input type="hidden" name="jumlah_sortir" id="jumlah_sortir" value="0">
                        <div id="wipHidden"></div>
                    </div>
                </div>
                <div class="card border-left-success mb-4">
                    <div class="card-header bg-light"><b>Hasil Sortasi</b></div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach (
                                [
                                    'release_box' => 'Release',
                                    'output_tampung' => 'Tampung',
                                    'output_kasar' => 'Kasar',
                                    'output_cuci' => 'Cuci'
                                ] as $name => $label
                            ): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= $label ?> (Box)</label>
                                        <input type="number" name="<?= $name ?>" id="<?= $name ?>"
                                            class="form-control outputBox" min="0" step="0.001"
                                            value="<?= set_value($name, 0) ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="alert alert-secondary mb-0">
                            <div class="row">
                                <div class="col-md-6">Belum teridentifikasi</div>
                                <div class="col-md-6 text-right">
                                    <b><span id="sisaKg">0.000</span> Kg / <span id="sisaBox">0.000</span> Box</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-left-danger mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <b>Bad Produk</b>

                        </div>
                    </div>
                    <div class="card-body">
                        <div id="badContainer">
                            <div class="text-center text-muted">Tidak ada Bad Produk.</div>
                        </div>
                        <div class="text-right mt-2"><b>Total Bad: <span id="totalBad">0.000</span> Kg</b></div>
                    </div>
                    <div class=card-footer>
                        <button type="button" id="btnTambahBad" class="btn btn-primary btn-sm"><i
                                class="fa fa-plus mr-1"></i>Tambah Bad Produk</button>

                    </div>
                </div>
                <div class="card border-left-secondary mb-4">
                    <div class="card-header bg-light"><b>Waktu & Tenaga Kerja</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><label>Jam Mulai <span class="text-danger">*</span></label><input
                                    type="time" name="mulai" class="form-control" value="<?= set_value('mulai') ?>"
                                    required></div>
                            <div class="col-md-6"><label>Jam Selesai <span class="text-danger">*</span></label><input
                                    type="time" name="selesai" class="form-control" value="<?= set_value('selesai') ?>"
                                    required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label>Jumlah Man Power <span
                                        class="text-danger">*</span></label><input type="number" name="jml_mp"
                                    class="form-control" min="1" step="1" value="<?= set_value('jml_mp') ?>" required>
                            </div>
                            <div class="col-md-6"><label>Keterangan</label><textarea name="keterangan"
                                    class="form-control" rows="1"><?= set_value('keterangan') ?></textarea></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?= base_url('sortasi') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {

        let boxKg = 0;
        let wipRows = [];
        let badIndex = 0;

        // ========================================================
        // DATA MESIN DOMINAN
        // ========================================================
        let daftarMesin = [];


        // ========================================================
        // HELPER
        // ========================================================
        function n(v) {
            return parseFloat(v) || 0;
        }

        function fmt(v) {
            return n(v).toLocaleString('id-ID', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
        }

        function escapeHtml(text) {
            return $('<div>').text(text ?? '').html();
        }


        // ========================================================
        // LOAD WIP
        // ========================================================
        function loadWip(uuid) {

            $.getJSON(
                "<?= base_url('sortasi/get_wip_batch/') ?>" + uuid,
                function(rows) {

                    wipRows = rows || [];

                    let total = 0;

                    wipRows.forEach(function(r) {
                        total += n(r.sisa_wip);
                    });

                    $('#sisaWipBox').text(fmt(total));
                    $('#sisaWipKg').text(fmt(total * boxKg));

                    $('#wipInfo').removeClass('d-none');

                    updateWip();
                }
            ).fail(function() {

                wipRows = [];

                $('#sisaWipBox').text('0.000');
                $('#sisaWipKg').text('0.000');

                console.error('Gagal mengambil data WIP batch.');

            });
        }


        // ========================================================
        // LOAD MESIN BATCH
        // ========================================================
        function loadMesin(uuid) {

            daftarMesin = [];

            // Hapus select2 yang sudah ada
            $('.select2Mesin').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            $.getJSON(
                "<?= base_url('sortasi/get_mesin_batch/') ?>" + uuid,
                function(rows) {

                    daftarMesin = rows || [];

                    console.log('Mesin batch:', daftarMesin);

                }
            ).fail(function() {

                daftarMesin = [];

                console.error('Gagal mengambil data mesin batch.');

            });
        }


        // ========================================================
        // PILIH BATCH
        // ========================================================
        $('#tbatch_uuid').change(function() {

            let selected = $(this).find(':selected');
            let uuid = $(this).val();

            boxKg = n(selected.data('box-kg'));

            $('#wip_kg').val('');
            $('#wip_box').val('');

            $('#wipHidden').empty();

            // Reset mesin
            daftarMesin = [];

            if (!uuid) {

                $('#wipInfo').addClass('d-none');

                return;
            }

            $('#sisaWipBox').text(
                fmt(selected.data('sisa-box'))
            );

            $('#sisaWipKg').text(
                fmt(selected.data('sisa-kg'))
            );

            $('#wipInfo').removeClass('d-none');

            // Load WIP
            loadWip(uuid);

            // Load mesin
            loadMesin(uuid);

        });


        // ========================================================
        // INPUT WIP KG
        // ========================================================
        $('#wip_kg').on('input', function() {

            if (boxKg > 0) {

                let kg = n($(this).val());

                $('#wip_box').val(
                    kg > 0 ? (kg / boxKg).toFixed(3) : ''
                );

            }

            updateWip();

        });


        // ========================================================
        // INPUT WIP BOX
        // ========================================================
        $('#wip_box').on('input', function() {

            if (boxKg > 0) {

                let box = n($(this).val());

                $('#wip_kg').val(
                    box > 0 ? (box * boxKg).toFixed(3) : ''
                );

            }

            updateWip();

        });


        // ========================================================
        // UPDATE WIP
        // ========================================================
        function updateWip() {

            let box = n($('#wip_box').val());

            $('#jumlah_sortir').val(box);

            allocate(box);

            calculate();

        }


        // ========================================================
        // ALOKASI WIP
        // ========================================================
        function allocate(total) {

            let remain = total;
            let html = '';

            $('#wipHidden').empty();

            for (
                let i = 0; i < wipRows.length && remain > 0; i++
            ) {

                let available = n(wipRows[i].sisa_wip);

                let take = Math.min(
                    remain,
                    available
                );

                if (take > 0) {

                    html += `
                        <input type="hidden"
                               name="wip_uuid[]"
                               value="${escapeHtml(wipRows[i].uuid)}">

                        <input type="hidden"
                               name="wip_jumlah[]"
                               value="${take}">
                    `;

                    remain -= take;
                }
            }

            $('#wipHidden').html(html);

        }


        // ========================================================
        // OUTPUT SORTASI
        // ========================================================
        $('.outputBox').on('input', function() {
            calculate();
        });


        // ========================================================
        // HITUNG HASIL
        // ========================================================
        function calculate() {

            let inputKg = n($('#wip_kg').val());

            let outputBox =
                n($('#release_box').val()) +
                n($('#output_tampung').val()) +
                n($('#output_kasar').val()) +
                n($('#output_cuci').val());

            let outputKg =
                outputBox * boxKg;

            let bad =
                n($('#totalBad').text());

            let sisa =
                inputKg -
                outputKg -
                bad;

            $('#sisaKg').text(
                fmt(sisa)
            );

            $('#sisaBox').text(
                boxKg > 0 ?
                fmt(sisa / boxKg) :
                '0.000'
            );
        }


        // ========================================================
        // TAMBAH BAD PRODUK
        // ========================================================
        $('#btnTambahBad').on('click', function(e) {

            e.preventDefault();

            $('#badContainer .text-muted').remove();

            let i = badIndex++;

            let row = badRow(i);

            $('#badContainer').append(row);

            // Inisialisasi select2 hanya pada row baru
            initSelect2Mesin(
                $('#badContainer .bad-card').last()
            );

        });


        // ========================================================
        // BAD ROW
        // ========================================================
        function badRow(i) {

            let options =
                '<option value="">Pilih Bad Produk</option>';

            <?php foreach ($badpro as $b): ?>

                options += `
                    <option
                        value="<?= html_escape($b->uuid_badpro) ?>"
                        data-kategori="<?= html_escape($b->kategori_nama) ?>">
                        <?= html_escape($b->nama_badpro) ?>
                    </option>
                `;

            <?php endforeach; ?>


            return `
                <div class="card border mb-2 bad-card" data-i="${i}">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-5">

                                <label>
                                    Bad Produk
                                </label>

                                <select
                                    name="badpro_uuid[]"
                                    class="form-control badSelect"
                                    required>

                                    ${options}

                                </select>

                            </div>


                            <div class="col-md-2">

                                <label>
                                    Kategori
                                </label>

                                <input
                                    type="text"
                                    class="form-control kategori"
                                    readonly>

                            </div>


                            <div class="col-md-3">

                                <label>
                                    Berat (Kg)
                                </label>

                                <input
                                    type="number"
                                    name="badpro_berat[]"
                                    class="form-control badWeight"
                                    min="0"
                                    step="0.001"
                                    required>

                            </div>


                            <div class="col-md-2">

                                <label>
                                    Mesin Dominan
                                    <small>(opsional)</small>
                                </label>

                                <select
                                    name="mesin_uuid[${i}][]"
                                    class="form-control mesinDominan select2Mesin"
                                    multiple="multiple">

                                    ${generateOptionMesinDominan()}

                                </select>

                            </div>

                        </div>


                        <div class="text-right mt-2">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm removeBad">

                                <i class="fa fa-trash"></i>

                            </button>

                        </div>

                    </div>

                </div>
            `;
        }


        // ========================================================
        // OPTION MESIN
        // ========================================================
        function generateOptionMesinDominan() {

            let html = '';

            if (!Array.isArray(daftarMesin) ||
                daftarMesin.length === 0) {

                return `
                    <option value="" disabled>
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


        // ========================================================
        // SELECT2 MESIN
        // ========================================================
        function initSelect2Mesin(container) {

            $(container)
                .find('.select2Mesin')
                .each(function() {

                    let $select = $(this);

                    if (
                        $select.hasClass(
                            'select2-hidden-accessible'
                        )
                    ) {
                        return;
                    }

                    $select.select2({

                        placeholder: 'Pilih Mesin Dominan',

                        width: '100%',

                        allowClear: true,

                        closeOnSelect: false

                    });

                });
        }


        // ========================================================
        // BAD PRODUK CHANGE
        // ========================================================
        $(document).on(
            'change',
            '.badSelect',
            function() {

                let kategori =
                    $(this)
                    .find(':selected')
                    .data('kategori') || '';

                $(this)
                    .closest('.bad-card')
                    .find('.kategori')
                    .val(kategori);

            }
        );


        // ========================================================
        // BERAT BAD
        // ========================================================
        $(document).on(
            'input',
            '.badWeight',
            function() {

                totalBad();

                calculate();

            }
        );


        // ========================================================
        // HAPUS BAD
        // ========================================================
        $(document).on(
            'click',
            '.removeBad',
            function() {

                let card =
                    $(this).closest('.bad-card');

                // Destroy select2 sebelum remove
                card.find('.select2Mesin').each(function() {

                    if (
                        $(this).hasClass(
                            'select2-hidden-accessible'
                        )
                    ) {

                        $(this).select2('destroy');

                    }

                });

                card.remove();

                if (
                    $('#badContainer .bad-card').length === 0
                ) {

                    $('#badContainer').html(`
                        <div class="text-center text-muted">
                            Tidak ada Bad Produk.
                        </div>
                    `);

                }

                totalBad();

                calculate();

            }
        );


        // ========================================================
        // TOTAL BAD
        // ========================================================
        function totalBad() {

            let total = 0;

            $('.badWeight').each(function() {

                total += n($(this).val());

            });

            $('#totalBad').text(
                total.toFixed(3)
            );

        }


        // ========================================================
        // SUBMIT
        // ========================================================
        $('#formSortasi').submit(function(e) {

            let input =
                n($('#wip_kg').val());

            if (input <= 0) {

                alert(
                    'WIP yang digunakan harus diisi.'
                );

                e.preventDefault();

                return false;
            }


            let available = 0;

            wipRows.forEach(function(r) {

                available +=
                    n(r.sisa_wip);

            });


            let usedBox =
                n($('#wip_box').val());


            if (usedBox > available + 0.000001) {

                alert(
                    'WIP yang digunakan melebihi sisa WIP batch.'
                );

                e.preventDefault();

                return false;
            }


            return true;

        });

    });
</script>