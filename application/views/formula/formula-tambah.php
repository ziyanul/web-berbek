<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">Tambah Formula</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('formula') ?>">
					<i class="fas fa-arrow-left mr-2"></i>Master Formula
				</a>
			</li>
			<li class="breadcrumb-item active">Tambah</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form action="<?= base_url('formula/tambah') ?>" method="post">

				<!-- MASTER -->
				<h5 class="font-weight-bold text-primary">Data Formula</h5>

				<div class="form-group row">
					<label class="col-md-2 col-form-label">Varian</label>
					<div class="col-md-4">
						<select name="varian_uuid" class="form-control" required>
							<option value="">Pilih Varian</option>

							<?php foreach($varian as $v): ?>
								<option value="<?= $v->uuid ?>">
									<?= $v->varian ?> ( <?= $v->keterangan ?> )
								</option>
							<?php endforeach; ?>

						</select>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-2 col-form-label">Nama Formula</label>
					<div class="col-md-4">
						<input
						type="text"
						name="nama_formula"
						class="form-control"
						required>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-2 col-form-label">Keterangan</label>
					<div class="col-md-6">
						<textarea
						name="keterangan"
						class="form-control"
						rows="3"></textarea>
					</div>
				</div>

				<hr>

				<!-- DETAIL -->
				<div class="d-flex justify-content-between align-items-center mb-3">

					<h5 class="font-weight-bold text-primary mb-0">
						Detail Bahan
					</h5>

					<button
					type="button"
					class="btn btn-sm btn-success"
					onclick="tambahBaris()">

					<i class="fa fa-plus"></i>
					Tambah Bahan

				</button>

			</div>

			<div class="table-responsive">

				<table class="table table-bordered" id="tbl_bahan">

					<thead class="thead-light">
						<tr>
							<th width="5%">#</th>
							<th width="40%">Bahan</th>
							<th width="20%">Qty</th>
							<th width="15%">Aksi</th>
						</tr>
					</thead>

					<tbody id="body_bahan">

						<tr>
							<td class="text-center nomor">1</td>

							<td>

								<select
								name="bahan_uuid[]"
								class="form-control"
								required>

								<option value="">
									Pilih Bahan
								</option>

								<?php foreach($bahan as $b): ?>

									<option
									value="<?= $b->uuid ?>"
									data-nama="<?= $b->nama_bahan ?>">

									<?= $b->nama_bahan ?>

								</option>

							<?php endforeach; ?>

						</select>

						<input
						type="hidden"
						name="nama_bahan[]">

					</td>

					<td>

						<input
						type="number"
						step="0.001"
						min="0"
						class="form-control qty"
						name="qty[]"
						required>

					</td>

					<td class="text-center">

						<button
						type="button"
						class="btn btn-danger btn-sm"
						onclick="hapusBaris(this)">

						<i class="fa fa-trash"></i>

					</button>

				</td>

			</tr>

		</tbody>

		<tfoot>
			<tr>
				<th colspan="2" class="text-right">
					Total Formula
				</th>

				<th>
					<span id="total_formula">0</span>
				</th>

				<th></th>
			</tr>
		</tfoot>

	</table>

</div>

<input
type="hidden"
name="total"
id="total_input">

<hr>

<button type="submit" class="btn btn-success">
	<i class="fa fa-save"></i>
	Simpan
</button>

<a href="<?= base_url('formula') ?>" class="btn btn-danger">
	<i class="fa fa-times"></i>
	Batal
</a>

</form>

</div>
</div>

</div>

<script>

	function updateNomor()
	{
		let rows = document.querySelectorAll('#body_bahan tr');

		rows.forEach(function(row,index){
			row.querySelector('.nomor').innerHTML = index + 1;
		});
	}

	function hitungTotal()
	{
		let total = 0;

		document.querySelectorAll('.qty').forEach(function(item){

			total += parseFloat(item.value) || 0;

		});

		document.getElementById('total_formula').innerHTML =
		total.toLocaleString('id-ID');

		document.getElementById('total_input').value = total;
	}

	function tambahBaris()
	{
		let tbody = document.getElementById('body_bahan');

		let row = tbody.rows[0].cloneNode(true);

		row.querySelectorAll('input').forEach(function(input){
			input.value = '';
		});

		row.querySelectorAll('select').forEach(function(select){
			select.selectedIndex = 0;
		});

		tbody.appendChild(row);

		updateNomor();
	}

	function hapusBaris(btn)
	{
		let tbody = document.getElementById('body_bahan');

		if(tbody.rows.length == 1)
		{
			alert('Minimal 1 bahan');
			return;
		}

		btn.closest('tr').remove();

		updateNomor();
		hitungTotal();
	}

	document.addEventListener('input', function(e){

		if(e.target.classList.contains('qty'))
		{
			hitungTotal();
		}

	});

	document.addEventListener('change', function(e){

		if(e.target.name == 'bahan_uuid[]')
		{
			let nama =
			e.target.options[e.target.selectedIndex]
			.getAttribute('data-nama');

			e.target.closest('td')
			.querySelector('input[name="nama_bahan[]"]')
			.value = nama;
		}

	});

	hitungTotal();

</script>