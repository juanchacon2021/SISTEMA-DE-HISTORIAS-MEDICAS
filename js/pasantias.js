// Variables globales
var modoActual = "";
var idActual = "";
var fechaInicioActual = "";
var tablaEstudiantes = null;
var tablaAreas = null;
var tablaAsistencia = null;

$(document).ready(function () {
  // Inicializar tabs
  $("#pasantiasTabs a").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
  });

  // Cargar datos iniciales
  cargarEstudiantes();
  cargarAreas();
  cargarSelectAreas();
  cargarSelectDoctores();
  cargarAsistencia();

  // Eventos para el modal de estudiantes
  $("#btnGuardarEstudiante").click(function () {
    guardarEstudiante();
  });

  // Eventos para el modal de áreas
  $("#btnGuardarArea").click(function () {
    guardarArea();
  });

  // Eventos para el modal de asistencia
  $("#btnGuardarAsistencia").click(function () {
    guardarAsistencia();
  });

  // Evento para confirmación de eliminación
  $("#btnConfirmarEliminar").click(function () {
    if (modoActual == "estudiante") {
      eliminarEstudianteConfirmado();
    } else if (modoActual == "area") {
      eliminarAreaConfirmado();
    } else if (modoActual == "asistencia") {
      eliminarAsistenciaConfirmado();
    }
  });

  // Filtros para asistencia
  $(
    "#filtroAreaAsistencia, #filtroFechaAsistencia, #filtroEstadoAsistencia"
  ).change(function () {
    cargarAsistencia();
  });

  // Validaciones de formulario
  $("#cedula_estudiante").on("keypress", function (e) {
    validarkeypress(/^[0-9-\b]*$/, e);
  });

  $("#cedula_estudiante").on("keyup", function () {
    validarkeyup(
      /^[0-9]{7,8}$/,
      $(this),
      $("#scedula_estudiante"),
      "El formato debe ser 12345678"
    );
  });

  $("#nombre").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $("#nombre").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $(this),
      $("#snombre"),
      "Solo letras entre 3 y 30 caracteres"
    );
  });

  $("#apellido").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $("#apellido").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $(this),
      $("#sapellido"),
      "Solo letras entre 3 y 30 caracteres"
    );
  });

  $("#telefono").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
  });

  $("#telefono").on("keyup", function () {
    validarkeyup(
      /^\d{11}$/,
      $(this),
      $("#stelefono"),
      "Formato del teléfono incorrecto (ej: 04121234567)"
    );
  });
});

// Funciones para estudiantes
function cargarEstudiantes() {
  var datos = new FormData();
  datos.append("accion", "consultar_estudiantes");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaEstudiantes != null) {
        tablaEstudiantes.destroy();
      }

      $("#resultadoEstudiantes").html("");

      respuesta.datos.forEach(function (estudiante) {
        var fila = `
        <tr>
            <td>${estudiante.cedula_estudiante}</td>
            <td>${estudiante.apellido}</td>
            <td>${estudiante.nombre}</td>
            <td>${estudiante.institucion}</td>
            <td>${estudiante.telefono || "N/A"}</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class='btn btn-sm btn-primary mr-1' onclick='editarEstudiante(${JSON.stringify(
                      estudiante
                    )})'>
                        <img src="img/lapiz.svg" style="width: 20px">
                    </button>
                    <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("estudiante", "${
                      estudiante.cedula_estudiante
                    }")'>
                        <img src='img/trash-can-solid.svg' style='width: 20px;'>
                    </button>
                </div>
            </td>
        </tr>`;
        $("#resultadoEstudiantes").append(fila);
      });

      tablaEstudiantes = $("#tablaEstudiantes").DataTable({
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
        order: [[1, "asc"]], // Ordena por la segunda columna (Apellidos) por defecto
      });
    }
  });
}

