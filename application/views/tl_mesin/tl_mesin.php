<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Data Tools Mesin</h1>
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table width="100%" cellspacing="0" class="table-bordered mb-5">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th rowspan="2" class="bg-light text-info align-middle" width="1"><i class="fa fa-tools"></i></th>
                            <td class="font-weight-bold">Area</td>
                            <td class="font-weight-bold">Tools Mesin</td>
                            <td class="font-weight-bold text-center">Action</td>
                        </tr>
                        <tr class="table bg-light">
                            <form class="user" action="<?= base_url('Tools_Mesin') ?>" method="post">
                                <td> <select class="form-control <?= form_error('area') ? 'invalid' : '' ?>" name="area"
                                        id="area">
                                        <option selected disabled>Pilih Area</option>
                                        <?php foreach ($area as $a): ?>
                                        <option value="<?= $a->uuid ?>" <?= set_select('area', $a->uuid);?>>
                                            <?= $a->nama_area ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback <?= !empty(form_error('area')) ? 'd-block':'';?>">
                                        <?= form_error('area') ?>
                                    </div>
                                </td>
                                <td><input type="text" name="tl_mesin"
                                        class="form-control <?= form_error('tl_mesin') ? 'invalid' : '' ?>"
                                        value="<?= set_value('tl_mesin'); ?>" placeholder="">
                                    <div class="invalid-feedback <?= !empty(form_error('tl_mesin')) ? 'd-block':'';?>">
                                        <?= form_error('tl_mesin') ?>
                                    </div>
                                </td>
                                <td class="text-center"><button type="submit" class="btn btn-md btn-success">
                                        <i class="fa fa-save text mr-1"></i> Simpan
                                    </button>
                                </td>
                            </form>
                        </tr>
                    </thead>
                </table>
                <table width="100%" cellspacing="0" class="table-bordered mb-2">
                    <thead class="table bg-info text-light">
                        <tr class="text-center">
                            <th width="1" class="font-weight-bold">No</th>
                            <th class="font-weight-bold">Area</th>
                            <th class="font-weight-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $no = 1;
                foreach ($data as $row) {
                    ?>
                        <tr class="text-center">
                            <td><?= $no; ?></td>
                            <td><?= $row->nama_area; ?></td>
                            <td class='align-middle text-center'>
                                <a href="<?= base_url('Tools_Mesin/detail/'.$row->area_uuid); ?>"
                                    class="btn btn-md btn-success shadow-sm btn-block"><i
                                    class="fa fa-info fa-sm text-white mr-2"></i> Detail Tools Mesin</a>
                            </td>
                        </tr>
                        <?php
                    $no++;
                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>