// Variables globales
var modoActual = "";
var idActual = "";
var tablaJornadas = null;
var participantes = [];
var personalDisponible = [];

$(document).ready(function () {
  // Cargar datos iniciales
  cargarJornadas();
  cargarPersonal();
  cargarResponsables();

  $("#pacientes_masculinos, #pacientes_femeninos, #pacientes_embarazadas").on(
    "change keyup",
    function () {
      actualizarContadores();
      validarTotales();
    }
  );

  // Eventos para el modal de jornadas
  $("#btnGuardarJornada").click(function () {
    guardarJornada();
  });

  // Evento para confirmación de eliminación
  $("#btnConfirmarEliminar").click(function () {
    eliminarJornadaConfirmado();
  });
});

// Funciones para jornadas
function cargarJornadas() {
  var datos = new FormData();
  datos.append("accion", "consultar");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaJornadas != null) {
        tablaJornadas.destroy();
      }

      $("#resultadoJornadas").html("");

      respuesta.datos.forEach(function (jornada) {
        var fila = `
        <tr>
            <td>${formatearFecha(jornada.fecha_jornada)}</td>
            <td>${jornada.ubicacion}</td>
            <td>${jornada.total_pacientes}</td>
            <td>${jornada.pacientes_masculinos}</td>
            <td>${jornada.pacientes_femeninos}</td>
            <td>${jornada.pacientes_embarazadas}</td>
            <td>${jornada.responsable}</td>
            <td class="text-center">
                <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                    <button type="button" class="btn btn-success" onclick="editarJornada('${jornada.cod_jornada}')">
                        <img src="img/lapiz.svg" style="width: 20px">
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmarEliminar('${jornada.cod_jornada}')">
                        <img src="img/basura.svg" style="width: 20px">
                    </button>
                    <button type="button" class="btn btn-primary" onclick="generarReporteIndividual('${jornada.cod_jornada}')">
                        <img src="img/descarga.svg" style="width: 20px">
                    </button>
                </div>
            </td>
        </tr>`;
        $("#resultadoJornadas").append(fila);
      });

      tablaJornadas = $("#tablaJornadas").DataTable({
        language: {
          lengthMenu: "Mostrar _MENU_ por página",
          zeroRecords: "No se encontraron registros",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "No hay registros disponibles",
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
          { orderable: false, targets: -1 }, // Hace que la última columna (acciones) no sea ordenable
          { className: "text-center", targets: -1 }, // Centra el contenido de la columna de acciones
        ],
        order: [[0, "desc"]], // Ordena por fecha de inicio (más reciente primero) por defecto
      });
    }
  });
}

function mostrarModalJornada(modo, codigo = null) {
  modoActual = modo;
  $("#accionJornada").val(modo == "incluir" ? "incluir" : "modificar");
  $("#modalJornadaLabel").text(
    modo == "incluir" ? "Registrar Jornada Médica" : "Editar Jornada Médica"
  );
  $("#btnGuardarJornada").text(modo == "incluir" ? "Registrar" : "Actualizar");

  if (modo == "incluir") {
    $("#formJornada")[0].reset();
    $("#cod_jornada").val("");
    $("#total_pacientes").val(0);
    $("#pacientes_masculinos").val(0);
    $("#pacientes_femeninos").val(0);
    $("#pacientes_embarazadas").val(0);
    participantes = [];
    actualizarListaParticipantes();
    $("#mensaje-validacion").text("");
    $("#contador-container").removeClass("alert-danger").addClass("alert-info");
  } else {
    cargarDatosJornada(codigo);
  }

  $("#modalJornada").modal("show");
}

function cargarDatosJornada(codigo) {
  var datos = new FormData();
  datos.append("accion", "consultar_jornada");
  datos.append("cod_jornada", codigo);

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      var jornada = respuesta.jornada;

      $("#cod_jornada").val(jornada.cod_jornada);
      $("#fecha_jornada").val(jornada.fecha_jornada);
      $("#ubicacion").val(jornada.ubicacion);
      $("#descripcion").val(jornada.descripcion);
      $("#total_pacientes").val(jornada.total_pacientes);
      $("#pacientes_masculinos").val(jornada.pacientes_masculinos);
      $("#pacientes_femeninos").val(jornada.pacientes_femeninos);
      $("#pacientes_embarazadas").val(jornada.pacientes_embarazadas);
      $("#cedula_responsable").val(jornada.cedula_responsable);

      // Cargar participantes
      participantes = [];
      respuesta.participantes.forEach(function (part) {
        participantes.push(part.cedula_personal);
      });
      actualizarListaParticipantes();
      actualizarContadores();
    }
  });
}

