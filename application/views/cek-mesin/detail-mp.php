<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">Detail dan Aproval Pengecekan Mesin MP</h2>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_mp') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin MP</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Pengecekan Mesin</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <h5 class="font-weight-bold">Informasi Pengecekan Mesin</h5>
                <table class="table table-success mb-4">
                    <tbody>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal Planning Produksi</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $nav->tgl_planning;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->varian;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tanggal Pengecekan</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->tgl;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->user;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Foreman/Lady</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (is_null($nav->fr_uuid)): ?>
                                <a href="#" data-uuid="<?= $nav->uuid; ?>" data-area="<?= $nav->area_uuid; ?>"
                                    class="btn btn-approve-fr btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else: ?>
                                <?= !empty($nav->foreman) ? $nav->foreman : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php if (!is_null($nav->fr_uuid)): ?>
                        <tr>
                            <td class="font-weight-bold">Approval Spv</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (is_null($nav->spv_uuid)): ?>
                                <a href="#" data-uuid="<?= $nav->uuid; ?>" data-area="<?= $nav->area_uuid; ?>"
                                    class="btn btn-approve-spv btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else: ?>
                                <?= !empty($nav->spv) ? $nav->spv : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
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
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th rowspan="2" class="font-weight-bold align-middle" width="150">Item</th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="120">Checklist Awal
                                Produksi</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="150">Keterangan
                            </th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="130"
                                style="border-right: 5px solid #079da5;">Paraf</th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="100">Checklist
                                Akhir Produksi</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="150">Keterangan
                            </th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="130">Paraf</th>
                        </tr>
                        <tr>
                            <th class="font-weight-bold align-middle text-center" width="50">Ya</th>
                            <th class="font-weight-bold align-middle text-center" width="50">Tidak</th>
                            <th class="font-weight-bold align-middle text-center" width="65">Prod</th>
                            <th class="font-weight-bold align-middle text-center" width="65"
                                style="border-right: 5px solid #079da5;">QC</th>
                            <th class="font-weight-bold align-middle text-center" width="50">Ya</th>
                            <th class="font-weight-bold align-middle text-center" width="50">Tidak</th>
                            <th class="font-weight-bold align-middle text-center" width="65">Prod</th>
                            <th class="font-weight-bold align-middle text-center" width="65">QC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $last_mesin = null;
        $mesin_no = 'A'; // Inisialisasi huruf untuk penomoran mesin
        $item_no = 1; // Nomor urut item di dalam mesin

        foreach ($data as $row) {
            if ($last_mesin !== $row->mesin) {
                // Tampilkan heading untuk mesin baru
                if ($last_mesin !== null) {
                    echo '</tbody>'; // Tutup <tbody> sebelumnya jika ada
                }
                echo "<tr><td style='border-right: 5px solid #079da5;' colspan='7'><strong>{$mesin_no}. {$row->mesin}</strong></td> <td colspan='5'></td></tr>";
                $last_mesin = $row->mesin;
                $mesin_no++;
                $item_no = 1; // Reset nomor item untuk mesin baru
            }
            ?>
                        <tr>
                            <td class="align-middle text-center"><?= $item_no ?></td>
                            <td class="align-middle"><?= $row->item ?></td>
                            <td class="align-middle text-center"><?= $row->check_ya ?></td>
                            <td class="align-middle text-center"><?= $row->check_tdk ?></td>
                            <td class="align-middle text-center">

                                <?php if ($row->checklist == 2 && $row->keterangan == NULL): ?>
                                <?= $row->keterangan ?: '-'; ?>
                                <?php else : ?>
                                <?= $row->keterangan ?: ''; ?>
                                <?php endif; ?></td>
                            <td class="align-middle text-center"><?= $row->fullname ?></td>
                            <td class="align-middle text-center" style="border-right: 5px solid #079da5;">
                                <?= $row->paraf_qc ?></td>
                                <td class="align-middle text-center">
                                <?php if ($row->checklist2 == 0): ?>
                                <a href="#" data-uuid="<?= $row->cek_uuid; ?>"
                                    class="btn btn-check-oke btn-block btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Check for oke" data-checklist="2">
                                    <i class="fa fa-check-circle fa-md" style="color: #ffffff;"></i>
                                </a>
                                <?php else: ?>
                                <?= $row->akhir_ya; ?>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center"><?php if ($row->checklist2 == 0): ?>
                                <a href="#" data-uuid="<?= $row->cek_uuid; ?>"
                                    class="btn btn-check-oke btn-block btn-danger shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Check for oke" data-checklist="1">
                                    <i class="fa fa-times-circle fa-md" style="color: #ffffff;"></i>
                                </a>
                                <?php else: ?>
                                <?= $row->akhir_tdk; ?>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <?php if ($row->checklist2 == 1 && $row->keterangan2 == NULL): ?>
                                <div class="row">
                                    <div class="col">
                                        <form class="form-keterangan" data-uuid="<?= $row->cek_uuid; ?>">
                                            <input type="text" name="keterangan" class="form-control form-control-sm"
                                                placeholder="Input keterangan" required>
                                            <button type="submit" class="btn btn-sm btn-success mt-2">
                                                <i class="fa fa-save"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php else: ?>
                                <?= $row->keterangan2 ?: '-'; ?>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <?= !empty($row->paraf_prod) ? $row->paraf_prod : '-' ?>
                            </td>
                            <td><?= $row->paraf_qc ?></td>
                        </tr>
                        <?php
                        $item_no++;
                        } ?>
                    </tbody>
                </table>

            </div>
            <div class="row mt-3">
                <div class="col">
                    <a href="<?= base_url('cekmesin_mp') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-check-oke', function(event) {
        event.preventDefault();
        var cek_uuid = $(this).attr('data-uuid');
        var checklist = $(this).data('checklist');

        $.post('<?= base_url('cekmesin/check_akhir/'); ?>' + cek_uuid, {
            checklist: checklist
        }, function(res) {
            var response = JSON.parse(res);
            if (response.status) {
                location.reload();
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

    $(document).on('submit', '.form-keterangan', function(event) {
        event.preventDefault(); // Mencegah submit default

        var form = $(this);
        var uuid = $(this).attr('data-uuid');
        var keterangan = form.find('input[name="keterangan"]').val();

        $.post('<?= base_url('cekmesin/keterangan/'); ?>' + uuid, {
                uuid: uuid,
                keterangan: keterangan
            },
            function(res) {
                var response = JSON.parse(res);
                if (response.status) {
                    location.reload(); // Refresh halaman

                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal menyimpan keterangan.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        );
    });

    // Approval cek mesin
    $(document).on('click', '.btn-approve-fr, .btn-approve-spv', function(event) {
        event.preventDefault();

        var uuid = $(this).data('uuid');
        var area = $(this).data('area');
        var isForeman = $(this).hasClass('btn-approve-fr');
        var url = isForeman ?
            '<?= base_url('cekmesin/approval_cekmesin2'); ?>/' + uuid + '/' + area :
            '<?= base_url('cekmesin/approval_cekmesin'); ?>/' + uuid + '/' + area;

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyetujui?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, function(res) {
                    var response = JSON.parse(res);
                    if (response.status) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Approval berhasil disetujui.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
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
            }
        });
    });
});
</script>