function mostrarModalEstudiante(modo, datos = null) {
  modoActual = modo;
  $("#accionEstudiante").val(
    modo == "incluir" ? "incluir_estudiante" : "modificar_estudiante"
  );
  $("#modalEstudianteLabel").text(
    modo == "incluir" ? "Registrar Estudiante" : "Editar Estudiante"
  );
  $("#btnGuardarEstudiante").text(
    modo == "incluir" ? "Registrar" : "Actualizar"
  );

  // Cargar áreas en el select
  cargarSelectAreas();

  if (modo == "incluir") {
    $("#formEstudiante")[0].reset();
    $("#cedula_estudiante").prop("readonly", false);
    $("#scedula_estudiante, #snombre, #sapellido, #stelefono").text("");
  } else {
    // Llenar formulario con datos
    $("#cedula_estudiante").val(datos.cedula_estudiante).prop("readonly", true);
    $("#nombre").val(datos.nombre);
    $("#apellido").val(datos.apellido);
    $("#institucion").val(datos.institucion);
    $("#telefono").val(datos.telefono || "");
    $("#cod_area").val(datos.cod_area || "");
  }

  $("#modalEstudiante").modal("show");
}

function guardarEstudiante() {
  if (!validarFormularioEstudiante()) {
    return;
  }

  var datos = new FormData($("#formEstudiante")[0]);
  datos.append("accion", $("#accionEstudiante").val());

  // Solo incluir el área si estamos creando un nuevo estudiante
  if ($("#accionEstudiante").val() == "incluir_estudiante") {
    datos.append("cod_area", $("#cod_area").val());
  }

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalEstudiante").modal("hide");
      cargarEstudiantes();
    }
  });
}

function editarEstudiante(estudiante) {
  mostrarModalEstudiante("editar", estudiante);
}

function eliminarEstudianteConfirmado() {
  var datos = new FormData();
  datos.append("accion", "eliminar_estudiante");
  datos.append("cedula_estudiante", idActual);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarEstudiantes();
  });
}

// Funciones para áreas
function cargarAreas() {
  var datos = new FormData();
  datos.append("accion", "consultar_areas");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaAreas != null) {
        tablaAreas.destroy();
      }

      $("#resultadoAreas").html("");

      respuesta.datos.forEach(function (area) {
        var fila = `
        <tr>
            <td>${area.nombre_area}</td>
            <td>${area.descripcion || "N/A"}</td>
            <td>${area.responsable}</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class='btn btn-sm btn-primary mr-1' onclick='editarArea(${JSON.stringify(
                      area
                    )})'>
                        <img src="img/lapiz.svg" style="width: 20px">
                    </button>
                    <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("area", "${
                      area.cod_area
                    }")'>
                        <img src='img/trash-can-solid.svg' style='width: 20px;'>
                    </button>
                </div>
            </td>
        </tr>`;
        $("#resultadoAreas").append(fila);
      });

      tablaAreas = $("#tablaAreas").DataTable({
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
        responsive: true,
        autoWidth: false,
        columnDefs: [
          { orderable: false, targets: -1 }, // Hace que la última columna (acciones) no sea ordenable
          { className: "text-center", targets: -1 }, // Centra el contenido de la columna de acciones
        ],
        order: [[0, "asc"]], // Ordena por la primera columna (Nombre del área) por defecto
      });
    }
  });
}

function mostrarModalArea(modo, datos = null) {
  modoActual = modo;
  $("#accionArea").val(modo == "incluir" ? "incluir_area" : "modificar_area");
  $("#modalAreaLabel").text(
    modo == "incluir" ? "Registrar Área" : "Editar Área"
  );
  $("#btnGuardarArea").text(modo == "incluir" ? "Registrar" : "Actualizar");

  if (modo == "incluir") {
    $("#formArea")[0].reset();
    $("#id_area").val(""); // Usar el nuevo ID
  } else {
    // Llenar formulario con datos
    $("#id_area").val(datos.cod_area); // Usar el nuevo ID
    $("#nombre_area").val(datos.nombre_area);
    $("#descripcion").val(datos.descripcion || "");
    $("#responsable_id").val(datos.cedula_responsable);
  }

  $("#modalArea").modal("show");
}

function editarArea(area) {
  mostrarModalArea("editar", area);
}

function guardarArea() {
  if (!validarFormularioArea()) {
    return;
  }

  var datos = new FormData($("#formArea")[0]);
  datos.append("accion", $("#accionArea").val());

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalArea").modal("hide");
      cargarAreas();
      cargarSelectAreas(); // Recargar áreas en los selects
    }
  });
}

function eliminarAreaConfirmado() {
  var datos = new FormData();
  datos.append("accion", "eliminar_area");
  datos.append("cod_area", idActual);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarAreas();
    cargarSelectAreas(); // Recargar áreas en los selects
  });
}

