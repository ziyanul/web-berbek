<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pemusnahan Bad Produk</h1>
        <a href="<?= base_url('Pemusnahan_Badproduct/tambah'); ?>" class="btn btn-md btn-primary shadow-sm font-weight-bold"><i class="fas fa-plus fa-sm text-white mr-2"></i> Tambah</a>
    </div>
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?php echo $this->session->flashdata('success_msg'); ?>    </div>
            <br>
        <?php endif; ?>
        <?php if($this->session->flashdata('error_msg')): ?>
            <div class="alert alert-danger  text-center">
                <i class="fas fa-times"></i>
                <?php echo $this->session->flashdata('error_msg'); ?>    
            </div>
            <br>
        <?php endif ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th class='align-middle text-center'>No.</th>
                                <th class='align-middle text-center'>Tanggal</th>
                                <th class='align-middle text-center'>Shift</th>
                                <th class='align-middle text-center' width='50%'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td class='align-middle text-center' width="1"><?= $no;?></td>
                                    <td class='align-middle text-center'><?= $row->tanggal;?></td>
                                    <td class='align-middle text-center'><?= $row->shift_name;?></td>
                                    <td class='align-middle text-center'>
                                        
                                        <a href="<?= base_url('pemusnahan_badproduct/detail/'.$row->tgl.'/'.$row->shift); ?>" class="btn btn-md btn-block btn-success shadow-sm mb-2 mr-2 font-weight-bold"><i class="fa fa-edit fa-sm text-white mr-2"></i> Detail Pemusnahan</a>
                                        <a href="<?= base_url('pemusnahan_badproduct/form/'.$row->tgl.'/'.$row->shift); ?>"target="_blank" class="btn btn-md btn-info btn-block shadow-sm mb-2 font-weight-bold"><i class="fa fa-print fa-sm text-white mr-2"></i> Form Pemusnahan Bad Produk</a>
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