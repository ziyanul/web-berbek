<div class="container-fluid">

    <!-- Page Heading -->
    <h3 class="h3 mb-2 text-gray-800">Detail Tools Mesin "<?= $data[0]->nama_area; ?>" </h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('tools_mesin') ?>"><i class="fas fa-arrow-left mr-2"></i> Data Tools Mesin</a></li>
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

<div class="card shadow mb-4">
    <div class="card-body">
<div class="row mt-3">
<div class="col mb-3">
<table class='table table-bordered' id="datatables" ><thead class='table text-light bg-info'> 
<tr>
    <th class='align-middle text-center' rowspan="2">No.</th>
    <th class='align-middle text-center' rowspan="2">Tools Mesin</th>
    <th class='align-middle text-center' rowspan="2">Action</th>
</tr>
</thead>
<tbody>
<?php
        $no = 1;
        foreach ($data as $row) {
            ?>
            <tr class='text-center'>
                <td width="1"><?= $no;?></td>
                <td><?= $row->nama_tools;?></td>
                <td><a href="<?= base_url('tools_mesin/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white mr-2"></i> Edit</a>
            </tr>
            <?php
            $no++;
        }
        ?>
</tbody>
</table>
    
            </div>
</div>
                    <div class="col mt-3">
                        <a href="<?= base_url('tools_mesin') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>        
</div>
</div>