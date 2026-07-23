<div class="container-fluid">
	<div class="card shadow mt-4">
		<div class="card-header">
			<div class="d-sm-flex align-items-center justify-content-between">
				<h5 class="h3">Tambah Data Reject Cooking Per Mesin</h5>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahrj">
					<i class="fa fa-plus"></i> Tambah
				</button>
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
			<!-- Modal -->
			<div class="modal fade" data-backdrop="static" id="modalTambahrj" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header bg-info">
							<h5 class="h3 modal-title text-light" id="modalTambahLabel">Tambah Data Reject Cooking Per Mesin</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form id="tambah_rjmesin" action="<?= base_url('view/rj_mesin') ?>" method="post">
							<div class="modal-body">
								<div class="container">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="varian">Varian</label>
												<select class="form-control" id="varian" name="varian" required>
													<option disabled selected>Pilih Varian</option>
													<option value="1">OKEY</option>
													<option value="2">CHAMP AYAM</option>
													<option value="3">CHAMP SAPI</option>
													<option value="4">CHAMP OTAK-OTAK</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="t_planning">Tanggal Produksi</label>
												<select class="form-control" id="t_planning" name="t_planning" required>
													<option disabled selected>Pilih Tanggal</option>
												</select>
											</div>
										</div>
									</div>

									<?php
									$mesin = [
										['KAP 1', 'k1'], ['ZAP 1', 'z1'],
										['CAP 1', 'c1'], ['ZAP 2', 'z2'],
										['CAP 2', 'c2'], ['ZAP 3', 'z3'],
										['CAP 3', 'c3'], ['ZAP 4', 'z4'],
										['CAP 4', 'c4'], ['ZAP 5', 'z5'],
										['CAP 5', 'c5'], ['ZAP 6', 'z6'],
										['CAP 6', 'c6'], ['ZAP 7', 'z7'],
									];
									for ($i = 0; $i < count($mesin); $i += 2): ?>
										<div class="row mb-2">
											<div class="col-md-3">
												<label class="form-label"><?= $mesin[$i][0] ?> :</label>
											</div>
											<div class="col-3">
												<input type="text" class="form-control" value="<?= $mesin[$i][1] ?>" name="mesin[]" hidden>
												<input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="<?= $mesin[$i][1] ?>">
											</div>
											<div class="col-md-3">
												<label class="form-label"><?= $mesin[$i+1][0] ?> :</label>
											</div>
											<div class="col-3">
												<input type="text" class="form-control" value="<?= $mesin[$i+1][1] ?>" name="mesin[]" hidden>
												<input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="<?= $mesin[$i+1][1] ?>">
											</div>
										</div>
									<?php endfor; ?>

									<div class="row mt-3">
										<div class="col">
											<!-- <div id="result" class="mb-2 font-weight-bold text-primary"></div> -->
											<input type="hidden" name="berat_tampil">
											<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
											<button type="submit" class="btn btn-success">Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="table-responsive mt-4">
				<table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1" class="font-weight-bold">No</th>
							<th class="font-weight-bold">Tanggal</th>
							<th class="font-weight-bold">Mesin</th>
							<th class="font-weight-bold">Berat Reject</th>
							<th class="font-weight-bold">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($data as $row): ?>
						<tr>
							<td><?= $no++; ?></td>
							<td><?= $row->tgl; ?></td>
							<td><?= $row->nama_mesin; ?></td>
							<td><?= $row->berat; ?></td>
							<td>
								<button type="button" class="btn btn-warning btn-block editBtn" data-toggle="modal" data-target="#modalEditPerformaMesin<?= $row->rc_uuid ?>" data-uuid="<?= $row->rc_uuid ?>">
									<i class="fa fa-edit"></i> Edit
								</button>

								<!-- Modal -->
								<div class="modal fade" data-backdrop="static" id="modalEditPerformaMesin<?= $row->rc_uuid ?>" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-info">
												<h5 class="h3 modal-title text-light" id="modalEditLabel">Ubah Data Performa Mesin</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form id="editForm" method="POST" action="<?= base_url('view/editrcmesin') ?>">
												<input type="hidden" name="uuid" id="edit_uuid">

												<div class="modal-body">
												<div class="mb-3">
														<label>Berat</label>
														<input type="number" name="berat" id="edit_rcmesin" class="form-control" step="0.001">
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
								<!-- <a href="#" data-uuid_hapus="<?= $row->uuid;?>" class="btn btn-sm btn-danger btn-hapus btn-block mt-2" data-toggle="tooltip" data-placement="top" title="Hapus Data">
									<i class="fa fa-trash mr-2"></i>Hapus
								</a> -->
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>

<!-- Script AJAX dan Kalkulasi -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function () {
		$('#varian').change(function () {
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
		

		
		$('tambah_rjmesin').on('submit', function (e) {
			if (!$('#t_planning').val()) {
				alert('Tanggal Produksi belum dipilih!');
				$('#t_planning').focus();
				e.preventDefault();
				return false;
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
  // Ketika tombol edit diklik
		$('.editBtn').on('click', function() {
    var uuid = $(this).data('uuid'); // Ambil UUID dari tombol yang diklik

    // Lakukan AJAX untuk mengambil data berdasarkan UUID
    $.ajax({
    	url: '<?= base_url('View/get_cooking_per_mesin') ?>',
    	type: 'POST',
    	data: { uuid: uuid },
    	dataType: 'json',
    	success: function(data) {
        // Pastikan data tersedia
    		if (data) {
    			console.log(typeof data.performa, data.performa);

          // Set nilai ke input modal
          $('#modalEditPerformaMesin' + uuid + ' #edit_uuid').val(data.uuid); // Menyimpan UUID di input tersembunyi untuk modal yang sesuai
          $('#modalEditPerformaMesin' + uuid + ' #edit_rcmesin').val(data.berat); // Menampilkan nilai performa untuk modal yang sesuai
          $('#modalEditPerformaMesin' + uuid + ' #mesin').val(data.mesin_uuid); // Menampilkan mesin yang dipilih untuk modal yang sesuai
        }
      },
      error: function() {
      	alert('Gagal mengambil data.');
      }
    });
  });
	});


	$(document).on('click', '.btn-hapus', function (e) {
   var data_uuid = $(this).data('uuid_hapus')
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
    		$.get('<?= base_url('View/hapus_rcmesin/'); ?>' + data_uuid, function (res) {
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

