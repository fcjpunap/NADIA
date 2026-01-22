<!DOCTYPE html>
<html lang="es">
<head><title>Estadísticas - NADIA</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4"><h3>Reporte Estadístico</h3><a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">Proyectos por Estado</div>
                <div class="card-body"><canvas id="chartEstado"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">Proyectos por Programa</div>
                <div class="card-body"><canvas id="chartPrograma"></canvas></div>
            </div>
        </div>
    </div>
</div>
<script>
    const ctx1 = document.getElementById('chartEstado');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($data['estados'], 'estado')); ?>,
            datasets: [{ data: <?php echo json_encode(array_column($data['estados'], 'cantidad')); ?>, borderWidth: 1 }]
        }
    });
    
    const ctx2 = document.getElementById('chartPrograma');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($data['programas'], 'programa')); ?>,
            datasets: [{ label: 'Cant. Proyectos', data: <?php echo json_encode(array_column($data['programas'], 'cantidad')); ?>, backgroundColor: '#36A2EB' }]
        }
    });
</script>
</body></html>
