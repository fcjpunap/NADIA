<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Constructor | NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; font-size: 0.82rem; }
        .sidebar { background: white; border-radius: 1rem; padding: 20px; border: 1px solid #e2e8f0; height: 95vh; overflow-y: auto; }
        .data-card { background: white; border-radius: 1rem; border: 1px solid #e2e8f0; }
        .sticky-custom { position: sticky; top: 15px; }
        .form-label { font-weight: bold; color: #475569; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.05em; }
        .form-select-sm { border-radius: 6px; border-color: #cbd5e1; }
    </style>
</head>
<body class="p-3">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="sidebar sticky-custom shadow-sm">
                <h6 class="fw-bold mb-3 text-indigo border-bottom pb-2"><i class="bi bi-sliders me-2"></i> FILTROS UNIVERSALES</h6>
                <form method="GET" id="filterForm">
                    <!-- Filtros Temporales -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Año</label>
                            <select name="anio" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="total">Histórico</option>
                                <?php foreach($data['anios'] as $a): ?><option value="<?php echo $a; ?>" <?php echo ($data['filtros']['anio']==$a?'selected':''); ?>><?php echo $a; ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Semestre</label>
                            <select name="semestre" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                <option value="1" <?php echo ($data['filtros']['semestre']=='1'?'selected':''); ?>>Sem I (Ene-Jul)</option>
                                <option value="2" <?php echo ($data['filtros']['semestre']=='2'?'selected':''); ?>>Sem II (Ago-Dic)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mes Específico</label>
                        <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">(Cualquiera)</option>
                            <?php $mN = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                            foreach($mN as $i => $m): ?>
                            <option value="<?php echo $i+1; ?>" <?php echo ($data['filtros']['mes']==($i+1)?'selected':''); ?>><?php echo $m; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Facultad</label>
                        <select name="facultad" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">(Todas)</option>
                            <?php foreach($data['facultades'] as $f): ?><option value="<?php echo $f['id']; ?>" <?php echo ($data['filtros']['facultad']==$f['id']?'selected':''); ?>><?php echo $f['nombre']; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtros Jerárquicos -->
                    <div class="p-2 mb-3 bg-light rounded border">
                        <label class="form-label text-primary">ÁREA DE INVESTIGACIÓN</label>
                        <select name="area" class="form-select form-select-sm mb-2" onchange="this.form.submit()">
                            <option value="">- Todas las Áreas -</option>
                            <?php foreach($data['areas'] as $ar): ?><option value="<?php echo $ar['id']; ?>" <?php echo ($data['filtros']['area']==$ar['id']?'selected':''); ?>><?php echo $ar['nombre']; ?></option><?php endforeach; ?>
                        </select>
                        <label class="form-label text-primary">LÍNEA</label>
                        <select name="linea" class="form-select form-select-sm mb-2" onchange="this.form.submit()" <?php echo empty($data['lineas'])?'disabled':''; ?>>
                            <option value="">- Todas las Líneas -</option>
                             <?php foreach($data['lineas'] as $li): ?><option value="<?php echo $li['id']; ?>" <?php echo ($data['filtros']['linea']==$li['id']?'selected':''); ?>><?php echo $li['nombre']; ?></option><?php endforeach; ?>
                        </select>
                        <label class="form-label text-primary">SUBLÍNEA</label>
                        <select name="sublinea" class="form-select form-select-sm" onchange="this.form.submit()" <?php echo empty($data['sublineas'])?'disabled':''; ?>>
                            <option value="">- Todas las Sublíneas -</option>
                             <?php foreach($data['sublineas'] as $sl): ?><option value="<?php echo $sl['id']; ?>" <?php echo ($data['filtros']['sublinea']==$sl['id']?'selected':''); ?>><?php echo $sl['nombre']; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Etapa Actual</label>
                        <select name="etapa" class="form-select form-select-sm" onchange="this.form.submit()">
                             <option value="">(Cualquiera)</option>
                             <option value="1" <?php echo ($data['filtros']['etapa']=='1'?'selected':''); ?>>1. Proyecto</option>
                             <option value="2" <?php echo ($data['filtros']['etapa']=='2'?'selected':''); ?>>2. Borrador</option>
                             <option value="3" <?php echo ($data['filtros']['etapa']=='3'?'selected':''); ?>>3. Sustentación</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" onclick="const p=new URLSearchParams(window.location.search); window.location.href='<?php echo URL_BASE; ?>reportes/exportar_csv?'+p.toString()" class="btn btn-success fw-bold py-2 text-white"><i class="bi bi-file-earmark-excel me-2"></i> DESCARGAR EXCEL</button>
                        <a href="<?php echo URL_BASE; ?>reportes" class="btn btn-light border py-2">Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row g-2 mb-3">
                <div class="col-md-3"><div class="p-2 data-card bg-primary text-white text-center"><h6>TASA ÉXITO</h6><h4><?php echo $data['stats_calidad']['exito']; ?>%</h4></div></div>
                <div class="col-md-3"><div class="p-2 data-card text-center text-success"><h6>APROBADOS</h6><h4><?php echo $data['stats_calidad']['aprobados']; ?></h4></div></div>
                <div class="col-md-3"><div class="p-2 data-card text-center text-muted"><h6>OBSERVADOS</h6><h4><?php echo $data['stats_calidad']['observados']; ?></h4></div></div>
                <div class="col-md-3"><div class="p-2 data-card text-center text-dark fw-bold"><h6>TOTAL</h6><h4><?php echo count($data['proyectos']); ?></h4></div></div>
            </div>
            <div class="data-card shadow-sm">
                <div class="table-responsive" style="max-height: 80vh;">
                    <table class="table table-hover align-middle mb-0 icon-link-hover">
                        <thead class="bg-light text-secondary sticky-top"><tr><th class="ps-3">TESISTA</th><th>TITULO / ÁREA / LÍNEA / SUBLÍNEA</th><th>ASESOR</th><th>ESTADO</th></tr></thead>
                        <tbody>
                            <?php foreach($data['proyectos'] as $p): ?>
                            <tr>
                                <td class="ps-3"><div class="fw-bold text-primary"><?php echo $p['tesista']; ?></div><div class="small text-muted"><?php echo $p['cotesista']; ?></div></td>
                                <td>
                                    <div class="small fw-bold lh-sm mb-1 text-dark"><?php echo mb_strimwidth($p['titulo'],0,120,"..."); ?></div>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-light text-secondary border"><?php echo $p['area']; ?></span>
                                        <span class="badge bg-light text-secondary border"><i class="bi bi-chevron-right" style="font-size:0.6rem"></i> <?php echo $p['linea_nombre']; ?></span>
                                        <span class="badge bg-indigo-soft text-primary border border-primary-subtle"><i class="bi bi-chevron-right" style="font-size:0.6rem"></i> <?php echo $p['sublinea']; ?></span>
                                    </div>
                                </td>
                                <td><div class="small fw-bold"><?php echo $p['asesor']; ?></div></td>
                                <td><span class="badge <?php echo (strpos(strtoupper($p['estado']),'APROBADO')!==false)?'bg-success':'bg-warning text-dark'; ?>"><?php echo $p['estado']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
