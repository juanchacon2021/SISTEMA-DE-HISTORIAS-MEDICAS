// Variables globales
var modoActual = '';
var idActual = '';
var tablaEstudiantes = null;
var tablaAreas = null;

$(document).ready(function() {
    // Inicializar tabs
    $('#pasantiasTabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Cargar datos iniciales
    cargarEstudiantes();
    cargarAreas();
    cargarSelectAreas();
    cargarSelectDoctores();

    // Eventos para el modal de estudiantes
    $('#btnGuardarEstudiante').click(function() {
        guardarEstudiante();
    });

    // Eventos para el modal de áreas
    $('#btnGuardarArea').click(function() {
        guardarArea();
    });

    // Evento para confirmación de eliminación
    $('#btnConfirmarEliminar').click(function() {
        if(modoActual == 'estudiante') {
            eliminarEstudianteConfirmado();
        } else if(modoActual == 'area') {
            eliminarAreaConfirmado();
        }
    });

    // Validaciones de formulario
    $("#cedula_estudiante").on("keypress", function(e) {
        validarkeypress(/^[0-9-\b]*$/, e);
    });

    $("#cedula_estudiante").on("keyup", function() {
        validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedula_estudiante"), "El formato debe ser 12345678");
    });

    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#apellido").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#apellido").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sapellido"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#telefono").on("keypress", function(e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#telefono").on("keyup", function() {
        validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto (ej: 04121234567)");
    });
});

// Funciones para estudiantes
function cargarEstudiantes() {
    var datos = new FormData();
    datos.append('accion', 'consultar_estudiantes');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            if(tablaEstudiantes != null) {
                tablaEstudiantes.destroy();
            }
            
            $('#resultadoEstudiantes').html('');
            
            respuesta.datos.forEach(function(estudiante) {
                var fila = `
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class='btn btn-sm btn-primary mr-1' onclick='editarEstudiante(${JSON.stringify(estudiante)})'>
                              <img src="img/lapiz.svg" style="width: 20px">
                            </button>
                            <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("estudiante", "${estudiante.cedula_estudiante}")'>
                                <img src='img/trash-can-solid.svg' style='width: 20px;'>
                            </button>
                        </div>
                    </td>
                    <td>${estudiante.cedula_estudiante}</td>
                    <td>${estudiante.apellido}</td>
                    <td>${estudiante.nombre}</td>
                    <td>${estudiante.institucion}</td>
                    <td>${estudiante.nombre_area}</td>
                    <td>${estudiante.responsable}</td>
                    <td>${estudiante.activo == 1 ? '<span class="badge badge-success" style="background-color: green;">Activo</span>' : '<span class="badge badge-secondary" style="background-color: red;">Inactivo</span>'}</td>
                </tr>`;
                $('#resultadoEstudiantes').append(fila);
            });
            
            tablaEstudiantes = $('#tablaEstudiantes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0, 7] }
                ]
            });
        }
    });
}

function mostrarModalEstudiante(modo, datos = null) {
    modoActual = modo;
    $('#accionEstudiante').val(modo == 'incluir' ? 'incluir_estudiante' : 'modificar_estudiante');
    $('#modalEstudianteLabel').text(modo == 'incluir' ? 'Registrar Estudiante' : 'Editar Estudiante');
    $('#btnGuardarEstudiante').text(modo == 'incluir' ? 'Registrar' : 'Actualizar');
    
    if(modo == 'incluir') {
        $('#formEstudiante')[0].reset();
        $('#cedula_estudiante').prop('readonly', false);
        $('#scedula_estudiante, #snombre, #sapellido, #stelefono').text('');
    } else {
        // Llenar formulario con datos
        $('#cedula_estudiante').val(datos.cedula_estudiante).prop('readonly', true);
        $('#nombre').val(datos.nombre);
        $('#apellido').val(datos.apellido);
        $('#institucion').val(datos.institucion);
        $('#telefono').val(datos.telefono);
        $('#cod_area').val(datos.cod_area);
        $('#fecha_inicio').val(datos.fecha_inicio);
        $('#fecha_fin').val(datos.fecha_fin);
        $('#activo').prop('checked', datos.activo == 1);
    }
    
    $('#modalEstudiante').modal('show');
}

function guardarEstudiante() {
    if(!validarFormularioEstudiante()) {
        return;
    }

    var datos = new FormData($('#formEstudiante')[0]);
    datos.append('accion', $('#accionEstudiante').val());
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        if(respuesta.resultado != 'error') {
            $('#modalEstudiante').modal('hide');
            cargarEstudiantes();
        }
    });
}

function editarEstudiante(estudiante) {
    mostrarModalEstudiante('editar', estudiante);
}

function confirmarEliminar(tipo, id) {
    modoActual = tipo;
    idActual = id;
    
    if(tipo == 'estudiante') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar este estudiante?<br>Esta acción no se puede deshacer.');
    } else if(tipo == 'area') {
        $('#mensajeConfirmacion').html('¿Está seguro de eliminar esta área?<br>Solo se puede eliminar si no tiene estudiantes asignados.');
    }
    
    $('#modalConfirmacion').modal('show');
}

