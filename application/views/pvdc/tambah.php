<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Input Data Pemakaian PVDC & WIRE</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('area') ?>"><i class="fas fa-arrow-left"></i> Fokus
                    Area</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <?php if ($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?= $this->session->flashdata('success_msg') ?>
    </div>
    <br>
    <?php endif ?>
    <?php if ($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
    <br>
    <?php endif ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="user" action="<?= base_url('pvdc/tambah/') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Tanggal Produksi : </label>
                        <select name="planning_uuid" id="planning_uuid" class="form-control" required>
                            <?php foreach ($data as $row) : ?>
                            <option value="<?= $row->uuid_planning ?>">
                                <?= $row->tanggal ?> - <?= $row->nama_varian ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6">
                        <label class="form-label">Pemakaian PVDC (Roll) <span class="text-danger">*</span></label>
                        <input type="number" name="pvdc_input" id="pvdc" class="form-control" required>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Pemakaian Wire (Roll) <span class="text-danger">*</span></label>
                        <input type="number" name="wire_input" id="wire" class="form-control" required>
                    </div>

                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('pvdc') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>