<div class="container-fluid">
	<h1 class="h3 mb-3 text-gray-800">
		Edit Sortasi
	</h1>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('sortasi') ?>">
					<i class="fas fa-arrow-left mr-2"></i>
					Sortasi
				</a>
			</li>
			<li class="breadcrumb-item active">
				Edit Data
			</li>
		</ol>
	</nav>
	<div class="card shadow">
		<div class="card-header">
			<b>
				<i class="fas fa-edit mr-2"></i>
				Edit Data Sortasi
			</b>
		</div>
		<div class="card-body">
			<form action="<?= base_url('sortasi/edit/' . $data->uuid) ?>"
				method="post">
				<!-- ==================================
     DATA BATCH
================================== -->
				<div class="card border-left-primary mb-4">
					<div class="card-header bg-light">
						<b>
							<i class="fas fa-layer-group mr-2"></i>
							Data Batch
						</b>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kode Batch
										<span class="text-danger">*</span>
									</label>
									<select
										name="tbatch_uuid"
										id="tbatch_uuid"
										class="form-control"
										required>
										<option value="">
											Pilih Batch
										</option>
										<?php foreach ($batch as $b): ?>
											<option
												value="<?= $b->uuid ?>"
												<?= ($b->uuid == $data->tbatch_uuid)
													? 'selected'
													: ''
												?>>
												<?= $b->kode_batch ?>
												-
												<?= $b->varian ?>
												(<?= $b->keterangan ?>)
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ==================================
     INFO BATCH
================================== -->
				<div class="card border-left-info mb-4">
					<div class="card-header bg-light">
						<b>
							<i class="fas fa-info-circle mr-2"></i>
							Informasi Batch
						</b>
					</div>
					<div class="card-body">
						<div class="row text-center">
							<div class="col-md-3">
								<h6>
									Filkar
								</h6>
								<h4>
									<span id="filkarBox">
										<?= $batch_info->filkar_box ?? 0 ?>
									</span>
									Box
								</h4>
							</div>
							<div class="col-md-3">
								<h6>
									Sudah Sortasi
								</h6>
								<h4>
									<span id="sortasiBox">
										<?= $batch_info->sortasi_box ?? 0 ?>
									</span>
									Box
								</h4>
							</div>
							<div class="col-md-3">
								<h6>
									Sisa Sortasi
								</h6>
								<h4 class="text-danger">
									<span id="sisaBox">
										<?= $batch_info->sisa_sortasi ?? 0 ?>
									</span>
									Box
								</h4>
							</div>
							<div class="col-md-3">
								<h6>
									Berat / Box
								</h6>
								<h4>
									<span id="boxKg">
										<?= $batch_info->box_kg ?? 0 ?>
									</span>
									Kg
								</h4>
							</div>
						</div>
					</div>
				</div>
				<!-- ==================================
     DATA SORTASI
