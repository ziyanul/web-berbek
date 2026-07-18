<!-- Begin Page Content -->
<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Control Printing DOD</h1>
        <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="zanasi/tambah" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
            <?php }?>
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
                <table class="table table-bordered" id="datatableszanasi" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                         <tr>
                            <th width="1" class="font-weight-bold">No</th>
                            <th class="font-weight-bold">Tanggal</th>
                            <th class="font-weight-bold">Tipe</th>
                            <th class="font-weight-bold">Varian</th>
                            <th class="font-weight-bold">Kode Produksi</th>
                            
                            <th width="70" class="font-weight-bold text-center">Jumlah Permintaan</th>
                            <th class="font-weight-bold">Realisasi</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="font-weight-bold">Action </th>
                        </tr> 
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#datatableszanasi').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[1, "desc"]],
        "ajax": {
            "url": "<?= base_url('zanasi/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [0, 7, 8],
                "orderable": false
            },
            {
                "targets": [2, 7, 8],
                "searchable": false
            }
        ],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
    });
});
</script>