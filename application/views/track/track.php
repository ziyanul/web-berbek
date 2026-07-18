<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tracking Mesin</h1>

    <form id="formFilter" class="mb-4">
        <div class="form-group">
            <label>Mesin: </label>
            <select name="mesin_uuid" class="form-control" required>
                <option disabled selected value="">Pilih Mesin</option>
                <?php foreach ($mesin as $row): ?>
                    <option value="<?= $row->device_id; ?>" <?= set_select('mesin_uuid', $row->device_id); ?>>
                        <?= $row->nama_mesin; ?>
                    </option>
                <?php endforeach ?>
            </select>

        </div>
        <div class="form-group">
            <label>Dari Tanggal</label>
            <input type="date" name="awal" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Sampai Tanggal</label>
            <input type="date" name="akhir" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <div id="hasilRekap"></div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- pastikan ini ada -->
<script>
    $('#formFilter').on('submit', function(e){
    e.preventDefault();

    var mesin = $('select[name="mesin_uuid"]').val();
    var awal = $('input[name="awal"]').val();
    var akhir = $('input[name="akhir"]').val();

    if(!mesin || !awal || !akhir){
        alert('Semua input harus diisi.');
        return;
    }

    $.post('<?= site_url('track/rekap') ?>', $(this).serialize(), function(res){
        console.log("Response dari server:", res);

        $('#hasilRekap').html(res);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log("Error:", jqXHR.responseText);
        alert('Terjadi kesalahan saat memproses data.');
    });
});


</script>

