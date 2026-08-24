<!-- Begin Page Content -->
<div class="container-fluid">
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Data Varian</h1>


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
                    <th width="1" class="font-weight-bold">No</th>
                    <th class="font-weight-bold">Varian</th>
                    <th class="font-weight-bold">Keterangan</th>
                    <th class="font-weight-bold">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row->varian; ?></td>
                        <td><?= $row->keterangan; ?></td>


                        <td>
                            <a href="<?= base_url('Varian/detail/' . $row->uuid); ?>" class="btn btn-md btn-success shadow-sm btn-block">Detail</a>
                         <a href="<?= base_url('Varian/edit/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block">Edit</a>


                     </td>
                 </tr>
                 <?php
                 $no++;
             }
             ?>
             <tr><form class="user" action="<?= base_url('Varian/simpan') ?>" method="post">
                <td>#
                </td>
                <td><input type="text" name="varian" class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" value="<?= set_value('varian'); ?>" placeholder="">
                    <div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
                        <?= form_error('varian') ?>
                    </div>
                </td>
                <td><input type="text" name="keterangan" class="form-control <?= form_error('keterangan') ? 'invalid' : '' ?>" value="<?= set_value('keterangan'); ?>" placeholder="">
                    <div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block':'';?>">
                        <?= form_error('keterangan') ?>
                    </div>
                </td>
                <td><button type="submit" class="btn btn-md btn-success mr-2">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </td>
        </form>
    </tr>



</tbody>

</table>
</div>
</div>
</div>
</div>