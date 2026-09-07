(function ($) {
    'use strict';

    function filters() {
        return {
            tanggal_awal: $('#tanggal_awal').val(),
            tanggal_akhir: $('#tanggal_akhir').val(),
            plan: $('#plan').val(),
            batch: $('#batch').val(),
            varian: $('#varian').val(),
            mesin: $('#mesin').val(),
            badpro: $('#badpro').val()
        };
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function fillOptions(data, current) {
        var planHtml = '<option value="">Semua Plan</option>';
        var batchHtml = '<option value="">Semua Batch</option>';
        (data.plans || []).forEach(function (p) {
            planHtml += '<option value="' + escapeHtml(p.uuid) + '">' + escapeHtml(p.label) + '</option>';
            batchHtml += '<optgroup label="' + escapeHtml(p.label) + '">';
            (p.batches || []).forEach(function (b) {
                batchHtml += '<option value="' + escapeHtml(b.uuid) + '">' + escapeHtml(b.kode_batch) + '</option>';
            });
            batchHtml += '</optgroup>';
        });
        $('#plan').html(planHtml);
        $('#batch').html(batchHtml);

        var vHtml = '<option value="">Semua Varian</option>';
        (data.varian || []).forEach(function (v) { vHtml += '<option value="' + escapeHtml(v.uuid) + '">' + escapeHtml(v.varian) + '</option>'; });
        $('#varian').html(vHtml);

        var mHtml = '<option value="">Semua Mesin</option>';
        (data.mesin || []).forEach(function (m) { mHtml += '<option value="' + escapeHtml(m.uuid) + '">' + escapeHtml(m.nama_mesin) + '</option>'; });
        $('#mesin').html(mHtml);

        var bHtml = '<option value="">Semua Bad Produk</option>';
        (data.badpro || []).forEach(function (b) { bHtml += '<option value="' + escapeHtml(b.uuid) + '">' + escapeHtml(b.nama_badpro) + '</option>'; });
        $('#badpro').html(bHtml);

        // Hanya pertahankan nilai yang masih valid dalam scope terbaru.
        ['plan', 'batch', 'varian', 'mesin', 'badpro'].forEach(function (id) {
            var value = current[id] || '';
            if ($('#' + id + ' option[value="' + CSS.escape(value) + '"]').length) $('#' + id).val(value);
            else $('#' + id).val('');
        });
    }

    function refreshFilterOptions() {
        var current = filters();
        $.ajax({
            url: base_url + 'yieldportal/ajax_filter_options',
            type: 'POST',
            dataType: 'json',
            data: current,
            success: function (res) {
                if (!res || !res.options) {
                    $('#filterHint').text('Pilihan filter gagal dimuat. Periksa endpoint AJAX / session.');
                    return;
                }
                fillOptions(res.options, current);
                if (res.scope) updateScope(res.scope, false);
            }
        });
    }

    function updateScope(scope, enabled) {
        $('#scopeBadge').text(scope.label || 'Tidak ada data');
        $('#filterHint').text('Scope: ' + (scope.label || 'Tidak ada data'));
        $('#btnExcel').prop('disabled', !enabled || !scope.total_batch);
        $('#navBadProduk').toggle(!!scope.has_badpro);
        if (!scope.has_badpro && $('#badproduk-tab').hasClass('active')) $('#ringkasan-tab').tab('show');
    }

    function loadAnalisa() {
        var data = filters();
        $('#btnTampilkan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memuat...');
        $.ajax({
            url: base_url + 'yieldportal/ajax_analisa',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (res) {
                $('#ringkasan-container').html(res.ringkasan || '');
                $('#monitoring-container').html(res.monitoring || '');
                $('#badproduk-varian-container').html(res.badproduk_varian || '');
                $('#badproduk-mesin-container').html(res.badproduk_mesin || '');
                $('#detail-batch-container').html(res.detail_batch || '');
                updateScope(res.scope || { label: 'Tidak ada data', total_batch: 0, has_badpro: false }, true);
            },
            error: function (xhr) {
                var detail = '';
                try {
                    var err = JSON.parse(xhr.responseText || '{}');
                    detail = err.error ? ' ' + escapeHtml(err.error) : '';
                } catch (e) {}
                $('#ringkasan-container').html('<div class="alert alert-danger"><b>Data analisa gagal dimuat.</b> HTTP ' + xhr.status + '.' + (detail ? '<br><small>' + detail + '</small>' : '') + '</div>');
                $('#scopeBadge').text('Gagal memuat data');
                $('#filterHint').text('Periksa error detail di atas.');
                $('#btnExcel').prop('disabled', true);
            },
            complete: function () {
                $('#btnTampilkan').prop('disabled', false).html('<i class="fa fa-search mr-1"></i> Tampilkan');
            }
        });
    }

    function resetFilter() {
        $('#tanggal_awal').val(defaultAwal);
        $('#tanggal_akhir').val(defaultAkhir);
        $('#plan,#batch,#varian,#mesin,#badpro').val('');
        $('#btnExcel').prop('disabled', true);
        $('#scopeBadge').text('Belum ditampilkan');
        $('#filterHint').text('');
        refreshFilterOptions();
        $('#ringkasan-container').html('<div class="text-center text-muted p-5">Silakan pilih filter kemudian klik <b>Tampilkan</b>.</div>');
        $('#monitoring-container,#badproduk-varian-container,#badproduk-mesin-container,#detail-batch-container').empty();
    }

    function exportExcel() {
        var q = $.param(filters());
        window.location.href = base_url + 'yieldportal/export_excel?' + q;
    }

    $(function () {
        // Cascading hanya memperbarui pilihan; hasil analisa tetap dimuat saat Tampilkan.
        $('#tanggal_awal,#tanggal_akhir,#plan,#batch,#varian,#mesin,#badpro').on('change', function () {
            var id = this.id;
            // Filter induk mengosongkan pilihan turunan agar tidak menyisakan
            // kombinasi yang sudah tidak valid. Filter lain tetap dipertahankan.
            if (id === 'tanggal_awal' || id === 'tanggal_akhir') {
                $('#plan,#batch').val('');
            } else if (id === 'plan') {
                $('#batch').val('');
            }
            refreshFilterOptions();
        });
        $('#btnTampilkan').on('click', function (e) { e.preventDefault(); loadAnalisa(); });
        $('#btnReset').on('click', function (e) { e.preventDefault(); resetFilter(); });
        $('#btnExcel').on('click', function (e) { e.preventDefault(); if (!$(this).prop('disabled')) exportExcel(); });
        refreshFilterOptions();
        // Tampilkan data default (tanggal 1 sampai hari ini) tanpa harus klik ulang.
        loadAnalisa();
    });
})(jQuery);
