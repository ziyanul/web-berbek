<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Kondisi Sanitasi</h1>
    
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
            <form class="user" action="<?= base_url('sanitasi/kondisi') ?>" method="post">
                <div class="row">
                    <div class="col-sm-6">
                       <table class="table table-bordered">
                        <thead class="table bg-info text-light">
                            <tr>
                               <th>No</th>
                               <th>Kondisi</th>
                               <th>Action</th>
                           </tr>
                       </thead>
                       <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row)

                         { ?>
                            <tr>
                                <td><?= $no ; ?></td>
                                <td><?= $row->kondisi ; ?></td>
                                <td></td>
                            </tr>
                            <?php 
                            $no ++ ;
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td>
                                <input type="text" name="kondisi" class="form-control <?= form_error('kondisi') ? 'invalid' : '' ?>" placeholder="Masukkan Nama kondisi" value="<?= set_value('kondisi'); ?>">
                                <div class="invalid-feedback <?= !empty(form_error('kondisi')) ? 'd-block':'';?>">
                                    <?= form_error('kondisi') ?>
                                </div>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-md btn-success mr-2">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
</div>
</div>