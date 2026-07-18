
<div class="container-fluid">
	<div class="card shadow mt-5">
		<div class="card-header">
			<div class="d-sm-flex align-items-center justify-content-between">
				<h5 class="h3" id="modalTambahFormulaLabel">Data Formula dan Filkar Produksi</h5>
				<!-- Tombol untuk membuka modal -->
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahFormula">
					<i class="fa fa-plus"></i> Tambah
				</button>
			</div>
			<!-- Modal -->
			<div class="modal fade" data-backdrop="static" id="tambahFormula" tabindex="-1" role="dialog" aria-labelledby="modalTambahFormulaLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header bg-info">
							<h5 class="h3 modal-title text-light" id="modalTambahFormulaLabel">Tambah Data Performa Mesin</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="<?= base_url('view/formula') ?>" method="post">
							<div class="modal-body">
								<div class="container">
									<div class="row">
										<div class="col-md">
											<div class="form-group">
												<label for="varian">Varian</label>
												<select class="form-control" id="varian" name="varian" required>
													<option value="" disabled selected>Pilih Varian</option>
													<option value="1">OKEY</option>
													<option value="2">CHAMP AYAM</option>
													<option value="3">CHAMP SAPI</option>
													<option value="4">CHAMP OTAK-OTAK</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md">
											<div class="form-group">
												<label for="t_planning">Tanggal Produksi</label>
												<select class="form-control" id="t_planning" name="t_planning" required>
													<?php if (!isset($varian) || $varian == null): ?>
														<option disabled selected>Pilih Varian terlebih dulu</option>
													<?php else: ?>
														<option disabled selected>Pilih tanggal</option>
														<!-- Kamu bisa tambahkan opsi tanggal di sini pakai loop -->
													<?php endif; ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-md">
											<label class="form-label">Formula:</label>
											<input type="hidden" name="uuid" id="uuid">
											<input type="number" step="0.001" class="form-control" placeholder="0" name="formula" id="formula">
										</div>		
									</div>
									<div class="row mb-2">
										<div class="col-md">
											<label class="form-label">Filkar :</label>
											<input type="number" step="0.001" class="form-control" placeholder="0" name="filkar" id="filkar">
										</div>
									</div>

								</div>

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-success">Simpan</button>
							</div>
						</form>


					</div>
				</div>
			</div>
		</div>

<?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?= $this->session->flashdata('success_msg') ?>
    </div>
<?php endif ?>

<?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
<?php endif ?>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1" class="font-weight-bold">No</th>
							<th class="font-weight-bold">Tanggal</th>
							<th class="font-weight-bold">Varian</th>
							<th class="font-weight-bold">Formula</th>
							<th class="font-weight-bold">Filkar</th>
						</tr> 
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= $row->tgl; ?></td>
								<td><?= $row->varian_name; ?></td>
								<td><?= $row->formula; ?></td>
								<td><?= $row->filkar; ?></td>
							</td>
						</tr>
						<?php
						$no++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function () {
		$("#varian").change(function () {
            var varian_uuid = $(this).val(); // Ambil nilai varian yang dipilih
            
            if (varian_uuid) {
            	$.ajax({
            		url: "<?= base_url('view/get_plan_data_by_varian') ?>",
            		type: "POST",
            		data: { varian: varian_uuid },
            		dataType: "json",
            		success: function (data) {
                        $("#t_planning").empty().append('<option disabled selected>Pilih Tanggal</option>'); // Reset select
                        $.each(data, function (key, value) {
                        	$("#t_planning").append('<option value="' + value.uuid + '">' + value.tanggal_produksi + '</option>');
                        });
                        $("#t_planning").prop("disabled", false); // Aktifkan select
                      }
                    });
            } else {
            	$("#t_planning").empty().append('<option disabled selected>Pilih Tanggal</option>').prop("disabled", true);
            }
          });

		$("#t_planning").change(function () {
			var planning_uuid = $(this).val();

			if (planning_uuid) {
				$.ajax({
					url: "<?= base_url('view/get_plandata_by_uuid') ?>",
					type: "POST",
					data: { uuid: planning_uuid },
					dataType: "json",
					success: function (data) {
						$("#formula").val(data.formula);
						$("#filkar").val(data.filkar);
                $("#uuid").val(data.uuid); // Set ke input hidden
              },
              error: function () {
              	alert("Gagal mengambil data planning.");
              }
            });
			}
		});


	});
</script>