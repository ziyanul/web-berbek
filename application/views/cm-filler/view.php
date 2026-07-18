<h2>Checklist Mesin Filler</h2>

<form method="post" action="<?= base_url('cekmesin_fillerbatch/save_checklist') ?>">
    <label for="batch_uuid">Pilih Batch:</label>
    <select name="batch_uuid" id="batch_uuid">
        <?php foreach ($planning as $plan): ?>
            <optgroup label="Planning <?= $plan->tanggal ?>">
                <?php
                $batches = $this->db->get_where('tbatch', ['t_planning_uuid' => $plan->uuid])->result();
                foreach ($batches as $batch): ?>
                    <option value="<?= $batch->uuid ?>">Batch <?= $batch->batch_ke ?></option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>

    <label for="mesin">Pilih Mesin:</label>
    <select name="mesin_uuid" id="mesin">
        <?php foreach ($mesin as $m): ?>
            <option value="<?= $m->uuid ?>"><?= $m->nama_mesin ?></option>
        <?php endforeach; ?>
    </select>

    <table border="1">
        <thead>
            <tr>
                <th>Item</th>
                <th>Checklist</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil daftar item berdasarkan mesin UUID
            if (!empty($mesin[0])) {
                $items = $this->db->get_where('item_cekmesin', ['mesin_uuid' => $mesin[0]->uuid])->result();
                foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?= $item->kegiatan ?>
                            <input type="hidden" name="items[]" value="<?= $item->uuid ?>">
                        </td>
                        <td>
                            <select name="ceklist[]">
                                <option value="yes">Ya</option>
                                <option value="no">Tidak</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="keterangan[]" placeholder="Keterangan">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php } ?>
        </tbody>
    </table>

    <button type="submit">Simpan Checklist</button>
</form>

<h3>Data Checklist</h3>
<table border="1">
    <thead>
        <tr>
            <th>Mesin</th>
            <th>Item</th>
            <th>Batch</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($checklist as $c): ?>
            <tr>
                <td><?= $c->nama_mesin ?></td>
                <td><?= $c->item ?></td>
                <td><?= $c->batch_ke ?></td>
                <td><?= $c->ceklist ?></td>
                <td><?= $c->keterangan ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
