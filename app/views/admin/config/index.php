<!DOCTYPE html><html lang="es"><head><title>Configuración</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><div class="container mt-5">
<div class="d-flex justify-content-between mb-3"><h3>Jerarquía de Investigación</h3><a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a></div>
<div class="row"><div class="col-md-4">
    <div class="card mb-3"><div class="card-header">Agregar Área</div><div class="card-body"><form action="<?php echo URL_BASE; ?>admin/agregar_nodo" method="POST"><input type="hidden" name="tipo" value="area"><input type="text" name="nombre" class="form-control mb-2" required><button class="btn btn-primary w-100">Crear</button></form></div></div>
    <div class="card mb-3"><div class="card-header">Agregar Línea</div><div class="card-body"><form action="<?php echo URL_BASE; ?>admin/agregar_nodo" method="POST"><input type="hidden" name="tipo" value="linea"><select name="parent_id" class="form-select mb-2"><?php foreach($data['arbol'] as $a) echo "<option value='{$a['id']}'>{$a['nombre']}</option>"; ?></select><input type="text" name="nombre" class="form-control mb-2" required><button class="btn btn-primary w-100">Crear</button></form></div></div>
    <div class="card"><div class="card-header">Agregar Sublínea</div><div class="card-body"><form action="<?php echo URL_BASE; ?>admin/agregar_nodo" method="POST"><input type="hidden" name="tipo" value="sublinea"><select name="parent_id" class="form-select mb-2"><?php foreach($data['arbol'] as $a) foreach($a['lineas'] as $l) echo "<option value='{$l['id']}'>{$a['nombre']} - {$l['nombre']}</option>"; ?></select><input type="text" name="nombre" class="form-control mb-2" required><button class="btn btn-primary w-100">Crear</button></form></div></div>
</div><div class="col-md-8">
    <div class="card"><div class="card-header">Estructura Actual</div><div class="card-body" style="max-height:600px;overflow-y:auto"><ul class="list-group">
    <?php if(empty($data['arbol'])): ?><li>Sin datos.</li><?php else: foreach($data['arbol'] as $a): ?>
        <li class="list-group-item bg-light fw-bold d-flex justify-content-between">
            <?php echo $a['nombre']; ?> 
            <div><button class="btn btn-sm btn-warning" onclick="editNode(<?php echo $a['id']; ?>,'area','<?php echo $a['nombre']; ?>')">✏️</button> <a href="<?php echo URL_BASE; ?>admin/eliminar_nodo?tipo=area&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger">×</a></div>
        </li>
        <?php if(isset($a['lineas'])) foreach($a['lineas'] as $l): ?>
            <li class="list-group-item ms-4 d-flex justify-content-between"><span>L: <?php echo $l['nombre']; ?></span>
            <div><button class="btn btn-sm btn-warning" onclick="editNode(<?php echo $l['id']; ?>,'linea','<?php echo $l['nombre']; ?>')">✏️</button> <a href="<?php echo URL_BASE; ?>admin/eliminar_nodo?tipo=linea&id=<?php echo $l['id']; ?>" class="btn btn-sm btn-danger">×</a></div></li>
            <?php if(isset($l['sublineas'])) foreach($l['sublineas'] as $s): ?>
                <li class="list-group-item ms-5 small d-flex justify-content-between"><span class="text-muted">- <?php echo $s['nombre']; ?></span>
                <div><button class="btn btn-sm btn-warning" onclick="editNode(<?php echo $s['id']; ?>,'sublinea','<?php echo $s['nombre']; ?>')">✏️</button> <a href="<?php echo URL_BASE; ?>admin/eliminar_nodo?tipo=sublinea&id=<?php echo $s['id']; ?>" class="btn btn-sm btn-danger">×</a></div></li>
            <?php endforeach; endforeach; endforeach; endif; ?>
    </ul></div></div>
</div></div></div>
<div class="modal fade" id="mEdit"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Editar</h5></div><form action="<?php echo URL_BASE; ?>admin/editar_nodo" method="POST"><div class="modal-body"><input type="hidden" name="id" id="eid"><input type="hidden" name="tipo" id="etipo"><label>Nombre</label><input type="text" name="nombre" id="enombre" class="form-control"></div><div class="modal-footer"><button class="btn btn-success">Guardar</button></div></form></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>const me=new bootstrap.Modal('#mEdit'); function editNode(id,type,name){ document.getElementById('eid').value=id; document.getElementById('etipo').value=type; document.getElementById('enombre').value=name; me.show(); }</script>
</body></html>
