<!--Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Penerimaan Sampel</h1>
        <a href="<?= base_url('penerimaan-sampel/tambah');?>" class="btn btn-md btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="penerimaan-sampel" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th width="1">No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kode</th>
                            <th>Best Before</th>
                            <th>Keterangan</th>
                            <th>Jenis Pengujian</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Progress</th>
                            <!-- <th>Status</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
    $(document).ready(function () {
        var table = new DataTable('#penerimaan-sampel',{
            ajax: {
                url: "<?= base_url('Penerimaan_sampel/ajax');?>", //controller/function
                type: "POST",
                complete: function (xhr) {
                    console.log(xhr.responseJSON)
                }
            },
            processing: true, 
            serverSide: true,
            searchable: true,
            pageLength: 25,
            responsive: true,
        });

    })
</script>