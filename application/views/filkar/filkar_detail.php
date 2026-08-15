<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Detail Filling Karantina</h1>
    </div>
    <div class="card shadow mb-4">
    <div class="row">
        <!-- ===========================
             INFORMASI FILKAR
        ============================ -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    Informasi Filling Karantina
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="40%">Kode Batch</th>
                            <td><?= $data->kode_batch ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td><?= tanggal_indo($data->created_at) ?></td>
                        </tr>
                        <tr>
                            <th>Varian</th>
                            <td><?= $data->varian ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Box</th>
                            <td><?= $data->jumlah_box ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Kg</th>
                            <td><?= number_format($data->jumlah_kg, 2) ?></td>
                        </tr>
                        <tr>
                            <th>Jam Mulai</th>
                            <td><?= date('H:i',strtotime($data->jam_mulai)) ?></td>
                        </tr>
                        <tr>
                            <th>Jam Selesai</th>
                            <td><?= date('H:i',strtotime($data->jam_selesai)) ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Main Power</th>
                            <td><?= $data->jml_mp ?></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?= $data->keterangan ?: '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- ===========================
             BAD PRODUK
        ============================ -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-info text-white">
                            <tr>
                                <th width="50">No</th>
                                <th>Bad Produk</th>
                                <th width="120">Kategori</th>
                                <th width="120">Berat (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total = 0;
                            foreach ($badpro as $bp):
                                $total += $bp->berat;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $bp->nama_badpro ?></td>
                                    <td><?= $bp->kategori_nama ?></td>
                                    <td class="text-right">
                                        <?= number_format($bp->berat, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($badpro)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada bad produk.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">
                                    Total Bad Produk
                                </th>
                                <th class="text-right">
                                    <?= number_format($total, 2) ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row ml-3">
            <div class="col-lg-12">
                <a href="<?= base_url('filkar') ?>" class="btn btn-danger shadow-sm mb-4">
                    <i class="fa fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-approve', function(event) {
            event.preventDefault();
            var varian = $(this).data('varian');
            var tanggal_kode = $(this).data('tanggal_kode');
            var role = $(this).data('role'); // Ambil role dari tombol
            var $button = $(this);
            // Kirim request approval via AJAX
            $.post('<?= base_url('filkar/approval'); ?>/' + varian + '/' + tanggal_kode + '/' + role,
                function(res) {
                    var response = JSON.parse(res);
                    if (response.status) {
                        // Ganti tombol dengan nama fullname
                        $button.closest('td').html(response.fullname);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }).fail(function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>