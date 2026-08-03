<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Detail Stock Rework</h1>
        <a href="<?= base_url('rework/tambah_kupas/' . $stock->tbatch_uuid . '/' . $stock->badpro_uuid); ?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('rework/kupas'); ?>"><i class="fas fa-arrow-left"></i>
                    Stock Rework</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>



    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-success mb-4">
                    <tbody>
                        <tr>
                            <td width="150" class="font-weight-bold border-top-0">Kode Batch</td>
                            <td width="10" class="border-top-0">:</td>
                            <td class="font-weight-bold border-top-0"><?= $stock->kode_batch; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Varian</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $stock->nama_varian; ?> ( <?= $stock->keterangan; ?> )</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Bad Produk</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $stock->nama_badpro; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Total Rework</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $stock->total_rework; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Total Kupas</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $stock->total_kupas; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Belum Kupas</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= $stock->sisa_kupas; ?></td>
                        </tr>



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
                            <td class="font-weight-bold align-middle text-center" rowspan="2">USER</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">BERAT</td>
                            <td class="font-weight-bold align-middle text-center" rowspan="2">TANGGAL MASUK CS</td>

                            <td class="font-weight-bold align-middle text-center" rowspan="2">ACTION</td>

                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($riwayat as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $no; ?></td>
                                <td><?= $row->fullname; ?></td>
                                <td><?= $row->berat; ?></td>
                                <td><?= tanggal_indo($row->tgl); ?></td>

                                <td>
                                    <a href="<?= base_url('rework/editpakai/' . $row->berat); ?>" class="btn btn-md btn-warning shadow-sm btn-block">Edit</a>

                                </td>

                            </tr>
                        <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="<?= base_url('rework/kupas'); ?>" class="btn btn-danger">

                <i class="fa fa-arrow-left"></i>
                Kembali

            </a>

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