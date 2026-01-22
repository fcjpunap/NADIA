<!DOCTYPE html><html lang="es"><head><title>Editar Usuario</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container mt-5" style="max-width:700px"><div class="card shadow"><div class="card-header bg-primary text-white">Editar Usuario</div><div class="card-body">
<form action="" method="POST">
    <div class="mb-2"><label>Nombres</label><input type="text" name="nombres" class="form-control" value="<?php echo $data['usuario']['nombres']; ?>"></div>
    <div class="mb-2"><label>Apellidos</label><input type="text" name="apellidos" class="form-control" value="<?php echo $data['usuario']['apellidos']; ?>"></div>
    <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $data['usuario']['email']; ?>"></div>
    
    <div class="row mb-3 mt-4 p-3 bg-light border rounded">
        <div class="col-md-4"><label class="fw-bold">Rol</label><select name="rol" class="form-select"><?php foreach($data['roles'] as $r): ?><option value="<?php echo $r['id']; ?>" <?php echo ($r['id']==$data['usuario']['id_rol_principal'])?'selected':''; ?>><?php echo $r['nombre_rol']; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="fw-bold">Facultad</label><select id="selFacultad" name="facultad_id" class="form-select" onchange="cargarProgramas()"><option value="">Seleccione...</option><?php foreach($data['facultades'] as $f): ?><option value="<?php echo $f['id']; ?>" <?php echo ($f['id']==$data['usuario']['id_facultad'])?'selected':''; ?>><?php echo $f['nombre']; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="fw-bold">Programa</label><select id="selPrograma" name="programa_id" class="form-select" <?php echo empty($data['programas']) ? 'disabled' : ''; ?>><option value="">- Seleccione -</option><?php if(isset($data['programas'])) foreach($data['programas'] as $p): ?><option value="<?php echo $p['id']; ?>" <?php echo ($p['id']==$data['usuario']['id_programa'])?'selected':''; ?>><?php echo $p['nombre']; ?> (<?php echo $p['nivel']; ?>)</option><?php endforeach; ?></select></div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-6"><label>Área de Investigación</label><select id="selArea" name="area" class="form-select" onchange="cargarLineas()"><option value="">- Ninguna -</option><?php foreach($data['areas'] as $a): ?><option value="<?php echo $a['id']; ?>" <?php echo ($a['id']==$data['usuario']['id_area_investigacion'])?'selected':''; ?>><?php echo $a['nombre']; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label>Línea de Investigación</label><select id="selLinea" name="linea" class="form-select" <?php echo empty($data['lineas']) ? 'disabled' : ''; ?>><option value="">- Seleccione -</option><?php if(isset($data['lineas'])) foreach($data['lineas'] as $l): ?><option value="<?php echo $l['id']; ?>" <?php echo ($l['id']==$data['usuario']['id_linea_investigacion'])?'selected':''; ?>><?php echo $l['nombre']; ?></option><?php endforeach; ?></select></div>
    </div>
    
    <div class="mb-3"><label>Estado</label><select name="activo" class="form-select"><option value="1" <?php echo ($data['usuario']['activo']==1)?'selected':''; ?>>Activo</option><option value="0" <?php echo ($data['usuario']['activo']==0)?'selected':''; ?>>Bloqueado</option></select></div>
    <div class="mb-3"><label class="text-danger">Nueva Contraseña (Opcional)</label><input type="password" name="password" class="form-control"></div>
    <button class="btn btn-success w-100 py-2">GUARDAR CAMBIOS</button>
</form>
</div></div></div>
<script>
async function cargarProgramas() {
    const idFac = document.getElementById('selFacultad').value;
    const selProg = document.getElementById('selPrograma');
    selProg.innerHTML = '<option>Cargando...</option>';
    if(!idFac) { selProg.innerHTML='<option>-</option>'; selProg.disabled=true; return; }
    const fd = new FormData(); fd.append('id_facultad', idFac);
    try {
        const res = await fetch('<?php echo URL_BASE; ?>api/get_programas', { method:'POST', body:fd });
        const data = await res.json();
        selProg.innerHTML = '<option value="">Seleccione Programa...</option>';
        data.forEach(p => { selProg.innerHTML += `<option value="${p.id}">${p.nombre} (${p.nivel})</option>`; });
        selProg.disabled = false;
    } catch(e) { console.error(e); }
}
async function cargarLineas() {
    const idArea = document.getElementById('selArea').value;
    const selLin = document.getElementById('selLinea');
    selLin.innerHTML = '<option>Cargando...</option>';
    if(!idArea) { selLin.innerHTML='<option>-</option>'; selLin.disabled=true; return; }
    const fd = new FormData(); fd.append('id_area', idArea);
    try {
        const res = await fetch('<?php echo URL_BASE; ?>api/get_lineas_por_area', { method:'POST', body:fd });
        const data = await res.json();
        selLin.innerHTML = '<option value="">Seleccione Línea...</option>';
        data.forEach(l => { selLin.innerHTML += `<option value="${l.id}">${l.nombre}</option>`; });
        selLin.disabled = false;
    } catch(e) { console.error(e); }
}
</script>
</body></html>
