<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
            <tr>
                <th rowspan="2">Varian</th>
                <th rowspan="2">Adonan</th>
                <th colspan="2">Filling Karantina</th>
                <th colspan="3">Sortasi (Box)</th>
                <th colspan="2">Bad Produk Filkar (Kg)</th>
                <th colspan="2">Bad Produk Sortasi (Kg)</th>
                <th colspan="2">Yield (%)</th>
            </tr>
            <tr>
                <th>Box</th>
                <th>Kg</th>
                <th>Sortir</th>
                <th>Release</th>
                <th>Belum</th>
                <th>Rework</th>
                <th>Reject</th>
                <th>Rework</th>
                <th>Reject</th>
                <th>Filkar</th>
                <th>Release</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monitoring as $row) { ?>
                <tr>
                    <td><?= $row->nama_varian ?></td>
                    <td><?= number_format($row->adonan_formula, 2) ?></td>
                    <td><?= number_format($row->filkar_box) ?></td>
                    <td><?= number_format($row->filkar_kg, 2) ?></td>
                    <td><?= number_format($row->sortasi_box) ?></td>
                    <td><?= number_format($row->release_box) ?></td>
                    <td><?= number_format($row->blm_sortir) ?></td>
                    <td><?= number_format($row->filkar_rework, 2) ?></td>
                    <td><?= number_format($row->filkar_reject, 2) ?></td>
                    <td><?= number_format($row->sortasi_rework, 2) ?></td>
                    <td><?= number_format($row->sortasi_reject, 2) ?></td>
                    <td><?= number_format($row->yield_formula, 2) ?>%</td>
                    <td><?= number_format($row->yield_release, 2) ?>%</td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>TOTAL</th>
                <th><?= number_format($total->adonan_formula, 2) ?></th>
                <th><?= number_format($total->filkar_box) ?></th>
                <th><?= number_format($total->filkar_kg, 2) ?></th>
                <th><?= number_format($total->sortasi_box) ?></th>
                <th><?= number_format($total->release_box) ?></th>
                <th><?= number_format($total->blm_sortir) ?></th>
                <th><?= number_format($total->filkar_rework, 2) ?></th>
                <th><?= number_format($total->filkar_reject, 2) ?></th>
                <th><?= number_format($total->sortasi_rework, 2) ?></th>
                <th><?= number_format($total->sortasi_reject, 2) ?></th>
                <th><?= number_format($total->yield_formula, 2) ?>%</th>
                <th><?= number_format($total->yield_release, 2) ?>%</th>
            </tr>
        </tfoot>
    </table>
</div>