// Funciones para asistencia
function cargarAsistencia() {
  var datos = new FormData();
  datos.append("accion", "consultar_asistencia");

  // Aplicar filtros
  var area = $("#filtroAreaAsistencia").val();
  var fecha = $("#filtroFechaAsistencia").val();
  var estado = $("#filtroEstadoAsistencia").val();

  if (area) datos.append("filtro_area", area);
  if (fecha) datos.append("filtro_fecha", fecha);
  if (estado) datos.append("filtro_estado", estado);

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      if (tablaAsistencia != null) {
        tablaAsistencia.destroy();
      }

      $("#resultadoAsistencia").html("");

      respuesta.datos.forEach(function (asistencia) {
        var fila = `
        <tr>
            <td>${asistencia.estudiante}</td>
            <td>${asistencia.nombre_area}</td>
            <td>${asistencia.fecha_inicio}</td>
            <td>${asistencia.fecha_fin || "En curso"}</td>
            <td>${
              asistencia.activo == 1
                ? '<span class="badge badge-success" style="background-color: green;">Activo</span>'
                : '<span class="badge badge-secondary" style="background-color: red;">Inactivo</span>'
            }</td>
            <td class="text-center">
                <button class='btn btn-sm btn-info mr-1' onclick='editarAsistencia(${JSON.stringify(
                  asistencia
                )})'>
                    <img src="img/lapiz.svg" style="width: 20px">
                </button>
                <button class='btn btn-sm btn-danger' onclick='confirmarEliminar("asistencia", "${
                  asistencia.cedula_estudiante
                }", "${asistencia.fecha_inicio}")'>
                    <img src='img/trash-can-solid.svg' style='width: 20px;'>
                </button>
            </td>
        </tr>`;
        $("#resultadoAsistencia").append(fila);
      });

      tablaAsistencia = $("#tablaAsistencia").DataTable({
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
        order: [[2, "desc"]], // Ordena por fecha de inicio (más reciente primero) por defecto
      });
    }
  });
}

function mostrarModalAsistencia(datos = null) {
  $("#modalAsistenciaLabel").text(
    datos ? "Editar Asistencia" : "Registrar Asistencia"
  );
  $("#btnGuardarAsistencia").text(datos ? "Actualizar" : "Registrar");

  // Cargar estudiantes en el select
  var selectEstudiante = $("#asistenciaEstudiante");
  selectEstudiante
    .empty()
    .append('<option value="">Seleccione un estudiante</option>');

  var datosEstudiantes = new FormData();
  datosEstudiantes.append("accion", "consultar_estudiantes");

  enviaAjax(datosEstudiantes, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      respuesta.datos.forEach(function (estudiante) {
        selectEstudiante.append(
          $("<option>", {
            value: estudiante.cedula_estudiante,
            text: estudiante.nombre + " " + estudiante.apellido,
          })
        );
      });

      if (datos) {
        $("#asistenciaEstudiante").val(datos.cedula_estudiante);
        $("#asistenciaArea").val(datos.cod_area);
        $("#asistenciaFechaInicio").val(datos.fecha_inicio);
        $("#asistenciaFechaFin").val(datos.fecha_fin || "");
        $("#asistenciaActivo").prop("checked", datos.activo == 1);
      }
    }
  });

  $("#modalAsistencia").modal("show");
}

function editarAsistencia(asistencia) {
  mostrarModalAsistencia(asistencia);
}

function guardarAsistencia() {
  // Validación básica
  if (
    $("#asistenciaEstudiante").val() === "" ||
    $("#asistenciaArea").val() === "" ||
    $("#asistenciaFechaInicio").val() === ""
  ) {
    muestraMensaje("Debe completar todos los campos requeridos", "error");
    return;
  }

  var datos = new FormData();
  var accion = $("#modalAsistenciaLabel").text().includes("Editar")
    ? "modificar_asistencia"
    : "incluir_asistencia";

  datos.append("accion", accion);
  datos.append("cedula_estudiante", $("#asistenciaEstudiante").val());
  datos.append("cod_area", $("#asistenciaArea").val());
  datos.append("fecha_inicio", $("#asistenciaFechaInicio").val());
  datos.append("fecha_fin", $("#asistenciaFechaFin").val());
  datos.append("activo", $("#asistenciaActivo").is(":checked") ? 1 : 0);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    if (respuesta.resultado != "error") {
      $("#modalAsistencia").modal("hide");
      cargarAsistencia();
    }
  });
}

