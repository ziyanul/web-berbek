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
			<form action="<?= base_url('sortasi/edit/' . $data->uuid) ?>" method="post">
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
									<select name="tbatch_uuid" id="tbatch_uuid" class="form-control" required>
										<option value="">
											Pilih Batch
										</option>
										<?php foreach ($batch as $b) : ?>
											<option value="<?= $b->uuid ?>" <?= ($b->uuid == $data->tbatch_uuid)
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
									<input type="number" name="jumlah_sortir" id="jumlah_sortir" class="form-control" min="0" value="<?= $data->jumlah_wip ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Release Box
										<span class="text-danger">*</span>
									</label>
									<input type="number" name="release_box" id="release_box" class="form-control" min="0" value="<?= $data->jml_release ?>">
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
							<textarea name="keterangan" class="form-control" rows="3"><?= $data->keterangan ?></textarea>
						</div>
					</div>
				</div>
				<!-- =========================================================
     BAD PRODUK
========================================================== -->

				<div class="card border-left-danger mb-4">

					<div class="card-header bg-light">

						<div class="d-flex justify-content-between align-items-center">

							<b>
								<i class="fas fa-exclamation-triangle mr-2"></i>
								Bad Produk
							</b>

							<button type="button" id="btnTambahBadProduk" class="btn btn-primary btn-sm">

								<i class="fa fa-plus"></i>
								Tambah Bad Produk

							</button>

						</div>

					</div>


					<div class="card-body">

						<div id="badProdukContainer">

							<?php if (empty($badpro_input)) : ?>

								<div class="text-center text-muted">

									Belum ada bad produk

								</div>

							<?php else : ?>

								<?php foreach ($badpro_input as $index => $bp) : ?>

									<div class="card border-left-secondary mb-3 bad-card" data-index="<?= $index ?>">

										<div class="card-header bg-light">

											<div class="d-flex justify-content-between">

												<b>

													<i class="fas fa-box-open mr-2"></i>

													Bad Produk

												</b>

												<button type="button" class="btn btn-danger btn-sm btnHapusBad">

													<i class="fa fa-trash"></i>

												</button>

											</div>

										</div>


										<div class="card-body">

											<div class="row">

												<!-- BAD PRODUK -->

												<div class="col-md-4">

													<div class="form-group">

														<label>
															Bad Produk
														</label>

														<select name="badpro_uuid[]" class="form-control badproSelect" required>

															<option value="">
																Pilih Bad Produk
															</option>

															<?php foreach ($badpro as $b) : ?>

																<option value="<?= $b->uuid_badpro ?>" data-kategori="<?= $b->kategori_nama ?>" <?= ($b->uuid_badpro
																																					==
																																					$bp->badpro_uuid
																																				)
																																					? 'selected'
																																					: ''
																																				?>>

																	<?= $b->nama_badpro ?>

																</option>

															<?php endforeach; ?>

														</select>

													</div>

												</div>


												<!-- BERAT -->

												<div class="col-md-4">

													<div class="form-group">

														<label>
															Berat Bad Produk (Kg)
														</label>

														<input type="number" step="0.01" min="0.01" name="badpro_berat[]" class="form-control jumlahBad" value="<?= $bp->berat ?>" required>

													</div>

												</div>


												<!-- MESIN DOMINAN -->

												<div class="col-md-4">

													<div class="form-group">

														<label>
															Mesin Dominan
														</label>

														<select name="mesin_uuid[<?= $index ?>][]" class="form-control mesinDominan" multiple>

															<?php foreach ($mesin as $m) : ?>

																<?php

																$selected = false;

																if (!empty($bp->mesin)) {

																	foreach ($bp->mesin
																		as $selectedMesin) {

																		if (
																			$selectedMesin->uuid
																			==
																			$m->uuid
																		) {

																			$selected = true;

																			break;
																		}
																	}
																}

																?>

																<option value="<?= $m->uuid ?>" <?= $selected
																									? 'selected'
																									: ''
																								?>>

																	<?= $m->nama_mesin ?>

																</option>

															<?php endforeach; ?>

														</select>

														<small class="text-muted">

															Pilih satu atau lebih mesin dominan.

														</small>

													</div>

												</div>

											</div>

										</div>

									</div>

								<?php endforeach; ?>

							<?php endif; ?>

						</div>

					</div>

				</div>
				<!-- =========================================================
     SUMMARY
