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
            <form action="<?= base_url('filkar/tambah') ?>" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Batch</label>
                            <select
                                name="tbatch_uuid"
                                class="form-control">
                                <option value="">Pilih Batch</option>
                                <?php foreach ($batch as $b): ?>
                                    <option
                                        value="<?= $b->uuid ?>"
                                        <?= set_select('tbatch_uuid', $b->uuid) ?>>
                                        <?= $b->kode_batch ?>
                                        -
                                        <?= $b->varian ?>
                                        (<?= $b->keterangan ?>)
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
                            <input
                                type="number"
                                step="0.01"
                                name="berat"
                                class="form-control"
                                value="<?= set_value('berat') ?>">
                            <small class="text-danger">
                                <?= form_error('berat') ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea
                        name="keterangan"
                        class="form-control"
                        rows="2"><?= set_value('keterangan') ?></textarea>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Data Bad Produk
                    </h5>
                    <button
                        type="button"
                        id="btnTambah"
                        class="btn btn-success btn-sm">
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
                <button
                    type="submit"
                    class="btn btn-success">
                    <i class="fa fa-save"></i>
                    Simpan
                </button>
                <a
                    href="<?= base_url('filkar') ?>"
                    class="btn btn-danger">
                    <i class="fa fas fa-times"></i>
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
<script>
    $(function() {
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
    });

    function getBadproRow() {
        return `
<tr>
<td>
<select
name="badpro_uuid[]"
class="form-control badproSelect"
required>
<option value="">Pilih Bad Produk</option>
            <?php foreach ($badpro as $bp): ?>
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