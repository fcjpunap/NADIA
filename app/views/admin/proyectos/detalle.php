<!DOCTYPE html>
<html lang="es">
<head>
    <title>Detalle Expediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between mb-4">
            <h3>Expediente: <?php echo substr($data['proyecto']['uuid'],0,8); ?></h3>
            <a href="<?php echo URL_BASE; ?>admin/proyectos" class="btn btn-secondary">Volver</a>
        </div>
        
        <ul class="nav nav-tabs mb-3" id="exTab">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t1">1. Proyecto</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t2">2. Borrador</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t3">3. Sustentación</button></li>
        </ul>
        
        <div class="tab-content">
            <!-- Pestaña 1: Proyecto -->
            <div class="tab-pane fade show active" id="t1">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h5><?php echo $data['proyecto']['titulo']; ?></h5>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <strong>Tesista:</strong><br><?php echo $data['proyecto']['t_completo']; ?>
                                    </div>
                                    <div class="col">
                                        <strong>Asesor:</strong><br><?php echo $data['proyecto']['a_completo']; ?>
                                    </div>
                                </div>
                                <!-- FIX V71: Línea y Sublínea -->
                                <div class="mt-2 p-2 bg-light rounded small">
                                    <strong>Línea:</strong> <?php echo $data['proyecto']['nombre_linea'] ?? '--'; ?> <br>
                                    <strong>Sublínea:</strong> <?php echo $data['proyecto']['nombre_sublinea'] ?? '--'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Archivos Proyecto</div>
                            <ul class="list-group list-group-flush">
                                <?php foreach($data['documentos'] as $d) if(strpos($d['tipo_documento'],'Proyecto')!==false) echo "<li class='list-group-item d-flex justify-content-between'><span>{$d['tipo_documento']}</span><a href='".URL_BASE.$d['ruta_archivo']."' target='_blank'>Descargar</a></li>"; ?>
                            </ul>
                        </div>
                        <div class="card">
                            <div class="card-header">Historial</div>
                            <ul class="list-group list-group-flush" style="max-height:150px;overflow-y:auto">
                                <?php foreach($data['historial'] as $h) echo "<li class='list-group-item small'>{$h['fecha']} - {$h['accion']}: {$h['detalle']}</li>"; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-center"><?php echo $data['proyecto']['estado']; ?></div>
                            <div class="card-body text-center">
                                <?php if($data['acta_proy']): ?><a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=proyecto" target="_blank" class="btn btn-success w-100 mb-2">Acta Proyecto</a><?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>tesista/imprimir_ficha?id_admin=<?php echo $data['proyecto']['id']; ?>" target="_blank" class="btn btn-outline-secondary w-100 mb-2">Imprimir Ficha</a>
                                <a href="<?php echo URL_BASE; ?>reportes/expediente_imprimible?id=<?php echo $data['proyecto']['id']; ?>&fase=Proyecto" target="_blank" class="btn btn-dark w-100">Imprimir Todo (P)</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">Jurado</div>
                            <ul class="list-group list-group-flush">
                                <?php foreach($data['jurados'] as $j): ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div><small class="fw-bold text-primary"><?php echo $j['rol_jurado']; ?></small><br><?php echo $j['nombre_completo']; ?></div>
                                        <button class="btn btn-sm btn-outline-danger" onclick="abrirCambio(<?php echo $j['id_jurado']; ?>)"><i class="bi bi-arrow-repeat"></i></button>
                                    </div>
                                    <small class="text-muted d-block">Voto: <?php echo $j['resultado']??'Pendiente'; ?></small>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pestaña 2: Borrador -->
            <div class="tab-pane fade" id="t2">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">Archivos Borrador</div>
                            <ul class="list-group list-group-flush">
                                <?php foreach($data['documentos'] as $d) if(strpos($d['tipo_documento'],'Borrador')!==false || strpos($d['tipo_documento'],'Requisitos')!==false) echo "<li class='list-group-item d-flex justify-content-between'><span>{$d['tipo_documento']}</span><a href='".URL_BASE.$d['ruta_archivo']."' target='_blank'>Descargar</a></li>"; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                
                                <?php if(!$data['proyecto']['autorizado_borrador']): ?>
                                    <a href="<?php echo URL_BASE; ?>admin/autorizar_borrador?id=<?php echo $data['proyecto']['id']; ?>" 
                                       class="btn btn-warning w-100 mb-2" 
                                       onclick="return confirm('¿Autorizar al tesista a subir su borrador de tesis?')">
                                       <i class="bi bi-check-circle"></i> Autorizar Subida Borrador
                                    </a>
                                <?php else: ?>
                                    <div class="alert alert-success small p-2 mb-2">Subida de Borrador Autorizada</div>
                                <?php endif; ?>
                                <?php if($data['acta_borr']): ?><a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=borrador" target="_blank" class="btn btn-success w-100 mb-2">Acta Borrador</a><?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>reportes/expediente_imprimible?id=<?php echo $data['proyecto']['id']; ?>&fase=Borrador" target="_blank" class="btn btn-outline-dark w-100 mb-2">Imprimir Todo (B)</a>
                                <?php if($data['puede_programar']): ?>
                                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#mProg">Programar Sustentación</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pestaña 3: Sustentación -->
            <div class="tab-pane fade" id="t3">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-success text-white">Programación</div>
                            <div class="card-body">
                                <p><strong>Fecha:</strong> <?php echo $data['proyecto']['fecha_sustentacion']; ?> <strong>Hora:</strong> <?php echo $data['proyecto']['hora_sustentacion']; ?></p>
                                <p><strong>Lugar:</strong> <?php echo $data['proyecto']['lugar_sustentacion']; ?></p>
                                <!-- FIX V71: Mostrar URL -->
                                <p><strong>URL:</strong> <a href="<?php echo $data['proyecto']['url_sustentacion']; ?>" target="_blank"><?php echo $data['proyecto']['url_sustentacion']; ?></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <?php if($data['acta_sust']): ?>
                                    <a href="<?php echo URL_BASE; ?>reportes/acta_imprimible?id=<?php echo $data['proyecto']['id']; ?>&tipo=sustentacion" target="_blank" class="btn btn-success w-100">Acta Sustentación</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambio Jurado -->
    <div class="modal fade" id="modalCambio">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reemplazar Jurado</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo URL_BASE; ?>admin/cambiar_jurado" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_proyecto" value="<?php echo $data['proyecto']['id']; ?>">
                        <input type="hidden" name="id_jurado_antiguo" id="inputOldJurado">
                        <label>Nuevo Jurado:</label>
                        <select name="id_jurado_nuevo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($data['all_docentes'] as $d) echo "<option value='{$d['id']}'>{$d['apellidos']} {$d['nombres']}</option>"; ?>
                        </select>
                    </div>
                    <div class="modal-footer"><button class="btn btn-danger">Confirmar</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Programar Sustentación -->
    <div class="modal fade" id="mProg">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Programar</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo URL_BASE; ?>admin/programar_sustentacion" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_proyecto" value="<?php echo $data['proyecto']['id']; ?>">
                        <!-- FIX V71: Pre-llenar valores -->
                        <div class="mb-2">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="<?php echo $data['proyecto']['fecha_sustentacion']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>Hora</label>
                            <input type="time" name="hora" class="form-control" value="<?php echo $data['proyecto']['hora_sustentacion']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>Lugar</label>
                            <input type="text" name="lugar" class="form-control" value="<?php echo $data['proyecto']['lugar_sustentacion']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>URL</label>
                            <input type="text" name="url" class="form-control" value="<?php echo $data['proyecto']['url_sustentacion']; ?>">
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-success">Guardar</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const mc = new bootstrap.Modal('#modalCambio');
        function abrirCambio(id){
            document.getElementById('inputOldJurado').value = id;
            mc.show();
        }
    </script>
</body>
</html>
