<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Tambah Jenis Kegiatan</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('am/data') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Data AM
                </a>
            </li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>

    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <?= $this->session->flashdata('success_msg') ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <?= $this->session->flashdata('error_msg') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('am/tambahkegiatan') ?>" method="post">

        <div class="row">

            <!-- FORM INPUT -->
            <div class="col-lg-7">

                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        Form Tambah Kegiatan
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Area <span class="text-danger">*</span></label>
                            <select class="form-control" name="area" id="area_select" required>
                                <option value="">Pilih Area</option>
                                <?php foreach($area as $row): ?>
                                    <option value="<?= $row->uuid ?>">
                                        <?= $row->nama_area ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mesin <span class="text-danger">*</span></label>
                            <select class="form-control" name="mesin" id="mesin_select" required>
                                <option value="">Pilih Mesin</option>
                            </select>
                        </div>

                        <hr>

                        <label>Daftar Kegiatan <span class="text-danger">*</span></label>

                        <div id="kegiatan-wrapper">

                            <div class="input-group mb-2 kegiatan-row">
                                <input type="text" name="kegiatan[]" class="form-control" placeholder="Masukkan kegiatan">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-row">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <button type="button" id="add-row" class="btn btn-sm btn-info mb-3">
                            <i class="fa fa-plus"></i> Tambah Baris
                        </button>

                        <hr>

                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan
                        </button>

                        <a href="<?= base_url('am/data') ?>" class="btn btn-danger">
                            Batal
                        </a>

                    </div>
                </div>

            </div>


            <!-- PREVIEW EXISTING -->
            <div class="col-lg-5">

                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        Kegiatan Existing Mesin
                    </div>

                    <div class="card-body">

                        <div id="existing-kegiatan">
                            <p class="text-muted mb-0">
                                Pilih mesin untuk melihat kegiatan existing
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>


<script>
    $(document).ready(function(){

    // LOAD MESIN BERDASARKAN AREA
        $('#area_select').change(function(){

            let area_uuid = $(this).val();

            $('#mesin_select').html('<option>Loading...</option>');

            $.get('<?= base_url('am/get_mesin_by_area/') ?>' + area_uuid, function(res){

                let data = JSON.parse(res);

                let html = '<option value="">Pilih Mesin</option>';

                data.forEach(function(item){
                    html += `<option value="${item.uuid}">${item.nama_mesin}</option>`;
                });

                $('#mesin_select').html(html);

                $('#existing-kegiatan').html(`
                <p class="text-muted mb-0">
                    Pilih mesin untuk melihat kegiatan existing
                </p>
                `);

            });

        });

        $('#mesin_select').change(function(){

            let mesin_uuid = $(this).val();

            $('#existing-kegiatan').html('<p>Loading...</p>');

            $.get('<?= base_url('am/get_kegiatan_by_mesin/') ?>' + mesin_uuid, function(res){

                let data = JSON.parse(res);

                if(data.length === 0){
                    $('#existing-kegiatan').html(`
                <p class="text-danger mb-0">
                    Belum ada kegiatan untuk mesin ini
                </p>
                    `);
                    return;
                }

                let html = '';

                data.forEach(function(item){
                    html += `
                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                    <span>${item.kegiatan}</span>

                    <a href="<?= base_url('am/delete_kegiatan/') ?>${item.uuid}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">
                       <i class="fa fa-trash"></i>
                    </a>
                </div>
                    `;
                });

                html += `
            <div class="card-footer mt-3">
                    Total:
                    <span class="badge badge-pill badge-primary">
                        ${data.length}
                    </span>
              
            </div>
                `;

                $('#existing-kegiatan').html(html);

            });

        });

    // TAMBAH BARIS INPUT
        $('#add-row').click(function(){

            let row = `
            <div class="input-group mb-2 kegiatan-row">
                <input type="text" name="kegiatan[]" class="form-control" placeholder="Masukkan kegiatan">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            `;

            $('#kegiatan-wrapper').append(row);

        });


    // HAPUS BARIS
        $(document).on('click', '.remove-row', function(){

            if($('.kegiatan-row').length > 1){
                $(this).closest('.kegiatan-row').remove();
            }

        });

    });
</script>