<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">After - Hasil</h1>
    </div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('tracking/beforeafter/' . $after->uuid) ?>"><i class="fas fa-arrow-left mr-2"></i>Before</a></li>
    </ol>
</nav>         
    <div class="card shadow mb-4">
        <div class="card-body">
             <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr>
                    <th width="1" class="font-weight-bold align-middle text-center" rowspan="2">No</th>
                    <th class="font-weight-bold align-middle text-center" colspan="3">After</th>
                    <th class="font-weight-bold align-middle text-center" colspan="5">Hasil Evaluasi</th>
                    
                </tr>
                <tr>
                    <th class="font-weight-bold align-middle text-center">CAP</th>
                    <th class="font-weight-bold align-middle text-center">Dokumentasi</th>
                    <th width="50" class="font-weight-bold align-middle text-center">Deadline</th>
                    <th class="font-weight-bold align-middle text-center">Tanggal</th>
                    <th class="font-weight-bold align-middle text-center">Hasil</th>
                    <th class="font-weight-bold align-middle text-center">Dokumentasi</th>
                    <th class="font-weight-bold align-middle text-center">Status</th>
                    <th width="50"class="font-weight-bold align-middle text-center">Action</th>

                </tr>
            </thead>
           <tbody>
             <?php
                            $no = 1;
                            foreach ($data as $row) {
                               
                        ?>
                        <tr>
                            <td width="1"><?= $no; ?></td>
                            <td><?= $row->cap;?></td>
                            <td class="text-center">
    <?php
    $imagePath = 'upload/' . $row->dok_after;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
                            <td><?= $row->u_deadline;?></td>
                            <td><?= $row->u_tanggal;?></td>
                            <td><?= $row->f_hasil;?></td>
                            <td class="text-center">
    <?php
    $imagePath = 'upload/' . $row->f_dok_hasil;
    
    if (!empty($row->f_dok_hasil) && file_exists($imagePath)) {
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
        echo 'Belum Ada Evaluasi';
    }
?>

</td>
                            <td><?= $row->status;?></td>
                            <td>
                                <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                                <a href="<?= base_url('tracking/tambahhasil/'.$row->uuid); ?>" class="btn btn-md btn-info shadow-sm btn-block"><i class="fa fa-edit fa-sm text-white"></i>Evaluasi</a>
                            <?php } ?>
                                <a href="<?= base_url('tracking/hasil/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block"><i class="fa fa-book fa-sm text-white"></i>Detail</a>
                            </td>
                        </tr>
                        <?php
                            $no++;
                            }
                        ?>
           
        </tbody>
    </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->