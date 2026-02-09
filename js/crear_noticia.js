// JavaScript para gestión de secciones dinámicas en crear_noticia.php y editar_noticia.php

let seccionCounter = 0;

// Funciones de utilidad
function getIconoTipo(tipo) {
    const iconos = {
        'titulo': 'heading',
        'subtitulo': 'heading',
        'parrafo': 'paragraph',
        'imagen': 'image',
        'video': 'video',
        'audio': 'volume-up',
        'enlace': 'link'
    };
    return iconos[tipo] || 'file-alt';
}

function getSeccionTemplate(index) {
    return `
        <div class="seccion-item" data-index="${index}">
            <input type="hidden" name="seccion_id[]" value="0">
            
            <div class="seccion-header">
                <div style="display: flex; align-items: center;">
                    <div class="seccion-numero">${index + 1}</div>
                    <span style="margin-left: 10px; font-weight: 600;">Sección ${index + 1}</span>
                    <i class="icono-seccion fas fa-file-alt"></i>
                </div>
                <button type="button" class="btn-remove-seccion" onclick="removeSeccion(this)">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
            
            <div class="form-group">
                <label>Tipo de Contenido *</label>
                <select name="seccion_tipo[]" class="seccion-tipo" onchange="cambiarTipoSeccion(this, ${index})" required>
                    <option value="titulo">Título Principal</option>
                    <option value="subtitulo">Subtítulo</option>
                    <option value="parrafo">Párrafo de Texto</option>
                    <option value="imagen">Imagen</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                    <option value="enlace">Enlace Externo</option>
                </select>
            </div>
            
            <div class="seccion-contenido">
                <div class="form-group">
                    <label>Contenido *</label>
                    <textarea name="seccion_contenido[]" class="seccion-texto" required placeholder="Ingresa el contenido de esta sección..."></textarea>
                </div>
            </div>
            
            <div class="seccion-extra" id="seccion-extra-${index}">
                <!-- Los campos adicionales se generarán aquí -->
            </div>
            
            <div class="seccion-actions">
                <button type="button" class="seccion-mover" onclick="moverSeccion(this, -1)">
                    <i class="fas fa-arrow-up"></i> Subir
                </button>
                <button type="button" class="seccion-mover" onclick="moverSeccion(this, 1)">
                    <i class="fas fa-arrow-down"></i> Bajar
                </button>
            </div>
        </div>
    `;
}

function cambiarTipoSeccion(select, index) {
    const tipo = select.value;
    const extraDiv = document.getElementById(`seccion-extra-${index}`);
    let html = '';
    
    if (['imagen', 'video', 'audio'].includes(tipo)) {
        html = `
            <div class="media-field">
                <label>Subir archivo (opcional)</label>
                <div class="drag-drop-area" onclick="document.getElementById('media_${index}').click()" 
                     ondragover="event.preventDefault(); this.classList.add('dragover')"
                     ondragleave="this.classList.remove('dragover')"
                     ondrop="handleDrop(event, ${index})">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #007bff;"></i>
                    <p>Arrastra y suelta el archivo aquí o haz clic para seleccionar</p>
                    <input type="file" id="media_${index}" name="seccion_media_${index}" 
                           style="display: none;" onchange="previewMedia(this, ${index})"
                           accept="${tipo === 'imagen' ? 'image/*' : (tipo === 'video' ? 'video/*' : 'audio/*')}">
                </div>
                <div class="file-info">Formato recomendado: ${tipo === 'imagen' ? 'JPG, PNG (máx 5MB)' : (tipo === 'video' ? 'MP4, WebM (máx 50MB)' : 'MP3, WAV (máx 10MB)')}</div>
                <img id="preview_${index}" class="media-preview" src="" alt="Vista previa" style="display: none;">
            </div>
            
            <div style="margin-top: 15px;">
                <label>O ingresa una URL</label>
                <input type="text" name="seccion_enlace[]" 
                       value="" 
                       placeholder="https://ejemplo.com/archivo" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <div class="file-info">Dejas el archivo vacío y pones solo la URL, o viceversa</div>
            </div>
        `;
    } else if (tipo === 'enlace') {
        html = `
            <div class="media-field">
                <label>URL del Enlace *</label>
                <input type="text" name="seccion_enlace[]" required
                       value=""
                       placeholder="https://ejemplo.com/noticia-completa"
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
        `;
    } else {
        html = `<input type="hidden" name="seccion_enlace[]" value="">`;
    }
    
    extraDiv.innerHTML = html;
    
    // Cambiar icono
    const header = select.closest('.seccion-item').querySelector('.icono-seccion');
    header.className = `icono-seccion fas fa-${getIconoTipo(tipo)}`;
}

