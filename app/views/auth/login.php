<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Acceso - NADIA</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{background:#f0f2f5;display:flex;align-items:center;justify-content:center;height:100vh}.login-card{width:100%;max-width:400px;padding:30px;background:white;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1)}</style></head><body>
<div class="login-card text-center">
    <h2 class="fw-bold mb-1">NADIA</h2>
    <small class="text-muted mb-4 d-block">Núcleo de Apoyo para Dictámenes<br>de Investigación Académica</small>
    <?php if(isset($data['error'])): ?><div class="alert alert-danger p-2 small"><?php echo $data['error']; ?></div><?php endif; ?>
    <form action="<?php echo URL_BASE; ?>auth/login" method="POST" class="text-start">
        <div class="mb-3"><label class="form-label small fw-bold">Correo Institucional</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label small fw-bold">Contraseña</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-primary w-100 py-2">INGRESAR AL SISTEMA</button>
    </form>
    <div class="mt-3 text-center">
        <a href="<?php echo URL_BASE; ?>auth/recuperar" class="small text-decoration-none">¿Olvidó su contraseña?</a>
    </div>
    <div class="mt-4 text-center small text-muted">&copy; 2025 Facultad de Ciencias Jurídicas y Políticas<br>Laboratorio de Cómputo de Derecho</div>
</div></body></html>
