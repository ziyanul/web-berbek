$(function () {
    $('#btnTampilkan').on('click', function (e) {
        e.preventDefault();
        loadAnalisa();
    });
    $('#btnReset').on('click', function (e) {
        e.preventDefault();
        $('#tanggal_awal').val(defaultAwal);
        $('#tanggal_akhir').val(defaultAkhir);
        $('#varian').val('');
        $('#mesin').val('');
        $('#badpro').val('');
        clearAnalisa();
    });
});
function loadAnalisa()
{
    $.ajax({
        url: base_url + "yieldportal/ajax_analisa",
        type: "POST",
        dataType: "json",
        data: {
            tanggal_awal : $('#tanggal_awal').val(),
            tanggal_akhir : $('#tanggal_akhir').val(),
            varian : $('#varian').val(),
            mesin : $('#mesin').val(),
            badpro : $('#badpro').val()
        },
        beforeSend:function(){
            $('#btnTampilkan')
                .prop('disabled',true)
                .html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        },
        success:function(res){
            $('#ringkasan-container').html(res.ringkasan);
            $('#monitoring-container').html(res.monitoring);
            $('#badproduk-varian-container').html(res.badproduk_varian);
            $('#badproduk-mesin-container').html(res.badproduk_mesin);
            $('#detail-batch-container').html(res.detail_batch);
        },
        error:function(){
            alert('Gagal mengambil data.');
        },
        complete:function(){
            $('#btnTampilkan')
                .prop('disabled',false)
                .html('<i class="fa fa-search"></i> Tampilkan');
        }
    });
}
function clearAnalisa()
{
    const info = `
        <div class="text-center text-muted p-5">
            Silakan pilih filter kemudian klik
            <b>Tampilkan</b>
        </div>
    `;
    $('#ringkasan-container').html(info);
    $('#monitoring-container').html('');
    $('#badproduk-varian-container').html('');
    $('#badproduk-mesin-container').html('');
    $('#detail-batch-container').html('');
}