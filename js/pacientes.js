// Variables globales
let currentStep = 1;
let familiaresTemporales = [];
let pacienteEditando = null;

// Función para consultar pacientes
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);    
}

// Función para destruir DataTable si existe
function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablapersonal")) {
        $("#tablapersonal").DataTable().destroy();
    }
}

$(document).ready(function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('accion') === 'registrar') {
        limpiarFormulario(); // Limpia el formulario
        pone(null, 3);       // Abre el modal en modo "INCLUIR"
        // Limpiar la URL
        window.history.replaceState({}, document.title, "?pagina=pacientes");
    }
});

// Función para crear DataTable
function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablapersonal")) {
        $("#tablapersonal").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró pacientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pacientes registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            autoWidth: false,
            order: [[1, "asc"]],
        });
    }         
}

// Función para inicializar validaciones
function inicializarValidaciones() {
    const validaciones = [
        { selector: "#cedula_paciente", regex: /^[0-9]{0,8}$/, errorSelector: "#scedula_paciente", errorMessage: "El formato debe ser 12345678" },
        { selector: "#apellido", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,30}$/, errorSelector: "#sapellido", errorMessage: "Solo letras entre 3 y 30 caracteres" },
        { selector: "#nombre", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,30}$/, errorSelector: "#snombre", errorMessage: "Solo letras entre 3 y 30 caracteres" },
        { 
            selector: "#telefono", 
            regex: /^[0-9]{0,11}$/, 
            errorSelector: "#stelefono", 
            errorMessage: "El formato debe ser de 11 números",
            onInput: function () {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            }
        },
        { selector: "#ocupacion", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#socupacion", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#hda", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#shda", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#habtoxico", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#shabtoxico", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#alergias", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#salergias", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#quirurgico", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#squirurgico", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#transsanguineo", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#stranssanguineo", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#alergias_med", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#salergias_med", errorMessage: "Solo letras entre 3 y 300 caracteres" },
        { selector: "#psicosocial", regex: /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{0,300}$/, errorSelector: "#spsicosocial", errorMessage: "Solo letras entre 3 e 300 caracteres" },
    ];

    validaciones.forEach(({ selector, regex, errorSelector, errorMessage, onInput }) => {
        if (onInput) {
            $(selector).on("input", onInput);
        }
        
        $(selector).on("keypress", function (e) {
            validarkeypress(regex, e);
        });

        $(selector).on("keyup", function () {
            validarkeyup(regex, $(this), $(errorSelector), errorMessage);
        });
    });
}

// Función pone() 
function pone(pos, accion) {
    const linea = $(pos).closest('tr');
    pacienteEditando = $(linea).find('.cedula_paciente').text();
    
    // Mapeo de campos
    const campos = {
        "cedula_paciente": "cedula_paciente",
        "apellido": "apellido",
        "nombre": "nombre",
        "fecha_nac": "fecha_nac",
        "edad": "edad",
        "telefono": "telefono",
        "estadocivil": "estadocivil",
        "direccion": "direccion",
        "ocupacion": "ocupacion",
        "hda": "hda",
        "habtoxico": "habtoxico",
        "alergias": "alergias",
        "alergias_med": "alergias_med",
        "quirurgico": "quirurgico",
        "transsanguineo": "transsanguineo",
        "psicosocial": "psicosocial"
    };

    // Llenar campos del formulario
    Object.entries(campos).forEach(([campo, clase]) => {
        $(`#${campo}`).val($(linea).find(`.${clase}`).text());
    });

    // Configurar acción
    if (accion === 0) {
        $("#proceso").text("MODIFICAR");
        $("#accion").val("modificar");
        cargarAntecedentes(pacienteEditando);
    } else {
        $("#proceso").text("INCLUIR");
        $("#accion").val("incluir");
        familiaresTemporales = [];
        $("#listaFamiliares").html('<div class="alert alert-info">Agregue antecedentes familiares</div>');
    }

    // Resetear pasos
    currentStep = 1;
    updateStep();
    
    // Mostrar modal
    $("#modal1").modal("show");
}

// Función para limpiar el formulario
function limpiarFormulario() {
    $("#cedula_paciente").val("");
    $("#apellido").val("");
    $("#nombre").val("");
    $("#fecha_nac").val("");
    $("#edad").val("");
    $("#telefono").val("");
    $("#estadocivil").val("");
    $("#direccion").val("");
    $("#ocupacion").val("");
    $("#hda").val("");
    $("#habtoxico").val("");
    $("#alergias").val("");
    $("#alergias_med").val("");
    $("#quirurgico").val("");
    $("#transsanguineo").val("");
    $("#psicosocial").val("");
    familiaresTemporales = [];
}

