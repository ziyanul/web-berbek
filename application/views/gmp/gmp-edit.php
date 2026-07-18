<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">INPUT PELAKSANA ISO/TS</h1>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tpm'?'gmp/tpm':'gmp') ?>"><i class="fas fa-arrow-left mr-2"></i>Monitoring ISO/TS</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tindakan</li>
    </ol>
  </nav>
  <div class="card shadow mb-4">
    <div class="card-body">
      <form class="user"
      action="<?= base_url('gmp/'.($this->uri->segment(2)=='tpm' ? 'tpm/' : '').'edit/'.$data->uuid) ?>"
      method="post"
      enctype="multipart/form-data">

      <!-- Informasi Data -->
      <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Area</label>
        <div class="col-sm-10 pt-2">
          <b>: <?= $data->nama_area; ?></b>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Lokasi</label>
        <div class="col-sm-10 pt-2">
          <b>: <?= $data->lokasi; ?></b>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Kegiatan</label>
        <div class="col-sm-10 pt-2">
          <b>: <?= $data->kegiatan; ?></b>
        </div>
      </div>

      <!-- Jadwal -->
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Pilih Waktu Terjadwal</label>
        <div class="col-sm-6">
          <select class="form-control <?= form_error('jadwal') ? 'invalid' : '' ?>" name="jadwal">
            <option disabled>berdasarkan RH, plan produksi atau counter?</option>
            <option value="0" <?= ($data->jadwal == 0) ? 'selected' : ''; ?>>RH Harian</option>
            <option value="1" <?= ($data->jadwal == 1) ? 'selected' : ''; ?>>Plan Produksi</option>
          </select>
          <div class="invalid-feedback <?= form_error('jadwal') ? 'd-block' : ''; ?>">
            <?= form_error('jadwal') ?>
          </div>
        </div>
      </div>

      <!-- Target -->
      <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">Jadwal</label>
        <div class="col-sm-6">
          <input type="text"
          name="target"
          class="form-control"
          value="<?= $data->target; ?>">
        </div>
      </div>

      <!-- Pelaksana -->
      <div class="form-group row">
        <label class="col-sm-2 col-form-label font-weight-bold">
          Pelaksana <span class="text-danger">*</span>
        </label>
        <div class="col-sm-6">
          <input type="text"
          name="pelaksana"
          class="form-control"
          placeholder="Dikerjakan oleh?"
          value="<?= $data->pelaksana; ?>">
        </div>
      </div>

      <!-- Dokumentasi -->
      <div class="form-group row">
        <label class="col-sm-2 col-form-label">Dokumentasi Hasil Pelaksanaan</label>
        <div class="col-sm-6">
          <input type="file"
          name="dokumentasi_after"
          id="dok_af"
          class="form-control <?= form_error('dokumentasi_after') ? 'invalid' : '' ?>">
          <div class="invalid-feedback <?= form_error('dokumentasi_after') ? 'd-block' : ''; ?>">
            <?= form_error('dokumentasi_after') ?>
          </div>
        </div>
      </div>

      <!-- Button -->
      <div class="form-group row mt-4">
        <div class="col-sm-8">
          <button type="submit" class="btn btn-success mr-2">
            <i class="fa fa-save"></i> Simpan
          </button>
          <a href="<?= base_url($this->uri->segment(2)=='tpm' ? 'gmp/tpm' : 'gmp') ?>"
           class="btn btn-danger">
           <i class="fa fa-times"></i> Batal
         </a>
       </div>
     </div>

   </form>
 </div>
</div>
</div>

<script>
  $(document).ready(function() {
    $('select[name="mesin"]').change(function() {
      var val = $(this).val();
      $.get('<?= base_url('mesin/get_mesin_name/');?>' + val,function(res) {
        var data = JSON.parse(res);
        $('input[name="mesin_name"]').val(data.nama_mesin);
      })
    })
  })
</script>