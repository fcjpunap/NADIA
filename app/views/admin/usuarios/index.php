<!DOCTYPE html><html lang="es"><head><title>Usuarios</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2>Usuarios</h2><div><a href="<?php echo URL_BASE; ?>admin/dashboard" class="btn btn-secondary">Volver</a> <a href="<?php echo URL_BASE; ?>admin/usuarios_crear" class="btn btn-primary">Nuevo</a></div></div>
    <form class="d-flex mb-3" method="GET"><input class="form-control me-2" type="search" name="q" placeholder="Buscar..." value="<?php echo $data['q']??''; ?>"><button class="btn btn-outline-success" type="submit">Buscar</button></form>
    <div class="card shadow"><div class="card-body"><table class="table table-hover"><thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Facultad</th><th>Rol</th><th>Acción</th></tr></thead><tbody>
    <?php foreach($data['usuarios'] as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo $u['apellidos'].' '.$u['nombres']; ?></td>
            <td><?php echo $u['email']; ?></td> <td><?php echo substr($u['nombre_facultad']??'',0,25); ?></td>
            <td><?php echo $u['nombre_rol']; ?></td>
            <td><a href="<?php echo URL_BASE; ?>admin/editar_usuario?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-primary">Editar</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div></div>
    <?php if($data['paginacion']['pages']>1): ?><nav class="mt-3"><ul class="pagination justify-content-center"><?php for($i=1;$i<=$data['paginacion']['pages'];$i++): ?><li class="page-item <?php echo ($i==$data['paginacion']['current'])?'active':''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&q=<?php echo $data['q']; ?>"><?php echo $i; ?></a></li><?php endfor; ?></ul></nav><?php endif; ?>
</div></body></html>
