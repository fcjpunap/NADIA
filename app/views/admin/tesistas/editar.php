<!DOCTYPE html>
<html lang="es">
<head><title>NADIA - Investigación</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5" style="max-width:600px">
    <div class="d-flex justify-content-between mb-3">
        <h3>Editar Estudiante</h3>
        <a href="<?php echo URL_BASE; ?>admin/tesistas" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-warning text-dark"><strong>Datos del Tesista</strong></div>
        <div class="card-body">
            <form action="" method="POST">
                <input type="hidden" name="rol" value="1">
                <input type="hidden" name="facultad" value="<?php echo $data['usuario']['facultad_asignada']; ?>">
                <input type="hidden" name="activo" value="<?php echo $data['usuario']['activo']; ?>">
                <input type="hidden" name="grado" value="Estudiante">
                <input type="hidden" name="condicion" value="Externo">

                <div class="row mb-2">
                    <div class="col"><label>Nombres</label><input type="text" name="nombres" class="form-control" value="<?php echo $data['usuario']['nombres']; ?>" required></div>
                    <div class="col"><label>Apellidos</label><input type="text" name="apellidos" class="form-control" value="<?php echo $data['usuario']['apellidos']; ?>" required></div>
                </div>
                
                <div class="row mb-2">
                    <div class="col"><label>DNI</label><input type="text" name="dni" class="form-control" value="<?php echo $data['usuario']['dni']; ?>"></div>
                    <div class="col"><label>Código Matrícula</label><input type="text" name="codigo" class="form-control" value="<?php echo $data['usuario']['codigo']; ?>"></div>
                </div>

                <div class="mb-3"><label>Teléfono / Celular</label><input type="text" name="telefono" class="form-control" value="<?php echo $data['usuario']['telefono']; ?>"></div>
                <div class="mb-3"><label>Email Institucional</label><input type="email" name="email" class="form-control" value="<?php echo $data['usuario']['email']; ?>" required></div>
                
                <button type="submit" class="btn btn-warning w-100">Actualizar Datos</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
