<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Gestión de Jurados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Gestión de Jurados y Asesores</h3>
                <p class="text-muted small mb-0">Facultad: <?php echo $data['ui']['scope_label']; ?></p>
            </div>
            <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver al Panel</a>
        </div>
        <form class="d-flex mb-3" method="GET">
            <input class="form-control me-2" type="search" name="q" placeholder="Buscar por nombre o apellido..." value="<?php echo $data['q'] ?? ''; ?>">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
            <?php if (!empty($data['q'])): ?>
                <a href="<?php echo URL_BASE; ?>admin/docentes" class="btn btn-outline-secondary ms-2">Limpiar</a>
            <?php endif; ?>
        </form>
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Docente</th>
                                <th>Teléfono</th>
                                <th>Grado</th>
                                <th>Facultad Asignada</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['docentes'])): ?>
                                <tr><td colspan="6" class="text-center py-4">No se encontraron docentes.</td></tr>
                            <?php else: ?>
                                <?php foreach ($data['docentes'] as $d): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border"><?php echo $d['codigo'] ?: 'S/C'; ?></span></td>
                                        <td class="fw-bold"><?php echo $d['apellidos'] . ' ' . $d['nombres']; ?></td>
                                        <td><?php echo $d['telefono']; ?></td>
                                        <td><?php echo $d['grado_academico']; ?></td>
                                        <td><small class="text-muted"><?php echo $d['facultad_asignada']; ?></small></td>
                                        <td>
                                            <a href="<?php echo URL_BASE; ?>admin/editar_docente?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
                                            <a href="<?php echo URL_BASE; ?>admin/docente_proyectos?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-folder2-open"></i> Ver Proyectos</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($data['paginacion']['pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($data['paginacion']['current'] == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $data['paginacion']['current'] - 1; ?>&q=<?php echo $data['q']; ?>">Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $data['paginacion']['pages']; $i++): ?>
                        <li class="page-item <?php echo ($i == $data['paginacion']['current']) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&q=<?php echo $data['q']; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($data['paginacion']['current'] == $data['paginacion']['pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $data['paginacion']['current'] + 1; ?>&q=<?php echo $data['q']; ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</body>
</html>
