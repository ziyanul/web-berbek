<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ubah Kegiatan ISO/TS</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('gmp/data') ?>"><i class="fas fa-arrow-left"></i> Data Iso/Ts</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-body">
        <form class="user" action="<?= base_url('gmp/editkegiatan/'.$kegiatan->uuid) ?>" method="post">
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kegiatan" class="form-control <?= form_error('kegiatan') ? 'invalid' : '' ?> " placeholder="" value="<?= $kegiatan->kegiatan; ?>">
                    <div class="invalid-feedback <?= !empty(form_error('kegiatan')) ? 'd-block':'';?>">
                        <?= form_error('kegiatan') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <i class="fa fa-plus-circle mt-3" style="color: #07e203; cursor: pointer;" onclick="addChemicalInputs()"></i> Larutan Chemical
                </div>
            </div>
            <div id="chemical-inputs-container" class="row mt-3">
                <!-- Chemical inputs will be appended here -->
            </div>
            <div class="row mt-5" >
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-md btn-success mr-2">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('gmp/data') ?>" class="btn btn-md btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


</div>



<script>
    function addChemicalInputs() {
    var newInputHtml = `
    <div class="row ml-3">
        <div class="col-sm-8">
            <label class="form-label">Kode Chemical:</label>
            <select class="form-control <?= form_error('kode') ? 'invalid' : '' ?>" name="kode[]">
                <option disabled selected>Pilih Kode Chemical</option>
                <?php foreach ($persen as $per): ?>
                    <option value="<?= $per->uuid;?>" <?= set_select('kode', $per->uuid);?>><?= $per->kode_chemical;?> - <?= $per->persentase;?> <?= $per->satuan;?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-8">
            <label class="form-label">Target Penggunaan Larutan:</label>
            <input type="number" name="larutan_used[]" class="form-control" placeholder="">
        </div>
    </div>
    `;
    $('#chemical-inputs-container').append(newInputHtml);
}

</script>
