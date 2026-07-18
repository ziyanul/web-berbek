<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Edit Pengecekan Benda Tajam</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"> <a href="<?= base_url('Pbtajam/form_pbtajam');?>">
                    <i class="fas fa-arrow-left"></i> Pengecekan Benda Tajam</a></li>
            <li class="breadcrumb-item active" aria-current="page"> <a
                    href="<?= base_url('Pbtajam/detailform/'.$data->tgl.'/'.$data->shift);?>"> <i></i> Detail
                    Benda Tajam</a></li>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-body, col-sm-8">
            <form class="user" action="<?= base_url('Pbtajam/editform/'.$data->uuid) ?>" method="post">
                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label"> Shift:<span class="text-danger">*</span></label>
                        <input class="form-control" value="<?= $data->shift_name?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6 mt-3">
                        <label class="form-label">Area <span class="text-danger">*</span></label>
                        <input class="form-control" value="<?= $data->nama_area?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label class="form-label">Pengecekan Berdasarkan Kode Benda: <span
                                class="text-danger">*</span></label>
                        <div id="kode-list">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class='table text-light bg-info'>
                        <tr>
                            <th class="align-middle" rowspan="2">Jenis Benda Tajam</th>
                            <th class="align-middle" rowspan="2">Kode Benda Tajam</th>
                            <th class="text-center align-middle" colspan="3">Kondisi</th>
                            <th class="text-center align-middle" rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th class="text-center">Baik</th>
                            <th class="text-center align-middle">Pecah</th>
                            <th class="text-center align-middle">Hilang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $data->jenis_benda;?></td>
                            <td><?= $data->kode_benda;?></td>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" value="1"
                                        <?= $data->kondisi == 1 ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" value="2"
                                        <?= $data->kondisi == 2 ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" value="3"
                                        <?= $data->kondisi == 3 ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td class="text-center">
                                <input class="form-input" type="text" name="keterangan"
                                    value="<?= $data->keterangan; ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-md btn-success mr-2 mb-3">
                            <i class="fa fa-save mr-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('Pbtajam/detailform/'.$data->tgl.'/'.$data->shift) ?>"
                            class="btn btn-md btn-danger mr-2 mb-3">
                            <i class="fa fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>