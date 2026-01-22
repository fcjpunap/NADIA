<!DOCTYPE html><html lang="es"><head><title>Obs - <?php echo $data['fase']; ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<style>body{height:100vh;display:flex;flex-direction:column;overflow:hidden}#main{display:flex;flex:1;overflow:hidden}#pdf{flex:7;background:#525659;overflow-y:auto;text-align:center}#panel{flex:3;background:#f8f9fa;border-left:1px solid #ccc;display:flex;flex-direction:column}</style></head><body>
<div class="bg-dark text-white p-2 d-flex justify-content-between align-items-center">
    <div><a href="<?php echo URL_BASE; ?>tesista/dashboard" class="btn btn-outline-light btn-sm me-2">Volver</a> <span class="badge bg-warning text-dark"><?php echo $data['fase']; ?></span></div>
</div>
<div id="main">
    <div id="pdf"><canvas id="cvs"></canvas></div>
    <div id="panel">
        <div class="p-2 border-bottom bg-white">Página <input type="number" id="pn" value="1" style="width:50px" onchange="jump()"></div>
        <div id="list" style="flex:1;overflow:auto;padding:10px">Cargando...</div>
    </div>
</div>
<script>
const url='<?php echo URL_BASE.$data['documento']['ruta_archivo']; ?>', pid=<?php echo $data['proyecto']['id']; ?>, fase='<?php echo $data['fase']; ?>';
let pdf=null,pn=1,scale=1.5,cvs=document.getElementById('cvs'),ctx=cvs.getContext('2d');
pdfjsLib.GlobalWorkerOptions.workerSrc='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
function render(n){pdf.getPage(n).then(p=>{var v=p.getViewport({scale:scale});cvs.height=v.height;cvs.width=v.width;p.render({canvasContext:ctx,viewport:v});}); document.getElementById('pn').value=n; loadC(n);}
function jump(){var n=parseInt(document.getElementById('pn').value);if(pdf && n>=1 && n<=pdf.numPages){pn=n;render(n);}}
async function loadC(n){
    const l=document.getElementById('list');l.innerHTML='...';
    try{
        const r=await fetch('<?php echo URL_BASE; ?>tesista/api_get_comentarios',{method:'POST',body:JSON.stringify({id_proyecto:pid,pagina:n,fase:fase})});
        const d=await r.json();l.innerHTML='';
        if(d.length===0) l.innerHTML='<small class="text-muted">Sin observaciones en esta página ('+fase+')</small>';
        d.forEach(c=>{
            let rol = c.rol_real ? c.rol_real : c.rol_autor;
            let color = (rol === 'Asesor') ? 'success' : 'danger';
            let badge = c.es_antiguo ? '<span class="badge bg-secondary ms-2" style="font-size:0.7em">Versión Anterior</span>' : '<span class="badge bg-info text-dark ms-2" style="font-size:0.7em">Versión Actual</span>';
            l.innerHTML+=`<div class="alert alert-secondary small border-${color} border-start border-4"><b>${c.nombres}</b> (${rol})${badge}:<br>${c.observacion_texto}</div>`
        });
    }catch(e){}
}
<?php if($data['documento']): ?>pdfjsLib.getDocument(url).promise.then(p=>{pdf=p;render(1);});<?php endif; ?>
</script></body></html>
