<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">
        Detail Formula
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="<?= base_url('formula') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Master Formula
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail
            </li>

        </ol>
    </nav>
<?php if($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?php echo $this->session->flashdata('success_msg'); ?>    
		</div>
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

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Informasi Formula
            </h6>
        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-2 font-weight-bold">
                    Nama Formula :
                </div>

                <div class="col-md-4">
                    <?= $data['master']->nama_formula ?>
                </div>

                <div class="col-md-2 font-weight-bold">
                    Total :
                </div>

                <div class="col-md-4">
                    <?= number_format($data['master']->total, 2, ',', '.') ?> KG
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-2 font-weight-bold">
                    Varian :
                </div>

                <div class="col-md-4">
                    <?= $data['master']->varian ?>
                </div>

            </div>

            <div class="row">

                <div class="col-md-2 font-weight-bold">
                    Keterangan :
                </div>

                <div class="col-md-10">
                    <?= nl2br($data['master']->keterangan) ?>
                </div>

            </div>

        </div>

    </div>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Detail Bahan Formula
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="table bg-info text-light">

                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Bahan</th>
                            <th width="20%">Qty</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $no = 1;
                        $total = 0;
                        ?>

                        <?php foreach($data['detail'] as $row): ?>

                            <?php
                            $total += $row->qty;
                            ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td>
                                    <?= $row->nama_bahan ?>
                                </td>

                                <td class="text-right"> 
                                    <?= number_format($row->qty, 2, ',', '.') ?> KG
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="2" class="text-center">
                                Total Formula
                            </th>

                            <th class="text-right">
                                <?= number_format($total, 2, ',', '.') ?> KG
                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <a href="<?= base_url('formula') ?>"
               class="btn btn-danger">

                <i class="fa fa-arrow-left"></i>
                Kembali

            </a>

            <a href="<?= base_url('formula/edit/'.$data['master']->uuid) ?>"
               class="btn btn-warning">

                <i class="fa fa-edit"></i>
                Edit

            </a>

        </div>

    </div>
</div>