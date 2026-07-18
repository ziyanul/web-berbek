<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<!-- Page Heading -->
		<h1 class="h3 mb-2 text-gray-800">Monitoring Preventive Maintenance</h1>
		
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
  <div class="card shadow mb-4">
    <div class="card-header py-3">
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
          <label>Status Pemeliharaan</label>
          <select id="filterStatus" class="form-control">
            <option value="">Semua</option>
            <option value="Urgent">Urgent</option>
            <option value="Top Urgent">Top Urgent</option>
          </select>
        </div>

      </div>
    </div>
    <div class="card-body">
     <div class="table-responsive">
      <table class="table table-bordered" id="datatablesmain" width="100%" cellspacing="0">
       <thead class="table bg-info text-light">
        <tr>
         <th class="font-weight-bold align-middle text-center" width="1">No</th>
         <th width="80" class="font-weight-bold align-middle text-center">Tanggal</th>
         <th class="font-weight-bold align-middle text-center">Area</th>
         <th class="font-weight-bold align-middle text-center">Mesin</th>
         <th class="font-weight-bold align-middle text-center">Keluhan</th>
         <th class="font-weight-bold align-middle text-center">Pengaju</th>
         <th width="30" class="font-weight-bold align-middle text-center">Total Pending</th>
         <th width="100" class="font-weight-bold align-middle text-center">Status Pemeliharaan</th>
         <th class="font-weight-bold align-middle text-center">Tindakan</th>
         <th class="font-weight-bold align-middle text-center">Status ACC</th>

         <th class="font-weight-bold align-middle text-center">Action</th>
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
         <td><?= $tanggal;?></td>
         <td><?= $row->nama_area; ?></td>
         <td><?= $row->nama_mesin;?></td>
         <td><?= $row->keluhan;?></td>
         <td><?= $row->username;?></td>
         <td><?= $row->selisih;?></td>
         <td><?= $row->kondisi;?></td>
         <td><?= $row->tindakan;?></td>
         <td><?= $row->status_mesin;?></td>
         <!-- <td><?= $row->nama_operator;?></td> -->
         <td>

          <a href="<?= base_url('pm/detail/'.$row->uuid); ?>" 
           class="btn btn-md btn-success btn-block">
           Detail
         </a>

         <?php if (is_engineering() || is_admin()): ?>
         <a href="<?= base_url('pm/tindakan/'.$row->uuid); ?>" 
           class="btn btn-md btn-warning btn-block">
           Tindakan
         </a>
       <?php endif; ?>


       <?php if(is_warehouse() || is_produksi() || is_admin()): ?>

       <?php if (empty($row->tindakan) || empty($row->nama_pelaksana)): ?>
       <a href="<?= base_url('pm/edit/'.$row->uuid); ?>" 
         class="btn btn-md btn-warning btn-block">
         Edit
       </a>
     <?php endif; ?>


     <?php
     $type = $this->session->userdata('type');

     if ((is_produksi() || is_warehouse() || is_admin()) && ($type == 1 || $type == 2) && !empty($row->tindakan_at)):
      ?>
    <a href="<?= base_url('pm/status/'.$row->uuid); ?>" 
     class="btn btn-md btn-info btn-block"
     onclick="return confirm('Acc Tindakan PM ini?')">
     ACC
   </a>
 <?php endif; ?>


 <?php if (empty($row->nama_pelaksana)): ?>
  <a href="<?= base_url('pm/delete_kegiatan/'.$row->uuid); ?>" 
   class="btn btn-md btn-danger shadow-sm btn-block"
   onclick="return confirm('Anda yakin ingin menghapus data ini?')">
   Hapus
 </a>
<?php endif; ?>

<?php endif; ?>

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

    var table = $('#datatablesmain').DataTable({
      destroy:true,
      searching:true,
      lengthChange:false,
      autoWidth:false,
      dom:'rtip'
    });

    // FILTER AREA
    $('#filterArea').on('change', function(){

      var area = $(this).val();

      table.column(2).search(area).draw();

        // reset mesin
      $('#filterMesin').val('');

        // hide mesin yang tidak sesuai area
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

      var mesin = $(this).val();

      table.column(3).search(mesin).draw();

    });

    // FILTER STATUS
    $('#filterStatus').on('change', function(){

      var status = $(this).val();

      table.column(7)
      .search(status ? '^'+status+'$' : '', true, false)
      .draw();

    });


  });
</script>