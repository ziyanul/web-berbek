<div class="container-fluid">
    <table style="padding-top:10px; padding-bottom:10px;">


        <tr>
            <td style="border:none; width:20px; text-align:left; padding-left:10px;">Tanggal</td>
            <td style="border:none; width:80px; text-align:left;">&nbsp;: <?= $nav->tgl ?></td>
            <td style="border:none; text-align:left;"></td>
        </tr>
        <tr>
            <td style="border:none; width:20px; text-align:left; padding-left:10px;">Varian</td>
            <td style="border:none; width:80px; text-align:left;">&nbsp;: <?= $nav->nama_varian; ?></td>
            <td style="border:none; width:800px; text-align:left;"></td>
        </tr>

    </table>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead style="text-align: center; font-weight: bold;">

                <tr>
                    <td rowspan="2">No</td>
                    <td style="text-align: left;" rowspan="2">&nbsp;Kode Produk</td>
                    <td colspan="2">Jam</td>
                    <td width="10%" rowspan="2">Jumlah
                        Manpower</td>
                    <td colspan="2">Jumlah</td>
                    <td style="text-align:center;" colspan="<?= count(array_unique(array_column($total_badpro, 'nama_badpro'))); ?>">Bad
                        Produk (Kg)</td>

                    <td rowspan="2">Keterangan</td>
                </tr>
                <tr>
                    <td>Mulai</td>
                    <td>Selesai</td>
                    <td>Box</td>
                    <td>Kg</td>
                    <?php
                    $unique_badpro = [];
                    foreach ($badpro_headers as $bp) :
                        if (!in_array($bp->nama_badpro, $unique_badpro)) {
                            $unique_badpro[] = $bp->nama_badpro;
                    ?>
                            <td><?= $bp->nama_badpro; ?></td>
                    <?php
                        }
                    endforeach;
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data_kode as $row) : ?>
                    <tr>
                        <td style="text-align:center;"><?= $no; ?></td>
                        <td>&nbsp;<?= $row->kode_prod; ?></td>
                        <td style="text-align:center;"><?= date('H:i', strtotime($row->jam_mulai)); ?>
                        </td>
                        <td style="text-align:center;">
                            <?= date('H:i', strtotime($row->jam_selesai)); ?></td>
                        <td style="text-align:center;"><?= $row->jml_mp; ?></td>
                        <td style="text-align:center;"><?= number_format($row->jumlah_box, 0, ',', '.'); ?></td>
                        <td style="text-align:center;"><?= number_format($row->jumlah_kg, 2, ',', '.'); ?></td>
                        <?php
                        foreach ($unique_badpro as $badpro_name) :
                            $badpro_val = array_filter($badpro_headers, function ($b) use ($row, $badpro_name) {
                                return $b->filkar_uuid === $row->uuid && $b->nama_badpro === $badpro_name;
                            });
                        ?>
                            <td style="text-align:center;">
                                <?= !empty($badpro_val) ? number_format(current($badpro_val)->jumlah, 2, ',', '.') : '-'; ?></td>
                        <?php endforeach; ?>
                        <td style="text-align:center;">
                            <?= isset($row->keterangan) && !empty($row->keterangan) ? $row->keterangan : '-'; ?>
                        </td>

                    </tr>
                <?php
                    $no++;
                endforeach;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-center align-middle">Total</th>
                    <th class="text-center align-middle">
                        <?= isset($total->total_box) ? number_format($total->total_box, 0, ',', '.') : 0; ?>
                    </th>
                    <th class="text-center align-middle">
                        <?= isset($total->total_kg) ? number_format($total->total_kg, 2, ',', '.') : 0; ?>
                    </th>
                    <?php foreach ($unique_badpro as $badpro_name) : ?>
                        <?php
                        $badpro_total = array_filter($total_badpro, function ($b) use ($badpro_name) {
                            return $b->nama_badpro === $badpro_name;
                        });
                        ?>
                        <th class="text-center align-middle">
                            <?= !empty($badpro_total) ? number_format(current($badpro_total)->total_badpro, 2, ',', '.') : '0,00'; ?>
                        </th>
                    <?php endforeach; ?>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<br><br>
<table width="100%">
    <tr>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Dibuat</b></td>
        <td style="border: none; width: 80px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Disetujui</b></td>
        <td style="border: none; width: 80px;"></td>
        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Diketahui</b></td>
    </tr>
    <tr>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $row->fullname; ?></td>
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $row->leader; ?></td>
        <td style="height: 50px; border: none; width: 80px;"></td>
        <td style="text-align: center; height: 50px; width: 200px;"><?= $row->spv; ?></td>
    </tr>
    <tr>
        <td style="width: 200px; text-align: center;">Checker</td>
        <td style="border: none; width: 30px;"></td>
        <td style="width: 200px; text-align: center;">Koordinator</td>
        <td style="border: none; width: 30px;"></td>
        <td style="width: 200px; text-align: center;">Spv.Produksi</td>
    </tr>
</table>
</body>

</html>