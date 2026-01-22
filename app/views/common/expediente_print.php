<!DOCTYPE html>
<html lang="es">
<head>
    <title>Expediente Completo - NADIA</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; background: #f4f7f6; color: #333; }
        .print-container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 8px; }
        .header-print { text-align: center; border-bottom: 3px solid #003366; padding-bottom: 20px; margin-bottom: 30px; }
        .header-print h1 { font-size: 1.5rem; color: #003366; margin: 0; }
        .header-print h2 { font-size: 1.2rem; color: #666; margin-top: 5px; }
        .info-section { margin-bottom: 30px; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        .page-block { page-break-after: always; padding-bottom: 30px; margin-bottom: 30px; border-bottom: 1px solid #eee; }
        .pdf-canvas { border: 1px solid #ccc; width: 100%; height: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .obs-box { background: #fffde7; border-left: 5px solid #fbc02d; padding: 15px; margin-top: 10px; position: relative; border-radius: 0 4px 4px 0; }
        .badge-ver { position: absolute; top: 10px; right: 10px; font-size: 0.7em; padding: 3px 8px; border-radius: 10px; color: white; font-weight: bold; }
        .bg-old { background: #9e9e9e; } .bg-new { background: #03a9f4; }
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        .btn-print { padding: 12px 24px; background: #003366; color: white; cursor: pointer; border: none; font-size: 16px; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        @media print { body { background: white; padding: 0; } .print-container { box-shadow: none; margin: 0; width: 100%; max-width: 100%; padding: 0; } .no-print { display: none; } .page-block { page-break-after: always; } }
    </style>
</head>
<body>
    <div class="no-print"><button class="btn-print" onclick="window.print()"><i class="bi bi-printer"></i> 🖨️ Imprimir Expediente</button></div>
    <div class="print-container">
        <div class="header-print">
            <h1>UNIVERSIDAD NACIONAL DEL ALTIPLANO</h1>
            <h2>Expediente de Revisión de Tesis (<?php echo $data['fase']; ?>)</h2>
        </div>
        
        <div class="info-section">
            <p><strong>Código:</strong> <span style="font-family: monospace;"><?php echo $data['proyecto']['uuid']; ?></span></p>
            <p><strong>Título:</strong> <?php echo $data['proyecto']['titulo']; ?></p>
            <p><strong>Tesista:</strong> <?php echo $data['proyecto']['t_nom'] . ' ' . $data['proyecto']['t_ape']; ?></p>
        </div>

        <div id="render-area">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Procesando y renderizando páginas del PDF...</p>
            </div>
        </div>
    </div>

    <script>
        const pdfUrl = '<?php echo ($data['documento'] ? URL_BASE . $data['documento']['ruta_archivo'] : ''); ?>';
        const obsData = <?php echo json_encode($data['paginas'] ?? []); ?>;
        const container = document.getElementById('render-area');
        
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        async function renderAll() {
            try {
                console.log('Solicitando PDF:', pdfUrl);
                if (pdfUrl.endsWith('/') || pdfUrl.endsWith('public/')) { throw new Error('No se encontró ningún archivo PDF asociado a este proyecto.'); }
                
                // Opción withCredentials para asegurar que se pase la sesión si es necesario
                const loadingTask = pdfjsLib.getDocument({
                    url: pdfUrl,
                    withCredentials: true
                });

                const pdf = await loadingTask.promise;
                container.innerHTML = '';

                // Observaciones Generales (Página 0)
                const generalObs = obsData[0] || [];
                if (generalObs.length > 0) {
                    const block = document.createElement('div');
                    block.className = 'page-block';
                    block.innerHTML = `<h3 style="color:#003366;">📋 Observaciones Generales</h3>`;
                    generalObs.forEach(o => {
                        const box = document.createElement('div');
                        box.className = 'obs-box';
                        box.style.background = '#e1f5fe';
                        box.style.borderLeftColor = '#0288d1';
                        const badge = o.es_antiguo ? '<span class="badge-ver bg-old">Versión Anterior</span>' : '<span class="badge-ver bg-new">Versión Actual</span>';
                        box.innerHTML = `${badge}<strong>${o.nombres} (${o.rol_autor}):</strong><br>${o.observacion_texto}`;
                        block.appendChild(box);
                    });
                    container.appendChild(block);
                }

                for (let i = 1; i <= pdf.numPages; i++) {
                    const block = document.createElement('div');
                    block.className = 'page-block';
                    block.innerHTML = `<h4 style="color:#666;">Página ${i}</h4>`;
                    
                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-canvas';
                    block.appendChild(canvas);

                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({ scale: 1.5 });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    await page.render({ 
                        canvasContext: canvas.getContext('2d'), 
                        viewport: viewport 
                    }).promise;

                    if (obsData[i]) {
                        const obsContainer = document.createElement('div');
                        obsContainer.innerHTML = '<h5 class="mt-3">Observaciones en esta página:</h5>';
                        obsData[i].forEach(o => {
                            const box = document.createElement('div');
                            box.className = 'obs-box';
                            const badge = o.es_antiguo ? '<span class="badge-ver bg-old">Versión Anterior</span>' : '<span class="badge-ver bg-new">Versión Actual</span>';
                            box.innerHTML = `${badge}<strong>${o.nombres} (${o.rol_autor}):</strong><br>${o.observacion_texto}`;
                            obsContainer.appendChild(box);
                        });
                        block.appendChild(obsContainer);
                    }
                    container.appendChild(block);
                }
            } catch (e) {
                console.error('Error detallado:', e);
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <h4>Error al cargar el PDF</h4>
                        <p>${e.message}</p>
                        <p class="small">Esto suele suceder si la sesión expiró o si el archivo está siendo bloqueado. 
                        Intente abrir el archivo directamente <a href="${pdfUrl}" target="_blank">aquí</a> para verificar acceso.</p>
                    </div>`;
            }
        }
        renderAll();
    </script>
</body>
</html>
