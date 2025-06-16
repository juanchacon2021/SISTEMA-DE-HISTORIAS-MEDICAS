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
                let cedulaActual = res.cedula_actual; // <-- la cédula del usuario logueado
                res.datos.forEach(function(pub) {
                    html += `<div class="publicacion cardd mb-3" style="padding:30px;">
                        <div class="card-body">
                            <h5 class="card-title mb-1"><strong>${pub.nombre_usuario || ''}</strong></h5>
                            <h6 class="card-subtitle mb-2 text-muted">${pub.fecha}</h6>
                            <p class="card-text">${pub.contenido}</p>
                            ${pub.imagen ? `<img src="${pub.imagen}" class="img-fluid rounded mb-2" style="max-width:800px; max-height:400px;">` : ''}
                            <div class="d-flex gap-2">`;
                    // Mostrar botones solo si la publicación es del usuario logueado
                    if (pub.cedula_personal == cedulaActual) {
                        html += `
                            <button class="btn btn-sm btn-warning me-2" onclick="editarPublicacion('${pub.cod_pub}')">Modificar</button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarPublicacion('${pub.cod_pub}')">Eliminar</button>
                        `;
                    }
                    html += `</div></div></div>`;
                });
                $("#listadoPublicaciones").html(html);
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
    window.scrollTo(0, 0);
}

// Ocultar formulario de publicación
function ocultarFormularioPublicacion() {
    $("#formPublicacion").hide();
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
    
    $.ajax({
        url: '', 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "incluir_publicacion" || lee.resultado == "modificar_publicacion") {
                    muestraMensaje(lee.mensaje);
                    ocultarFormularioPublicacion();
                    // Actualizar las publicaciones inmediatamente después de la acción
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
        }
    });
});

function confirmarEliminacion(cod_pub) {
    codPublicacionAEliminar = cod_pub;
    $('#modalConfirmacion').modal('show');
}

// Eliminar publicación confirmada
$('#btnConfirmarEliminar').off('click').on('click', function() {
    if (codPublicacionAEliminar) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'eliminar_publicacion',
                cod_pub: codPublicacionAEliminar
            },
            success: function(respuesta) {
                const data = JSON.parse(respuesta);
                if(data.resultado === 'eliminar_publicacion') {
                    cargarPublicaciones(); // Recargar listado
                    muestraMensaje('Publicación eliminada');
                } else {
                    muestraMensaje(data.mensaje || 'Error al eliminar');
                }
                $('#modalConfirmacion').modal('hide');
                codPublicacionAEliminar = null;
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