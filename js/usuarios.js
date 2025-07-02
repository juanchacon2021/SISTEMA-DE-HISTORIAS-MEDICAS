// Variables globales
var modoActual = '';
var idActual = '';
var tablaUsuarios = null;
var tablaRoles = null;
var tablaModulos = null;

$(document).ready(function() {
    // Inicializar tabs
    $('#usuariosTabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Cargar datos iniciales
    cargarUsuarios();
    cargarRoles();
    cargarModulosTabla();
    cargarSelectRoles();
    cargarModulos();
    cargarSelectPersonal();
    // Eventos para el modal de usuarios
    $('#btnGuardarUsuario').click(function() {
        guardarUsuario();
    });

    // Eventos para el modal de roles
    $('#btnGuardarRol').click(function() {
        guardarRol();
    });

    // Eventos para el modal de módulos
    $('#btnGuardarModulo').click(function() {
        guardarModulo();
    });

    // Evento para confirmación de eliminación
    $('#btnConfirmarEliminar').click(function() {
        if(modoActual == 'usuario') {
            eliminarUsuarioConfirmado();
        } else if(modoActual == 'rol') {
            eliminarRolConfirmado();
        } else if(modoActual == 'modulo') {
            eliminarModuloConfirmado();
        }
    });

    // Validaciones de formulario
    $("#nombreUsuario").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombreUsuario").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombreUsuario"), "Solo letras entre 3 y 50 caracteres");
    });

    $("#cedulaUsuario").on("keypress", function(e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#cedulaUsuario").on("keyup", function() {
        validarkeyup(/^[0-9]{6,10}$/, $(this), $("#scedulaUsuario"), "Solo números entre 6 y 10 dígitos");
    });

    $("#passwordUsuario").on("keyup", function() {
        if($(this).val().length > 0) {
            validarkeyup(/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,}$/, $(this), $("#spasswordUsuario"), "Mínimo 8 caracteres, puede incluir símbolos");
        } else {
            $("#spasswordUsuario").text("");
        }
    });

    // Vista previa de la foto de perfil
    $('#fotoPerfil').change(function() {
        var file = this.files[0];
        if(file) {
            // Validar tamaño (50MB máximo)
            if(file.size > 52428800) {
                muestraMensaje('El archivo es demasiado grande (máximo 50MB)', 'error');
                $(this).val('');
                return;
            }
            
            // Mostrar vista previa
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#fotoPreview').attr('src', e.target.result);
                $('#previewFoto').show();
            }
            reader.readAsDataURL(file);
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
                var foto = usuario.foto_perfil ? 
                    `<img src="img/perfiles/${usuario.foto_perfil}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">` : 
                    '<i class="fas fa-user-circle fa-2x"></i>';
                
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
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ml-2">${usuario.nombre}</div>
                        </div>
                    </td>
                    <td>${usuario.cedula}</td>
                    <td>${usuario.rol_nombre}</td>
                    <td>${usuario.fecha_creacion}</td>
                    <td><span class="badge badge-success" style="color:black;">Activo</span></td>
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
            $('#fotoActual').val('');
            $('#previewFoto').hide();
            $('#fotoPerfil').val('');
            $('#fotoPerfilLabel').text('Seleccionar archivo (máx. 50MB)');
            $('.invalid-feedback').text('');
        } else {
            // Llenar formulario con datos
            $('#idUsuario').val(datos.id);
            $('#nombreUsuario').val(datos.nombre);
            $('#cedulaUsuario').val(datos.cedula);
            $('#passwordUsuario').val('');
            $('#rolUsuario').val(datos.rol_id);
            
            if(datos.foto_perfil) {
                $('#fotoActual').val(datos.foto_perfil);
                $('#fotoPreview').attr('src', 'img/perfiles/' + datos.foto_perfil);
                $('#previewFoto').show();
            } else {
                $('#fotoActual').val('');
                $('#previewFoto').hide();
            }
            $('#fotoPerfil').val('');
            $('#fotoPerfilLabel').text('Cambiar foto...');
}
    
    $('#modalUsuario').modal('show');
}

function guardarUsuario() {
    if(!validarFormularioUsuario()) {
        return;
    }

    // Si hay una foto nueva, subirla primero
    if($('#fotoPerfil')[0].files.length > 0) {
        $('#btnGuardarUsuario').html('<i class="fas fa-spinner fa-spin"></i> Subiendo foto...').prop('disabled', true);
        
        var formData = new FormData();
        formData.append('accion', 'subir_foto');
        formData.append('foto_perfil', $('#fotoPerfil')[0].files[0]);
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(respuesta) {
                try {
                    var json = JSON.parse(respuesta);
                    if(json.resultado === 'exito') {
                        // Continuar con el guardado del usuario
                        guardarUsuarioDatos(json.nombre_archivo);
                    } else {
                        muestraMensaje(json.mensaje, 'error');
                        $('#btnGuardarUsuario').html(modoActual == 'incluir' ? 'Registrar' : 'Actualizar').prop('disabled', false);
                    }
                } catch(e) {
                    muestraMensaje('Error al procesar la respuesta', 'error');
                    $('#btnGuardarUsuario').html(modoActual == 'incluir' ? 'Registrar' : 'Actualizar').prop('disabled', false);
                }
            },
            error: function() {
                muestraMensaje('Error en la conexión', 'error');
                $('#btnGuardarUsuario').html(modoActual == 'incluir' ? 'Registrar' : 'Actualizar').prop('disabled', false);
            }
        });
    } else {
        // No hay foto nueva, guardar directamente
        guardarUsuarioDatos($('#fotoActual').val());
    }
}

