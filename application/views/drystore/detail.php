<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Detail Dry Store</h1>
            <?php if (!empty($drystore)): ?>
                <div class="text-muted">
                    Tanggal:
                    <strong>
                        <?= date('d-m-Y', strtotime($drystore->tanggal)); ?>
                    </strong>
                </div>
            <?php endif; ?>
        </div>
        <a href="<?= base_url('drystore'); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
    <?php if (empty($detail)): ?>
        <div class="card shadow mb-4">
            <div class="card-body text-center text-muted">
                Tidak ada data transaksi Dry Store.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($detail as $type): ?>
            <?php
            $total_reject = 0;
            $onproduk = (float) $type['onproduk'];
            foreach ($type['items'] as $item) {
                $total_reject += (float) $item['berat'];
            }
            $total_use = $total_reject + $onproduk;
            $total_waste = 0;
            if ($total_use > 0) {
                $total_waste = ($total_reject / $total_use) * 100;
            }
            $total_yield = 100 - $total_waste;
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= htmlspecialchars($type['type_nama']); ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">
                                        No
                                    </th>
                                    <th>
                                        Jenis Waste
                                    </th>
                                    <th class="text-right">
                                        Reject
                                    </th>
                                    <th class="text-right">
                                        On Product
                                    </th>
                                    <th class="text-right">
                                        Use
                                    </th>
                                    <th class="text-right">
                                        %Waste
                                    </th>
                                    <th class="text-right">
                                        %Yield
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                ?>
                                <?php foreach ($type['items'] as $item): ?>
                                    <?php
                                    $berat = (float) $item['berat'];
                                    $onproduk_item = (float) $item['onproduk'];
                                    $penggunaan = $berat + $onproduk_item;
                                    $persen_waste = 0;
                                    if ($penggunaan > 0) {
                                        $persen_waste =
                                            ($berat / $penggunaan) * 100;
                                    }
                                    $persen_yield = 100 - $persen_waste;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= $no++; ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($item['waste_nama']); ?>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format(
                                                $berat,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format(
                                                $onproduk_item,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format(
                                                $penggunaan,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format(
                                                $persen_waste,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>%
                                        </td>
                                        <td class="text-right">
                                            <?= number_format(
                                                $persen_yield,
                                                2,
                                                ',',
                                                '.'
                                            ); ?>%
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td></td>
                                    <td>
                                        Total
                                    </td>
                                    <td class="text-right">
                                        <?= number_format(
                                            $total_reject,
                                            2,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format(
                                            $onproduk,
                                            2,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format(
                                            $total_use,
                                            2,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format(
                                            $total_waste,
                                            2,
                                            ',',
                                            '.'
                                        ); ?>%
                                    </td>
                                    <td class="text-right">
                                        <?= number_format(
                                            $total_yield,
                                            2,
                                            ',',
                                            '.'
                                        ); ?>%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>