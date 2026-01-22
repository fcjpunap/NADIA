<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Revisión de Asesoría</h4>
        <a href="<?php echo URL_BASE; ?>docente/dashboard" class="btn btn-secondary">Volver</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5><?php echo $data['proyecto']['titulo']; ?></h5>
                    <p class="text-muted">Tesista: <?php echo $data['proyecto']['nombres'].' '.$data['proyecto']['apellidos']; ?></p>
                    <hr>
                    <h6>Resumen:</h6>
                    <p><?php echo nl2br($data['proyecto']['resumen']); ?></p>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Documentos del Estudiante</div>
                <ul class="list-group list-group-flush">
                    <?php foreach($data['documentos'] as $doc): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="bi bi-file-pdf text-danger"></i> <?php echo $doc['nombre_archivo_original']; ?></span>
                        <a href="<?php echo URL_BASE . $doc['ruta_archivo']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver PDF</a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-success h-100">
                <div class="card-header bg-success text-white">Dictamen de Asesor</div>
                <div class="card-body text-center">
                    <p>Si el proyecto cumple con los requisitos, apruébelo para que la Coordinación realice el sorteo de jurados.</p>
                    
                    <form action="<?php echo URL_BASE; ?>docente/aprobar_para_sorteo" method="POST" onsubmit="return confirm('¿Está seguro? El proyecto pasará a manos del Administrador.');">
                        <input type="hidden" name="id_proyecto" value="<?php echo $data['proyecto']['id']; ?>">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Aprobar y Enviar a Sorteo
                            </button>
                            <button type="button" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Devolver con Observaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
