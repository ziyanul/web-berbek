<style>
    .info-card {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        padding: 16px 18px;
        height: 100%;
    }

    .info-label {
        font-size: 13px;
        color: #858796;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 18px;
        font-weight: 700;
        color: #2e2f37;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #4e73df;
    }

    .summary-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 700;
        background: #fdecea;
        color: #c0392b;
    }

    .custom-input {
        height: 46px;
        border-radius: 8px;
    }

    .readonly-input {
        background: #f8f9fc !important;
        font-weight: 700;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 font-weight-bold">Reject Mesin Filler</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('filler/performance/'. $quality->t_planning_uuid) ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Performa Filler
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Reject</li>
        </ol>
    </nav>

    <!-- Info Header -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="info-card">
                <div class="info-label">Tanggal Produksi</div>
                <div class="info-value"><?= tanggal_indo($quality->tgl) ?></div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="info-card">
                <div class="info-label">Varian</div>
                <div class="info-value"><?= $quality->vrn ?></div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="info-card">
                <div class="info-label">Mesin</div>
                <div class="info-value"><?= $quality->mesin ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4 border-left-danger">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                <h5 class="mb-0 form-section-title">Input Reject Produksi</h5>
            </div>

            <form class="user" action="<?= base_url('filler/tambahquality/'. $quality->t_planning_uuid .'/'.$quality->t_sensor_device_id) ?>" method="post">

                <div class="mb-4">
                    <label class="form-label font-weight-bold d-block mb-2">Jumlah Reject Sebelumnya</label>
                    <span class="summary-badge"><?= number_format($quality->quality, 0, ',', '.'); ?> Pcs</span>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Berat KG</label>
                        <input type="text" id="berat_kg" name="berat" 
                               class="form-control custom-input <?= form_error('berat') ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: 1.25" 
                               value="<?= set_value('berat'); ?>">
                        <small class="text-muted">Gunakan titik untuk desimal. Contoh: 0.75</small>
                        <div class="invalid-feedback">
                            <?= form_error('berat') ?>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label font-weight-bold">Jumlah PCS</label>
                        <input type="text" id="jumlah_pcs" name="jumlah" 
                               class="form-control custom-input readonly-input <?= form_error('jumlah') ? 'is-invalid' : '' ?>" 
                               value="" readonly>
                        <small class="text-muted">Hasil hitung otomatis dari berat KG</small>
                        <div class="invalid-feedback">
                            <?= form_error('jumlah') ?>
                        </div>
                    </div>
                </div>

                <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){ ?>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label font-weight-bold">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" 
                                   class="form-control custom-input <?= form_error('keterangan') ? 'is-invalid' : '' ?>" 
                                   placeholder="Keterangan tambahan reject..." 
                                   value="<?= set_value('keterangan', $quality->keterangan); ?>">
                            <div class="invalid-feedback d-block">
                                <?= form_error('keterangan') ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-success px-4 shadow-sm mr-2">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>

                        <a href="<?= base_url('filler/performance/'.$quality->t_planning_uuid) ?>" class="btn btn-danger px-4 shadow-sm">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

<script>
    var inputBeratKG = document.getElementById('berat_kg');
    var inputJumlahPCS = document.getElementById('jumlah_pcs');

    inputBeratKG.addEventListener('input', function() {
        var beratKG = parseFloat(inputBeratKG.value);
        var varian = <?= $quality->varian ?>;
        var quality = <?= $quality->quality ?>;
        var jumlahPCS = 0;

        if (!isNaN(beratKG)) {
            if (varian == 1) {
                jumlahPCS = beratKG / 0.0125;
            } else if (varian == 2) {
                jumlahPCS = beratKG / 0.021;
            }

            jumlahPCS += quality;
            inputJumlahPCS.value = Math.round(jumlahPCS);
        } else {
            inputJumlahPCS.value = '';
        }
    });
</script>