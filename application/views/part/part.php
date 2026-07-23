<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Sparepart</h1>
 
            <a href="<?= base_url('part/tambah'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>
    <!-- DataTales Example -->


    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tablepart" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Mesin</th>
                            <th>Nama Sparepart</th>
                            <th>Lifetime</th>
                            <th>Harga</th> 
                            <th>Aksi</th>                                      
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
        var table = new DataTable('#tablepart',{
            ajax: {
                url: "<?= base_url('Part/ajax');?>", //controller/function
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