<!DOCTYPE html>
<html lang="es">
<head>
    <title>Casilla Electrónica - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h3><i class="bi bi-envelope-paper-fill text-primary"></i> Casilla Electrónica</h3>
        <a href="<?php echo URL_BASE; if($_SESSION['rol']==1)echo'tesista';elseif($_SESSION['rol']==2)echo'docente';else echo'admin'; ?>/dashboard" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="d-grid gap-2 mb-3">
                <a href="<?php echo URL_BASE; ?>mensajes/crear" class="btn btn-primary shadow-sm">
                    <i class="bi bi-pencil-square"></i> Redactar Nuevo
                </a>
            </div>
            <div class="list-group shadow-sm">
                <a href="?c=Entrada" class="list-group-item list-group-item-action <?php echo ($data['carpeta']=='Entrada')?'active':''; ?>">
                    <i class="bi bi-inbox-fill me-2"></i> Bandeja de Entrada
                </a>
                <a href="?c=Enviados" class="list-group-item list-group-item-action <?php echo ($data['carpeta']=='Enviados')?'active':''; ?>">
                    <i class="bi bi-send-fill me-2"></i> Enviados
                </a>
                <a href="?c=Papelera" class="list-group-item list-group-item-action <?php echo ($data['carpeta']=='Papelera')?'active':''; ?>">
                    <i class="bi bi-trash-fill me-2"></i> Papelera
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold border-bottom">
                    <?php 
                    if($data['carpeta'] == 'Entrada') echo 'Mensajes Recibidos';
                    elseif($data['carpeta'] == 'Enviados') echo 'Mensajes Enviados';
                    else echo 'Papelera (Auto-elimina después de 30 días)';
                    ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo ($data['carpeta']=='Enviados') ? 'Para' : 'De'; ?></th>
                                    <th>Asunto</th>
                                    <th class="text-end">Fecha</th>
                                    <?php if($data['carpeta']=='Papelera'): ?><th class="text-center">Acción</th><?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['mensajes'])): ?>
                                    <tr>
                                        <td colspan="<?php echo ($data['carpeta']=='Papelera')?'4':'3'; ?>" class="text-center p-5 text-muted">
                                            <i class="bi bi-envelope-open fs-1 d-block mb-3"></i>
                                            No hay mensajes en esta bandeja.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($data['mensajes'] as $m): ?>
                                    <tr class="<?php echo (isset($m['leido']) && !$m['leido'] && $data['carpeta']!='Enviados') ? 'fw-bold' : 'text-muted'; ?>">
                                        <td style="cursor:pointer" onclick="window.location='<?php echo URL_BASE; ?>mensajes/detalle?id=<?php echo $m['id']; ?>'">
                                            <?php 
                                            if($data['carpeta'] == 'Enviados') {
                                                $dests = $m['lista_destinatarios'] ?? 'Sin destinatarios';
                                                echo (strlen($dests) > 40) ? substr($dests, 0, 40) . '...' : $dests;
                                            } else {
                                                echo isset($m['rem_n']) ? $m['rem_n'].' '.$m['rem_a'] : 'Sistema'; 
                                            }
                                            ?>
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='<?php echo URL_BASE; ?>mensajes/detalle?id=<?php echo $m['id']; ?>'">
                                            <?php echo htmlspecialchars($m['asunto']); ?> 
                                            <?php if($m['adjuntos']>0) echo '<i class="bi bi-paperclip text-secondary ms-1"></i>'; ?>
                                        </td>
                                        <td class="text-end small" style="cursor:pointer" onclick="window.location='<?php echo URL_BASE; ?>mensajes/detalle?id=<?php echo $m['id']; ?>'">
                                            <?php echo date('d/M H:i', strtotime($m['fecha_envio'])); ?>
                                        </td>
                                        <?php if($data['carpeta']=='Papelera'): ?>
                                            <td class="text-center">
                                                <a href="<?php echo URL_BASE; ?>mensajes/restaurar?id=<?php echo $m['id']; ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Restaurar a Bandeja de Entrada">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                                </a>
                                            </td>
                                        <?php endif; ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
