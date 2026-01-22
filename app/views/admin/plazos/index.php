<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Plazos - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h3><i class="bi bi-calendar-range"></i> Gestión de Plazos y Control de Acceso</h3>
        <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a>
    </div>
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-lock-fill"></i> Control de Acceso (Vacaciones)</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Cierre temporal para Tesistas y Jurados durante vacaciones.</p>
                    
                    <form action="<?php echo URL_BASE; ?>plazos/guardar_acceso" method="POST">
                        <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                            <input class="form-check-input" type="checkbox" id="chkActivo" name="activo" value="1" 
                                <?php echo (isset($data['acceso']['activo']) && $data['acceso']['activo'] == 1) ? 'checked' : ''; ?>
                                onchange="document.getElementById('seccionFechas').style.display = this.checked ? 'block' : 'none';">
                            <label class="form-check-label fw-bold text-danger" for="chkActivo">
                                Activar Cierre del Sistema
                            </label>
                        </div>
                        <div id="seccionFechas" style="<?php echo (isset($data['acceso']['activo']) && $data['acceso']['activo'] == 1) ? '' : 'display:none;'; ?>">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small">Fecha Inicio</label>
                                    <input type="datetime-local" name="fecha_inicio" class="form-control form-control-sm" 
                                           value="<?php echo !empty($data['acceso']['fecha_inicio']) ? date('Y-m-d\TH:i', strtotime($data['acceso']['fecha_inicio'])) : ''; ?>">
                                    <small class="text-muted">Vacío = inmediato</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Fecha Fin</label>
                                    <input type="datetime-local" name="fecha_fin" class="form-control form-control-sm" 
                                           value="<?php echo !empty($data['acceso']['fecha_fin']) ? date('Y-m-d\TH:i', strtotime($data['acceso']['fecha_fin'])) : ''; ?>">
                                    <small class="text-muted">Vacío = indefinido</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Mensaje para Usuarios</label>
                                <textarea name="mensaje" class="form-control form-control-sm" rows="3"><?php echo htmlspecialchars($data['acceso']['mensaje_cierre'] ?? 'Sistema cerrado temporalmente por vacaciones.'); ?></textarea>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                Guardar Configuración
                            </button>
                        </div>
                    </form>
                    <hr>
                    <?php if(isset($data['acceso']['activo']) && $data['acceso']['activo'] == 1): ?>
                        <div class="alert alert-danger mb-0 small">
                            <strong>Estado:</strong> Sistema CERRADO
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-0 small">
                            <strong>Estado:</strong> Sistema ABIERTO
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Plazos por Etapa</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Etapa</th>
                                <th>Estado</th>
                                <th>Días</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['plazos'])): ?>
                                <tr><td colspan="4" class="text-center text-muted">Sin plazos configurados</td></tr>
                            <?php else: ?>
                                <?php foreach($data['plazos'] as $p): ?>
                                <tr>
                                    <td class="small"><?php echo $p['etapa_nombre']; ?></td>
                                    <td class="small text-muted"><?php echo $p['estado_trigger']; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $p['dias_plazo']; ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editPlazo(<?php echo $p['id']; ?>, <?php echo $p['dias_plazo']; ?>, '<?php echo $p['etapa_nombre']; ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="d-grid">
                        <a href="<?php echo URL_BASE; ?>plazos/monitor" class="btn btn-outline-info btn-sm">
                            Ver Monitor de Plazos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mPlazo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Editar Plazo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo URL_BASE; ?>plazos/actualizar_plazo" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="eId">
                    <div class="mb-3">
                        <label>Etapa</label>
                        <input type="text" id="eEtapa" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Días</label>
                        <input type="number" name="dias_plazo" id="eDias" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const m = new bootstrap.Modal(document.getElementById('mPlazo'));
function editPlazo(id, dias, etapa) {
    document.getElementById('eId').value = id;
    document.getElementById('eDias').value = dias;
    document.getElementById('eEtapa').value = etapa;
    m.show();
}
</script>
</body>
</html>
