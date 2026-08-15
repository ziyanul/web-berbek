<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div class="mb-3 mb-sm-0">
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Data Batch</h1>
            <p class="mb-0 text-gray-600">
                <i class="far fa-calendar-alt mr-1"></i> <?= $data->tgl; ?>
                <span class="mx-2">|</span>
                <i class="fas fa-tag mr-1"></i> <?= $data->vrn; ?> (<?= $data->keterangan; ?>)
            </p>
        </div>
        <a href="<?= base_url('counter/tambahbatch/'.$data->uuid); ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white mr-1"></i> Tambah Batch
        </a>
    </div>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white shadow-sm">
            <li class="breadcrumb-item">
                <a href="<?= base_url('counter') ?>">
                    <i class="fas fa-arrow-left mr-2"></i>Form Counter
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Batch</li>
        </ol>
    </nav>
    <!-- Flash Message -->
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= $this->session->flashdata('success_msg'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-times-circle mr-2"></i>
            <?= $this->session->flashdata('error_msg'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <!-- Card Table -->
    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Batch Produksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="datatables" width="100%" cellspacing="0">
                    <thead class="bg-info text-white text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>User</th>
                            <th>Batch Ke-</th>
                            <th>kode Batch</th>
                            <th>Jam</th>
                            <th>Total Counter</th>
                            <th width="25%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($batch as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= $row->username; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-primary px-3 py-2">
                                        <?= $row->batch_ke; ?>
                                    </span>
                                </td>
                                <td><?= $row->kode_batch ?></td>
                                <td class="text-center"><?= $row->time; ?></td>
                                <td class="text-right font-weight-bold text-success">
                                    <?= number_format($row->total, 0, '', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('counter/detailcounter/'.$row->uuid); ?>" class="btn btn-sm btn-success shadow-sm">
                                            <i class="fa fa-book mr-1"></i> Detail
                                        </a>
                                        <a href="<?= base_url('counter/editbatch/'.$row->uuid); ?>" class="btn btn-sm btn-warning shadow-sm">
                                            <i class="fa fa-edit mr-1"></i> Edit
                                        </a>
                                        <a href="<?= base_url('counter/deletebatch/'.$row->uuid); ?>"
                                         class="btn btn-sm btn-danger shadow-sm"
                                         onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                         <i class="fa fa-trash mr-1"></i> Hapus
                                     </a>
                                 </div>
                             </td>
                         </tr>
                     <?php endforeach; ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>
</div>
<style>
    .table td, .table th {
    vertical-align: middle !important;
}
.badge {
    font-size: 13px;
}
.card-header h6 {
    font-size: 15px;
}
</style>