================================== -->
				<div class="card border-left-success mb-4">
					<div class="card-header bg-light">
						<b>
							<i class="fas fa-box mr-2"></i>
							Data Sortasi
						</b>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Jumlah Sortir (Box)
										<span class="text-danger">*</span>
									</label>
									<input
										type="number"
										name="jumlah_sortir"
										id="jumlah_sortir"
										class="form-control"
										min="0"
										value="<?= $data->jumlah_wip ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Release Box
										<span class="text-danger">*</span>
									</label>
									<input
										type="number"
										name="release_box"
										id="release_box"
										class="form-control"
										min="0"
										value="<?= $data->jml_release ?>">
								</div>
							</div>
						</div>
						<div class="alert alert-warning">
							Maksimal Bad Produk :
							<b>
								<span id="maksimalBadProduk">
									0
								</span>
								Kg
							</b>
						</div>
						<div class="form-group">
							<label>
								Keterangan
							</label>
							<textarea
								name="keterangan"
								class="form-control"
								rows="3"><?= $data->keterangan ?></textarea>
						</div>
					</div>
				</div>
				<!-- =========================
                     MESIN
                ========================== -->
				<div class="card border-left-danger mb-4">
					<div class="card-header bg-light">
						<div class="d-flex justify-content-between align-items-center">
							<b>
								<i class="fas fa-industry mr-2"></i>
								Bad Produk Per Mesin
							</b>
							<button
								type="button"
								id="btnTambahMesin"
								class="btn btn-primary btn-sm">
								<i class="fa fa-plus"></i>
								Tambah Mesin
							</button>
						</div>
					</div>
					<div class="card-body">
						<div id="mesinContainer">
							<?php
							$mesin_group = [];
							foreach ($badpro_input as $bp) {
								$mesin_group[$bp->mesin_uuid][] = $bp;
							}
							$indexMesin = 0;
							foreach ($mesin_group as $mesin_uuid => $bad):
							?>
								<div class="card border-left-secondary mb-3 mesin-card" data-index="<?= $indexMesin ?>">
									<div class="card-header bg-light">
										<b>
											<i class="fas fa-industry"></i>
											<?= $bad[0]->nama_mesin ?>
										</b>
										<button
											type="button"
											class="btn btn-danger btn-sm float-right btnHapusMesin">
											<i class="fa fa-trash"></i>
										</button>
									</div>
									<div class="card-body">
										<select
											name="mesin_uuid[]"
											class="form-control mesinSelect">
											<option value="">
												Pilih Mesin
											</option>
											<?php foreach ($mesin as $m): ?>
												<option
													value="<?= $m->uuid ?>"
													<?= ($m->uuid == $mesin_uuid) ? 'selected' : '' ?>>
													<?= $m->nama_mesin ?>
												</option>
											<?php endforeach; ?>
										</select>
										<table class="table table-bordered table-sm mt-3">
											<thead>
												<tr>
													<th>
														Bad Produk
													</th>
													<th>
														Kategori
													</th>
													<th>
														Kg
													</th>
													<th>
														Aksi
													</th>
												</tr>
											</thead>
											<tbody class="badproContainer">
												<?php foreach ($bad as $b): ?>
													<tr>
														<td>
															<select
																name="badpro_uuid[<?= $indexMesin ?>][]"
																class="form-control badproSelect">
																<option value="">
																	Pilih Bad Produk
																</option>
																<?php foreach ($badpro as $bp): ?>
																	<option
																		value="<?= $bp->uuid_badpro ?>"
																		<?= ($bp->uuid_badpro == $b->badpro_uuid) ? 'selected' : '' ?>>
																		<?= $bp->nama_badpro ?>
																	</option>
																<?php endforeach; ?>
															</select>
														</td>
														<td>
															<input
																type="text"
																class="form-control"
																readonly
																value="<?= $b->kategori_nama ?>">
														</td>
														<td>
															<input
																type="number"
																step="0.01"
																name="jumlah_badpro[<?= $indexMesin ?>][]"
																class="form-control jumlahBad"
																value="<?= $b->berat ?>">
														</td>
														<td>
															<button
																type="button"
																class="btn btn-danger btn-sm btnRemoveBad">
																<i class="fa fa-trash"></i>
															</button>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
										<button
											type="button"
											class="btn btn-success btn-sm btnTambahBad"
											data-index="<?= $indexMesin ?>">
											<i class="fa fa-plus"></i>
											Tambah Bad Produk
										</button>
									</div>
								</div>
								<?php
								$indexMesin++;
								?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!-- =========================
                     SUMMARY
                ========================== -->
				<div class="alert alert-danger">
					<div class="row text-center">
						<div class="col-md-4">
							<h6>
								Total Mesin
							</h6>
							<h4 id="totalMesin">
								0
							</h4>
						</div>
						<div class="col-md-4">
							<h6>
								Total Baris Bad Produk
							</h6>
							<h4 id="totalBarisBad">
								0
							</h4>
						</div>
						<div class="col-md-4">
							<h6>
								Total Bad Produk
							</h6>
							<h4>
								<span id="totalBadKg">
									0.00
								</span>
								Kg
							</h4>
						</div>
					</div>
				</div>
				<button type="submit"
					class="btn btn-success">
					<i class="fa fa-save"></i>
					Simpan
				</button>
				<a href="<?= base_url('sortasi') ?>"
					class="btn btn-danger">
					<i class="fa fa-times"></i>
					Batal
				</a>
			</form>
		</div>
	</div>
