<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Beranda</h1>
    </div>

    <?php
    $type = $this->session->userdata('type');
    $subrole = $this->session->userdata('subrole');
    ?>

    <!-- =========================
         TOP SUMMARY CARDS
    ========================== -->
    <div class="row">
        <?php if (is_produksi() || is_admin() || is_engineering() || is_warehouse()) { ?>
            <!-- Sparepart -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow border-left-secondary h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase">
                                Sparepart
                            </div>
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-warning font-weight-bold">
                                <i class="fas fa-exclamation-triangle"></i> Warning
                            </span>
                            <span class="font-weight-bold text-gray-800">
                                <?= $monitor['warning']; ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-danger font-weight-bold">
                                <i class="fas fa-times-circle"></i> Over Lifetime
                            </span>
                            <span class="font-weight-bold text-gray-800">
                                <?= $monitor['over_lifetime']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (is_produksi() || is_admin()) { ?>
            <!-- ISO/TS -->
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow border-left-danger h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    ISO/TS
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                    <?= $gmp; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (is_produksi() || is_admin() || is_engineering()) { ?>
            <!-- Maintenance -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-1">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">PREVENTIVE MAINTENANCE
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                    <?= $maintenance; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tools fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (is_produksi() || is_admin()) { ?>
            <!-- Autonomous Maintenance -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Autonomous Maintenance
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                    <?= $auto; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cogs fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Permintaan Sparepart -->

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Permintaan Sparepart
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $pengajuan; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- =========================
         PERFORMA MESIN
    ========================== -->
    <!-- <?php if (is_produksi() || is_admin()) { ?> -->

    <?php
    $total_target = 0;
    $total_counters = 0;
    $total_losses = 0;
    $total_downtime = 0;
    $total_performa = 0;
    $total_quality_persen = 0;
    $count = count($performa_data['target']);

    foreach ($performa_data['target'] as $row) {
        $total_target += $row->target;
        $total_counters += $row->counters;
        $total_losses += $row->total_losses;
        $total_downtime += $row->total_downtime;
        $total_performa += $row->performa;
        $total_quality_persen += $row->quality_persen;
    }

    $average_performa = $total_target > 0 ? ($total_counters / $total_target) * 100 : 0;
    $average_quality_persen = $count > 0 ? $total_quality_persen / $count : 0;
    $average_losses = $count > 0 ? $total_losses / $count : 0;
    $average_downtime = $count > 0 ? $total_downtime / $count : 0;
    ?>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow border-0">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div class="mb-2">
                            <h4 class="font-weight-bold text-info mb-1">Performa Mesin Filler</h4>
                        </div>

                        <div style="min-width: 280px;">
                            <label for="uuidDropdown" class="small font-weight-bold text-muted mb-1">
                                Pilih Tanggal Produksi
                            </label>
                            <select class="form-control form-control-sm" name="uuid" id="uuidDropdown">
                                <?php foreach ($performa_data['uuids'] as $item): ?>
                                    <option value="<?= $item->uuid ?>" <?= ($performa_data['plan'] && $item->uuid == $performa_data['plan']->uuid) ? 'selected' : '' ?>>
                                        <?= $item->tanggal ?> - <?= $item->varian ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4" id="summaryCards">
                        <div class="col-md-2 col-6 mb-2">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body p-2">
                                    <div class="text-xs text-primary font-weight-bold text-uppercase">Target</div>
                                    <div class="font-weight-bold text-gray-800" id="sumTarget">
                                        <?= number_format($total_target, 0, ',', '.'); ?> pcs
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-6 mb-2">
                            <div class="card border-left-success shadow-sm h-100 py-2">
                                <div class="card-body p-2">
                                    <div class="text-xs text-success font-weight-bold text-uppercase">Actual</div>
                                    <div class="font-weight-bold text-gray-800" id="sumActual">
                                        <?= number_format($total_counters, 0, ',', '.'); ?> pcs
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-6 mb-2">
                            <div class="card border-left-info shadow-sm h-100 py-2">
                                <div class="card-body p-2">
                                    <div class="text-xs text-info font-weight-bold text-uppercase">Performa</div>
                                    <div class="font-weight-bold text-gray-800" id="sumPerforma">
                                        <?= number_format($average_performa, 2, ',', '.'); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-2">
                            <div class="card border-left-warning shadow-sm h-100 py-2">
                                <div class="card-body p-2">
                                    <div class="text-xs text-warning font-weight-bold text-uppercase">Lost Time</div>
                                    <div class="font-weight-bold text-gray-800" id="sumLosses">
                                        <?= number_format(($total_losses / 60), 2, ',', '.'); ?> Jam
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-2">
                            <div class="card border-left-danger shadow-sm h-100 py-2">
                                <div class="card-body p-2">
                                    <div class="text-xs text-danger font-weight-bold text-uppercase">Down Time</div>
                                    <div class="font-weight-bold text-gray-800" id="sumDowntime">
                                        <?= number_format($total_downtime, 0, ',', '.'); ?> Menit
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" width="100%">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th class="font-weight-bold align-middle text-center" width="220">Mesin</th>
                                    <th class="font-weight-bold align-middle text-center">Target</th>
                                    <th class="font-weight-bold align-middle text-center">Actual</th>
                                    <th class="font-weight-bold align-middle text-center">Performa</th>
                                    <th class="font-weight-bold align-middle text-center">Lost Time (Menit)</th>
                                    <th class="font-weight-bold align-middle text-center">Down Time (Menit)</th>
                                </tr>
                            </thead>
                            <tbody id="performaTable">
                                <?php if (!empty($performa_data['target'])): ?>
                                    <?php foreach ($performa_data['target'] as $row): ?>
                                        <tr>
                                            <td class="align-middle"><?= $row->nama_mesin ?></td>
                                            <td class="align-middle text-center"><?= number_format($row->target, 0, ',', '.'); ?> pcs</td>
                                            <td class="align-middle text-center"><?= number_format($row->counters, 0, ',', '.'); ?> pcs</td>
                                            <td class="align-middle text-center"><?= number_format($row->performa, 2, ',', '.'); ?> %</td>
                                            <td class="align-middle text-center"><?= number_format($row->total_losses, 0, ',', '.'); ?> Menit</td>
                                            <td class="align-middle text-center"><?= number_format($row->total_downtime, 0, ',', '.'); ?> Menit</td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr style="background-color: #f8f9fc;">
                                        <td class="font-weight-bold align-middle text-center">Jumlah</td>
                                        <td class="font-weight-bold align-middle text-center"><?= number_format($total_target, 0, ',', '.'); ?> pcs</td>
                                        <td class="font-weight-bold align-middle text-center"><?= number_format($total_counters, 0, ',', '.'); ?> pcs</td>
                                        <td class="font-weight-bold align-middle text-center"><?= number_format($average_performa, 2, ',', '.'); ?> %</td>
                                        <td class="font-weight-bold align-middle text-center"><?= number_format($total_losses, 0, ',', '.'); ?> Menit</td>
                                        <td class="font-weight-bold align-middle text-center"><?= number_format($total_downtime, 0, ',', '.'); ?> Menit</td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Data performa belum tersedia</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- <?php } ?> -->
</div>

<script src="<?= base_url('assets/vendor/chart.js/Chart.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#uuidDropdown").change(function () {
            var selectedUUID = $(this).val();

            $.ajax({
                url: "<?= base_url('home/get_performa_by_uuid') ?>",
                type: "GET",
                data: { uuid: selectedUUID },
                dataType: "json",

                beforeSend: function () {
                    $("#performaTable").html(`
                        <tr>
                            <td colspan="6" class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data...
                            </td>
                        </tr>
                    `);
                },

                success: function (data) {
                    var total_target = 0,
                    total_counters = 0,
                    total_losses = 0,
                    total_downtime = 0,
                    total_performa = 0;

                    var count = data.length;
                    var tableContent = '';

                    if (count > 0) {
                        data.forEach(function (row) {
                            total_target += parseFloat(row.target) || 0;
                            total_counters += parseFloat(row.counters) || 0;
                            total_losses += parseFloat(row.total_losses) || 0;
                            total_downtime += parseFloat(row.total_downtime) || 0;
                            total_performa += parseFloat(row.performa) || 0;

                            tableContent += `
                                <tr>
                                    <td class="align-middle">${row.nama_mesin}</td>
                                    <td class="align-middle text-center">${formatNumber(row.target)} pcs</td>
                                    <td class="align-middle text-center">${formatNumber(row.counters)} pcs</td>
                                    <td class="align-middle text-center">${formatNumber(row.performa, 2)} %</td>
                                    <td class="align-middle text-center">${formatNumber(row.total_losses)} Menit</td>
                                    <td class="align-middle text-center">${formatNumber(row.total_downtime)} Menit</td>
                                </tr>
                            `;
                        });

                        var avg_performa = total_target > 0 ? ((total_counters / total_target) * 100).toFixed(2) : 0;

                        tableContent += `
                            <tr style="background-color: #f8f9fc;">
                                <td class="font-weight-bold align-middle text-center">Jumlah</td>
                                <td class="font-weight-bold align-middle text-center">${formatNumber(total_target)} pcs</td>
                                <td class="font-weight-bold align-middle text-center">${formatNumber(total_counters)} pcs</td>
                                <td class="font-weight-bold align-middle text-center">${formatNumber(avg_performa, 2)} %</td>
                                <td class="font-weight-bold align-middle text-center">${formatNumber(total_losses)} Menit</td>
                                <td class="font-weight-bold align-middle text-center">${formatNumber(total_downtime)} Menit</td>
                            </tr>
                        `;

                        // update summary cards
                        $("#sumTarget").text(formatNumber(total_target) + " pcs");
                        $("#sumActual").text(formatNumber(total_counters) + " pcs");
                        $("#sumPerforma").text(formatNumber(avg_performa, 2) + "%");
                        $("#sumLosses").text(formatNumber(total_losses) + " Menit");
                        $("#sumDowntime").text(formatNumber(total_downtime) + " Menit");

                    } else {
                        tableContent = "<tr><td colspan='6' class='text-center'>Data tidak ditemukan</td></tr>";

                        $("#sumTarget").text("0 pcs");
                        $("#sumActual").text("0 pcs");
                        $("#sumPerforma").text("0%");
                        $("#sumLosses").text("0 Menit");
                        $("#sumDowntime").text("0 Menit");
                    }

                    $("#performaTable").html(tableContent);
                },

                error: function () {
                    $("#performaTable").html(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                Gagal mengambil data!
                            </td>
                        </tr>
                    `);
                }
            });
});

function formatNumber(num, decimals = 0) {
    return Number(num).toLocaleString("id-ID", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

});
</script>