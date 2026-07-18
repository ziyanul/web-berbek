<?php if (empty($result)): ?>
    <p>Tidak ada data.</p>
<?php else: ?>
    <?php foreach ($result as $kategori => $items): ?>
        <h4 class="h4 mt-4"><?= $judul_tabel[$kategori] ?? strtoupper($kategori) ?></h4>

        <table class="table table-bordered">
            <thead class="table bg-info text-light">
            <tr>
                <th width="1">No</th>
                <th width="200">Tanggal</th>
                <th width="300">varian</th>
                <!-- <th>Mesin</th> -->
                <th>Berat</th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $row['tanggal_format'] ?? '-' ?></td>
                    <td><?= $row['nama_varian'] ?? '-' ?></td>
                    <!-- <td><?= $row['mesin_uuid'] ?? '-' ?></td> -->
                    <td><?= $row['persentase'] ?? '-' ?></td>
                    
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php endforeach ?>
<?php endif ?>
