<div class="container-fluid">

	<!-- Page Heading -->
	<h3 class="h3 mb-2 text-gray-800">Detail "" </h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url('counter') ?>"><i class="fas fa-arrow-left mr-2"></i>Counter Batch</a></li>
			<li class="breadcrumb-item active" aria-current="page">Detail</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<!-- Page Heading -->
				<p class="font-weight-bold">Tanggal : <?= $data->tanggal ?></p>
				<p class="font-weight-bold">Varian : <?= $data->vrn ?></p>

			</div>
			<table class=" table table-bordered table-sm mb-1">
				<tr>
					<td rowspan="4" width="200">foto logo</td>
					<td rowspan="2" class="align-middle text-center font-weight-bold" width="600">FORM</td>
					<td>No Dokumen</td>
					<td>: FR - Prod - 02</td>
				</tr>
				<tr>
					<td>Revisi</td>
					<td>: 2</td>
				</tr>
				<tr>
					<td rowspan="2" class="align-middle text-center font-weight-bold" width="600">PEMAKAIN PVDC & WIRE</td>
					<td>Tanggal Efektif</td>
					<td>: 01/04/2016</td>
				</tr>
				<tr>
					<td>Halaman</td>
					<td>: 1</td>
				</tr>
			</table>
			<table class="mb-1">
				<tr>
					<td width="200">Hari & Tanggal</td>
					<td width="10">:</td>
					<td class="font-weight-bold"><?= $data->tanggal ?></td>
				</tr>
				<tr>
					<td width="200">Varian</td>
					<td width="10">:</td>
					<td class="font-weight-bold"><?= $data->vrn ?></td>
				</tr>
			</table>
			<table class="table table-bordered">
        <thead>
            <tr>
                <th>Batch</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
                <th>13</th>
                <th>14</th>
                <th>Total</th>
                <th>PVDC</th>
                <th>WIRE</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>

			<a href="<?= base_url('counter') ?>" class="btn btn-md btn-primary mt-3">
				<i class="fa fa-arrow-left"></i> Kembali
			</a>
			
			
		</div>
	</div>
