<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Mesin</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('mesin') ?>"><i class="fas fa-arrow-left mr-2"></i>Data Mesin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="<?= base_url('manual_books/tambah'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group col-sm-6 mb-3">
                <label for="area">Nama Area <span class="text-danger">*</span></label>
                <select name="area" id="area" class="form-control <?= form_error('area') ? 'is-invalid' : '' ?>" aria-describedby="areaFeedback" aria-invalid="<?= form_error('area') ? 'true' : 'false'; ?>">
                    <option value="">Pilih Area</option>
                    <?php foreach ($area as $are): ?>
                        <option value="<?= $are->uuid; ?>" <?= set_select('area', $are->uuid); ?>><?= $are->nama_area; ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="areaFeedback" class="invalid-feedback <?= form_error('area') ? 'd-block' : ''; ?>">
                    <?= form_error('area'); ?>
                </div>
            </div>

            <div class="form-group col-sm-6 mb-3">
                <label for="mesin">Nama Mesin <span class="text-danger">*</span></label>
                <select name="mesin" id="mesin" class="form-control <?= form_error('mesin') ? 'is-invalid' : '' ?>">

                </select>
                <div class="invalid-feedback <?= !empty(form_error('mesin')) ? 'd-block' : ''; ?>">
                    <?= form_error('mesin'); ?>
                </div>
            </div>


            <div class="form-group col-sm-6 mb-3">
                <label for="judul" class="form-label">Judul Pdf <span class="text-danger">*</span></label>
                <input type="text" name="judul" id="judul" class="form-control <?= form_error('judul') ? 'is-invalid' : '' ?>" value="<?= set_value('judul'); ?>">
                <div class="invalid-feedback <?= !empty(form_error('judul')) ? 'd-block' : ''; ?>">
                    <?= form_error('judul'); ?>
                </div>
            </div>

            <div class="form-group col-sm-6">
                <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                <textarea type="text" class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
            </div>

            <div class="form-group col-sm-6 mb-3">
                <label for="formFile" class="form-label">File PDF <span class="text-danger">*</span></label>
                <input class="form-control <?= form_error('pdf') ? 'is-invalid' : '' ?>" type="file" name="pdf" id="pdf">
                
                <!-- Div untuk pesan error -->
                <div class="invalid-feedback <?= !empty(form_error('pdf')) ? 'd-block' : ''; ?>">
                    <?= form_error('pdf'); ?>
                </div>
            </div>
            
            <div class="form-group d-flex justify-content">
                <button type="submit" class="btn btn-md btn-success mr-2 mt-3">
                        <i class="fa fa-save"></i> Simpan
                    </button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
$(document).ready(function() {
    $('select[name="area"]').change(function() {
      var area_uuid = $(this).val();

      $.get('<?= base_url('part/get_mesin_by_area/');?>' + area_uuid, function(res) {
        var result = JSON.parse(res);
        var elem = '<option disabled selected>Pilih Mesin</option>';
        result.forEach(function(val) {
            elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
        })
        $('select[name="mesin"]').html(elem);
        $('select[name="mesin"]').change(function() {
            var mesin_uuid = $(this).val();
            $.get('<?= base_url('mesin/get_mesin_name/');?>'+mesin_uuid,function(res) {
                var data = JSON.parse(res);
                $('input[name="mesin_name"]').val(data.nama_mesin);
            })
        })
      })
    })
})
</script>