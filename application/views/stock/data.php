<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h1 mb-2 text-gray-800">Stock Bahan Baku</h1>
    </div>
    <?php if($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?php echo $this->session->flashdata('success_msg'); ?>    </div>
            <br>
        <?php endif; ?>
        <?php if($this->session->flashdata('error_msg')): ?>
            <div class="alert alert-danger  text-center">
                <i class="fas fa-times"></i>
                <?php echo $this->session->flashdata('error_msg'); ?>    
            </div>
            <br>
        <?php endif ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="loading" class="text-center my-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select class="form-control mb-4" name="area" id="filter-area">
                            <option value="" selected>Pilih Area</option>
                            <option value="sparepart">SPAREPART</option>
                            <?php foreach ($area as $val) { ?>
                                <option value="<?= $val->uuid ?>"><?= $val->nama_area ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="table bg-info text-light">
                            <tr>
                                <th class='align-middle text-center'>No.</th>
                                <th class='align-middle text-center'>Barang</th>
                                <th class='align-middle text-center'>Area</th>
                                <th class='align-middle text-center'>Quantity</th>
                                <th class='align-middle text-center' width='35%'>Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-tbody">
                            <?php
                            $no = 1;
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td class='align-middle text-center' width="1"><?= $no;?></td>
                                    <td><?= $row->item_barang;?></td>
                                    <td class='align-middle text-center'><?= $row->nama_area;?></td>
                                    <td class='align-middle text-center'><?= $row->total_qty;?></td>
                                    <td class='align-middle text-center'>
                                        <a href="<?= base_url('stock/detail_mp/'. $row->item_barang_uuid) ?>"
                                            class="btn btn-md btn-success shadow-sm"><i
                                            class="fa fa-info fa-sm text-white"></i> Detail</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   


            <script>
                $(document).ready(function() {
                    $('#filter-area').change(function() {
                        var area_uuid = $(this).val();

        // Tampilkan loading sebelum AJAX dimulai
                        $('#loading').show();
        $('#data-tbody').html(''); // Kosongkan tabel sementara
        
        $.ajax({
            url: '<?= base_url('stock/get_filtered_data'); ?>',
            type: 'POST',
            data: { area_uuid: area_uuid },
            dataType: 'json',
            success: function(response) {
                var rows = '';
                var no = 1;

                $.each(response, function(index, item) {
                    rows += `<tr>
                    <td class='align-middle text-center'>${no}</td>
                    <td>${item.item_barang}</td>
                    <td class='align-middle text-center'>${item.nama_area}</td>
                    <td class='align-middle text-center'>${item.total_qty}</td>
                    <td class='align-middle text-center'>
                    <a href="<?= base_url('stock/detail_mp/') ?>${item.item_barang_uuid}" class="btn btn-md btn-success shadow-sm">
                    <i class="fa fa-info fa-sm text-white"></i> Detail
                    </a>
                    </td>
                    </tr>`;
                    no++;
                });

                // Sembunyikan loading setelah data berhasil dimuat
                $('#loading').hide();
                $('#data-tbody').html(rows);
            },
            error: function() {
                $('#loading').hide();
                $('#data-tbody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    });
                });

            </script>
