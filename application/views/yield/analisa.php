<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h1 mb-2 text-gray-800">Data Varian</h1>


    </div>

    <div class="card mb-3">

        <div class="card-header">
            <b>Filter Data</b>
        </div>

        <div class="card-body">

            <form method="get">

                <div class="row">

                    <div class="col-md-2">
                        <label>Dari</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="<?= $filter['tanggal_awal'] ?>">
                    </div>

                    <div class="col-md-2">
                        <label>Sampai</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="<?= $filter['tanggal_akhir'] ?>">
                    </div>

                    <div class="col-md-2">
                        <label>Varian</label>

                        <select name="varian" class="form-control">

                            <option value="">Semua</option>

                            <?php foreach($varian_list as $v){ ?>

                            <option value="<?= $v->uuid ?>">

                                <?= $v->varian ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>Mesin</label>

                        <select name="mesin" class="form-control">

                            <option value="">Semua</option>

                            <?php foreach($mesin_list as $m){ ?>

                            <option value="<?= $m->mesin_uuid ?>">

                                <?= $m->nama_mesin ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>Bad Produk</label>

                        <select name="badpro" class="form-control">

                            <option value="">Semua</option>

                            <?php foreach($badpro_list as $b){ ?>

                            <option value="<?= $b->uuid ?>">

                                <?= $b->nama_badpro ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="col-md-2">


                        <button class="btn btn-primary btn-block">

                            Tampilkan

                        </button>
                        <button id="btnReset" class="btn btn-danger btn-block">

                            Reset

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    $('#btnReset').click(function(e) {
        e.preventDefault();
        $('input[name="tanggal_awal"]').val('');
        $('input[name="tanggal_akhir"]').val('');
        $('select[name="varian"]').val('');
        $('select[name="mesin"]').val('');
        $('select[name="badpro"]').val('');
    });
});
</script>