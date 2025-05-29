$(document).ready(function() {
    function cargarNotificaciones() {
        var datos = new FormData();
        datos.append('accion', 'obtener_notificaciones');
        $.ajax({
            url: 'controlador/notificaciones.php',
            type: 'POST',
            data: datos,
            contentType: false,
            processData: false,
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
            },
            error: function(xhr, status, error) {
                $("#listaNotificaciones").html('<div class="dropdown-item text-danger">Error al cargar notificaciones</div>');
                $("#notificacionCounter").hide();
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