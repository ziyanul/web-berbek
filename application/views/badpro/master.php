<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Master Bad Produk</h1>
        <a href="<?= base_url('badpro/tambahmaster'); ?>" class="btn btn-md btn-primary shadow-sm"><i
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
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class='align-middle text-center' width='1'>No.</th>
                            <th>Area</th>
                            <th>Nama Bad Produk</th>
                            <th>Kategori</th>
                            <th class='align-middle text-center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                     $no = 1;
                     foreach ($data as $row) {
                        ?>
                        <tr>
                            <td width="1"><?= $no;?></td>
                            <td><?= $row->nama_proses;?></td>
                            <td class="badpro"><?= $row->nama_badpro;?></td>
                            <td><?= $row->jenis;?></td>
                            <td>
                                <a href="<?= base_url('badpro/editmaster/'.$row->uuid); ?>"
                                    class="btn btn-md btn-warning btn-block shadow-sm"><i
                                        class="fa fa-edit mr-2"></i>Edit</a>
                                <a href="#" data-uuid="<?= $row->uuid; ?>"
                                    class="btn btn-sm btn-danger btn-hps btn-block" data-toggle="tooltip"
                                    title="Hapus Data">
                                    <i class="fa fa-trash mr-2"></i>Hapus
                                </a>
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
$(document).ready(function() {
    $(document).on('click', '.btn-hps', function(e) {
        e.preventDefault();
        var data_uuid = $(this).data('uuid');
        var badpro = $(this).closest('tr').find('.badpro').text();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: 'Ingin menghapus data: <strong>' + badpro + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                $.get('<?= base_url('badpro/hapus_badpro/'); ?>' + data_uuid, function(
                    res) {
                    var response = JSON.parse(res);
                    if (response.status) {
                        Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                });
            }
        });
    });
});
</script>