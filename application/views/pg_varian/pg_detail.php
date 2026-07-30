<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">Detail Pergantian Varian Retort</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian_retort') ?>"><i
                        class="fas fa-arrow-left mr-2"></i> Pergantian Varian</a></li>
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
                <h5 class="font-weight-bold">Informasi Pergantian Varian</h5>
                <table class="table table-success mb-4">
                    <tbody>
                        <?php foreach ($nav as $row) : ?>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= htmlspecialchars($row->tgl ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= htmlspecialchars($row->fullname ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Koordinator</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold border-bottom-0" style="color: #219b0b;">
                                <?php if (empty($row->kr_uuid)) : ?>
                                <a href="#" data-tanggal="<?= htmlspecialchars($row->created_at); ?>" data-role="1"
                                    class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval Koordinator">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else : ?>
                                <?= htmlspecialchars($row->leader ?? 'Sudah Disetujui'); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approval SPV</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold border-bottom-0" style="color: #219b0b;">
                                <?php if (empty($row->spv_uuid)) : ?>
                                <a href="#" data-tanggal="<?= htmlspecialchars($row->created_at); ?>" data-role="2"
                                    class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval SPV">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else : ?>
                                <?= htmlspecialchars($row->spv ?? 'Sudah Disetujui'); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col mb-3">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-5 display nowrap" id="datatables" width="100%"
                            cellspacing="0">
                            <thead class="table bg-info text-light font-weight-bold">
                                <tr>
                                    <th class='align-middle text-center' rowspan="2">No.</th>
                                    <th class='align-middle text-center' colspan="2">Dari Proses Produksi</th>
                                    <th class='align-middle text-center' colspan="2">Ke Proses Produksi</th>
                                    <th class='align-middle text-center' colspan="2">Kondisi</th>
                                    <th class='align-middle text-center' rowspan="2">Keterangan</th>
                                    <th class='align-middle text-center' colspan="2">TTD</th>
                                    <th class='align-middle text-center' rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th class='align-middle text-center'>Varian</th>
                                    <th class='align-middle text-center'>Kode Batch</th>
                                    <th class='align-middle text-center'>Varian</th>
                                    <th class='align-middle text-center'>Kode Batch</th>
                                    <th class='align-middle text-center'>Bersih dari Kontaminasi</th>
                                    <th class='align-middle text-center'>Belum Bersih dari Kontaminasi</th>
                                    <th class='align-middle text-center'>KR/Checker</th>
                                    <th class='align-middle text-center'>QC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach ($data as $row) {
                                        ?>
                                <tr>
                                    <td class='align-middle text-center' width="1"><?= $no;?></td>
                                    <td class='align-middle text-center'><?= $row->varian_name_1;?></td>
                                    <td class='align-middle text-center'><?= $row->uuid_kode_prod_1;?></td>
                                    <td class='align-middle text-center'><?= $row->varian_name_2;?></td>
                                    <td class='align-middle text-center'><?= $row->uuid_kode_prod_2;?></td>
                                    <td class='align-middle text-center'><?= $row->kondisi1;?></td>
                                    <td class='align-middle text-center'><?= $row->kondisi2;?></td>
                                    <td class='align-middle text-center'><?= $row->keterangan;?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->fullname) ? $row->fullname : '-'; ?></td>
                                    <td class='align-middle text-center'><?= $row->acc_qc;?></td>
                                    <td class='align-middle text-center'>
                                        <a href="<?= base_url('pergantian_varian_retort/edit/'.$row->uuid); ?>"
                                            class="btn btn-md btn-warning shadow-sm"><i
                                                class="fa fa-edit fa-sm text-white mr-1"></i> Edit</a>
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
            <div class="col mt-3">
                <a href="<?= base_url('pergantian_varian_retort') ?>" class="btn btn-md btn-danger">
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

        var tanggal = $(this).data('tanggal'); // Ambil tanggal
        var role = $(this).data('role'); // Ambil role dari tombol
        var $button = $(this);

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
                $button.prop('disabled', true);

                // Kirim request approval via AJAX
                $.ajax({
                    url: '<?= base_url('pergantian_varian/approval_retort'); ?>/' +
                        encodeURIComponent(tanggal) + '/' + encodeURIComponent(role),
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Tambahkan log untuk debugging
                        if (response.status) {
                            $button.closest('td').html(response.fullname);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            $button.prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log response error
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memproses data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        $button.prop('disabled', false);
                    }
                });
            }
        });
    });
});
</script>