<div class="container-fluid">\
	<h3 class="h3 mb-2 text-gray-800">Detail Sortasi Per Bad Produk </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('view/srbadpro') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Sortasi Per Bad Produk</a></li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>
	<div class="card shadow mb-4">
		<div class="card-body">
			<table class="table table-bordered mb-4">
				<tr class="bg-info text-light">
					<th>No</th>
					<th>Bad Produk</th>
					<th>Jumlah</th>
					<th>Action</th>
				</tr>
				<?php
				$no = 1;
				foreach ($data as $value) {
					?>
					<tr>
						<td width="1"><?= $no ;?></td>
						<td><?= $value->badpro ;?></td>
						<td><?= $value->jumlah ;?></td>
						<td>
							<button class="btn btn-md btn-warning btn-edit btn-block"
							data-uuid="<?= $value->uuid; ?>"
							data-badpro="<?= $value->badpro_uuid; ?>"
							data-jumlah="<?= $value->jumlah; ?>"
							data-toggle="modal"
							data-target="#editSBP">
							Edit
						</button>
						<!-- Modal Edit -->
						<div class="modal fade" id="editSBP" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<form action="<?= base_url('view/edit_detailsb'); ?>" method="post">
									<div class="modal-content">
										<div class="modal-header bg-warning">
											<h5 class="modal-title" id="editModalLabel">Edit Bad Produk</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<input type="hidden" name="uuid" id="editUuid">
											<div class="mb-3">
												<label for="editBadpro" class="form-label">Bad Produk</label>
												<select id="editBadpro" name="badpro" class="form-control" required>
													<option value="">Pilih Bad Produk</option>
													<?php foreach ($badpro as $bp): ?>
														<option value="<?= $bp->uuid ?>"><?= $bp->badpro ?></option>
													<?php endforeach; ?>
													<option value="tambah-badpro">+ Tambah Badpro?</option>
												</select>
											</div>
											<div class="mb-3">
												<label for="editJumlah" class="form-label">Jumlah</label>
												<input type="number" class="form-control" id="editJumlah" name="jumlah" step="0.001" required>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<a href="#" data-uuid="<?= $value->uuid;?>" class="btn btn-sm btn-danger btn-hapus btn-block mt-2" data-toggle="tooltip" data-placement="top" title="Hapus Data">
							<i class="fa fa-trash mr-2"></i>Hapus
						</a>
					</td>
				</tr>
				<?php
				$no ++;
			} ?>
		</table>

		<a href="<?= base_url('view/srbadpro') ?>" class="btn btn-md btn-primary mt-3">
			<i class="fa fa-arrow-left"></i> Kembali
		</a>
	</div>

</div>
</div>

<!-- Modal Tambah Bad Produk -->
<div class="modal fade" id="modalTambahbp" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form id="form-tambah-badpro" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tambah Bad Produk</h5>
				</div>
				<div class="modal-body">
					<input type="text" name="badpro" placeholder="Nama Bad Produk" class="form-control" required>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).on('click', '.btn-edit', function () {
		let uuid = $(this).data('uuid');
  let badpro = $(this).data('badpro'); // sekarang isinya UUID, bukan nama
  let jumlah = $(this).data('jumlah');

  $('#editUuid').val(uuid);
  $('#editBadpro').val(badpro); // pasti cocok dengan value di option
  $('#editJumlah').val(jumlah);
});

</script>

<script>
	$(document).on('change', 'select[name="badpro"]', function () {
		if ($(this).val() === 'tambah-badpro') {
			$(this).val('');
			$('#modalTambahbp').modal('show');
		}
	});

	$('#form-tambah-badpro').on('submit', function(e) {
    e.preventDefault(); // Cegah form submit biasa

    $.ajax({
    	url: "<?= base_url('View/badpro_tambah') ?>",
    	type: "POST",
    	data: $(this).serialize(),
    	dataType: "json",
    	success: function(response) {
    		if (response.status === 'success') {
                // Tambahkan opsi baru ke semua dropdown
    			$('select[name="badpro"]').each(function() {
                    // Simpan opsi '+ Tambah Badpro?'
    				let tambahOption = $(this).find('option[value="tambah-badpro"]').detach();

                    // Tambah opsi baru
    				$(this).append(`<option value="${response.data.uuid}">${response.data.badpro}</option>`);

                    // Tambahkan kembali opsi '+ Tambah Badpro?'
    				$(this).append(tambahOption);
    			});

                // Reset form dan tutup modal
    			$('#form-tambah-badpro')[0].reset();
    			$('#modalTambahbp').modal('hide');
    		} else {
    			alert("Gagal menambahkan data.");
    		}
    	},
    	error: function() {
    		alert("Terjadi kesalahan.");
    	}
    });
});


</script>

<script>
$(document).ready(function() {
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();

        var data_uuid = $(this).data('uuid');

        if (!data_uuid) {
            Swal.fire('Error', 'UUID tidak ditemukan.', 'error');
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#e74a3b'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('view/hapus_srbadpro/'); ?>' + data_uuid, function(res) {
                    try {
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
                            Swal.fire('Gagal', 'Hapus data gagal.', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Respon server tidak valid.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                });
            }
        });
    });
});
</script>