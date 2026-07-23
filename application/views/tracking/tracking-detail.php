<div class="container-fluid">
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="row">
        <div class="col">
    <h1 class="h1 mb-2 text-gray-800">Detail Issue: <?= $data->issue ;?></h1>
</div>
</div>
<div class="row">
    <div class="col">
        <a href="<?= base_url('tracking/beforeafter/'.$data->uuid); ?>" class="btn btn-md btn-success btn-block">Before - After</a>
    <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
        <a href="<?= base_url('tracking/tambahdetail/'.$data->uuid); ?>" class="btn btn-md btn-primary btn-block mb-2"><i class="fas fa-plus fa-sm text-white mr-2"></i> Detail</a>
        <a href="<?= base_url('tracking/tambahbefore/'.$data->uuid); ?>" class="btn btn-md btn-info btn-block"><i class="fas fa-plus fa-sm text-white mr-2"></i>Before</a>
<?php }?>
</div></div>
</div>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('tracking') ?>"><i class="fas fa-arrow-left mr-2"></i>Tracking Improve</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>
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
                    <th width="1" class="font-weight-bold align-middle text-center" rowspan="2">No</th>
                    <th class="font-weight-bold align-middle text-center" colspan="3">Issue</th>
                    

                    <th class="font-weight-bold align-middle text-center" colspan="3">Before</th>
                    
                </tr>
                <tr>
                    <th class="font-weight-bold align-middle text-center">Detail Issue</th>
                    <th class="font-weight-bold align-middle text-center">Dokumentasi</th>
                    <th width="50" class="font-weight-bold align-middle text-center">Action Detail</th>
                    <th class="font-weight-bold align-middle text-center">Gap Analisis</th>
                    <th class="font-weight-bold align-middle text-center">Dokument</th>
                    <th width="50"class="font-weight-bold align-middle text-center">Action Before</th>

                </tr>
            </thead>
            <tbody>
               <?php
               $no = 1;
// Loop through t_before
               foreach ($before as $val) {
                ?>
                <tr>
                    <td><?= $no++;?></td>
                    <?php if (!empty($detail)) : ?>
                        <td><?= $detail[0]->detail;?></td>
                        <td class="text-center" width="100">
                            <?php
                            $imagePath = 'upload/' . $detail[0]->dokumentasi;

                            if (file_exists($imagePath)) {

                                echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80" alt="Dokumentasi"></a>';
                            } else {

                                echo '<span class="text-muted">Tidak ada file</span>';
                            }
                            ?>
                        </td>

                        <td>
                            <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                            <a href="<?= base_url('tracking/delete_detail/'.$detail[0]->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                        <?php } ?>
                        </td>
                        <?php
            // Remove the matched element from $detail
                        array_shift($detail);
                        else : ?>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php endif; ?>
                        <td><?= $val->gap;?></td>
                        <td class="text-center" width="100">
                            <?php
                            $imagePath = 'upload/' . $val->dok_before;

                            if (file_exists($imagePath)) {

                                echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80"></a>';
                            } else {

                                echo 'Tidak ada file';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if($this->session->userdata('type')==1 || $this->session->userdata('type')==2){?>
                            <a href="<?= base_url('tracking/delete_before/'.$val->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                        <?php } ?>
                        </td>
                        
                            
                        
                    </tr>
                    <?php
                }

// Loop through remaining t_detail elements
                foreach ($detail as $row) {
                    ?>
                    <tr>
                        <td><?= $no++;?></td>
                        <td><?= $row->detail;?></td>
                        <td class="text-center" width="100">
                            <?php
                            $imagePath = 'upload/' . $row->dokumentasi;

                            if (file_exists($imagePath)) {

                                echo '<a href="' . base_url($imagePath) . '"><img src="' . base_url('assets/img/buku.jpg') . '" width="60" height="80" alt="Dokumentasi"></a>';
                            } else {

                                echo 'Tidak ada file';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url('tracking/delete_detail/'.$row->uuid); ?>" class="btn btn-md btn-danger shadow-sm btn-block" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
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