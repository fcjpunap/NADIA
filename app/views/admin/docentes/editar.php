<!DOCTYPE html>
<html lang="es">
<head><title>Editar Docente</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4"><h3>Editar Docente</h3><a href="<?php echo URL_BASE; ?>admin/docentes" class="btn btn-secondary">Volver</a></div>
    <form action="" method="POST">
        <input type="hidden" name="rol" value="2">
        <input type="hidden" name="activo" value="<?php echo $data['usuario']['activo']; ?>">
        <div class="row">
            <div class="col-md-5">
                <div class="card shadow mb-3">
                    <div class="card-header bg-primary text-white">Datos Académicos</div>
                    <div class="card-body">
                        <label>Facultad</label>
                        <select name="facultad_input" id="sFac" class="form-select mb-2" onchange="loadProg()">
                            <option value="">Seleccione...</option>
                            <?php foreach($data['facultades'] as $f): ?>
                                <option value="<?php echo $f['id']; ?>" <?php echo ($f['id']==$data['usuario']['id_facultad'])?'selected':''; ?>><?php echo $f['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Nombres</label><input type="text" name="nombres" class="form-control mb-2" value="<?php echo $data['usuario']['nombres']; ?>">
                        <label>Apellidos</label><input type="text" name="apellidos" class="form-control mb-2" value="<?php echo $data['usuario']['apellidos']; ?>">
                        <label>DNI</label><input type="text" name="dni" class="form-control mb-2" value="<?php echo $data['usuario']['dni']; ?>">
                        <label>Email</label><input type="email" name="email" class="form-control mb-2" value="<?php echo $data['usuario']['email']; ?>">
                        
                        <label>Grado Académico</label><input type="text" name="grado" class="form-control mb-2" value="<?php echo $data['usuario']['grado_academico']; ?>" placeholder="Dr. / Mg. / Abg.">
                        
                        <div class="row mt-2">
                            <div class="col">
                                <label>Categoría</label>
                                <select name="categoria" class="form-select">
                                    <?php $cat = $data['usuario']['categoria_docente']; ?>
                                    <option <?php echo ($cat=='Principal')?'selected':''; ?>>Principal</option>
                                    <option <?php echo ($cat=='Asociado')?'selected':''; ?>>Asociado</option>
                                    <option <?php echo ($cat=='Auxiliar')?'selected':''; ?>>Auxiliar</option>
                                    <option <?php echo ($cat=='Contratado')?'selected':''; ?>>Contratado</option>
                                </select>
                            </div>
                            <div class="col"><label>Antigüedad (Años)</label><input type="number" name="antiguedad" class="form-control" value="<?php echo $data['usuario']['antiguedad_anios']; ?>"></div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success w-100">Guardar Cambios</button>
            </div>
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">Expertise</div>
                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                        <?php if(empty($data['arbol'])): ?><div>Sin datos.</div><?php else: foreach($data['arbol'] as $a): ?>
                            <h6 class="fw-bold bg-light p-2"><?php echo $a['nombre']; ?></h6>
                            <?php if(isset($a['lineas'])) foreach($a['lineas'] as $l): ?>
                                <div class="ms-3"><strong><?php echo $l['nombre']; ?></strong>
                                <?php if(isset($l['sublineas'])) foreach($l['sublineas'] as $s): ?>
                                    <div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="sublineas[]" value="<?php echo $s['id']; ?>" <?php echo in_array($s['id'],$data['expertise'])?'checked':''; ?>><label><?php echo $s['nombre']; ?></label></div>
                                <?php endforeach; ?></div>
                            <?php endforeach; endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</body></html>
