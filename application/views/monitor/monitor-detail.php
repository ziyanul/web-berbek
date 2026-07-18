<div class="container-fluid">

  <h3 class="h3 mb-3 text-gray-800">
    Detail Data Part "<?= $data->nama_part; ?>"
  </h3>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
     <li class="breadcrumb-item">
      <a href="<?= base_url($this->uri->segment(2) == 'history' ? 'monitor/history' : 'monitor') ?>">
        <i class="fas fa-arrow-left mr-2"></i>
        <?= $this->uri->segment(2) == 'history' ? 'History Pergantian Sparepart' : 'Monitoring Sparepart' ?>
      </a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
  </ol>
</nav>

<div class="card shadow mb-4">
  <div class="card-body">

    <div class="col-md-6">

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Area</div>
        <div class="col-1">:</div>
        <div class="col-7"><?= $data->nama_area; ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Mesin</div>
        <div class="col-1">:</div>
        <div class="col-7"><?= $data->nama_mesin; ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Part</div>
        <div class="col-1">:</div>
        <div class="col-7"><?= $data->nama_part; ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Lifetime Part</div>
        <div class="col-1">:</div>
        <div class="col-7"><?= number_format($data->lifetime); ?></div>
      </div>

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Harga Part</div>
        <div class="col-1">:</div>
        <div class="col-7">
          Rp <?= number_format($data->harga,0,',','.'); ?>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col-4 font-weight-bold">Perhitungan RH</div>
        <div class="col-1">:</div>
        <div class="col-7">
         <?= $data->jadwal_name; ?>
       </div>
     </div>

     <?php
     $satuan = ($data->jadwal == 0) ? ' hari' : ' jam';
     ?>

     <div class="row mb-2">
      <div class="col-4 font-weight-bold">RH sekarang</div>
      <div class="col-1">:</div>
      <div class="col-7">
        <?= ($data->status == 0 || $data->status == 3) 
        ? '-' 
        : ($data->status == 1 
          ? number_format($data->rh_end) . $satuan
          : ($data->final_rh !== null 
            ? number_format($data->final_rh) . $satuan
            : '-'
          )
        ); ?>
      </div>
    </div>

    <div class="row mb-2">
      <div class="col-4 font-weight-bold">Pengaju</div>
      <div class="col-1">:</div>
      <div class="col-7"><?= $data->fullname; ?></div>
    </div>

    <div class="row mb-2">
      <div class="col-4 font-weight-bold">Pelaksana</div>
      <div class="col-1">:</div>
      <div class="col-7"><?= $data->nama_pelaksana; ?></div>
    </div>

    <div class="row mb-2">
      <div class="col-4 font-weight-bold">ACC</div>
      <div class="col-1">:</div>
      <div class="col-7"><?= $data->nama_foreman; ?></div>
    </div>

    <div class="row mb-3">
      <div class="col-4 font-weight-bold">Catatan</div>
      <div class="col-1">:</div>
      <div class="col-7"><?= $data->catatan; ?></div>
    </div>

    <a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'monitor/tpm' : ($this->uri->segment(2) == 'history' ? 'monitor/history' : 'monitor')) ?>" 
      class="btn btn-danger mt-5">
      <i class="fa fa-arrow-left mr-2"></i> Kembali
    </a>

  </div>

</div>
</div>
</div>