function guardarJornada() {
  if (!validarFormularioJornada()) {
    return;
  }

  var datos = new FormData($("#formJornada")[0]);
  datos.append("accion", $("#accionJornada").val());

  // Agregar participantes al FormData
  participantes.forEach(function (cedula, index) {
    datos.append("participantes[]", cedula);
  });

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalJornada").modal("hide");
      cargarJornadas();
    }
  });
}

function editarJornada(codigo) {
  mostrarModalJornada("editar", codigo);
}

function confirmarEliminar(codigo) {
  modoActual = "jornada";
  idActual = codigo;
  $("#mensajeConfirmacion").html(
    "¿Está seguro de eliminar esta jornada médica?<br>Esta acción no se puede deshacer."
  );
  $("#modalConfirmacion").modal("show");
}

function eliminarJornadaConfirmado() {
  var datos = new FormData();
  datos.append("accion", "eliminar");
  datos.append("cod_jornada", idActual);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarJornadas();
  });
}

// Funciones para participantes
function cargarPersonal() {
  var datos = new FormData();
  datos.append("accion", "obtener_personal");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      personalDisponible = respuesta.datos;

      var select = $("#participante");
      select
        .empty()
        .append('<option value="">Seleccione un participante</option>');

      respuesta.datos.forEach(function (persona) {
        select.append(
          $("<option>", {
            value: persona.cedula_personal,
            text: persona.nombre_completo,
          })
        );
      });
    }
  });
}

