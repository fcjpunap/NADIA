<!DOCTYPE html><html lang="es"><head><title>Leer Mensaje</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"></head><body class="bg-light">
<div class="container mt-4" style="max-width:800px">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0">
            <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($data['m']['asunto']); ?></h5>
            <a href="<?php echo URL_BASE; ?>mensajes/index" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        <div class="card-body pt-0">
            <div class="bg-light p-3 rounded mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <strong>De:</strong> <?php echo htmlspecialchars($data['m']['nombres'].' '.$data['m']['apellidos']); ?> <span class="text-muted"><small>&lt;<?php echo $data['m']['email']; ?>&gt;</small></span><br>
                        <strong>Para:</strong> 
                        <?php 
                        $nombres = array_map(function($d) { return htmlspecialchars($d['nombres'].' '.$d['apellidos']); }, $data['destinatarios']);
                        echo implode(', ', $nombres);
                        ?>
                    </div>
                    <div class="col-md-4 text-end text-muted small">
                        <?php echo date('d M Y, H:i', strtotime($data['m']['fecha_envio'])); ?>
                    </div>
                </div>
            </div>
            
            <div class="message-body mb-4 p-2" style="white-space: pre-wrap; font-family: sans-serif;"><?php echo nl2br(htmlspecialchars($data['m']['cuerpo'])); ?></div>
            
            <?php if(!empty($data['adjuntos'])): ?>
                <h6 class="border-top pt-3 text-primary"><i class="bi bi-paperclip"></i> Archivos Adjuntos (Seguros)</h6>
                <div class="list-group">
                    <?php foreach($data['adjuntos'] as $a): ?>
                        <a href="<?php echo URL_BASE; ?>mensajes/descargar?id=<?php echo $a['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-file-earmark-text me-2"></i> <?php echo htmlspecialchars($a['nombre_original']); ?></span>
                            <span class="badge bg-secondary rounded-pill">Descargar</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <small class="text-muted mt-1">* Los archivos se descargan de forma segura verificando sus credenciales.</small>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end bg-white border-top-0">
            <?php if(!$data['soy_remitente']): ?>
                <a href="<?php echo URL_BASE; ?>mensajes/crear?reply=<?php echo $data['m']['id']; ?>" class="btn btn-primary"><i class="bi bi-reply"></i> Responder</a>
            <?php endif; ?>
            <a href="<?php echo URL_BASE; ?>mensajes/eliminar?id=<?php echo $data['m']['id']; ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</a>
        </div>
    </div>
</div></body></html>
