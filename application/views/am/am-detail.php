<style>
	.doc_wrapper{width: 200px;}
	.doc_wrapper img{width: 100%;}
</style>
<div class="container-fluid">

	<!-- Page Heading -->
	<h3 class="h3 mb-2 text-gray-800">Detail Kegiatan AM</h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url(
					$this->uri->segment(2) == 'tpm' 
					? 'am/tpm' 
					: ($this->uri->segment(2) == 'history' ? 'am/history' : 'am')
					) ?>">
					<i class="fas fa-arrow-left mr-2"></i>
					<?= 
					$this->uri->segment(2) == 'tpm' 
					? 'Planning AM' 
					: ($this->uri->segment(2) == 'history' ? 'History AM' : 'Task AM') 
					?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="row">
				<div class="col-md-5">
					<table class="table">
						<tbody>
							<tr>
								<td width="200" class="border-top-0">Area</td>
								<td width="10" class="border-top-0">:</td>
								<td class="font-weight-bold border-top-0"><?= $data->nama_area; ?></td>
							</tr>
							<tr>
								<td width="200">Mesin</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->nama_mesin; ?></td>
							</tr>
							<tr>
								<td width="200">Kegiatan</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->kegiatan; ?></td>
							</tr>
							<tr>
								<td width="200">Metode Jadwal</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->jadwal == 0 ? 'RH Harian' : ($data->jadwal == 1 ? 'Plan Produksi' : 'Counter Filler'); ?></td>
							</tr>
							<tr>
								<td width="200">Jadwal</td>
								<td width="10">:</td>
								<td class="font-weight-bold">setiap <?= $data->target; ?> Jam Sekali</td>
							</tr>
							<tr>
								<td width="200">Pelaksana</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->pelaksana; ?></td>
							</tr>
							<tr>
								<td width="200" class="border-bottom">Catatan</td>
								<td width="10" class="border-bottom">:</td>
								<td class="font-weight-bold border-bottom"><?= $data->catatan; ?></td>
							</tr>
							<tr>
								<td width="200" class="border-bottom">Dokumentasi</td>
								<td width="10" class="border-bottom">:</td>
								<td class="border-bottom"><div class="doc_wrapper"><?= !empty($data->dokumentasi_acc) ? '<img src="' . base_url('upload/'.$data->dokumentasi_acc) . '">' : 'Belum Dokumentasi'; ?></div></td>
							</tr>




						<!-- <tr>
							<td>Tindakan</td>
							<td>:</td>
							<td></td>
						</tr>    -->
						<!-- <tr>
							<td>Catatan</td>
							<td>:</td>
							<td><?= $catat->catatan; ?></td>
						</tr>  -->
						
					</tbody>
				</table>

				<a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'am/tpm' : ($this->uri->segment(2) == 'history' ? 'am/history' : 'am')) ?>" class="btn btn-md btn-danger">
					<i class="fa fa-arrow-left"></i> Kembali
				</a>
			</div>

            </div>

</div>
</div>

        </div>
