<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Tambah Filling Karantina</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('filkar') ?>"><i class="fas fa-arrow-left mr-2"></i>Filling Karantina</a></li>
        <li class="breadcrumb-item active">Tambah Data</li>
    </ol></nav>

    <div class="card shadow">
        <div class="card-header"><b>Input Data Filling Karantina</b></div>
        <div class="card-body">
            <form id="formData" action="<?= base_url('filkar/tambah') ?>" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Kode Batch <span class="text-danger">*</span></label>
                            <select name="tbatch_uuid" id="tbatch_uuid" class="form-control" required>
                                <option value="">Pilih Batch</option>
                                <?php foreach($batch as $b): ?>
                                    <option value="<?= html_escape($b->uuid) ?>" data-box-kg="<?= (float)$b->box_kg ?>" data-adonan="<?= (float)$b->adonan ?>" <?= set_select('tbatch_uuid',$b->uuid) ?>>
                                        <?= html_escape($b->kode_batch) ?> - <?= html_escape($b->varian) ?><?= !empty($b->keterangan)?' ('.html_escape($b->keterangan).')':'' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('tbatch_uuid') ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Berat Filling</label>
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" name="berat" id="berat" class="form-control" value="<?= set_value('berat') ?>" required>
                                <div class="input-group-append"><span class="input-group-text">Kg</span></div>
                            </div>
                            <small class="text-danger"><?= form_error('berat') ?></small>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" id="jumlah_box" class="form-control" placeholder="Isi Box">
                                <div class="input-group-append"><span class="input-group-text">Box</span></div>
                            </div>
                            <small class="text-muted">Isi Kg atau Box. Nilai lainnya otomatis.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6"><label>Jam Mulai</label><input type="time" name="mulai" class="form-control" value="<?= set_value('mulai') ?>" required><small class="text-danger"><?= form_error('mulai') ?></small></div>
                    <div class="col-md-6"><label>Jam Selesai</label><input type="time" name="selesai" class="form-control" value="<?= set_value('selesai') ?>" required><small class="text-danger"><?= form_error('selesai') ?></small></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6"><label>Jumlah Man Power</label><input type="number" step="1" min="1" name="jml_mp" class="form-control" value="<?= set_value('jml_mp') ?>" required></div>
                    <div class="col-md-6"><label>Keterangan</label><textarea name="keterangan" class="form-control" rows="1"><?= set_value('keterangan') ?></textarea></div>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <b>Bad Produk</b>
                    <button type="button" id="btnTambah" class="btn btn-success btn-sm"><i class="fa fa-plus mr-1"></i>Tambah</button>
                </div>
                <div id="badproSection" style="display:none">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tblBadpro">
                            <thead class="thead-light"><tr><th>Bad Produk</th><th>Kategori</th><th>Berat (Kg)</th><th>Aksi</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?= base_url('filkar') ?>" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    let boxKg=0, allowSubmit=false;

    $('#tbatch_uuid').change(function(){
        boxKg=parseFloat($(this).find(':selected').data('box-kg'))||0;
        let kg=parseFloat($('#berat').val())||0;
        if(boxKg>0 && kg>0) $('#jumlah_box').val((kg/boxKg).toFixed(3));
    });
    $('#berat').on('input',function(){
        if(boxKg>0) $('#jumlah_box').val(((parseFloat(this.value)||0)/boxKg).toFixed(3));
    });
    $('#jumlah_box').on('input',function(){
        if(boxKg>0) $('#berat').val(((parseFloat(this.value)||0)*boxKg).toFixed(3));
    });

    $('#btnTambah').click(function(){
        $('#badproSection').show();
        $('#tblBadpro tbody').append(getBadproRow());
    });
    $(document).on('change','.badproSelect',function(){
        $(this).closest('tr').find('.kategori_nama').val($(this).find(':selected').data('kategori')||'');
    });
    $(document).on('click','.btnRemove',function(){
        $(this).closest('tr').remove();
        if(!$('#tblBadpro tbody tr').length)$('#badproSection').hide();
    });

    $('#formData').submit(function(e){
        if(allowSubmit)return true;
        let batch=$('#tbatch_uuid').find(':selected'), adonan=parseFloat(batch.data('adonan'))||0, berat=parseFloat($('#berat').val())||0;
        /*
         * Peringatan 50% ini adalah rule lama yang sudah ada di aplikasi,
         * bukan rumus Box <-> Kg.
         */
        if(batch.val() && berat>adonan*1.5){
            e.preventDefault();
            if(confirm('Berat '+berat.toFixed(3)+' Kg melebihi 150% adonan ('+(adonan*1.5).toFixed(3)+' Kg). Apakah data sudah sesuai?')){
                allowSubmit=true; $('#formData').submit();
            }
            return false;
        }
        return true;
    });

    function getBadproRow(){
        return `<tr>
            <td><select name="badpro_uuid[]" class="form-control badproSelect" required>
                <option value="">Pilih Bad Produk</option>
                <?php foreach($badpro as $bp): ?>
                <option value="<?= html_escape($bp->uuid_badpro) ?>" data-kategori="<?= html_escape($bp->kategori_nama) ?>"><?= html_escape($bp->nama_badpro) ?></option>
                <?php endforeach; ?>
            </select></td>
            <td><input type="text" class="form-control kategori_nama bg-light" readonly></td>
            <td><input type="number" step="0.001" min="0" name="jumlah_badpro[]" class="form-control" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger btnRemove"><i class="fa fa-trash"></i></button></td>
        </tr>`;
    }
});
</script>
