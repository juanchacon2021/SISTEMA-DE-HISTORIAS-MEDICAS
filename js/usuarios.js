// Variables globales
var modoActual = '';
var idActual = '';
var tablaUsuarios = null;
var tablaRoles = null;

$(document).ready(function() {
    // Inicializar tabs
    $('#usuariosTabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Cargar datos iniciales
    cargarUsuarios();
    cargarRoles();
    cargarSelectRoles();
    cargarModulos();

    // Eventos para el modal de usuarios
    $('#btnGuardarUsuario').click(function() {
        guardarUsuario();
    });

    // Eventos para el modal de roles
    $('#btnGuardarRol').click(function() {
        guardarRol();
    });

    // Evento para confirmación de eliminación
    $('#btnConfirmarEliminar').click(function() {
        if(modoActual == 'usuario') {
            eliminarUsuarioConfirmado();
        } else if(modoActual == 'rol') {
            eliminarRolConfirmado();
        }
    });

    // Validaciones de formulario
    $("#nombreUsuario").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombreUsuario").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombreUsuario"), "Solo letras entre 3 y 50 caracteres");
    });

    $("#emailUsuario").on("keyup", function() {
        validarkeyup(/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/, $(this), $("#semailUsuario"), "Ingrese un email válido");
    });

    $("#passwordUsuario").on("keyup", function() {
        if($(this).val().length > 0) {
            validarkeyup(/^[A-Za-z0-9]{7,15}$/, $(this), $("#spasswordUsuario"), "Solo letras y números entre 7 y 15 caracteres");
        } else {
            $("#spasswordUsuario").text("");
        }
    });
});

// Funciones para usuarios
function cargarUsuarios() {
    var datos = new FormData();
    datos.append('accion', 'consultar_usuarios');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            if(tablaUsuarios != null) {
                tablaUsuarios.destroy();
            }
            
            $('#resultadoUsuarios').html('');
            
            respuesta.datos.forEach(function(usuario) {
                var fila = `
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class='btn btn-sm btn-primary mr-1' onclick='editarUsuario(${JSON.stringify(usuario)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("usuario", "${usuario.id}")'>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                    <td>${usuario.id}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.rol_nombre}</td>
                    <td>${usuario.fecha_creacion}</td>
                    <td><span class="badge badge-success">Activo</span></td>
                </tr>`;
                $('#resultadoUsuarios').append(fila);
            });
            
            tablaUsuarios = $('#tablaUsuarios').DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontraron usuarios",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay usuarios registrados",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior",
                    },
                },
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ]
            });
        }
    });
}

function mostrarModalUsuario(modo, datos = null) {
    modoActual = modo;
    $('#accionUsuario').val(modo == 'incluir' ? 'incluir_usuario' : 'modificar_usuario');
    $('#modalUsuarioLabel').text(modo == 'incluir' ? 'Registrar Usuario' : 'Editar Usuario');
    $('#btnGuardarUsuario').text(modo == 'incluir' ? 'Registrar' : 'Actualizar');
    
    if(modo == 'incluir') {
        $('#formUsuario')[0].reset();
        $('#idUsuario').val('');
        $('#snombreUsuario, #semailUsuario, #spasswordUsuario').text('');
    } else {
        // Llenar formulario con datos
        $('#idUsuario').val(datos.id);
        $('#nombreUsuario').val(datos.nombre);
        $('#emailUsuario').val(datos.email);
        $('#passwordUsuario').val('');
        $('#rolUsuario').val(datos.rol_id);
    }
    
    $('#modalUsuario').modal('show');
}

function guardarUsuario() {
    if(!validarFormularioUsuario()) {
        return;
    }

    var datos = new FormData($('#formUsuario')[0]);
    datos.append('accion', $('#accionUsuario').val());
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        if(respuesta.resultado != 'error') {
            $('#modalUsuario').modal('hide');
            cargarUsuarios();
        }
    });
}

function editarUsuario(usuario) {
    mostrarModalUsuario('editar', usuario);
}

function confirmarEliminar(tipo, id) {
    modoActual = tipo;
    idActual = id;
    
    if(tipo == 'usuario') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este usuario?<br>Esta acción no se puede deshacer.');
    } else if(tipo == 'rol') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este rol?<br>Solo se puede eliminar si no tiene usuarios asignados.');
    }
    
    $('#modalConfirmacion').modal('show');
}

