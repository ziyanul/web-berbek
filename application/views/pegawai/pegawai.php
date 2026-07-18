 <!-- Begin Page Content -->
 <div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pegawai</h1>
        <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="<?= base_url('pegawai/tambah');?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
        <?php } ?>
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
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr align="center">
                            <th>No</th>
                            <!-- <th>NIK</th> -->
                            <th>Nama Lengkap</th>
                            <th>User Name</th>
                            <!-- <th>Email</th> -->
                            <th width="1">Departemen</th>
                            <th>Type</th>
                            <!-- <th>Status</th> -->
                            <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                            <th>Action</th>
                        <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($pegawai as $val) {
                            ?>
                            <tr>
                                <td><?= $no;?></td>
                                <!-- <td><?= $val->nik;?></td> -->
                                <td><?= $val->fullname;?></td>
                                <td><?= $val->username ?></td>
                                <!-- <td><?= empty($val->email)?'-':$val->email; ?></td> -->
                                <td><?= $val->departemen;?></td>
                                <td><?= $val->type;?></td>
                                <!-- <td class="text-center"><?= $val->resign_date == NULL ?'<span class="text-success">Aktif</span>':'<span class="text-danger">Tidak Aktif</span>';?></td> -->
                                <td id="pegawai-actions" class="text-center">
                                    <!-- <a href="<?= base_url('pegawai/detail/'.$val->uuid); ?>" class="btn btn-sm btn-success btn-block">
                                        <i class="fa fa-search mr-2"> Detail</i>
                                    </a> -->

                                    <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                                    <a href="#" data-uuid="<?= $val->uuid;?>" class="btn btn-sm btn-danger btn-reset-password btn-block" data-toggle="tooltip" data-placement="top" title="Reset Password">
                                        <i class="fa fa-lock mr-2"></i> Reset Password
                                    </a>
                                    <!-- <a href="<?= base_url('pegawai/subrole/'.$val->uuid); ?>" class="btn btn-sm btn-warning btn-block">
                                        <i class="fa fa-plus">SubRole</i>
                                    </a> -->
                                    
                                        <a href="<?= base_url('pegawai/edit/'.$val->uuid); ?>" class="btn btn-sm btn-warning btn-block">
                                            <i class="fa fa-edit mr-2"> Edit Data</i>

                                        </a>

                                        <a href="#" data-uuid="<?= $val->uuid;?>" class="btn btn-sm btn-danger btn-hapus-data btn-block" data-toggle="tooltip" data-placement="top" title="Hapus Data">
                                            <i class="fa fa-trash mr-2"></i> Hapus
                                        </a>
                                    <?php } ?>
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
    $(document).ready(function () {
    // Cek apakah DataTable sudah ada atau belum

        // var statusColumn = pegawaiTable.column(8);
        // var statusFilter = "<select id='status-filter'><option value=''>Status</option><option value='Aktif'>Aktif</option><option value='Tidak Aktif'>Tidak Aktif</option></select>";
        // $(statusFilter).appendTo($(statusColumn.header()).empty());

        // $('#status-filter').on('change', function() {
        //     var selectedValue = $(this).val();
        //     statusColumn.search(selectedValue).draw();
        // });

        $(document).on('click', '.btn-reset-password', function () {
            var user_uuid = $(this).attr('data-uuid');
            var fullname = $(this).closest('tr').find('td.fullname').text();

            Swal.fire({
                title: 'Apakah Anda yakin ingin mereset password ' + fullname + '?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#e74a3b',
                icon: 'question'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('<?= base_url('pegawai/reset_password/');?>' + user_uuid, function (res) {
                        var response = JSON.parse(res);
                        if (response.status) {
                        // Hancurkan DataTable sebelum reload
                            if ($.fn.DataTable.isDataTable('#datatables')) {
                                $('#datatables').DataTable().clear().destroy();
                            }
                        location.reload(); // Reload halaman setelah reset password
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Reset password failed.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }).fail(function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Request failed.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
        });

        $(document).on('click', '.btn-hapus-data', function () {
            var user_uuid = $(this).attr('data-uuid');
            var fullname = $(this).closest('tr').find('td.fullname').text();

            Swal.fire({
                title: 'Apakah Anda yakin ingin Hapus ' + fullname + '?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#e74a3b',
                icon: 'question'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('<?= base_url('pegawai/hapus_data/');?>' + user_uuid, function (res) {
                        var response = JSON.parse(res);
                        if (response.status) {
                        // Hancurkan DataTable sebelum reload
                            if ($.fn.DataTable.isDataTable('#datatables')) {
                                $('#datatables').DataTable().clear().destroy();
                            }
                        location.reload(); // Reload halaman setelah reset password
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Data Gagal dihapus!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }).fail(function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Request failed.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
        });

    });

</script>