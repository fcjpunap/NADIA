<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Dashboard BI Pro | NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #4e44e7; --bg: #f8fafc; }
        body { background: var(--bg); font-family: 'Inter', sans-serif; color: #1e293b; }
        .card { border: none; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .ranking-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .ranking-item:last-child { border: 0; }
        .badge-rank { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; background: #eef2ff; color: #4338ca; }
        .bg-gold { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="container-fluid py-4 px-lg-5">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div><h2 class="fw-bold mb-0">Centro de Inteligencia Académica</h2><p class="text-muted">Rankings de productividad y análisis científico</p></div>
            <div class="d-flex gap-2">
                <a href="<?php echo URL_BASE; ?>reportes/constructor" class="btn btn-primary fw-bold px-4 rounded-pill shadow">Constructor Maestro</a>
                <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-light border px-4 rounded-pill shadow-sm">Panel Admin</a>
            </div>
        </header>
        <!-- Rankings Section -->
        <div class="row g-4 mb-5">
            <!-- Top Asesores -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-person-check-fill me-2"></i> Top Asesores</h6>
                    <div class="ranking-list">
                        <?php foreach($data['stats']['rankings']['asesores'] as $i => $r): ?>
                        <div class="ranking-item">
                            <div class="d-flex align-items-center">
                                <span class="badge-rank me-3 <?php echo ($i==0?'bg-gold':''); ?>"><?php echo $i+1; ?></span>
                                <span class="small fw-bold text-truncate" style="max-width: 150px;"><?php echo $r['etiqueta']; ?></span>
                            </div>
                            <span class="badge bg-indigo-soft text-primary rounded-pill px-3"><?php echo $r['total']; ?> Proy.</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Top Jurados -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-people-fill me-2"></i> Jurados más Activos</h6>
                    <div class="ranking-list">
                        <?php foreach($data['stats']['rankings']['jurados'] as $i => $r): ?>
                        <div class="ranking-item">
                            <div class="d-flex align-items-center">
                                <span class="badge-rank me-3"><?php echo $i+1; ?></span>
                                <span class="small fw-bold text-truncate" style="max-width: 150px;"><?php echo $r['etiqueta']; ?></span>
                            </div>
                            <span class="badge bg-emerald-soft text-success rounded-pill px-3"><?php echo $r['total']; ?> Particip.</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Top Facultades -->
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-building-fill-check me-2"></i> Facultades Líderes</h6>
                    <div class="ranking-list">
                        <?php foreach($data['stats']['rankings']['facultades'] as $i => $r): ?>
                        <div class="ranking-item">
                            <div class="d-flex align-items-center">
                                <span class="badge-rank me-3"><?php echo $i+1; ?></span>
                                <span class="small fw-bold text-truncate" style="max-width: 150px; font-size: 0.75rem;"><?php echo $r['etiqueta']; ?></span>
                            </div>
                            <span class="badge bg-amber-soft text-warning rounded-pill px-3"><?php echo $r['total']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-6"><div class="card h-100 p-4"><h5 class="fw-bold mb-4">Estado de la Tesis</h5><div style="height:300px"><canvas id="chartEstados"></canvas></div></div></div>
            <div class="col-lg-6">
                <div class="card h-100 p-4"><h5 class="fw-bold mb-4">Actividad Reciente</h5>
                    <div class="list-group list-group-flush">
                        <?php foreach($data['stats']['recientes'] as $r): ?>
                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                            <div class="d-flex justify-content-between"><h6 class="mb-0 fw-bold text-truncate" style="max-width:300px"><?php echo $r['titulo']; ?></h6><small><?php echo date('H:i', strtotime($r['fecha'])); ?></small></div>
                            <p class="small text-muted mb-0"><?php echo $r['accion']; ?> - <b><?php echo $r['usuario']; ?></b></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        new Chart(document.getElementById('chartEstados'), { type: 'doughnut', data: { labels: <?php echo json_encode(array_column($data['stats']['estados'], 'estado')); ?>, datasets: [{ data: <?php echo json_encode(array_column($data['stats']['estados'], 'total')); ?>, backgroundColor: ['#4e44e7', '#10b981', '#f59e0b', '#ef4444', '#6366f1'], borderWidth: 0, cutout: '70%' }] }, options: { plugins: { legend: { position: 'bottom' } }, maintainAspectRatio: false } });
    </script>
</body>
</html>