function eliminarEstudianteConfirmado() {
    var datos = new FormData();
    datos.append('accion', 'eliminar_estudiante');
    datos.append('cedula_estudiante', idActual);
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        $('#modalConfirmacion').modal('hide');
        cargarEstudiantes();
    });
}

// Funciones para áreas
function cargarAreas() {
    var datos = new FormData();
    datos.append('accion', 'consultar_areas');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            if(tablaAreas != null) {
                tablaAreas.destroy();
            }
            
            $('#resultadoAreas').html('');
            
            respuesta.datos.forEach(function(area) {
                var fila = `
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class='btn btn-sm btn-primary mr-1' onclick='editarArea(${JSON.stringify(area)})'>
                               <img src="img/lapiz.svg" style="width: 20px">
                            </button>
                            <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("area", "${area.cod_area}")'>
                               <img src='img/trash-can-solid.svg' style='width: 20px;'>
                            </button>
                        </div>
                    </td>
                    <td>${area.nombre_area}</td>
                    <td>${area.descripcion || 'N/A'}</td>
                    <td>${area.responsable}</td>
                </tr>`;
                $('#resultadoAreas').append(fila);
            });
            
            tablaAreas = $('#tablaAreas').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
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

function mostrarModalArea(modo, datos = null) {
    modoActual = modo;
    $('#accionArea').val(modo == 'incluir' ? 'incluir_area' : 'modificar_area');
    $('#modalAreaLabel').text(modo == 'incluir' ? 'Registrar Área' : 'Editar Área');
    $('#btnGuardarArea').text(modo == 'incluir' ? 'Registrar' : 'Actualizar');
    
    if(modo == 'incluir') {
        $('#formArea')[0].reset();
        $('#cod_area').val('');
    } else {
        // Llenar formulario con datos
        $('#cod_area').val(datos.cod_area);
        $('#nombre_area').val(datos.nombre_area);
        $('#descripcion').val(datos.descripcion);
        $('#responsable_id').val(datos.responsable_id);
    }
    
    $('#modalArea').modal('show');
}

function editarArea(area) {
    mostrarModalArea('editar', area);
}

function guardarArea() {
    if(!validarFormularioArea()) {
        return;
    }

    var datos = new FormData($('#formArea')[0]);
    datos.append('accion', $('#accionArea').val());
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        if(respuesta.resultado != 'error') {
            $('#modalArea').modal('hide');
            cargarAreas();
            cargarSelectAreas(); // Recargar áreas en los selects
        }
    });
}

function eliminarAreaConfirmado() {
    var datos = new FormData();
    datos.append('accion', 'eliminar_area');
    datos.append('cod_area', idActual);
    
    enviaAjax(datos, function(respuesta) {
        muestraMensaje(respuesta.mensaje, respuesta.resultado == 'error' ? 'error' : 'success');
        $('#modalConfirmacion').modal('hide');
        cargarAreas();
        cargarSelectAreas(); // Recargar áreas en los selects
    });
}

// Funciones auxiliares
function cargarSelectAreas() {
    var datos = new FormData();
    datos.append('accion', 'obtener_areas_select');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            var select = $('#cod_area');
            select.empty().append('<option value="">Seleccione un área</option>');
            
            respuesta.datos.forEach(function(area) {
                select.append($('<option>', {
                    value: area.cod_area,
                    text: area.nombre_area
                }));
            });
        }
    });
}

function cargarSelectDoctores() {
    var datos = new FormData();
    datos.append('accion', 'obtener_doctores');
    
    enviaAjax(datos, function(respuesta) {
        if(respuesta.resultado == 'consultar') {
            var select = $('#responsable_id');
            select.empty().append('<option value="">Seleccione un doctor</option>');
            
            respuesta.datos.forEach(function(doctor) {
                select.append($('<option>', {
                    value: doctor.cedula_personal,
                    text: doctor.nombre_completo
                }));
            });
        }
    });
}

function validarFormularioEstudiante() {
    let valido = true;
    
    if(validarkeyup(/^[0-9]{7,8}$/, $("#cedula_estudiante"), $("#scedula_estudiante"), "El formato debe ser 12345678") == 0){
        valido = false;
    }
    
    if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0){
        valido = false;
    }
    
    if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#apellido"), $("#sapellido"), "Solo letras entre 3 y 30 caracteres") == 0){
        valido = false;
    }
    
    if($("#cod_area").val() == ""){
        muestraMensaje("Debe seleccionar un área de pasantía");
        valido = false;
    }
    
    if($("#fecha_inicio").val() == ""){
        muestraMensaje("Debe especificar la fecha de inicio");
        valido = false;
    }
    
    return valido;
}

function validarFormularioArea() {
    if($("#nombre_area").val().trim() == ""){
        muestraMensaje("Debe ingresar el nombre del área");
        return false;
    }
    
    if($("#responsable_id").val() == ""){
        muestraMensaje("Debe seleccionar un doctor responsable");
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
    $('#formEstudiante')[0].reset();
    $('#formArea')[0].reset();
    $('.invalid-feedback').text('');
}