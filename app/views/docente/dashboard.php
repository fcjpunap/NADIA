<!DOCTYPE html>
<html lang="es">
<head>
    <title>Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-success p-3">
        <div class="container"><span class="navbar-brand">NADIA Docente</span>
            <div class="text-white">
                <?php echo $data['nombre']; ?>
                <a href="<?php echo URL_BASE; ?>mensajes/index" class="btn btn-warning btn-sm ms-2"><i class="bi bi-envelope"></i> Casilla</a>
                <a href="<?php echo URL_BASE; ?>perfil/cambiar_password" class="btn btn-outline-light btn-sm ms-2">Contraseña</a>
                <a href="<?php echo URL_BASE; ?>auth/logout" class="btn btn-outline-light btn-sm ms-2">Salir</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <ul class="nav nav-tabs mb-3" id="dTab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tA">Mis Asesorados</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t1">J. Proyectos</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t2">J. Borradores</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t3">J. Sustentaciones</button></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tA">
                <div class="card"><div class="card-body">
                        <table class="table"><thead><tr><th>Título</th><th>Tesista</th><th>Estado</th><th>Acción</th></tr></thead>
                            <tbody><?php foreach ($data['asesorados'] as $p) echo "<tr><td>{$p['titulo']}</td><td>{$p['tesista_n']} {$p['tesista_a']}</td><td>{$p['estado']}</td><td><a href='" . URL_BASE . "docente/revision?id={$p['id']}' class='btn btn-sm btn-primary'>Ver</a></td></tr>"; ?></tbody>
                        </table>
                </div></div>
            </div>
            <div class="tab-pane fade" id="t1">
                <div class="card"><div class="card-body">
                    <table class="table"><thead><tr><th>Título</th><th>Tesista</th><th>Voto</th><th>Acción</th></tr></thead>
                        <tbody><?php foreach ($data['jurados_proy'] as $j) echo "<tr><td>{$j['titulo']}</td><td>{$j['tesista_n']} {$j['tesista_a']}</td><td>" . ($j['mi_voto'] ?? 'Pendiente') . "</td><td><a href='" . URL_BASE . "docente/dictaminar?id={$j['id']}' class='btn btn-sm btn-primary'>Ver</a></td></tr>"; ?></tbody>
                    </table>
                </div></div>
            </div>
            <div class="tab-pane fade" id="t2">
                <div class="card"><div class="card-body">
                    <table class="table"><thead><tr><th>Título</th><th>Tesista</th><th>Voto</th><th>Acción</th></tr></thead>
                        <tbody><?php foreach ($data['jurados_borr'] as $j) echo "<tr><td>{$j['titulo']}</td><td>{$j['tesista_n']} {$j['tesista_a']}</td><td>" . ($j['mi_voto'] ?? 'Pendiente') . "</td><td><a href='" . URL_BASE . "docente/dictaminar?id={$j['id']}' class='btn btn-sm btn-primary'>Ver</a></td></tr>"; ?></tbody>
                    </table>
                </div></div>
            </div>
            <div class="tab-pane fade" id="t3">
                <div class="card border-success"><div class="card-header bg-success text-white">Sustentaciones</div>
                    <div class="card-body">
                        <table class="table"><thead><tr><th>Título</th><th>Tesista</th><th>Fecha/Hora</th><th>Lugar</th><th>Mi Voto</th><th>Acciones</th></tr></thead>
                            <tbody>
                                <?php foreach ($data['jurados_sust'] as $j): ?>
                                    <tr>
                                        <td><?php echo $j['titulo']; ?></td><td><?php echo $j['tesista_n'] . ' ' . $j['tesista_a']; ?></td>
                                        <td><?php echo $j['fecha_sustentacion'] . ' ' . $j['hora_sustentacion']; ?></td>
                                        <td><?php echo $j['lugar_sustentacion']; if(!empty($j['url_sustentacion'])) echo "<br><a href='{$j['url_sustentacion']}' target='_blank' class='btn btn-sm btn-outline-success mt-1'>Sala Virtual</a>"; ?></td>
                                        <td><?php echo $j['mi_voto'] ?? 'Pendiente'; ?></td>
                                        <td><a href="<?php echo URL_BASE; ?>docente/dictaminar?id=<?php echo $j['id']; ?>" class="btn btn-sm btn-primary mb-1">Revisar</a><?php if ($j['conteo_votos'] >= 3): ?><a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $j['id']; ?>&tipo=sustentacion" target="_blank" class="btn btn-sm btn-dark">Acta</a><?php endif; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
