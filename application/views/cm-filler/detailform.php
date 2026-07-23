<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h1 text-gray-800">Detail Cek Mesin</h1>
    
</div>

<?php if($this->session->flashdata('success_msg')): ?>
    <div class="alert alert-success text-center">
        <i class="fas fa-check"></i>
        <?= $this->session->flashdata('success_msg') ?>
    </div>
    <br>
<?php endif ?>

<?php if($this->session->flashdata('error_msg')): ?>
    <div class="alert alert-danger  text-center">
        <i class="fas fa-times"></i>
        <?= $this->session->flashdata('error_msg') ?>
    </div>
    <br>
<?php endif ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                    <tr>
            <th rowspan="2">Mesin</th>
            <th rowspan="2">Item</th>
            <th rowspan="2">Frekuensi</th>
            <th colspan="20">Ceklist /BATCH</th>
            <th>Keterangan</th>
        </tr>
        <tr>
            <!-- Header untuk nomor batch -->
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16</th>
            <th>17</th>
            <th>18</th>
            <th>19</th>
            <th>20</th>
            <th></th>
        </tr>
                </thead>
                <tbody>
        <!-- Mulai proses pengelompokan data -->
        <?php
        $groupedData = [];
        foreach ($item as $row) {
            $groupedData[$row->mesin][] = $row;
        }

        foreach ($groupedData as $mesin => $items) {
            $rowspan = count($items);
            $firstRow = true;
            foreach ($items as $item) {
                echo '<tr>';
                if ($firstRow) {
                    echo '<td rowspan="' . $rowspan . '">' . $mesin . '</td>';
                    $firstRow = false;
                }
                echo '<td>' . $item->item . '</td>';
                echo '<td></td>'; // Kolom Frekuensi kosong (bisa diisi jika ada data)

                // Proses ceklist batch
                $ceklists = explode(',', $item->ceklists);
                for ($i = 0; $i < 20; $i++) {
                    echo '<td>' . ($ceklists[$i] ?? '') . '</td>';
                }

                echo '<td>Keterangan</td>'; // Kolom keterangan
                echo '</tr>';
            }
        }
        ?>
    </tbody>
            </table>
        </div>
    </div>
</div>
</div>