<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Data Stock Rework</h1>
        <a href="<?= base_url('rework/tambahpakai/'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>
    <?php if ($this->session->flashdata('success_msg')) : ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div>
        <br>
    <?php endif ?>
    <?php if ($this->session->flashdata('error_msg')) : ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div>
        <br>
    <?php endif ?>


    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-success mb-4">
                    <tbody>
                        <?
                        foreach ($data as $row)
                        ?>
                        <tr>
                            <td width="150" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $row->tanggal; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $row->varian; ?></td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Foreman / lady</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($row->foreman_uuid)) : ?>
                                    <a href="#" data-tanggal_kode="<?= $row->tanggal_kode; ?>" data-role="1" class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip" data-placement="top" title="Approval Foreman">
                                        <i class="fa fa-check-circle mr-2"></i> ACC
                                    </a>
                                <?php else : ?>
                                    <?= $row->leader ? $row->leader : 'Foreman/Lady Belum ACC'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php if (!empty($row->foreman_uuid)) : ?>
                            <tr>
                                <td class="font-weight-bold">Approval SPV</td>
                                <td width="15">:</td>
                                <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                    <?php if (empty($row->spv_uuid)) : ?>
                                        <a href="#" data-tanggal_kode="<?= $row->tanggal_kode; ?>" data-role="2" class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip" data-placement="top" title="Approval SPV">
                                            <i class="fa fa-check-circle mr-2"></i> ACC
                                        </a>
                                    <?php else : ?>
                                        <?= $row->spv ? $row->spv : 'Sudah Disetujui'; ?>
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
                            <td width="1" class="font-weight-bold align-middle text-center" rowspan="2">NO.</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">VARIAN REWORK</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">KODE REWORK</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">TANGGAL MASUK CS</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">QTY MASUK CS (KG)</td>
                            <td class="font-weight-bold align-middle text-center" colspan="3">REWORK</td>
                            <td class="font-weight-bold align-middle text-center" colspan="2">TEMUAN</td>
                            <td class="font-weight-bold align-middle text-center" colspan="2">ACC</td>
                            <?php if (empty($row->foreman_uuid)) : ?>
                                <td class="font-weight-bold align-middle text-center" rowspan="2">ACTION</td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="font-weight-bold align-middle text-center">QTY PEMAKAIAN(KG)</td>
                            <td class="font-weight-bold align-middle text-center">KODE BATCH PRODUKSI</td>
                            <td class="font-weight-bold align-middle text-center">SISA REWORK (KG)</td>
                            <td class="font-weight-bold align-middle text-center">PLASTIK</td>
                            <td class="font-weight-bold align-middle text-center">METAL</td>
                            <td class="font-weight-bold align-middle text-center">QC</td>
                            <td class="font-weight-bold align-middle text-center">OPERATOR</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $no; ?></td>
                                <td><?= $row->varian; ?></td>
                                <td><?= $row->kode_rework; ?></td>
                                <td><?= $row->tanggal_masuk; ?></td>
                                <td class="text-center"><?= $row->total_rework; ?></td>
                                <td class="text-center"><?= $row->dipakai; ?></td>
                                <td><?= $row->kode_batch; ?></td>
                                <td class="text-center"><?= $row->sisa_stock; ?></td>
                                <td class="text-center"><?= $row->plastik; ?></td>
                                <td class="text-center"><?= $row->metal; ?></td>
                                <td><?= $row->acc_qc; ?></td>
                                <td><?= $row->pembuat; ?></td>
                                <?php if (empty($row->foreman_uuid)) : ?>
                                    <td>
                                        <a href="<?= base_url('rework/editpakai/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block">Edit</a>
                                        <?php
                                        if ($row->acc_qc == null && $this->session->userdata('type') == 1 || $this->session->userdata('type') == 2) { ?>
                                            <a href="<?= base_url('rework/acc_qc/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block">ACC</a>
                                        <?php } ?>
                                    </td>
                                <?php endif; ?>
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

<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-approve', function(event) {
            event.preventDefault();
            var tanggal_kode = $(this).data('tanggal_kode');
            var role = $(this).data('role');
            var $button = $(this);

            $.post('<?= base_url('rework/approval'); ?>/' + tanggal_kode + '/' + role, function(response) {
                try {
                    var res = JSON.parse(response);

                    if (res.status) {
                        // Ganti tombol dengan nama user yang menyetujui
                        $button.closest('td').html(res.fullname);
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: res.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Respon tidak valid dari server.',
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