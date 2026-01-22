<!DOCTYPE html>
<html lang="es">
<head><title>Nuevo Usuario</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">Registrar Usuario</div>
        <div class="card-body">
            <form action="<?php echo URL_BASE; ?>admin/usuarios_crear" method="POST">
                <!-- Datos Personales -->
                <div class="row mb-3">
                    <div class="col"><label>Nombres</label><input type="text" name="nombres" class="form-control" required></div>
                    <div class="col"><label>Apellidos</label><input type="text" name="apellidos" class="form-control" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col"><label>DNI</label><input type="text" name="dni" class="form-control"></div>
                    <div class="col"><label>Código</label><input type="text" name="codigo" class="form-control"></div>
                </div>
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                
                <!-- Asignación Académica -->
                <div class="mb-3 p-3 bg-light border rounded">
                    <label class="fw-bold">Asignación Académica</label>
                    <div class="row mt-2">
                        <div class="col">
                            <small>Facultad</small>
                            <select id="selFacultad" name="facultad_input" class="form-select" onchange="cargarProgramas()">
                                <option value="">Seleccione...</option>
                                <?php foreach($data['facultades'] as $f): ?>
                                    <option value="<?php echo $f['id']; ?>"><?php echo $f['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <small>Programa</small>
                            <select id="selPrograma" name="programa_input" class="form-select" disabled>
                                <option value="">- Seleccione Facultad -</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Área y Línea -->
                    <div class="row mt-2">
                        <div class="col">
                            <small>Área de Investigación</small>
                            <select id="selArea" name="area" class="form-select" onchange="cargarLineas()">
                                <option value="">- Ninguna -</option>
                                <?php if(isset($data['areas'])) foreach($data['areas'] as $a): ?>
                                    <option value="<?php echo $a['id']; ?>"><?php echo $a['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <small>Línea de Investigación</small>
                            <select id="selLinea" name="linea" class="form-select" disabled>
                                <option value="">- Seleccione Área -</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>Rol</label>
                        <select name="rol" class="form-select">
                            <option value="1">Tesista</option>
                            <option value="2">Docente</option>
                            <option value="4">Coordinador</option>
                        </select>
                    </div>
                    <div class="col"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
                </div>
                <button type="submit" class="btn btn-success w-100">Guardar</button>
            </form>
        </div>
    </div>
</div>
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
