<!DOCTYPE html>
<html lang="es">
<head><title>Nuevo Proyecto</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5" style="max-width:800px">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">Registro de Proyecto</div>
        <div class="card-body">
            <form action="<?php echo URL_BASE; ?>tesista/guardar_proyecto" method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label class="fw-bold">Título</label><textarea name="titulo" class="form-control" required></textarea></div>
                <div class="mb-3"><label class="fw-bold">Resumen</label><textarea name="resumen" class="form-control" rows="4" required></textarea></div>
                
                <div class="row mb-3">
                    <div class="col">
                         <?php if(isset($data['user_linea_id'])): ?>
                            <label class="fw-bold">Línea de Investigación (Asignada)</label>
                            <input type="text" class="form-control mb-2" value="<?php echo htmlspecialchars($data['user_linea_nombre']); ?>" readonly disabled>
                            
                            <label class="fw-bold">Seleccione Sublínea</label>
                            <select id="selSublinea" name="sublinea" class="form-select" required onchange="cargarAsesores()">
                                <option value="">Seleccione...</option>
                                <?php foreach($data['sublineas_disponibles'] as $sl): ?>
                                    <option value="<?php echo $sl['id']; ?>"><?php echo htmlspecialchars($sl['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                         <?php else: ?>
                            <div class="alert alert-warning">Su usuario no tiene una Línea de Investigación asignada. Contacte al administrador.</div>
                         <?php endif; ?>
                    </div>
                    <div class="col">
                        <label class="fw-bold">Asesor Sugerido (Experto)</label>
                        <select id="selAsesor" name="asesor" class="form-select" required>
                            <option value="">- Seleccione Sublínea Primero -</option>
                        </select>
                    </div>
                </div>
                <!-- FIX V92: SELECCION DE SEGUNDO TESISTA -->
                <div class="mb-3 p-3 bg-light border rounded">
                     <label class="fw-bold">Agregar Segundo Tesista (Opcional)</label>
                     <p class="small text-muted mb-1">Busque por email, nombre o código. Ambos deben pertenecer a la misma línea.</p>
                     <div class="input-group mb-2">
                         <input type="text" id="busqTesista" class="form-control" placeholder="Escriba para buscar...">
                         <button type="button" class="btn btn-secondary" onclick="buscarTesista()">Buscar</button>
                     </div>
                     <div id="resultadoBusqueda" class="list-group mb-2"></div>
                     <div id="tesistaSeleccionado" class="alert alert-info" style="display:none">
                         <strong>Seleccionado:</strong> <span id="nombreTesista2"></span>
                         <button type="button" class="btn-close float-end" onclick="quitarTesista()"></button>
                         <input type="hidden" name="id_tesista_2" id="idTesista2">
                     </div>
                </div>
                
                <h6 class="text-muted mt-4">Archivos</h6>
                <div class="row mb-3">
                    <div class="col"><label>PDF</label><input type="file" name="archivo_pdf" class="form-control" accept=".pdf" required></div>
                    <div class="col"><label>Word</label><input type="file" name="archivo_word" class="form-control" accept=".doc,.docx" required></div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="<?php echo URL_BASE; ?>tesista/dashboard" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
async function cargarAsesores() {
    const idSub = document.getElementById('selSublinea').value;
    const selA = document.getElementById('selAsesor');
    if(!idSub) { selA.innerHTML='<option>-</option>'; return; }
    selA.innerHTML = '<option>Cargando expertos...</option>';
    const fd = new FormData(); fd.append('id_sublinea', idSub);
    try {
        const res = await fetch('<?php echo URL_BASE; ?>api/get_asesores_por_linea', {method:'POST', body:fd});
        const data = await res.json();
        if(data.length > 0) {
            selA.innerHTML = '<option value="">Seleccione Asesor...</option>';
            data.forEach(d => { selA.innerHTML += `<option value="${d.id}">${d.apellidos} ${d.nombres}</option>`; });
        } else { selA.innerHTML = '<option value="">No hay expertos registrados</option>'; }
    } catch(e) { console.error(e); selA.innerHTML = '<option>Error al cargar</option>'; }
}
async function buscarTesista() {
    const t = document.getElementById('busqTesista').value;
    if(t.length < 3) return;
    const resDiv = document.getElementById('resultadoBusqueda');
    const uId = <?php echo $_SESSION['user_id']; ?>;
    resDiv.innerHTML = 'Buscando...';
    
    const fd = new FormData(); fd.append('termino', t); fd.append('id_usuario_actual', uId);
    try {
        const res = await fetch('<?php echo URL_BASE; ?>api/buscar_tesista_colegiado', {method:'POST', body:fd});
        const data = await res.json();
        resDiv.innerHTML = '';
        if(data.length === 0) { resDiv.innerHTML = '<div class="list-group-item">No se encontraron resultados</div>'; return; }
        data.forEach(u => {
            resDiv.innerHTML += `<a href="#" class="list-group-item list-group-item-action" onclick="seleccionarTesista(${u.id}, '${u.nombres} ${u.apellidos}')">${u.apellidos}, ${u.nombres} (${u.email})</a>`;
        });
    } catch(e) { console.error(e); }
}
function seleccionarTesista(id, nombre) {
    document.getElementById('idTesista2').value = id;
    document.getElementById('nombreTesista2').innerText = nombre;
    document.getElementById('tesistaSeleccionado').style.display = 'block';
    document.getElementById('resultadoBusqueda').innerHTML = '';
    document.getElementById('busqTesista').value = '';
}
function quitarTesista() {
    document.getElementById('idTesista2').value = '';
    document.getElementById('tesistaSeleccionado').style.display = 'none';
}
</script>
</body></html>
