<div class="container-fluid">
    <h3 class="h3 mb-2 text-gray-800">Detail Pergantian Varian Packing</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian_packing') ?>"><i
                        class="fas fa-arrow-left"></i> Pergantian Varian</a></li>
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
                <h5 class="font-weight-bold">Informasi Pergantian Varian Packing</h5>
                <table class="table table-success mb-4">
                    <tbody>
                        <?php
                    $first_row = reset($data);
                    if ($first_row) :
                    ?>
                        <tr>
                            <td width="230" class="font-weight-bold border-top-0">Tanggal</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $first_row->tgl; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Shift</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $first_row->shift_name; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">User</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold">
                                <?= !empty($first_row->fullname) ? $first_row->fullname : '-'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Koordinator</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($first_row->kr_uuid)): ?>
                                <button data-tanggal="<?= $first_row->tgl; ?>" data-shift="<?= $first_row->shift; ?>"
                                    data-role="1" class="btn btn-approve btn-success shadow-sm">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </button>
                                <?php else: ?>
                                <?= $first_row->leader ? $first_row->leader : 'Sudah Disetujui'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approval SPV</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($first_row->spv_uuid)): ?>
                                <button data-tanggal="<?= $first_row->tgl; ?>" data-shift="<?= $first_row->shift; ?>"
                                    data-role="2" class="btn btn-approve btn-success shadow-sm">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </button>
                                <?php else: ?>
                                <?= $first_row->spv ? $first_row->spv : 'Sudah Disetujui'; ?>
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
            <div class="row mt-3">
                <div class="col mb-3">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-5 display nowrap" id="datatables" width="100%"
                            cellspacing="0">
                            <thead class="table bg-info text-light font-weight-bold">
                                <tr>
                                    <th class='align-middle text-center' rowspan="2">No.</th>
                                    <th class='align-middle text-center' colspan="2" width="150px">Dari Proses Sortasi
                                    </th>
                                    <th class='align-middle text-center' colspan="2" width="150px">Ke Proses Sortasi
                                    </th>
                                    <th class='align-middle text-center' colspan="2" width="50px">Kondisi</th>
                                    <th class='align-middle text-center' rowspan="2">Waktu</th>
                                    <th class='align-middle text-center' rowspan="2">Keterangan</th>
                                    <th class='align-middle text-center' colspan="2">TTD</th>
                                    <th class='align-middle text-center' rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th class='align-middle text-center' width="100px">Varian</th>
                                    <th class='align-middle text-center' width="70px">Kode Batch</th>
                                    <th class='align-middle text-center'>Varian</th>
                                    <th class='align-middle text-center'>Kode Batch</th>
                                    <th class='align-middle text-center' width="50px">Bersih dari Kontaminasi</th>
                                    <th class='align-middle text-center' width="50px">Belum Bersih dari Kontaminasi</th>
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
                                    <td class='align-middle text-center'><?= $row->varian_1;?></td>
                                    <td class='align-middle text-center'><?= $row->kode_prod;?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->varian_2) ? $row->varian_2 : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->kode_prod_2) ? $row->kode_prod_2 : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->kondisi1) ? $row->kondisi1 : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->kondisi2) ? $row->kondisi2 : '-'; ?></td>
                                    <td class='align-middle text-center'><?= $row->jam_mulai;?> -
                                        <?= $row->jam_selesai;?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->pg_keterangan) ? $row->pg_keterangan : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->fullname) ? $row->fullname : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?= !empty($row->qc_id) ? $row->qc_id : '-'; ?></td>
                                    <td class='align-middle text-center'>
                                        <?php if (empty($row->kondisi1) && empty($row->kondisi2)) : ?>
                                        <a href="<?= base_url('pergantian_varian_packing/tambah/'.$row->uuid); ?>"
                                            class="btn btn-md btn-info shadow-sm">
                                            <i class="fa fa-plus fa-sm text-white mr-1"></i> Tambah
                                        </a>
                                        <?php endif; ?>
                                        <?php if (!empty($row->pg_uuid)): ?>
                                        <a href="<?= base_url('pergantian_varian_packing/edit/'.$row->pg_uuid); ?>"
                                            class="btn btn-md btn-warning shadow-sm">
                                            <i class="fa fa-edit fa-sm text-white mr-1"></i> Edit
                                        </a>
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
            <div class="col mt-3">
                <a href="<?= base_url('pergantian_varian_packing') ?>" class="btn btn-md btn-danger">
                    <i class="fa fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click', '.btn-approve', function(event) {
    event.preventDefault();

    let tanggal = $(this).data('tanggal');
    let shift = $(this).data('shift');
    let role = $(this).data('role'); 
    let $button = $(this);

    console.log("Tanggal:", tanggal);
    console.log("Shift:", shift);
    console.log("Role:", role);

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

            $.post('<?= base_url('pergantian_varian/approval_packing'); ?>/' + tanggal + '/' + shift + '/' + role,
                function(res) {
                    console.log("Response:", res);
                    let response = JSON.parse(res);

                    if (response.status) {
                        $button.closest('td').html(response.fullname);
                        Swal.fire('Berhasil!', response.message, 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                        $button.prop('disabled', false);
                    }
                }).fail(function() {
                Swal.fire('Error!', 'Terjadi kesalahan saat memproses data.', 'error');
                $button.prop('disabled', false);
            });
        }
    });
});
</script>