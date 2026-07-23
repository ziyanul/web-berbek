<div class="container-fluid">

    <!-- Page Heading -->
    <h3 class="h3 mb-2 text-gray-800">Detail Pergantian Varian "<?= $data[0]->area_name; ?> / <?= $data[0]->shift_name; ?>" </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('pergantian_varian') ?>"><i class="fas fa-arrow-left mr-2"></i> Pergantian Varian</a></li>
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
    <th class='align-middle text-center' colspan="2">Dari Proses Sortasi</th>
    <th class='align-middle text-center' colspan="2">Ke Proses Sortasi</th>
    <th class='align-middle text-center' colspan="2">Kondisi</th>
    <th class='align-middle text-center' rowspan="2">Keterangan</th>
    <th class='align-middle text-center' colspan="2">TTD</th>
    <th class='align-middle text-center' rowspan="2">Action</th>
  </tr>
  <tr>
    <th class='align-middle text-center'>Varian</th>
    <th class='align-middle text-center'>Kode Batch</th>
    <th class='align-middle text-center'>Varian</th>
    <th class='align-middle text-center'>Kode Batch</th>
    <th class='align-middle text-center'>Bersih dari Kontaminasi</th>
    <th class='align-middle text-center'>Belum Bersih dari Kontaminasi</th>
    <th class='align-middle text-center'>KR/Checker</th>
    <th class='align-middle text-center'>QC</th>
  </tr></thead>
  <tbody>
  <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td width="1"><?= $no;?></td>
                                <td><?= $row->varian_1;?></td>
                                <td><?= $row->batch_1;?></td>
                                <td><?= $row->varian_2;?></td>
                                <td><?= $row->batch_2;?></td>
                                <td class='text-center'><?= $row->kondisi1;?></td>
                                <td class='text-center'><?= $row->kondisi2;?></td>
                                <td class='text-center'><?= $row->keterangan;?></td>
                                <td class='text-center'><?= $row->username;?></td>
                                <td class='text-center'><?= $row->acc_qc;?></td>
                                <td><a href="<?= base_url('pergantian_varian/edit/'.$row->uuid); ?>" class="btn btn-md btn-warning shadow-sm"><i class="fa fa-edit fa-sm text-white"></i> Edit</a>
                                  
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
                        <a href="<?= base_url('pergantian_varian') ?>" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Kembali
                        </a>
                    </div>        
</div>
  </div>
  