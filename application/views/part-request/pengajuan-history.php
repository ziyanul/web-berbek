<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">History Pengajuan Repair & New Part</h2>
        
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
                                <td width="220">

                                    <span class="badge badge-<?= $row->status_data['badge']; ?>">
                                        <?= $row->status_data['label']; ?>
                                    </span>

                                    <?php if ($row->last_status == 5): ?>

                                        <div class="border rounded p-2 mt-2 bg-light small">

                                            <div class="mb-1">
                                                <span class="<?= $row->approval['Produksi'] ? 'text-success' : 'text-secondary'; ?>">
                                                    <?= $row->approval['Produksi'] ? '✓' : '○'; ?>
                                                </span>
                                                Produksi
                                            </div>

                                            <div class="mb-1">
                                                <span class="<?= $row->approval['Engineering'] ? 'text-success' : 'text-secondary'; ?>">
                                                    <?= $row->approval['Engineering'] ? '✓' : '○'; ?>
                                                </span>
                                                Engineering
                                            </div>

                                            <div>
                                                <span class="<?= $row->approval['Warehouse'] ? 'text-success' : 'text-secondary'; ?>">
                                                    <?= $row->approval['Warehouse'] ? '✓' : '○'; ?>
                                                </span>
                                                Warehouse
                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </td>
                                <td width="150">
                                    <a href="<?= base_url('partrequest/history/detail/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm"><i class="fas fa-sm fa-eye text-light"></i> Detail</a>
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
<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-check-approval', function (event) {
        event.preventDefault(); // Prevent the default action
        var uuid = $(this).attr('data-uuid');
        $.get('<?= base_url('partrequest//mengetahui/'); ?>' + uuid, function (res) {
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