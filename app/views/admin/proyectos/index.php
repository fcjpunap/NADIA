<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Proyectos - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive { border-radius: 12px; overflow: hidden; background: white; }
        .badge-status { padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; }
        .bg-custom-info { background-color: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-briefcase text-primary"></i> Proyectos Académicos</h2>
            <p class="text-muted">Administración y seguimiento de tesis</p>
        </div>
        <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary rounded-pill px-4">
            <i class="bi bi-house-door"></i> Inicio
        </a>
    </div>
    <!-- Buscador Avanzado -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-9">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Buscar por título, tesista, DNI..." value="<?php echo htmlspecialchars($data['q']??''); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm">Buscar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Tabla de Proyectos -->
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" style="width: 40%">Proyecto</th>
                            <th>Tesista</th>
                            <th>Estado</th>
                            <th class="text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($data['proyectos'])): ?>
                        <tr><td colspan="4" class="text-center py-5">No se encontraron proyectos para esta búsqueda.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['proyectos'] as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 400px;"><?php echo htmlspecialchars($p['titulo']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($p['fac_nombre']??''); ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?php echo strtoupper(substr($p['tesista'],0,1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($p['tesista']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-status bg-custom-info">
                                    <?php echo htmlspecialchars($p['estado']); ?>
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group shadow-sm">
                                    <a href="<?php echo URL_BASE; ?>admin/archivos_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info text-white" title="Gestionar Archivos">
                                        <i class="bi bi-folder-fill"></i>
                                    </a>
                                    <a href="<?php echo URL_BASE; ?>admin/ver_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo URL_BASE; ?>admin/editar_proyecto?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button onclick="archivarProyecto(<?php echo $p['id']; ?>)" class="btn btn-sm btn-outline-secondary" title="Archivar">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                    <button onclick="eliminarProyecto(<?php echo $p['id']; ?>)" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Paginador Profesional -->
    <?php if(isset($data['paginacion']) && $data['paginacion']['pages'] > 1): ?>
    <div class="row align-items-center mt-5">
        <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
            <span class="text-muted fw-bold">
                Página <span class="text-primary"><?php echo $data['paginacion']['current']; ?></span> de <?php echo $data['paginacion']['pages']; ?>
            </span>
            <div class="small text-muted text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">
                Total: <?php echo $data['paginacion']['total']; ?> registros encontrados
            </div>
        </div>
        <div class="col-md-8">
            <nav>
                <ul class="pagination justify-content-center justify-content-md-end mb-0 shadow-sm border rounded-pill p-1 bg-white">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($data['paginacion']['current'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded-pill px-3" href="?page=<?php echo $data['paginacion']['current'] - 1; ?>&q=<?php echo urlencode($data['q']??''); ?>">
                            <i class="bi bi-arrow-left me-1"></i> Anterior
                        </a>
                    </li>
                    <!-- Números de Página -->
                    <?php 
                    $range = 2; // Cantidad de paginas a mostrar antes y después de la actual
                    for($i=1; $i<=$data['paginacion']['pages']; $i++): 
                        if ($i == 1 || $i == $data['paginacion']['pages'] || ($i >= $data['paginacion']['current'] - $range && $i <= $data['paginacion']['current'] + $range)):
                    ?>
                        <li class="page-item <?php echo ($i == $data['paginacion']['current']) ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded-circle fw-bold mx-1" href="?page=<?php echo $i; ?>&q=<?php echo urlencode($data['q']??''); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php elseif($i == $data['paginacion']['current'] - ($range + 1) || $i == $data['paginacion']['current'] + ($range + 1)): ?>
                        <li class="page-item disabled"><span class="page-link border-0">...</span></li>
                    <?php endif; endfor; ?>
                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($data['paginacion']['current'] >= $data['paginacion']['pages']) ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded-pill px-3" href="?page=<?php echo $data['paginacion']['current'] + 1; ?>&q=<?php echo urlencode($data['q']??''); ?>">
                            Siguiente <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function eliminarProyecto(id) {
    if(confirm("⚠️ ADVERTENCIA: Esta acción eliminará PERMANENTEMENTE el proyecto y todos sus datos relacionados (jurados, actas, archivos, etc).\n\n¿Desea continuar?")) {
        const motivo = prompt("Ingrese el motivo de la eliminación para el registro de auditoría:");
        if (motivo) { 
            window.location.href = "<?php echo URL_BASE; ?>admin/eliminar_proyecto?id=" + id + "&motivo=" + encodeURIComponent(motivo); 
        }
    }
}
function archivarProyecto(id) {
    const motivo = prompt("Ingrese el motivo para archivar (ej: Abandono, Renuncia, Plazo vencido):");
    if (motivo) { 
        window.location.href = "<?php echo URL_BASE; ?>admin/archivar_proyecto?id=" + id + "&motivo=" + encodeURIComponent(motivo); 
    }
}
</script>
</body>
</html>
