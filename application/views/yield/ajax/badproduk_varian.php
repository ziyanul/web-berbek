<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="180">Bad Produk</th>
                <?php foreach ($varian as $v) { ?>
                    <th class="text-center">
                        <?= $v->varian ?>
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
                    <td>
                        <?= $row->nama_badpro ?>
                    </td>
                    <?php foreach ($varian as $v) { ?>
                        <td class="text-center">
                            <?= number_format($row->{$v->uuid}, 2) ?>
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
                <th>TOTAL</th>
                <?php
                $grand = 0;
                foreach ($varian as $v) {
                    $total = 0;
                    foreach ($rows as $r) {
                        $total += $r->{$v->uuid};
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