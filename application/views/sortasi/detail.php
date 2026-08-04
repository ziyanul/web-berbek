<div class="container-fluid">

    <h1 class="h3 mb-3 text-gray-800">
        Detail Sortasi
    </h1>


    <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="<?= base_url('sortasi') ?>">

                    <i class="fas fa-arrow-left mr-2"></i>

                    Sortasi

                </a>

            </li>


            <li class="breadcrumb-item active">

                Detail

            </li>

        </ol>

    </nav>


    <!-- =========================================================
         INFORMASI
    ========================================================== -->

    <div class="row">


        <!-- =====================================================
             INFORMASI BATCH
        ====================================================== -->

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-primary text-white">

                    <b>
                        Informasi Batch
                    </b>

                </div>


                <div class="card-body">

                    <table class="table table-borderless table-sm">

                        <tr>

                            <th width="35%">
                                Kode Batch
                            </th>

                            <td>
                                <?= $data->kode_batch ?>
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Varian
                            </th>

                            <td>
                                <?= $data->varian ?>
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Keterangan
                            </th>

                            <td>
                                <?= $data->varian_keterangan ?>
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Box / Kg
                            </th>

                            <td>
                                <?= number_format(
                                    $data->box_kg,
                                    2
                                ) ?>
                                Kg
                            </td>

                        </tr>


                        <tr>

                            <th>
                                User
                            </th>

                            <td>
                                <?= $data->fullname ?>
                            </td>

                        </tr>


                        <tr>

                            <th>
                                Tanggal
                            </th>

                            <td>

                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime(
                                        $data->created_at
                                    )
                                ) ?>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>


        <!-- =====================================================
             RINGKASAN SORTASI
        ====================================================== -->

        <?php

        $rework =
            $badpro_summary->rework_kg ?? 0;

        $reject =
            $badpro_summary->reject_kg ?? 0;

        $total_bad =
            $badpro_summary->total_bad_kg ?? 0;


        $total_sortasi_kg =
            ($data->jumlah_wip ?? 0)
            *
            ($data->box_kg ?? 0);


        $persen_bad =
            ($total_sortasi_kg > 0)
            ? (
                ($total_bad / $total_sortasi_kg)
                * 100
            )
            : 0;


        $sisa_wip =
            ($data->jumlah_wip ?? 0)
            -
            ($data->jml_release ?? 0);


        $sisa_sortasi =
            ($data->filkar_box ?? 0)
            -
            ($data->sortasi_box ?? 0);

        ?>


        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-header bg-success text-white">

                    <b>
                        Ringkasan Sortasi
                    </b>

                </div>


                <div class="card-body">

                    <table class="table table-borderless table-sm">


                        <tr>

                            <th width="45%">
                                Jumlah Sortasi
                            </th>

                            <td class="text-right">

                                <b>
                                    <?= $data->jumlah_wip ?>
                                    Box
                                </b>

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Release
                            </th>

                            <td class="text-right">

                                <?= $data->jml_release ?>
                                Box

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Belum Sortasi
                            </th>

                            <td class="text-right">

                                <?= $sisa_sortasi ?>
                                Box

                            </td>

                        </tr>





                        <tr>

                            <td colspan="2">

                                <hr class="my-2">

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Bad Rework
                            </th>

                            <td class="text-right">

                                <?= number_format(
                                    $rework,
                                    2
                                ) ?>

                                Kg

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Bad Reject
                            </th>

                            <td class="text-right">

                                <?= number_format(
                                    $reject,
                                    2
                                ) ?>

                                Kg

                            </td>

                        </tr>


                        <tr class="font-weight-bold">

                            <th>
                                Total Bad
                            </th>

                            <td class="text-right text-danger">

                                <?= number_format(
                                    $total_bad,
                                    2
                                ) ?>

                                Kg

                            </td>

                        </tr>


                        <tr>

                            <td colspan="2">

                                <hr class="my-2">

                            </td>

                        </tr>


                        <tr class="font-weight-bold">

                            <th>
                                Persentase Bad
                            </th>

                            <td class="text-right
                                <?= ($persen_bad > 5)
                                    ? 'text-danger'
                                    : 'text-success'
                                ?>">

                                <?= number_format(
                                    $persen_bad,
                                    2
                                ) ?>

                                %

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         DATA BAD PRODUK
    ========================================================== -->

    <div class="card shadow">


        <div class="card-header bg-info text-white">

            <b>
                Data Bad Produk
            </b>

        </div>


        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover">


                    <thead class="bg-secondary text-white">

                        <tr>

                            <th width="5%" class="text-center">
                                No
                            </th>

                            <th>
                                Bad Produk
                            </th>

                            <th width="15%">
                                Kategori
                            </th>

                            <th width="20%" class="text-right">
                                Berat
                            </th>

                            <th width="35%">
                                Mesin Dominan
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if (empty($badpro)) : ?>


                            <tr>

                                <td colspan="5" class="text-center text-muted">

                                    Tidak ada data bad produk.

                                </td>

                            </tr>


                        <?php else : ?>


                            <?php $no = 1; ?>


                            <?php foreach ($badpro as $bp) : ?>


                                <?php

                                $kategori_nama =
                                    ($bp->kategori == 1)
                                    ? 'Rework'
                                    : 'Reject';

                                ?>


                                <tr>


                                    <!-- NO -->

                                    <td class="text-center">

                                        <?= $no++ ?>

                                    </td>


                                    <!-- BAD PRODUK -->

                                    <td>

                                        <b>
                                            <?= $bp->nama_badpro ?>
                                        </b>

                                    </td>


                                    <!-- KATEGORI -->

                                    <td>

                                        <?php if ($bp->kategori == 1) : ?>

                                            <span class="badge badge-warning">

                                                Rework

                                            </span>

                                        <?php else : ?>

                                            <span class="badge badge-danger">

                                                Reject

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- BERAT -->

                                    <td class="text-right">

                                        <b>

                                            <?= number_format(
                                                $bp->berat,
                                                2
                                            ) ?>

                                            Kg

                                        </b>

                                    </td>


                                    <!-- MESIN DOMINAN -->

                                    <td>

                                        <?php if (!empty($bp->nama_mesin)) : ?>

                                            <i class="fas fa-industry mr-1"></i>

                                            <?= htmlspecialchars(
                                                $bp->nama_mesin,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php else : ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php endif; ?>


                    </tbody>


                    <?php if (!empty($badpro)) : ?>

                        <tfoot>

                            <tr class="font-weight-bold">

                                <td colspan="3" class="text-right">

                                    Total Bad Produk

                                </td>

                                <td class="text-right text-danger">

                                    <?= number_format(
                                        array_sum(
                                            array_map(
                                                function ($row) {
                                                    return (float) $row->berat;
                                                },
                                                $badpro
                                            )
                                        ),
                                        2
                                    ) ?>

                                    Kg

                                </td>

                                <td></td>

                            </tr>

                        </tfoot>

                    <?php endif; ?>


                </table>

            </div>

        </div>

    </div>


    <!-- =========================================================
         BUTTON KEMBALI
    ========================================================== -->

    <div class="mt-3">

        <a href="<?= base_url('sortasi') ?>" class="btn btn-danger">

            <i class="fa fa-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>