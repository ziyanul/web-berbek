<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Analisa Produksi</h1>
            <div class="text-muted small">Range tanggal adalah filter utama. Varian, Batch, Proses, Bad Product dan Mesin bersifat opsional.</div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header"><b>Filter Global</b></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-2"><label>Mulai</label><input id="start" type="date" class="form-control" value="<?= date('Y-m-01') ?>"></div>
                <div class="form-group col-md-2"><label>Sampai</label><input id="end" type="date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                <div class="form-group col-md-2"><label>Varian</label><select id="varian_uuid" class="form-control"><option value="">Semua Varian</option></select></div>
                <div class="form-group col-md-2"><label>Kode Batch</label><select id="batch_uuid" class="form-control"><option value="">Semua Batch</option></select></div>
                <div class="form-group col-md-2"><label>Mesin</label><select id="mesin_uuid" class="form-control"><option value="">Semua Mesin</option></select></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label>Proses</label><select id="proses_uuid" class="form-control"><option value="">Semua Proses</option></select></div>
                <div class="form-group col-md-3"><label>Bad Product</label><select id="badpro_uuid" class="form-control"><option value="">Semua Bad Product</option></select></div>
                <div class="form-group col-md-3"><label>Mode Analisa</label><select id="mode" class="form-control"><option value="journey">Perjalanan Produksi</option><option value="yield">Yield</option><option value="performance">Performa Mesin</option><option value="badpro">Bad Product</option></select></div>
                <div class="form-group col-md-3 d-flex align-items-end"><button id="btnAnalisa" class="btn btn-primary btn-block"><i class="fas fa-chart-line mr-1"></i> Tampilkan Analisa</button></div>
            </div>
            <div id="exportBox" class="d-none mt-2">
                <button id="btnExport" class="btn btn-success"><i class="fas fa-file-excel mr-1"></i> Download Excel Semua Mode</button>
                <div class="small text-muted mt-1">Excel berisi seluruh 4 mode analisa sesuai range dan filter yang dipilih.</div>
            </div>
        </div>
    </div>
    <div id="result"></div>
