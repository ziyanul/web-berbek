<div class="container-fluid">

    <!-- Page Heading -->
    <h3 class="h3 mb-2 text-gray-800">Detail Benda Tajam</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('Pbtajam/form_pbtajam') ?>"><i
                        class="fas fa-arrow-left mr-2"></i>Pengecekan Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

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
                <h5 class="font-weight-bold">Informasi Pengecekan Benda Tajam</h5>
                <table class="table table-success mb-4">
                    <tbody>
                        <?
                    foreach ($data as $row)
                    ?>
                        <tr>
                            <td width="200" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $row->tgl;?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Shift</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $row->shift_name; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $row->fullname; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Foreman/Lady</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($row->leader)): ?>
                                <a href="#" data-uuid="<?= $row->tanggal; ?>" data-shift="<?= $row->shift; ?>"
                                    data-role="2" class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval Foreman">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else: ?>
                                <?= $row->leader ? $row->leader : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approval SPV</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($row->spv) && (!empty($row->leader)) && $this->session->userdata('type') == 1): ?>
                                <a href="#" data-uuid="<?= $row->tanggal; ?>" data-shift="<?= $row->shift; ?>"
                                    data-role="1" class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval SPV">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else: ?>
                                <?= $row->spv ? $row->spv : '-'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col mb-3">
                    <table class='table table-bordered'>
                        <thead class='table text-light bg-info'>
                            <tr>
                                <th class="align-middle" rowspan="2">Area</th>
                                <th class="align-middle" rowspan="2">Jenis Benda Tajam</th>
                                <th class="align-middle" rowspan="2">Kode Benda Tajam</th>
                                <th class="text-center align-middle" colspan="3">Kondisi</th>
                                <th class="text-center align-middle" rowspan="2">Keterangan</th>
                                <th class="text-center align-middle" rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">Baik</th>
                                <th class="text-center align-middle">Pecah</th>
                                <th class="text-center align-middle">Hilang</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                        $currentArea = '';
                        $currentJenisBenda = '';

                        foreach ($data as $row) {
                            echo "<tr>";

                            // Kolom Area dengan rowspan
                            if ($currentArea != $row->nama_area) {
                                $areaRowCount = count(array_filter($data, fn($r) => $r->nama_area == $row->nama_area));
                                echo "<td class='align-middle' rowspan='$areaRowCount'>{$row->nama_area}</td>";
                                $currentArea = $row->nama_area;
                            }

                            // Kolom Jenis Benda Tajam dengan rowspan
                            if ($currentJenisBenda != $row->jenis_benda) {
                                $jenisRowCount = count(array_filter($data, fn($r) => $r->nama_area == $row->nama_area && $r->jenis_benda == $row->jenis_benda));
                                echo "<td class='align-middle' rowspan='$jenisRowCount'>{$row->jenis_benda}</td>";
                                $currentJenisBenda = $row->jenis_benda;
                            }

                            // Kolom Kode Benda Tajam
                            echo "<td class='align-middle'>{$row->kode_benda}</td>
                            <td class='text-center align-middle'>" . (isset($row->kondisi1) ? $row->kondisi1 : '-') . "</td>
                            <td class='text-center align-middle'>" . (isset($row->kondisi2) ? $row->kondisi2 : '-') . "</td>
                            <td class='text-center align-middle'>" . (isset($row->kondisi3) ? $row->kondisi3 : '-') . "</td>
                            <td>{$row->keterangan}</td>
                            <td class='text-center'>
                                    <a href='" . base_url('Pbtajam/editform/'.$row->uuid) . "' class='btn btn-md btn-warning shadow-sm'>
                                        <i class='fa fa-edit fa-md text-white mr-2'></i> Edit
                                    </a>
                                </td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col mt-3">
                <a href="<?= base_url('Pbtajam/form_pbtajam/') ?>" class="btn btn-md btn-danger">
                    <i class="fa fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-approve', function(event) {
        event.preventDefault();

        var uuid = $(this).data('uuid');
        var shift = $(this).data('shift');
        var role = $(this).data('role'); // Ambil role dari tombol
        var $button = $(this);

        // Kirim request approval via AJAX
        $.post('<?= base_url('pbtajam/approval'); ?>/' + uuid + '/' + shift + '/' + role,
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