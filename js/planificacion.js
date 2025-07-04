function muestraMensaje(mensaje) {
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    }, 2000);
}

console.log();

// Variable global para guardar el código a eliminar
let codPublicacionAEliminar = null;

// Función para cargar las publicaciones
function cargarPublicaciones() {
    var datos = new FormData();
    datos.append('accion', 'consultar_publicaciones');
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res.resultado === 'consultar_publicaciones') {
                let html = '';
                let cedulaActual = res.cedula_actual;
                if (!res.datos || res.datos.length === 0) {
                    html = `
                        <div class="d-flex justify-content-center align-items-center" style="height:200px;">
                            <center><div class="alert alert-danger text-center" style="font-size:1.2rem;">
                                No hay publicaciones
                            </div></center>
                        </div>
                    `;
                } else {
                    res.datos.forEach(function(pub) {
                        html += `<div class="publicacion cardd mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-between">
                                    <div>
                                        <h5 class="card-title mb-1"><strong>${pub.nombre_usuario || ''} ${pub.apellido_usuario || ''}</strong></h5>
                                        <h6 class="card-subtitle mb-2 text-muted">${pub.fecha}</h6>
                                    </div>
                                    <div class="d-flex gap-2">`;
                        if (pub.cedula_personal == cedulaActual) {
                            html += `
                                <button class="btn btn-sm btn-warning me-2" onclick="editarPublicacion('${pub.cod_pub}')" style="height:40px;">
                                    <img src="img/lapiz.svg" alt="Editar" style="width:20px;">
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion('${pub.cod_pub}')" style="height:40px;">
                                    <img src="img/basura.svg" alt="Eliminar" style="width:20px;">
                                </button>
                            `;
                        }
                        html += `</div>
                                </div>
                                <p class="card-text my-2">${pub.contenido}</p>
                                <center>${pub.imagen ? `<img src="${pub.imagen}" class="img-publicacion-ampliable" style="cursor:pointer;max-height:350px;">` : ''}</center>
                            </div>
                        </div>`;
                    });
                }
                $("#listadoPublicaciones").html(html);

                // Evento para ampliar imagen
                $(".img-publicacion-ampliable").off("click").on("click", function() {
                    $("#imagenAmpliadaPublicacion").attr("src", $(this).attr("src"));
                    $("#modalImagenPublicacion").modal("show");
                });
            }
        }
    });
}

// Mostrar formulario de publicación
function mostrarFormularioPublicacion() {
    $("#formPublicacion").show();
    $("#formPublicacionData")[0].reset();
    $("#accion").val('incluir_publicacion');
    $("#procesoPublicacion").text('Publicar');
    // Limpiar la vista previa de la imagen
    $("#imagenVistaPrevia").attr("src", "").hide();
    window.scrollTo(0, 0);
}

// Ocultar formulario de publicación
function ocultarFormularioPublicacion() {
    $("#formPublicacion").hide();
    // Limpiar la vista previa de la imagen también al ocultar
    $("#imagenVistaPrevia").attr("src", "").hide();
}

// Editar publicación
function editarPublicacion(cod_pub) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            accion: 'obtener_publicacion',
            cod_pub: cod_pub
        },
        success: function(respuesta) {
            const data = JSON.parse(respuesta);
            if(data.resultado === 'obtener_publicacion') {
                // Mostrar el formulario y cargar los datos
                mostrarFormularioPublicacion();
                $("#accion").val('modificar_publicacion');
                $("#procesoPublicacion").text('Modificar');
                $("#cod_pub").val(data.datos.cod_pub);
                $("#contenido").val(data.datos.contenido);

                // Mostrar imagen previa si existe
                if (data.datos.imagen) {
                    if ($("#imagenPrevia").length === 0) {
                        $("#imagen").after('<div id="imagenPrevia" class="mt-2"></div>');
                    }
                    $("#imagenPrevia").html(
                        `<img src="${data.datos.imagen}" alt="Imagen actual" class="img-fluid rounded" style="max-width:200px;">`
                    );
                } else {
                    $("#imagenPrevia").remove();
                }
                window.scrollTo(0, 0);
            } else {
                muestraMensaje(data.mensaje || 'Error al obtener publicación');
            }
        }
    });
}

// Confirmar eliminación
function confirmarEliminacion(cod_pub) {
    codPublicacionAEliminar = cod_pub;
    $('#modalConfirmacion').modal('show');
}

// Procesar formulario de publicación
$(document).on('click', '#procesoPublicacion', function() {
    var formData = new FormData($("#formPublicacionData")[0]);
    
    // Validar contenido
    if (!formData.get('contenido') || formData.get('contenido').trim() === '') {
        muestraMensaje("Debes escribir algo en la publicación");
        return;
    }

    // Deshabilitar botón y mostrar "Procesando..."
    $('#procesoPublicacion').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    $.ajax({
        url: '', 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "incluir" || lee.resultado == "modificar") {
                    muestraMensaje(lee.mensaje);
                    ocultarFormularioPublicacion();
                    cargarPublicaciones();
                } else {
                    muestraMensaje(lee.mensaje);
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
                muestraMensaje("Error al procesar la publicación");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición AJAX:", error);
            muestraMensaje("Error al guardar la publicación");
        },
        complete: function() {
            // Restaurar botón
            $('#procesoPublicacion').prop('disabled', false).text(
                $('#accion').val() === 'modificar_publicacion' ? 'Modificar' : 'Publicar'
            );
        }
    });
});

// Eliminar publicación confirmada
$('#btnConfirmarEliminar').off('click').on('click', function() {
    var $btn = $(this);
    if (codPublicacionAEliminar) {
        // Deshabilitar botón y mostrar spinner
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'eliminar_publicacion',
                cod_pub: codPublicacionAEliminar
            },
            success: function(respuesta) {
                const data = JSON.parse(respuesta);
                if(data.resultado === 'eliminar' || data.resultado === 'eliminar_publicacion') {
                    cargarPublicaciones(); // Recargar listado
                    muestraMensaje('Publicación eliminada');
                } else {
                    muestraMensaje(data.mensaje || 'Error al eliminar');
                }
                $('#modalConfirmacion').modal('hide');
                codPublicacionAEliminar = null;
            },
            error: function() {
                muestraMensaje('Error al eliminar');
                $('#modalConfirmacion').modal('hide');
                codPublicacionAEliminar = null;
            },
            complete: function() {
                // Restaurar botón
                $btn.prop('disabled', false).text('Eliminar');
            }
        });
    }
});

// Cargar publicaciones al iniciar
$(document).ready(function() {
    cargarPublicaciones();
    
    // Ocultar formulario al hacer clic fuera
    $(document).mouseup(function(e) {
        var container = $("#formPublicacion");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            ocultarFormularioPublicacion();
        }
    });
});

// NOTIFICACIONES
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al WebSocket');
};

ws.onclose = function() {
    console.log('WebSocket cerrado');
};

ws.onerror = function(error) {
    console.error('WebSocket error:', error);
};

// Ejemplo: enviar un mensaje (puedes quitar esto si solo quieres recibir)
function enviarNotificacion(msg) {
    ws.send(msg);
}