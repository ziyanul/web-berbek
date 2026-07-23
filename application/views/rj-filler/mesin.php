
<div class="container-fluid">
    <div class="card shadow mt-5">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between">

                <h2 class="h2 mb-2 text-gray-800">Data Reject Filler per Mesin</h2>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahrjmesin">
                    <i class="fa fa-plus"></i> Tambah
                </button>
                <!-- Modal -->
                <div class="modal fade" data-backdrop="static" id="modalTambahrjmesin" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="h3 modal-title text-light" id="modalTambahLabel">Tambah Data Reject Filler Per Mesin</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="<?= base_url('rj_filler/mesin') ?>" method="post">
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
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 1 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c1" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c1">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 2 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c2" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c2">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 3 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c3" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c3">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 4 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c4" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c4">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 5 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c5" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c5">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">CAP 6 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="c6" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="c6">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">KAP 1 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="k1" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="k1">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 1 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z1" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 2 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z2" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z2">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 3 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z3" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z3">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 4 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z4" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z4">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 5 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z5" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z5">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 6 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z6" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z6">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">ZAP 7 :</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control" value="z7" name="mesin[]" hidden>
                                                <input type="number" step="0.001" class="form-control" placeholder="0" name="performa[]" id="z7">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col">
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
                            <th class="font-weight-bold">Tanggal</th>
                            <th class="font-weight-bold">Varian</th>
                            <th class="font-weight-bold">Mesin</th>
                            <!-- <th class="font-weight-bold">Operator</th> -->
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
                                <td><?= $row->tanggal; ?></td>
                                <td><?= $row->varian_name; ?></td>
                                <td><?= $row->nama_mesin; ?></td>
                                <!-- <td><?= $row->fullname; ?></td> -->
                                <td><?= $row->berat; ?></td>
                                <!-- <td><?= $row->keterangan; ?></td> -->
                                <td>
                                    <button type="button" class="btn btn-warning btn-block editBtn" data-toggle="modal" data-target="#modalEditRejectMesin<?= $row->rjfiller_uuid ?>" data-uuid="<?= $row->rjfiller_uuid ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" data-backdrop="static" id="modalEditRejectMesin<?= $row->rjfiller_uuid ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="h3 modal-title text-light" id="modalEditLabel">Ubah Data Reject Filler per Mesin</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="editForm" method="POST" action="<?= base_url('rj_filler/editrjmesin') ?>">
                                                    <input type="hidden" name="uuid" id="edit_uuid">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <select class="form-control" id="mesin" name="mesin" required>
                                                                <option value="" disabled selected>Pilih Mesin</option>
                                                                <?php foreach ($mesin as $m) { ?>
                                                                    <option value="<?= $m->device_id ?>"><?= $m->nama_mesin ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Berat</label>
                                                            <input type="number" name="berat" id="edit_rjmesin" class="form-control" step="0.001">
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
                                    <a href="#" data-uuid="<?= $row->rjfiller_uuid;?>" class="btn btn-sm btn-danger btn-hapus btn-block mt-2" data-toggle="tooltip" data-placement="top" title="Hapus Data">
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

        // --- Perhitungan Total Reject
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
                $('#modalEditRejectMesin' + uuid + ' #edit_uuid').val(data.uuid);
                $('#modalEditRejectMesin' + uuid + ' #edit_rjmesin').val(data.berat);
                $('#modalEditRejectMesin' + uuid + ' #mesin').val(data.mesin_uuid);
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



