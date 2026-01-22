<!DOCTYPE html>
<html lang="es">
<head><title>Gestión Académica - NADIA</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"></head>
<body class="bg-light">
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4"><h2><i class="bi bi-bank"></i> Estructura Académica</h2><a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a></div>
    <div class="row">
        <div class="col-md-4"><div class="card shadow"><div class="card-header bg-primary text-white fw-bold">Facultades</div><div class="card-body">
            <button class="btn btn-sm btn-outline-primary w-100 mb-3" onclick="modalFacultad()">+ Nueva Facultad</button>
            <ul class="list-group">
                <?php foreach($data['facultades'] as $f): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div><strong><?php echo $f['siglas']; ?></strong><br><small class="text-muted"><?php echo $f['nombre']; ?></small></div>
                    <button class="btn btn-xs btn-light border" onclick='modalFacultad(<?php echo json_encode($f); ?>)'><i class="bi bi-pencil"></i></button>
                </li>
                <?php endforeach; ?>
            </ul>
        </div></div></div>
        <div class="col-md-8"><div class="card shadow"><div class="card-header bg-success text-white fw-bold">Programas</div><div class="card-body">
            <button class="btn btn-sm btn-outline-success w-100 mb-3" onclick="modalPrograma()">+ Nuevo Programa</button>
            <table class="table table-sm align-middle"><thead><tr><th>Facultad</th><th>Programa</th><th>Nivel</th><th>Acción</th></tr></thead><tbody>
                <?php foreach($data['programas'] as $p): ?>
                <tr>
                    <td class="text-muted small"><?php echo $p['facultad']; ?></td><td><?php echo $p['nombre']; ?></td><td><?php echo $p['nivel']; ?></td>
                    <td><button class="btn btn-sm btn-light border" onclick='modalPrograma(<?php echo json_encode($p); ?>)'><i class="bi bi-pencil"></i></button></td>
                </tr>
                <?php endforeach; ?>
            </tbody></table>
        </div></div></div>
    </div>
</div>

<div class="modal fade" id="mdlFac"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 id="titFac">Facultad</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><form action="<?php echo URL_BASE; ?>academico/guardar_facultad" method="POST"><div class="modal-body"><input type="hidden" name="id" id="fid"><input type="text" name="nombre" id="fnom" class="form-control mb-2" placeholder="Nombre" required><input type="text" name="siglas" id="fsig" class="form-control" placeholder="Siglas" required></div><div class="modal-footer"><button class="btn btn-primary">Guardar</button></div></form></div></div></div>
<div class="modal fade" id="mdlProg"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 id="titProg">Programa</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><form action="<?php echo URL_BASE; ?>academico/guardar_programa" method="POST"><div class="modal-body"><input type="hidden" name="id" id="pid"><div class="mb-2"><label>Facultad</label><select name="id_facultad" id="pfi" class="form-select"><?php foreach($data['facultades'] as $f) echo "<option value='{$f['id']}'>{$f['nombre']}</option>"; ?></select></div><input type="text" name="nombre" id="pnom" class="form-control mb-2" placeholder="Nombre Programa" required><select name="nivel" id="pniv" class="form-select"><option>Pregrado</option><option>Posgrado</option><option>Segunda Especialidad</option></select></div><div class="modal-footer"><button class="btn btn-success">Guardar</button></div></form></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const mf = new bootstrap.Modal('#mdlFac'); const mp = new bootstrap.Modal('#mdlProg');
    function modalFacultad(d=null) {
        document.getElementById('titFac').innerText = d ? 'Editar Facultad' : 'Nueva Facultad';
        document.getElementById('fid').value = d ? d.id : '';
        document.getElementById('fnom').value = d ? d.nombre : '';
        document.getElementById('fsig').value = d ? d.siglas : '';
        mf.show();
    }
    function modalPrograma(d=null) {
        document.getElementById('titProg').innerText = d ? 'Editar Programa' : 'Nuevo Programa';
        document.getElementById('pid').value = d ? d.id : '';
        document.getElementById('pfi').value = d ? d.id_facultad : '';
        document.getElementById('pnom').value = d ? d.nombre : '';
        if(d) document.getElementById('pniv').value = d.nivel;
        mp.show();
    }
</script>
</body></html>
