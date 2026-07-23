<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">History Preventive Maintenance</h1>
		<!-- <a href="#" class="btn btn-md btn-primary shadow-sm mr-4"><i class="fas fa-print fa-sm text-white mr-2"></i>Simpan</a> -->
	</div>
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header">

			<!-- BARIS 1 -->
			<div class="row mb-3">

				<div class="col-md-3">
					<label>Area</label>
					<select id="filterArea" class="form-control">
						<option value="">Semua</option>
						<?php
						$areas=[];
						foreach($data as $row){
							$areas[$row->nama_area]=true;
						}
						foreach(array_keys($areas) as $area){ ?>
							<option value="<?= $area ?>"><?= $area ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<label>Mesin</label>
					<select id="filterMesin" class="form-control">
						<option value="">Semua</option>
						<?php
						$mesins=[];
						foreach($data as $row){
							$mesins[$row->nama_mesin]=$row->nama_area;
						}
						foreach($mesins as $mesin=>$area){ ?>
							<option value="<?= $mesin ?>" data-area="<?= $area ?>">
								<?= $mesin ?>
							</option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<label>Status</label>
					<select id="filterStatus" class="form-control">
						<option value="">Semua</option>
						<option value="Pengajuan">Pengajuan</option>
						<option value="Urgent">Urgent</option>
						<option value="Top Urgent">Top Urgent</option>
					</select>
				</div>

				<div class="col-md-3">
					<label>Total Pending</label>
					<select id="filterPending" class="form-control">
						<option value="">Semua</option>
						<option value="0-7">0 - 7</option>
						<option value="8-14">8 - 14</option>
						<option value="15-30">15 - 30</option>
						<option value="30+">30+</option>
					</select>
				</div>

			</div>

			<!-- BARIS 2 -->
			<div class="row">

				<div class="col-md-4">
					<label>Range Tanggal Pengajuan</label>
					<div class="input-group">
						<input type="date" id="startDate" class="form-control">
						<div class="input-group-prepend input-group-append">
							<span class="input-group-text">s/d</span>
						</div>
						<input type="date" id="endDate" class="form-control">
					</div>
				</div>

				<div class="col-md-3 d-flex align-items-end">
					<button id="downloadExcel" class="btn btn-success btn-block">
						<i class="fa fa-file-excel"></i> Download Excel
					</button>
				</div>

			</div>

		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="datatablesmainhis" width="100%" cellspacing="0">
					<thead class="table bg-info text-light">
						<tr>
							<th width="1">No.</th>
							<th>Tanggal</th>
							<th>Area</th>
							<th>Mesin</th>
							<th>Keluhan</th>
							<th>Total Pending</th>
							<th>Status Pemeliharaan</th>
							<th>Tindakan Perbaikan</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$no = 1;
						foreach ($data as $row) {
							$tgl = strtotime($row->created_at);
							$tanggal = date('d M Y', $tgl);
							?>
							<tr>
								<td width="1"><?= $no;?></td>
								<td data-date="<?= date('Y-m-d',$tgl) ?>">
									<?= $tanggal;?>
								</td>
								<td><?= $row->nama_area;?></td>
								<td><?= $row->nama_mesin;?></td>
								<td><?= $row->keluhan;?></td>
								<td><?= $row->selisih;?></td>
								<td><?= $row->kondisi;?></td>
								<td><?= $row->tindakan;?></td>
								<td>
									<a href="<?= base_url('pm/history/detail/'.$row->uuid); ?>" class="btn btn-md btn-success shadow-sm">Detail</a>

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


<script>

	$(document).ready(function(){

		var table = $('#datatablesmainhis').DataTable({
			destroy:true,
			searching:true,
			lengthChange:false,
			autoWidth:false,
			dom:'rtip'
		});


// FILTER AREA
		$('#filterArea').on('change',function(){

			var area=$(this).val();

			table.column(2).search(area).draw();

			$('#filterMesin').val('');

			$('#filterMesin option').each(function(){

				var mesinArea=$(this).data('area');

				if($(this).val()==""){
					$(this).show();
					return;
				}

				if(area=="" || mesinArea==area){
					$(this).show();
				}else{
					$(this).hide();
				}

			});

		});


// FILTER MESIN
		$('#filterMesin').on('change',function(){
			table.column(3).search(this.value).draw();
		});


// FILTER STATUS
		$('#filterStatus').on('change',function(){
			table.column(6).search(this.value).draw();
		});



// FILTER RANGE TANGGAL
		$.fn.dataTable.ext.search.push(function(settings,data,dataIndex){

			var start=$('#startDate').val();
			var end=$('#endDate').val();

			var tanggal=$(table.row(dataIndex).node()).find('td:eq(1)').data('date');
			var rowDate=new Date(tanggal);

			if(!start && !end){
				return true;
			}

			var startDate=start ? new Date(start):null;
			var endDate=end ? new Date(end):null;

			if(startDate && rowDate<startDate){
				return false;
			}

			if(endDate && rowDate>endDate){
				return false;
			}

			return true;

		});

		$('#startDate,#endDate').on('change',function(){
			table.draw();
		});



// FILTER TOTAL PENDING
		$.fn.dataTable.ext.search.push(function(settings,data,dataIndex){

			var filter=$('#filterPending').val();

			if(filter==""){
				return true;
			}

			var pending=parseInt(data[5]) || 0;

			if(filter=="0-7"){
				return pending>=0 && pending<=7;
			}

			if(filter=="8-14"){
				return pending>=8 && pending<=14;
			}

			if(filter=="15-30"){
				return pending>=15 && pending<=30;
			}

			if(filter=="30+"){
				return pending>30;
			}

			return true;

		});

		$('#filterPending').on('change',function(){
			table.draw();
		});

		$('#downloadExcel').click(function(){

			var area = $('#filterArea').val();
			var mesin = $('#filterMesin').val();
			var status = $('#filterStatus').val();
			var start = $('#startDate').val();
			var end = $('#endDate').val();
			var pending = $('#filterPending').val();

			var url = "<?= base_url('Pm/export_excel') ?>?area="+area+
			"&mesin="+mesin+
			"&status="+status+
			"&start="+start+
			"&end="+end+
			"&pending="+pending;

			window.location.href = url;

		});

	});

</script>