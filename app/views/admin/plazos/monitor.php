<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Monitor de Plazos - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="bi bi-stopwatch"></i> Monitor de Plazos</h3>
            <div>
                <a href="<?php echo URL_BASE; ?>plazos/index" class="btn btn-secondary me-2"><i class="bi bi-gear"></i> Configurar</a>
                <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Volver</a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form class="d-flex gap-2" method="GET">
                    <input type="text" name="q" class="form-control" placeholder="Buscar por título o tesista..." value="<?php echo htmlspecialchars($data['q']); ?>">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Proyecto</th>
                            <th>Tesista</th>
                            <th>Fase Actual</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Venc.</th>
                            <th>Tiempo Ejec.</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['proyectos'])): ?>
                            <tr><td colspan="8" class="text-center py-4">No se encontraron proyectos.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['proyectos'] as $p): ?>
                                <tr>
                                    <td style="max-width: 300px;">
                                        <div class="fw-bold text-truncate" title="<?php echo htmlspecialchars($p['titulo']); ?>">
                                            <?php echo htmlspecialchars($p['titulo']); ?>
                                        </div>
                                        <small class="text-muted">ID: <?php echo $p['id']; ?></small>
                                    </td>
                                    <td><?php echo $p['nombres'].' '.$p['apellidos']; ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?php echo $p['plazo_info']['fase']; ?></span>
                                    </td>
                                    <td><?php echo $p['fecha_inicio']; ?></td>
                                    <td><?php echo $p['fecha_vencimiento']; ?></td>
                                    
                                    <td class="fw-bold text-primary">
                                        <?php echo $p['tiempo_ejecucion']; ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?php echo $p['plazo_info']['color']; ?>">
                                            <?php echo $p['plazo_info']['texto']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo URL_BASE; ?>admin/ver_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- FIX V77: Usar claves correctas 'pages' y 'current' -->
            <?php if(isset($data['paginacion']['pages']) && $data['paginacion']['pages'] > 1): ?>
            <div class="card-footer">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php for($i=1; $i<=$data['paginacion']['pages']; $i++): ?>
                            <li class="page-item <?php echo ($i==$data['paginacion']['current'])?'active':''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&q=<?php echo $data['q']; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
