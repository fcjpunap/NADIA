<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Proyecto - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; font-size: 11pt; }
            .ficha-container { border: none !important; box-shadow: none !important; width: 100% !important; max-width: none !important; padding: 0 !important; }
        }
        .ficha-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border: 1px solid #ddd;
        }
        .header-logo {
            text-align: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .section-title {
            background: #f8f9fa;
            color: #003366;
            padding: 8px 15px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: bold;
            border-left: 5px solid #003366;
            text-transform: uppercase;
        }
        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #f2f2f2;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            flex: 0 0 220px;
        }
        .info-value {
            color: #000;
            flex: 1;
        }
        table.info-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        table.info-table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        table.info-table td.label-cell {
            background: #fdfdfd;
            font-weight: bold;
            width: 30%;
            color: #555;
        }
        .firma-box {
            margin-top: 80px;
            text-align: center;
        }
        .firma-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
            padding-top: 10px;
            font-weight: bold;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-light py-4">
    <div class="ficha-container shadow-sm bg-white">
        
        <div class="no-print d-flex justify-content-between mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button onclick="window.print()" class="btn btn-primary px-4">
                <i class="bi bi-printer"></i> Imprimir Ficha Oficial
            </button>
        </div>
        <?php if (!empty($data['proyecto'])): 
            $p = $data['proyecto']; 
        ?>
        <div class="header-logo">
            <h4 class="mb-1 fw-bold"><?php echo htmlspecialchars($data['institucion'] ?? 'UNIVERSIDAD NACIONAL DEL ALTIPLANO'); ?></h4>
            <h5 class="mb-0 text-uppercase text-secondary">
                <?php echo htmlspecialchars($p['facultad_nombre'] ?? 'FACULTAD DE CIENCIAS JURÍDICAS Y POLÍTICAS'); ?>
            </h5>
            <div class="mt-4">
                <span class="badge bg-dark px-4 py-2 fs-6">FICHA DEL PROYECTO DE INVESTIGACIÓN</span>
            </div>
        </div>
        <!-- SECCIÓN I: DATOS DEL PROYECTO -->
        <div class="section-title">I. DATOS DEL PROYECTO</div>
        <div class="info-row">
            <span class="info-label">Título del Proyecto:</span>
            <span class="info-value text-uppercase fw-bold"><?php echo htmlspecialchars($p['titulo'] ?? '---'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Línea de Investigación:</span>
            <span class="info-value">
                <?php echo htmlspecialchars($p['linea_nombre'] ?? '---'); ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Sublínea:</span>
            <span class="info-value">
                <?php echo htmlspecialchars($p['sublinea_nombre'] ?? '---'); ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Programa / Nivel:</span>
            <span class="info-value">
                <?php echo htmlspecialchars($p['programa_nombre'] ?? '---'); ?> 
                <span class="text-muted">(<?php echo htmlspecialchars($p['nivel_academico'] ?? '---'); ?>)</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado del Trámite:</span>
            <span class="info-value fw-bold text-primary"><?php echo $p['estado'] ?? '---'; ?></span>
        </div>
        <!-- SECCIÓN II: TESISTAS -->
        <div class="section-title">II. INFORMACIÓN DE LOS TESISTAS</div>
        
        <p class="mt-2 mb-1 fw-bold text-primary"><i class="bi bi-person-check-fill"></i> Tesista Principal:</p>
        <table class="info-table mb-4">
            <tr>
                <td class="label-cell">Nombres y Apellidos</td>
                <td class="text-uppercase"><?php echo htmlspecialchars(($p['t1_nombres'] ?? '') . ' ' . ($p['t1_apellidos'] ?? '---')); ?></td>
            </tr>
            <tr>
                <td class="label-cell">DNI / Código</td>
                <td><?php echo htmlspecialchars(($p['t1_dni'] ?? '---') . ' / ' . ($p['t1_codigo'] ?? '---')); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Email / Celular</td>
                <td><?php echo htmlspecialchars(($p['t1_email'] ?? '---') . ' / ' . ($p['t1_telefono'] ?? '---')); ?></td>
            </tr>
        </table>
        <?php if (!empty($p['id_tesista_2']) && !empty($p['t2_nombres'])): ?>
        <p class="mt-3 mb-1 fw-bold text-primary"><i class="bi bi-people-fill"></i> Co-Tesista:</p>
        <table class="info-table mb-4">
            <tr>
                <td class="label-cell">Nombres y Apellidos</td>
                <td class="text-uppercase"><?php echo htmlspecialchars(($p['t2_nombres'] ?? '') . ' ' . ($p['t2_apellidos'] ?? '---')); ?></td>
            </tr>
            <tr>
                <td class="label-cell">DNI / Código</td>
                <td><?php echo htmlspecialchars(($p['t2_dni'] ?? '---') . ' / ' . ($p['t2_codigo'] ?? '---')); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Email / Celular</td>
                <td><?php echo htmlspecialchars(($p['t2_email'] ?? '---') . ' / ' . ($p['t2_telefono'] ?? '---')); ?></td>
            </tr>
        </table>
        <?php endif; ?>
        <!-- SECCIÓN III: ASESOR -->
        <div class="section-title">III. ASESOR DEL PROYECTO</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Nombres y Apellidos</td>
                <td class="text-uppercase"><?php echo htmlspecialchars(($p['asesor_nombres'] ?? '') . ' ' . ($p['asesor_apellidos'] ?? '---')); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Grado Académico</td>
                <td><?php echo htmlspecialchars($p['asesor_grado'] ?? '---'); ?></td>
            </tr>
        </table>
        <!-- SECCIÓN IV: JURADO EVALUADOR (Ordenado: Presidente primero) -->
        <?php if (!empty($data['jurados'])): ?>
        <div class="section-title">IV. JURADO EVALUADOR</div>
        <table class="info-table">
            <?php foreach($data['jurados'] as $j): ?>
            <tr>
                <td class="label-cell"><?php echo htmlspecialchars($j['rol_jurado']); ?></td>
                <td class="text-uppercase fw-bold"><?php echo htmlspecialchars($j['nombre_completo']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
        <!-- SECCIÓN DE FIRMAS -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-6 firma-box">
                    <div class="firma-line">FIRMA DEL TESISTA</div>
                    <div class="small text-muted"><?php echo htmlspecialchars(($p['t1_nombres'] ?? '') . ' ' . ($p['t1_apellidos'] ?? '')); ?></div>
                </div>
                <div class="col-6 firma-box">
                    <div class="firma-line">FIRMA DEL ASESOR</div>
                    <div class="small text-muted"><?php echo htmlspecialchars(($p['asesor_nombres'] ?? '') . ' ' . ($p['asesor_apellidos'] ?? '')); ?></div>
                </div>
            </div>
            
            <?php if (!empty($p['id_tesista_2'])): ?>
            <div class="row mt-4">
                <div class="col-3"></div>
                <div class="col-6 firma-box">
                    <div class="firma-line">FIRMA DEL CO-TESISTA</div>
                    <div class="small text-muted"><?php echo htmlspecialchars(($p['t2_nombres'] ?? '') . ' ' . ($p['t2_apellidos'] ?? '')); ?></div>
                </div>
                <div class="col-3"></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="mt-5 pt-4 text-center text-muted small no-print">
            <hr>
            Ficha Generada por el Sistema NADIA el <?php echo date('d/m/Y \a \l\a\s H:i'); ?>
        </div>
        <?php else: ?>
        <div class="alert alert-danger text-center p-5">
            <i class="bi bi-exclamation-octagon-fill fs-1 d-block mb-3"></i>
            <h4>Información no disponible</h4>
            <p>No se pudo recuperar la ficha del proyecto solicitado.</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
