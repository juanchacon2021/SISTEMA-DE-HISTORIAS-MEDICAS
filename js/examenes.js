// Variables globales
var modoActual = "";
var idActual = "";
var tablaTiposExamen = null;
var tablaRegistrosExamen = null;

$(document).ready(function () {
  // Inicializar tabs
  $("#examenesTabs a").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
  });

  // Cargar datos iniciales
  cargarTiposExamen();
  cargarRegistrosExamen();
  cargarSelectTipos();
  cargarSelectPacientes();

  // Eventos para el modal de tipos de examen
  $("#btnGuardarTipoExamen").click(function () {
    guardarTipoExamen();
  });

  // Eventos para el modal de registros de examen
  $("#btnGuardarRegistroExamen").click(function () {
    guardarRegistroExamen();
  });

  // Evento para confirmación de eliminación
  $("#btnConfirmarEliminar").click(function () {
    if (modoActual == "tipo") {
      eliminarTipoExamenConfirmado();
    } else if (modoActual == "registro") {
      eliminarRegistroExamenConfirmado();
    }
  });

  // Validaciones de formulario
  $("#nombre_examen").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $("#nombre_examen").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/,
      $(this),
      $("#snombre_examen"),
      "Solo letras entre 3 y 50 caracteres"
    );
  });

  // Mostrar imagen seleccionada
  $("#archivoExamen").on("change", function () {
    mostrarImagen(this, "#imagenExamen");
  });
});

// Funciones para tipos de examen
function cargarTiposExamen() {
  var datos = new FormData();
  datos.append("accion", "consultar_tipos");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaTiposExamen != null) {
        tablaTiposExamen.destroy();
      }

      $("#resultadoTiposExamen").html("");

      respuesta.datos.forEach(function (tipo) {
        var fila = `
                <tr>
                    <td>${tipo.cod_examen}</td>
                    <td>${tipo.nombre_examen}</td>
                    <td>${tipo.descripcion_examen || "N/A"}</td>
                    <td>
                        <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                            <button type="button" class="btn btn-success" onclick='editarTipoExamen(${JSON.stringify(
                              tipo
                            )})'>
                                <img src="img/lapiz.svg" style="width: 20px">
                            </button>
                            <button type="button" class="btn btn-danger" onclick='confirmarEliminar("tipo", "${
                              tipo.cod_examen
                            }")'>
                                <img src="img/basura.svg" style="width: 20px">
                            </button>
                        </div>
                    </td>
                </tr>`;
        $("#resultadoTiposExamen").append(fila);
      });

      tablaTiposExamen = $("#tablaTiposExamen").DataTable({
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
        order: [[1, "asc"]],
      });
    }
  });
}

function mostrarModalTipoExamen(modo, datos = null) {
  modoActual = modo;
  $("#accionTipoExamen").val(
    modo == "incluir" ? "incluir_tipo" : "modificar_tipo"
  );
  $("#modalTipoExamenLabel").text(
    modo == "incluir" ? "Registrar Tipo de Examen" : "Editar Tipo de Examen"
  );
  $("#btnGuardarTipoExamen").text(
    modo == "incluir" ? "Registrar" : "Actualizar"
  );

  if (modo == "incluir") {
    $("#formTipoExamen")[0].reset();
    $("#cod_examen").val("");
  } else {
    $("#cod_examen").val(datos.cod_examen);
    $("#nombre_examen").val(datos.nombre_examen);
    $("#descripcion_examen").val(datos.descripcion_examen || "");
  }

  $("#modalTipoExamen").modal("show");
}

function editarTipoExamen(tipo) {
  mostrarModalTipoExamen("editar", tipo);
}

function guardarTipoExamen() {
  if (!validarFormularioTipoExamen()) {
    return;
  }

  var datos = new FormData($("#formTipoExamen")[0]);
  datos.append("accion", $("#accionTipoExamen").val());

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalTipoExamen").modal("hide");
      cargarTiposExamen();
      cargarSelectTipos();
    }
  });
}

function eliminarTipoExamenConfirmado() {
  var datos = new FormData();
  datos.append("accion", "eliminar_tipo");
  datos.append("cod_examen", idActual);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarTiposExamen();
    cargarSelectTipos();
  });
}

// Funciones para registros de examen

