<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Penerimaan Bahan Baku Filler</h1>

    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('bahan_filler') ?>"><i class="fas fa-arrow-left"></i>
                    Permintaan Bahan Baku Filler</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('bahan_filler/detail_'. $nav->tanggal) ?>"> Detail
                    Permintaan Bahan Baku Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Penerimaan Bahan Baku</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <h5 class="font-weight-bold">Informasi Penerimaan</h5>
                <table class="table table-success mb-4">
                    <tbody>
                        <?
                    foreach ($data as $row)
                    ?>
                        <tr>
                            <td width="200" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $nav->tgl;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nomor Reservasi</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= sprintf("%04d", ( $nav->no_reservasi)); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Item Barang</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->item_barang;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jumlah Reservasi</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold" style="color: #36b9cc;"><?= $nav->qty_reservasi. ' ' .$nav->satuan;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jumlah Dikirim</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold" style="color: #07e203;">
                                <?= isset($nav->total_kirim) && $nav->total_kirim !== null ? $nav->total_kirim  : '0'?> <?= $nav->satuan; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Sisa Belum Dikirim</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold" style="color: #e74a3b;"><?= $nav->qty_reservasi - $nav->total_kirim. ' ' .$nav->satuan;?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class='align-middle text-center' width='1px'>No.</th>
                            <th class='align-middle text-center' width='50px'>Jumlah Dikirim</th>
                            <th class='align-middle text-center' width='50px'>Kode Produk</th>
                            <th class='align-middle text-center' width='50px'>Exp Date Produk</th>
                            <th class='align-middle' width='80px'>Pengirim</th>
                            <th class='align-middle' width='80px'>ACC QC</th>
                            <th class='align-middle' width='80px'>Penerima</th>
                            <th class='align-middle text-center' width='100px'>Tanggal / Waktu Terima</th>
                            <th class='align-middle text-center' width='50px'>Keterangan QC</th>
                            <th class='align-middle text-center' width='120px'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                        <!-- Tampilkan baris ini jika tidak ada data -->
                        <tr>
                            <td colspan="10" class="font-weight-bold text-center">No data available in table</td>
                        </tr>
                        <?php else: ?>
                        <?php
                            $no = 1;
                            foreach ($data as $row) {
                                $modified = $row->penerima_uuid != NULL ? $row->jam_terima : ''; // hanya untuk ditampilkan
                            ?>
                        <tr>
                            <td class='align-middle text-center'><?= $no; ?></td>
                            <td class='align-middle text-center'><?= $row->qty_dikirim; ?></td>
                            <td class='align-middle text-center'><?= $row->kode_produk; ?></td>
                            <td class='align-middle text-center'><?= $row->exp_date; ?></td>
                            <td class='align-middle'><?= !empty($row->pengirim) ? $row->pengirim : '-'; ?></td>
                            <td class='align-middle'><?= !empty($row->acc_qc) ? $row->acc_qc : '-'; ?></td>
                            <td class='align-middle'><?= !empty($row->penerima) ? $row->penerima : '-'; ?></td>
                            <td class='align-middle text-center'>
                                <?= !empty($modified) ? date('d M Y / H:i', strtotime($modified)) : '-'; ?>
                            <td class='align-middle text-center'>
                                <?= !empty($row->qc_keterangan) ? $row->qc_keterangan : '-'; ?></td>
                            </td>
                            <td class='align-middle text-center'>
                                <?php if ($row->kondisi_kemasan == 1 && $row->kontaminasi == 1 && $row->kondisi_pallet == 1 && $row->penempatan == 1): ?>
                                <!-- Semua nilai kondisi = 1, tampilkan tombol -->
                                <?php if ($row->qc_uuid != NULL && $row->penerima_uuid == NULL): ?>
                                <a href="#" data-uuid="<?= $row->uuid; ?>"
                                    class="btn btn-check-approval btn-info btn-block" data-toggle="tooltip"
                                    data-placement="top" title="Check for Approval">
                                    <i class="fa fa-check-circle fa-lg mr-2" style="color: #07e203;"></i> Diterima
                                </a>
                                <?php elseif ($row->penerima_uuid != NULL): ?>
                                <!-- Sudah diterima -->
                                <i class="fa fa-thumbs-up fa-2x" style="color: #1cc88a;" data-toggle="tooltip"
                                    data-placement="top" title="Sudah Diterima"></i>
                                <?php endif; ?>
                                <?php elseif ($row->kondisi_kemasan == 2 || $row->kontaminasi == 2 || $row->kondisi_pallet == 2 || $row->penempatan == 2): ?>
                                <!-- Salah satu nilai kondisi = 2, QC Tolak -->
                                <span class="badge badge-danger" style="font-size: 16px; padding: 5px 10px;">QC
                                    Tolak</span>
                                <?php else: ?>
                                <!-- Nilai kondisi NULL, Belum ACC QC -->
                                <span class="badge badge-warning" style="font-size: 16px; padding: 5px 10px;">Belum ACC
                                    QC</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            $no++;
                            }
                        ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-check-approval', function(event) {
        event.preventDefault();
        var uuid = $(this).attr('data-uuid');

        $.get('<?= base_url('bahan_baku/diterima/'); ?>' + uuid, function(res) {
            var response = JSON.parse(res);
            if (response.status) {
                location.reload(); // Reload halaman untuk menampilkan perubahan
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Check for approval failed.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>