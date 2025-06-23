let notificacionCount = 0;
let notificaciones = [];

if (typeof window.ws === "undefined") {
    window.ws = new WebSocket('ws://localhost:8080');

    ws.onopen = function() {
        console.log('Conectado al WebSocket');
    };

    ws.onmessage = function(event) {
        let data;
        try {
            data = JSON.parse(event.data);
        } catch {
            mostrarToast(event.data);
            return;
        }
        mostrarToast(`
            <div style="display:flex;align-items:center;">
                <img src="${data.foto}" style="width:32px;height:32px;border-radius:50%;margin-right:10px;">
                <div>
                    <strong>${data.nombre}</strong><br>
                    <span>${data.descripcion}</span>
                </div>
            </div>
        `);
        notificaciones.unshift(data);
        actualizarListaNotificaciones();

        // Mostrar y actualizar el badge de notificaciones
        notificacionCount++;
        const badge = document.getElementById('notificacionBadge');
        badge.textContent = notificacionCount;
        badge.style.display = 'inline-block';
    };

    ws.onclose = function() {
        console.log('WebSocket cerrado');
    };

    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
}

function mostrarToast(mensaje) {
    const toast = document.getElementById('toastNotificacion');
    toast.innerHTML = mensaje;
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

function actualizarListaNotificaciones() {
    const lista = document.getElementById('listaNotificaciones');
    lista.innerHTML = '';
    notificaciones.slice(0, 10).forEach(msg => {
        let data;
        try {
            data = typeof msg === 'string' ? JSON.parse(msg) : msg;
        } catch {
            data = {};
        }
        // Valores por defecto
        const foto = data.foto ? data.foto : 'img/default-user.png';
        const nombre = data.nombre ? data.nombre : '';
        const descripcion = data.descripcion ? data.descripcion : (typeof msg === 'string' ? msg : '');

        const li = document.createElement('li');
        li.style.padding = '10px';
        li.style.borderBottom = '1px solid #eee';
        li.innerHTML = `
            <div style="display:flex;align-items:center;">
                <img src="${foto}" style="width:32px;height:32px;border-radius:50%;margin-right:10px;">
                <div>
                    <strong>${nombre}</strong><br>
                    <span>${descripcion}</span>
                </div>
            </div>
        `;
        lista.appendChild(li);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if(window.notificacionesPersistentes) {
        notificaciones = window.notificacionesPersistentes.map(n => {
            // Si ya tiene nombre y foto, úsalo directamente
            if (n.nombre && n.foto) return n;
            // Si solo tiene descripcion, crea un objeto básico
            return {
                descripcion: n.descripcion || '',
                nombre: n.nombre || '',
                foto: n.foto || 'img/default-user.png'
            };
        });
        actualizarListaNotificaciones();
    }

    document.getElementById('notificacionIcono').onclick = function() {
        const panel = document.getElementById('panelNotificaciones');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            notificacionCount = 0;
            document.getElementById('notificacionBadge').style.display = 'none';
        } else {
            panel.style.display = 'none';
        }
    };

    document.addEventListener('click', function(e) {
        const panel = document.getElementById('panelNotificaciones');
        const icono = document.getElementById('notificacionIcono');
        if (!panel.contains(e.target) && !icono.contains(e.target)) {
            panel.style.display = 'none';
        }
    });
});