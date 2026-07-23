<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Checklist Pengecekan Tools Mesin</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('tools_mesin/data/') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Tools Mesin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checklist</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('tools_mesin/tambahdata/'); ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area" id="area"
                            required>
                            <option disabled selected>Pilih Area</option>
                            <?php foreach ($area as $a) { ?>
                            <option value="<?= $a->uuid; ?>" <?= set_select('area', $a->uuid); ?>>
                                <?= $a->nama_area; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <label class="form-label">Pengecekan Tools :<span class="text-danger">*</span></label>
                        <div id="tools-list">
                            <!-- Daftar kode barang akan dimuat di sini -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan :<span class="text-danger">*</span></label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            placeholder="Masukkan Keterangan" value="<?= set_value('keterangan'); ?>">
                        <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                            <?= form_error('keterangan') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-5">
                        <label class="form-label"> <b>NOTE :</b><br>
                            ● Jika Kondisi dan Kelengkapan Ya maka centang (✓).<br>
                            ● Jika Kondisi dan Kelengkapan Tidak maka tidak perlu centang.
                        </label><br><br>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('tools_mesin/data/') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Muat Sub Area Berdasarkan Area Terpilih

    $('#area').change(function() {
        var area_uuid = $(this).val();

        $.get('<?= base_url('tools_mesin/get_tools_by_area/'); ?>' + area_uuid, function(res) {
            var data = JSON.parse(res);
            var elem = `
                <table class="table table-bordered">
                    <thead class="bg-info text-light">
                        <tr>
                            <th class="text-center align-middle">Kode Barang</th>
                            <th class="text-center">Kondisi Baik</th>
                            <th class="text-center">Kelengkapan</th>
                        </tr>
                            
                        
                    </thead>
                    <tbody>`;

            data.forEach(function(val) {
                elem += `
                    <tr>
                        <td>${val.nama_tools}</td>
                        <td class="text-center">
                        <input class="form-check-input kode-checkbox" type="checkbox" name="tools[${val.uuid}]" value="1" id="tools_${val.uuid}">
                        </td>
                        <td class="text-center">
                        <input class="form-check-input kode-checkbox" type="checkbox" name="lengkap[${val.uuid}]" value="1" id="lengkap_${val.uuid}">
                        </td>
                    </tr>`;
            });

            elem += `</tbody></table>`;
            $('#tools-list').html(elem);
        });
    });
});
</script>