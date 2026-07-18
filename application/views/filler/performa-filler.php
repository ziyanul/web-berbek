<style>
    .summary-card {
        border-left: 4px solid #4e73df;
        border-radius: 10px;
        background: #fff;
        padding: 16px 18px;
        box-shadow: 0 .15rem 1rem 0 rgba(58, 59, 69, .08);
        height: 100%;
    }
    .summary-title {
        font-size: 13px;
        color: #858796;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .summary-value {
        font-size: 22px;
        font-weight: 700;
        color: #2e2f37;
    }
    .table thead th {
        vertical-align: middle !important;
        text-align: center;
    }
    .table tbody td {
        vertical-align: middle !important;
    }
    .table td,
    .table th {
        padding: 12px 10px;
    }
    .performance-badge {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 20px;
        font-weight: 700;
        display: inline-block;
        min-width: 85px;
        text-align: center;
    }
    .perf-danger {
        background: #fde8e8;
        color: #c0392b;
    }
    .perf-warning {
        background: #fff4db;
        color: #d68910;
    }
    .perf-success {
        background: #eafaf1;
        color: #1e8449;
    }
    .action-btn {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #4e73df;
    }
    .sub-title {
        font-size: 16px;
        font-weight: 600;
        color: #5a5c69;
    }
    .table-summary-row {
        background: #f8f9fc;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="mb-3">
        <h2 class="h3 mb-1 text-gray-800 font-weight-bold">Performa Mesin Filler</h2>
        <?php if (!empty($data)) : ?>
            <h3 class="h5 text-primary font-weight-bold">
                Varian <?= $data[0]->vrn ?> / <?= tanggal_indo($data[0]->tanggal); ?>
            </h3>
        <?php else : ?>
            <h3 class="h5 text-primary font-weight-bold">
                Data performa belum tersedia
            </h3>
        <?php endif; ?>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('filler/planning/') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Planning Produksi
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"> Performa Filler</li>
        </ol>
    </nav>
    <form method="get">
        <div class="row mb-4">
            <div class="col-md-4">
                <label>Mesin</label>
                <select name="mesin_uuid"
                    class="form-control"
                    onchange="this.form.submit()">
                    <option value="">Semua Mesin</option>
                    <?php foreach ($mesin as $m) { ?>
                        <option
                            value="<?= $m->uuid ?>"
                            <?= $this->input->get('mesin_uuid') == $m->uuid ? 'selected' : '' ?>>
                            <?= $m->nama_mesin ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Kode Batch</label>
                <select name="batch_uuid"
                    class="form-control"
                    onchange="this.form.submit()">
                    <option value="">Semua Batch</option>
                    <?php foreach ($batch as $b) { ?>
                        <option
                            value="<?= $b->uuid ?>"
                            <?= $this->input->get('batch_uuid') == $b->uuid ? 'selected' : '' ?>>
                            <?= $b->kode_batch ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </form>
    <?php
    $total_target = 0;
    $total_counters = 0;
    $total_losses = 0;
    $total_downtime = 0;
    $total_performa = 0;
    $total_quality_persen = 0;
    $count = count($data);
    foreach ($data as $row) {
        $total_target += $row->target;
        $total_counters += $row->counters;
        $total_losses += $row->total_losses;
        $total_downtime += $row->total_downtime;
        $total_performa += $row->performa;
        $total_quality_persen += $row->quality_persen;
    }
    $average_performa = $total_target > 0 ? ($total_counters / $total_target) * 100 : 0;
    $average_quality_persen = $count > 0 ? $total_quality_persen / $count : 0;
    ?>
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="summary-card">
                <div class="summary-title">Total Target</div>
                <div class="summary-value"><?= number_format($total_target, 0, ',', '.'); ?> <small>pcs</small></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card" style="border-left-color:#1cc88a;">
                <div class="summary-title">Total Actual</div>
                <div class="summary-value"><?= number_format($total_counters, 0, ',', '.'); ?> <small>pcs</small></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card" style="border-left-color:#f6c23e;">
                <div class="summary-title">Rata-rata Performa</div>
                <div class="summary-value"><?= number_format($average_performa, 2, ',', '.'); ?> <small>%</small></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card" style="border-left-color:#e74a3b;">
                <div class="summary-title">Rata-rata Reject</div>
                <div class="summary-value"><?= number_format($average_quality_persen, 2, ',', '.'); ?> <small>%</small></div>
            </div>
        </div>
    </div>
    <!-- Table Card -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100">
                    <thead class="bg-info text-light">
                        <tr>
                            <th width="180">Mesin</th>
                            <th width="150">Target</th>
                            <th width="150">Actual</th>
                            <th width="150">Performa</th>
                            <th width="150">Lost Time</th>
                            <th width="150">Down Time</th>
                            <th width="150">Reject</th>
                            <!-- <th style="min-width: 120px;">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row) {
                            if ($row->performa < 85) {
                                $perf_class = 'perf-danger';
                            } elseif ($row->performa >= 85 && $row->performa <= 95) {
                                $perf_class = 'perf-warning';
                            } else {
                                $perf_class = 'perf-success';
                            }
                        ?>
                            <tr>
                                <td class="font-weight-bold"><?= $row->nama_mesin ?></td>
                                <td class="text-center"><?= number_format($row->target, 0, ',', '.'); ?> pcs</td>
                                <td class="text-center"><?= number_format($row->counters, 0, ',', '.'); ?> pcs</td>
                                <td class="text-center">
                                    <span class="performance-badge <?= $perf_class ?>">
                                        <?= number_format($row->performa, 2, ',', '.'); ?>%
                                    </span>
                                </td>
                                <td class="text-center"><?= number_format($row->total_losses, 0, ',', '.'); ?> Menit</td>
                                <td class="text-center"><?= number_format($row->total_downtime, 0, ',', '.'); ?> Menit</td>
                                <td class="text-center"><?= number_format($row->quality_persen, 2, ',', '.'); ?>%</td>
                                <!-- <td class="text-center">
                                    <a href="<?= base_url('filler/tambahdowntime/' . $row->speed_uuid); ?>" 
                                     class="btn btn-sm btn-success shadow-sm btn-block"
                                     data-toggle="tooltip" title="Tambah Downtime">
                                     <i class="fa fa-plus"></i> Downtime
                                 </a>
                                 <a href="<?= base_url('filler/tambahquality/' . $row->uuid . '/' . $row->mesin_uuid); ?>" 
                                     class="btn btn-sm btn-warning shadow-sm btn-block"
                                     data-toggle="tooltip" title="Tambah Reject">
                                     <i class="fa fa-exclamation-triangle text-white"></i> Riject
                                 </a>
                             </td> -->
                            </tr>
                        <?php } ?>
                        <tr class="table-summary-row font-weight-bold">
                            <td class="text-center">Jumlah</td>
                            <td class="text-center"><?= number_format($total_target, 0, ',', '.'); ?> pcs</td>
                            <td class="text-center"><?= number_format($total_counters, 0, ',', '.'); ?> pcs</td>
                            <td class="text-center"><?= number_format($average_performa, 2, ',', '.'); ?>%</td>
                            <td class="text-center"><?= number_format($total_losses, 0, ',', '.'); ?> Menit</td>
                            <td class="text-center"><?= number_format($total_downtime, 0, ',', '.'); ?> Menit</td>
                            <td class="text-center"><?= number_format($average_quality_persen, 2, ',', '.'); ?>%</td>
                            <!-- <td></td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <a href="<?= base_url('filler/planning') ?>" class="btn btn-danger px-4 shadow-sm">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tooltip -->
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<!-- End of Main Content -->