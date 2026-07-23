<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h2 class="h2 mb-2 text-gray-800">Data Reject Filler</h2>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahrjoperator">
        <i class="fa fa-plus"></i> Tambah
    </button>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="modalTambahrjoperator" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= site_url('rj_filler/') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="h3 modal-title text-light" id="modalTambahLabel">Tambah Data Reject Filler per Batch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="t_planning">Tanggal Produksi</label>
                                        <select class="form-control" id="t_planning" name="t_planning" required>
                                            <?php if (!isset($varian) || $varian == null): ?>
                                                <option disabled selected>Pilih Varian terlebih dulu</option>
                                            <?php else: ?>
                                                <option disabled selected>Pilih tanggal</option>
                                                <!-- Tambahkan opsi tanggal di sini -->
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="t_planning">Batch</label>
                                        <input type="text" name="batch" placeholder="0" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mesin">Mesin</label>
                                        <select id="mesin" name="mesin_uuid" class="form-control" required>
                                            <option value="" disabled selected>Pilih Mesin</option>
                                            <?php foreach ($mesin as $msn): ?>
                                                <option value="<?= $msn->device_id ?>"><?= $msn->nama_mesin ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bad Produk Pertama -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operator_0">Nama Operator</label>
                                        <select id="operator_0" name="operator_uuid[]" class="form-control" required>
                                            <option value="">Pilih Operator</option>
                                            <?php foreach ($operator as $bp): ?>
                                                <option value="<?= $bp->uuid ?>"><?= $bp->fullname ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jumlah_0">Jumlah</label>
                                        <input type="number" step="0.001" name="berat[]" placeholder="Jumlah" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Tambahan input dinamis -->
                            <div id="input-container" class="mt-3"></div>
                            <button type="button" id="btn-tambah" class="btn btn-secondary mt-3">+ Operator</button>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"> X Batal</span>
                        </button>
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
    <br>
<?php endif ?>
<?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
    <br>
<?php endif ?>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr>
                    <th width="1" class="font-weight-bold">No</th>
                    <th class="font-weight-bold">Kode Batch</th>
                    <th class="font-weight-bold">Varian</th>
                    <th class="font-weight-bold">Mesin</th>
                    <th class="font-weight-bold">Operator</th>
                    <th class="font-weight-bold">Berat Reject</th>
                    <!-- <th class="font-weight-bold">Keterangan</th> -->
                    <th width="2px" class="font-weight-bold">Action</th>
                </tr> 
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row->kode_batch; ?></td>
                        <td><?= $row->varian_name; ?></td>
                        <td><?= $row->nama_mesin; ?></td>
                        <td><?= $row->fullname; ?></td>
                        <td><?= $row->berat; ?></td>
                        <!-- <td><?= $row->keterangan; ?></td> -->
                        <td>
                            <button type="button" class="btn btn-warning btn-block editBtn" data-toggle="modal" data-target="#modalEditRejectOpr<?= $row->uuid ?>" data-uuid="<?= $row->uuid ?>">
                                <i class="fa fa-edit"></i> Edit
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" data-backdrop="static" id="modalEditRejectOpr<?= $row->uuid ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="h3 modal-title text-light" id="modalEditLabel">Ubah Data Reject Filler per Operator</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="editForm" method="POST" action="<?= base_url('rj_filler/editrjopr') ?>">
                                            <input type="hidden" name="uuid" id="edit_uuid">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <select class="form-control" id="mesin" name="mesin" required>
                                                        <option value="" disabled selected>Pilih Mesin</option>
                                                        <?php foreach ($mesin as $msn) { ?>
                                                            <option value="<?= $msn->device_id ?>"><?= $msn->nama_mesin ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <select class="form-control" id="operator" name="operator" required>
                                                        <option value="" disabled selected>Pilih Operator</option>
                                                        <?php foreach ($operator as $opr) { ?>
                                                            <option value="<?= $opr->uuid ?>"><?= $opr->fullname ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Berat</label>
                                                    <input type="number" name="berat" id="edit_rjopr" class="form-control" step="0.001">
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

            $('#result').html('Total Reject Filler : ' + total.toFixed(2) + ' Pcs');
            $('input[name="berat_tampil"]').val(total.toFixed(2));
        }

        // Jalankan saat halaman load
        updateResult();

        // Event listener saat input performa berubah
        $('input[name="performa[]"]').on('input', updateResult);
    });
</script>

<script>
    $('.editBtn').on('click', function () {
        var uuid = $(this).data('uuid');

        $.ajax({
            url: '<?= base_url('Rj_filler/get_data_by_uuid') ?>',
            type: 'POST',
            data: { uuid: uuid },
            dataType: 'json',
            success: function (data) {
            console.log(data); // lihat isi datanya

            if (data) {
                $('#modalEditRejectOpr' + uuid + ' #edit_uuid').val(data.uuid);
                $('#modalEditRejectOpr' + uuid + ' #edit_rjopr').val(data.berat);
                $('#modalEditRejectOpr' + uuid + ' #operator').val(data.operator_uuid);
                $('#modalEditRejectOpr' + uuid + ' #mesin').val(data.mesin_uuid);
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
            $.get('<?= base_url('Rj_filler/hapus_rjmesin/'); ?>' + data_uuid, function (res) {
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


<script>
    $(function() {
        const badprodukOptions = `<?= json_encode($operator) ?>`;

        $('#btn-tambah').click(function() {
            const data = JSON.parse(badprodukOptions);
            let selectHtml = `<option value="">Pilih operator</option>`;

            data.forEach(bp => {
                selectHtml += `<option value="${bp.uuid}">${bp.fullname}</option>`;
            });

    // Tambahkan opsi "+ Tambah Badpro?" setelah loop selesai
            selectHtml += `<option value="tambah-operator">+ Tambah Badpro?</option>`;

            const inputGroup = `
            <div class="row mb-2 input-group-row">
            <div class="col-md-6">
            <select name="operator_uuid[]" class="form-control" required>
            ${selectHtml}
            </select>
            </div>
            <div class="col-md-4">
            <input type="number" step="0.001" name="berat[]" placeholder="Jumlah" class="form-control" required>
            </div>
            <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-block btn-hapus-tambah"> X </button>
            </div>
            </div>
            `;

            $('#input-container').append(inputGroup);
        });


    // Event delegation untuk tombol hapus
        $('#input-container').on('click', '.btn-hapus-tambah', function() {
            // Menghapus elemen input group yang berisi tombol hapus
            $(this).closest('.input-group-row').remove();
        });
    });
</script>