function cargarRegistrosExamen() {
  var datos = new FormData();
  datos.append("accion", "consultar_registros");

  // Aplicar filtros
  var paciente = $("#filtroPaciente").val();
  var fecha = $("#filtroFechaExamen").val();
  var tipo = $("#filtroTipoExamen").val();

  if (paciente) datos.append("filtro_paciente", paciente);
  if (fecha) datos.append("filtro_fecha", fecha);
  if (tipo) datos.append("filtro_tipo", tipo);

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaRegistrosExamen != null) {
        tablaRegistrosExamen.destroy();
      }

      $("#resultadoRegistrosExamen").html("");

      respuesta.datos.forEach(function (registro) {
        var fila = `
            <tr>
                <td>${registro.paciente} (${registro.cedula_paciente})</td>
                <td>${registro.nombre_examen}</td>
                <td>${registro.fecha_e} ${registro.hora_e}</td>
                <td>${registro.observacion_examen || "N/A"}</td>
                <td>
                    <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                        <button type="button" class="btn btn-success" onclick='editarRegistroExamen(${JSON.stringify(
                          registro
                        )})'>
                            <img src="img/lapiz.svg" style="width: 20px">
                        </button>
                        <button type="button" class="btn btn-danger" onclick='confirmarEliminar("registro", "${
                          registro.cedula_paciente
                        }|${registro.fecha_e}|${registro.cod_examen}")'>
                            <img src="img/basura.svg" style="width: 20px">
                        </button>
                        ${
                          registro.ruta_imagen
                            ? `<button type="button" class="btn btn-primary" onclick='mostrarImagenExamen("${registro.ruta_imagen}")'>
                              <img src="img/ojo.svg" style="width: 20px">
                          </button>`
                            : '<button type="button" class="btn btn-danger" disabled><img src="img/ojo-cruzado.svg" style="width: 20px"></button>'
                        }
                        <button type="button" class="btn btn-danger" onclick='generarReporteExamen("${
                          registro.cedula_paciente
                        }", "${registro.fecha_e}", "${registro.cod_examen}")'>
                            <img src="img/descarga.svg" style="width: 20px">
                        </button>
                    </div>
                </td>
            </tr>`;
        $("#resultadoRegistrosExamen").append(fila);
      });

      tablaRegistrosExamen = $("#tablaRegistrosExamen").DataTable({
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
        order: [[2, "desc"]],
      });
    }
  });
}

function generarReporteExamen(cedula, fecha, codExamen) {
    window.open(`vista/fpdf/examenes.php?cedula=${cedula}&fecha=${fecha}&cod_examen=${codExamen}`, '_blank');
}

function mostrarImagenExamen(rutaImagen) {
  if (!rutaImagen) {
    muestraMensaje("No hay imagen disponible para este examen", "error");
    return;
  }

  $("#imagenGrandeExamen").attr("src", rutaImagen);
  $("#modalImagenExamen").modal("show");
}

function mostrarModalRegistroExamen(datos = null) {
  $("#modalRegistroExamenLabel").text(
    datos ? "Editar Registro de Examen" : "Registrar Examen"
  );
  $("#btnGuardarRegistroExamen").text(datos ? "Actualizar" : "Registrar");
  $("#accionRegistroExamen").val(
    datos ? "modificar_registro" : "incluir_registro"
  );

  // Cargar pacientes en el select
  var selectPaciente = $("#pacienteExamen");
  selectPaciente
    .empty()
    .append('<option value="">Seleccione un paciente</option>');

  // Cargar tipos de examen en el select
  var selectTipo = $("#tipoExamen");
  selectTipo.empty().append('<option value="">Seleccione un tipo</option>');

  // Si hay datos, llenar el formulario
  if (datos) {
    $("#pacienteExamen").val(datos.cedula_paciente);
    $("#tipoExamen").val(datos.cod_examen);
    $("#fechaExamen").val(datos.fecha_e);
    $("#horaExamen").val(datos.hora_e);
    $("#observacionExamen").val(datos.observacion_examen || "");

    if (datos.ruta_imagen) {
      $("#imagenExamen").attr("src", datos.ruta_imagen);
    } else {
      $("#imagenExamen").attr("src", "img/logo.png");
    }
  } else {
    $("#formRegistroExamen")[0].reset();
    $("#imagenExamen").attr("src", "img/logo.png");
  }

  // Cargar selects
  var datosPacientes = new FormData();
  datosPacientes.append("accion", "obtener_pacientes_select");

  enviaAjax(datosPacientes, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      respuesta.datos.forEach(function (paciente) {
        selectPaciente.append(
          $("<option>", {
            value: paciente.cedula_paciente,
            text: paciente.nombre_completo,
          })
        );
      });
    }
  });

  var datosTipos = new FormData();
  datosTipos.append("accion", "obtener_tipos_select");

  enviaAjax(datosTipos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      respuesta.datos.forEach(function (tipo) {
        selectTipo.append(
          $("<option>", {
            value: tipo.cod_examen,
            text: tipo.nombre_examen,
          })
        );

        // También cargar en el filtro
        var filtroTipo = $("#filtroTipoExamen");
        filtroTipo.empty().append('<option value="">Todos los tipos</option>');

        respuesta.datos.forEach(function (tipo) {
          filtroTipo.append(
            $("<option>", {
              value: tipo.cod_examen,
              text: tipo.nombre_examen,
            })
          );
        });
      });
    }
  });

  $("#modalRegistroExamen").modal("show");
}

