<!DOCTYPE html>
<html lang="es">
<head><title>Acta Oficial</title>
<style>
    body { font-family: 'Times New Roman', serif; padding: 40px; line-height: 1.6; max-width: 900px; margin: 0 auto; }
    @media print { .no-print { display: none; } }
</style>
</head>
<body onload="window.print()">
    <button class="no-print" onclick="window.print()" style="position:fixed; top:10px; right:10px; padding:10px;">Imprimir</button>
    
    <?php echo $data['html']; ?>
    
</body>
</html>
