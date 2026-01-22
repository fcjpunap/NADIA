<!DOCTYPE html>
<html lang="es">
<head>
    <title>Proyectos del Docente - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h3>Historial de Proyectos</h3><p class="text-muted mb-0">Docente: <strong><?php echo $data['docente']['nombres'] . ' ' . $data['docente']['apellidos']; ?></strong></p></div>
            <div class="d-flex gap-2">
                <a href="<?php echo URL_BASE; ?>admin/imprimir_constancia?id=<?php echo $data['docente']['id']; ?>" class="btn btn-success"><i class="bi bi-printer"></i> Imprimir Constancia</a>
                <a href="<?php echo URL_BASE; ?>admin/docentes" class="btn btn-secondary">Volver</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-primary border-start-5">
                    <div class="card-header bg-white fw-bold text-primary"><i class="bi bi-gavel"></i> Participación como Jurado</div>
                    <div class="card-body">
                        <?php if (empty($data['jurado'])): ?><p class="text-muted">No ha sido asignado como jurado aún.</p><?php else: ?>
                            <div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Fecha Asignación</th><th>Rol</th><th>Proyecto</th><th>Tesista</th><th>Estado</th><th>Acción</th></tr></thead><tbody><?php foreach ($data['jurado'] as $p): ?><tr><td><?php echo date('d/m/Y', strtotime($p['fecha_asignacion'])); ?></td><td><span class="badge bg-info text-dark"><?php echo $p['rol_jurado']; ?></span></td><td><?php echo $p['titulo']; ?></td><td><?php echo $p['t_nom'] . ' ' . $p['t_ape']; ?></td><td><?php echo $p['estado']; ?></td><td><a href="<?php echo URL_BASE; ?>admin/ver_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary">Ver</a></td></tr><?php endforeach; ?></tbody></table></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow-sm border-success border-start-5">
                    <div class="card-header bg-white fw-bold text-success"><i class="bi bi-eyeglasses"></i> Participación como Asesor</div>
                    <div class="card-body">
                        <?php if (empty($data['asesor'])): ?><p class="text-muted">No ha asesorado proyectos aún.</p><?php else: ?>
                            <div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Fecha Registro</th><th>Proyecto</th><th>Tesista</th><th>Estado</th><th>Acción</th></tr></thead><tbody><?php foreach ($data['asesor'] as $p): ?><tr><td><?php echo date('d/m/Y', strtotime($p['fecha_asignacion'])); ?></td><td><?php echo $p['titulo']; ?></td><td><?php echo $p['t_nom'] . ' ' . $p['t_ape']; ?></td><td><?php echo $p['estado']; ?></td><td><a href="<?php echo URL_BASE; ?>admin/ver_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-success">Ver</a></td></tr><?php endforeach; ?></tbody></table></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
