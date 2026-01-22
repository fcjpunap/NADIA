<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo $data['ui']['rol_label']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #212529
        }
        .nav-link {
            color: rgba(255, 255, 255, .8)
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1)
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar" style="width:260px">
            <div class="text-center mb-3"><span class="fs-4 fw-bold">NADIA</span><br><small>Núcleo de Apoyo
                    Académico</small></div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="<?php echo URL_BASE; ?>admin/dashboard" class="nav-link active"><i
                            class="bi bi-speedometer2 me-2"></i> Inicio</a></li>
                <li><a href="<?php echo URL_BASE; ?>admin/usuarios" class="nav-link text-white"><i
                            class="bi bi-people"></i> Usuarios</a></li>
                <li class="mt-3 small text-muted text-uppercase">Gestión</li>
                <li><a href="<?php echo URL_BASE; ?>admin/docentes" class="nav-link text-warning">Jurados</a></li>
                <li><a href="<?php echo URL_BASE; ?>admin/tesistas" class="nav-link text-info">Tesistas</a></li>
                <li><a href="<?php echo URL_BASE; ?>admin/proyectos" class="nav-link text-white">Proyectos</a></li>
                <li><a href="<?php echo URL_BASE; ?>admin/sorteo" class="nav-link text-warning fw-bold"><i
                            class="bi bi-shuffle"></i> Sorteo Jurados</a></li>
                <li><a href="<?php echo URL_BASE; ?>reportes/index" class="nav-link text-white">Reportes</a></li>
                <li><a href="<?php echo URL_BASE; ?>perfil/cambiar_password" class="nav-link text-white"><i class="bi bi-key"></i> Cambiar Contraseña</a></li>
                <?php if ($_SESSION['rol'] == 3): ?>
                    <li class="mt-3 small text-muted text-uppercase text-danger">Config</li>
                    <li><a href="<?php echo URL_BASE; ?>academico/index" class="nav-link text-white">Facultades y
                            Programas</a></li>
                    <li><a href="<?php echo URL_BASE; ?>admin/config" class="nav-link text-white">Jerarquía</a></li>
                    <li><a href="<?php echo URL_BASE; ?>plazos/index" class="nav-link text-white">Plazos</a></li>
                    <li><a href="<?php echo URL_BASE; ?>admin/config_email" class="nav-link text-white">Email SMTP</a></li>
                    <li><a href="<?php echo URL_BASE; ?>admin/plantillas" class="nav-link text-white">Plantillas</a></li>
                <?php endif; ?>
            </ul>
            <hr><a href="<?php echo URL_BASE; ?>auth/logout" class="btn btn-danger w-100">Salir</a> <a href="<?php echo URL_BASE; ?>mensajes/index" class="btn btn-warning w-100 mt-2"><i class="bi bi-envelope"></i> Casilla Electrónica</a>
        </div>
        <div class="container-fluid bg-light p-4">
            <h3><?php echo $data['ui']['rol_label']; ?></h3>
            <!-- Filtros -->
            <form method="GET" action="<?php echo URL_BASE; ?>admin/dashboard" class="row g-2 mb-4 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small text-muted">Año</label>
                    <select name="anio" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($data['filtros']['anios_disponibles'] as $a): ?>
                            <option value="<?php echo $a; ?>" <?php echo ($a == $data['filtros']['anio']) ? 'selected' : ''; ?>><?php echo $a; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Mes</label>
                    <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php
                        $meses = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];
                        foreach ($meses as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php echo ($k == $data['filtros']['mes']) ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8 text-end">
                    <small class="text-muted">Mostrando datos de:
                        <strong><?php echo $data['filtros']['anio']; ?></strong>
                        <?php echo ($data['filtros']['mes']) ? ' - ' . $meses[$data['filtros']['mes']] : ''; ?></small>
                </div>
            </form>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 border-primary border-start-5 shadow-sm h-100">
                        <h6 class="text-muted small">PROYECTOS NUEVOS</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><?php echo $data['stats']['proyectos']; ?></h2>
                            <i class="bi bi-folder-plus fs-1 text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-warning border-start-5 shadow-sm h-100">
                        <h6 class="text-muted small">BORRADORES</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><?php echo $data['stats']['borradores']; ?></h2>
                            <span
                                class="badge bg-warning text-dark"><?php echo $data['stats']['perc_borradores']; ?>%</span>
                        </div>
                        <small class="text-muted" style="font-size:0.8rem">del total de proyectos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-success border-start-5 shadow-sm h-100">
                        <h6 class="text-muted small">SUSTENTACIONES</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><?php echo $data['stats']['sustentaciones']; ?></h2>
                            <span class="badge bg-success"><?php echo $data['stats']['perc_sustentados']; ?>%</span>
                        </div>
                        <small class="text-muted" style="font-size:0.8rem">del total de proyectos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 border-dark border-start-5 shadow-sm h-100">
                        <h6 class="text-muted small">JURADOS ACTIVOS</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><?php echo $data['stats']['jurados_activos']; ?></h2>
                            <i class="bi bi-person-badge fs-1 text-dark opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sección de Gráficos Superiores -->
            <div class="row g-4 mb-4">
                <!-- Gráfico de Evolución Mensual -->
                <div class="col-md-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold"><i class="bi bi-graph-up"></i> Evolución Mensual
                            (Último Año)</div>
                        <div class="card-body">
                            <canvas id="chartMensual"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Gráfico de Estados -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold"><i class="bi bi-pie-chart"></i> Estado de Proyectos
                        </div>
                        <div class="card-body">
                            <canvas id="chartEstados"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sección de Temáticas (Full Width) -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-list-ul"></i> Proyectos por Temática (Detalle)</span>
                            <span class="badge bg-light text-dark border">Top Temáticas</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Columna Gráfico -->
                                <div class="col-md-5 border-end">
                                    <h6 class="text-muted small mb-3 text-center">Distribución Visual</h6>
                                    <div style="height: 300px;">
                                        <canvas id="chartTematicas"></canvas>
                                    </div>
                                </div>
                                <!-- Columna Tabla -->
                                <div class="col-md-7">
                                    <h6 class="text-muted small mb-3">Detalle por Sublínea</h6>
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-sm table-hover align-middle">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th>Área</th>
                                                    <th>Línea</th>
                                                    <th>Sublínea</th>
                                                    <th class="text-center">Cant.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($data['stats']['graficos']['tematicas'])): ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-4">Sin datos registrados
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($data['stats']['graficos']['tematicas'] as $t): ?>
                                                        <tr>
                                                            <td><small class="text-muted"><?php echo $t['area']; ?></small></td>
                                                            <td><?php echo $t['linea']; ?></td>
                                                            <td class="fw-bold text-primary"><?php echo $t['sublinea']; ?></td>
                                                            <td class="text-center"><span class="badge bg-secondary rounded-pill"><?php echo $t['total']; ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts para Gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos desde PHP
        const datosProyectos = <?php echo json_encode($data['stats']['graficos']['proyectos_mes']); ?>;
        const datosSustentaciones = <?php echo json_encode($data['stats']['graficos']['sustentaciones_mes']); ?>;
        const datosEstados = <?php echo json_encode($data['stats']['graficos']['estados']); ?>;
        // Procesar datos para Chart.js
        const etiquetas = datosProyectos.map(d => d.mes_anio);
        const valoresProyectos = datosProyectos.map(d => d.total);
        const valoresSustentaciones = etiquetas.map(mes => {
            const found = datosSustentaciones.find(d => d.mes_anio === mes);
            return found ? found.total : 0;
        });
        // Gráfico Mensual
        new Chart(document.getElementById('chartMensual'), {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [
                    {
                        label: 'Nuevos Proyectos',
                        data: valoresProyectos,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Sustentaciones',
                        data: valoresSustentaciones,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        // Gráfico de Estados
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: datosEstados.map(d => d.estado),
                datasets: [{
                    data: datosEstados.map(d => d.total),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
        // Gráfico de Temáticas (Detallado)
        const datosTematicas = <?php echo json_encode($data['stats']['graficos']['tematicas']); ?>;
        
        // Usamos etiquetas detalladas: Línea > Sublínea
        // Limitamos a los top 10 para que el gráfico no sea infinito si hay muchas
        const topTematicas = datosTematicas.slice(0, 15);
        
        const labelsTematicas = topTematicas.map(t => t.sublinea.substring(0, 25) + (t.sublinea.length > 25 ? '...' : ''));
        const dataTematicas = topTematicas.map(t => t.total);
        new Chart(document.getElementById('chartTematicas'), {
            type: 'bar',
            data: {
                labels: labelsTematicas,
                datasets: [{
                    label: 'Proyectos',
                    data: dataTematicas,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
