<div class="container-fluid">

    <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>
            <tr>
                <td style="border:none; width:20px; text-align:left; padding-left:10px;">Varian</td>
                <td style="border:none; width:80px; text-align:left;">: <?= $info->varian ?></td>
                <td style="border:none; width:800px; text-align:left;"></td>
            </tr>
            <tr>
                <td style="border:none; width:20px; text-align:left; padding-left:10px;">Tanggal</td>
                <td style="border:none; width:80px; text-align:left;">: <?= $info->tgl ?></td>
                <td style="border:none; text-align:left;"></td>
            </tr>
        </tbody>
    </table><br>

    <div class="table-responsive">
        <?php 
        $batch_chunks = array_chunk($masak_data, 5, true);
        $tableIndex = 1;
        foreach ($batch_chunks as $batchDataChunk) : ?>
            <table class="table table-bordered mt-3 mb-0">
                <thead class="table table-bordered bg-info">
                    <tr>
                        <th rowspan="2">KETERANGAN</th>
                        <th rowspan="2">Satuan</th>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <th colspan="<?= count($batchData); ?>">Batch <?= $batch; ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <?php foreach ($batchData as $data): ?>
                                <th>B <?= $data['batch']; ?> (<?= $data['masak']; ?>)</th>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="bg-info" style="text-align: left;">Jumlah Reject Per Cooking</th>
                        <th class="bg-info text-light">(Kg)</th>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <?php foreach ($batchData as $data): ?>
                                <td style="text-align: center;"><?= $data['rj_cooking']; ?></td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="bg-info text-light" style="text-align: left;">Total Reject</th>
                        <th class="bg-info text-light">(Kg)</th>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <td colspan="<?= count($batchData); ?>" style="text-align: center;">
                                <?= array_sum(array_column($batchData, 'rj_cooking')); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th class="bg-info text-light" style="text-align: left;">Jumlah Tray</th>
                        <th class="bg-info text-light">(Pcs)</th>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <?php foreach ($batchData as $data): ?>
                                <td style="text-align: center;"><?= $data['jml_tray']; ?></td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th colspan="2" class="bg-info text-light" style="text-align: left;">Dimasak di Chamber</th>
                        <?php foreach ($batchDataChunk as $batch => $batchData): ?>
                            <?php foreach ($batchData as $data): ?>
                                <td>Chamber <?= $data['MR_NOCHAM']; ?></td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
            <br>
            <?php 
            $tableIndex++;
        endforeach; ?>
    </div>
</div>
</div>
<small class="text-muted mt-0">
    Keterangan: <br>
    * B1 = batch 1 dan seterusnya <br>
    * 1(1) = batch 1 masak ke-1 dan seterusnya
</small>



<br><br><table width="100%">
    <tr>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Dibuat Oleh</b></td>
        <td style="border: none; width: 300px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Disetujui Oleh</b></td> 
        <td style="border: none; width: 300px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Mengetahui Oleh</b></td> 
    </tr>
    <tr>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $info->user ?></td>
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $info->kr_name ?></td> 
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $info->spv_name ?></td> 
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