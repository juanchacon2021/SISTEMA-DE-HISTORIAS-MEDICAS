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
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'consultar_publicaciones' },
        success: function(respuesta) {
            try {
                const data = JSON.parse(respuesta);
                if(data.resultado === 'consultar_publicaciones') {
                    let html = '';
                    
                    data.datos.forEach(publicacion => {
                        const esPropietario = publicacion.es_propietario === 1;
                        const fecha = new Date(publicacion.fecha).toLocaleString();
                        
                        html += `
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <strong>${publicacion.nombre_usuario}</strong>
                                    <small class="text-muted">${fecha}</small>
                                </div>
                                ${esPropietario ? `
                                <div>
                                    <button onclick="editarPublicacion(${publicacion.cod_pub})" 
                                            class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmarEliminacion(${publicacion.cod_pub})" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            <div class="card-body">
                                <p>${publicacion.contenido}</p>
                                ${publicacion.imagen ? `
                                <img src="${publicacion.imagen}" class="img-fluid" alt="Imagen publicación">
                                ` : ''}
                            </div>
                        </div>
                        `;
                    });
                    
                    $('#listadoPublicaciones').html(html || '<div class="alert alert-info">No hay publicaciones</div>');
                }
            } catch(e) {
                console.error('Error procesando respuesta:', e);
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