function guardarUsuarioDatos(nombreFoto) {
    var datos = new FormData($('#formUsuario')[0]);
    datos.append('accion', $('#accionUsuario').val());
    if(nombreFoto) {
        datos.append('foto_perfil', nombreFoto);
    }
    
    enviaAjax(datos, function(respuesta) {
        $('#btnGuardarUsuario').html(modoActual == 'incluir' ? 'Registrar' : 'Actualizar').prop('disabled', false);
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

function eliminarFoto() {
    if(!confirm('¿Está seguro de eliminar la foto de perfil?')) return;
    
    var datos = new FormData();
    datos.append('accion', 'eliminar_foto');
    datos.append('id', $('#idUsuario').val());
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'exito') {
            $('#fotoActual').val('');
            $('#previewFoto').hide();
            $('#fotoPerfil').val('');
            $('#fotoPerfilLabel').text('Seleccionar archivo (máx. 50MB)');
            muestraMensaje(respuesta.mensaje, 'success');
        } else {
            muestraMensaje(respuesta.mensaje, 'error');
        }
    });
}

function confirmarEliminar(tipo, id) {
    modoActual = tipo;
    idActual = id;
    
    if(tipo == 'usuario') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este usuario?<br>Esta acción no se puede deshacer.');
    } else if(tipo == 'rol') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este rol?<br>Solo se puede eliminar si no tiene usuarios asignados.');
    } else if(tipo == 'modulo') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este módulo?<br>Esta acción afectará los permisos asignados.');
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

// Funciones para módulos
function cargarModulosTabla() {
    var datos = new FormData();
    datos.append('accion', 'consultar_modulos_tabla');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            if(tablaModulos != null) {
                tablaModulos.destroy();
            }
            
            $('#resultadoModulos').html('');
            
            respuesta.datos.forEach(function(modulo) {
                var fila = `
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class='btn btn-sm btn-primary mr-1' onclick='editarModulo(${JSON.stringify(modulo)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("modulo", "${modulo.id}")'>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                    <td>${modulo.id}</td>
                    <td>${modulo.nombre}</td>
                    <td>${modulo.descripcion || 'N/A'}</td>
                </tr>`;
                $('#resultadoModulos').append(fila);
            });
            
            tablaModulos = $('#tablaModulos').DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontraron módulos",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay módulos registrados",
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

function mostrarModalModulo(modo, datos = null) {
    modoActual = modo;
    $('#accionModulo').val(modo == 'incluir' ? 'incluir_modulo' : 'modificar_modulo');
    $('#modalModuloLabel').text(modo == 'incluir' ? 'Registrar Módulo' : 'Editar Módulo');
    $('#btnGuardarModulo').text(modo == 'incluir' ? 'Registrar' : 'Actualizar');
    
    if(modo == 'incluir') {
        $('#formModulo')[0].reset();
        $('#idModulo').val('');
    } else {
        // Llenar formulario con datos
        $('#idModulo').val(datos.id);
        $('#nombreModulo').val(datos.nombre);
        $('#descripcionModulo').val(datos.descripcion);
    }
    
    $('#modalModulo').modal('show');
}

function editarModulo(modulo) {
    mostrarModalModulo('editar', modulo);
}

function guardarModulo() {
    if(!validarFormularioModulo()) {
        return;
    }

    var datos = new FormData($('#formModulo')[0]);
    datos.append('accion', $('#accionModulo').val());
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        if(respuesta.resultado != 'error') {
            $('#modalModulo').modal('hide');
            cargarModulosTabla();
            cargarModulos(); // Recargar módulos en la pestaña de permisos
        }
    });
}

