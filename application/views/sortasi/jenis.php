<!-- Begin Page Content -->
<div class="container-fluid">
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Page Heading -->
    <h1 class="h1 mb-2 text-gray-800">Data Jenis Sortasi</h1>


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
                    <th class="font-weight-bold">Jenis Sortasi</th>
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
                        <td><?= $row->jenis; ?></td>
                        <td><?= $row->keterangan; ?></td>


                        <td>
                            <a href="<?= base_url('sortasi/edit_jenis/' . $row->uuid); ?>" class="btn btn-md btn-warning shadow-sm btn-block"><i class="fa fa-edit"></i> Edit</a>

                     </td>
                 </tr>
                 <?php
                 $no++;
             }
             ?>
             <tr><form class="user" action="<?= base_url('sortasi/jenis') ?>" method="post">
                <td>#
                </td>
                <td><input type="text" name="jenis" class="form-control <?= form_error('jenis') ? 'invalid' : '' ?>" value="<?= set_value('jenis'); ?>" placeholder="">
                    <div class="invalid-feedback <?= !empty(form_error('jenis')) ? 'd-block':'';?>">
                        <?= form_error('jenis') ?>
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