function cargarResponsables() {
  var datos = new FormData();
  datos.append("accion", "obtener_responsables");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      var select = $("#cedula_responsable");
      select
        .empty()
        .append('<option value="">Seleccione un responsable</option>');

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

function agregarParticipante() {
  var cedula = $("#participante").val();

  if (cedula == "") {
    muestraMensaje("Debe seleccionar un participante");
    return;
  }

  if (participantes.includes(cedula)) {
    muestraMensaje("Este participante ya fue agregado");
    return;
  }

  participantes.push(cedula);
  actualizarListaParticipantes();
  $("#participante").val("");
}

function eliminarParticipante(index) {
  participantes.splice(index, 1);
  actualizarListaParticipantes();
}

function actualizarListaParticipantes() {
  $("#listaParticipantes").html("");

  if (participantes.length == 0) {
    $("#listaParticipantes").append(
      '<tr><td colspan="2" class="text-center">No hay participantes agregados</td></tr>'
    );
    return;
  }

  participantes.forEach(function (cedula, index) {
    var persona = personalDisponible.find((p) => p.cedula_personal == cedula);
    var nombre = persona ? persona.nombre_completo : "Desconocido";

    var fila = `
        <tr>
            <td>${nombre}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-danger" onclick="eliminarParticipante(${index})">
                    <img src='img/trash-can-solid.svg' style='width: 15px;'>
                </button>
            </td>
        </tr>`;
    $("#listaParticipantes").append(fila);
  });
}

// Funciones auxiliares
function validarFormularioJornada() {
  if ($("#fecha_jornada").val() == "") {
    muestraMensaje("Debe especificar la fecha de la jornada");
    return false;
  }

  if ($("#ubicacion").val().trim() == "") {
    muestraMensaje("Debe especificar la ubicación de la jornada");
    return false;
  }

  if ($("#cedula_responsable").val() == "") {
    muestraMensaje("Debe seleccionar un responsable");
    return false;
  }

  if (!validarTotales()) {
    return false;
  }

  return true;
}

function validarTotales() {
  var femeninos = parseInt($("#pacientes_femeninos").val()) || 0;
  var embarazadas = parseInt($("#pacientes_embarazadas").val()) || 0;

  if (embarazadas > femeninos) {
    $("#mensaje-validacion").text(
      `Embarazadas (${embarazadas}) no puede ser mayor a pacientes femeninos (${femeninos})`
    );
    $("#contador-container").removeClass("alert-info").addClass("alert-danger");
    return false;
  }

  $("#mensaje-validacion").text("");
  $("#contador-container").removeClass("alert-danger").addClass("alert-info");
  return true;
}

function actualizarContadores() {
  var masculinos = parseInt($("#pacientes_masculinos").val()) || 0;
  var femeninos = parseInt($("#pacientes_femeninos").val()) || 0;
  var suma = masculinos + femeninos;

  $("#total_pacientes").val(suma);
  $("#suma-mf").text(suma);

  // Validar que embarazadas no supere a femeninos
  validarTotales();
}

function formatearFecha(fecha) {
  if (!fecha) return "";

  var partes = fecha.split("-");
  if (partes.length == 3) {
    return partes[2] + "/" + partes[1] + "/" + partes[0];
  }
  return fecha;
}

function mostrarModalReportes() {
    $('#modalReportes').modal('show');
}

function generarReporte(tipo) {
    $('#modalReportes').modal('hide');
    window.open(`vista/fpdf/jornadas_reportes.php?tipo=${tipo}`, '_blank');
}

function generarReporteIndividual(codigo) {
    window.open(`vista/fpdf/jornadas_reportes.php?tipo=individual&codigo=${codigo}`, '_blank');
}

function enviaAjax(datos, callback) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    timeout: 10000,
    beforeSend: function () {
      // Opcional: mostrar un spinner de carga aquí
    },
    success: function (respuesta, textStatus, xhr) { // Añadimos xhr aquí
      try {
        const json = JSON.parse(respuesta);
        
        // 1. Manejo de error de negocio (si el PHP devolvió {"resultado": "error", ...})
        if (json.resultado === "error" && json.mensaje) {
          muestraMensaje(json.mensaje, "error");
          return;
        }
        
        // 2. Si es exitoso, llama al callback para actualizar la tabla
        if (typeof callback === "function") {
          callback(json); 
        }
      } catch (e) {
        console.error("Error al intentar parsear JSON:", e);
        console.error("Respuesta cruda del servidor (causa del error):", respuesta); 
        
        // Mensaje de error más específico para el usuario
        muestraMensaje(
          "La operación en BD fue exitosa, pero la respuesta no es JSON válido. (Revisar consola y PHP).",
          "error"
        );
      }
    },
    error: function (xhr, status, err) {
      let mensajeError = "Error desconocido en la solicitud.";

      // 1. Manejo de Timeouts
      if (status === "timeout") {
        mensajeError = "El servidor tardó demasiado en responder. Intente de nuevo.";
      } 
      // 2. Intentar parsear el error del servidor (JSON)
      else if (xhr.responseText) {
        try {
          const errorJson = JSON.parse(xhr.responseText);
          // Si el servidor (e.g., PHP catch block) devuelve un JSON con 'mensaje'
          if (errorJson.mensaje) {
            mensajeError = errorJson.mensaje;
          } else {
            mensajeError = `Error del servidor (${xhr.status}): ${xhr.statusText}.`;
          }
        } catch (e) {
          // Si no es JSON, manejar el error HTTP genérico
          switch (xhr.status) {
            case 404:
              mensajeError = "Recurso no encontrado (404). Revise la ruta de la solicitud.";
              break;
            case 403:
              mensajeError = "Acceso denegado (403). No tiene permisos.";
              break;
            case 500:
              mensajeError = "Error interno del servidor (500). Contacte a soporte técnico.";
              break;
            default:
              if (xhr.status > 0) {
                mensajeError = `Error en la conexión: ${xhr.status} ${xhr.statusText}`;
              } else {
                 mensajeError = `Error de red. Verifique su conexión a Internet.`;
              }
              break;
          }
        }
      } else {
         // Error de conexión sin respuesta de texto
          mensajeError = `Error de solicitud: ${err}. Verifique su conexión.`;
      }
      
      console.error("Error AJAX:", xhr, status, err);
      muestraMensaje(mensajeError, "error");
    },
    complete: function () {
      // Opcional: ocultar el spinner de carga aquí
    }
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