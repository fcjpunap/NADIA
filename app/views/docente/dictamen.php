<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow border-danger">
        <div class="card-header bg-danger text-white">
            <h4>Emisión de Dictamen - Jurado Calificador</h4>
        </div>
        <div class="card-body">
            <h5>Proyecto: <?php echo $data['proyecto']['titulo']; ?></h5>
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Documentos a Evaluar:</h6>
                    <ul class="list-group mb-3">
                        <?php foreach($data['documentos'] as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?php echo $doc['nombre_archivo_original']; ?>
                            <a href="<?php echo URL_BASE . $doc['ruta_archivo']; ?>" target="_blank" class="btn btn-sm btn-outline-dark">Ver</a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="col-md-6">
                    <form action="<?php echo URL_BASE; ?>docente/guardar_dictamen" method="POST">
                        <input type="hidden" name="id_proyecto" value="<?php echo $data['proyecto']['id']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Veredicto:</label>
                            <select name="resultado" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="Aprobado" class="text-success">APROBADO</option>
                                <option value="Observado" class="text-warning">OBSERVADO (Requiere correcciones)</option>
                                <option value="Rechazado" class="text-danger">RECHAZADO</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Observaciones / Recomendaciones:</label>
                            <textarea name="observaciones" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100">Emitir Voto Oficial</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
