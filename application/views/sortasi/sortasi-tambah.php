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
                <!-- =========================
                     BATCH
                ========================== -->
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
                <!-- =========================
                     INFO BATCH
                ========================== -->
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
                <!-- =========================
                     SORTASI
                ========================== -->
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
                                    <input type="number" name="jumlah_sortir" id="jumlah_sortir" class="form-control" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Release Box
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="release_box" id="release_box" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            Maksimal Bad Produk :
                            <b>
                                <span id="maksimalBadProduk">
                                    0
                                </span>
                                Kg
                            </b>
                        </div>
                        <div class="form-group">
                            <label>
                                Keterangan
                            </label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <!-- =========================
                     MESIN
                ========================== -->
                <div class="card border-left-danger mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <b>
                                <i class="fas fa-industry mr-2"></i>
                                Bad Produk Per Mesin
                            </b>
                            <button type="button" id="btnTambahMesin" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i>
                                Tambah Mesin
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="mesinContainer">
                            <div class="text-center text-muted">
                                Belum ada mesin dipilih
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =========================
                     SUMMARY
                ========================== -->
                <div class="alert alert-danger">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6>
                                Total Mesin
                            </h6>
                            <h4 id="totalMesin">
                                0
                            </h4>
                        </div>
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
    let indexMesin = 0;
    $(document).ready(function() {
        // ===============================
        // PILIH BATCH
        // ===============================
        $('#tbatch_uuid').change(function() {
            let uuid = $(this).val();
            resetMesin();
            if (uuid == '') {
                resetInfoBatch();
                return;
            }
            loadBatchInfo(uuid);
            loadMesin(uuid);
        });
        // ===============================
        // LOAD MESIN
        // ===============================
        function loadMesin(uuid) {
            $.ajax({
                url: "<?= base_url('sortasi/get_mesin_batch/') ?>" + uuid,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    daftarMesin = data;
                    updateButtonMesin();
                }
            });
        }
        // ===============================
        // TAMBAH MESIN
        // ===============================
        $('#btnTambahMesin').click(function() {
            if (daftarMesin.length == 0) {
                alert('Mesin belum tersedia.');
                return;
            }
            $('#mesinContainer .text-muted').remove();
            $('#mesinContainer').append(
                createMesinCard()
            );
            indexMesin++;
            updateButtonMesin();
            hitungTotalMesin();
        });
        // ===============================
        // BUAT CARD MESIN
        // ===============================
        function createMesinCard() {
            let html = `
        <div class="card border-left-secondary mb-3 mesin-card"
             data-index="${indexMesin}">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between">
                    <b>
                        <i class="fas fa-industry mr-2"></i>
                        Mesin
                    </b>
                    <button type="button"
                            class="btn btn-danger btn-sm btnHapusMesin">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>
                        Pilih Mesin
                    </label>
                    <select
                        name="mesin_uuid[]"
                        class="form-control mesinSelect"
                        required>
                        <option value="">
                            Pilih Mesin
                        </option>
                        ${generateOptionMesin()}
                    </select>
                </div>
                <div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th width="35%">
    Bad Produk
</th>
<th width="20%">
    Kategori
</th>
<th width="25%">
    Berat (Kg)
</th>
<th width="20%">
    Aksi
</th>
            </tr>
        </thead>
        <tbody class="badproContainer">
        </tbody>
    </table>
</div>
<button
    type="button"
    class="btn btn-success btn-sm btnTambahBad"
    data-index="${indexMesin}">
    <i class="fa fa-plus"></i>
    Tambah Bad Produk
</button>
            </div>
        </div>
        `;
            return html;
        }
        // ===============================
        // OPTION MESIN
        // HANYA YANG BELUM DIPAKAI
        // ===============================
        function generateOptionMesin() {
            let html = '';
            let terpakai = [];
            $('.mesinSelect').each(function() {
                let val = $(this).val();
                if (val != '') {
                    terpakai.push(val);
                }
            });
            daftarMesin.forEach(function(m) {
                if (!terpakai.includes(m.uuid)) {
                    html += `
                <option value="${m.uuid}">
                    ${m.nama_mesin}
                </option>
                `;
                }
            });
            return html;
        }
        // ===============================
        // SAAT MESIN BERUBAH
        // ===============================
        $(document).on(
            'change',
            '.mesinSelect',
            function() {
                refreshSemuaDropdownMesin();
                updateButtonMesin();
            }
        );
        // ===============================
        // REFRESH DROPDOWN
        // ===============================
        function refreshSemuaDropdownMesin() {
            let terpilih = [];
            $('.mesinSelect').each(function() {
                let val = $(this).val();
                if (val != '') {
                    terpilih.push(val);
                }
            });
            $('.mesinSelect').each(function() {
                let nilaiLama = $(this).val();
                let html = `
            <option value="">
                Pilih Mesin
            </option>
        `;
                daftarMesin.forEach(function(m) {
                    if (
                        !terpilih.includes(m.uuid) ||
                        m.uuid == nilaiLama
                    ) {
                        html += `
                    <option value="${m.uuid}">
                        ${m.nama_mesin}
                    </option>
                `;
                    }
                });
                $(this)
                    .html(html)
                    .val(nilaiLama);
            });
        }
        // ===============================
        // HAPUS MESIN
        // ===============================
        $(document).on(
            'click',
            '.btnHapusMesin',
            function() {
                $(this)
                    .closest('.mesin-card')
                    .remove();
                refreshSemuaDropdownMesin();
                updateButtonMesin();
                hitungTotalMesin();
                if ($('.mesin-card').length == 0) {
                    $('#mesinContainer').html(
                        '<div class="text-center text-muted">' +
                        'Belum ada mesin dipilih' +
                        '</div>'
                    );
                }
            }
        );
        // ===============================
        // BUTTON TAMBAH MESIN
        // ===============================
        function updateButtonMesin() {
            let jumlahDipakai = $('.mesinSelect option:selected')
                .filter(function() {
                    return $(this).val() != '';
                }).length;
            if (jumlahDipakai >= daftarMesin.length) {
                $('#btnTambahMesin')
                    .prop('disabled', true);
            } else {
                $('#btnTambahMesin')
                    .prop('disabled', false);
            }
        }

        function hitungTotalMesin() {
            $('#totalMesin')
                .text(
                    $('.mesin-card').length
                );
        }
        // ===============================
        // RESET
        // ===============================
        function resetMesin() {
            daftarMesin = [];
            $('#mesinContainer')
                .html(
                    '<div class="text-center text-muted">' +
                    'Belum ada mesin dipilih' +
                    '</div>'
                );
            $('#btnTambahMesin')
                .prop('disabled', false);
            hitungTotalMesin();
        }

        function resetInfoBatch() {
            $('#filkarBox').text(0);
            $('#sortasiBox').text(0);
            $('#sisaBox').text(0);
            $('#boxKg').text(0);
        }
        // sementara placeholder
        function loadBatchInfo(uuid) {
            $.ajax({
                url: "<?= base_url('sortasi/get_batch_info/') ?>" + uuid,
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        $('#filkarBox')
                            .text(data.filkar_box);
                        $('#sortasiBox')
                            .text(data.sortasi_box);
                        $('#sisaBox')
                            .text(data.sisa_sortasi);
                        $('#boxKg')
                            .text(data.box_kg);
                    }
                }
            });
        }
        // ===============================
        // row Bad Produk
        // ===============================
        function createBadProdukRow(indexMesin) {
            let html = `
    <tr>
<td>
<select
    name="badpro_uuid[${indexMesin}][]"
    class="form-control badproSelect"
    required>
<option value="">
    Pilih Bad Produk
</option>
<?php foreach ($badpro as $bp) : ?>
<option
    value="<?= $bp->uuid_badpro ?>"
    data-kategori="<?= $bp->kategori_nama ?>">
    <?= $bp->nama_badpro ?>
</option>
<?php endforeach; ?>
</select>
</td>
<td>
<input
    type="text"
    class="form-control kategoriBad"
    readonly>
</td>
<td>
<input
    type="number"
    step="0.01"
    min="0"
    name="jumlah_badpro[${indexMesin}][]"
    class="form-control jumlahBad"
    required>
</td>
<td class="text-center">
<button
    type="button"
    class="btn btn-danger btn-sm btnRemoveBad">
<i class="fa fa-trash"></i>
</button>
</td>
</tr>
    `;
            return html;
        }
        // ===============================
        // TAMBAH BAD PRODUK
        // ===============================
        $(document).on(
            'click',
            '.btnTambahBad',
            function() {
                let index = $(this).data('index');
                let table = $(this)
                    .closest('.card-body')
                    .find('.badproContainer');
                table.append(
                    createBadProdukRow(index)
                );
            });
        // ===============================
        // SET KATEGORI BAD PRODUK
        // ===============================
        $(document).on(
            'change',
            '.badproSelect',
            function() {
                let kategori =
                    $(this)
                    .find(':selected')
                    .data('kategori');
                $(this)
                    .closest('tr')
                    .find('.kategoriBad')
                    .val(kategori || '');
            }
        );
        // ===============================
        // HAPUS BAD PRODUK
        // ===============================
        $(document).on(
            'click',
            '.btnRemoveBad',
            function() {
                $(this)
                    .closest('tr')
                    .remove();
            });
        // ===============================
        // Cegah Bad Produk Dobel dalam Mesin Sama
        // ===============================
        $(document).on(
            'change',
            '.badproSelect',
            function() {
                let container =
                    $(this)
                    .closest('.mesin-card');
                let nilai = [];
                container
                    .find('.badproSelect')
                    .each(function() {
                        let val = $(this).val();
                        if (val != '') {
                            if (nilai.includes(val)) {
                                alert(
                                    'Bad Produk tidak boleh dipilih dua kali pada mesin yang sama.'
                                );
                                $(this).val('');
                                return false;
                            }
                            nilai.push(val);
                        }
                    });
            });
        // ===============================
        // hitungan total Bad Produk
        // ===============================
        $(document).on(
            'input',
            '.jumlahBad',
            function() {
                hitungTotalBad();
            });

        function hitungTotalBad() {
            let total = 0;
            let baris = 0;
            $('.jumlahBad').each(function() {
                let nilai =
                    parseFloat($(this).val()) || 0;
                total += nilai;
                if ($(this).val() != '') {
                    baris++;
                }
            });
            $('#totalBarisBad')
                .text(baris);
            $('#totalBadKg')
                .text(
                    total.toFixed(2)
                );
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
        // ===============================
        // hitung maksimal Bad Produk
        // ===============================
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
                .text(
                    maksimal.toFixed(2)
                );
            return maksimal;
        }
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
            });
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
            });
        // ===============================
        // validasi saat submit
        // ===============================
        $('form').submit(function(e) {
            let valid = true;
            // ==========================
            // cek mesin yang dibuat
            // ==========================
            $('.mesin-card').each(function() {
                let mesin = $(this)
                    .find('.mesinSelect')
                    .val();
                if (mesin == '') {
                    alert(
                        'Ada mesin yang belum dipilih.'
                    );
                    valid = false;
                    return false;
                }
            });
            if (!valid) {
                e.preventDefault();
                return false;
            }
            // ==========================
            // cek bad produk
            // ==========================
            $('.badproContainer tr').each(function() {
                let bad =
                    $(this)
                    .find('.badproSelect')
                    .val();
                let berat =
                    parseFloat(
                        $(this)
                        .find('.jumlahBad')
                        .val()
                    ) || 0;
                if (bad == '') {
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
            });
            if (!valid) {
                e.preventDefault();
                return false;
            }
            // ==========================
            // batas maksimal bad produk
            // ==========================
            let totalBad =
                parseFloat($('#totalBadKg').text()) ||
                0;
            let maksimal =
                hitungMaksimalBadProduk();
            if (totalBad > maksimal) {
                alert(
                    'Total Bad Produk melebihi maksimal ' +
                    maksimal.toFixed(2) +
                    ' Kg.'
                );
                e.preventDefault();
                return false;
            }
        });
    });
</script>