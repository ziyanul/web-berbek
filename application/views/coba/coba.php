<div class="container-fluid">
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
 </div>

 <div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered">
    <thead class="bg-info text-light text-center">
        <tr>
            <th rowspan="3" class="align-middle">No</th>
            <th rowspan="3" class="align-middle">Area</th>
            <th rowspan="3" class="align-middle">Item yang dibersihkan</th>
            <th rowspan="3" class="align-middle">Kode Chemical</th>
            <th colspan="12">Pemakaian Larutan Chemical (ml)</th>
            <th rowspan="3">Total</th>
        </tr>
        <tr>
            <th colspan="4">Shift 1</th>
            <th colspan="4">Shift 2</th>
            <th colspan="4">Shift 3</th>
        </tr>
        <tr>
            <th>08.00</th>
            <th>10.00</th>
            <th>12.00</th>
            <th>14.00</th>
            <th>16.00</th>
            <th>18.00</th>
            <th>20.00</th>
            <th>22.00</th>
            <th>24.00</th>
            <th>02.00</th>
            <th>04.00</th>
            <th>06.00</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $current_area = '';
        $current_item = '';
        $area_rowspan_count = [];
        $item_rowspan_count = [];
        $row_number = 1;

        // Hitung rowspan untuk area dan kegiatan_uuid terlebih dahulu
        foreach ($data as $row) {
            // Hitung rowspan untuk Area
            if (!isset($area_rowspan_count[$row->area])) {
                $area_rowspan_count[$row->area] = 0;
            }
            $area_rowspan_count[$row->area]++;

            // Hitung rowspan untuk Nama Item berdasarkan kegiatan_uuid
            if (!isset($item_rowspan_count[$row->kegiatan_uuid])) {
                $item_rowspan_count[$row->kegiatan_uuid] = 0;
            }
            $item_rowspan_count[$row->kegiatan_uuid]++;
        }

        // Loop kedua untuk menampilkan data dengan rowspan
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td class='align-middle'>" . $row_number++ . "</td>";

            // Rowspan untuk Area
            if ($current_area != $row->area) {
                $current_area = $row->area;
                echo "<td class='align-middle' rowspan='{$area_rowspan_count[$row->area]}'>" . $row->area . "</td>";
            }

            // Rowspan untuk Nama Item (berdasarkan kegiatan_uuid)
            if ($current_item != $row->kegiatan_uuid) {
                $current_item = $row->kegiatan_uuid;
                echo "<td class='align-middle' rowspan='{$item_rowspan_count[$row->kegiatan_uuid]}'>" . $row->nama_item . "</td>";
            }

            // Tampilkan Kode Chemical dan Pemakaian Chemical
            echo "<td class='align-middle'>" . $row->kode_chemical . "</td>";
            echo "<td class='align-middle'>" . $row->jam8 . "</td>";
            echo "<td class='align-middle'>" . $row->jam10 . "</td>";
            echo "<td class='align-middle'>" . $row->jam12 . "</td>";
            echo "<td class='align-middle'>" . $row->jam14 . "</td>";
            echo "<td class='align-middle'>" . $row->jam16 . "</td>";
            echo "<td class='align-middle'>" . $row->jam18 . "</td>";
            echo "<td class='align-middle'>" . $row->jam20 . "</td>";
            echo "<td class='align-middle'>" . $row->jam22 . "</td>";
            echo "<td class='align-middle'>" . $row->jam0 . "</td>";
            echo "<td class='align-middle'>" . $row->jam2 . "</td>";
            echo "<td class='align-middle'>" . $row->jam4 . "</td>";
            echo "<td class='align-middle'>" . $row->jam6 . "</td>";
            echo "<td class='align-middle'>" . $row->total_used . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>




        </div>
    </div>
</div>