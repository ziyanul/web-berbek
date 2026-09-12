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
                            <button type="button" id="btnTambahBad" class="btn btn-primary btn-sm"><i
                                    class="fa fa-plus mr-1"></i>Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="badContainer">
                            <div class="text-center text-muted">Tidak ada Bad Produk.</div>
                        </div>
                        <div class="text-right mt-2"><b>Total Bad: <span id="totalBad">0.000</span> Kg</b></div>
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
        let boxKg = 0,
            wipRows = [],
            badIndex = 0;

        function n(v) {
            return parseFloat(v) || 0;
        }

        function fmt(v) {
            return n(v).toLocaleString('id-ID', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
        }

        function loadWip(uuid) {
            $.getJSON("<?= base_url('sortasi/get_wip_batch/') ?>" + uuid, function(rows) {
                wipRows = rows || [];
                let total = 0;
                wipRows.forEach(r => total += n(r.sisa_wip));
                $('#sisaWipBox').text(fmt(total));
                $('#sisaWipKg').text(fmt(total * boxKg));
                $('#wipInfo').removeClass('d-none');
                updateWip();
            });
        }
        $('#tbatch_uuid').change(function() {
            let o = $(this).find(':selected');
            boxKg = n(o.data('box-kg'));
            $('#wip_kg,#wip_box').val('');
            $('#wipHidden').empty();
            if (!$(this).val()) {
                $('#wipInfo').addClass('d-none');
                return;
            }
            $('#sisaWipBox').text(fmt(o.data('sisa-box')));
            $('#sisaWipKg').text(fmt(o.data('sisa-kg')));
            $('#wipInfo').removeClass('d-none');
            loadWip($(this).val());
        });
        $('#wip_kg').on('input', function() {
            if (boxKg > 0) $('#wip_box').val(n($(this).val()) / boxKg || '');
            updateWip();
        });
        $('#wip_box').on('input', function() {
            if (boxKg > 0) $('#wip_kg').val((n($(this).val()) * boxKg).toFixed(3));
            updateWip();
        });

        function updateWip() {
            let box = n($('#wip_box').val()),
                kg = box * boxKg;
            $('#jumlah_sortir').val(box);
            allocate(box);
            calculate();
        }

        function allocate(total) {
            let remain = total,
                html = '';
            $('#wipHidden').empty();
            for (let i = 0; i < wipRows.length && remain > 0; i++) {
                let available = n(wipRows[i].sisa_wip);
                let take = Math.min(remain, available);
                if (take > 0) {
                    html += '<input type="hidden" name="wip_uuid[]" value="' + wipRows[i].uuid + '">';
                    html += '<input type="hidden" name="wip_jumlah[]" value="' + take + '">';
                    remain -= take;
                }
            }
            $('#wipHidden').html(html);
        }
        $('.outputBox').on('input', calculate);

        function calculate() {
            let inputKg = n($('#wip_kg').val());
            let outputKg = (
                n($('#release_box').val()) +
                n($('#output_tampung').val()) +
                n($('#output_kasar').val()) +
                n($('#output_cuci').val())
            ) * boxKg;
            let bad = n($('#totalBad').text());
            let sisa = inputKg - outputKg - bad;
            $('#sisaKg').text(fmt(sisa));
            $('#sisaBox').text(boxKg > 0 ? fmt(sisa / boxKg) : '0.000');
        }
        $('#btnTambahBad').click(function() {
            $('#badContainer .text-muted').remove();
            let i = badIndex++;
            $('#badContainer').append(badRow(i));
            loadMachines(i);
        });

        function badRow(i) {
            let options = '<option value="">Pilih Bad Produk</option>';
            <?php foreach ($badpro as $b): ?>
                options +=
                    '<option value="<?= html_escape($b->uuid_badpro) ?>" data-kategori="<?= html_escape($b->kategori_nama) ?>"><?= html_escape($b->nama_badpro) ?></option>';
            <?php endforeach; ?>
            return '<div class="card border mb-2 bad-card" data-i="' + i +
                '"><div class="card-body"><div class="row">' +
                '<div class="col-md-5"><label>Bad Produk</label><select name="badpro_uuid[]" class="form-control badSelect" required>' +
                options + '</select></div>' +
                '<div class="col-md-2"><label>Kategori</label><input class="form-control kategori" readonly></div>' +
                '<div class="col-md-3"><label>Berat (Kg)</label><input type="number" name="badpro_berat[]" class="form-control badWeight" min="0" step="0.001" required></div>' +
                '<div class="col-md-2"><label>Mesin Dominan <small>(opsional)</small></label><select name="mesin_uuid[' +
                i + '][]" class="form-control mesin" multiple></select></div>' +
                '</div><div class="text-right mt-2"><button type="button" class="btn btn-danger btn-sm removeBad"><i class="fa fa-trash"></i></button></div></div></div>';
        }

        function loadMachines(i) {
            $.getJSON("<?= base_url('sortasi/get_mesin_batch/') ?>" + $('#tbatch_uuid').val(), function(rows) {
                let sel = $('.bad-card[data-i="' + i + '"] .mesin');
                (rows || []).forEach(function(m) {
                    sel.append('<option value="' + m.uuid + '">' + m.nama_mesin + '</option>');
                });
            });
        }
        $(document).on('change', '.badSelect', function() {
            $(this).closest('.bad-card').find('.kategori').val($(this).find(':selected').data('kategori') ||
                '');
        });
        $(document).on('input', '.badWeight', function() {
            totalBad();
            calculate();
        });
        $(document).on('click', '.removeBad', function() {
            $(this).closest('.bad-card').remove();
            totalBad();
            calculate();
        });

        function totalBad() {
            let t = 0;
            $('.badWeight').each(function() {
                t += n($(this).val());
            });
            $('#totalBad').text(t.toFixed(3));
        }
        $('#formSortasi').submit(function(e) {
            let input = n($('#wip_kg').val()),
                totalOutput = (
                    n($('#release_box').val()) + n($('#output_tampung').val()) + n($('#output_kasar')
                        .val()) + n($('#output_cuci').val())
                ) * boxKg + n($('#totalBad').text());
            if (input <= 0) {
                alert('WIP yang digunakan harus diisi.');
                e.preventDefault();
                return false;
            }
            let available = 0;
            wipRows.forEach(r => available += n(r.sisa_wip));



            return true;
        });
    });
</script>