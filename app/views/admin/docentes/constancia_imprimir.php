<?php
// Function to format date in Spanish
function fechaEspanol($fecha) {
    if(!$fecha) return '';
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $timestamp = strtotime($fecha);
    $dia = date('d', $timestamp);
    $mes = $meses[date('n', $timestamp)-1];
    $anio = date('Y', $timestamp);
    return "$dia de $mes del $anio";
}

// Ensure valid verification URL
$verifUrl = $data['docente']['url_verificacion'] ?? '';
if (empty($verifUrl)) {
    $protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $verifUrl = $protocol . "://" . $host . "/sespecialidad/nadia/gemini/public/verificacion/constancia?id=" . ($data['docente']['id'] ?? 0) . "&cvd=" . ($data['docente']['cvd'] ?? $data['cvd'] ?? '');
}
$qrSrc = "https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=" . urlencode($verifUrl);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia Digital</title>
    <style>
        @page { size: A4; margin: 1cm; }
        body { font-family: 'Times New Roman', serif; line-height: 1.5; color: #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .univ { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .sub { font-size: 12pt; display: block; margin-top: 5px; }
        .title { text-align: center; font-size: 18pt; font-weight: bold; margin: 30px 0; text-decoration: underline; text-transform: uppercase; }
        .content { font-size: 12pt; text-align: justify; margin-bottom: 30px; }
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10pt; }
        .table-custom th, .table-custom td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        .table-custom th { background-color: #f0f0f0; }
        .footer-qr { border-top: 1px dashed #000; margin-top: 40px; padding-top: 15px; display: flex; align-items: center; font-family: Arial, sans-serif; font-size: 9pt; }
        .qr-img { margin-right: 15px; }
        .firma-box { text-align: right; margin-top: 60px; margin-bottom: 20px; }
        .no-print { position: fixed; top: 10px; right: 10px; background: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .tesista-info { display: block; font-size: 0.9em; margin-top: 8px; }
        .rol-label { font-weight: bold; color: #333; }
        .contacto-info { font-style: italic; color: #555; font-size: 0.9em; margin-left: 5px; }
        .item-persona { margin-bottom: 4px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-weight: bold;">Imprimir Constancia</button>
        <?php if (isset($data['cvd_verificado'])): ?>
            <div style="margin-top:10px; color:green; font-weight:bold;">✅ Documento Validado</div>
        <?php endif; ?>
    </div>
    <div class="header">
        <div class="univ">UNIVERSIDAD NACIONAL DEL ALTIPLANO</div>
        <span class="sub">
            <?php echo htmlspecialchars($data['docente']['nombre_facultad'] ?? 'FACULTAD DE CIENCIAS JURÍDICAS Y POLÍTICAS'); ?>
        </span>
         <span style="display: block; font-size: 1.1rem; color: #444; margin-top: 2px;">
            <?php echo htmlspecialchars($data["docente"]["nombre_programa"] ?? ""); ?>
        </span>
    </div>
    <div class="title">CONSTANCIA DE JURADO / ASESOR</div>
    <div class="content">
        <p>El que suscribe, certifica que el docente:</p>
        <p style="text-align: center; font-size: 14pt; font-weight: bold; margin: 20px 0;">
            <?php echo htmlspecialchars(($data['docente']['nombres'] ?? '') . ' ' . ($data['docente']['apellidos'] ?? '')); ?>
        </p>
        <p>Ha participado en los siguientes proyectos de investigación registrados en el sistema NADIA:</p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th width="50%">Título del Proyecto / Tesista(s)</th>
                    <th width="15%">Rol</th>
                    <th width="15%">Estado</th>
                    <th width="20%">Fecha Presentación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['proyectos'] as $p): ?>
                    <tr>
                        <td>
                            <div style="font-weight:bold; margin-bottom:5px;"><?php echo htmlspecialchars($p['titulo']); ?></div>
                            
                            <div class="tesista-info">
                                <div class="item-persona">
                                    <span class="rol-label">Tesista Principal:</span><br>
                                    <?php echo htmlspecialchars($p['tesista_nom'] ?? '') . ' ' . htmlspecialchars($p['tesista_ape'] ?? ''); ?>
                                    <?php if(!empty($p['tesista_email']) || !empty($p['tesista_cel'])): ?>
                                        <br><span class="contacto-info"><?php echo trim(($p['tesista_email']??'') . ' / ' . ($p['tesista_cel']??''), ' / '); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($p['cotesista_nom']) || !empty($p['cotesista_ape'])): ?>
                                    <div class="item-persona" style="margin-top:5px;">
                                        <span class="rol-label">Co-Tesista:</span><br>
                                        <?php echo htmlspecialchars($p['cotesista_nom'] ?? '') . ' ' . htmlspecialchars($p['cotesista_ape'] ?? ''); ?>
                                        <?php if(!empty($p['cotesista_email']) || !empty($p['cotesista_cel'])): ?>
                                            <br><span class="contacto-info"><?php echo trim(($p['cotesista_email']??'') . ' / ' . ($p['cotesista_cel']??''), ' / '); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($p['rol_jurado']); ?></td>
                        <td><?php echo htmlspecialchars($p['estado']); ?></td>
                        <td><?php echo fechaEspanol($p['fecha_presentacion'] ?? $p['created_at'] ?? null); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['proyectos'])): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No se encontraron registros recientes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p style="margin-top: 20px;">Se expide la presente a solicitud del interesado para los fines que estime conveniente.</p>
        <p style="text-align: right; margin-top: 30px;">Puno, <?php echo fechaEspanol(date('Y-m-d')); ?></p>
    </div>

    <div class="firma-box">
        <p>______________________________________</p>
        <p><strong>Firma y Sello Autorizado</strong></p>
    </div>

    <div class="footer-qr">
        <div class="qr-img">
            <img src="<?php echo $qrSrc; ?>" alt="QR" width="90" height="90">
        </div>
        <div>
            <strong>DOCUMENTO CON VALIDEZ ELECTRÓNICA</strong><br>
            CVD: <b><?php echo $data['docente']['cvd'] ?? $data['cvd'] ?? '---'; ?></b><br>
            Verificación: <a href="<?php echo $verifUrl; ?>" style="color: black; text-decoration: none;"><?php echo $verifUrl; ?></a><br>
            <small>Generado automáticamente el <?php echo date('d/m/Y H:i:s'); ?></small>
        </div>
    </div>
</body>
</html>