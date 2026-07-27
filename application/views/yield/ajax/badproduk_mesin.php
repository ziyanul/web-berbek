<?php
$mesin = $mesin ?? [];
$rows  = $rows ?? [];
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="140">
                    Mesin
                </th>
                <?php foreach ($badproduk as $bp) { ?>
                    <th>
                        <?= $bp->nama_badpro ?>
                    </th>
                <?php } ?>
                <th width="90">
                    TOTAL
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="font-weight-bold">
                        <?= $row->mesin ?>
                    </td>
                    <?php foreach ($badproduk as $bp) { ?>
                        <td class="text-center">
                            <?= number_format($row->{$bp->uuid}, 2) ?>
                        </td>
                    <?php } ?>
                    <td class="font-weight-bold text-center">
                        <?= number_format($row->total, 2) ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>
                    TOTAL
                </th>
                <?php
                $grand = 0;
                foreach ($badproduk as $bp) {
                    $total = 0;
                    foreach ($rows as $row) {
                        $total += $row->{$bp->uuid};
                    }
                    $grand += $total;
                ?>
                    <th class="text-center">
                        <?= number_format($total, 2) ?>
                    </th>
                <?php } ?>
                <th class="text-center">
                    <?= number_format($grand, 2) ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>