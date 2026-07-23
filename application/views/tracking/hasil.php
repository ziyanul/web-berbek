<!-- Begin Page Content -->
<div class="container-fluid">
   
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Detail</h1>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('tracking/afterhasil/'.$after->t_issue_uuid) ?>"><i class="fas fa-arrow-left"></i>After - Hasil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Hasil</li>
      </ol>
    </nav>




<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">

       <table class="table">
                        <tbody>
                            <tr>
                                <td width="200" class="border-top-0">Issue</td>
                                <td width="10" class="border-top-0">:</td>
                                <td class="font-weight-bold border-top-0"><?= $after->issue; ?></td>
                            </tr>
                            <tr>
                                <td width="200">PIC</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $after->pic; ?></td>
                            </tr>
                            <tr>
                                <td width="200">CAP After</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $after->cap; ?></td>
                            </tr>
                            <tr>
                                <td width="200">DeadLine</td>
                                <td width="10">:</td>
                                <td class="font-weight-bold"><?= $after->f_deadline; ?></td>
                            </tr>
                            <tr>
                                <td width="200" class="border-bottom">Dokumentasi CAP</td>
                                <td width="10" class="border-bottom">:</td>
                                <td class="border-bottom">
    <?php
    $imagePath = 'upload/' . $after->dok_after;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
                            </tr>   
                       
                        </tbody>
                        
                    </table>
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr class="bg-info text-light">
                        <th width="75">Tanggal</th>
                        <th>Evaluasi</th>
                        <th width="175">Dokumentasi</th>
                        <th width="100">Status</th>
                    </tr>
            </thead>
           <tbody>
           <?php
                    foreach ($hasil as $value) {
                    
                        $value->tanggal = date("d M Y", strtotime($value->created_at));
                        ?>
                        <tr>
                            <td><?= $value->tanggal;?></td>
                            <td><?= $value->evaluasi;?></td>
                            <td><?php
    $imagePath = 'upload/' . $value->dok_hasil;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?></td>
                            <td><?= $value->status;?></td>
                        </tr>
                    <?php } ?>
        </tbody>
    </table>

    
</div>
</div>
</div>
</div>