<!DOCTYPE html>
<html lang="es">
<head>
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-<?php echo $data['layout']['color']; ?> text-white">
                        <h5 class="mb-0">Cambiar Contraseña</h5>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($data['msg'])): 
                            $parts = explode('|', $data['msg']);
                            $alertClass = ($parts[0] == 'success') ? 'alert-success' : 'alert-danger';
                        ?>
                            <div class="alert <?php echo $alertClass; ?>"><?php echo $parts[1]; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" name="actual" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="nueva" class="form-control" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="confirmar" class="form-control" required minlength="6">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-<?php echo $data['layout']['color']; ?>">Actualizar Contraseña</button>
                                <a href="<?php echo URL_BASE . $data['layout']['home']; ?>" class="btn btn-secondary">Volver al Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
