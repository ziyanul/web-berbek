<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-2 text-gray-800">
            Edit Drystore
        </h1>

        <a href="<?= base_url('drystore'); ?>"
            class="btn btn-md btn-secondary shadow-sm">

            <i class="fas fa-arrow-left fa-sm text-white mr-2"></i>
            Kembali

        </a>

    </div>


    <?php if ($this->session->flashdata('error_msg')): ?>

        <div class="alert alert-danger text-center">

            <i class="fas fa-times mr-2"></i>

            <?= $this->session->flashdata('error_msg'); ?>

        </div>

        <br>

    <?php endif; ?>


    <?php if ($this->session->flashdata('success_msg')): ?>

        <div class="alert alert-success text-center">

            <i class="fas fa-check mr-2"></i>

            <?= $this->session->flashdata('success_msg'); ?>

        </div>

        <br>

    <?php endif; ?>


    <div class="card shadow mb-4">

        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">
                Edit Data Drystore
            </h6>

        </div>


        <div class="card-body">


            <!-- TANGGAL -->

            <div class="row mb-4">

                <div class="col-md-4">

                    <label class="font-weight-bold">
                        Tanggal
                    </label>

                    <div class="form-control bg-light">

                        <i class="fas fa-calendar-alt mr-2 text-primary"></i>

                        <?= tanggal_indo($drystore->tanggal); ?>

                        <small class="text-muted ml-2">
                            (otomatis dari server)
                        </small>

                    </div>

                </div>

            </div>


            <form
                action="<?= base_url('drystore/update/' . $drystore->uuid); ?>"
                method="post"
            >

<div class="row">

    <?php foreach ($types as $type): ?>

        <div class="col-lg-6 mb-4">

            <div class="card border-left-primary shadow-sm h-100">

                <div class="card-header py-2">

                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box mr-2"></i>
                        <?= htmlspecialchars($type->nama); ?>
                    </h6>

                </div>

                <div class="card-body py-2">

                    <?php foreach ($wastes as $waste): ?>

                        <?php
                        $value = '';

                        if (isset($matrix[$type->uuid][$waste->uuid])) {
                            $value = $matrix[$type->uuid][$waste->uuid];
                        }
                        ?>

                        <div class="form-group row align-items-center mb-2">

                            <label class="col-sm-7 col-form-label py-1">
                                <?= htmlspecialchars($waste->nama); ?>
                            </label>

                            <div class="col-sm-5">

                                <div class="input-group input-group-sm">

                                    <input
                                        type="number"
                                        name="waste[<?= $type->uuid; ?>][<?= $waste->uuid; ?>]"
                                        class="form-control text-right"
                                        step="0.001"
                                        min="0"
                                        value="<?= $value; ?>"
                                        placeholder="0"
                                    >

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            Kg
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>


                <!-- BUTTON -->

                <div class="text-right mt-4">

                    <a href="<?= base_url('drystore'); ?>"
                        class="btn btn-secondary shadow-sm mr-2">

                        <i class="fas fa-times mr-2"></i>
                        Batal

                    </a>


                    <button type="submit"
                        class="btn btn-warning shadow-sm">

                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>