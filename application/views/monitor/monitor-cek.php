<div class="container-fluid">

  <h3 class="h3 mb-3 text-gray-800">
    Detail Data Part "<?= $data->nama_part; ?>"
  </h3>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= base_url('monitor/tpm') ?>">
          <i class="fas fa-arrow-left mr-2"></i> Data Part
        </a>
      </li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </nav>
<div class="card shadow mb-4">
<div class="card-header py-3">
<h6 class="m-0 font-weight-bold text-primary">Perbandingan Data Part</h6>
</div>

<div class="card-body">

<div class="table-responsive">
<table class="table table-bordered table-sm">

<thead class="thead-light">
<tr>
<th width="20%">keterangan</th>
<th width="30%">Pengajuan</th>

<?php foreach($data->part_aktif as $p) : ?>
<th><?= $p->nama_mesin ?></th>
<?php endforeach; ?>

</tr>
</thead>

<tbody>

<tr>
<td>Area</td>
<td><?= $data->nama_area ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= $p->nama_area ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Mesin</td>
<td><?= $data->nama_mesin ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= $p->nama_mesin ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Part</td>
<td><?= $data->nama_part ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= $p->nama_part ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Lifetime Part</td>
<td><?= number_format($data->lifetime) ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= number_format($p->lifetime) ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Harga Part</td>
<td>Rp <?= number_format($data->harga) ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td>Rp <?= number_format($p->harga) ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Perhitungan RH</td>
<td><?= $data->jadwal_name ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= $p->jadwal_name ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>RH Sekarang</td>
<td><?= number_format($data->rh_end) ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= number_format($p->rh_end) ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Pengaju</td>
<td><?= $data->fullname ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= $p->fullname ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Pelaksana</td>
<td><?= empty($data->nama_pelaksana) ? '-' : $data->nama_pelaksana ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= empty($p->nama_pelaksana) ? '-' : $p->nama_pelaksana ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>ACC</td>
<td><?= empty($data->nama_foreman) ? '-' : $data->nama_foreman ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= empty($p->nama_foreman) ? '-' : $p->nama_foreman ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Catatan</td>
<td><?= empty($data->catatan) ? '-' : $data->catatan ?></td>
<?php foreach($data->part_aktif as $p) : ?>
<td><?= empty($p->catatan) ? '-' : $p->catatan ?></td>
<?php endforeach; ?>
</tr>

<tr>
<td>Waktu Pasang</td>
<td>-</td>
<?php foreach($data->part_aktif as $p) : ?>
<td>
<?= empty($p->installed_at) ? '-' : date('d M Y', strtotime($p->installed_at)) ?>
</td>
<?php endforeach; ?>
</tr>

<tr>
<td>Kondisi</td>

<td>
-
</td>

<?php foreach($data->part_aktif as $p) : ?>
<td>
<span class="badge badge-<?= $p->badge ?>">
<?= $p->kondisi ?>
</span>
</td>
<?php endforeach; ?>

</tr>

</tbody>
</table>
</div>

<a href="<?= base_url('monitor/tpm') ?>" 
  class="btn btn-danger mt-5">
  <i class="fa fa-arrow-left"></i> Kembali
</a>
</div>
</div>

</div>