<div class="container-fluid">
    <div class="card shadow mt-4">
        <div class="card-header">
            <!-- Tombol untuk membuka modal -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h5 class="h3" id="modalTambahLabel">Tambah Data Reject Sortasi Per Bad Produk</h5>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('view/srbadpro') ?>">
                            <i class="fas fa-arrow-left mr-2"></i>Data Sortasi
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </nav>
        </div>

        <div class="card-body">
            <form action="<?= site_url('view/tambah_srbadpro') ?>" method="post">
                <div class="container">
                    <div class="row">
                        <!-- Kolom 1: Varian -->
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

                        <!-- Kolom 2: Tanggal Produksi -->
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
                        <!-- Kolom 1: Bad Produk dan Jumlah -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="badpro-select">Bad Produk</label>
                                <select id="badpro-select" name="badpro_uuid[]" class="form-control" required>
                                    <option value="">Pilih Bad Produk</option>
                                    <?php foreach ($badpro as $bp): ?>
                                        <option value="<?= $bp->uuid ?>"><?= $bp->badpro ?></option>
                                    <?php endforeach; ?>
                                    <option value="tambah-badpro">+ Tambah Badpro?</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" step="0.001" name="jumlah[]" placeholder="Jumlah" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div id="input-container" class="mt-3"></div>
                    <button type="button" id="btn-tambah" class="btn btn-secondary mt-3">+ Bad Produk</button>
                    
                    <br><br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a class="btn btn-danger ml-3" href="<?= base_url('view/srbadpro') ?>">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
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


<!-- jQuery (wajib) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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
    });
</script>
<script>
    $(function() {
        const badprodukOptions = `<?= json_encode($badpro) ?>`;

        $('#btn-tambah').click(function() {
            const data = JSON.parse(badprodukOptions);
            let selectHtml = `<option value="">Pilih Bad Produk</option>`;

            data.forEach(bp => {
                selectHtml += `<option value="${bp.uuid}">${bp.badpro}</option>`;
            });

    // Tambahkan opsi "+ Tambah Badpro?" setelah loop selesai
            selectHtml += `<option value="tambah-badpro">+ Tambah Badpro?</option>`;

            const inputGroup = `
            <div class="row mb-2 input-group-row">
            <div class="col-md-6">
            <select name="badpro_uuid[]" class="form-control" required>
            ${selectHtml}
            </select>
            </div>
            <div class="col-md-4">
            <input type="number" step="0.001" name="jumlah[]" placeholder="Jumlah" class="form-control" required>
            </div>
            <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-block btn-hapus">X Batal</button>
            </div>
            </div>
            `;

            $('#input-container').append(inputGroup);
        });


    // Event delegation untuk tombol hapus
        $('#input-container').on('click', '.btn-hapus', function() {
            // Menghapus elemen input group yang berisi tombol hapus
            $(this).closest('.input-group-row').remove();
        });
    });
</script>
<script>
    $(document).on('change', 'select[name="badpro_uuid[]"]', function () {
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
                $('select[name="badpro_uuid[]"]').each(function() {
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