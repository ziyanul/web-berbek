 <!-- Begin Page Content -->
 <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">History Pergantian Part</h1>
        <div class="col-md-5">

        </div>
    </div>
<!-- DataTales Example -->
<div class="card shadow mb-0">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2">
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

            <div class="col-md-2">
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

            <div class="col-md-2">
                <label>Kondisi</label>
                <select id="filterKondisi" class="form-control">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik">Baik</option>
                    <option value="Warning">Warning</option>
                    <option value="Over Lifetime">Over Lifetime</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Tanggal Dari</label>
                <input type="date" id="startDate" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Tanggal Sampai</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">

                <button id="resetTanggal" class="btn btn-sm btn-secondary mr-2">
                    Reset
                </button>

                <button id="exportExcel" class="btn btn-sm btn-success">
                    <i class="fa fa-download fa-success"></i>
                </button>

            </div>

        </div>
        <div class="card shadow mb-0">
            <div class="card-body">
                <div class="table-responsive">
                 <table class="table table-bordered" id="datatableshistory" width="100%" cellspacing="0">

                    <thead class="bg-info text-white">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th style="display:none;">Area</th>
                            <th>Nama Mesin</th>
                            <th>Nama Part</th>
                            <th>RH End</th>
                            <th>Lifetime Part</th>
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
                                <td data-date="<?= $row->tgl ?>"><?= $row->tanggal;?></td>
                                <td style="display:none;"><?= $row->nama_area ?></td>
                                <td><?= $row->nama_mesin;?></td>
                                <td><?= $row->nama_part;?></td>
                                <td><?= $row->rh_end !== null ? number_format($row->rh_end) : 0;?><?= $row->jadwal == 0 ? ' Hari' : ' Jam'; ?></td>
                                <td><?= $row->lifetime;?></td>

                                <td>
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
                                    <a href="<?= base_url('monitor/history/detail/'.$row->uuid); ?>" class="btn btn-md btn-success btn-block shadow-sm mt-2 mr-2" style="flex: 1;">Detail</a>
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
</div>
</div>
<script>
    $(document).ready(function(){

        var table = $('#datatableshistory').DataTable({
            destroy:true,
            searching:true,
            lengthChange:false,
            dom:'rtip',
            autoWidth: false
        });

    // FILTER RANGE TANGGAL
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){

            var start = $('#startDate').val();
            var end = $('#endDate').val();

            var tanggal = $(table.row(dataIndex).node()).find('td:eq(1)').data('date');
            var rowDate = new Date(tanggal);

            if(!start && !end){
                return true;
            }

            var startDate = start ? new Date(start) : null;
            var endDate = end ? new Date(end) : null;

            if(startDate && rowDate < startDate){
                return false;
            }

            if(endDate && rowDate > endDate){
                return false;
            }

            return true;
        });

    // FILTER AREA
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

    // FILTER MESIN
        $('#filterMesin').on('change', function(){
            table.column(3).search(this.value).draw();
        });

    // FILTER KONDISI
        $('#filterKondisi').on('change', function(){
            table.column(7).search(this.value).draw();
        });

    // FILTER TANGGAL
        $('#startDate, #endDate').on('change', function(){
            table.draw();
        });

        $('#resetTanggal').on('click', function(){

            $('#startDate').val('');
            $('#endDate').val('');

            table.draw();

        });

    });
</script>

<script>
    $('#exportExcel').on('click', function(){

        var area = $('#filterArea').val();
        var mesin = $('#filterMesin').val();
        var kondisi = $('#filterKondisi').val();
        var start = $('#startDate').val();
        var end = $('#endDate').val();

        var url = "<?= base_url('monitor/export_history') ?>?";

        url += "area="+area;
        url += "&mesin="+mesin;
        url += "&kondisi="+kondisi;
        url += "&start="+start;
        url += "&end="+end;

        window.location.href = url;

    });
</script>