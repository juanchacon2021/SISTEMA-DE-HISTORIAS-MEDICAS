// Variables globales
var modoActual = "";
var idActual = "";
var tablaPersonal = null;

$(document).ready(function () {
    // Cargar datos iniciales
    cargarPersonal();
    cargarSelectDoctores();

    // Eventos para el modal de personal
    $("#btnGuardarPersonal").click(function () {
        guardarPersonal();
    });

    // Evento para confirmación de eliminación
    $("#btnConfirmarEliminar").click(function () {
        eliminarPersonalConfirmado();
    });

    // Validaciones de formulario
    $("#cedula_personal").on("keypress", function (e) {
        validarkeypress(/^[0-9-\b]*$/, e);
    }).on("keyup", function () {
        validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedula_personal"), "El formato debe ser 12345678");
    });

    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    }).on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#apellido").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    }).on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sapellido"), "Solo letras entre 3 y 30 caracteres");
    });

    $("#correo").on("keypress", function (e) {
        validarkeypress(/^[\w@.-]*$/, e);
    }).on("keyup", function () {
        validarkeyup(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/, $(this), $("#scorreo"), "Formato incorrecto");
    });

    // Manejar teléfonos
    $('#btn-add-phone').click(function() {
        agregarCampoTelefono();
    });

    $(document).on('click', '.btn-remove-phone', function() {
        $(this).closest('.input-group').remove();
    });
});

// Funciones para personal
function cargarPersonal() {
    var datos = new FormData();
    datos.append("accion", "consultar");

    enviaAjax(datos, function (respuesta) {
        if (respuesta.resultado == "consultar") {
            if (tablaPersonal != null) {
                tablaPersonal.destroy();
            }

            $("#resultadoPersonal").html("");

            respuesta.datos.forEach(function (personal) {
                var fila = `
                <tr>
                    <td>${personal.cedula_personal}</td>
                    <td>${personal.apellido}</td>
                    <td>${personal.nombre}</td>
                    <td>${personal.correo || "N/A"}</td>
                    <td>${personal.telefonos || "N/A"}</td>
                    <td>${personal.cargo}</td>
                    <td class="text-center">
                        <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                            <button type="button" class="btn btn-success" onclick='editarPersonal(${JSON.stringify(personal)})'>
                                <img src="img/lapiz.svg" style="width: 20px">
                            </button>
                            <button type="button" class="btn btn-danger" onclick='confirmarEliminar("personal", "${personal.cedula_personal}")'>
                                <img src="img/basura.svg" style="width: 20px">
                            </button>
                        </div>
                    </td>
                </tr>`;
                $("#resultadoPersonal").append(fila);
            });

            tablaPersonal = $("#tablaPersonal").DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontró personal",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay personal registrado",
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
                    { orderable: false, targets: -1 },
                    { className: "text-center", targets: -1 },
                ],
                order: [[1, "asc"]],
            });
        }
    });
}

function mostrarModalPersonal(modo, datos = null) {
    modoActual = "personal";
    $("#accionPersonal").val(modo == "incluir" ? "incluir" : "modificar");
    $("#modalPersonalLabel").text(modo == "incluir" ? "Registrar Personal" : "Editar Personal");
    $("#btnGuardarPersonal").text(modo == "incluir" ? "Registrar" : "Actualizar");

    if (modo == "incluir") {
        $("#formPersonal")[0].reset();
        $("#cedula_personal").prop("readonly", false);
        $("#telefonos-container").empty();
        agregarCampoTelefono();
    } else {
        // Llenar formulario con datos
        $("#cedula_personal").val(datos.cedula_personal).prop("readonly", true);
        $("#nombre").val(datos.nombre);
        $("#apellido").val(datos.apellido);
        $("#correo").val(datos.correo || "");
        $("#cargo").val(datos.cargo || "Doctor");
        
        $("#telefonos-container").empty();
        if (datos.telefonos) {
            const telefonos = datos.telefonos.split(", ");
            telefonos.forEach(tel => {
                if (tel.trim() !== '') {
                    agregarCampoTelefono(tel);
                }
            });
        }
        
        if ($("#telefonos-container").children().length === 0) {
            agregarCampoTelefono();
        }
    }

    $("#modalPersonal").modal("show");
}

function editarPersonal(personal) {
    mostrarModalPersonal("editar", personal);
}

function guardarPersonal() {
    if (!validarFormularioPersonal()) {
        return;
    }

    var datos = new FormData($("#formPersonal")[0]);
    datos.append("accion", $("#accionPersonal").val());
    
    // Recoger todos los teléfonos correctamente
    const telefonos = [];
    $(".telefono-input").each(function() {
        if ($(this).val().trim() !== '') {
            telefonos.push($(this).val().trim());
        }
    });
    
    datos.append("telefonos", JSON.stringify(telefonos));

    enviaAjax(datos, function (respuesta) {
        muestraMensaje(
            respuesta.mensaje,
            respuesta.resultado == "error" ? "error" : "success"
        );
        if (respuesta.resultado != "error") {
            $("#modalPersonal").modal("hide");
            cargarPersonal();
        }
    });
}

