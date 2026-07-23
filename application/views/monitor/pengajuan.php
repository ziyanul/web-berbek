<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">Pengajuan Spare Part</h2>
        <a href="<?= base_url ('monitor/tambahpengajuan') ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
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
    <!-- DataTales Example -->
    <div class="card shadow mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama Part</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Action</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td width="1"><?= $no; ?></td>
                                <td><?= $row->tgl;?></td>
                                <td><?= $row->part;?></td>
                                <td><?= $row->keterangan;?></td>
                                <td><?= $row->jns; ?></td>
                                <td><?= $row->status; ?></td>
                                <td>
                                    <a href="<?= base_url('monitor/detailpengajuan/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm mt-2 mr-2">Detail</a>
                                    <a href="<?= base_url('monitor/status_part/'.$row->uuid); ?>" class="btn btn-md btn-warning btn-block shadow-sm mr-2"><i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Status</a>
                                    <a href="<?= base_url('monitor/tambahfoto/'.$row->uuid); ?>" class="btn btn-md btn-secondary shadow-sm btn-block"><i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Foto</a>
                                </td>
                            </tr>
                            <?php
                            $no++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-check-approval', function (event) {
        event.preventDefault(); // Prevent the default action
        var uuid = $(this).attr('data-uuid');
        $.get('<?= base_url('monitor/mengetahui/'); ?>' + uuid, function (res) {
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
    });
</script>