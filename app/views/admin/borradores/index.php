<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Revisión de Borradores de Tesis</h2>
        <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a>
    </div>
    
    <div class="card shadow border-warning">
        <div class="card-header bg-warning text-dark">Expedientes en Fase de Borrador</div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr><th>Cód</th><th>Título</th><th>Tesista</th><th>Versión</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach($data['proyectos'] as $p): ?>
                    <tr>
                        <td><?php echo $p['uuid']; ?></td>
                        <td><?php echo $p['titulo']; ?></td>
                        <td><?php echo $p['tesista']; ?></td>
                        <td>v.2.0</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">Ver PDF</button>
                            <button class="btn btn-sm btn-outline-success">Dictamen</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