</div>
<script>
(() => {
    const base='<?= base_url('analisa') ?>';
    const g=id=>document.getElementById(id);
    const esc=v=>String(v??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
    const n=v=>Number(v||0).toLocaleString('id-ID',{maximumFractionDigits:3});
    async function get(u){
        const r=await fetch(u,{headers:{'X-Requested-With':'XMLHttpRequest'}});
        let x; try{x=await r.json()}catch(e){throw Error('Response bukan JSON')}
        if(!r.ok)throw Error(x.error||('HTTP '+r.status));
        return x;
    }
    function qs(extra={}){
        return new URLSearchParams({
            start:g('start').value,end:g('end').value,varian_uuid:g('varian_uuid').value,
            batch_uuid:g('batch_uuid').value,proses_uuid:g('proses_uuid').value,
            badpro_uuid:g('badpro_uuid').value,mesin_uuid:g('mesin_uuid').value,...extra
        });
    }
    function table(title,heads,rows){
        return `<div class="card shadow mb-4"><div class="card-header"><b>${esc(title)}</b></div><div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead><tr>${heads.map(x=>`<th>${esc(x)}</th>`).join('')}</tr></thead><tbody>${rows||`<tr><td colspan="${heads.length}" class="text-center text-muted py-3">Tidak ada data</td></tr>`}</tbody></table></div></div>`;
    }
    function cards(a){
        return `<div class="row">${a.map(x=>`<div class="col-md-3 mb-3"><div class="card shadow-sm"><div class="card-body"><div class="small text-uppercase text-primary font-weight-bold">${esc(x[0])}</div><div class="h5 mb-0">${esc(x[1])}</div></div></div></div>`).join('')}</div>`;
    }
    function setOptions(el,rows,first,selected,formatter){
        el.innerHTML=`<option value="">${esc(first)}</option>`+rows.map(formatter).join('');
        if(selected && [...el.options].some(o=>o.value===selected)) el.value=selected;
    }
    async function loadVariants(preserve=true){
        const old=g('varian_uuid').value;
        const v=await get(base+'/variants?'+qs());
        setOptions(g('varian_uuid'),v,'Semua Varian',preserve?old:'',x=>`<option value="${esc(x.uuid)}">${esc(x.varian)}</option>`);
    }
    async function loadBatches(preserve=true){
        const old=g('batch_uuid').value;
        const b=await get(base+'/batches?'+qs());
        setOptions(g('batch_uuid'),b,'Semua Batch',preserve?old:'',x=>`<option value="${esc(x.uuid)}">${esc(x.kode_batch)} - ${esc(x.varian||'-')}</option>`);
    }
    async function loadMachines(preserve=true){
        const old=g('mesin_uuid').value;
        const m=await get(base+'/machines?'+qs());
        setOptions(g('mesin_uuid'),m,'Semua Mesin',preserve?old:'',x=>`<option value="${esc(x.uuid)}">${esc(x.nama_mesin)}</option>`);
    }
    async function loadBad(preserve=true){
        const old=g('badpro_uuid').value;
        const x=await get(base+'/badproducts?'+qs());
        setOptions(g('badpro_uuid'),x,'Semua Bad Product',preserve?old:'',r=>`<option value="${esc(r.uuid)}">${esc(r.nama_badpro)} - ${n(r.total_kg)} kg</option>`);
    }
    async function loadInitial(){
        const pr=await get(base+'/processes');
        setOptions(g('proses_uuid'),pr,'Semua Proses','',x=>`<option value="${esc(x.uuid)}">${esc(x.kode||x.nama_proses)}</option>`);
        await loadVariants(false);
        await loadBatches(false);
        await loadMachines(false);
        await loadBad(false);
    }
    // Jika filter berubah, pilihan lain tidak boleh tiba-tiba kembali ke Semua.
    async function refreshDependents(type){
        if(type==='date'){
            await loadVariants(false); await loadBatches(false); await loadMachines(false); await loadBad(false);
            return;
        }
        if(type==='variant'){
            g('batch_uuid').value=''; g('mesin_uuid').value=''; g('badpro_uuid').value='';
            await loadBatches(false); await loadMachines(false); await loadBad(false); return;
        }
        if(type==='batch'){
            g('mesin_uuid').value=''; g('badpro_uuid').value=''; await loadMachines(false); await loadBad(false); return;
        }
        if(type==='process'){
            g('badpro_uuid').value=''; await loadBad(false); return;
        }
        if(type==='machine'){
            g('badpro_uuid').value=''; await loadBad(false); return;
        }
    }
    function line(rows){
        if(!rows.length)return '<div class="text-muted text-center py-5">Tidak ada data trend.</div>';
        let max=Math.max(...rows.map(x=>+x.total_kg),1),W=1100,H=320,L=60,B=50,T=20,R=20,dx=rows.length>1?(W-L-R)/(rows.length-1):0;
        let pts=rows.map((x,i)=>[L+i*dx,T+(H-T-B)*(1-(+x.total_kg/max))]);
        return `<svg viewBox="0 0 ${W} ${H}" style="width:100%;min-width:750px;height:320px;color:#007bff"><line x1="${L}" y1="${H-B}" x2="${W-R}" y2="${H-B}" stroke="#999"/><polyline points="${pts.map(p=>p.join(',')).join(' ')}" fill="none" stroke="currentColor" stroke-width="3"/>${pts.map((p,i)=>`<circle cx="${p[0]}" cy="${p[1]}" r="4" fill="currentColor"><title>${esc(rows[i].tanggal_display||rows[i].tanggal)}: ${n(rows[i].total_kg)} kg</title></circle>`).join('')}${rows.map((x,i)=>i%Math.ceil(rows.length/12)===0?`<text x="${pts[i][0]}" y="${H-25}" font-size="10" text-anchor="middle">${esc(x.tanggal_display||x.tanggal)}</text>`:'').join('')}</svg>`;
    }
    async function journey(){
        const x=await get(base+'/journey?'+qs()),r=x.batches||[],p=x.plans||[];
        return cards([['Planning Produksi',p.length],['Batch',r.length],['MP Total',n(r.reduce((s,x)=>s+ +x.mp_total_kg,0))+' KG'],['Filkar',n(r.reduce((s,x)=>s+ +x.filkar_kg,0))+' KG'],['Bad Rework',n(r.reduce((s,x)=>s+ +x.bad_rework_kg,0))+' KG'],['Hasil Kupas',n(r.reduce((s,x)=>s+ +x.hasil_kupas_kg,0))+' KG'],['Belum Kupas',n(r.reduce((s,x)=>s+ +x.belum_kupas_kg,0))+' KG'],['Terpakai Rework',n(r.reduce((s,x)=>s+ +x.terpakai_rework_kg,0))+' KG']])
        +table('Perjalanan Semua Planning Produksi',['Tanggal Planning','Varian','Jumlah Batch','MP Total KG','Counter','Filkar KG','Sortasi BOX','Bad KG','Tampung','Kasar','Cuci','Release','Sisa WIP','Bad Rework','Hasil Kupas','Belum Kupas','Terpakai','Sisa Kupas'],p.map(x=>`<tr><td>${esc(x.tanggal_plan_display||x.tanggal_plan)}</td><td>${esc(x.varian||'-')}</td><td>${n(x.jumlah_batch)}</td><td>${n(x.mp_total_kg)}</td><td>${n(x.counter)}</td><td>${n(x.filkar_kg)}</td><td>${n(x.sortasi_input_box)}</td><td>${n(x.bad_kg)}</td><td>${n(x.tampung_box)}</td><td>${n(x.kasar_box)}</td><td>${n(x.cuci_box)}</td><td>${n(x.release_box)}</td><td>${n(x.sisa_wip_box)}</td><td>${n(x.bad_rework_kg)}</td><td>${n(x.hasil_kupas_kg)}</td><td>${n(x.belum_kupas_kg)}</td><td>${n(x.terpakai_rework_kg)}</td><td>${n(x.sisa_kupas_kg)}</td></tr>`).join(''))
        +table('Perjalanan Semua Batch',['Tanggal','Varian','Batch','MP Total KG','Counter','Filkar KG','Sortasi BOX','Bad KG','Tampung','Kasar','Cuci','Release','Sisa WIP','Bad Rework','Hasil Kupas','Belum Kupas','Terpakai','Sisa Kupas','Batch Hasil Cuci'],r.map(x=>`<tr><td>${esc(x.tanggal_produksi_display||x.tanggal_produksi)}</td><td>${esc(x.varian||'-')}</td><td><b>${esc(x.kode_batch)}</b></td><td>${n(x.mp_total_kg)}</td><td>${n(x.counter)}</td><td>${n(x.filkar_kg)}</td><td>${n(x.sortasi_input_box)}</td><td>${n(x.bad_kg)}</td><td>${n(x.tampung_box)}</td><td>${n(x.kasar_box)}</td><td>${n(x.cuci_box)}</td><td>${n(x.release_box)}</td><td>${n(x.sisa_wip_box)}</td><td>${n(x.bad_rework_kg)}</td><td>${n(x.hasil_kupas_kg)}</td><td>${n(x.belum_kupas_kg)}</td><td>${n(x.terpakai_rework_kg)}</td><td>${n(x.sisa_kupas_kg)}</td><td>${esc(x.batch_hasil_cuci||'-')}</td></tr>`).join(''));
    }
    async function yieldMode(){
        const x=await get(base+'/yield_analysis?'+qs());
        let html=cards([
            ['Planning Produksi',x.jumlah_planning],['Batch',x.jumlah_batch],['MP Formula',n(x.mp_formula_kg)+' KG'],['MP Rework',n(x.mp_rework_kg)+' KG'],
            ['MP Total',n(x.mp_total_kg)+' KG'],['Filkar',n(x.filkar_kg)+' KG'],['Yield Filkar',n(x.yield_filkar_pct)+'%'],
            ['Release',n(x.release_box)+' BOX / '+n(x.release_kg)+' KG'],['Bad Product',n(x.bad_kg)+' KG'],['Yield Release',n(x.yield_release_pct)+'%'],
            ['PVDC Dipakai',n(x.pvdc)+' ROLL'],['PVDC Onproduk',n(x.onproduk_pvdc)+' ROLL'],['PVDC Reject',n(x.reject_pvdc)+' ROLL / '+n(x.reject_pvdc_persen)+'%'],
            ['Wire Dipakai',n(x.wire)+' ROLL'],['Wire Onproduk',n(x.onproduk_wire)+' ROLL'],['Wire Reject',n(x.reject_wire)+' ROLL / '+n(x.reject_wire_persen)+'%'],
            ['Bad Rework',n(x.bad_rework_kg)+' KG'],['Hasil Kupas',n(x.hasil_kupas_kg)+' KG'],['Belum Kupas',n(x.belum_kupas_kg)+' KG'],['Terpakai Rework',n(x.terpakai_rework_kg)+' KG'],['Sisa Hasil Kupas',n(x.sisa_kupas_kg)+' KG'],
            ['Tampung',n(x.tampung_box)+' BOX'],['Kasar',n(x.kasar_box)+' BOX'],['Cuci',n(x.cuci_box)+' BOX'],['Sisa WIP',n(x.sisa_wip_box)+' BOX']
        ]);
        html += table('Detail Yield per Planning Produksi',['Tanggal Planning','Varian','Jumlah Batch','MP Formula KG','MP Rework KG','Total MP KG','Filkar KG','Yield Filkar','Release BOX','Release KG','Bad Product KG','Yield Release','PVDC Dipakai (ROLL)','PVDC Onproduk','PVDC Reject','PVDC Reject %','Wire Dipakai (ROLL)','Wire Onproduk','Wire Reject','Wire Reject %'],(x.plans||[]).map(r=>`<tr><td>${esc(r.tanggal_plan_display||r.tanggal_plan)}</td><td>${esc(r.varian||'-')}</td><td>${n(r.jumlah_batch)}</td><td>${n(r.mp_formula_kg)}</td><td>${n(r.mp_rework_kg)}</td><td>${n(r.mp_total_kg)}</td><td>${n(r.filkar_kg)}</td><td>${n(r.yield_filkar_pct)}%</td><td>${n(r.release_box)}</td><td>${n(r.release_kg)}</td><td>${n(r.bad_kg)}</td><td>${n(r.yield_release_pct)}%</td><td>${n(r.pvdc)}</td><td>${n(r.onproduk_pvdc)}</td><td>${n(r.reject_pvdc)}</td><td>${n(r.reject_pvdc_persen)}%</td><td>${n(r.wire)}</td><td>${n(r.onproduk_wire)}</td><td>${n(r.reject_wire)}</td><td>${n(r.reject_wire_persen)}%</td></tr>`).join(''));
        return html;
    }
    async function performance(){
        const x=await get(base+'/performance?'+qs());
        return table('Performa Mesin',['Mesin','Batch','Counter Aktual','Target Counter','Performa','Bad KG','Bad/Counter'],(x.machines||[]).map(x=>`<tr><td>${esc(x.nama_mesin||'-')}</td><td>${n(x.jumlah_batch)}</td><td>${n(x.total_counter)}</td><td>${n(x.total_target)}</td><td>${n(x.performance_pct)}%</td><td>${n(x.bad_kg)}</td><td>${n(x.bad_per_counter)}</td></tr>`).join(''))
        +table('Bad Product per Mesin',['Mesin','Proses','Bad Product','KG'],(x.bad_by_machine||[]).map(x=>`<tr><td>${esc(x.nama_mesin||'-')}</td><td>${esc(x.proses||'-')}</td><td>${esc(x.nama_badpro||'-')}</td><td>${n(x.total_kg)}</td></tr>`).join(''));
    }
    async function badpro(){
        const x=await get(base+'/badpro?'+qs()),t=await get(base+'/badpro_trend?'+qs());
        const detail=x.detail||[];
        const total=detail.reduce((s,r)=>s+ +r.berat,0);
        const planCount=new Set(detail.map(r=>r.tanggal+'|'+r.varian)).size;
        const machine=(x.ranking_mesin||[])[0]?.nama_mesin||'-';
        let html=cards([['Total Bad',n(total)+' KG'],['Batch',new Set(detail.map(r=>r.kode_batch)).size],['Jumlah Planning Produksi',planCount],['Mesin Dominan',machine],['Bad Rework',n(x.rework?.bad_rework_kg)+' KG'],['Hasil Kupas',n(x.rework?.hasil_kupas_kg)+' KG'],['Belum Kupas',n(x.rework?.belum_kupas_kg)+' KG'],['Terpakai Rework',n(x.rework?.terpakai_kg)+' KG'],['Sisa Hasil Kupas',n(x.rework?.sisa_kupas_kg)+' KG']])
            +`<div class="card shadow mb-4"><div class="card-header"><b>Trend Harian Bad Product</b><div class="small text-muted">Semua tanggal range, termasuk 0.</div></div><div class="card-body" style="overflow:auto">${line(t)}</div></div>`;
        html+=table('Ranking Mesin',['Mesin','Total Bad Product (KG)','Jumlah Transaksi Bad Product','Jumlah Batch'],(x.ranking_mesin||[]).map((r,i)=>`<tr><td>${i+1}. ${esc(r.nama_mesin||'-')}</td><td>${n(r.total_kg)}</td><td>${n(r.jumlah_transaksi)}</td><td>${n(r.jumlah_batch)}</td></tr>`).join(''));
        html+=table('Detail Bad Product per Planning Produksi',['Tanggal Planning','Varian','Jumlah Batch','Total Bad Product (KG)'],(x.journey_plan||[]).map(r=>`<tr><td>${esc(r.tanggal_plan_display||r.tanggal_plan)}</td><td>${esc(r.varian||'-')}</td><td>${n(r.jumlah_batch)}</td><td>${n(r.total_kg)}</td></tr>`).join(''));
        html+=table('Detail Bad Product per Batch',['Tanggal','Varian','Batch','Total Bad Product (KG)'],(x.journey_batch||[]).map(r=>`<tr><td>${esc(r.tanggal_produksi_display||r.tanggal_produksi)}</td><td>${esc(r.varian||'-')}</td><td>${esc(r.kode_batch)}</td><td>${n(r.total_kg)}</td></tr>`).join(''));
        html+=table('Detail Bad Product',['Tanggal','Varian','Proses','Batch','Mesin','Bad Product','Kategori','KG'],detail.map(r=>`<tr><td>${esc(r.tanggal_display||r.tanggal)}</td><td>${esc(r.varian||'-')}</td><td>${esc(r.proses||'-')}</td><td>${esc(r.kode_batch)}</td><td>${esc(r.mesin||'-')}</td><td>${esc(r.nama_badpro||'-')}</td><td>${esc(r.kategori||'-')}</td><td>${n(r.berat)}</td></tr>`).join(''));
        return html;
    }
    async function render(){
        g('result').innerHTML='<div class="text-center py-5"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>';
        try{let m=g('mode').value,x=m==='journey'?await journey():m==='yield'?await yieldMode():m==='performance'?await performance():await badpro();g('result').innerHTML=x;g('exportBox').classList.remove('d-none')}
        catch(e){console.error(e);g('result').innerHTML=`<div class="alert alert-danger">${esc(e.message)}</div>`}
    }
    g('btnAnalisa').onclick=render;
    g('btnExport').onclick=()=>location.href=base+'/export?'+qs({mode:'all'});
    g('start').onchange=async()=>{await refreshDependents('date');};
    g('end').onchange=async()=>{await refreshDependents('date');};
    g('varian_uuid').onchange=async()=>{await refreshDependents('variant');};
    g('batch_uuid').onchange=async()=>{await refreshDependents('batch');};
    g('proses_uuid').onchange=async()=>{await refreshDependents('process');};
    g('mesin_uuid').onchange=async()=>{await refreshDependents('machine');};
    (async()=>{try{await loadInitial();await render()}catch(e){console.error(e);g('result').innerHTML=`<div class="alert alert-danger">${esc(e.message)}</div>`}})();
})();
</script>
