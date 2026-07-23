    <!-- Begin Page Content -->
    <div class="container-fluid">

    	<!-- Page Heading -->
    	<h1 class="h3 mb-2 text-gray-800">Tambah Stock Rework</h1>

    	<nav aria-label="breadcrumb">
    		<ol class="breadcrumb">
    			<li class="breadcrumb-item"><a href="<?= base_url('rework');?>"><i class="fas fa-arrow-left"></i> Rework</a></li>
    			<li class="breadcrumb-item active" aria-current="page">Tambah</li>
    		</ol>
    	</nav>

    	<div class="card shadow mb-4">
    		<div class="card-body">
    			<form class="user" action="<?= base_url('rework/tambah');?>" method="post">

    				<div class="form-group row">
    					<div class="col-sm-6 mb-3 mb-sm-0">
    						<label class="form-label">Varian</label>
    						<select class="form-control <?= form_error('varian') ? 'invalid' : '' ?>" name="varian">
    							<option disabled selected>Pilih varian</option>
    							<?php
    							foreach ($data as $row) { ?>
    								<option value="<?= $row->uuid;?>" <?= set_select('varian', $row->uuid);?>><?= $row->varian;?>
    							</option>
    							<?php
    						}
    						?>
    					</select>
    					<div class="invalid-feedback <?= !empty(form_error('varian')) ? 'd-block':'';?>">
    						<?= form_error('varian') ?>
    					</div>
    				</div>
    			</div>
    			<div class="form-group row">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <label class="form-label">Kode Rework</label>
        <input type="text" name="kode_rework" class="form-control <?= form_error('kode_rework') ? 'invalid' : '' ?>" 
               placeholder="contoh: OI24701AA0" value="<?= set_value('kode_rework'); ?>" 
               oninput="this.value = this.value.toUpperCase();">
        <div class="invalid-feedback <?= !empty(form_error('kode_rework')) ? 'd-block' : '';?>">
            <?= form_error('kode_rework') ?>
        </div>
    </div>
</div>
    			<div class="form-group row">
    				<div class="col-sm-6 mb-3 mb-sm-0">
    					<label class="form-label">Berat</label>
    					<input type="number" name="berat" class="form-control <?= form_error('berat') ? 'invalid' : '' ?>" placeholder="satuan KG" value="<?= set_value('berat'); ?>" >
    					<div class="invalid-feedback <?= !empty(form_error('berat')) ? 'd-block':'';?>">
    						<?= form_error('berat') ?>
    					</div>
    				</div>

    			</div>

    			<div class="row">
    				<div class="col">
    					<button type="submit" class="btn btn-md btn-success mr-2">
    						<i class="fa fa-save"></i> Simpan
    					</button>
    					<a href="<?= base_url('rework');?>" class="btn btn-md btn-danger">
    						<i class="fa fa-times"></i> Batal
    					</a>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>

</div>
