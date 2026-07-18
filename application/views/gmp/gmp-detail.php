<style>
	.doc_wrapper{width: 200px;}
	.doc_wrapper img{width: 100%;}
</style>
<div class="container-fluid">

	<!-- Page Heading -->
	<h3 class="h3 mb-2 text-gray-800">Detail Data Kegiatan "<?= $data->kegiatan; ?>" </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url($this->uri->segment(2)=='tpm'?'gmp/tpm':'gmp') ?>"><i class="fas fa-arrow-left mr-2"></i> Monitoring ISO/TS</a></li>
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
								<td width="150" class="border-top-0">Area</td>
								<td width="10" class="border-top-0">:</td>
								<td class="font-weight-bold border-top-0"><?= $data->nama_area; ?></td>
							</tr>
							<tr>
								<td width="150">Lokasi</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->lokasi; ?></td>
							</tr>
							<tr>
								<td width="150">Kegiatan</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->kegiatan; ?></td>
							</tr>
							<tr>
								<td width="150">Jadwal</td>
								<td width="10">:</td>
								<td class="font-weight-bold"><?= $data->target; ?> hari sekali</td>
							</tr>
							<tr>
								<td width="150" class="border-bottom">Pelaksana</td>
								<td width="10" class="border-bottom">:</td>
								<td class="font-weight-bold border-bottom"><?= $data->pelaksana; ?></td>
							</tr>
							<tr>
								<td width="150" class="border-bottom">Dokumentasi</td>
								<td width="10" class="border-bottom">:</td>
								<td class="font-weight-bold border-bottom">
									<?php if (!empty($data->dokumentasi_acc)) : ?>
										<div class="doc_wrapper">
											<img src="<?= base_url('upload/'.$data->dokumentasi_acc); ?>">
										</div>
									<?php else : ?>
										<span class="text-muted">Tidak ada dokumentasi</span>
									<?php endif; ?>
								</td>
							</tr>



						<!-- <tr>
							sTindakan</td>
							<td width="10">:</td>
							<td class="font-weight-bold"></td>
						</tr>    -->
						<!-- <tr>
							<td>Catatan</td>
							<td width="10">:</td>
							<td class="font-weight-bold"><?= $catat->catatan; ?></td>
						</tr>  -->
						
					</tbody>
				</table>

				<a href="<?= base_url($this->uri->segment(2) == 'tpm' ? 'gmp/tpm' : ($this->uri->segment(2) == 'history' ? 'gmp/history' : 'gmp')) ?>" class="btn btn-md btn-danger mt-3">
					<i class="fa fa-arrow-left"></i> Kembali
				</a>
			</div>

			<div class="col-md-7">
				<h5 class="font-weight-bold">Proses Perubahan Status "<?= $data->kegiatan; ?>"</h5>
				<hr>
				<table class="table table-bordered table-success">
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>Status</th>
							<th>Catatan</th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($status as $value) {
							?>
							<tr>
								<td><?= $value->created_at;?></td>
								<td><?= $value->status_gmp;?></td>
								<td><?= $value->catatan;?></td>

							</tr>
						<?php } ?>
					</tbody>
				</table>

				<!-- <h5 class="text-gray-800">Foto Dokumentasi</h5>
                <table class="table table-bordered">
                    <tr class="bg-info text-light">
                        <th>Sebelum</th>
                        <th>Sesudah</th>
                    </tr> -->
                    
                        <!-- <tr>
                        	
                            <td><div class="doc_wrapper"><img src="<?= base_url('upload/'.$data->dokumentasi);?>"></div></td>
                            <td><div class="doc_wrapper"> <img src="<?= base_url('upload/'.$data->dokumentasi_acc);?>"></div></td>
                        </tr>
                   
                </table> -->
            </div>
        </div>

    </div>

</div>
</div>
