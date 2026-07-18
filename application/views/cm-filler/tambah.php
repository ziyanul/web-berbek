<form method="post" action="<?= base_url('checklist/save_batch') ?>">
    <label for="mesin">Pilih Mesin:</label>
    <select name="id_mesin" id="mesin">
        <?php foreach ($mesin as $m): ?>
            <option value="<?= $m->id_mesin ?>"><?= $m->nama_mesin ?></option>
        <?php endforeach; ?>
    </select>

    <label for="batch_ke">Batch ke:</label>
    <input type="number" name="batch_ke" id="batch_ke" required>

    <label for="items">Item Checklist:</label>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <input type="checkbox" name="items[]" value="<?= $item->id_item ?>">
                <?= $item->nama_item ?>
            </li>
        <?php endforeach; ?>
    </ul>


    <button type="submit">Simpan</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th rowspan="2">MESIN</th>
            <th rowspan="2">ITEM</th>
            <th colspan="<?= $max_batch ?>">CHECKLIST (✓)/BATCH</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $max_batch; $i++): ?>
                <th><?= $i ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mesin as $m): ?>
            <tr>
                <td rowspan="5"><?= $m->nama_mesin ?></td>
                <?php foreach (['Pisau', 'Gathering', 'Engklosse Kiri', 'Brush Seal', 'Nozzle'] as $item): ?>
                    <td><?= $item ?></td>
                    <?php for ($i = 1; $i <= $max_batch; $i++): ?>
                        <td>
                            <?php
                            $checked = false;
                            foreach ($batches as $batch) {
                                if ($batch->nama_mesin == $m->nama_mesin &&
                                    $batch->item == $item &&
                                    $batch->batch_ke == $i &&
                                    $batch->status == 'done') {
                                    $checked = true;
                                    break;
                                }
                            }
                            echo $checked ? '✓' : '';
                            ?>
                        </td>
                    <?php endfor; ?>
                    <td></td> <!-- Kolom Keterangan -->
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
