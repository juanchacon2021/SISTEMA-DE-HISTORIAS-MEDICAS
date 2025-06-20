let notificacionCount = 0;
let notificaciones = [];

if (typeof window.ws === "undefined") {
    window.ws = new WebSocket('ws://localhost:8080');

    ws.onopen = function() {
        console.log('Conectado al WebSocket');
    };

    ws.onmessage = function(event) {
        notificacionCount++;
        document.getElementById('notificacionBadge').innerText = notificacionCount;
        document.getElementById('notificacionBadge').style.display = 'inline';

        // Guardar la notificación
        notificaciones.unshift(event.data);
        actualizarListaNotificaciones();

        // Mostrar toast flotante
        mostrarToast(event.data);
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
    toast.innerText = mensaje;
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

function actualizarListaNotificaciones() {
    const lista = document.getElementById('listaNotificaciones');
    lista.innerHTML = '';
    notificaciones.slice(0, 10).forEach(msg => {
        const li = document.createElement('li');
        li.style.padding = '10px';
        li.style.borderBottom = '1px solid #eee';
        li.innerText = msg;
        lista.appendChild(li);
    });
}

document.addEventListener('DOMContentLoaded', function() {
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