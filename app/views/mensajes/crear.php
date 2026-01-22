<!DOCTYPE html>
<html lang="es">
<head>
    <title>Redactar Mensaje - NADIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .search-results { position: absolute; width: 100%; max-height: 250px; overflow-y: auto; z-index: 1050; background: white; border: 1px solid #ddd; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border-radius: 0 0 8px 8px; }
        .tag-box { border: 1px solid #ced4da; padding: 10px; min-height: 55px; border-radius: 8px; background: #fff; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
        .tag { background: #e7f1ff; color: #0d6efd; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; border: 1px solid #0d6efd; font-weight: 500; display: flex; align-items: center; }
        .tag span.close { margin-left: 10px; cursor: pointer; font-weight: bold; font-size: 1.1rem; }
        .tag span.close:hover { color: #dc3545; }
        .file-item { display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; padding: 8px 12px; border-radius: 6px; margin-bottom: 5px; border: 1px solid #eee; }
        .file-info { display: flex; align-items: center; gap: 10px; }
        .file-name { font-size: 0.9rem; font-weight: 500; }
        .file-size { font-size: 0.75rem; color: #6c757d; }
    </style>
</head>
<body class="bg-light py-4">
<div class="container" style="max-width: 850px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Nuevo Mensaje Electrónico</h5>
            <a href="<?php echo URL_BASE; ?>mensajes/index" class="btn btn-sm btn-light rounded-pill px-3">Volver</a>
        </div>
        <div class="card-body p-4">
            <form action="<?php echo URL_BASE; ?>mensajes/guardar" method="POST" enctype="multipart/form-data" id="msgForm">
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Para:</label>
                    <div id="sel-list" class="tag-box mb-3">
                        <span class="text-muted small">Seleccione destinatarios o use el buscador...</span>
                    </div>
                    <div id="hiddens"></div>
                    <?php if($_SESSION['rol'] == 3 || $_SESSION['rol'] == 4): ?>
                        <div class="row g-2">
                            <div class="col-md-7 position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" id="inU" class="form-control" placeholder="Buscar Persona (Nombre o DNI)">
                                </div>
                                <div id="resU" class="list-group search-results d-none"></div>
                            </div>
                            <div class="col-md-5 position-relative">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-journal-bookmark"></i></span>
                                    <input type="text" id="inP" class="form-control" placeholder="Buscar por Proyecto">
                                </div>
                                <div id="resP" class="list-group search-results d-none"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-3 border rounded bg-white small">
                            <p class="mb-2 text-muted fw-bold">Contactos sugeridos del proyecto:</p>
                            <?php foreach($data['destinatarios'] as $id => $nom): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary m-1 rounded-pill" onclick="addRecipient('<?php echo $id; ?>', '<?php echo htmlspecialchars($nom); ?>')"> + <?php echo htmlspecialchars($nom); ?> </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Asunto</label>
                    <input type="text" name="asunto" class="form-control form-control-lg" required placeholder="Ingrese el asunto del mensaje" value="<?php echo htmlspecialchars($data['reply_to']['asunto'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Contenido del Mensaje</label>
                    <textarea name="cuerpo" class="form-control" rows="6" required placeholder="Escriba aquí su mensaje..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold"><i class="bi bi-paperclip"></i> Archivos Adjuntos</label>
                    <div class="input-group mb-2">
                        <input type="file" id="fileInput" class="form-control" multiple onchange="handleFiles(this.files)">
                        <button type="button" class="btn btn-outline-danger" onclick="clearFiles()">Limpiar</button>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> Tamaño máximo: <?php echo $data['max_size'] ?? '2M'; ?></small>
                        <small class="text-muted" id="fileCount">0 archivos seleccionados</small>
                    </div>
                    <div id="fileListContainer" class="mt-2"></div>
                    <input type="file" name="adjuntos[]" id="realFileInput" style="display:none" multiple>
                </div>

                <div class="form-check form-switch mb-4 p-3 border rounded bg-white">
                    <input class="form-check-input ms-0 me-2" type="checkbox" name="notificar_email" id="chk" checked>
                    <label class="form-check-label fw-bold" for="chk">Notificar automáticamente por correo electrónico</label>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 py-3 shadow-sm"><i class="bi bi-send-fill me-2"></i> Enviar Mensaje</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const base = "<?php echo URL_BASE; ?>".replace(/\/$/, "");
const selectedIds = new Set();
const dt = new DataTransfer();

function addRecipient(id, name) {
    if (selectedIds.has(id.toString())) return;
    selectedIds.add(id.toString());
    if (selectedIds.size === 1) document.getElementById('sel-list').innerHTML = '';
    
    const t = document.createElement('div');
    t.className = 'tag animate__animated animate__fadeInScale';
    t.id = 't' + id;
    t.innerHTML = `${name} <span class="close" onclick="removeRecipient('${id}')">&times;</span>`;
    document.getElementById('sel-list').appendChild(t);
    
    const i = document.createElement('input');
    i.type = 'hidden'; i.name = 'destinatarios[]'; i.value = id; i.id = 'i' + id;
    document.getElementById('hiddens').appendChild(i);
}

function removeRecipient(id) {
    selectedIds.delete(id.toString());
    const tag = document.getElementById('t' + id); if(tag) tag.remove();
    const input = document.getElementById('i' + id); if(input) input.remove();
    if (selectedIds.size === 0) document.getElementById('sel-list').innerHTML = '<span class="text-muted small">Seleccione destinatarios...</span>';
}

function setupSearch(inpId, resId, endpoint) {
    const inp = document.getElementById(inpId);
    const res = document.getElementById(resId);
    if (!inp) return;
    inp.oninput = () => {
        if (inp.value.length < 2) { res.classList.add('d-none'); return; }
        fetch(base + "/api/" + endpoint + "?q=" + encodeURIComponent(inp.value))
            .then(r => r.json()).then(data => {
                res.innerHTML = '';
                if (data.length) {
                    data.forEach(x => {
                        const a = document.createElement('a'); a.className = 'list-group-item list-group-item-action py-2'; a.href = '#';
                        if (endpoint.includes('usuario')) {
                            a.innerHTML = `<div class="d-flex justify-content-between"><strong>${x.nombres} ${x.apellidos}</strong> <small class="badge bg-secondary">${x.rol || 'User'}</small></div><small class="text-muted">DNI: ${x.dni || ''}</small>`;
                            a.onclick = (e) => { e.preventDefault(); addRecipient(x.id, x.nombres+' '+x.apellidos); res.classList.add('d-none'); inp.value=''; };
                        } else {
                            a.innerHTML = `<div class='fw-bold small py-1'>${x.titulo}</div><small class="text-success">+ Añadir todos</small>`;
                            a.onclick = (e) => { e.preventDefault(); x.participantes.forEach(p => addRecipient(p.id, p.nombre)); res.classList.add('d-none'); inp.value=''; };
                        }
                        res.appendChild(a);
                    });
                    res.classList.remove('d-none');
                } else { res.classList.add('d-none'); }
            });
    };
}

setupSearch('inU', 'resU', 'buscar_usuarios_mensaje');
setupSearch('inP', 'resP', 'buscar_proyecto_participantes');

function handleFiles(files) { for (let i = 0; i < files.length; i++) { dt.items.add(files[i]); } document.getElementById('fileInput').value = ''; renderFiles(); }
function removeFile(index) { dt.items.remove(index); renderFiles(); }
function clearFiles() { while(dt.items.length > 0) dt.items.remove(0); renderFiles(); }
function renderFiles() {
    const list = document.getElementById('fileListContainer');
    const realInput = document.getElementById('realFileInput');
    const counter = document.getElementById('fileCount');
    realInput.files = dt.files;
    list.innerHTML = ''; counter.innerText = `${dt.files.length} archivo(s) seleccionado(s)`;
    for (let i = 0; i < dt.files.length; i++) {
        const file = dt.files[i];
        const div = document.createElement('div');
        div.className = 'file-item';
        div.innerHTML = `<div class="file-info"><i class="bi bi-file-earmark-text text-primary fs-4"></i><div><div class="file-name">${file.name}</div><div class="file-size">${(file.size/1024).toFixed(1)} KB</div></div></div><button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeFile(${i})"><i class="bi bi-x-circle-fill"></i></button>`;
        list.appendChild(div);
    }
}

document.onclick = (e) => { if (!e.target.closest('.position-relative')) document.querySelectorAll('.search-results').forEach(r => r.classList.add('d-none')); };
document.getElementById('msgForm').onsubmit = (e) => { if (selectedIds.size === 0) { alert('Debe seleccionar al menos un destinatario.'); return false; } };

document.addEventListener("DOMContentLoaded", function () {
    <?php if (isset($data['reply_to'])): ?>
        addRecipient("<?php echo $data['reply_to']['id']; ?>", "<?php echo htmlspecialchars($data['reply_to']['nombre']); ?>");
    <?php endif; ?>
});
</script>
</body>
</html>
