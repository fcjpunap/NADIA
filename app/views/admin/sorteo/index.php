<!DOCTYPE html><html lang="es"><head><title>Sorteo</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container mt-5"><div class="d-flex justify-content-between mb-4"><h3>Sorteo de Jurados</h3><a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a></div>
<div class="card shadow mb-4"><div class="card-body">
<form action="<?php echo URL_BASE; ?>admin/ejecutar_sorteo" method="POST">
<label>Seleccione Proyecto Apto</label><select name="id_proyecto" class="form-select mb-3"><?php foreach($data['proyectos'] as $p) echo "<option value='{$p['id']}'>{$p['titulo']}</option>"; ?></select>
<button class="btn btn-warning w-100">Ejecutar Sorteo Automático</button></form></div></div>
<div class="card"><div class="card-header">Historial Reciente</div><ul class="list-group list-group-flush">
<?php foreach($data['historial'] as $h): ?>
<li class="list-group-item d-flex justify-content-between align-items-center">
<div><strong><?php echo substr($h['titulo'],0,60); ?>...</strong><br><small class="text-muted"><?php echo $h['nombres'].' '.$h['apellidos']; ?> (<?php echo $h['rol_jurado']; ?>)</small></div>
<span class="badge bg-light text-dark"><?php echo date('d/m/Y', strtotime($h['fecha'])); ?></span>
</li><?php endforeach; ?></ul></div></div></body></html>
