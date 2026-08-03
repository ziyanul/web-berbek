<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>FR-Prod-14 FORM PENGECEKAN MESIN MP</title>

    <meta name="author" content="Arthur Herbert Fonzarelli">
    <meta name="keywords" content="fonzie, cool, ehhhhhhh">

    <style>
        @page {
            margin: 5px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            border: 1px solid #000;
        }

        table thead tr {
            background-color: #dbe5f1;
        }

        table thead tr#standar {
            background-color: #b8cce4 !important;
        }

        table.data tr th {
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
        }

        .data th,
        .data td {
            padding: 2px;
        }

        table.data tr td {
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table width="100%">
        <tr>

            <td width="70">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td rowspan="2" align="center" valign="middle" style="border:0;">
                                <img src="<?= $logo ?>" width="110px">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td width="380">
                <table width="102%">
                    <tbody>
                        <tr>
                            <td style="
                                    text-align:center;
                                    border-top:0;
                                    border-left:0;
                                    border-right:0;
                                ">
                                <h2>FORM</h2>
                            </td>
                        </tr>

                        <tr>
                            <td style="
                                    text-align:center;
                                    border:0;
                                    text-transform:uppercase;
                                ">
                                <h2>PENGECEKAN MESIN</h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td>
                <table width="101%" style="margin-left:-1px;">
                    <tbody>

                        <tr>
                            <td style="border:0;height:30px;">
                                &nbsp;No. Dokumen
                            </td>
                            <td style="border:0;height:30px;">:</td>
                            <td style="border:0;height:30px;">
                                &nbsp;FR-Prod-14
                            </td>
                        </tr>

                        <tr>
                            <td style="border-left:0;border-right:0;height:30px;">
                                &nbsp;Revisi
                            </td>
                            <td style="border-left:0;border-right:0;height:30px;">
                                :
                            </td>
                            <td style="border-left:0;border-right:0;height:30px;">
                                &nbsp;1
                            </td>
                        </tr>

                        <tr>
                            <td style="border-left:0;border-right:0;height:30px;">
                                &nbsp;Tanggal Efektif
                            </td>
                            <td style="border-left:0;border-right:0;height:30px;">
                                :
                            </td>
                            <td style="border-left:0;border-right:0;height:30px;">
                                &nbsp;02/01/2024
                            </td>
                        </tr>

                        <tr>
                            <td style="
                                    border-left:0;
                                    border-right:0;
                                    border-bottom:0;
                                    height:30px;
                                ">
                                &nbsp;Halaman
                            </td>

                            <td style="
                                    border-left:0;
                                    border-right:0;
                                    border-bottom:0;
                                    height:30px;
                                ">
                                :
                            </td>

                            <td style="
                                    border-left:0;
                                    border-right:0;
                                    border-bottom:0;
                                    height:30px;
                                ">
                                &nbsp;1 dari 6
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>

        </tr>
    </table>


    <!-- INFORMASI -->
    <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>

            <tr>
                <td style="
                        border:none;
                        width:60px;
                        text-align:left;
                    ">
                    Area
                </td>

                <td style="
                        border:none;
                        width:10px;
                        text-align:left;
                    ">
                    :
                </td>

                <td style="
                        border:none;
                        text-align:left;
                    ">
                    <?= htmlspecialchars($cek_mesin[0]->area) ?>
                </td>
            </tr>

            <tr>
                <td style="
                        border:none;
                        width:60px;
                        text-align:left;
                    ">
                    Tanggal
                </td>

                <td style="
                        border:none;
                        width:10px;
                        text-align:left;
                    ">
                    :
                </td>

                <td style="
                        border:none;
                        text-align:left;
                    ">
                    <?= htmlspecialchars($cek_mesin[0]->tgl) ?>
                </td>

                <td style="
                        border:none;
                        width:60px;
                        text-align:left;
                    ">
                    Varian
                </td>

                <td style="
                        border:none;
                        width:10px;
                        text-align:left;
                    ">
                    :
                </td>

                <td style="
                        border:none;
                        width:450px;
                        text-align:left;
                    ">
                    <?= htmlspecialchars($cek_mesin[0]->varian) ?> ( <?= htmlspecialchars($cek_mesin[0]->keterangan) ?> )
                </td>
            </tr>

        </tbody>
    </table>

    <br>


    <!-- DATA CHECKLIST -->
    <table class="data" width="100%">

        <thead>
            <tr>

                <th rowspan="2" width="1">
                    No
                </th>

                <th rowspan="2" style="text-align:left; width:130px;">
                    Item
                </th>

                <th colspan="2">
                    Checklist Awal Produksi
                </th>

                <th rowspan="2" style="width:80px;">
                    Keterangan
                </th>

                <th colspan="2" style="border-right:3px solid #000000;">
                    Paraf
                </th>

                <th colspan="2">
                    Checklist Akhir Produksi
                </th>

                <th rowspan="2" style="width:80px;">
                    Keterangan
                </th>

                <th colspan="2">
                    Paraf
                </th>

            </tr>

            <tr>

                <th style="width:40px;">
                    Ya
                </th>

                <th style="width:40px;">
                    Tidak
                </th>

                <th style="width:60px;">
                    Prod
                </th>

                <th style="
                        width:60px;
                        border-right:3px solid #000000;
                    ">
                    QC
                </th>

                <th style="width:40px;">
                    Ya
                </th>

                <th style="width:40px;">
                    Tidak
                </th>

                <th style="width:60px;">
                    Prod
                </th>

                <th style="width:60px;">
                    QC
                </th>

            </tr>
        </thead>

        <tbody>

            <?php
            $last_mesin = null;
            $mesin_no  = 'A';
            $item_no   = 1;
            ?>

            <?php foreach ($cek_mesin as $row) : ?>

                <?php if ($last_mesin !== $row->mesin) : ?>

                    <?php
                    if ($last_mesin !== null) {
                        // Tidak perlu </tbody> di sini.
                        // tbody cukup ditutup sekali di bawah.
                    }
                    ?>

                    <tr>

                        <td style="
                            text-align:left;
                            border-right:3px solid #000000;
                        " colspan="7">
                            <strong>
                                <?= $mesin_no ?>.
                                <?= htmlspecialchars($row->mesin) ?>
                            </strong>
                        </td>

                        <td colspan="5"></td>

                    </tr>

                    <?php
                    $last_mesin = $row->mesin;
                    $mesin_no++;
                    $item_no = 1;
                    ?>

                <?php endif; ?>


                <!-- BARIS ITEM -->
                <tr>

                    <td>
                        <?= $item_no ?>
                    </td>

                    <td style="text-align:left;">
                        <?= htmlspecialchars($row->item) ?>
                    </td>

                    <td style="font-size:12px;">
                        <?= $row->checklist == 2 ? '&check;' : '-' ?>
                    </td>

                    <td style="font-size:12px;">
                        <?= $row->checklist == 0 ? 'x' : '-' ?>
                    </td>

                    <td>
                        <?= $row->keterangan ?: '-' ?>
                    </td>

                    <td>
                        <?= $row->nama_ceker ?: '' ?>
                    </td>

                    <td style="border-right:3px solid #000000;">
                        <?= $row->paraf_qc ?? '' ?>
                    </td>

                    <td style="font-size:12px;">
                        <?= $row->checklist2 == 2 ? '&check;' : '-' ?>
                    </td>

                    <td style="font-size:12px;">
                        <?= $row->checklist2 == 1 ? 'x' : '-' ?>
                    </td>

                    <td>
                        <?= $row->keterangan2 ?: '-' ?>
                    </td>

                    <td>
                        <?= $row->paraf_prod ?: '' ?>
                    </td>

                    <td>
                        <?= $row->paraf_qc ?? '' ?>
                    </td>

                </tr>

                <?php $item_no++; ?>

            <?php endforeach; ?>

        </tbody>
    </table>


    <br>


    <!-- TANDA TANGAN -->
    <table width="100%">

        <tr>

            <td style="
                    width:200px;
                    text-align:center;
                    background-color:#dbe5f1;
                ">
                <b>Dilaksanakan Oleh</b>
            </td>

            <td style="
                    border:none;
                    width:30px;
                "></td>

            <td style="
                    width:200px;
                    text-align:center;
                    background-color:#dbe5f1;
                ">
                <b>Diverifikasi Oleh</b>
            </td>

            <td style="
                    border:none;
                    width:30px;
                "></td>

            <td style="
                    width:200px;
                    text-align:center;
                    background-color:#dbe5f1;
                ">
                <b>Disetujui Oleh</b>
            </td>

        </tr>


        <tr>

            <td style="
                    text-align:center;
                    height:50px;
                    width:200px;
                ">
                <?= htmlspecialchars($row->fullname ?? '') ?>
            </td>

            <td style="
                    height:50px;
                    border:none;
                    width:80px;
                "></td>

            <td style="
                    text-align:center;
                    height:50px;
                    width:200px;
                ">
                <?= htmlspecialchars($row->foreman ?? '') ?>
            </td>

            <td style="
                    height:50px;
                    border:none;
                    width:80px;
                "></td>

            <td style="
                    text-align:center;
                    height:50px;
                    width:200px;
                ">
                <?= htmlspecialchars($row->spv ?? '') ?>
            </td>

        </tr>


        <tr>

            <td style="
                    width:200px;
                    text-align:center;
                ">
                Checker
            </td>

            <td style="
                    border:none;
                    width:30px;
                "></td>

            <td style="
                    width:200px;
                    text-align:center;
                ">
                Foreman/Lady
            </td>

            <td style="
                    border:none;
                    width:30px;
                "></td>

            <td style="
                    width:200px;
                    text-align:center;
                ">
                Spv.Produksi
            </td>

        </tr>

    </table>

</body>

</html>