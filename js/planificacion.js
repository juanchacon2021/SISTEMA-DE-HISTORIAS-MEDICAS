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
    
    // Validar cantidad de imágenes
    function validarCantidadImagenes(input) {
        const maxImagenes = 4;
        if (input.files.length > maxImagenes) {
            $('#errorImagenes').text(`Solo puedes subir un máximo de ${maxImagenes} imágenes.`);
            input.value = ''; // Limpia el input
        } else {
            $('#errorImagenes').text('');
        }
    }
    
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
                        console.log(publicacion);
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
                            <center><img src="${publicacion.imagen}" class="img-fluid rounded" style="max-height: 300px;"></center>
                          </div>`;
        }
        
        let accionesHTML = '';
        if (publicacion.cedula_p == $('#usuarioActual').val()) { // Asume que hay un input hidden con el usuario actual
            accionesHTML = `<div class="d-flex justify-content-end mt-2">
                              <button class="btn btn-sm btn-primary me-2 btn-editar" 
                                    data-id="${publicacion.cod_pub}">
                                    <img src="img/lapiz.svg" alt="Editar" width="16">
                              </button>
                              <button class="btn btn-sm btn-danger btn-eliminar" 
                                    data-id="${publicacion.cod_pub}">
                                    <img src="img/basura.svg" alt="Eliminar" width="16">
                              </button>
                           </div>`;
        }
        
        return `<div class="card-pub" id="publicacion-${publicacion.cod_pub}">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                <img src="img/user-1.svg" alt="Usuario" class="rounded-circle" width="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h1 class="font-bold">${publicacion.nombre} ${publicacion.apellido}</h1>
                                <small class="text-sm">${formatFecha(publicacion.fecha)}</small>
                            </div>
                            ${accionesHTML}
                        </div>
                        <p class="card-text">${publicacion.contenido || ''}</p>
                        ${imagenHTML}
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
        $('#contenidoMensaje').html(mensaje); // Inserta el mensaje en el cuerpo del modal
        $('#modalMensaje').modal('show'); // Muestra el modal
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