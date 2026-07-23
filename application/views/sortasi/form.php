<div class="container-fluid">
    <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>
            <tr>
                <td style="border:none; width:50px; text-align:left; padding-left:10px;">Tanggal</td>
                <td style="border:none; width:5px; text-align:left;">:</td>
                <td style="border:none; text-align:left;"><?= $get_tanggal ?></td>
                <td style="border:none; width:1000px; text-align:left;"></td>
            </tr>
            <tr>
                <td style="border:none; width:20px; text-align:left; padding-left:10px;">Shift</td>
                <td style="border:none; width:5px; text-align:left;">:</td>
                <td style="border:none; text-align:left;"><?= $shift_name ?></td>
                <td style="border:none; width:1000px; text-align:left;"></td>
            </tr>
        </tbody>
    </table><br>
    <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="table bg-info text-light text-center">
                <tr >
                    <th class="align-middle" rowspan="3">Varian</th>
                    <th class="align-middle" rowspan="3">Kode Batch</th>
                    <th class="align-middle" rowspan="3" width="50">Jumlah WIP (box)</th>
                    <th class="align-middle" colspan="2" rowspan="2">Jam</th>
                    <th class="align-middle" rowspan="3" width="50">Jumlah Manpower</th>
                    <th class="align-middle" rowspan="3" width="50">Jumlah di Release (box)</th>
                    <th class="align-middle" colspan="<?= array_sum(array_map(fn($b) => count($b['sub_badpro']) ?: 1, $badpro_with_subbadpro)); ?>">Bad Products</th>
                    <th class="align-middle" rowspan="3">Keterangan</th>
                </tr>
                <tr>
                    <?php foreach ($badpro_with_subbadpro as $badpro_uuid => $badpro): ?>
                        <?php $sub_count = count($badpro['sub_badpro']); ?>
                        <?php if ($sub_count > 0): ?>
                            <th class="align-middle" colspan="<?= $sub_count; ?>"><?= $badpro['nama_badpro']; ?></th>
                        <?php else: ?>
                            <th class="align-middle" rowspan="2"><?= $badpro['nama_badpro']; ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>

                <tr>
                    <th class="align-middle">Mulai</th>
                    <th class="align-middle">Selesai</th>
                    <?php foreach ($badpro_with_subbadpro as $badpro_uuid => $badpro): ?>
                        <?php if (count($badpro['sub_badpro']) > 0): ?>
                            <?php foreach ($badpro['sub_badpro'] as $sub_uuid => $sub): ?>
                                <th  class="align-middle"><?= $sub['sub_badpro']; ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= $row->varian; ?></td>
                        <td><?= $row->kode_prod; ?></td>
                        <td><?= $row->jumlah_wip; ?></td>
                        <td><?= $row->jam_mulai; ?></td>
                        <td><?= $row->jam_selesai; ?></td>
                        <td><?= $row->jml_mp; ?></td>
                        <td><?= $row->jml_release; ?></td>

                        <!-- Tampilkan jumlah di kolom Bad Products -->
                        <?php foreach ($badpro_with_subbadpro as $badpro_uuid => $badpro): ?>
                            <?php if (count($badpro['sub_badpro']) > 0): ?>
                                <?php foreach ($badpro['sub_badpro'] as $sub_uuid => $sub): ?>
                                    <!-- Tampilkan jumlah untuk setiap sub_badpro -->
                                    <td>
                                        <?= isset($row->badpro_sortir) ? 
                                        array_reduce($row->badpro_sortir, function($carry, $item) use ($badpro_uuid, $sub_uuid) {
                                            return ($item['badpro_uuid'] == $badpro_uuid && $item['sub_badpro_uuid'] == $sub_uuid) ? $item['jumlah'] : $carry;
                                        }, '-') 
                                        : '-'; ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Tampilkan jumlah untuk badpro tanpa sub_badpro -->
                                <td>
                                    <?= isset($row->badpro_sortir) ? 
                                    array_reduce($row->badpro_sortir, function($carry, $item) use ($badpro_uuid) {
                                        return ($item['badpro_uuid'] == $badpro_uuid && empty($item['sub_badpro_uuid'])) ? $item['jumlah'] : $carry;
                                    }, '-') 
                                    : '-'; ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <td><?= $row->keterangan ?? '-'; ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
    <!-- Baris Total Berdasarkan Varian -->
    <?php 
    // Kelompokkan data berdasarkan varian_uuid
    $totals_by_varian = [];
    foreach ($data as $row) {
        if (!isset($totals_by_varian[$row->varian])) {
            $totals_by_varian[$row->varian] = [
                'total_wip' => 0,
                'total_mp' => 0,
                'total_release' => 0,
                'badpro_totals' => [],
            ];
        }
        $totals_by_varian[$row->varian]['total_wip'] += $row->jumlah_wip;
        $totals_by_varian[$row->varian]['total_mp'] += $row->jml_mp;
        $totals_by_varian[$row->varian]['total_release'] += $row->jml_release;

        foreach ($row->badpro_sortir ?? [] as $badpro) {
            $key = $badpro['badpro_uuid'] . ':' . ($badpro['sub_badpro_uuid'] ?? 'no_sub');
            if (!isset($totals_by_varian[$row->varian]['badpro_totals'][$key])) {
                $totals_by_varian[$row->varian]['badpro_totals'][$key] = 0;
            }
            $totals_by_varian[$row->varian]['badpro_totals'][$key] += $badpro['jumlah'];
        }
    }
    ?>

    <?php foreach ($totals_by_varian as $varian => $totals): ?>
        <tr>
            <th colspan="2" class="text-right">Total <?= $varian; ?></th>
            <th><?= $totals['total_wip']; ?></th>
            <th colspan="3"></th>
            
            <th><?= $totals['total_release']; ?></th>
            <?php foreach ($badpro_with_subbadpro as $badpro_uuid => $badpro): ?>
                <?php if (count($badpro['sub_badpro']) > 0): ?>
                    <?php foreach ($badpro['sub_badpro'] as $sub_uuid => $sub): ?>
                        <?php $key = $badpro_uuid . ':' . $sub_uuid; ?>
                        <th><?= $totals['badpro_totals'][$key] ?? 0; ?></th>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php $key = $badpro_uuid . ':no_sub'; ?>
                    <th><?= $totals['badpro_totals'][$key] ?? 0; ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
            <th></th>
        </tr>
    <?php endforeach; ?>
</tfoot>


                        </table>
                    </div>
                </div>   

                <br><br><table width="100%">
                    <tr>
                        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Dibuat</b></td>
                        <td style="border: none; width: 300px;"></td>
                        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Disetujui</b></td> 
                        <td style="border: none; width: 300px;"></td>
                        <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Diketahui</b></td> 
                    </tr>
                    <tr>
                        <td style="text-align: center; height: 50px; width: 200px;"><?= $nav->user_name ?></td>
                        <td style="height: 50px; border: none; width: 80px;"></td>
                        <td style="text-align: center; height: 50px; width: 200px;"><?= $nav->kr_name ?></td> 
                        <td style="height: 50px; border: none; width: 80px;"></td>
                        <td style="text-align: center; height: 50px; width: 200px;"><?= $nav->spv_name ?></td> 
                    </tr>
                    <tr>
                        <td style="width: 200px; text-align: center;">Checker Packing</td>
                        <td style="border: none; width: 30px;"></td>
                        <td style="width: 200px; text-align: center;">Koordinator Packing</td> 
                        <td style="border: none; width: 30px;"></td>
                        <td style="width: 200px; text-align: center;">Spv.Produksi</td> 
                    </tr>
                </table>
            </body></html>
