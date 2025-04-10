
$(document).ready(function() {
    // Variables globales
    let publicacionActual = null;
    
    // Mostrar modal para nueva publicación
    $('#btnNuevaPublicacion').click(function() {
        $('#accion').val('guardarPublicacion');
        $('#cod_pub').val('');
        $('#contenido').val('');
        $('#previewImagen').html('');
        $('#tituloModal').text('Crear publicación');
        $('#modalPublicacion').modal('show');
    });
    
    // Previsualización de imagen
    $('#imagen').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImagen').html(`<img src="${e.target.result}" class="img-fluid" style="max-height: 200px;">`);
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Envío del formulario
    $('#formPublicacion').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const accion = $('#accion').val();
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.resultado === 'success') {
                    mostrarMensaje('success', response.mensaje);
                    $('#modalPublicacion').modal('hide');
                    cargarPublicaciones();
                } else {
                    mostrarMensaje('error', response.mensaje);
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error al procesar la solicitud');
            }
        });
    });
    
    // Cargar publicaciones
    function cargarPublicaciones() {
        $.ajax({
            url: '',
            type: 'POST',
            data: { accion: 'obtenerPublicaciones' },
            dataType: 'json',
            success: function(response) {
                $('#contenedorPublicaciones').html('');
                if (response.length > 0) {
                    response.forEach(function(publicacion) {
                        $('#contenedorPublicaciones').append(crearCardPublicacion(publicacion));
                    });
                } else {
                    $('#contenedorPublicaciones').html('<p class="text-center">No hay publicaciones</p>');
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error al cargar las publicaciones');
            }
        });
    }
    
    // Crear HTML para una publicación
    function crearCardPublicacion(publicacion) {
        let imagenHTML = '';
        if (publicacion.imagen) {
            imagenHTML = `<div class="text-center mt-2">
                            <img src="${publicacion.imagen}" class="img-fluid rounded" style="max-height: 300px;">
                          </div>`;
        }
        
        let accionesHTML = '';
        if (publicacion.cedula_p == $('#usuarioActual').val()) { // Asume que hay un input hidden con el usuario actual
            accionesHTML = `<div class="d-flex justify-content-end mt-2">
                              <button class="btn btn-sm btn-outline-primary me-2 btn-editar" 
                                      data-id="${publicacion.cod_pub}">
                                  Editar
                              </button>
                              <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                      data-id="${publicacion.cod_pub}">
                                  Eliminar
                              </button>
                           </div>`;
        }
        
        return `<div class="card mb-3" id="publicacion-${publicacion.cod_pub}">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                <img src="img/user-default.png" alt="Usuario" class="rounded-circle" width="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">${publicacion.nombre} ${publicacion.apellido}</h5>
                                <small class="text-muted">${formatFecha(publicacion.fecha)}</small>
                            </div>
                        </div>
                        <p class="card-text">${publicacion.contenido || ''}</p>
                        ${imagenHTML}
                        ${accionesHTML}
                    </div>
                </div>`;
    }
    
    // Formatear fecha
    function formatFecha(fechaStr) {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString() + ' ' + fecha.toLocaleTimeString();
    }
    
    // Mostrar mensajes
    function mostrarMensaje(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        $('#mensaje').html(`<div class="alert ${alertClass}">${mensaje}</div>`);
        setTimeout(() => $('#mensaje').html(''), 3000);
    }
    
    // Editar publicación
    $(document).on('click', '.btn-editar', function() {
        const codPub = $(this).data('id');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { accion: 'obtenerPublicacion', cod_pub: codPub },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    publicacionActual = response;
                    $('#accion').val('modificarPublicacion');
                    $('#cod_pub').val(response.cod_pub);
                    $('#contenido').val(response.contenido);
                    $('#tituloModal').text('Editar publicación');
                    
                    if (response.imagen) {
                        $('#previewImagen').html(`<img src="${response.imagen}" class="img-fluid" style="max-height: 200px;">`);
                    } else {
                        $('#previewImagen').html('');
                    }
                    
                    $('#modalPublicacion').modal('show');
                } else {
                    mostrarMensaje('error', 'No se pudo cargar la publicación');
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error al cargar la publicación');
            }
        });
    });
    
    // Eliminar publicación
    $(document).on('click', '.btn-eliminar', function() {
        publicacionActual = $(this).data('id');
        $('#modalConfirmacion').modal('show');
    });
    
    $('#btnConfirmarEliminar').click(function() {
        $.ajax({
            url: '',
            type: 'POST',
            data: { accion: 'eliminarPublicacion', cod_pub: publicacionActual },
            dataType: 'json',
            success: function(response) {
                if (response.resultado === 'success') {
                    mostrarMensaje('success', response.mensaje);
                    $('#modalConfirmacion').modal('hide');
                    $(`#publicacion-${publicacionActual}`).remove();
                } else {
                    mostrarMensaje('error', response.mensaje);
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error al eliminar la publicación');
            }
        });
    });
    
    // Cargar publicaciones al iniciar
    cargarPublicaciones();
});
