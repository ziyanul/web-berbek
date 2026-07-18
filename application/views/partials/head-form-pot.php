<html>
        <head>
        <title><?= $no_form ?> <?= $title ?></title>
        <meta name="author" content="Arthur Herbert Fonzarelli">
        <meta name="keywords" content="fonzie, cool, ehhhhhhh">
        </head>
        <body>

        <style>
        @page { margin: 5px; }
        body {sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        table tr td{border:1px solid #000;}
        table thead tr {background-color:#dbe5f1}
        table thead tr#standar{background-color:#b8cce4!important;}
        table.data tr th{border:1px solid #000;text-align:center;font-size:12px;}
        .data th, .data td { padding: 2px; }
        table.data tr td{text-align:center;}
        table tr th{border:1px solid #000;}
        </style>


<table>
    <tr>
        <td rowspan="4" width="50"><img src="<?= $logo ?>" width="100"></td>
        <td width="500px" rowspan="2" style="text-align: center;"><h2 class="h2">FORM</h2></td>
        <td>&nbsp;No Dokumen</td>
        
        <td> &nbsp;: <?= $no_form ?></td>
    </tr>
    <tr>

        
        <td>&nbsp;Revisi</td>
        
        <td> &nbsp;: <?= $revisi ?></td>
    </tr>
    <tr>

        <td rowspan="2" style="text-align: center;"><h2 class="h2"><?= $title ?></h2></td>
        <td>&nbsp;Tanggal Efektif</td>
        
        <td> &nbsp;: <?= $tanggal_efektif ?></td>
    </tr>

    <tr>

        
        <td>&nbsp;Halaman</td>
        
        <td> &nbsp;: <?= $halaman ?></td>
    </tr>
    
</table>

