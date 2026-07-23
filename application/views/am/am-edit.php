<div class="container-fluid">
  <h1 class="h3 mb-2 text-gray-800">Ubah Data Planning AM</h1>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= base_url($this->uri->segment(2)=='tpm'?'am/tpm':'am') ?>">
          <i class="fas fa-arrow-left mr-2"></i>Planning AM
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
  </nav>

  <div class="card shadow mb-4">
    <div class="card-body">
      <form class="user" action="<?= base_url('am/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'edit/'.$data->uuid) ?>" method="post" enctype="multipart/form-data">
        
        <!-- AREA -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Area</label>
            <select class="form-control <?= form_error('area') ? 'is-invalid' : '' ?>" name="area" id="area">
              <option disabled>Pilih Area</option>
              <?php foreach ($area as $a): ?>
                <option value="<?= $a->uuid; ?>" <?= ($data->area_uuid == $a->uuid) ? 'selected' : ''; ?>>
                  <?= $a->nama_area; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
              <?= form_error('area') ?>
            </div>
          </div>
        </div>

        <!-- MESIN -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Mesin</label>
            <select class="form-control <?= form_error('mesin') ? 'is-invalid' : '' ?>" name="mesin" id="mesin">
              <option disabled selected>Pilih Mesin</option>
            </select>
            <div class="invalid-feedback">
              <?= form_error('mesin') ?>
            </div>
          </div>
        </div>

        <!-- KEGIATAN -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Kegiatan</label>
            <select class="form-control <?= form_error('kegiatan') ? 'is-invalid' : '' ?>" name="kegiatan" id="kegiatan">
              <option disabled selected>Pilih Kegiatan</option>
            </select>
            <input type="hidden" name="kegiatan_name" id="kegiatan_name">
            <div class="invalid-feedback">
              <?= form_error('kegiatan') ?>
            </div>
          </div>
        </div>

        <!-- JADWAL -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Pilih Waktu Terjadwal</label>
            <select class="form-control <?= form_error('jadwal') ? 'is-invalid' : '' ?>" name="jadwal">
              <option disabled>berdasarkan RH, plan produksi atau counter?</option>
              <option value="0" <?= ($data->jadwal == 0) ? 'selected' : ''; ?>>RH Harian</option>
              <option value="1" <?= ($data->jadwal == 1) ? 'selected' : ''; ?>>Plan Produksi</option>
              <option value="2" <?= ($data->jadwal == 2) ? 'selected' : ''; ?>>Counter Filler</option>
            </select>
            <div class="invalid-feedback">
              <?= form_error('jadwal') ?>
            </div>
          </div>
        </div>

        <!-- TARGET -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Jadwal</label>
            <input type="text" name="target" class="form-control <?= form_error('target') ? 'is-invalid' : '' ?>" value="<?= set_value('target', $data->target); ?>">
            <div class="invalid-feedback">
              <?= form_error('target') ?>
            </div>
          </div>
        </div>

        <!-- BUTTON -->
        <div class="row mt-3">
          <div class="col">
            <button type="submit" class="btn btn-md btn-success mr-2">
              <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?= base_url($this->uri->segment(2)=='tpm'?'am/tpm':'am') ?>" class="btn btn-md btn-danger">
              <i class="fa fa-times"></i> Batal
            </a>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
  <script>
$(document).ready(function () {
    var selectedMesin = "<?= $data->mesin_uuid; ?>";
    var selectedKegiatan = "<?= $data->kegiatan_uuid; ?>";

    // =========================
    // LOAD MESIN BERDASARKAN AREA
    // =========================
    function loadMesin(area_uuid, selected = '') {
        $('#mesin').html('<option disabled selected>Loading mesin...</option>');
        $('#kegiatan').html('<option disabled selected>Pilih Kegiatan</option>');

        $.ajax({
            url: "<?= base_url('am/get_mesin_by_area/'); ?>" + area_uuid,
            type: "GET",
            dataType: "json",
            success: function (res) {
                var html = '<option disabled selected>Pilih Mesin</option>';
                $.each(res, function (i, item) {
                    var isSelected = (item.uuid == selected) ? 'selected' : '';
                    html += '<option value="' + item.uuid + '" ' + isSelected + '>' + item.nama_mesin + '</option>';
                });
                $('#mesin').html(html);

                if (selected) {
                    loadKegiatan(selected, selectedKegiatan);
                }
            }
        });
    }

    // =========================
    // LOAD KEGIATAN BERDASARKAN MESIN
    // =========================
    function loadKegiatan(mesin_uuid, selected = '') {
        $('#kegiatan').html('<option disabled selected>Loading kegiatan...</option>');

        $.ajax({
            url: "<?= base_url('am/get_kegiatan_by_mesin/'); ?>" + mesin_uuid,
            type: "GET",
            dataType: "json",
            success: function (res) {
                var html = '<option disabled selected>Pilih Kegiatan</option>';
                $.each(res, function (i, item) {
                    var isSelected = (item.uuid == selected) ? 'selected' : '';
                    html += '<option value="' + item.uuid + '" ' + isSelected + '>' + item.kegiatan + '</option>';
                });
                $('#kegiatan').html(html);
            }
        });
    }

    // =========================
    // SAAT AREA DIGANTI
    // =========================
    $('#area').change(function () {
        var area_uuid = $(this).val();
        loadMesin(area_uuid);
    });

    // =========================
    // SAAT MESIN DIGANTI
    // =========================
    $('#mesin').change(function () {
        var mesin_uuid = $(this).val();
        loadKegiatan(mesin_uuid);
    });

    // =========================
    // SAAT KEGIATAN DIGANTI
    // =========================
    $('#kegiatan').change(function () {
        var kegiatan_name = $('#kegiatan option:selected').text();
        $('#kegiatan_name').val(kegiatan_name);
    });

    // =========================
    // AUTO LOAD SAAT EDIT
    // =========================
    var currentArea = $('#area').val();
    if (currentArea) {
        loadMesin(currentArea, selectedMesin);
    }
});
</script>