function eliminarAsistenciaConfirmado() {
  var datos = new FormData();
  datos.append("accion", "eliminar_asistencia");
  datos.append("cedula_estudiante", idActual);
  datos.append("fecha_inicio", fechaInicioActual);

  enviaAjax(datos, function (respuesta) {
    muestraMensaje(
      respuesta.mensaje,
      respuesta.resultado == "error" ? "error" : "success"
    );
    $("#modalConfirmacion").modal("hide");
    cargarAsistencia();
  });
}

// Funciones auxiliares
function cargarSelectAreas() {
  var datos = new FormData();
  datos.append("accion", "obtener_areas_select");

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      // Select en formulario de estudiante
      var selectEstudiante = $("#cod_area");
      selectEstudiante
        .empty()
        .append('<option value="">Seleccione un área</option>');

      // Select en formulario de asistencia
      var selectAsistencia = $("#asistenciaArea");
      selectAsistencia
        .empty()
        .append('<option value="">Seleccione un área</option>');

      respuesta.datos.forEach(function (area) {
        selectEstudiante.append(
          $("<option>", {
            value: area.cod_area,
            text: area.nombre_area,
          })
        );

        selectAsistencia.append(
          $("<option>", {
            value: area.cod_area,
            text: area.nombre_area,
          })
        );
      });

      // También cargar en el filtro de asistencia
      var filtroArea = $("#filtroAreaAsistencia");
      filtroArea.empty().append('<option value="">Todas las áreas</option>');

      respuesta.datos.forEach(function (area) {
        filtroArea.append(
          $("<option>", {
            value: area.cod_area,
            text: area.nombre_area,
          })
        );
      });
    }
  });
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

function confirmarEliminar(tipo, id, fechaInicio = "") {
  modoActual = tipo;
  idActual = id;
  fechaInicioActual = fechaInicio;

  if (tipo == "estudiante") {
    $("#mensajeConfirmacion").html(
      "¿Está seguro de eliminar este estudiante?<br>Esta acción no se puede deshacer."
    );
  } else if (tipo == "area") {
    $("#mensajeConfirmacion").html(
      "¿Está seguro de eliminar esta área?<br>Solo se puede eliminar si no tiene estudiantes asignados."
    );
  } else if (tipo == "asistencia") {
    $("#mensajeConfirmacion").html(
      "¿Está seguro de eliminar este registro de asistencia?"
    );
  }

  $("#modalConfirmacion").modal("show");
}

function validarFormularioEstudiante() {
  let valido = true;

  if (
    validarkeyup(
      /^[0-9]{7,8}$/,
      $("#cedula_estudiante"),
      $("#scedula_estudiante"),
      "El formato debe ser 12345678"
    ) == 0
  ) {
    valido = false;
  }

  if (
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#nombre"),
      $("#snombre"),
      "Solo letras entre 3 y 30 caracteres"
    ) == 0
  ) {
    valido = false;
  }

  if (
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $("#apellido"),
      $("#sapellido"),
      "Solo letras entre 3 y 30 caracteres"
    ) == 0
  ) {
    valido = false;
  }

  if ($("#institucion").val() == "") {
    muestraMensaje("Debe especificar la institución");
    valido = false;
  }

  return valido;
}

function validarFormularioArea() {
  let valido = true;

  if ($("#nombre_area").val().trim() === "") {
    $("#snombre_area").text("Debe ingresar el nombre del área");
    valido = false;
  } else {
    $("#snombre_area").text("");
  }

  if ($("#responsable_id").val() === "") {
    $("#sresponsable_id").text("Debe seleccionar un doctor responsable");
    valido = false;
  } else {
    $("#sresponsable_id").text("");
  }

  if (!valido) {
    muestraMensaje("Por favor complete todos los campos requeridos", "error");
  }

  return valido;
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
    url: "?pagina=pasantias",
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

function limpia() {
  $("#formEstudiante")[0].reset();
  $("#formArea")[0].reset();
  $("#formAsistencia")[0].reset();
  $(".invalid-feedback").text("");
}