function editarRegistroExamen(registro) {
  mostrarModalRegistroExamen(registro);
}

function guardarRegistroExamen() {
  // Validación básica
  if (
    $("#pacienteExamen").val() === "" ||
    $("#tipoExamen").val() === "" ||
    $("#fechaExamen").val() === "" ||
    $("#horaExamen").val() === ""
  ) {
    muestraMensaje("Debe completar todos los campos requeridos", "error");
    return;
  }

  var datos = new FormData($("#formRegistroExamen")[0]);
  datos.append("accion", $("#accionRegistroExamen").val());

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalRegistroExamen").modal("hide");
      cargarRegistrosExamen();
    }
  });
}

function eliminarRegistroExamenConfirmado() {
  var partes = idActual.split("|");
  var cedula = partes[0];
  var fecha = partes[1];
  var examen = partes[2];

  var datos = new FormData();
  datos.append("accion", "eliminar_registro");
  datos.append("cedula_paciente", cedula);
  datos.append("fecha_e", fecha);
  datos.append("cod_examen", examen);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarRegistrosExamen();
  });
}

// Funciones auxiliares
function cargarSelectTipos() {
  var datos = new FormData();
  datos.append("accion", "obtener_tipos_select");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      var select = $("#tipoExamen");
      select.empty().append('<option value="">Seleccione un tipo</option>');

      respuesta.datos.forEach(function (tipo) {
        select.append(
          $("<option>", {
            value: tipo.cod_examen,
            text: tipo.nombre_examen,
          })
        );
      });
    }
  });
}

function cargarSelectPacientes() {
  var datos = new FormData();
  datos.append("accion", "obtener_pacientes_select");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      var select = $("#pacienteExamen");
      select.empty().append('<option value="">Seleccione un paciente</option>');

      respuesta.datos.forEach(function (paciente) {
        select.append(
          $("<option>", {
            value: paciente.cedula_paciente,
            text: paciente.nombre_completo,
          })
        );
      });
    }
  });
}

function confirmarEliminar(tipo, id) {
  modoActual = tipo;
  idActual = id;

  if (tipo == "tipo") {
    $("#mensajeConfirmacion").html(
      "¿Está seguro de eliminar este tipo de examen?<br>Solo se puede eliminar si no tiene registros asociados."
    );
  } else if (tipo == "registro") {
    $("#mensajeConfirmacion").html(
      "¿Está seguro de eliminar este registro de examen?<br>Esta acción no se puede deshacer."
    );
  }

  $("#modalConfirmacion").modal("show");
}

function validarFormularioTipoExamen() {
  let valido = true;

  if (
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/,
      $("#nombre_examen"),
      $("#snombre_examen"),
      "Solo letras entre 3 y 50 caracteres"
    ) == 0
  ) {
    valido = false;
  }

  return valido;
}

function mostrarImagen(input, selectorImagen) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(selectorImagen).attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Funciones de validación
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

function enviaAjax(datos, callback) {
  $.ajax({
    async: true,
    url: "?pagina=examenes",
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
        muestraMensaje(
          "Error al procesar la respuesta del servidor: " + e.message
        );
      }
    },
    error: function (xhr, status, err) {
      console.error("Error en la petición AJAX:", status, err);
      if (status == "timeout") {
        muestraMensaje("Servidor ocupado, intente de nuevo");
      } else {
        muestraMensaje("ERROR: <br/>" + request + status + err);
      }
    },
    complete: function () {},
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
