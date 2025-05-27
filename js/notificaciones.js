/**
 * Función para cargar notificaciones desde el servidor
 */
function cargarNotificaciones() {
    console.log("Iniciando carga de notificaciones...");
    
    fetch('controlador/notificaciones.php?accion=obtener_notificaciones&t=' + Date.now(), {
        cache: 'no-store'
    })
    .then(response => {
        if(!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Datos recibidos:", data);
        
        if(data.resultado === 'ok') {
            mostrarNotificaciones(data);
        } else {
            mostrarErrorNotificaciones(data.mensaje || 'Error desconocido');
        }
    })
    .catch(error => {
        console.error("Error al cargar notificaciones:", error);
        mostrarErrorNotificaciones('Error de conexión con el servidor');
    });
}

/**
 * Muestra las notificaciones en la interfaz
 */
function mostrarNotificaciones(data) {
    const contador = document.getElementById('contadorNotificaciones');
    const lista = document.getElementById('listaNotificaciones');
    
    // Limpiar lista actual
    lista.innerHTML = '<li><h6 class="dropdown-header">Notificaciones recientes</h6></li>';
    
    if(!data.datos || data.datos.length === 0) {
        lista.innerHTML += '<li><div class="dropdown-item text-center py-3">No hay notificaciones</div></li>';
        contador.classList.add('d-none');
        return;
    }
    
    // Agregar cada notificación
    data.datos.forEach(notif => {
        const fecha = new Date(notif.fecha_hora).toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const item = document.createElement('li');
        item.innerHTML = `
            <a class="dropdown-item">
                <div class="d-flex justify-content-between small text-muted">
                    <span>${notif.modulo || 'Sistema'}</span>
                    <span>${fecha}</span>
                </div>
                <div class="mt-1">
                    <strong>${notif.accion}:</strong> ${notif.descripcion}
                </div>
            </a>
        `;
        lista.appendChild(item);
    });
    
    // Agregar pie
    const verTodas = document.createElement('li');
    verTodas.innerHTML = '<hr class="dropdown-divider"><li><a class="dropdown-item text-center small" href="?pagina=bitacora">Ver todas</a></li>';
    lista.appendChild(verTodas);
    
    // Actualizar contador
    contador.textContent = data.total;
    contador.classList.remove('d-none');
}

/**
 * Muestra un mensaje de error en el panel de notificaciones
 */
function mostrarErrorNotificaciones(mensaje) {
    const lista = document.getElementById('listaNotificaciones');
    lista.innerHTML = `
        <li><h6 class="dropdown-header">Error</h6></li>
        <li><div class="dropdown-item text-center text-danger py-3">${mensaje}</div></li>
    `;
    document.getElementById('contadorNotificaciones').classList.add('d-none');
}

// Cargar notificaciones al iniciar y cada 60 segundos
document.addEventListener('DOMContentLoaded', () => {
    cargarNotificaciones();
    setInterval(cargarNotificaciones, 60000);
});

// Recargar al hacer clic en el icono de notificaciones
document.getElementById('notificacionesDropdown').addEventListener('click', (e) => {
    e.preventDefault();
    cargarNotificaciones();
});