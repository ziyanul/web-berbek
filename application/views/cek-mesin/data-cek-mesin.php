<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="h2 mb-2 text-gray-800">Detail dan Aproval Pengecekan Mesin</h2>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="1">No</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="100">Area</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="200">Group</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="200">Item</th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="200">CheckList</th>
                            <th rowspan="2" class="font-weight-bold align-middle text-center" width="130">Keterangan</th>
                            <th colspan="2" class="font-weight-bold align-middle text-center" width="130">Paraf</th>
                        </tr>
                        <tr>
                            <th class="font-weight-bold align-middle text-center" width="100">Ya</th>
                            <th class="font-weight-bold align-middle text-center" width="100">Tidak</th>
                            <th class="font-weight-bold align-middle text-center" width="65">Prod</th>
                            <th class="font-weight-bold align-middle text-center" width="65">QC</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td width="1"><?= $no ?></td>
                                <td><?= $row->area ?></td>
                                <td><?= $row->group ?></td>
                                <td><?= $row->item ?></td>
                                <td class="text-center"><?= $row->check_ya ?></td>
                                <td class="text-center"><?= $row->check_tdk ?></td>
                                <td><?= $row->keterangan ?></td>
                                <td>
                                    <?php if ($row->paraf_prod == NULL && $this->session->userdata('type')==1 || $this->session->userdata('type')==2): ?>
                                <a href="#" data-uuid="<?= $row->cek_uuid; ?>" class="btn btn-check-approval btn-block" data-toggle="tooltip" data-placement="top" title="Check for Approval">
                                    <i class="fa fa-check-circle mr-2" style="color: #07e203;"></i>
                                </a>
                                <?php else: ?>
                                    <?= $row->paraf_prod; ?>
                                    <?php endif; ?></td>
                                    <td><?= $row->paraf_qc ?></td>
                                
                            </tr>
                            <?php
                            $no++;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3">
                    <div class="col">
                       
                        <a href="<?= base_url('cekmesin') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
        </div>
    </div>
</div>

    <script>
$(document).ready(function () {
    $(document).on('click', '.btn-check-approval', function (event) {
        event.preventDefault(); // Prevent the default action
        var cek_uuid = $(this).attr('data-uuid');
        $.get('<?= base_url('cekmesin/paraf_prod/'); ?>' + cek_uuid, function (res) {
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