function eliminarUsuarioConfirmado() {
    var datos = new FormData();
    datos.append('accion', 'eliminar_usuario');
    datos.append('id', idActual);
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        $('#modalConfirmacion').modal('hide');
        cargarUsuarios();
    });
}

// Funciones para roles
function cargarRoles() {
    var datos = new FormData();
    datos.append('accion', 'consultar_roles');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            if(tablaRoles != null) {
                tablaRoles.destroy();
            }
            
            $('#resultadoRoles').html('');
            
            respuesta.datos.forEach(function(rol) {
                var fila = `
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class='btn btn-sm btn-primary mr-1' onclick='editarRol(${JSON.stringify(rol)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("rol", "${rol.id}")'>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                    <td>${rol.id}</td>
                    <td>${rol.nombre}</td>
                    <td>${rol.descripcion || 'N/A'}</td>
                </tr>`;
                $('#resultadoRoles').append(fila);
            });
            
            tablaRoles = $('#tablaRoles').DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontraron roles",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay roles registrados",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior",
                    },
                },
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });
        }
    });
}

function mostrarModalRol(modo, datos = null) {
    modoActual = modo;
    $('#accionRol').val(modo == 'incluir' ? 'incluir_rol' : 'modificar_rol');
    $('#modalRolLabel').text(modo == 'incluir' ? 'Registrar Rol' : 'Editar Rol');
    $('#btnGuardarRol').text(modo == 'incluir' ? 'Registrar' : 'Actualizar');
    
    if(modo == 'incluir') {
        $('#formRol')[0].reset();
        $('#idRol').val('');
    } else {
        // Llenar formulario con datos
        $('#idRol').val(datos.id);
        $('#nombreRol').val(datos.nombre);
        $('#descripcionRol').val(datos.descripcion);
    }
    
    $('#modalRol').modal('show');
}

function editarRol(rol) {
    mostrarModalRol('editar', rol);
}

function guardarRol() {
    if(!validarFormularioRol()) {
        return;
    }

    var datos = new FormData($('#formRol')[0]);
    datos.append('accion', $('#accionRol').val());
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        if(respuesta.resultado != 'error') {
            $('#modalRol').modal('hide');
            cargarRoles();
            cargarSelectRoles(); // Recargar roles en los selects
        }
    });
}

function eliminarRolConfirmado() {
    var datos = new FormData();
    datos.append('accion', 'eliminar_rol');
    datos.append('id', idActual);
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        $('#modalConfirmacion').modal('hide');
        cargarRoles();
        cargarSelectRoles(); // Recargar roles en los selects
    });
}

// Funciones para permisos
// Funciones para permisos
function cargarModulos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_modulos');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            var lista = $('#listaModulos');
            lista.empty();
            
            respuesta.datos.forEach(function(modulo) {
                lista.append(`
                <div class="list-group-item">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input modulo-check" 
                               id="modulo_${modulo.id}" value="${modulo.id}">
                        <label class="form-check-label" for="modulo_${modulo.id}">
                            <strong>${modulo.nombre}</strong> - ${modulo.descripcion}
                        </label>
                    </div>
                </div>`);
            });
            
            // Deshabilitar botón guardar inicialmente
            $('#btnGuardarPermisos').prop('disabled', true);
        }
    });
}

function cargarPermisos() {
    var rol_id = $('#selectRolPermisos').val();
    
    // Habilitar/deshabilitar botón guardar según selección
    if(rol_id) {
        $('#btnGuardarPermisos').prop('disabled', false);
    } else {
        $('#btnGuardarPermisos').prop('disabled', true);
    }
    
    if(!rol_id) {
        // Desmarcar todos si no hay rol seleccionado
        $('.modulo-check').prop('checked', false).prop('disabled', true);
        return;
    }
    
    var datos = new FormData();
    datos.append('accion', 'consultar_permisos_rol');
    datos.append('rol_id', rol_id);
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            // Desmarcar todos los checkboxes primero
            $('.modulo-check').prop('checked', false);
            
            // Marcar los módulos a los que tiene permiso el rol
            respuesta.datos.forEach(function(modulo_id) {
                $(`#modulo_${modulo_id}`).prop('checked', true);
            });
            
            // Habilitar/deshabilitar checkboxes según el rol seleccionado
            if(rol_id == 3) { // Rol admin (todos los permisos)
                $('.modulo-check').prop('disabled', true).prop('checked', true);
                $('#btnGuardarPermisos').prop('disabled', true);
                muestraMensaje('El rol Administrador tiene todos los permisos y no puede ser modificado', 'info');
            } else {
                $('.modulo-check').prop('disabled', false);
                $('#btnGuardarPermisos').prop('disabled', false);
            }
        }
    });
}

