<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Area Proses Produksi</h1>
        <a href="<?= base_url('area_proses/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th width="1">No.</th>
                            <th>Area Proses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td width="1" class="text-center"><?= $no;?></td>
                                <td><?= $row->nama_proses;?></td>
                                <td>
                                    <a href="<?= base_url('area_proses/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white"></i> Edit</a>
                                    <a href="javascript:void(0)"
   class="btn btn-md btn-danger shadow-sm btn-hapus"
   data-url="<?= base_url('area_proses/hapus/'.$row->uuid); ?>">
    <i class="fa fa-trash fa-sm text-white"></i> Hapus
</a>
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
    $('.btn-hapus').click(function () {
    let url = $(this).data('url');

    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
</script>