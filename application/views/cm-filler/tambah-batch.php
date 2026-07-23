<div class="container-fluid">
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-2 text-gray-800">Input Pengecekan Mesin/Batch</h1>
  <!-- <a href="<?= base_url('cekmesin_fillerbatch/dataitem') ?>" class="btn btn-md btn-primary shadow-sm" target="blank"><i class="fas fa-plus fa-sm text-white mr-2"></i>Data Item</a> -->
</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin Filler</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_fillerbatch/detail-'.$data->planprod_uuid) ?>"> Pengecekan Mesin/Batch Filler</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
</nav>
<div class="card shadow mb-4">
  <div class="card-body">
    <form class="user" action="<?= base_url('cekmesin_fillerbatch/ceklist_batch/'.$data->MN_BATCH) ?>" method="post">
      <div class="row">
        <div class="col-sm-6 mb-4">
          <label class="form-label">Nama Area <span class="text-danger">*</span></label>
          <input class="form-control" type="text" name="area_name" value="Filler" readonly>
          <input type="hidden" name="area_uuid" value="<?php 
          foreach ($area as $a) {
            if ($a->uuid === $this->filler) { 
              echo $a->uuid; 
            }
          }
        ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 mb-4">
        <label class="form-label">Mesin <span class="text-danger">*</span></label>
        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 mb-4">
        <label class="form-label">Item: <span class="text-danger">*</span></label>
        <div id="item-list">
          <!-- Daftar kegiatan akan dimuat di sini -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 mt-3">
        <label class="form-label"> <b>NOTE :</b><br>
          ● Jika Kegiatan Ya maka centang (✓).<br>
          ● Jika Kegiatan Tidak maka isi keterangan dan tidak perlu centang.
        </label><br><br>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col">
        <button type="submit" class="btn btn-md btn-success mr-2">
          <i class="fa fa-save"></i> Simpan
        </button>
        <a href="<?= base_url('cekmesin_fillerbatch/detail-'.$data->planprod_uuid) ?>" class="btn btn-md btn-danger">
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
    var area_uuid = $('input[name="area_uuid"]').val();

    $.get('<?= base_url('cekmesin_fillerbatch/get_mesin_by_area/'); ?>' + area_uuid + '/<?= $MN_BATCH; ?>', function(res) {
      var result = JSON.parse(res);
      var elem = '<option disabled selected>Pilih Mesin</option>';

      result.forEach(function(val) {
        if (parseInt(val.is_used) > 0) { // Jika mesin sudah digunakan di batch ini
          elem += '<option value="' + val.uuid + '" disabled>' + val.nama_mesin +
          ' ⚠️ (Sudah di Input pada Batch Ini)</option>';
        } else { // Jika mesin belum digunakan
          elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
        }
      });

      $('select[name="mesin"]').html(elem);
    });

    $('select[name="mesin"]').change(function() {
      var mesin_uuid = $(this).val();
      $.get('<?= base_url('cekmesin_fillerbatch/get_item_by_mesin/'); ?>' + mesin_uuid, function(res) {
        var data = JSON.parse(res);
        var elem = '';
        data.forEach(function(val) {
          elem += '<div class="form-check">';
          elem +=
          '<input class="form-check-input item-checkbox" type="checkbox" name="item[' +
          val.uuid + ']" value="2" id="item_' + val.uuid + '">';
          elem += '<label class="form-check-label" for="item_' + val.uuid +
          '">' + val.item + '</label>';
          elem += '<div class="keterangan-container">';
          elem +=
          '<input type="text" class="form-control mt-2" name="keterangan[' +
          val.uuid + ']" placeholder="Keterangan">';
          elem += '</div>';
          elem += '</div>';
        });
        $('#item-list').html(elem);
      });
    });
    $('#item-list').on('change', '.item-checkbox', function() {
      var isChecked = $(this).is(':checked');
      if (isChecked) {
        $(this).closest('.form-check').find('.keterangan-container').hide();
      } else {
        $(this).closest('.form-check').find('.keterangan-container').show();
      }
    });

$('form').on('submit', function(e) {
    var isValid = true;

    $('#item-list .form-check').each(function() {
        var isChecked = $(this).find('.item-checkbox').is(':checked');
        var keteranganInput = $(this).find('.keterangan-container input');

        if (!isChecked && keteranganInput.val().trim() === '') {
            isValid = false;
            keteranganInput.addClass('is-invalid');
        } else {
            keteranganInput.removeClass('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Keterangan wajib di isi untuk item yang tidak dipilih.');
    }
});
  });

</script>

