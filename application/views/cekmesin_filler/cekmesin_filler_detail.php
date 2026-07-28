<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">Detail Pengecekan Mesin Filler</h2>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('cekmesin_filler') ?>"><i class="fas fa-arrow-left mr-2"></i>Pengecekan Mesin Filler</a></li>
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
                            <td class="font-weight-bold border-top-0"><?= $nav->tgl_planning; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->varian; ?> ( <?= $nav->keterangan ?> )</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tanggal Pengecekan</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->tgl; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $nav->user; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Foreman/Lady</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (is_null($nav->fr_uuid)) : ?>
                                    <a href="#" data-uuid="<?= $nav->uuid; ?>" data-area="<?= $nav->area_uuid; ?>" class="btn btn-approve-fr btn-success shadow-sm" data-toggle="tooltip" data-placement="top" title="Approval">
                                        <i class="fa fa-check-circle mr-2"></i> ACC
                                    </a>
                                <?php else : ?>
                                    <?= !empty($nav->foreman) ? $nav->foreman : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php if (!is_null($nav->fr_uuid)) : ?>
                            <tr>
                                <td class="font-weight-bold">Approval Spv</td>
                                <td width="15">:</td>
                                <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                    <?php if (is_null($nav->spv_uuid)) : ?>
                                        <a href="#" data-uuid="<?= $nav->uuid; ?>" data-area="<?= $nav->area_uuid; ?>" class="btn btn-approve-spv btn-success shadow-sm" data-toggle="tooltip" data-placement="top" title="Approval">
                                            <i class="fa fa-check-circle mr-2"></i> ACC
                                        </a>
                                    <?php else : ?>
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
                            <th rowspan="2" class="font-weight-bold align-middle" width="100">Item</th>
                            <th colspan="<?= isset($mesin_headers) ? count($mesin_headers) : 0; ?>" class="font-weight-bold align-middle text-center">Checklist (&check;) Mesin</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="150">Keterangan
                            </th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="80">Paraf</th>
                        </tr>
                        <tr>
                            <?php if (isset($mesin_headers) && !empty($mesin_headers)) : ?>
                                <?php foreach ($mesin_headers as $uuid_mesin => $nama_mesin) : ?>
                                    <th class="font-weight-bold align-middle text-center" width="50"><?= $nama_mesin; ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <th class="font-weight-bold align-middle text-center" width="40">Prod</th>
                            <th class="font-weight-bold align-middle text-center" width="40">QC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $grouped_data = [];
                        // Grup item berdasarkan nama item
                        foreach ($data as $row) {
                            $grouped_data[$row->item][] = $row;
                        }

                        foreach ($grouped_data as $item => $rows) { ?>
                            <tr>
                                <td class='align-middle text-center' width="1"><?= $no; ?></td>
                                <td class="align-middle" width="50"><?= $item; ?></td>
                                <?php foreach ($mesin_headers as $mesin_uuid => $nama_mesin) : ?>
                                    <td class="font-weight-bold align-middle text-center" width="50">
                                        <?php
                                        $checklist = "-"; // Default kosong
                                        foreach ($rows as $row) {
                                            if ($row->mesin_uuid === $mesin_uuid) {
                                                $checklist = $row->checklist == 2 ? $row->check_ya : $row->check_tdk;
                                                break;
                                            }
                                        }
                                        echo $checklist;
                                        ?>
                                    </td>
                                <?php endforeach; ?>

                                <td class="align-middle text-center">
                                    <?php
                                    $keterangan_list = [];
                                    foreach ($rows as $row) {
                                        if ($row->checklist == 0) {
                                            $keterangan_list[] = "({$mesin_headers[$row->mesin_uuid]}) - {$row->keterangan}";
                                        }
                                    }
                                    echo !empty($keterangan_list) ? implode("<br>", $keterangan_list) : "-";
                                    ?>
                                </td>
                                <td class="align-middle text-center"><?= $row->fullname ?></td>
                                <td class="align-middle text-center">
                                    <?= $rows[0]->paraf_qc ?: "-" ?>
                                </td>
                            </tr>
                        <?php
                            $no++;
                        } ?>
                    </tbody>
                </table>


            </div>
            <div class="row mt-3">
                <div class="col">
                    <a href="<?= base_url('cekmesin_filler') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-approve-fr, .btn-approve-spv', function(event) {
            event.preventDefault();

            var uuid = $(this).data('uuid');
            var area = $(this).data('area');
            var isForeman = $(this).hasClass('btn-approve-fr');
            var url = isForeman ?
                '<?= base_url('cekmesin_filler/approval_cekmesin2'); ?>/' + uuid + '/' + area :
                '<?= base_url('cekmesin_filler/approval_cekmesin'); ?>/' + uuid + '/' + area;

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