<div class="container-fluid">
	<div class="card shadow mt-4">
		<div class="card-header">
			<div class="d-sm-flex align-items-center justify-content-between">
				<h5 class="h3" id="modalTambahLabel">Tambah Data Reject Cooking</h5>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahrjc">
					<i class="fa fa-plus"></i> Tambah
				</button>
			</div>

			<!-- Modal Tambah -->
			<div class="modal fade" data-backdrop="static" id="modalTambahrjc" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header bg-info">
							<h5 class="h3 modal-title text-light" id="modalTambahLabel">Tambah Data</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form id="formTambah" action="<?= base_url('view/rj_cooking') ?>" method="post">

							<div class="modal-body">
								<div class="row mb-2">
									<div class="col-sm-12">
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
								</div>

								<div class="row mb-2">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="t_planning">Tanggal Produksi</label>
											<select class="form-control" id="t_planning" name="t_planning" required>
												<option disabled selected>Pilih Tanggal</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row mb-2">
									<div class="col-sm-12">
										<label class="form-label">Berat Reject :<span class="text-danger">*</span></label>
										<input type="number" step="0.001" name="berat" class="form-control <?= form_error('berat') ? 'invalid' : '' ?>" placeholder="berat (kg)" value="<?= set_value('berat'); ?>">
										<div class="invalid-feedback <?= !empty(form_error('berat')) ? 'd-block' : ''; ?>">
											<?= form_error('berat') ?>
										</div>
									</div>
								</div>

								<div class="row mt-3">
									<div class="col">
										<button type="submit" class="btn btn-success mr-2">
											<i class="fa fa-save"></i> Simpan
										</button>
										<button type="button" class="btn btn-danger" data-dismiss="modal">
											<i class="fa fa-times"></i> Batal
										</button>
									</div>
								</div>
							</div> <!-- modal-body -->
						</form>
					</div> <!-- modal-content -->
				</div> <!-- modal-dialog -->
			</div> <!-- modal -->
		</div> <!-- card-header -->
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
							<th width="1">No</th>
							<th>Tanggal</th>
							<th>Varian</th>
							<th>Berat Reject</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; foreach ($data as $row): ?>
						<tr>
							<td><?= $no++; ?></td>
							<td><?= $row->tgl; ?></td>
							<td><?= $row->varian_name; ?></td>
							<td><?= $row->berat; ?></td>
							<td>
								<!-- Tombol Edit -->
								<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalEditRejectCooking<?= $row->uuid ?>">
									<i class="fa fa-edit"></i> Edit
								</button>

								<!-- Modal Edit -->
								<div class="modal fade" data-backdrop="static" id="modalEditRejectCooking<?= $row->uuid ?>" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header bg-info">
												<h5 class="h3 modal-title text-light">Ubah Data Reject</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form method="POST" action="<?= base_url('view/editrjcooking') ?>">
												<input type="hidden" name="uuid" value="<?= $row->uuid ?>">

												<div class="modal-body">
													<div class="mb-3">
														<label>Berat</label>
														<input type="number" name="berat" class="form-control" step="0.001" value="<?= $row->berat ?>">
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

								<!-- Tombol Hapus -->
								<a href="#" data-uuid="<?= $row->uuid; ?>" class="btn btn-sm btn-danger btn-hapus btn-block mt-2" data-toggle="tooltip" title="Hapus Data">
									<i class="fa fa-trash mr-2"></i>Hapus
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div> <!-- card-body -->
</div> <!-- card -->
</div> <!-- container-fluid -->

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

        updateResult(); // saat halaman dimuat
        $('input[name="performa[]"]').on('input', updateResult); // saat nilai berubah

        $('#formTambah').on('submit', function (e) {
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
    	url: '<?= base_url('view/get_data_cooking') ?>',
    	type: 'POST',
    	data: { uuid: uuid },
    	dataType: 'json',
    	success: function(data) {
        // Pastikan data tersedia
    		if (data) {
    			console.log(typeof data.performa, data.performa);

          // Set nilai ke input modal
          $('#modalEditRejectCooking' + uuid + ' #edit_uuid').val(data.uuid); // Menyimpan UUID di input tersembunyi untuk modal yang sesuai
          $('#modalEditRejectCooking' + uuid + ' #edit_rjcooking').val(data.berat); // Menampilkan nilai performa untuk modal yang sesuai
          
        }
      },
      error: function() {
      	alert('Gagal mengambil data.');
      }
    });
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
    		$.get('<?= base_url('view/hapus_rjcooking/'); ?>' + data_uuid, function (res) {
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
