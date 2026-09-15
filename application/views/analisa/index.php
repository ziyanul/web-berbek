<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Analisa Produksi</h1>
            <div class="text-muted small">Analisa perjalanan produksi, performa mesin, bad product dan yield.</div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Filter Analisa</h6></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" id="start" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Tanggal Akhir</label>
                    <input type="date" id="end" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Varian <small class="text-muted">(opsional)</small></label>
                    <select id="varian_uuid" class="form-control"><option value="">Semua Varian</option></select>
                </div>
                <div class="form-group col-md-3">
                    <label>Plan Produksi <small class="text-muted">(opsional)</small></label>
                    <select id="plan_uuid" class="form-control"><option value="">Semua Plan</option></select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Kode Batch <small class="text-muted">(opsional)</small></label>
                    <select id="batch_uuid" class="form-control"><option value="">Semua Batch</option></select>
                </div>
                <div class="form-group col-md-3">
                    <label>Proses <small class="text-muted">(opsional)</small></label>
                    <select id="proses_uuid" class="form-control"><option value="">Semua Proses</option></select>
                </div>
                <div class="form-group col-md-3">
                    <label>Bad Product <small class="text-muted">(opsional)</small></label>
                    <select id="badpro_uuid" class="form-control"><option value="">Semua Bad Product</option></select>
                </div>
                <div class="form-group col-md-3">
                    <label>Mode Analisa</label>
                    <select id="mode" class="form-control">
                        <option value="journey">Perjalanan Batch</option>
                        <option value="performance">Performa Mesin</option>
                        <option value="badpro" selected>Bad Product</option>
                        <option value="yield">Yield</option>
                    </select>
                </div>
            </div>
            <div>
                <button id="btnAnalisa" class="btn btn-primary"><i class="fas fa-chart-line mr-1"></i> Tampilkan Analisa</button>
                <button id="btnExport" class="btn btn-success d-none"><i class="fas fa-file-excel mr-1"></i> Export Excel</button>
            </div>
        </div>
    </div>
    <div id="result" class="mb-4"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const base = '<?= base_url('analisa') ?>';
    const $ = id => document.getElementById(id);
    let trendChart = null;
    let lastBadData = null;
    function esc(v) {
        return String(v ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
    }
    function num(v, d=3) { return Number(v || 0).toLocaleString('id-ID', {minimumFractionDigits:d, maximumFractionDigits:d}); }
    async function get(url) {
        const r = await fetch(url, {headers: {'X-Requested-With':'XMLHttpRequest'}});
        let data = null;
        try { data = await r.json(); } catch (_) {}
        if (!r.ok) throw new Error((data && data.error) ? data.error : ('HTTP ' + r.status));
        if (data && data.error) throw new Error(data.error);
        return data;
    }
    function params(extra = {}) {
        return new URLSearchParams({
            start: $('start').value, end: $('end').value,
            varian_uuid: $('varian_uuid').value, plan_uuid: $('plan_uuid').value,
            batch_uuid: $('batch_uuid').value, proses_uuid: $('proses_uuid').value,
            badpro_uuid: $('badpro_uuid').value, ...extra
        }).toString();
    }
    function selectedNames() {
        return {
            varian_name: $('varian_uuid').selectedOptions[0]?.text || '',
            proses_name: $('proses_uuid').selectedOptions[0]?.text || '',
            badpro_name: $('badpro_uuid').selectedOptions[0]?.text || ''
        };
    }
    async function loadVariants() {
        const keep = $('varian_uuid').value;
        const data = await get(base + '/variants?' + params({varian_uuid:''}));
        $('varian_uuid').innerHTML = '<option value="">Semua Varian</option>' + data.map(x => `<option value="${esc(x.uuid)}">${esc(x.varian)}</option>`).join('');
        if (data.some(x => x.uuid === keep)) $('varian_uuid').value = keep;
    }
    async function loadPlans() {
        const keep = $('plan_uuid').value;
        const data = await get(base + '/plans?' + params({plan_uuid:'', batch_uuid:''}));
        $('plan_uuid').innerHTML = '<option value="">Semua Plan</option>' + data.map(x => `<option value="${esc(x.uuid)}">${esc(x.tanggal)} - Plan ${esc(x.plan)} - ${esc(x.varian || '-')} (${esc(x.jumlah_batch)} batch)</option>`).join('');
        if (data.some(x => x.uuid === keep)) $('plan_uuid').value = keep;
        await loadBatches();
    }
    async function loadBatches() {
        const keep = $('batch_uuid').value;
        const data = await get(base + '/batches?' + params({batch_uuid:''}));
        $('batch_uuid').innerHTML = '<option value="">Semua Batch</option>' + data.map(x => `<option value="${esc(x.uuid)}">${esc(x.kode_batch)} - ${esc(x.varian || '-')}</option>`).join('');
        if (data.some(x => x.uuid === keep)) $('batch_uuid').value = keep;
    }
    async function loadProcesses() {
        const data = await get(base + '/processes');
        $('proses_uuid').innerHTML = '<option value="">Semua Proses</option>' + data.map(x => `<option value="${esc(x.uuid)}">${esc(x.kode || x.nama_proses)}</option>`).join('');
    }
    async function loadBadproducts() {
        const keep = $('badpro_uuid').value;
        const data = await get(base + '/badproducts?' + params({badpro_uuid:''}));
        $('badpro_uuid').innerHTML = '<option value="">Semua Bad Product</option>' + data.map(x => `<option value="${esc(x.uuid)}">${esc(x.nama_badpro)} — ${num(x.total_kg)} kg</option>`).join('');
        if (data.some(x => x.uuid === keep)) $('badpro_uuid').value = keep;
    }
    function card(title, value, sub='') {
        return `<div class="col-md-3 mb-3"><div class="card shadow-sm h-100"><div class="card-body"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">${esc(title)}</div><div class="h5 mb-0 font-weight-bold text-gray-800">${esc(value)}</div><div class="small text-muted mt-1">${esc(sub)}</div></div></div></div>`;
    }
    function table(title, headers, rows, extra='') {
        return `<div class="card shadow mb-4"><div class="card-header d-flex justify-content-between align-items-center"><b>${esc(title)}</b>${extra}</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead><tr>${headers.map(h=>`<th>${esc(h)}</th>`).join('')}</tr></thead><tbody>${rows || `<tr><td colspan="${headers.length}" class="text-center text-muted py-3">Tidak ada data.</td></tr>`}</tbody></table></div></div></div>`;
    }
    async function renderJourney() {
        if (!$('batch_uuid').value) { $('result').innerHTML = '<div class="alert alert-info">Pilih Kode Batch untuk melihat perjalanan produksi lengkap.</div>'; return; }
        const data = await get(base + '/batch/' + encodeURIComponent($('batch_uuid').value));
        if (data.error) { $('result').innerHTML = `<div class="alert alert-danger">${esc(data.error)}</div>`; return; }
        const b=data.batch, mp=data.mp||[], counter=data.counter||[], filkar=data.filkar||[], sortasi=data.sortasi||[], bad=data.badpro||[];
        const totalBad=bad.reduce((s,x)=>s+Number(x.berat||0),0), totalCounter=counter.reduce((s,x)=>s+Number(x.counter||0),0), totalFilkar=filkar.reduce((s,x)=>s+Number(x.jumlah_kg||0),0);
        $('result').innerHTML=`<div class="card shadow mb-4"><div class="card-body"><h4 class="mb-1">${esc(b.kode_batch)}</h4><div class="text-muted">Plan ${esc(b.plan)} · Varian <b>${esc(b.varian||'-')}</b> · Produksi ${esc(b.tanggal_produksi||'-')}</div></div></div><div class="row">${card('MP Usage',num(mp.reduce((s,x)=>s+Number(x.total_output||0),0),2)+' kg','formula + rework')}${card('Counter Filler',num(totalCounter,0),counter.length+' mesin')}${card('Filkar',num(totalFilkar,2)+' kg',filkar.length+' transaksi')}${card('Bad Product',num(totalBad)+' kg',bad.length+' transaksi')}</div>`;
        $('result').innerHTML += table('Bad Product Batch',['Bad Product','Proses','Mesin','Kg','Kategori'],bad.map(x=>`<tr><td>${esc(x.nama_badpro||'-')}</td><td>${esc(x.nama_proses||'-')}</td><td>${esc(x.nama_mesin||'-')}</td><td>${num(x.berat)}</td><td>${Number(x.kategori)==1?'Rework':(Number(x.kategori)==2?'Reject':'-')}</td></tr>`).join(''));
    }
    async function renderPerformance() {
        const data=await get(base+'/performance?'+params());
        $('result').innerHTML=table('Ranking Performa Mesin',['Rank','Mesin','Area','Batch','Total Counter','Avg Speed','Performance'],data.map((x,i)=>`<tr><td>${i+1}</td><td>${esc(x.nama_mesin||'-')}</td><td>${esc(x.nama_area||'-')}</td><td>${esc(x.jumlah_batch)}</td><td>${num(x.total_counter,0)}</td><td>${num(x.avg_speed,2)}</td><td>${x.performance==null?'-':num(x.performance,2)+'%'}</td></tr>`).join(''));
    }
    function renderTrend(rows) {
        const box=document.getElementById('badTrend');
        if (!box) return;
        rows=Array.isArray(rows)?rows:[];
        if (!rows.length) { box.innerHTML='<div class=\"text-center text-muted py-5\">Tidak ada tanggal pada range yang dipilih.</div>'; return; }
        const vals=rows.map(x=>Number(x.total_kg||0));
        const max=Math.max(...vals,1);
        const W=1000,H=300,L=65,R=20,T=20,B=55;
        const pw=W-L-R, ph=H-T-B;
        const step=rows.length===1?pw:pw/(rows.length-1);
        const points=vals.map((v,i)=>[L+i*step,T+ph-(v/max)*ph]);
        const poly=points.map(p=>p.join(',')).join(' ');
        const grid=[0,.25,.5,.75,1].map(f=>{const y=T+ph-f*ph;const val=max*f;return `<line x1=\"${L}\" y1=\"${y}\" x2=\"${W-R}\" y2=\"${y}\" stroke=\"#ddd\"/><text x=\"${L-8}\" y=\"${y+4}\" text-anchor=\"end\" font-size=\"11\">${num(val,2)}</text>`;}).join('');
        const labels=rows.map((x,i)=>{ if(rows.length>15 && i%Math.ceil(rows.length/10)!==0 && i!==rows.length-1)return ''; const p=points[i]; return `<text x=\"${p[0]}\" y=\"${H-25}\" text-anchor=\"middle\" font-size=\"10\">${esc(x.tanggal)}</text>`; }).join('');
        const dots=points.map((p,i)=>`<circle cx=\"${p[0]}\" cy=\"${p[1]}\" r=\"4\" fill=\"currentColor\"><title>${esc(rows[i].tanggal)}: ${num(vals[i])} kg</title></circle>`).join('');
        box.innerHTML=`<div style=\"width:100%;overflow-x:auto\"><svg viewBox=\"0 0 ${W} ${H}\" style=\"width:100%;min-width:700px;height:300px;color:#007bff\" role=\"img\" aria-label=\"Trend harian bad product\">${grid}<polyline points=\"${poly}\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"3\"/>${dots}${labels}</svg></div>`;
    }
    async function renderBadpro() {
        const data=await get(base+'/badpro?'+params());
        lastBadData=data;
        const detail=data.detail||[];
        const total=detail.reduce((s,x)=>s+Number(x.berat||0),0), batches=new Set(detail.map(x=>x.kode_batch)).size, plans=new Set(detail.map(x=>x.tanggal+'|'+x.plan)).size;
        const machines={}; detail.forEach(x=>{(x.mesin||'Tidak diketahui').split(',').map(s=>s.trim()).filter(Boolean).forEach(m=>machines[m]=(machines[m]||0)+Number(x.berat||0)/(x.mesin.split(',').filter(Boolean).length||1));});
        const dominant=Object.entries(machines).sort((a,b)=>b[1]-a[1])[0]?.[0]||'-';
        const trend=await get(base+'/badpro_trend?'+params());
        const journeyPlan=await get(base+'/badpro_journey?'+params({group_by:'plan'}));
        const journeyBatch=await get(base+'/badpro_journey?'+params({group_by:'batch'}));
        $('result').innerHTML=`<div class="row">${card('Total Bad',num(total)+' kg','sesuai filter')}${card('Jumlah Batch',batches)}${card('Jumlah Plan',plans)}${card('Mesin Dominan',dominant)}</div><div class="card shadow mb-4"><div class="card-header"><b>Trend Harian Bad Product</b></div><div class="card-body"><div id="badTrend" style="min-height:300px"></div></div></div>`;
        renderTrend(trend);
        const mr=(data.ranking_mesin||[]).map((x,i)=>`<tr><td>${i+1}</td><td>${esc(x.nama_mesin)}</td><td>${num(x.total_kg)}</td><td>${esc(x.jumlah_transaksi)}</td><td>${esc(x.jumlah_batch)}</td></tr>`).join('');
        $('result').innerHTML+=table('Breakdown Mesin',['Rank','Mesin','Total Kontribusi Kg','Transaksi','Batch'],mr,'<span class="small text-muted">Jika 1 bad tercatat di beberapa mesin, berat dibagi rata.</span>');
        const jp=journeyPlan.map(x=>`<tr><td>${esc(x.tanggal_plan)}</td><td>${esc(x.varian||'-')}</td><td>${esc(x.jumlah_batch)}</td><td>${num(x.total_kg)}</td></tr>`).join('');
        const jb=journeyBatch.map(x=>`<tr><td>${esc(x.tanggal_produksi)}</td><td>${esc(x.varian||'-')}</td><td>${esc(x.kode_batch)}</td><td>${num(x.total_kg)}</td></tr>`).join('');
        $('result').innerHTML+=`<div class="card shadow mb-4"><div class="card-header d-flex justify-content-between align-items-center"><b>Perjalanan Bad Product</b><div class="btn-group btn-group-sm"><button class="btn btn-primary active" id="journeyPlan">Per Plan Produksi</button><button class="btn btn-outline-primary" id="journeyBatch">Per Kode Batch</button></div></div><div id="journeyTable" class="card-body p-0"></div></div>`;
        const setJourney=(mode)=>{if(mode==='plan'){$('journeyPlan').className='btn btn-primary active';$('journeyBatch').className='btn btn-outline-primary';$('journeyTable').innerHTML='<div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead><tr><th>Tanggal Plan</th><th>Varian</th><th>Jumlah Batch</th><th>Total Bad (Kg)</th></tr></thead><tbody>'+ (jp||'<tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data.</td></tr>')+'</tbody></table></div>';}else{$('journeyPlan').className='btn btn-outline-primary';$('journeyBatch').className='btn btn-primary active';$('journeyTable').innerHTML='<div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead><tr><th>Tanggal Produksi</th><th>Varian</th><th>Kode Batch</th><th>Total Bad (Kg)</th></tr></thead><tbody>'+ (jb||'<tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data.</td></tr>')+'</tbody></table></div>';}};
        $('journeyPlan').onclick=()=>setJourney('plan');$('journeyBatch').onclick=()=>setJourney('batch');setJourney('plan');
        const dr=detail.map(x=>`<tr><td>${esc(x.tanggal)}</td><td>${esc(x.varian||'-')}</td><td>${esc(x.proses)}</td><td>${esc(x.kode_batch)}</td><td>${esc(x.mesin)}</td><td>${esc(x.nama_badpro)}</td><td>${esc(x.kategori)}</td><td>${num(x.berat)}</td></tr>`).join('');
        $('result').innerHTML+=table('Detail Bad Product',['Tanggal','Varian','Proses','Kode Batch','Mesin','Bad Product','Kategori','Berat (Kg)'],dr);
        $('btnExport').classList.remove('d-none');
    }
    async function renderYield() {
        const x=await get(base+'/yield_analysis?'+params());
        $('result').innerHTML=`<div class="row">${card('Jumlah Batch',x.jumlah_batch||0)}${card('MP Usage',num(x.total_mp_kg,2)+' kg')}${card('Filkar',num(x.total_filkar_kg,2)+' kg','Yield '+num(x.filkar_yield,2)+'%')}${card('Sortasi WIP',num(x.total_sortasi_box,0)+' box')}</div>`;
    }
    async function render(){
        $('result').innerHTML='<div class="text-center py-5"><i class="fas fa-spinner fa-spin"></i> Memuat analisa...</div>';
        $('btnExport').classList.add('d-none');
        try { if($('mode').value==='journey') await renderJourney(); else if($('mode').value==='performance') await renderPerformance(); else if($('mode').value==='badpro') await renderBadpro(); else await renderYield(); }
        catch(e){console.error(e);$('result').innerHTML='<div class="alert alert-danger">Gagal memuat analisa. Periksa log aplikasi dan query database.</div>';}
    }
    function exportBadpro(){
        const q=params(selectedNames());
        window.location.href=base+'/export_badpro?'+q;
    }
    $('start').addEventListener('change',async()=>{await loadVariants();await loadPlans();await loadBadproducts();});
    $('end').addEventListener('change',async()=>{await loadVariants();await loadPlans();await loadBadproducts();});
    $('varian_uuid').addEventListener('change',async()=>{await loadPlans();await loadBadproducts();});
    $('plan_uuid').addEventListener('change',async()=>{await loadBatches();await loadBadproducts();});
    $('batch_uuid').addEventListener('change',loadBadproducts);
    $('proses_uuid').addEventListener('change',loadBadproducts);
    $('mode').addEventListener('change',()=>{ if($('mode').value!=='badpro') $('btnExport').classList.add('d-none'); });
    $('btnAnalisa').addEventListener('click',render);
    $('btnExport').addEventListener('click',exportBadpro);
    // Saat halaman pertama selesai memuat filter, langsung tampilkan analisa.
    Promise.all([loadProcesses(), loadVariants()])
        .then(loadPlans)
        .then(loadBadproducts)
        .then(() => render())
        .catch(e => {
            console.error(e);
            $('result').innerHTML = '<div class="alert alert-danger">Gagal memuat filter/analisa: ' + esc(e.message || e) + '</div>';
        });
})();
</script>
