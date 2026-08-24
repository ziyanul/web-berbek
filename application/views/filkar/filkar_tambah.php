<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">
        Tambah Filling Karantina
    </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('filkar') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Filling Karantina
                </a>
            </li>
            <li class="breadcrumb-item active">
                Tambah Data
            </li>
        </ol>
    </nav>
    <div class="card shadow">
        <div class="card-header">
            <b>Input Data Filling Karantina</b>
        </div>
        <div class="card-body">
            <form id="formData" action="<?= base_url('filkar/tambah') ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Batch</label>
                            <select name="tbatch_uuid" id="tbatch_uuid" class="form-control">
                                <option value="">Pilih Batch</option>
                                <?php foreach ($batch as $b) : ?>
                                    <option value="<?= $b->uuid ?>" data-adonan="<?= $b->adonan ?>" data-kelebihan="<?= $b->kelebihan ?>" <?= set_select('tbatch_uuid', $b->uuid) ?>>
                                        <?= $b->kode_batch ?> - <?= $b->varian ?> (<?= $b->keterangan ?>)
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
                            <label>Berat (Kg)</label>
                            <input type="number" step="0.001" name="berat" id="berat" class="form-control" value="<?= set_value('berat') ?>">
                            <small class="text-danger">
                                <?= form_error('berat') ?>
                            </small>
                        </div>
                    </div>
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
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Data Bad Produk
                    </h5>
                    <button type="button" id="btnTambah" class="btn btn-success btn-sm">
                        <i class="fa fa-plus mr-1"></i>
                        Tambah Bad Produk
                    </button>
                </div>
                <div id="badproSection" style="display:none;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tblBadpro">
                            <thead class="thead-light bg-info">
                                <tr>
                                    <th width="40%">Bad Produk</th>
                                    <th width="25%">Kategori</th>
                                    <th width="20%">Berat (Kg)</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i>
                    Simpan
                </button>
                <a href="<?= base_url('filkar') ?>" class="btn btn-danger">
                    <i class="fa fas fa-times"></i>
                    Batal
                </a>
            </form>
        </div>
        <!-- Modal Konfirmasi Berat -->
<div class="modal fade" id="modalKonfirmasiBerat" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiBeratLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalKonfirmasiBeratLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Data
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <p>
        Berat yang Anda masukkan melebihi 50% dari nilai adonan batch.
    </p>
    <div class="alert alert-warning mb-0">
        <div class="row">
            <div class="col-6">
                <small>Adonan</small>
                <br>
                <strong id="modalAdonan"></strong> Kg
            </div>
            <div class="col-6">
                <small>Berat yang dimasukkan</small>
                <br>
                <strong id="modalBerat"></strong> Kg
            </div>
        </div>
    </div>
    <p class="mt-3 mb-0">
        Apakah data yang Anda masukkan sudah sesuai?
    </p>
</div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Tidak
                </button>
                <button
                    type="button"
                    id="btnKonfirmasiBerat"
                    class="btn btn-success">
                    <i class="fas fa-check mr-1"></i>
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<script>
    $(function() {
        let allowSubmit = false;
        // ============================
        // TAMBAH BAD PRODUK
        // ============================
        $('#btnTambah').click(function() {
            $('#badproSection').show();
            $('#tblBadpro tbody').append(getBadproRow());
        });
        $(document).on('change', '.badproSelect', function() {
            let kategori = $(this).find(':selected').data('kategori') ?? '';
            $(this)
                .closest('tr')
                .find('.kategori_nama')
                .val(kategori);
        });
        $(document).on('click', '.btnRemove', function() {
            $(this).closest('tr').remove();
            if ($('#tblBadpro tbody tr').length == 0) {
                $('#badproSection').hide();
            }
        });
        // ============================
        // SUBMIT FORM
        // ============================
        $('#formData').on('submit', function(e) {
            // Jika sudah dikonfirmasi "Ya",
            // lanjutkan submit normal.
            if (allowSubmit) {
                return true;
            }
            let batch = $('#tbatch_uuid').find(':selected');
            let adonan = parseFloat(batch.data('adonan')) || 0;
            let batas = parseFloat(batch.data('kelebihan')) || 0;
            let berat = parseFloat($('#berat').val()) || 0;
            // Jika batch belum dipilih,
            // biarkan form_validation menangani.
            if (!batch.val()) {
                return true;
            }
            // Jika berat belum ada,
            // biarkan form_validation menangani.
            if (!berat) {
                return true;
            }
            // ============================
            // CEK BATAS 50%
            // ============================
            if (berat > batas) {
                e.preventDefault();
                $('#modalAdonan').text(
                    adonan.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 3
                    })
                );
                $('#modalBatas').text(
                    batas.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 3
                    })
                );
                $('#modalBerat').text(
                    berat.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 3
                    })
                );
                $('#modalKonfirmasiBerat').modal('show');
                return false;
            }
            return true;
        });
        // ============================
        // KLIK "YA, LANJUTKAN"
        // ============================
        $('#btnKonfirmasiBerat').click(function() {
            allowSubmit = true;
            $('#modalKonfirmasiBerat').modal('hide');
            // Submit ulang form secara normal
            $('#formData').submit();
        });
    });
    // ============================
    // BAD PRODUK ROW
    // ============================
    function getBadproRow() {
        return `
            <tr>
                <td>
                    <select
                        name="badpro_uuid[]"
                        class="form-control badproSelect"
                        required>
                        <option value="">Pilih Bad Produk</option>
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
                        class="form-control kategori_nama bg-light"
                        readonly>
                </td>
                <td>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="jumlah_badpro[]"
                        class="form-control"
                        placeholder="Kg"
                        required>
                </td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-danger btnRemove">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }
</script>