function eliminarModuloConfirmado() {
    var datos = new FormData();
    datos.append('accion', 'eliminar_modulo');
    datos.append('id', idActual);
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        $('#modalConfirmacion').modal('hide');
        cargarModulosTabla();
        cargarModulos(); // Recargar módulos en la pestaña de permisos
    });
}

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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${modulo.nombre}</strong> - ${modulo.descripcion}
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input permiso-check" 
                                    id="modulo_${modulo.id}_registrar" value="${modulo.id}" data-tipo="registrar">
                                <label class="form-check-label small" for="modulo_${modulo.id}_registrar">Registrar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input permiso-check" 
                                    id="modulo_${modulo.id}_modificar" value="${modulo.id}" data-tipo="modificar">
                                <label class="form-check-label small" for="modulo_${modulo.id}_modificar">Modificar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input permiso-check" 
                                    id="modulo_${modulo.id}_eliminar" value="${modulo.id}" data-tipo="eliminar">
                                <label class="form-check-label small" for="modulo_${modulo.id}_eliminar">Eliminar</label>
                            </div>
                        </div>
                    </div>`);
                });
                
                $('#btnGuardarPermisos').prop('disabled', true);
            }
        });
    }

    
function cargarPermisos() {
    var rol_id = $('#selectRolPermisos').val();
    
    if(rol_id) {
        $('#btnGuardarPermisos').prop('disabled', false);
    } else {
        $('#btnGuardarPermisos').prop('disabled', true);
    }
    
    if(!rol_id) {
        $('.permiso-check').prop('checked', false).prop('disabled', true);
        return;
    }
    
    var datos = new FormData();
    datos.append('accion', 'consultar_permisos_rol');
    datos.append('rol_id', rol_id);
    
    enviaAjax(datos, function(respuesta) {
        // Desmarcar todos los checkboxes primero
        $('.permiso-check').prop('checked', false);
        
        // Marcar los permisos que tiene el rol
        respuesta.datos.forEach(function(permiso) {
            $(`#modulo_${permiso.modulo_id}_registrar`).prop('checked', permiso.registrar == 1);
            $(`#modulo_${permiso.modulo_id}_modificar`).prop('checked', permiso.modificar == 1);
            $(`#modulo_${permiso.modulo_id}_eliminar`).prop('checked', permiso.eliminar == 1);
        });
        
        // Habilitar/deshabilitar según el rol seleccionado
        if(rol_id == 3) { // Rol admin (todos los permisos)
            $('.permiso-check').prop('disabled', true).prop('checked', true);
            $('#btnGuardarPermisos').prop('disabled', true);
            muestraMensaje('El rol Administrador tiene todos los permisos y no puede ser modificado', 'info');
        } else {
            $('.permiso-check').prop('disabled', false);
            $('#btnGuardarPermisos').prop('disabled', false);
        }
    });
}

function guardarPermisos() {
    var rol_id = $('#selectRolPermisos').val();
    if(!rol_id || rol_id == 3) {
        muestraMensaje('Seleccione un rol válido para asignar permisos');
        return;
    }
    
    if(!confirm('¿Está seguro de actualizar los permisos para este rol?')) {
        return;
    }
    
    var permisos = [];
    $('.permiso-check:checked').each(function() {
        var modulo_id = $(this).val();
        var tipo = $(this).data('tipo');
        
        permisos.push({
            modulo_id: modulo_id,
            tipo: tipo
        });
    });
    
    var datos = new FormData();
    datos.append('accion', 'actualizar_permisos');
    datos.append('rol_id', rol_id);
    datos.append('permisos', JSON.stringify(permisos));
    
    $('#btnGuardarPermisos').html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
    
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                var json = JSON.parse(respuesta);
                if(json.resultado === 'exito') {
                    muestraMensaje(json.mensaje, 'success');
                    cargarPermisos();
                } else {
                    muestraMensaje(json.mensaje, 'error');
                }
            } catch(e) {
                console.error('Error parsing JSON:', e, 'Response:', respuesta);
                muestraMensaje('Error al interpretar la respuesta del servidor', 'error');
            }
        },
        error: function(xhr, status, error) {
            muestraMensaje('Error en la conexión: ' + error, 'error');
        },
        complete: function() {
            $('#btnGuardarPermisos').html('<i class="fas fa-save mr-1"></i> Guardar Permisos').prop('disabled', false);
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

function cargarSelectPersonal() {
    var datos = new FormData();
    datos.append('accion', 'obtener_personal');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            var select = $('#cedulaUsuario');
            select.empty().append('<option value="">Seleccione un personal</option>');
            
            respuesta.datos.forEach(function(personal) {
                select.append($('<option>', {
                    value: personal.cedula_personal,
                    text: personal.nombre_completo + ' (' + personal.cedula_personal + ')'
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
    
    $("#cedulaUsuario").on("change", function() { if($(this).val() === "") { $("#scedulaUsuario").text("Debe seleccionar un personal");
        } else {
            $("#scedulaUsuario").text("");
        }
    });    
    
    if($("#passwordUsuario").val().length > 0 && 
       validarkeyup(/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,}$/, $("#passwordUsuario"), $("#spasswordUsuario"), "Mínimo 8 caracteres, puede incluir símbolos") == 0){
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

function validarFormularioModulo() {
    if($("#nombreModulo").val().trim() == ""){
        muestraMensaje("Debe ingresar el nombre del módulo");
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
            console.log(datos);
        },
        
        success: function(respuesta) {
            console.log(respuesta);
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
    $('#formModulo')[0].reset();
    $('.invalid-feedback').text('');
}

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