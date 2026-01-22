<!DOCTYPE html>
<html lang="es">
<head>
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-center">
            <div class="mb-3">
                <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
            </div>
            <h4 class="mb-3">Recuperar Acceso</h4>
            <p class="text-muted small mb-4">Ingrese su correo institucional. Le enviaremos un código de verificación.</p>
            <?php if(isset($data['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show text-start small">
                    <?php echo $data['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <form action="<?php echo URL_BASE; ?>auth/recuperar" method="POST">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Correo Electrónico</label>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Enviar Código</button>
                    <a href="<?php echo URL_BASE; ?>auth/login" class="btn btn-link text-decoration-none text-secondary">Volver al Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
