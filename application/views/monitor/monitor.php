 <!-- Begin Page Content -->
 <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h3 class="h3 mb-2 text-gray-800">Monitoring Spare Part</h3>
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
    <div class="card shadow mb-0">
        <div class="card-body">
            <div class="row mb-3">

                <div class="col-md-3">
                    <label>Area</label>
                    <select id="filterArea" class="form-control">
                        <option value="">Semua Area</option>
                        <?php 
                        $areas = [];
                        foreach($data as $row){
                            $areas[$row->nama_area] = true;
                        }
                        foreach(array_keys($areas) as $area){ ?>
                            <option value="<?= $area ?>"><?= $area ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Mesin</label>
                    <select id="filterMesin" class="form-control">
                        <option value="">Semua Mesin</option>
                        <?php 
                        $mesins = [];

                        foreach($data as $row){
                            $mesins[$row->nama_mesin] = $row->nama_area;
                        }

                        foreach($mesins as $mesin => $area){ ?>
                            <option value="<?= $mesin ?>" data-area="<?= $area ?>">
                                <?= $mesin ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Kondisi</label>
                    <select id="filterKondisi" class="form-control">
                        <option value="">Semua</option>
                        <option value="Baik">Baik</option>
                        <option value="Warning">Warning</option>
                        <option value="Over Lifetime">Over Lifetime</option>
                    </select>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="datatablesmonitor" width="100%" cellspacing="0">
                    <thead class="table bg-info text-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th style="display:none;">Area</th>
                            <th>Nama Mesin</th>
                            <th>Nama Part</th>
                            <th>RH End</th>
                            <th>Lifetime Part</th>
                            <!-- <th>Status Pengajuan</th> -->

                            <th>Kondisi Part</th>
                            <th>Action</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            ?>

                            <tr>
                                <td width="1"><?= $no; ?></td>
                                <td><?= $row->tanggal;?></td>
                                <td style="display:none;"><?= $row->nama_area ?></td>
                                <td><?= $row->nama_mesin;?></td>
                                <td><?= $row->nama_part;?></td>
                                <td><?= number_format($row->rh_end);?><?= $row->jadwal == 0 ? ' Hari' : ' Jam'; ?></td>
                                <td><?= $row->lifetime;?></td>
                                <!-- <td>
                                    <?php if ($row->status == 0): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($row->status == 1): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php elseif ($row->status == 2): ?>
                                        <span class="badge badge-secondary">History</span>
                                    <?php elseif ($row->status == 3): ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php endif; ?>
                                </td> -->

                                <td class="text-center">
                                    <?php
                                    $badge_map = [
                                        'Over Lifetime'    => ['text' => 'Over Lifetime', 'class' => 'badge badge-danger'],
                                        'Warning' => ['text' => 'Warning', 'class' => 'badge badge-warning'],
                                        'Baik'    => ['text' => 'Baik', 'class' => 'badge badge-success'],
                                    ];
                                    ?>

                                    <span class="<?= $badge_map[$row->kondisi]['class'] ?>">
                                        <?= $badge_map[$row->kondisi]['text'] ?>
                                    </span>
                                </td>
                                <td>

                                    <a href="<?= base_url('monitor/detail/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm mt-2 mr-2" style="flex: 1;"><i class="fas fa-eye text-light"></i> Detail</a>
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
<!-- /.container-fluid -->

<!-- End of Main Content -->

<script>
 $(document).ready(function(){

    var table = $('#datatablesmonitor').DataTable({
        destroy:true,
        searching:true,
        lengthChange:false,
        autoWidth: false,
        dom:'rtip'
    });

    $('#filterArea').on('change', function(){

        var area = $(this).val();

        table.column(2).search(area).draw();

        $('#filterMesin').val("");

        $('#filterMesin option').each(function(){

            var mesinArea = $(this).data('area');

            if($(this).val() == ""){
                $(this).show();
                return;
            }

            if(area == "" || mesinArea == area){
                $(this).show();
            }else{
                $(this).hide();
            }

        });

    });

    $('#filterMesin').on('change', function(){
        table.column(3).search(this.value).draw();
    });

    $('#filterKondisi').on('change', function(){
        table.column(7).search(this.value).draw();
    });

});
</script>