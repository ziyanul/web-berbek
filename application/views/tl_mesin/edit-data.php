<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Edit Data Pengecekan Tools Mesin</h1>
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
                        <select class="form-control <?= form_error('area') ? 'is-invalid' : '' ?>" name="area">

                            <option value="">-- Pilih Area --</option>

                            <?php foreach($area as $a): ?>
                                <option value="<?= $a->uuid ?>"
                                    <?= set_select(
                                        'area',
                                        $a->uuid,
                        ($data->area_uuid == $a->uuid) // auto selected saat edit
                        ); ?>>
                        <?= $a->nama_area ?>
                    </option>
                <?php endforeach; ?>

            </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Tools :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('tools') ? 'is-invalid' : '' ?>" name="area">

                            <option value="">-- Pilih Tools --</option>

                            <?php foreach($tools as $t): ?>
                                <option value="<?= $t->uuid ?>"
                                    <?= set_select(
                                        'tools',
                                        $t->uuid,
                        ($data->tools_uuid == $t->uuid) // auto selected saat edit
                        ); ?>>
                        <?= $t->nama_tools ?>
                    </option>
                <?php endforeach; ?>

            </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 mb-4">
                        <label class="form-label">Kondisi :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('tools') ? 'is-invalid' : '' ?>" name="area">

                            <option value="">-- Pilih Kondisi --</option>

                            <?php foreach($tools as $t): ?>
                                <option value="<?= $t->uuid ?>"
                                    <?= set_select(
                                        'tools',
                                        $t->uuid,
                        ($data->tools_uuid == $t->uuid) // auto selected saat edit
                        ); ?>>
                        <?= $t->nama_tools ?>
                    </option>
                <?php endforeach; ?>

            </select>
                    </div>
                    <div class="col-sm-3 mb-4">
                        <label class="form-label">Kelengkapan :<span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('kelengkapan') ? 'is-invalid' : '' ?>" name="area">

                            <option value="">-- Pilih Kelengkapan --</option>

                            <select class="form-control" name="kelengkapan">
                                <option value="1" <?= $data->kelengkapan == 1 ? 'selected' : '' ?>>
                                    OK
                                </option>
                                <option value="0.75" <?= $data->kelengkapan == 2 ? 'selected' : '' ?>>
                                    NO
                                </option>
                            </select>
                    </option>
                <?php endforeach; ?>

            </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Keterangan :<span class="text-danger">*</span></label>
                        <input type="text" name="keterangan"
                            class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>"
                            value="<?= $data->keterangan; ?>">
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
        });
    });
});
</script>