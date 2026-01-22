<!DOCTYPE html>
<html lang="es">
<head>
    <title>NADIA - Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Gestión de Sustentaciones</h2>
        <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">Programar Defensa</div>
                <div class="card-body">
                    <form action="<?php echo URL_BASE; ?>admin/programar_sustentacion" method="POST">
                        <div class="mb-3">
                            <label>Proyecto Apto</label>
                            <select name="id_proyecto" class="form-select" required>
                                <?php foreach($data['aptos'] as $apto): ?>
                                <option value="<?php echo $apto['id']; ?>"><?php echo $apto['titulo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_hora" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Lugar / Enlace Meet</label>
                            <input type="text" name="lugar" class="form-control" placeholder="meet.google.com/..." required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Programar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">Calendario de Defensas</div>
                <div class="card-body">
                    <?php if(empty($data['sustentaciones'])): ?>
                        <div class="alert alert-info">No hay defensas programadas.</div>
                    <?php else: ?>
                    <table class="table">
                        <thead><tr><th>Fecha</th><th>Tesista</th><th>Lugar</th><th>Estado</th></tr></thead>
                        <tbody>
                            <?php foreach($data['sustentaciones'] as $s): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($s['fecha_hora'])); ?></td>
                                <td><?php echo $s['tesista']; ?></td>
                                <td><a href="#"><?php echo $s['lugar_enlace']; ?></a></td>
                                <td><span class="badge bg-info"><?php echo $s['estado']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