// Función para validar antes de enviar
function validarenvio() {
    if(validarkeyup(/^[0-9]{7,8}$/,$("#cedula_paciente"),
        $("#scedula_paciente"),"El formato debe ser 12345678")==0){
        muestraMensaje("La cedula debe coincidir con el formato 12345678");	
        return false;					
    }	
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
        $("#apellido"),$("#sapellido"),"Solo letras entre 3 y 30 caracteres")==0){
        muestraMensaje("Apellido: Solo letras entre 3 y 30 caracteres");
        return false;
    }
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
        $("#nombre"),$("#snombre"),"Solo letras entre 3 y 30 caracteres")==0){
        muestraMensaje("Nombre: Solo letras entre 3 y 30 caracteres");
        return false;
    }
    
    return true;
}

// Función para mostrar mensajes
function muestraMensaje(mensaje) {
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    }, 3000);
}

// Funciones para validación de campos
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    }
    else{
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

// Función para enviar datos por AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    destruyeDT();	
                    var html = '';
                    
                    lee.datos.forEach(function (fila) {
                        html += `<tr>
                            <td>
                                <div class="button-containerotro">
                                    <a type="button" class="btn btn-success" onclick="pone(this,0)"
                                        cedula_paciente="${fila.cedula_paciente}"
                                        apellido="${fila.apellido}"
                                        nombre="${fila.nombre}"
                                        fecha_nac="${fila.fecha_nac}"
                                        edad="${fila.edad}"
                                        telefono="${fila.telefono}"
                                        estadocivil="${fila.estadocivil}"
                                        direccion="${fila.direccion}"
                                        ocupacion="${fila.ocupacion}"
                                        hda="${fila.hda}"
                                        habtoxico="${fila.habtoxico}"
                                        alergias="${fila.alergias}"
                                        alergias_med="${fila.alergias_med}"
                                        quirurgico="${fila.quirurgico}"
                                        transsanguineo="${fila.transsanguineo}"
                                        psicosocial="${fila.psicosocial}">
                                        <img src="img/lapiz.svg" style="width: 20px">
                                    </a>
                                    <a class="btn btn-danger" href="vista/fpdf/historia.php?cedula_paciente=${fila.cedula_paciente}" target="_blank">
                                        <img src="img/descarga.svg" style="width: 20px;">
                                    </a>
                                </div>
                            </td>
                            <td class="cedula_paciente">${fila.cedula_paciente}</td>
                            <td class="apellido">${fila.apellido}</td>
                            <td class="nombre">${fila.nombre}</td>
                            <td class="fecha_nac">${fila.fecha_nac}</td>
                            <td class="edad">${fila.edad}</td>
                            <td class="telefono">${fila.telefono}</td>
                            <td class="estadocivil" style="display: none;">${fila.estadocivil}</td>
                            <td class="direccion" style="display: none;">${fila.direccion}</td>
                            <td class="ocupacion" style="display: none;">${fila.ocupacion}</td>
                            <td class="hda" style="display: none;">${fila.hda}</td>
                            <td class="habtoxico" style="display: none;">${fila.habtoxico}</td>
                            <td class="alergias" style="display: none;">${fila.alergias}</td>
                            <td class="alergias_med" style="display: none;">${fila.alergias_med}</td>
                            <td class="quirurgico" style="display: none;">${fila.quirurgico}</td>
                            <td class="psicosocial" style="display: none;">${fila.psicosocial}</td>
                            <td class="transsanguineo" style="display: none;">${fila.transsanguineo}</td>
                        </tr>`;
                    });
                    $("#resultadoconsulta").html(html);
                    crearDT();
                }
                else if (lee.resultado == "incluir") {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje.includes('exitosamente')) {
                        $("#modal1").modal("hide");
                        consultar();
                        limpiarFormulario();
                    }
                }
                else if (lee.resultado == "modificar") {
                    muestraMensaje(lee.mensaje);
                    if(lee.mensaje.includes('Modificado')){
                        $("#modal1").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == "error") {
                    muestraMensaje(lee.mensaje);
                }
            } catch (e) {
                console.error("Error en JSON:", respuesta);
                muestraMensaje("Error procesando respuesta del servidor");
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                muestraMensaje("Servidor ocupado, intente de nuevo");
            } else {
                muestraMensaje("Error: " + err);
            }
        }
    });
}

