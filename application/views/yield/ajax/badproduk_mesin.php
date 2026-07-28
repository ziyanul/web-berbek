<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="150">
                    Mesin
                </th>
                <?php foreach ($badproduk as $bp) { ?>
                    <th class="text-center">
                        <?= $bp->nama_badpro ?>
                    </th>
                <?php } ?>
                <th width="100">
                    TOTAL
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)) { ?>
                <tr>
                    <td colspan="<?= count($badproduk) + 2 ?>"
                        class="text-center text-muted">
                        Tidak ada data.
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($rows as $row) { ?>
                    <tr>
                        <td>
                            <?= $row->mesin ?>
                        </td>
                        <?php foreach ($badproduk as $bp) { ?>
                            <td class="text-center">
                                <?= number_format(
                                    $row->{$bp->uuid} ?? 0,
                                    2
                                ) ?>
                            </td>
                        <?php } ?>
                        <td class="text-center font-weight-bold">
                            <?= number_format(
                                $row->total ?? 0,
                                2
                            ) ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>
                    TOTAL
                </th>
                <?php
                $grand_total = 0;
                foreach ($badproduk as $bp) {
                    $total = 0;
                    foreach ($rows as $row) {
                        $total += $row->{$bp->uuid} ?? 0;
                    }
                    $grand_total += $total;
                ?>
                    <th class="text-center">
                        <?= number_format($total, 2) ?>
                    </th>
                <?php } ?>
                <th class="text-center">
                    <?= number_format($grand_total, 2) ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>