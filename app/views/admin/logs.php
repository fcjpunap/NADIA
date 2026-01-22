<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Auditoría del Sistema</h3>
    <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary mb-3">Volver</a>
    
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Detalle</th></tr>
        </thead>
        <tbody>
            <?php foreach($data['logs'] as $log): ?>
            <tr>
                <td><?php echo $log['fecha']; ?></td>
                <td><?php echo $log['usuario']; ?></td>
                <td><span class="badge bg-info"><?php echo $log['accion']; ?></span></td>
                <td><?php echo $log['detalle']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
