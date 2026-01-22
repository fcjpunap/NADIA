<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Plantilla | NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- CKEditor 4 FULL (Súper estable, incluye código HTML y alineación completa) -->
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <style>
        :root { --primary-purple: #4e44e7; --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        body { background: var(--bg-gradient); min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .editor-container { background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 35px; margin-top: 40px; border: 1px solid rgba(255,255,255,0.3); }
        .variable-badge { cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); margin-bottom: 10px; display: inline-block; padding: 8px 16px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; background: #fdfdff; color: #4e44e7; border: 1px solid #e0e7ff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .variable-badge:hover { background: #4e44e7; color: white; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(78, 68, 231, 0.25); border-color: #4e44e7; }
        .header-title { border-bottom: 2px solid #f1f5f9; margin-bottom: 30px; padding-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
        .btn-save { background: var(--primary-purple); border: none; padding: 14px 45px; border-radius: 12px; font-weight: 700; font-size: 1.1rem; color: white; transition: all 0.3s ease; }
        .btn-save:hover { background: #3f36c5; transform: translateY(-2px); box-shadow: 0 10px 25px rgba(78, 68, 231, 0.35); }
        .asunto-input { border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 15px; transition: all 0.3s; }
        .asunto-input:focus { border-color: var(--primary-purple); box-shadow: 0 0 0 4px rgba(78, 68, 231, 0.1); }
        .alert-custom { background: #f8fbff; border: 1px solid #e0e7ff; border-radius: 15px; }
    </style>
</head>
<body>
    <div class="container pb-5">
        <div class="editor-container">
            <div class="header-title">
                <h3 class="m-0 d-flex align-items-center"><i class="bi bi-magic text-primary me-3 fs-2"></i> <span>Diseñador de Plantillas: <small class="text-muted fw-normal"><?php echo htmlspecialchars($data['plantilla']['nombre']); ?></small></span></h3>
                <a href="<?php echo URL_BASE; ?>admin/plantillas" class="btn btn-light rounded-pill px-4 border shadow-sm"><i class="bi bi-chevron-left me-1"></i> Regresar</a>
            </div>
            <form action="<?php echo URL_BASE; ?>admin/guardar_plantilla" method="POST" id="formPlantilla">
                <input type="hidden" name="id" value="<?php echo $data['plantilla']['id']; ?>">
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-envelope-paper me-2"></i> ASUNTO DEL DOCUMENTO / EMAIL</label>
                    <input type="text" name="asunto" class="form-control asunto-input form-control-lg" value="<?php echo htmlspecialchars($data['plantilla']['asunto']); ?>" placeholder="Escriba el asunto aquí...">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark"><i class="bi bi-layout-text-window-reverse me-2"></i> CUERPO DEL DOCUMENTO (HTML)</label>
                    <textarea id="editor_wysiwyg" name="contenido"><?php echo $data['plantilla']['contenido']; ?></textarea>
                </div>
                <div class="alert-custom p-4 shadow-sm">
                    <h6 class="fw-bold mb-3 text-primary d-flex align-items-center">
                        <i class="bi bi-database-fill-add me-2"></i> 
                        Variables Dinámicas (Insertar con un Clic)
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php 
                        $vars = ['[tesista]', '[cotesista]', '[titulo]', '[codigo]', '[programa]', '[facultad]', '[fecha]', '[asesor]', '[coordinador]', '[presidente]', '[primer_miembro]', '[segundo_miembro]', '[resultado]', '[fecha_sustentacion]', '[hora_sustentacion]', '[lugar_sustentacion]', '[institucion]'];
                        foreach($vars as $v): ?>
                            <span class="variable-badge" onclick="insertarEnEditor('<?php echo $v; ?>')"><?php echo $v; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-save shadow">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Guardar Plantilla de NADIA
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Configuración de CKEditor 4 FULL
        CKEDITOR.replace('editor_wysiwyg', {
            height: 480,
            language: 'es',
            // Añadir todas las funciones: Alineación, Código Fuentes, Tablas, etc.
            toolbar: [
                { name: 'document', items: [ 'Source', '-', 'Preview', 'Print' ] },
                { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                '/',
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak' ] },
                '/',
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximalize', 'ShowBlocks' ] }
            ],
            removePlugins: 'easyimage, cloudservices', // Quitar servicios de pago
            entities: false,
            basicEntities: false,
            htmlEncodeOutput: false
        });
        // Función para insertar variables en la posición del cursor
        function insertarEnEditor(variable) {
            CKEDITOR.instances.editor_wysiwyg.insertHtml(variable);
        }
    </script>
</body>
</html>
