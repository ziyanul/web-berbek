
<div class="container-fluid">
	<div class="card shadow mt-4">
		<div class="card-header">
			<!-- Tombol untuk membuka modal -->
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h3 class="h3">Tambah Data Reject Sortasi</h3>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahSortasi">
					<i class="fa fa-plus"></i> Tambah
				</button>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="modalTambahSortasi" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<form id="tambahform" action="<?= base_url('view/sortasi') ?>" method="post">
						<div class="modal-content">
							<div class="modal-header bg-info">
								<h5 class="h3 modal-title text-light" id="modalTambahSortasi">Tambah Data Reject Sortasi</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="container">
									<div class="row">
										<!-- Kolom 1 -->
										<div class="col-md">
											<!-- Dropdown Varian -->
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
									<div class="row">
										<div class="col-md">
											<div class="form-group">
												<label class="form-label">Reject :</label>

												<input type="number" step="0.001" class="form-control" placeholder="0" name="persen">
											</div>
										</div>
									</div>
									<div class="row mt-5">
										<div class="col">
											<button type="submit" class="btn btn-primary">Simpan</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
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
							<th class="font-weight-bold">Reject Sortasi</th>
							<th class="font-weight-bold">Action</th>
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
								<td><?= $row->persen; ?></td>
								<td>                           
									<button type="button" class="btn btn-warning btn-block editBtn" data-toggle="modal" data-target="#modalEditRejectSortasi<?= $row->uuid ?>" data-uuid="<?= $row->uuid ?>">
										<i class="fa fa-edit"></i> Edit
									</button>

									<!-- Modal -->
									<div class="modal fade" data-backdrop="static" id="modalEditRejectSortasi<?= $row->uuid ?>" tabindex="-1">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-info">
													<h5 class="h3 modal-title text-light" id="modalEditLabel">Ubah Data Reject Sortasi</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form id="editForm" method="POST" action="<?= base_url('View/edit_sortasi') ?>">
													<input type="hidden" name="uuid" id="edit_uuid">

													<div class="modal-body">
														<div class="mb-3">
															<label>Berat</label>
															<input type="number" name="berat" id="edit_rjsrt" class="form-control" step="0.001">
														</div>
													</div>

													<div class="modal-footer">
														<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
														<button type="submit" class="btn btn-success">Update</button>
													</div>
												</form>

											</div>
										</div>
									</div>
									<a href="#" data-uuid="<?= $row->uuid;?>" class="btn btn-sm btn-danger btn-hapus btn-block mt-2" data-toggle="tooltip" data-placement="top" title="Hapus Data">
										<i class="fa fa-trash mr-2"></i>Hapus
									</a>
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
			$('#tambahform').on('submit', function (e) {
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


<script>
    $('.editBtn').on('click', function () {
        var uuid = $(this).data('uuid');

        $.ajax({
            url: '<?= base_url('View/get_data_sortasi') ?>',
            type: 'POST',
            data: { uuid: uuid },
            dataType: 'json',
            success: function (data) {
            console.log(data); // lihat isi datanya

            if (data) {
                $('#modalEditRejectSortasi' + uuid + ' #edit_uuid').val(data.uuid);
                $('#modalEditRejectSortasi' + uuid + ' #edit_rjsrt').val(data.persen);
            }
        },
        error: function () {
            alert('Gagal mengambil data.');
        }
    });
    });



    $(document).on('click', '.btn-hapus', function (e) {
    e.preventDefault(); // hindari reload jika href="#"

    var data_uuid = $(this).data('uuid'); // lebih aman pakai .data()

    Swal.fire({
        title: 'Apakah Anda yakin ingin hapus data ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#e74a3b'
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('<?= base_url('View/hapus_sortasi/'); ?>' + data_uuid, function (res) {
                var response = JSON.parse(res);
                if (response.status) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data berhasil dihapus.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error!', 'Hapus data gagal.', 'error');
                }
            }).fail(function () {
                Swal.fire('Error!', 'Request gagal.', 'error');
            });
        }
    });
});

</script>