// Función para actualizar los pasos del formulario
function updateStep() {
    $('.step').addClass('d-none');
    $(`#step-${currentStep}`).removeClass('d-none');

    $('#prev-btn').toggle(currentStep > 1);
    
    if (currentStep === 3) {
        $('#next-btn').hide();
        $('#proceso').show().text($('#accion').val() === 'modificar' ? 'MODIFICAR' : 'INCLUIR');
    } else {
        $('#next-btn').show();
        $('#proceso').hide();
    }
}

// Funciones para antecedentes familiares
function mostrarModalFamiliar(editar = false, familiar = null) {
    const modal = $('#modalFamiliar');
    const form = $('#formFamiliar')[0];
    
    if(editar && familiar) {
        $('#modalFamiliarTitle').text('Editar Familiar');
        $('#id_familiar').val(familiar.id_familiar);
        $('#nom_familiar').val(familiar.nom_familiar);
        $('#ape_familiar').val(familiar.ape_familiar);
        $('#relacion_familiar').val(familiar.relacion_familiar);
        $('#observaciones').val(familiar.observaciones || '');
    } else {
        $('#modalFamiliarTitle').text('Agregar Familiar');
        form.reset();
        $('#id_familiar').val('');
    }
    
    $('#familiar_cedula_paciente').val($('#cedula_paciente').val());
    modal.modal('show');
}

function guardarFamiliar() {
    const form = $('#formFamiliar')[0];
    const formData = new FormData(form);
    
    // Validación básica
    if(!formData.get('nom_familiar') || !formData.get('ape_familiar') || !formData.get('relacion_familiar')) {
        muestraMensaje('Complete todos los campos requeridos');
        return;
    }

    const familiar = {
        nom_familiar: formData.get('nom_familiar'),
        ape_familiar: formData.get('ape_familiar'),
        relacion_familiar: formData.get('relacion_familiar'),
        observaciones: formData.get('observaciones') || ''
    };

    // Agregar a lista temporal
    familiaresTemporales.push(familiar);
    
    // Actualizar vista
    actualizarListaFamiliares();
    
    // Cerrar modal y limpiar formulario
    $('#modalFamiliar').modal('hide');
    form.reset();
    
    muestraMensaje('Familiar agregado (se guardará al registrar el paciente)');
}