function addSeccion() {
    const container = document.getElementById('secciones-container');
    const index = document.querySelectorAll('.seccion-item').length;
    
    const div = document.createElement('div');
    div.innerHTML = getSeccionTemplate(index);
    container.appendChild(div.firstElementChild);
    
    actualizarNumerosSecciones();
}

function removeSeccion(btn) {
    const item = btn.closest('.seccion-item');
    item.remove();
    actualizarNumerosSecciones();
}

function moverSeccion(btn, direccion) {
    const item = btn.closest('.seccion-item');
    const container = document.getElementById('secciones-container');
    
    if (direccion === -1) {
        const prev = item.previousElementSibling;
        if (prev) {
            container.insertBefore(item, prev);
        }
    } else {
        const next = item.nextElementSibling;
        if (next && next.nextElementSibling) {
            container.insertBefore(item, next.nextElementSibling);
        } else if (next) {
            container.appendChild(item);
        }
    }
    
    actualizarNumerosSecciones();
}

function actualizarNumerosSecciones() {
    const items = document.querySelectorAll('.seccion-item');
    items.forEach((item, index) => {
        item.setAttribute('data-index', index);
        item.querySelector('.seccion-numero').textContent = index + 1;
        item.querySelector('.seccion-header span').textContent = `Sección ${index + 1}`;
    });
}

function previewMedia(input, index) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(`preview_${index}`);
            if (input.files[0].type.startsWith('image/')) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxWidth = '200px';
                preview.style.marginTop = '10px';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleDrop(event, index) {
    event.preventDefault();
    const dropArea = event.target.closest('.drag-drop-area');
    if (dropArea) {
        dropArea.classList.remove('dragover');
    }
    
    if (event.dataTransfer.files && event.dataTransfer.files[0]) {
        const input = document.getElementById(`media_${index}`);
        input.files = event.dataTransfer.files;
        
        // Disparar evento change
        const event2 = new Event('change', { bubbles: true });
        input.dispatchEvent(event2);
    }
}

function previewPrincipal(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('previewPrincipalContainer');
            const img = document.getElementById('previewImagenPrincipal');
            img.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Botón para agregar sección
    const btnAddSeccion = document.getElementById('btnAddSeccion');
    if (btnAddSeccion) {
        btnAddSeccion.addEventListener('click', addSeccion);
    }
    
    // Si no hay secciones, agregar una por defecto
    const container = document.getElementById('secciones-container');
    if (container && container.querySelectorAll('.seccion-item').length === 0) {
        addSeccion();
    }
    
    // Inicializar secciones existentes
    document.querySelectorAll('.seccion-tipo').forEach((select, index) => {
        const tipo = select.value;
        const item = select.closest('.seccion-item');
        const itemIndex = Array.from(document.querySelectorAll('.seccion-item')).indexOf(item);
        
        // Recrear campos extra si es necesario
        if (['imagen', 'video', 'audio', 'enlace'].includes(tipo)) {
            cambiarTipoSeccion(select, itemIndex);
        }
    });
    
    actualizarNumerosSecciones();
    
    // Prevenir comportamiento por defecto del drag-drop
    document.addEventListener('dragover', (e) => e.preventDefault());
    document.addEventListener('drop', (e) => e.preventDefault());
});
