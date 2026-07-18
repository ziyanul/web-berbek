<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Autonomous Maintenance</h1>

    <a href="<?= base_url('am/tambahkegiatan'); ?>" class="btn btn-md btn-primary shadow-sm mr-3"><i class="fas fa-plus fa-sm text-white"></i> Tambah</a>


</div>
<!-- DataTales Example -->
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
            <table class="table table-bordered" id="datatables">
                <thead class="bg-info text-white">
                    <tr>
                        <th width="1">No</th>
                        <th>Area</th>
                        <th>Mesin</th>
                        <th width="120">Jumlah Kegiatan</th>
                        <th width="250">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($kegiatan as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $k->nama_area ?></td>
                        <td><?= $k->nama_mesin ?></td>
                        <td>
                            <span class="badge badge-primary">
                                <?= $k->total_kegiatan ?> Kegiatan
                            </span>
                        </td>
                        <td>
                            <button 
                            class="btn btn-info btn-sm btn-detail"
                            data-mesin="<?= $k->mesin_uuid ?>"
                            data-nama="<?= $k->nama_mesin ?>">
                            <i class="fa fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="modal fade" id="modalDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detail Kegiatan Mesin</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="detail-content">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Kegiatan</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <form id="formEditKegiatan">
                <div class="modal-body">

                    <input type="hidden" name="uuid" id="edit_uuid">

                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" 
                        class="form-control" 
                        name="kegiatan" 
                        id="edit_kegiatan"
                        required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    $(document).on('click','.btn-detail',function(){

    let mesin_uuid = $(this).data('mesin');
    let nama_mesin = $(this).data('nama');

    $('#modalDetail .modal-title').text('Detail Kegiatan - ' + nama_mesin);
    $('#detail-content').html('Loading...');
    $('#modalDetail').modal('show');

    $.get('<?= base_url('am/get_kegiatan_by_mesin/') ?>'+mesin_uuid,function(res){

        let data = JSON.parse(res);
        let html = '<ul class="list-group">';

        data.forEach(function(item){
            html += `
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span>${item.kegiatan}</span>

                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-warning btn-edit"
                            data-uuid="${item.uuid}"
                            data-kegiatan="${item.kegiatan}">
                            <i class="fa fa-edit"></i>
                        </button>

                        <a href="<?= base_url('am/delete_kegiatan/') ?>${item.uuid}"
                           class="btn btn-danger"
                           onclick="return confirm('Hapus kegiatan ini?')">
                           <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </li>`;
        });

        html += '</ul>';

        $('#detail-content').html(html);
    });

});


$(document).on('click', '.btn-edit', function(){

    $('#edit_uuid').val($(this).data('uuid'));
    $('#edit_kegiatan').val($(this).data('kegiatan'));

    $('#modalEdit').modal('show');
});


$('#formEditKegiatan').submit(function(e){
    e.preventDefault();

    $.post('<?= base_url("am/update_kegiatan_ajax") ?>',
        $(this).serialize(),
        function(res){

            let response = JSON.parse(res);

            if(response.status){

                $('#modalEdit').modal('hide');

                alert('Berhasil update');

                $('.btn-detail[data-mesin="'+response.mesin_uuid+'"]').click();

            } else {
                alert('Gagal update');
            }
        }
    );
});
</script>