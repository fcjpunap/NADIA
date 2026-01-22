<?php
// Asegurar URL válida antes de renderizar
$verifUrl = $data['url_verificacion'] ?? '';
if(empty($verifUrl) || $verifUrl == '---') {
    $protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // CHANGE: Point to verificacion/acta instead of generic public root
    $verifUrl = $protocol . "://" . $host . "/sespecialidad/nadia/gemini/public/verificacion/acta?id=" . ($data['proyecto']['id'] ?? 0) . "&tipo=" . ($data['tipo'] ?? 'Acta') . "&cvd=" . ($data['cvd'] ?? '');
}
$qrSrc = "https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=" . urlencode($verifUrl);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Acta Digital - NADIA</title>
    <style>
        @page { size: A4; margin: 0.5cm 2cm; }
        body { font-family: "Times New Roman", Times, serif; color: #000; line-height: 1.6; background: white; margin: 20px; }
        .header { text-align: center; border-bottom: none; padding-bottom: 0px; margin-bottom: 5px; }
        .univ { font-size: 1.4rem; font-weight: bold; text-transform: uppercase; display: block; }
        .fac { font-size: 1.1rem; font-weight: normal; display: block; margin-top: 5px; }
        .main-content { min-height: 0; text-align: justify; padding: 0; }
        .verification-footer { margin-top: 5px; padding: 15px; border: 1px dashed #444; display: flex; align-items: center; font-size: 0.8rem; font-family: Arial, sans-serif; background: #fafafa; }
        .qr-code { margin-right: 15px; }
        .cvd-text { flex-grow: 1; }
        .cvd-code { font-family: monospace; font-weight: bold; font-size: 0.9rem; background: #eee; padding: 2px 4px; }
        .no-print { position: fixed; top: 10px; right: 10px; z-index: 1000; }
        .btn { padding: 8px 15px; cursor: pointer; border-radius: 4px; border: 1px solid #ccc; background: #f8f9fa; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Imprimir</button>
        <button onclick="window.history.back()" class="btn">Regresar</button>
    </div>
    <div class="header">
        <span class="univ"><?php echo defined('UNIVERSITY_NAME') ? UNIVERSITY_NAME : (isset($data['institucion'])?$data['institucion']:'UNIVERSIDAD NACIONAL DEL ALTIPLANO'); ?></span>
        <span class="fac"><?php echo isset($data['proyecto']['fac_nombre']) ? htmlspecialchars($data['proyecto']['fac_nombre']) : (isset($data['proyecto']['facultad']) ? htmlspecialchars($data['proyecto']['facultad']) : 'FACULTAD'); ?></span>
    </div>
    <div class="main-content">
        <?php echo isset($data['contenido']) ? $data['contenido'] : (isset($data['html']) ? $data['html'] : '<p>Error: No hay contenido para mostrar.</p>'); ?>
    </div>
    <div class="verification-footer">
        <div class="qr-code">
            <img src="<?php echo $qrSrc; ?>" alt="QR" width="90" height="90">
        </div>
        <div class="cvd-text">
            <strong>ACTA DE VERIFICACIÓN ELECTRÓNICA</strong><br>
            CVD: <span class="cvd-code"><?php echo $data['cvd'] ?? '---'; ?></span><br>
            Verifique en: <?php echo $verifUrl; ?>
        </div>
    </div>
</body>
</html>