function eliminarPersonalConfirmado() {
    var datos = new FormData();
    datos.append("accion", "eliminar");
    datos.append("cedula_personal", idActual);

    enviaAjax(datos, function (respuesta) {
        muestraMensaje(
            respuesta.mensaje,
            respuesta.resultado == "error" ? "error" : "success"
        );
        $("#modalConfirmacion").modal("hide");
        cargarPersonal();
    });
}

function agregarCampoTelefono(valor = '') {
    const container = $('#telefonos-container');
    const nuevoInput = $(`
        <div class="input-group mb-2">
            <input class="form-control bg-gray-200 rounded-lg border-white telefono-input" type="text" value="${valor}" />
            <button type="button" class="btn btn-danger btn-remove-phone">-</button>
        </div>
    `);
    container.append(nuevoInput);
    
    nuevoInput.find('input').on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    nuevoInput.find('input').on("keyup", function () {
        validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto (ej: 04121234567)");
    });
}

function validarFormularioPersonal() {
    let valido = true;

    if (validarkeyup(/^[0-9]{7,8}$/, $("#cedula_personal"),
        $("#scedula_personal"), "El formato debe ser 12345678") == 0) {
        muestraMensaje("La cédula debe coincidir con el formato 12345678");    
        valido = false;                    
    }    
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
        $("#apellido"), $("#sapellido"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("Apellido: Solo letras entre 3 y 30 caracteres");
        valido = false;
    }
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
        $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("Nombre: Solo letras entre 3 y 30 caracteres");
        valido = false;
    }
    else if(validarkeyup(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/,
        $("#correo"), $("#scorreo"), "Formato de correo inválido") == 0) {
        muestraMensaje("Debe ingresar un correo válido");
        valido = false;
    }
    
    let telefonosValidos = false;
    $(".telefono-input").each(function() {
        if(validarkeyup(/^\d{11}$/, $(this), $("#stelefono"), "Formato del teléfono incorrecto") == 1) {
            telefonosValidos = true;
        }
    });
    
    if(!telefonosValidos) {
        muestraMensaje("Debe ingresar al menos un teléfono válido (11 dígitos)");
        valido = false;
    }
    
    return valido;
}

function cargarSelectDoctores() {
    var datos = new FormData();
    datos.append("accion", "obtener_doctores");

    enviaAjax(datos, function (respuesta) {
        if (respuesta.resultado == "consultar") {
            var select = $("#responsable_id");
            select.empty().append('<option value="">Seleccione un doctor</option>');

            respuesta.datos.forEach(function (doctor) {
                select.append(
                    $("<option>", {
                        value: doctor.cedula_personal,
                        text: doctor.nombre_completo,
                    })
                );
            });
        }
    });
}

function confirmarEliminar(tipo, id) {
    modoActual = tipo;
    idActual = id;

    $("#mensajeConfirmacion").html(
        "¿Está seguro de eliminar este miembro del personal?<br>Esta acción no se puede deshacer."
    );

    $("#modalConfirmacion").modal("show");
}

// Funciones auxiliares (compartidas con pasantias.js)
function enviaAjax(datos, callback) {
    $.ajax({
        async: true,
        url: "?pagina=personal",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        beforeSend: function () {},
        success: function (respuesta) {
            try {
                if (typeof respuesta === "object") {
                    if (typeof callback === "function") {
                        callback(respuesta);
                    }
                } else {
                    const json = JSON.parse(respuesta);
                    if (typeof callback === "function") {
                        callback(json);
                    }
                }
            } catch (e) {
                console.error("Error parsing JSON:", e, respuesta);
                muestraMensaje("Error al procesar la respuesta del servidor: " + e.message);
            }
        },
        error: function (xhr, status, err) {
            console.error("Error en la petición AJAX:", status, err);
            if (status == "timeout") {
                muestraMensaje("Servidor ocupado, intente de nuevo");
            } else {
                muestraMensaje("Error en la solicitud: " + err);
            }
        },
    });
}

function muestraMensaje(mensaje, tipo = "error") {
    const modal = $("#mostrarmodal");
    const contenido = $("#contenidodemodal");

    contenido.html(mensaje);

    if (tipo == "error") {
        modal.find(".modal-header").removeClass("bg-success").addClass("bg-danger");
    } else {
        modal.find(".modal-header").removeClass("bg-danger").addClass("bg-success");
    }

    modal.modal("show");

    setTimeout(function () {
        modal.modal("hide");
    }, 5000);
}

function validarkeypress(er, e) {
    const key = e.keyCode || e.which;
    const tecla = String.fromCharCode(key);
    const a = er.test(tecla);

    if (!a) {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    const a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}