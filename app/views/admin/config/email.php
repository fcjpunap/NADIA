<!DOCTYPE html>
<html lang="es">
<head>
    <title>Configuración SMTP - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
        function toggleSMTP() {
            const isActive = document.getElementById('chkActive').checked;
            const fields = document.getElementById('smtpFields');
            if (fields) {
                fields.style.display = isActive ? 'block' : 'none';
                fields.querySelectorAll('input, select').forEach(el => el.disabled = !isActive);
            }
        }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 650px;">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-envelope-gear"></i> Configuración de Correo Electrónico</h5>
                <a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-sm btn-outline-light">Volver</a>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_BASE; ?>admin/config_email" method="POST">
                    
                    <div class="form-check form-switch mb-4 p-3 border rounded bg-white">
                        <input class="form-check-input" type="checkbox" id="chkActive" name="smtp_active" value="1" 
                            <?php echo (isset($data['conf']['smtp_active']) && $data['conf']['smtp_active'] == 1) ? 'checked' : ''; ?> 
                            onchange="toggleSMTP()">
                        <label class="form-check-label fw-bold" for="chkActive">
                            Activar Configuración SMTP Personalizada
                        </label>
                        <div class="form-text small mt-2">
                            <i class="bi bi-info-circle"></i> 
                            Si está <strong>desactivado</strong>, el sistema usará el servicio de correo por defecto del servidor (sendmail/gsmtp).
                            Si está <strong>activado</strong>, configure un servidor SMTP externo.
                        </div>
                    </div>
                    <div id="smtpFields" style="<?php echo (isset($data['conf']['smtp_active']) && $data['conf']['smtp_active'] == 1) ? '' : 'display:none;'; ?>">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-server"></i> SMTP Server Settings
                        </h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Servidor SMTP (Host)</label>
                            <input type="text" name="smtp_server" class="form-control" 
                                   placeholder="mail.example.com" 
                                   value="<?php echo htmlspecialchars($data['conf']['smtp_server'] ?? ''); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Puerto</label>
                                <input type="number" name="smtp_port" class="form-control" 
                                       placeholder="465 (SSL) o 587 (TLS)" 
                                       value="<?php echo htmlspecialchars($data['conf']['smtp_port'] ?? '25'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Seguridad / Encriptación</label>
                                <select name="smtp_secure" class="form-select">
                                    <option value="ssl" <?php echo (($data['conf']['smtp_secure'] ?? '') == 'ssl') ? 'selected' : ''; ?>>SSL (Puerto 465)</option>
                                    <option value="tls" <?php echo (($data['conf']['smtp_secure'] ?? '') == 'tls') ? 'selected' : ''; ?>>TLS (Puerto 587)</option>
                                    <option value="none" <?php echo (($data['conf']['smtp_secure'] ?? '') == 'none') ? 'selected' : ''; ?>>None (Puerto 25)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usuario SMTP (Email de autenticación)</label>
                            <input type="text" name="smtp_user" class="form-control" 
                                   placeholder="usuario@dominio.com" 
                                   value="<?php echo htmlspecialchars($data['conf']['smtp_user'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña SMTP</label>
                            <input type="password" name="smtp_pass" class="form-control" 
                                   placeholder="••••••••" 
                                   value="<?php echo htmlspecialchars($data['conf']['smtp_pass'] ?? ''); ?>">
                        </div>
                    </div>
                    <hr>
                    
                    <h6 class="text-secondary mb-3">
                        <i class="bi bi-person-badge"></i> Información del Remitente
                    </h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small text-muted">Nombre del Remitente</label>
                            <input type="text" name="sender_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['conf']['sender_name'] ?? 'Sistema NADIA'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Email del Remitente</label>
                            <input type="email" name="sender_email" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['conf']['sender_email'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>toggleSMTP();</script>
</body>
</html>