========================================================== -->

				<div class="alert alert-danger">

					<div class="row text-center">

						<div class="col-md-4">

							<h6>
								Total Bad Produk
							</h6>

							<h4 id="totalBarisBad">
								0
							</h4>

						</div>


						<div class="col-md-4">

							<h6>
								Total Mesin Dominan
							</h6>

							<h4 id="totalMesin">
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
				<button type="submit" class="btn btn-success">
					<i class="fa fa-save"></i>
					Simpan
				</button>
				<a href="<?= base_url('sortasi') ?>" class="btn btn-danger">
					<i class="fa fa-times"></i>
					Batal
				</a>
			</form>
		</div>
	</div>
</div>


<script>
	let daftarMesin = [];

	let indexBadProduk =
		<?= !empty($badpro_input)
			? count($badpro_input)
			: 0
		?>;


	$(document).ready(function() {


		/*
		 * =====================================================
		 * SELECT2
		 * =====================================================
		 */

		$('.mesinDominan').select2({
			width: '100%',
			placeholder: 'Pilih Mesin Dominan',
			allowClear: true
		});


		/*
		 * =====================================================
		 * BATCH
		 * =====================================================
		 */

		$('#tbatch_uuid').change(function() {

			let uuid = $(this).val();

			if (uuid === '') {

				daftarMesin = [];

				$('.mesinDominan').empty();

				return;
			}

			loadBatchInfo(uuid);

			loadMesin(uuid);

		});


		/*
		 * =====================================================
		 * LOAD BATCH INFO
		 * =====================================================
		 */

		function loadBatchInfo(uuid) {

			$.ajax({

				url: "<?= base_url('sortasi/get_batch_info/') ?>" +
					uuid,

				type: 'GET',

				dataType: 'json',

				success: function(data) {

					if (!data) {
						return;
					}

					$('#filkarBox')
						.text(data.filkar_box);

					$('#sortasiBox')
						.text(data.sortasi_box);

					$('#sisaBox')
						.text(data.sisa_sortasi);

					$('#boxKg')
						.text(data.box_kg);

					hitungMaksimalBadProduk();

				}

			});

		}


		/*
		 * =====================================================
		 * LOAD MESIN BATCH
		 * =====================================================
		 */

		function loadMesin(uuid) {

			$.ajax({

				url: "<?= base_url('sortasi/get_mesin_batch/') ?>" +
					uuid,

				type: 'GET',

				dataType: 'json',

				success: function(data) {

					daftarMesin = data || [];

					refreshMesinOptions();

				}

			});

		}


		/*
		 * =====================================================
		 * REFRESH OPTION MESIN
		 * =====================================================
		 */

		function refreshMesinOptions() {

			$('.mesinDominan').each(function() {

				let select = $(this);

				let selectedValues =
					select.val() || [];


				/*
				 * Jika batch berubah,
				 * mesin lama yang tidak tersedia
				 * otomatis dibuang.
				 */

				selectedValues =
					selectedValues.filter(function(uuid) {

						return daftarMesin.some(
							function(m) {

								return m.uuid === uuid;

							}
						);

					});


				select.empty();


				daftarMesin.forEach(function(m) {

					let selected =
						selectedValues.includes(m.uuid) ?
						'selected' :
						'';

					select.append(

						`<option
                        value="${m.uuid}"
                        ${selected}>
                        ${m.nama_mesin}
                    </option>`

					);

				});


				select
					.val(selectedValues)
					.trigger('change');

			});

		}


		/*
		 * =====================================================
		 * TAMBAH BAD PRODUK
		 * =====================================================
		 */

		$('#btnTambahBadProduk').click(function() {

			$('#badProdukContainer .text-muted')
				.remove();


			let index =
				indexBadProduk++;


			$('#badProdukContainer').append(

				createBadProdukCard(index)

			);


			$('.mesinDominan').last().select2({

				width: '100%',

				placeholder: 'Pilih Mesin Dominan',

				allowClear: true

			});


			hitungTotalBad();

			hitungTotalMesin();

		});


		/*
		 * =====================================================
		 * CREATE BAD PRODUK CARD
		 * =====================================================
		 */

		function createBadProdukCard(index) {

			let optionBadProduk = '';

			<?php foreach ($badpro as $bp) : ?>

				optionBadProduk += `
                <option
                    value="<?= $bp->uuid_badpro ?>"
                    data-kategori="<?= $bp->kategori_nama ?>">

                    <?= htmlspecialchars(
						$bp->nama_badpro,
						ENT_QUOTES,
						'UTF-8'
					) ?>

                </option>
            `;

			<?php endforeach; ?>


			let optionMesin = '';

			daftarMesin.forEach(function(m) {

				optionMesin += `
                <option value="${m.uuid}">
                    ${m.nama_mesin}
                </option>
            `;

			});


			return `

        <div
            class="card border-left-secondary mb-3 bad-card"
            data-index="${index}">

            <div class="card-header bg-light">

                <div class="d-flex justify-content-between">

                    <b>

                        <i class="fas fa-box-open mr-2"></i>

                        Bad Produk

                    </b>


                    <button
                        type="button"
                        class="btn btn-danger btn-sm btnHapusBad">

                        <i class="fa fa-trash"></i>

                    </button>

                </div>

            </div>


            <div class="card-body">

                <div class="row">


                    <!-- BAD PRODUK -->

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Bad Produk
                            </label>

                            <select
                                name="badpro_uuid[]"
                                class="form-control badproSelect"
                                required>

                                <option value="">
                                    Pilih Bad Produk
                                </option>

                                ${optionBadProduk}

                            </select>

                        </div>

                    </div>


                    <!-- BERAT -->

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Berat Bad Produk (Kg)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="badpro_berat[]"
                                class="form-control jumlahBad"
                                required>

                        </div>

                    </div>


                    <!-- MESIN DOMINAN -->

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                Mesin Dominan
                            </label>

                            <select
                                name="mesin_uuid[${index}][]"
                                class="form-control mesinDominan"
                                multiple>

                                ${optionMesin}

                            </select>

                            <small class="text-muted">

                                Pilih satu atau lebih mesin dominan.

                            </small>

                        </div>

                    </div>


                </div>

            </div>

        </div>

        `;

		}


		/*
		 * =====================================================
		 * HAPUS BAD PRODUK
		 * =====================================================
		 */

		$(document).on(
			'click',
			'.btnHapusBad',
			function() {

				let card =
					$(this).closest('.bad-card');


				let select =
					card.find('.mesinDominan');


				if (select.hasClass('select2-hidden-accessible')) {

					select.select2('destroy');

				}


				card.remove();


				if (
					$('.bad-card').length === 0
				) {

					$('#badProdukContainer').html(

						'<div class="text-center text-muted">' +
						'Belum ada bad produk' +
						'</div>'

					);

				}


				hitungTotalBad();

				hitungTotalMesin();

			}
		);


		/*
		 * =====================================================
		 * KATEGORI BAD PRODUK
		 * =====================================================
		 */

		$(document).on(
			'change',
			'.badproSelect',
			function() {

				let kategori =
					$(this)
					.find(':selected')
					.data('kategori');

				/*
				 * Tidak ada input kategori
				 * yang perlu disimpan.
				 *
				 * Ini hanya informasi visual.
				 */

			}
		);


		/*
		 * =====================================================
		 * HITUNG TOTAL BAD
		 * =====================================================
		 */

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
					parseFloat($(this).val()) ||
					0;


				total += nilai;


				if (
					$(this).val() !== ''
				) {

					baris++;

				}

			});


			$('#totalBarisBad')
				.text(baris);


			$('#totalBadKg')
				.text(
					total.toFixed(2)
				);


			let maksimal =
				hitungMaksimalBadProduk();


			if (total > maksimal) {

				$('#totalBadKg')
					.removeClass('text-success')
					.addClass('text-danger');

			} else {

				$('#totalBadKg')
					.removeClass('text-danger')
					.addClass('text-success');

			}

		}


		/*
		 * =====================================================
		 * HITUNG TOTAL MESIN DOMINAN
		 * =====================================================
		 *
		 * Yang dihitung adalah jumlah pilihan mesin,
		 * bukan jumlah card bad produk.
		 *
		 * Contoh:
		 *
		 * Pecah 10 Kg → Mesin A, Mesin B
		 * Busuk  5 Kg → Mesin C
		 *
		 * Total Mesin Dominan = 3
		 */

		$(document).on(
			'change',
			'.mesinDominan',
			function() {

				hitungTotalMesin();

			}
		);


		function hitungTotalMesin() {

			let total = 0;


			$('.mesinDominan').each(
				function() {

					let values =
						$(this).val() || [];


					total += values.length;

				}
			);


			$('#totalMesin')
				.text(total);

		}


		/*
		 * =====================================================
		 * MAKSIMAL BAD PRODUK
		 * =====================================================
		 */

		function hitungMaksimalBadProduk() {

			let sortir =
				parseFloat(
					$('#jumlah_sortir').val()
				) || 0;


			let release =
				parseFloat(
					$('#release_box').val()
				) || 0;


			let beratBox =
				parseFloat(
					$('#boxKg').text()
				) || 0;


			let maksimal =
				(sortir - release) *
				beratBox;


			if (maksimal < 0) {

				maksimal = 0;

			}


			$('#maksimalBadProduk')
				.text(
					maksimal.toFixed(2)
				);


			return maksimal;

		}


		/*
		 * =====================================================
		 * JUMLAH SORTIR
		 * =====================================================
		 */

		$('#jumlah_sortir').on(
			'input',
			function() {

				let jumlah =
					parseFloat($(this).val()) ||
					0;


				let sisa =
					parseFloat(
						$('#sisaBox').text()
					) ||
					0;


				/*
				 * Saat edit, sisa batch sudah
				 * mengandung data sortasi ini.
				 *
				 * Jadi kita tidak boleh langsung
				 * menganggap nilai lama sebagai
				 * kelebihan.
				 *
				 * Validasi batas tetap dilakukan
				 * saat submit.
				 */

				hitungMaksimalBadProduk();

			}
		);


		/*
		 * =====================================================
		 * RELEASE
		 * =====================================================
		 */

		$('#release_box').on(
			'input',
			function() {

				let sortir =
					parseFloat(
						$('#jumlah_sortir').val()
					) ||
					0;


				let release =
					parseFloat($(this).val()) ||
					0;


				if (release > sortir) {

					alert(
						'Release tidak boleh melebihi jumlah sortir.'
					);

					$(this).val('');

				}


				hitungMaksimalBadProduk();

			}
		);


		/*
		 * =====================================================
		 * VALIDASI SUBMIT
		 * =====================================================
		 */

		$('form').submit(function(e) {

			let valid = true;


			/*
			 * -------------------------------------------------
			 * BATCH
			 * -------------------------------------------------
			 */

			if (
				$('#tbatch_uuid').val() === ''
			) {

				alert(
					'Batch belum dipilih.'
				);

				e.preventDefault();

				return false;

			}


			/*
			 * -------------------------------------------------
			 * JUMLAH SORTIR
			 * -------------------------------------------------
			 */

			let jumlahSortir =
				parseFloat(
					$('#jumlah_sortir').val()
				) ||
				0;


			if (jumlahSortir <= 0) {

				alert(
					'Jumlah sortir harus lebih dari 0.'
				);

				e.preventDefault();

				return false;

			}


			/*
			 * -------------------------------------------------
			 * RELEASE
			 * -------------------------------------------------
			 */

			let release =
				parseFloat(
					$('#release_box').val()
				) ||
				0;


			if (release > jumlahSortir) {

				alert(
					'Release tidak boleh melebihi jumlah sortir.'
				);

				e.preventDefault();

				return false;

			}


			/*
			 * -------------------------------------------------
			 * BAD PRODUK
			 * -------------------------------------------------
			 */

			if (
				$('.bad-card').length === 0
			) {

				alert(
					'Minimal harus ada satu Bad Produk.'
				);

				e.preventDefault();

				return false;

			}


			$('.bad-card').each(function() {

				let bad =
					$(this)
					.find('.badproSelect')
					.val();


				let berat =
					parseFloat(
						$(this)
						.find('.jumlahBad')
						.val()
					) ||
					0;


				if (!bad) {

					alert(
						'Bad Produk belum dipilih.'
					);

					valid = false;

					return false;

				}


				if (berat <= 0) {

					alert(
						'Berat Bad Produk harus lebih dari 0.'
					);

					valid = false;

					return false;

				}

			});


			if (!valid) {

				e.preventDefault();

				return false;

			}


			/*
			 * -------------------------------------------------
			 * TOTAL BAD
			 * -------------------------------------------------
			 */

			let totalBad =
				parseFloat(
					$('#totalBadKg').text()
				) ||
				0;


			let maksimal =
				hitungMaksimalBadProduk();


			if (totalBad > maksimal) {

				alert(
					'Total Bad Produk melebihi maksimal ' +
					maksimal.toFixed(2) +
					' Kg.'
				);

				e.preventDefault();

				return false;

			}


			return true;

		});


		/*
		 * =====================================================
		 * INIT
		 * =====================================================
		 */

		hitungTotalBad();

		hitungTotalMesin();

		hitungMaksimalBadProduk();

	});
</script>