function guardarPermisos() {
    var rol_id = $('#selectRolPermisos').val();
    if(!rol_id || rol_id == 3) {
        muestraMensaje('Seleccione un rol válido para asignar permisos');
        return;
    }
    
    // Mostrar confirmación antes de guardar
   
    
    var modulos = [];
    $('.modulo-check:checked').each(function() {
        modulos.push($(this).val());
    });
    
    var datos = new FormData();
    datos.append('accion', 'actualizar_permisos');
    datos.append('rol_id', rol_id);
    datos.append('modulos', JSON.stringify(modulos));
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        
        // Recargar los permisos para verificar los cambios
        if(respuesta.resultado != 'error') {
            cargarPermisos();
        }
    });
}

// Funciones auxiliares
function cargarSelectRoles() {
    var datos = new FormData();
    datos.append('accion', 'obtener_roles_select');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            // Para el select de usuarios
            var selectUsuario = $('#rolUsuario');
            selectUsuario.empty().append('<option value="">Seleccione un rol</option>');
            
            // Para el select de permisos
            var selectPermisos = $('#selectRolPermisos');
            selectPermisos.empty().append('<option value="">-- Seleccione --</option>');
            
            respuesta.datos.forEach(function(rol) {
                selectUsuario.append($('<option>', {
                    value: rol.id,
                    text: rol.nombre
                }));
                
                selectPermisos.append($('<option>', {
                    value: rol.id,
                    text: rol.nombre
                }));
            });
        }
    });
}

function validarFormularioUsuario() {
    let valido = true;
    
    if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombreUsuario"), $("#snombreUsuario"), "Solo letras entre 3 y 50 caracteres") == 0){
        valido = false;
    }
    
    if(validarkeyup(/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/, $("#emailUsuario"), $("#semailUsuario"), "Ingrese un email válido") == 0){
        valido = false;
    }
    
    if($("#passwordUsuario").val().length > 0 && 
       validarkeyup(/^[A-Za-z0-9]{7,15}$/, $("#passwordUsuario"), $("#spasswordUsuario"), "Solo letras y números entre 7 y 15 caracteres") == 0){
        valido = false;
    }
    
    if($("#rolUsuario").val() == ""){
        muestraMensaje("Debe seleccionar un rol");
        valido = false;
    }
    
    return valido;
}

function validarFormularioRol() {
    if($("#nombreRol").val().trim() == ""){
        muestraMensaje("Debe ingresar el nombre del rol");
        return false;
    }
    
    return true;
}

// Funciones de validación (manteniendo tu estilo original)
function validarkeypress(er, e) {
    const key = e.keyCode || e.which;
    const tecla = String.fromCharCode(key);
    const a = er.test(tecla);
    
    if(!a){
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    const a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function enviaAjax(datos, callback) {
    $.ajax({
        async: true,
        url: '',
        type: 'POST',
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        beforeSend: function() {
            // Mostrar loader si es necesario
        },
        success: function(respuesta) {
            try {
                const json = JSON.parse(respuesta);
                if(typeof callback === 'function') {
                    callback(json);
                }
            } catch(e) {
                console.error('Error parsing JSON:', e);
                muestraMensaje('Error al procesar la respuesta del servidor');
            }
        },
        error: function(xhr, status, err) {
            if (status == "timeout") {
                muestraMensaje("Servidor ocupado, intente de nuevo");
            } else {
                muestraMensaje("Error en la solicitud: " + err);
            }
        }
    });
}

function muestraMensaje(mensaje, tipo = 'error') {
    const modal = $('#mostrarmodal');
    const contenido = $('#contenidodemodal');
    
    contenido.html(mensaje);
    
    if(tipo == 'error') {
        modal.find('.modal-header').removeClass('bg-success').addClass('bg-danger');
    } else {
        modal.find('.modal-header').removeClass('bg-danger').addClass('bg-success');
    }
    
    modal.modal('show');
    
    setTimeout(function() {
        modal.modal('hide');
    }, 5000);
}

function limpia() {
    $('#formUsuario')[0].reset();
    $('#formRol')[0].reset();
    $('.invalid-feedback').text('');
}