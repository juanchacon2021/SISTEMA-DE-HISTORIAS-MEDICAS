$(document).ready(function() {
    function cargarNotificaciones() {
        $.ajax({
            url: 'controlador/notificaciones.php',
            type: 'POST',
            data: { accion: 'obtener_notificaciones' },
            dataType: 'json',
            success: function(res) {
                if(res.resultado === 'exito') {
                    let html = '';
                    let count = res.datos.length;
                    res.datos.forEach(function(n) {
                        html += `<div class="dropdown-item notificacion-item">${n.descripcion}</div>`;
                    });
                    $("#listaNotificaciones").html(html);
                    if(count > 0) {
                        $("#notificacionCounter").text(count).show();
                    } else {
                        $("#notificacionCounter").hide();
                    }
                } else {
                    $("#listaNotificaciones").html('<div class="dropdown-item">No hay notificaciones</div>');
                    $("#notificacionCounter").hide();
                }
            }
        });
    }

    // Mostrar/ocultar el dropdown
    $("#btnMostrarNotificaciones").on('click', function(e) {
        e.stopPropagation();
        $("#notificacionesDropdown").toggle();
        cargarNotificaciones();
    });

    // Ocultar el dropdown al hacer click fuera
    $(document).on('click', function() {
        $("#notificacionesDropdown").hide();
    });
});