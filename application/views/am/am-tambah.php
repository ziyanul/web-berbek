<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Planning Autonomous Maintenance</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('am/tpm') ?>"><i class="fas fa-arrow-left mr-2"></i>Planning AM</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
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
            <form class="user" action="<?= base_url('am/'.($this->uri->segment(2)=='tpm'?'tpm/':'').'tambah/') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area">
                            <option disabled selected>Pilih Area</option>
                            <?php
                            foreach ($area as $a) {
                                ?>
                                <option value="<?= $a->uuid;?>" <?= set_select('area', $a->uuid);?>><?= $a->nama_area;?></option>
                                <?php
                            }
                            ?>
                        </select>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Mesin <span class="text-danger">*</span></label>
                        <select class="form-control <?= form_error('mesin') ? 'invalid' : '' ?>" name="mesin">
                            <option disabled selected>Pilih mesin</option>
                        </select>
                        
                    </div>
                </div>
                
                <div class="row">
                    <table class="table table-bordered" id="table-kegiatan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Jadwal</th>
                                <th>Target</th>
                                <th>Terakhir dikerjakan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url($this->uri->segment(2)=='tpm'?'am/tpm':'am') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('select[name="area"]').change(function() {
            var area_uuid = $(this).val();

            $.get('<?= base_url('am/get_mesin_by_area/'); ?>' + area_uuid, function(res) {
                var result = JSON.parse(res);
                var elem = '<option disabled selected>Pilih Mesin</option>';

                result.forEach(function(val) {
                    elem += '<option value="' + val.uuid + '">' + val.nama_mesin + '</option>';
                })

                $('select[name="mesin"]').html(elem);
            })
        })

        $('select[name="mesin"]').change(function () {

            let mesin = $(this).val();

            $.get('<?= base_url("am/get_kegiatan_available/") ?>' + mesin, function (res) {

                let data = JSON.parse(res);

                let html = '';

                data.forEach((v, i) => {
                    html += `
            <tr>
                <td>${i+1}</td>
                <td>
                    ${v.kegiatan}
                    <input type="hidden" name="items[${i}][kegiatan_uuid]" value="${v.uuid}">
                    <input type="hidden" name="items[${i}][mesin_uuid]" value="${mesin}">
                </td>
                <td>
                    <select name="items[${i}][jadwal]" class="form-control">
                        <option value="1">Planning Produksi</option>
                        <option value="2">Counter filler</option>
                        <option value="3">Harian</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${i}][target]" class="form-control">
                </td>
                        <td>
        <input type="date" name="items[${i}][dikerjakan]" class="form-control">
    </td>
                    </tr>`;
                });

                $('#table-kegiatan tbody').html(html);
            });
        });
    })


    
</script>

