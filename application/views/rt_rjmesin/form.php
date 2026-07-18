
<div class="container-fluid">


        <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>
            <tr>
        <td style="border:none; width:20px; text-align:left; padding-left:10px;">Varian</td>
        <td style="border:none; width:80px; text-align:left;">: <?= $plan->varian; ?></td>
        <td style="border:none; width:800px; text-align:left;"></td>
        </tr>
        <tr>
        <td style="border:none; width:20px; text-align:left; padding-left:10px;">Tanggal</td>
        <td style="border:none; width:80px; text-align:left;">: <?= $plan->tgl ?></td>
        <td style="border:none; text-align:left;"></td>
        </tr>
        </tbody>
        </table><br>

    <div class="table-responsive">
       <table class="table table-bordered">
    <thead class="table table-bordered bg-info text-light text-center">
        <tr>
            <th rowspan="3">Mesin Filler</th>
            <th width="4" rowspan="3">Satuan</th>
            <th colspan="<?= count($badpro_headers) * count($batches) ?>">NOMOR BATCH</th>
            <th rowspan="3">Total</th>
            <th rowspan="3">Keterangan</th>
        </tr>
        <tr>
            <?php foreach ($batches as $batch):
                $batch = substr($batch, 5, 2);
                ?>
                <th colspan="<?= count($badpro_headers) ?>">Batch ke: <?= $batch ?></th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <?php foreach ($batches as $batch): ?>
                <?php foreach ($badpro_headers as $header): ?>
                    <th><?= $header->nama_badpro ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_mesin as $mesin_uuid => $mesin): ?>
            <tr>
                <td><?= $mesin['nama_mesin'] ?></td>
                <td>Kg</td>
                <?php 
                $row_total = 0; // Inisialisasi total untuk baris ini
                foreach ($batches as $batch): 
                    foreach ($badpro_headers as $header): 
                        $value = isset($mesin['batches'][$batch][$header->nama_badpro]) ? $mesin['batches'][$batch][$header->nama_badpro] : 0;
                        $row_total += (float) $value; // Hitung total baris
                ?>
                        <td><?= $value ?: '-' ?></td>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <td><?= number_format($row_total, 1, '.', ','); ?></td> <!-- Tampilkan total baris -->
                <td></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>Total</td>
            <td></td>
            <?php 
            $grand_total = 0; // Total keseluruhan
            foreach ($batches as $batch): 
                foreach ($badpro_headers as $header): 
                    $value = isset($totals[$batch][$header->nama_badpro]) ? $totals[$batch][$header->nama_badpro] : 0;
                    $grand_total += (float) $value; // Hitung total keseluruhan
            ?>
                    <td><?= $value ?: '-' ?></td>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <td><?= number_format($grand_total, 1, '.', ',') ?></td> <!-- Total keseluruhan -->
            <td></td>
        </tr>
    </tbody>
</table>

    

</div>
</div>

<br><br><table width="100%">
        <tr>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Dilaksanakan Oleh</b></td>
        <td style="border: none; width: 300px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Diverifikasi Oleh</b></td> 
        <td style="border: none; width: 300px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Disetujui Oleh</b></td> 
        </tr>
        <tr>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $plan->user_name ?></td>
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $plan->foreman_name ?></td> 
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $plan->spv_name ?></td> 
        </tr>
        <tr>
        <td style="width: 200px; text-align: center;">Checker</td>
        <td style="border: none; width: 30px;"></td>
        <td style="width: 200px; text-align: center;">Foreman/Lady</td> 
        <td style="border: none; width: 30px;"></td>
        <td style="width: 200px; text-align: center;">Spv.Produksi</td> 
        </tr>
        </table>
        </body></html>
