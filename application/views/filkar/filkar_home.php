<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Filling Karantina</h1>
        <a href="<?= base_url('filkar/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
    </div>
    <?php if ($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?php echo $this->session->flashdata('success_msg'); ?>
        </div>
        <br>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger  text-center">
            <i class="fas fa-times"></i>
            <?php echo $this->session->flashdata('error_msg'); ?>
        </div>
        <br>
    <?php endif ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class='align-middle text-center'>No.</th>
                            <th class='align-middle text-center'>Tanggal</th>
                            <th class='align-middle text-center'>Kode Batch</th>
                            <th class='align-middle text-center'>Varian</th>
                            <th class='align-middle text-center'>Jumlah Box</th>
                            <th class='align-middle text-center'>Jumlah KG</th>
                            <th class='align-middle text-center' width='35%'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                        ?>
                            <tr>
                                <td class='align-middle text-center' width="1"><?= $no; ?></td>
                                <td class='align-middle text-center'><?= tanggal_indo($row->created_at) ?></td>
                                <td class='align-middle text-center'><?= $row->kode_batch; ?></td>
                                <td class='align-middle text-center'><?= $row->varian; ?></td>
                                <td><?= $row->jumlah_box ?></td>
                                <td><?= $row->jumlah_kg ?></td>
                                <td class='align-middle text-center'>
                                    <a href="<?= base_url('filkar/detail/' . $row->uuid) ?>" class="btn btn-md btn-info shadow-sm mb-2"><i
                                            class="fa fa-edit fa-sm text-white mr-2"></i> Detail</a>
                                    <a href="<?= base_url('filkar/edit/' . $row->uuid) ?>" class="btn btn-md btn-warning shadow-sm mb-2"><i
                                            class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-delete mb-2"
                                        data-id="<?= $row->uuid ?>">
                                        <i class="fa fa-trash mr-2"></i> Hapus
                                    </button>
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
</div>
<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let uuid = $(this).data('id');
        Swal.fire({
            title: 'Hapus data?',
            text: 'Data Filkar akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('filkar/hapus/') ?>" + uuid;
            }
        });
    });
</script>