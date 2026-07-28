<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-dark">
            <tr>
                <th width="180">
                    Bad Produk
                </th>
                <th width="120">
                    Proses
                </th>
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
            <?php if (empty($rows)) { ?>
                <tr>
                    <td colspan="<?= count($varian) + 3 ?>"
                        class="text-center text-muted">
                        Tidak ada data.
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($rows as $row) { ?>
                    <tr>
                        <td>
                            <?= $row->nama_badpro ?>
                        </td>
                        <td>
                            <?php
                            if (stripos($row->proses, 'sortasi') !== false) {
                                echo 'Sortasi';
                            } else {
                                echo 'Filkar';
                            }
                            ?>
                        </td>
                        <?php foreach ($varian as $v) { ?>
                            <td class="text-center">
                                <?= number_format(
                                    $row->{$v->uuid} ?? 0,
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
                <th colspan="2">
                    TOTAL
                </th>
                <?php
                $grand_total = 0;
                foreach ($varian as $v) {
                    $total = 0;
                    foreach ($rows as $row) {
                        $total += $row->{$v->uuid} ?? 0;
                    }
                    $grand_total += $total;
                ?>
                    <th class="text-center">
                        <?= number_format(
                            $total,
                            2
                        ) ?>
                    </th>
                <?php } ?>
                <th class="text-center">
                    <?= number_format(
                        $grand_total,
                        2
                    ) ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>