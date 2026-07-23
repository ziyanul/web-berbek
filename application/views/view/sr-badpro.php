			<div class="container-fluid">
	<div class="card shadow mt-4">
		<div class="card-header">
			<!-- Header dan Tombol Tambah -->
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h5 class="h3" id="modalTambahLabel">Data Reject Sortasi per Bad Produk</h5>
				<a href="<?= base_url('view/tambah_srbadpro'); ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white"></i> Tambah
				</a>
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
							<th class="font-weight-bold">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) :
						?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= $row->tgl; ?></td>
								<td><?= $row->varian_name; ?></td>
								<td>
									<a href="<?= base_url('view/detailsrbp/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block">
										<i class="fas fa-info fa-sm text-white"></i> Detail
									</a>
								</td>
							</tr>
						<?php
							$no++;
						endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


					<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
					<script>
						$(document).ready(function () {
		// --- AJAX saat varian dipilih ---
							$("#varian").change(function () {
								var varian_uuid = $(this).val();
								if (varian_uuid) {
									$.ajax({
										url: "<?= base_url('view/get_plan_data_by_varian') ?>",
										type: "POST",
										data: { varian: varian_uuid },
										dataType: "json",
										success: function (data) {
											$("#t_planning").empty().append('<option disabled selected>Pilih Tanggal</option>');
											$.each(data, function (index, item) {
												$("#t_planning").append(
													$('<option>', {
														value: item.uuid,
														text: item.tanggal_produksi
													})
													);
											});
											$("#t_planning").prop("disabled", false);

										}
									});
								} else {
									$("#t_planning").empty().append('<option disabled selected>Pilih Tanggal</option>').prop("disabled", true);
								}
							});

		// --- Perhitungan Total Reject ---
							function updateResult() {
								const inputIds = ['z2', 'k1', 'c2', 'c3', 'c4', 'z7', 'z6', 'z5', 'z4', 'z3', 'c5', 'c6', 'z1', 'c1'];
								let total = 0;

								inputIds.forEach(function (id) {
									const input = document.getElementById(id);
									if (input && input.value !== '') {
										let val = parseFloat(input.value.replace(',', '.'));
										total += isNaN(val) ? 0 : val;
									}
								});

								$('#result').html('Total Reject Cooking : ' + total.toFixed(2) + ' Pcs');
								$('input[name="berat_tampil"]').val(total.toFixed(2));
							}

		// Jalankan saat halaman load
							updateResult();

		// Event listener saat input performa berubah
							$('input[name="performa[]"]').on('input', updateResult);
						});
					</script>
					<script>
						$(document).ready(function () {
							$('form').on('submit', function (e) {
								var tPlanning = $('#t_planning').val();
								if (!tPlanning) {
									alert('Tanggal Produksi belum dipilih!');
									$('#t_planning').focus();
        e.preventDefault(); // Mencegah submit form
        return false;
    }
});
						});
					</script>

