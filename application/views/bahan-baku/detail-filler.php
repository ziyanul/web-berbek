<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h3 class="h3 mb-2 text-gray-800">Detail Permintaan Bahan Baku Filler</h3>
        <?php
            $now = date('Y-m-d');
                if ($data[0]->tanggal == $now) {
            ?>
            <a href="<?= base_url('bahan_filler/detail_'.$data[0]->tanggal.'/tambah'); ?>" class="btn btn-md btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white"></i> Tambah</a>
        <?php } ?>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('bahan_filler') ?>"><i class="fas fa-arrow-left"></i>
                    Permintaan Bahan Baku Filler</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?php echo $this->session->flashdata('success_msg'); ?>
    </div>
    <br>
    <?php endif; ?>
    <?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?php echo $this->session->flashdata('error_msg'); ?>
    </div>
    <br>
    <?php endif ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <h5 class="font-weight-bold">Informasi Permintaan</h5>
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
                            <td class="font-weight-bold">Nomor Reservasi</td>
                            <td width="15">:</td>
                            <td class="font-weight-bold"><?= sprintf("%04d", ( $row->no_reservasi)); ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approval Spv</td>
                            <td width="15">:</td>
                            <td class='font-weight-bold border-bottom-0' style="color: #219b0b;">
                                <?php if (empty($row->spv_uuid)): ?>
                                <!-- Tombol ACC untuk persetujuan -->
                                <a href="#" data-uuid="<?= $row->tanggal; ?>" data-area="<?= $row->area_uuid; ?>"
                                    class="btn btn-approve btn-success shadow-sm" data-toggle="tooltip"
                                    data-placement="top" title="Approval">
                                    <i class="fa fa-check-circle mr-2"></i> ACC
                                </a>
                                <?php else: ?>
                                <!-- Tampilkan fullname jika sudah di-ACC -->
                                <?= $row->spv ? $row->spv : 'Sudah Disetujui'; ?>
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
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th class='align-middle text-center'>No.</th>
                            <th class='align-middle'>User</th>
                            <th class='align-middle' width='200px'>Nama Barang</th>
                            <th class='align-middle text-center' width='5px'>Satuan</th>
                            <th class='align-middle text-center'>Waktu Reservasi</th>
                            <th class='align-middle text-center'>Jumlah Reservasi</th>
                            <th class='align-middle text-center'>Jumlah Dikirim</th>
                            <th class='align-middle text-center'>Belum Dikirim</th>
                            <th class='align-middle text-center'>Keterangan</th>
                            <th class='align-middle text-center'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                        <tr>
                            <td class='align-middle text-center' width="1"><?= $no;?></td>
                            <td class='align-middle'><?= $row->fullname;?></td>
                            <td class='align-middle'><?= $row->item_barang;?></td>
                            <td class='align-middle text-center'><?= $row->satuan;?></td>
                            <td class='align-middle text-center'><?= $row->jam;?></td>
                            <td class='align-middle text-center'><?= $row->qty_reservasi;?></td>
                            <td class='align-middle text-center'><?= !empty($row->total_kirim) ? $row->total_kirim : '-'; ?></td>
                            <td class='align-middle text-center'><?= $row->qty_reservasi - $row->total_kirim;?></td>
                            <td class='align-middle text-center'><?= !empty($row->keterangan) ? $row->keterangan : '-'; ?></td>
                            <td class='align-middle text-center'>
                                <a href="<?= base_url('bahan_filler/'.$row->uuid); ?>"
                                    class="btn btn-md btn-info shadow-sm"><i
                                        class="fa fa-clipboard-check fa-md text-white mr-2"></i>Penerimaan</a>
                            </td>
                        </tr>
                        <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col mt-3">
                <a href="<?= base_url('bahan_filler') ?>" class="btn btn-md btn-danger">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-approve', function(event) {
        event.preventDefault();

        var uuid = $(this).data('uuid'); // Ambil UUID dari tombol yang diklik
        var area = $(this).data('area');
        var $button = $(this); // Simpan referensi tombol yang diklik

        // Kirim request approval via AJAX
        $.post('<?= base_url('bahan_baku/approval_filler'); ?>/' + uuid + '/' + area, function(res) {
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