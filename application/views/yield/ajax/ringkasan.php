<div class="table-responsive">
    <table class="table table-bordered table-sm mb-0">
        <thead class="thead-light">
            <tr>
                <th>Total Batch</th>
                <th>Adonan (Kg)</th>
                <th>Filkar Box</th>
                <th>Filkar Kg</th>
                <th>Sortasi</th>
                <th>Release</th>
                <th>Belum Sortir</th>
                <th>Yield Filkar</th>
                <th>Yield Release</th>
                <th>Rework Filkar</th>
                <th>Reject Filkar</th>
                <th>Rework Sortasi</th>
                <th>Reject Sortasi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ringkasan) { ?>
                <tr>
                    <td><?= number_format($ringkasan->total_batch) ?></td>
                    <td><?= number_format($ringkasan->adonan_formula, 2) ?></td>
                    <td><?= number_format($ringkasan->filkar_box) ?></td>
                    <td><?= number_format($ringkasan->filkar_kg, 2) ?></td>
                    <td><?= number_format($ringkasan->sortasi_box) ?></td>
                    <td><?= number_format($ringkasan->release_box) ?></td>
                    <td><?= number_format($ringkasan->blm_sortir) ?></td>
                    <td>
                        <span class="font-weight-bold text-primary">
                            <?= number_format($ringkasan->yield_formula, 2) ?>%
                        </span>
                    </td>
                    <td>
                        <span class="font-weight-bold text-success">
                            <?= number_format($ringkasan->yield_release, 2) ?>%
                        </span>
                    </td>
                    <td class="text-warning">
                        <?= number_format($ringkasan->filkar_rework, 2) ?>
                    </td>
                    <td class="text-danger">
                        <?= number_format($ringkasan->filkar_reject, 2) ?>
                    </td>
                    <td class="text-warning">
                        <?= number_format($ringkasan->sortasi_rework, 2) ?>
                    </td>
                    <td class="text-danger">
                        <?= number_format($ringkasan->sortasi_reject, 2) ?>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="13" class="text-center text-muted">
                        Tidak ada data.
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>