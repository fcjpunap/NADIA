<!DOCTYPE html>
<html lang="es">
<head>
    <title>Verificar Código - Sistema NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0" style="width: 100%; max-width: 420px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-envelope-check text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-2">Verificar Email</h4>
                <p class="text-muted small">Hemos enviado un código a <strong><?php echo htmlspecialchars($data['email']); ?></strong></p>
            </div>
            <?php if(isset($data['error'])): ?>
                <div class="alert alert-danger text-center small"><?php echo $data['error']; ?></div>
            <?php endif; ?>
            <form action="<?php echo URL_BASE; ?>auth/procesar_reseteo" method="POST">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Código de Verificación</label>
                    <input type="text" name="codigo" class="form-control form-control-lg text-center tracking-widest" placeholder="######" maxlength="6" required style="letter-spacing: 5px; font-weight: bold;">
                </div>
                <hr class="my-4">
                <h6 class="mb-3 small text-muted text-uppercase">Nueva Contraseña</h6>
                <div class="mb-3">
                    <input type="password" name="pass1" class="form-control" placeholder="Nueva contraseña" required minlength="6">
                </div>
                <div class="mb-4">
                    <input type="password" name="pass2" class="form-control" placeholder="Confirmar contraseña" required minlength="6">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Cambiar Contraseña</button>
                    <a href="<?php echo URL_BASE; ?>auth/recuperar" class="btn btn-link text-muted mt-2 btn-sm">Reenviar Código / Cambiar Email</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
