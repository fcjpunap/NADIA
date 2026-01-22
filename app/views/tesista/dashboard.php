<!DOCTYPE html>
<html lang="es">

<head>
    <title>Tesista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary p-3">
        <div class="container"><span class="navbar-brand">NADIA</span>
            <div class="text-white"><?php echo $data['nombre']; ?>
                <a href="<?php echo URL_BASE; ?>perfil/cambiar_password"
                    class="btn btn-outline-light btn-sm ms-2">Contraseña</a>
                <a href="<?php echo URL_BASE; ?>mensajes/index" class="btn btn-warning btn-sm ms-2"><i
                        class="bi bi-envelope"></i> Casilla</a> <a href="<?php echo URL_BASE; ?>auth/logout"
                    class="btn btn-outline-light btn-sm ms-2">Salir</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php if (!$data['proyecto']): ?>
            <div class="text-center py-5"><a href="<?php echo URL_BASE; ?>tesista/nuevo_proyecto"
                    class="btn btn-primary btn-lg">Registrar Proyecto</a></div><?php else: ?>
            <ul class="nav nav-tabs mb-3" id="tTab">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tp1">1.
                        Proyecto</button></li>
                <li class="nav-item"><button
                        class="nav-link <?php echo ($data['proyecto']['id_etapa_actual'] >= 2) ? '' : 'disabled'; ?>"
                        data-bs-toggle="tab" data-bs-target="#tp2">2. Borrador Tesis</button></li>
                <li class="nav-item"><button
                        class="nav-link <?php echo ($data['proyecto']['id_etapa_actual'] >= 3) ? '' : 'disabled'; ?>"
                        data-bs-toggle="tab" data-bs-target="#tp3">3. Sustentación</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tp4">Historial</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tp1">
                    <?php if ($data['proyecto']['estado'] == 'Iniciado'): ?>
                        <div class="alert alert-warning border-warning shadow-sm">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Pendiente de Sorteo</h5>
                            <p class="mb-0">Su proyecto ha sido registrado. El Coordinador de Investigación realizará el sorteo
                                de jurados en un plazo máximo de <strong>5 días hábiles</strong>.</p>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3 border-primary">
                                <div class="card-body">
                                    <h4><?php echo $data['proyecto']['titulo']; ?></h4>
                                    <span class="badge bg-info"><?php echo $data['proyecto']['estado']; ?></span>
                                    <hr>
                                    <p class="mb-0"><strong>Asesor:</strong>
                                        <?php echo $data['proyecto']['asesor_nombre']; ?></p>
                                </div>
                            </div>

                            <!-- JURADOS -->
                            <div class="card mb-3">
                                <div class="card-header bg-light fw-bold">Jurado Calificador</div>
                                <div class="card-body p-0">
                                    <?php if (empty($data['jurados'])): ?>
                                        <p class="p-3 text-muted mb-0">Jurados aún no asignados.</p>
                                    <?php else: ?>
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Rol</th>
                                                    <th>Docente</th>
                                                    <th>Dictamen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['jurados'] as $j): ?>
                                                    <tr>
                                                        <td><span class="badge bg-secondary"><?php echo $j['rol_jurado']; ?></span>
                                                        </td>
                                                        <td><?php echo $j['nombres'] . ' ' . $j['apellidos']; ?></td>
                                                        <td>
                                                            <?php
                                                            $res = $j['ultimo_dictamen'] ?? 'Pendiente';
                                                            $cls = ($res == 'Aprobado') ? 'success' : (($res == 'Observado') ? 'danger' : 'secondary');
                                                            if (strpos($res, 'Aprobado') !== false)
                                                                $cls = 'success'; // Para 'Aprobado con distinción'
                                                            echo "<span class='badge bg-$cls'>$res</span>";
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">Archivos</div>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($data['docs_proy'] as $d)
                                        echo "<li class='list-group-item d-flex justify-content-between'><span>{$d['nombre_archivo_original']}</span><a href='" . URL_BASE . $d['ruta_archivo'] . "' target='_blank'>Descargar</a></li>"; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2">
                                <?php if ($data['accion_boton'] == 'proyecto_correccion'): ?>
                                    <button class="btn btn-primary" onclick="modalUpload('proyecto_correccion')">Subir
                                        Corrección (PDF + Word)</button>
                                <?php endif; ?>
                                <?php if ($data['accion_boton'] == 'grado' && $data['proyecto']['id_etapa_actual'] == 1): ?>
                                    <button class="btn btn-warning" onclick="modalUpload('grado')">Subir Requisitos</button>
                                <?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>tesista/ver_observaciones?fase=Proyecto"
                                    class="btn btn-danger">Ver Observaciones</a>
                                <?php if ($data['actas']['proyecto']): ?>
                                    <a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=proyecto"
                                        target="_blank" class="btn btn-dark">Acta Aprobación</a>
                                <?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>tesista/imprimir_ficha" target="_blank"
                                    class="btn btn-outline-secondary">Imprimir Ficha</a>
                                <a href="<?php echo URL_BASE; ?>reportes/expediente_imprimible?id=<?php echo $data['proyecto']['id']; ?>&fase=Proyecto"
                                    target="_blank" class="btn btn-outline-dark mt-2">Imprimir Expediente Completo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tp2">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-warning">Borrador</div>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($data['docs_borr'] as $d)
                                        echo "<li class='list-group-item d-flex justify-content-between'><span>{$d['nombre_archivo_original']}</span><a href='" . URL_BASE . $d['ruta_archivo'] . "' target='_blank'>Descargar</a></li>"; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2"><?php if ($data['accion_boton'] == 'borrador'): ?><button
                                        class="btn btn-success" onclick="modalUpload('borrador')">Subir
                                        Borrador</button><?php endif; ?><?php if ($data['accion_boton'] == 'borrador_correccion'): ?><button
                                        class="btn btn-warning" onclick="modalUpload('borrador_correccion')">Subir
                                        Corrección</button><?php endif; ?>

                                <?php if ($data['accion_boton'] == 'grado' && $data['proyecto']['id_etapa_actual'] == 2): ?>
                                    <button class="btn btn-info text-white" onclick="modalUpload('sustentacion')">Subir
                                        Requisitos Sustentación</button>
                                <?php endif; ?>

                                <?php if ($data['accion_boton'] == 'borrador_correccion' || $data['proyecto']['estado'] == 'En Revisión'): ?>
                                    <a href="<?php echo URL_BASE; ?>tesista/ver_observaciones?fase=Borrador"
                                        class="btn btn-danger">Ver Observaciones Borrador</a>
                                <?php endif; ?>

                                <?php if ($data['actas']['borrador']): ?><a
                                        href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=borrador"
                                        target="_blank" class="btn btn-dark">Acta Borrador</a><?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>reportes/expediente_imprimible?id=<?php echo $data['proyecto']['id']; ?>&fase=Borrador"
                                    target="_blank" class="btn btn-outline-secondary">Imprimir Expediente</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tp3">
                    <div class="card p-4 shadow-sm">
                        <h4 class="text-success"><i class="bi bi-calendar-check"></i> Programación de Sustentación</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong>
                                    <?php echo $data['proyecto']['fecha_sustentacion'] ?? 'Por definir'; ?></p>
                                <p><strong>Hora:</strong> <?php echo $data['proyecto']['hora_sustentacion'] ?? '--:--'; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Lugar:</strong> <?php echo $data['proyecto']['lugar_sustentacion'] ?? '--'; ?>
                                </p>
                                <p><strong>Enlace:</strong> <a
                                        href="<?php echo $data['proyecto']['url_sustentacion'] ?? '#'; ?>"
                                        target="_blank">Sala Virtual</a></p>
                            </div>
                        </div>
                        <hr>
                        <?php
                        if ($data['actas']['sustentacion']):
                            ?>
                            <div class="alert alert-success">¡Sustentación Finalizada!</div>
                            <a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=sustentacion"
                                target="_blank" class="btn btn-lg btn-success w-100">Descargar Acta de Sustentación</a>
                        <?php else: ?>
                            <div class="alert alert-info">En proceso de evaluación por jurados.</div><?php endif; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="tp4">
                    <div class="card">
                        <div class="card-header">Historial de Movimientos</div>
                        <ul class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                            <?php foreach ($data['historial'] as $h): ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted"><?php echo $h['fecha']; ?></small>
                                        <span class="badge bg-secondary"><?php echo $h['accion']; ?></span>
                                    </div>
                                    <p class="mb-1"><?php echo $h['detalle']; ?></p>
                                    <small class="text-primary"><?php echo $h['nombres']; ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div><?php endif; ?>
    </div>
    <div class="modal fade" id="mUpload">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Archivos</h5><button class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo URL_BASE; ?>tesista/subir_correccion" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id_proyecto" value="<?php echo $data['proyecto']['id'] ?? ''; ?>">
                        <input type="hidden" name="tipo_archivo" id="hTipo">

                        <div id="singleFile">
                            <label id="lblSingle">Archivo</label>
                            <input type="file" name="archivo_correccion" class="form-control" id="inputSingle">
                        </div>

                        <div id="multiFile" style="display:none">
                            <div class="mb-3">
                                <label>Versión PDF (Corregido)</label>
                                <input type="file" name="archivo_pdf" class="form-control" accept=".pdf">
                            </div>
                            <div class="mb-3">
                                <label>Versión Word (Corregido)</label>
                                <input type="file" name="archivo_word" class="form-control" accept=".doc,.docx">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success">Enviar</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const m = new bootstrap.Modal('#mUpload');
        function modalUpload(t) {
            const isCorrection = (t == 'proyecto_correccion' || t == 'borrador_correccion');
            const isBorrador = (t == 'borrador');
            const isGrado = (t == 'grado' || t == 'sustentacion'); const isMultiFile = isCorrection || isBorrador || isGrado;

            document.getElementById('hTipo').value = (t == 'borrador_correccion') ? 'Corrección Borrador' : ((t == 'borrador') ? 'Borrador Tesis' : ((t == 'grado') ? 'Requisitos de Grado' : ((t == 'sustentacion') ? 'Requisitos de Sustentacion' : 'Corrección Proyecto')));

            // Mostrar campos dobles si es corrección o borrador
            document.getElementById('singleFile').style.display = isMultiFile ? 'none' : 'block';
            document.getElementById('multiFile').style.display = isMultiFile ? 'block' : 'none';

            // Ajustar para requisitos
            if (t == 'grado') {
                document.getElementById('lblSingle').innerText = 'Archivo PDF (Requisitos)';
                document.getElementById('inputSingle').accept = '.pdf';
            } else {
                document.getElementById('lblSingle').innerText = 'Archivo';
                document.getElementById('inputSingle').removeAttribute('accept');
            }

            m.show();
        }
    </script>
</body>

</html>