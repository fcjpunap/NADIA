<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Expediente - Archivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .doc-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .doc-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-top: 6px solid #dee2e6;
        }
        .doc-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .doc-card.filled { border-top-color: #28a745; background-color: #f8fff9; }
        .doc-card.missing { border-top-color: #dc3545; background-color: #fffafb; }
        
        .card-header-doc {
            padding: 15px;
            text-align: center;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            color: #444;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body-doc {
            padding: 20px;
            flex-grow: 1;
            text-align: center;
        }
        .file-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            display: block;
        }
        .file-name-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 15px;
            display: block;
            height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-footer-doc {
            padding: 15px;
            background: rgba(0,0,0,0.02);
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            gap: 8px;
        }
        .btn-action {
            flex: 1;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 10px 5px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">Gestor de Documentos Nadia</h1>
                <p class="text-muted mb-0">Expediente: <span class="badge bg-dark"><?php echo substr($data['proyecto']['uuid'], 0, 8); ?></span> - <?php echo $data['proyecto']['titulo']; ?></p>
            </div>
            <a href="<?php echo URL_BASE; ?>admin/ver_proyecto?id=<?php echo $data['proyecto']['id']; ?>" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i> Volver al Detalle
            </a>
        </div>

        <div class="doc-container">
            <?php 
                        $tiposRequeridos = [
                "Proyecto (PDF)", "Proyecto (Word)", 
                "Corrección Proyecto (PDF)", "Corrección Proyecto (Word)",
                "Borrador Tesis (PDF)", "Borrador Tesis (Word)",
                "Corrección Borrador (PDF)", "Corrección Borrador (Word)",
                "Requisitos de Grado (PDF)", "Requisitos de Grado (Word)",
                "Requisitos de Sustentacion (PDF)", "Requisitos de Sustentacion (Word)"
            ];

            foreach($tiposRequeridos as $tipo):
                $doc = $data['docsByType'][$tipo] ?? null;
                $hasFile = !is_null($doc);
            ?>
                <div class="doc-card <?php echo $hasFile ? 'filled' : 'missing'; ?>">
                    <div class="card-header-doc">
                        <?php echo $tipo; ?>
                    </div>
                    <div class="card-body-doc">
                        <?php if($hasFile): ?>
                            <?php 
                                $isPdf = strpos(strtolower($doc['nombre_archivo_original']), '.pdf') !== false;
                                $icon = $isPdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-word text-primary';
                            ?>
                            <i class="bi <?php echo $icon; ?> file-icon"></i>
                            <span class="file-name-label"><?php echo htmlspecialchars($doc['nombre_archivo_original']); ?></span>
                        <?php else: ?>
                            <i class="bi bi-cloud-upload text-muted file-icon" style="opacity: 0.2;"></i>
                            <span class="file-name-label italic text-muted">Aún no se ha cargado</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer-doc">
                        <?php if($hasFile): ?>
                            <a href="<?php echo URL_BASE . $doc['ruta_archivo']; ?>" target="_blank" class="btn btn-primary btn-action">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <button onclick="intentarReemplazar(<?php echo $doc['id']; ?>, '<?php echo $tipo; ?>')" class="btn btn-warning btn-action">
                                <i class="bi bi-arrow-repeat"></i> Cambiar
                            </button>
                            <button onclick="intentarEliminar(<?php echo $doc['id']; ?>, '<?php echo $tipo; ?>')" class="btn btn-danger btn-action">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php else: ?>
                            <button onclick="intentarSubir('<?php echo $tipo; ?>')" class="btn btn-success btn-action w-100">
                                <i class="bi bi-plus-circle"></i> Cargar Archivo
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Script de Acciones con Motivo Obligatorio -->
    <script>
        const baseUrl = '<?php echo URL_BASE; ?>';
        const projectId = '<?php echo $data['proyecto']['id']; ?>';

        function intentarSubir(tipo) {
            Swal.fire({
                title: 'Cargar Documento',
                html: '<div class="text-start mb-2"><small class="fw-bold">Tipo: ' + tipo + '</small></div>' +
                      '<input type="file" id="f_up" class="form-control mb-3">' +
                      '<textarea id="f_motivo" class="form-control" placeholder="Escriba el motivo de esta carga (Obligatorio)..."></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Guardar Archivo',
                preConfirm: () => {
                    const file = document.getElementById('f_up').files[0];
                    const motivo = document.getElementById('f_motivo').value;
                    if (!file) return Swal.showValidationMessage('Seleccione un archivo');
                    if (!motivo || motivo.length < 5) return Swal.showValidationMessage('El motivo debe ser más detallado');
                    return { file: file, motivo: motivo };
                }
            }).then(r => {
                if (r.isConfirmed) processAction('subir', { tipo: tipo, file: r.value.file, motivo: r.value.motivo });
            });
        }

        function intentarReemplazar(id, tipo) {
            Swal.fire({
                title: 'Reemplazar Archivo',
                html: '<div class="text-start mb-2 text-warning"><small class="fw-bold">Se reemplazará el documento existente de: ' + tipo + '</small></div>' +
                      '<input type="file" id="f_up" class="form-control mb-3">' +
                      '<textarea id="f_motivo" class="form-control" placeholder="Escriba el motivo del reemplazo (Obligatorio)..."></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Actualizar Archivo',
                preConfirm: () => {
                    const file = document.getElementById('f_up').files[0];
                    const motivo = document.getElementById('f_motivo').value;
                    if (!file) return Swal.showValidationMessage('Seleccione el nuevo archivo');
                    if (!motivo || motivo.length < 5) return Swal.showValidationMessage('Escriba el motivo del reemplazo');
                    return { file: file, motivo: motivo };
                }
            }).then(r => {
                if (r.isConfirmed) processAction('reemplazar', { id: id, file: r.value.file, motivo: r.value.motivo });
            });
        }

        function intentarEliminar(id, tipo) {
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: 'Se eliminará el registro de ' + tipo + '. Esta acción no se puede deshacer.',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Indique detalladamente el motivo de la eliminación (Obligatorio)...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar permanentemente',
                preConfirm: (m) => {
                    if (!m || m.length < 5) return Swal.showValidationMessage('El motivo es obligatorio');
                    return m;
                }
            }).then(r => {
                if (r.isConfirmed) {
                    window.location.href = baseUrl + "admin/eliminar_archivo_admin?id=" + id + "&pid=" + projectId + "&motivo=" + encodeURIComponent(r.value);
                }
            });
        }

        function processAction(mode, data) {
            const fd = new FormData();
            fd.append('id_proyecto', projectId);
            fd.append('motivo', data.motivo);
            fd.append('archivo', data.file);
            
            let url = baseUrl + 'admin/subir_archivo_admin';
            if (mode === 'subir') fd.append('tipo_documento', data.tipo);
            if (mode === 'reemplazar') {
                fd.append('id_documento', data.id);
                url = baseUrl + 'admin/reemplazar_archivo_admin';
            }

            Swal.fire({ title: 'Procesando...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

            fetch(url, { method: 'POST', body: fd })
            .then(resp => resp.redirected ? window.location.href = resp.url : location.reload());
        }
    </script>
</body>
</html>