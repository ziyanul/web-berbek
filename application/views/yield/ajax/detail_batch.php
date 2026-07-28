
<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Batch</th>
                <th>Varian</th>
                <th>Mesin</th>
                <th>Adonan</th>
                <th>Filkar<br>Box</th>
                <th>Filkar<br>Kg</th>
                <th>Sortasi</th>
                <th>Release</th>
                <th>Belum</th>
                <th>Filkar<br>Rework</th>
                <th>Filkar<br>Reject</th>
                <th>Sortasi<br>Rework</th>
                <th>Sortasi<br>Reject</th>
                <th>Yield<br>Filkar</th>
                <th>Yield<br>Release</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($detail_batch)) { ?>
                <tr>
                    <td colspan="16" class="text-center text-muted">
                        Tidak ada data.
                    </td>
                </tr>
            <?php } ?>
            <?php $no = 1;
            foreach ($detail_batch as $row) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?= date('d-m-Y', strtotime($row->tanggal)) ?>
                    </td>
                    <td>
                        <?= $row->kode_batch ?>
                    </td>
                    <td>
                        <?= $row->varian ?>
                    </td>
                    <td style="min-width:120px">
                        <?= $row->nama_mesin ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($row->adonan, 2) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($row->filkar_box) ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($row->filkar_kg, 2) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($row->sortasi_box) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($row->release_box) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($row->belum_sortir) ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($row->bad_filkar_rework_kg, 2) ?>
                    </td>
                    <td class="text-right text-danger">
                        <?= number_format($row->bad_filkar_reject_kg, 2) ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($row->bad_sortasi_rework_kg, 2) ?>
                    </td>
                    <td class="text-right text-danger">
                        <?= number_format($row->bad_sortasi_reject_kg, 2) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($row->yield_formula, 2) ?>%
                    </td>
                    <td class="text-center">
                        <?= number_format($row->yield_release, 2) ?>%
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>