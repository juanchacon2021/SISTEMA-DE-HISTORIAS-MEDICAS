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
        // Guardar en localStorage
        let guardadas = JSON.parse(localStorage.getItem('notificacionesGuardadas') || '[]');
        guardadas.unshift(data);
        // Evitar duplicados por fecha_hora
        let fechas = new Set();
        let unicas = [];
        for (let n of guardadas) {
            if (!fechas.has(n.fecha_hora)) {
                fechas.add(n.fecha_hora);
                unicas.push(n);
            }
        }
        localStorage.setItem('notificacionesGuardadas', JSON.stringify(unicas));
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
    let leidas = JSON.parse(localStorage.getItem('notificacionesLeidas') || '[]');

    notificaciones.slice(0, 20).forEach(msg => {
        let data;
        try {
            data = typeof msg === 'string' ? JSON.parse(msg) : msg;
        } catch {
            data = {};
        }
        const foto = data.foto ? data.foto : 'img/user.png';
        const nombre = data.nombre ? data.nombre : '';
        const descripcion = data.descripcion ? data.descripcion : (typeof msg === 'string' ? msg : '');
        const fecha = data.fecha_hora ? data.fecha_hora : '';

        // Marcar como no leída si su fecha_hora no está en localStorage
        const isUnread = fecha && !leidas.includes(fecha);

        const li = document.createElement('li');
        li.style.padding = '10px';
        li.style.borderBottom = '1px solid #eee';
        if (isUnread) {
            li.style.background = '#f8f9fa';
            li.style.fontWeight = 'bold';
        }
        li.innerHTML = `
            <div style="display:flex;align-items:center;">
                <img src="${foto}" style="width:32px;height:32px;border-radius:50%;margin-right:10px;">
                <div>
                    <strong>${nombre}</strong><br>
                    <span>${descripcion}</span>
                    <div style="font-size:11px;color:#888;">${fecha}</div>
                </div>
            </div>
        `;
        lista.appendChild(li);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let guardadas = JSON.parse(localStorage.getItem('notificacionesGuardadas') || '[]');
    let leidas = JSON.parse(localStorage.getItem('notificacionesLeidas') || '[]');
    let nuevas = [];

    // Si hay notificaciones del backend, agrégalas a las guardadas solo si no existen
    if(window.notificacionesPersistentes) {
        let fechas = new Set(guardadas.map(n => n.fecha_hora));
        window.notificacionesPersistentes.forEach(n => {
            if (n.fecha_hora && !fechas.has(n.fecha_hora)) {
                guardadas.push(n);
                fechas.add(n.fecha_hora);
            }
        });
        localStorage.setItem('notificacionesGuardadas', JSON.stringify(guardadas));
    }

    notificaciones = guardadas;

    nuevas = notificaciones.filter(n => n.fecha_hora && !leidas.includes(n.fecha_hora));
    notificacionCount = nuevas.length;

    const badge = document.getElementById('notificacionBadge');
    if (notificacionCount > 0) {
        badge.textContent = notificacionCount;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }

    actualizarListaNotificaciones();

    document.getElementById('notificacionIcono').onclick = function() {
        const panel = document.getElementById('panelNotificaciones');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
            // Marcar todas como leídas
            let ids = notificaciones.map(n => n.fecha_hora).filter(Boolean);
            localStorage.setItem('notificacionesLeidas', JSON.stringify(ids));
            notificacionCount = 0;
            document.getElementById('notificacionBadge').style.display = 'none';
            actualizarListaNotificaciones();
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

document.getElementById('userDropdownBtn').onclick = function(e) {
    e.stopPropagation();
    document.getElementById('userDropdownMenu').classList.toggle('show');
};

document.addEventListener('click', function(e) {
    var menu = document.getElementById('userDropdownMenu');
    if(menu.classList.contains('show')) menu.classList.remove('show');
});
    