</div>
<script>
	let daftarMesin = [];
	let indexMesin = <?= $indexMesin ?>;
	$(document).ready(function() {
		// =====================================
		// LOAD MESIN AWAL SESUAI BATCH
		// =====================================
		let batchAwal = $('#tbatch_uuid').val();
		if (batchAwal != '') {
			loadMesin(batchAwal);
		}
		$('#tbatch_uuid').change(function() {
			let uuid = $(this).val();
			loadMesin(uuid);
		});
		function loadMesin(uuid) {
			$.ajax({
				url: "<?= base_url('sortasi/get_mesin_batch/') ?>" + uuid,
				type: "GET",
				dataType: "json",
				success: function(data) {
					daftarMesin = data;
					updateButtonMesin();
				}
			});
		}
		// =====================================
		// TAMBAH MESIN
		// =====================================
		$('#btnTambahMesin').click(function() {
			$('#mesinContainer .text-muted').remove();
			$('#mesinContainer')
				.append(
					createMesinCard()
				);
			updateButtonMesin();
			hitungTotalMesin();
		});
		function createMesinCard() {
			let index = indexMesin;
			let html = `
<div class="card border-left-secondary mb-3 mesin-card"
data-index="${index}">
<div class="card-header bg-light">
<b>
<i class="fas fa-industry"></i>
 Mesin Baru
</b>
<button
type="button"
class="btn btn-danger btn-sm float-right btnHapusMesin">
<i class="fa fa-trash"></i>
</button>
</div>
<div class="card-body">
<select
name="mesin_uuid[]"
class="form-control mesinSelect"
required>
<option value="">
Pilih Mesin
</option>
${generateOptionMesin()}
</select>
<table class="table table-bordered table-sm mt-3">
<thead>
<tr>
<th>
Bad Produk
</th>
<th>
Kategori
</th>
<th>
Kg
</th>
<th>
Aksi
</th>
</tr>
</thead>
<tbody class="badproContainer">
</tbody>
</table>
<button
type="button"
class="btn btn-success btn-sm btnTambahBad"
data-index="${index}">
<i class="fa fa-plus"></i>
Tambah Bad Produk
</button>
</div>
</div>
`;
			indexMesin++;
			return html;
		}
		// =====================================
		// OPTION MESIN
		// =====================================
		function generateOptionMesin() {
			let html = '';
			let terpakai = [];
			$('.mesinSelect').each(function() {
				let val = $(this).val();
				if (val != '') {
					terpakai.push(val);
				}
			});
			daftarMesin.forEach(function(m) {
				if (!terpakai.includes(m.uuid)) {
					html += `
<option value="${m.uuid}">
${m.nama_mesin}
</option>
`;
				}
			});
			return html;
		}
		// =====================================
		// SAAT GANTI MESIN
		// =====================================
		$(document).on(
			'change',
			'.mesinSelect',
			function() {
				refreshDropdownMesin();
				updateButtonMesin();
			}
		);
		function refreshDropdownMesin() {
			let semuaNilai = [];
			$('.mesinSelect').each(function() {
				semuaNilai.push(
					$(this).val()
				);
			});
			$('.mesinSelect').each(function(index) {
				let nilaiLama = semuaNilai[index];
				let html = `
<option value="">
Pilih Mesin
</option>
`;
				daftarMesin.forEach(function(m) {
					let sedangDipakai = false;
					$('.mesinSelect').each(function(i) {
						if (i != index &&
							$(this).val() == m.uuid) {
							sedangDipakai = true;
						}
					});
					if (!sedangDipakai) {
						html += `
<option value="${m.uuid}">
${m.nama_mesin}
</option>
`;
					}
				});
				$(this)
					.html(html)
					.val(nilaiLama);
			});
		}
		// =====================================
		// TAMBAH BAD PRODUK
		// =====================================
		$(document).on(
			'click',
			'.btnTambahBad',
			function() {
				let index =
					$(this).data('index');
				let tbody =
					$(this)
					.closest('.card-body')
					.find('.badproContainer');
				tbody.append(
					createBadProdukRow(index)
				);
			}
		);
		function createBadProdukRow(index) {
			return `
<tr>
<td>
<select
name="badpro_uuid[${index}][]"
class="form-control badproSelect">
<option value="">
Pilih Bad Produk
</option>
<?php foreach ($badpro as $bp): ?>
<option
value="<?= $bp->uuid_badpro ?>"
data-kategori="<?= $bp->kategori_nama ?>"
>
<?= $bp->nama_badpro ?>
</option>
<?php endforeach; ?>
</select>
</td>
<td>
<input
type="text"
class="form-control kategoriBad"
readonly>
</td>
<td>
<input
type="number"
step="0.01"
min="0"
name="jumlah_badpro[${index}][]"
class="form-control jumlahBad">
</td>
<td>
<button
type="button"
class="btn btn-danger btn-sm btnRemoveBad">
<i class="fa fa-trash"></i>
</button>
</td>
</tr>
`;
		}
		// =====================================
		// AUTO KATEGORI BAD PRODUK
		// =====================================
		$(document).on(
			'change',
			'.badproSelect',
			function() {
				let kategori =
					$(this)
					.find(':selected')
					.data('kategori');
				$(this)
					.closest('tr')
					.find('.kategoriBad')
					.val(kategori || '');
			}
		);
		// =====================================
		// HAPUS MESIN
		// =====================================
		$(document).on(
			'click',
			'.btnHapusMesin',
			function() {
				$(this)
					.closest('.mesin-card')
					.remove();
				refreshDropdownMesin();
				updateButtonMesin();
				hitungTotalMesin();
			}
		);
		// =====================================
		// HAPUS BAD PRODUK
		// =====================================
		$(document).on(
			'click',
			'.btnRemoveBad',
			function() {
				$(this)
					.closest('tr')
					.remove();
				hitungTotalBad();
			}
		);
		// =====================================
		// BUTTON TAMBAH MESIN
		// =====================================
		function updateButtonMesin() {
			let jumlahDipakai = 0;
			$('.mesinSelect').each(function() {
				if ($(this).val() != '') {
					jumlahDipakai++;
				}
			});
			if (jumlahDipakai >= daftarMesin.length) {
				$('#btnTambahMesin')
					.prop('disabled', true);
			} else {
				$('#btnTambahMesin')
					.prop('disabled', false);
			}
		}
		// =====================================
		// TOTAL MESIN
		// =====================================
		function hitungTotalMesin() {
			$('#totalMesin')
				.text(
					$('.mesin-card').length
				);
		}
		// =====================================
		// TOTAL BAD PRODUK
		// =====================================
		$(document).on(
			'input',
			'.jumlahBad',
			function() {
				hitungTotalBad();
			}
		);
		function hitungTotalBad() {
			let total = 0;
			let baris = 0;
			$('.jumlahBad').each(function() {
				let nilai =
					parseFloat($(this).val()) || 0;
				total += nilai;
				if ($(this).val() != '') {
					baris++;
				}
			});
			$('#totalBarisBad')
				.text(baris);
			$('#totalBadKg')
				.text(
					total.toFixed(2)
				);
		}
		// =====================================
		// INIT
		// =====================================
		hitungTotalMesin();
		hitungTotalBad();
	});
</script>