function actualizarListaFamiliares() {
    const lista = $('#listaFamiliares');
    lista.empty();
    
    if(familiaresTemporales.length === 0) {
        lista.html('<div class="alert alert-info">No hay familiares agregados</div>');
        return;
    }
    
    familiaresTemporales.forEach((familiar, index) => {
        lista.append(`
            <div class="card mb-3 familiar-card" data-index="${index}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>${familiar.nom_familiar} ${familiar.ape_familiar}</h5>
                            <p><strong>Relación:</strong> ${familiar.relacion_familiar}</p>
                            ${familiar.observaciones ? `<p><strong>Observaciones:</strong> ${familiar.observaciones}</p>` : ''}
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="eliminarFamiliarTemporal(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
    });
}

function eliminarFamiliarTemporal(index) {
    familiaresTemporales.splice(index, 1);
    actualizarListaFamiliares();
}

function cargarAntecedentes(cedula) {
    const datos = new FormData();
    datos.append('accion', 'consultarAntecedentes');
    datos.append('cedula_paciente', cedula);
    
    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                const lee = JSON.parse(respuesta);
                const lista = $('#listaFamiliares');
                
                if(lee.resultado === "consultar" && lee.datos && lee.datos.length > 0) {
                    let html = '';
                    
                    lee.datos.forEach(function(familiar) {
                        html += `
                        <div class="card mb-3 familiar-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>${familiar.nom_familiar} ${familiar.ape_familiar}</h5>
                                        <p><strong>Relación:</strong> ${familiar.relacion_familiar}</p>
                                        ${familiar.observaciones ? `<p><strong>Observaciones:</strong> ${familiar.observaciones}</p>` : ''}
                                    </div>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarFamiliar(${familiar.id_familiar})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`;
                    });
                    
                    lista.html(html);
                } else {
                    lista.html('<div class="alert alert-info">No se han registrado antecedentes familiares</div>');
                }
            } catch(e) {
                console.error("Error al procesar antecedentes familiares", e);
                $('#listaFamiliares').html('<div class="alert alert-danger">Error al cargar los antecedentes</div>');
            }
        },
        error: function() {
            $('#listaFamiliares').html('<div class="alert alert-danger">Error al comunicarse con el servidor</div>');
        }
    });
}

function eliminarFamiliar(id_familiar) {
    if(!confirm('¿Está seguro que desea eliminar este familiar?')) return;
    
    const datos = new FormData();
    datos.append('accion', 'eliminarFamiliar');
    datos.append('id_familiar', id_familiar);
    
    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                const lee = JSON.parse(respuesta);
                if(lee.resultado === "success") {
                    muestraMensaje(lee.mensaje);
                    cargarAntecedentes($('#cedula_paciente').val());
                } else {
                    muestraMensaje(lee.mensaje);
                }
            } catch(e) {
                console.error("Error al procesar respuesta", e);
                muestraMensaje("Error al procesar la respuesta del servidor");
            }
        },
        error: function() {
            muestraMensaje("Error al comunicarse con el servidor");
        }
    });
}

// Función para enviar datos
function registrarPaciente() {
    if (!validarenvio()) return;
    
    const datos = new FormData(document.getElementById('f'));
    const accion = $('#accion').val();
    datos.append('accion', accion);
    
    if(accion === 'incluir' && familiaresTemporales.length > 0) {
        datos.append('familiares', JSON.stringify(familiaresTemporales));
    } else if(accion === 'modificar' && familiaresTemporales.length > 0) {
        datos.append('familiares', JSON.stringify(familiaresTemporales));
    }

    const campos = [
        'cedula_paciente', 'nombre', 'apellido', 'fecha_nac', 'edad',
        'telefono', 'estadocivil', 'direccion', 'ocupacion', 'hda',
        'habtoxico', 'alergias', 'alergias_med', 'quirurgico', 
        'transsanguineo', 'psicosocial'
    ];

    campos.forEach(campo => {
        datos.append(campo, $(`#${campo}`).val());
    });

    $('#proceso').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                // Elimina espacios y saltos de línea antes y después del JSON
                const clean = respuesta.trim();
                const res = JSON.parse(clean);
                if(res.resultado === 'success' || res.resultado === 'modificar') {
                    muestraMensaje(res.mensaje);
                    $('#modal1').modal('hide');
                    if(accion === 'incluir') {
                        familiaresTemporales = [];
                        limpiarFormulario();
                    }
                    consultar();
                } else {
                    muestraMensaje(res.mensaje);
                }
            } catch(e) {
                console.error("Error parsing response:", respuesta);
                muestraMensaje("Error procesando respuesta del servidor");
            }
        },
        error: function(xhr) {
            muestraMensaje("Error en la solicitud: " + xhr.statusText);
        },
        complete: function() {
            $('#proceso').prop('disabled', false).text(accion === 'modificar' ? 'MODIFICAR' : 'INCLUIR');
        }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la aplicación
    consultar();
    inicializarValidaciones();
    updateStep();
    
    // Configurar navegación por pasos
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentStep < 3) {
            currentStep++;
            updateStep();
        }
    });

    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });
    
    // Eventos para antecedentes familiares
    document.getElementById('btnAgregarFamiliar').addEventListener('click', function() {
        mostrarModalFamiliar();
    });
    
    document.getElementById('btnGuardarFamiliar').addEventListener('click', guardarFamiliar);
    
    document.getElementById('formFamiliar').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            guardarFamiliar();
        }
    });
    
    // Calcular edad automáticamente
    const fechaNacInput = document.getElementById('fecha_nac');
    const edadInput = document.getElementById('edad');

    if(fechaNacInput && edadInput) {
        fechaNacInput.addEventListener('input', function() {
            const fechaNac = new Date(this.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNac.getFullYear();
            const mes = hoy.getMonth() - fechaNac.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
            }

            edadInput.value = edad;
        });
    }
    
    // Efecto blur en el modal
    const modal = document.getElementById('modal1');
    if(modal) {
        modal.addEventListener('show.bs.modal', function() {
            document.body.classList.add('blur-background');
        });

        modal.addEventListener('hidden.bs.modal', function() {
            document.body.classList.remove('blur-background');
        });
    }
    // Configurar botón INCLUIR
    $('#proceso').click(registrarPaciente);
});

// NOTIFICACIONES
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al WebSocket');
};

// ws.onmessage = function(event) {
//     // Aquí recibes las notificaciones
//     alert('Notificación: ' + event.data);
//     // O puedes mostrarlo en un div, toast, etc.
// };

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