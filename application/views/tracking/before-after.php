<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="row">
        <div class="col">
    <h1 class="h1 mb-2 text-gray-800">Detail Issue: <?= $data->issue ;?></h1>
</div>
</div>
<div class="row">
    <div class="col">
        <a href="<?= base_url('tracking/afterhasil/'.$data->uuid); ?>" class="btn btn-md btn-success btn-block">After - Hasil</a>
    <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
        <a href="<?= base_url('tracking/tambahbefore/'.$data->uuid); ?>" class="btn btn-md btn-info btn-block"><i class="fas fa-plus fa-sm text-white mr-2"></i>Before</a>
        <a href="<?= base_url('tracking/tambahafter/'.$data->uuid); ?>" class="btn btn-md btn-info shadow-sm btn-block"><i class="fas fa-plus fa-sm text-white mr-2"></i>After</a>
    <?php }?>
</div></div>

</div>
<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('tracking') ?>"><i class="fas fa-arrow-left mr-2"></i>Tracking Improve</a></li>
             <li class="breadcrumb-item"><a href="<?= base_url('tracking/detail/' .$data->uuid) ?>">Detail issue</a></li>
            <li class="breadcrumb-item active" aria-current="page">after</li>
        </ol>
    </nav>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead class="table bg-info text-light">
                   <tr>
                    <th width="1" class="font-weight-bold align-middle text-center" rowspan="2">No</th>
                    <th class="font-weight-bold align-middle text-center" colspan="3">Before</th>
                    

                    <th class="font-weight-bold align-middle text-center" colspan="6">After</th>
                </tr>
                <tr>

                    <th class="font-weight-bold align-middle text-center">GAP Analis</th>
                    <th class="font-weight-bold align-middle text-center">Dokumentasi</th>
                    <th width="50" class="font-weight-bold align-middle text-center">Action</th>
                    <th class="font-weight-bold align-middle text-center">Tanggal</th>
                    <th class="font-weight-bold align-middle text-center">CAP</th>
                    <th class="font-weight-bold align-middle text-center">DeadLine</th>
                    <th class="font-weight-bold align-middle text-center">Dokumentasi</th>
                    <th class="font-weight-bold align-middle text-center">Pencapaian</th>
                    <th width="50"class="font-weight-bold align-middle text-center">Action</th>

                </tr>
            </thead>
           <tbody>
             <?php
$no = 1;

// Loop through t_before
foreach ($after as $val) {
    
    ?>
    <tr>
        <td><?= $no++;?></td>
        <?php if (!empty($before)) : ?>
            <td><?= $before[0]->gap;?></td>
            <td class="text-center">
    <?php
    $imagePath = 'upload/' . $before[0]->dok_before;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
            <td>
                <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <a href="<?= base_url('tracking/delete_before/'.$before[0]->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
        <?php } ?>
        </td>
            <?php
          
            array_shift($before);
        else : ?>
            <td></td>
            <td></td>
            <td></td>
        <?php endif; ?>

        <td><?= $val->tanggal;?></td>
        <td><?= $val->cap;?></td>
        <td><?= $val->dead ?></td>
        <td class="text-center">
    <?php
    $imagePath = 'upload/' . $val->dok_after;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
        <td><?= $val->pencapaian ?></td>
        <td> 
            <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
            <?php if (!empty($val->uuid)) : ?>
            <a href="<?= base_url('tracking/delete_after/'.$val->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus <?= $val->cap;?> ini?')">Hapus</a>
             <?php endif; ?>
         <?php } ?>
        </td>
        
    </tr>
    <?php
}

// Loop through remaining t_detail elements
foreach ($before as $row) {
    ?>
    <tr>
        <td><?= $no++;?></td>
        <td><?= $row->gap;?></td>
        <td class="text-center">
    <?php
    $imagePath = 'upload/' . $row->dok_before;
    
    if (file_exists($imagePath)) {
     
        echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
    } else {
      
        echo 'Tidak ada file';
    }
    ?>
</td>
        <td>
            <a href="<?= base_url('tracking/delete_before/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
       
    </tr>
    <?php
}
?>
                    
           
        </tbody>
    </table>

    
</div>
</div>
</div>
</div>