$(document).ready(function () {
  // Inicializar fechas
  $("#fecha_inicio, #fecha_fin").val(new Date().toISOString().split("T")[0]);

  // Cuando cambia la tabla seleccionada
  $("#tabla").change(function () {
    cargarEstructuraTabla();
  });

  // Cargar estructura inicial si hay tabla seleccionada
  if ($("#tabla").val()) {
    cargarEstructuraTabla();
  }
});

function cargarEstructuraTabla() {
  var tabla = $("#tabla").val();
  if (!tabla) return;

  var datos = new FormData();
  datos.append("accion", "consultar_estructura");
  datos.append("tabla", tabla);

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      // Limpiar secciones
      $("#campos_filtro, #campos_seleccion").empty();

      // Crear campos de filtro
      var filtrosHtml = '<div class="col-md-12"><h5>Filtrar por:</h5></div>';

      respuesta.datos.forEach(function (campo) {
        // Solo mostrar campos que no sean claves primarias
        if (campo.clave != "PRI") {
          filtrosHtml += `
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>${campo.nombre}</label>
                            <div class="input-group">
                                <select class="form-control operador" data-campo="${campo.nombre}">
                                <option value="LIKE">contiene</option>    
                                <option value="=">Igual a:</option>
                                    <option value="!=">Diferente a:</option>
                                    <option value=">">Menor que:</option>
                                    <option value="<">Mayor que:</option>
                                    <option value=">=">Menor o igual a:</option>
                                    <option value="<=">Mayor o igual a:</option>
                                    
                                </select>
                                <input type="text" class="form-control valor-filtro" data-campo="${campo.nombre}">
                            </div>
                        </div>
                    </div>`;
        }
      });

      $("#campos_filtro").html(filtrosHtml);

      // Crear checkboxes de campos a mostrar
      var camposHtml = "";

      respuesta.datos.forEach(function (campo) {
        camposHtml += `
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input campo-seleccion" type="checkbox" 
                               id="campo_${campo.nombre}" value="${campo.nombre}" checked>
                        <label class="form-check-label" for="campo_${campo.nombre}">
                            ${campo.nombre}
                        </label>
                    </div>
                </div>`;
      });

      $("#campos_seleccion").html(camposHtml);

      // Cargar datos iniciales
      cargarDatosTabla();
    }
  });
}

function cargarDatosTabla() {
  var tabla = $("#tabla").val();
  if (!tabla) return;

  // Obtener filtros
  var filtros = [];
  $(".operador").each(function () {
    var campo = $(this).data("campo");
    var operador = $(this).val();
    var valor = $(this).closest(".input-group").find(".valor-filtro").val();

    if (valor) {
      // Para fechas, convertir formato
      if (campo.toLowerCase().includes("fecha")) {
        valor = valor.split("-").reverse().join("-");
      }

      // Para operador LIKE, agregar %%
      if (operador == "LIKE") {
        valor = "%" + valor + "%";
      }

      filtros.push({
        campo: campo,
        operador: operador,
        valor: valor,
      });
    }
  });

  // Obtener campos seleccionados
  var campos = [];
  $(".campo-seleccion:checked").each(function () {
    campos.push($(this).val());
  });

  var datos = new FormData();
  datos.append("accion", "consultar_datos");
  datos.append("tabla", tabla);
  datos.append("filtros", JSON.stringify(filtros));
  datos.append("campos", JSON.stringify(campos));

  enviaAjax(datos, function (respuesta) {
    if (respuesta.resultado == "consultar") {
      // Limpiar tabla
      $("#tablaResultadosHead, #tablaResultadosBody").empty();

      // Crear encabezados
      var thead = "<tr>";
      if (respuesta.datos.length > 0) {
        for (var campo in respuesta.datos[0]) {
          thead += `<th>${campo}</th>`;
        }
      }
      thead += "</tr>";
      $("#tablaResultadosHead").html(thead);

      // Crear filas
      var tbody = "";
      respuesta.datos.forEach(function (fila) {
        tbody += "<tr>";
        for (var campo in fila) {
          tbody += `<td>${fila[campo] || ""}</td>`;
        }
        tbody += "</tr>";
      });
      $("#tablaResultadosBody").html(tbody);
    }
  });
}

function generarReporte() {
  var tabla = $("#tabla").val();
  if (!tabla) {
    muestraMensaje("Debe seleccionar una tabla", "error");
    return;
  }

  // Obtener filtros
  var filtros = [];
  $(".operador").each(function () {
    var campo = $(this).data("campo");
    var operador = $(this).val();
    var valor = $(this).closest(".input-group").find(".valor-filtro").val();

    if (valor) {
      // Para fechas, convertir formato
      if (campo.toLowerCase().includes("fecha")) {
        valor = valor.split("-").reverse().join("-");
      }

      // Para operador LIKE, agregar %%
      if (operador == "LIKE") {
        valor = "%" + valor + "%";
      }

      filtros.push({
        campo: campo,
        operador: operador,
        valor: valor,
      });
    }
  });

  // Obtener campos seleccionados
  var campos = [];
  $(".campo-seleccion:checked").each(function () {
    campos.push({
      campo: $(this).val(),
      titulo: $(this).val(),
    });
  });

  if (campos.length == 0) {
    muestraMensaje("Debe seleccionar al menos un campo", "error");
    return;
  }

  // Abrir generador de PDF en nueva pestaña
  var url = `vista/fpdf/reportes_p.php?tabla=${tabla}&filtros=${encodeURIComponent(
    JSON.stringify(filtros)
  )}&campos=${encodeURIComponent(JSON.stringify(campos))}`;
  window.open(url, "_blank");
}

// Funciones auxiliares (similares a pasantias.js)
function enviaAjax(datos, callback) {
  $.ajax({
    async: true,
    url: "?pagina=reportes_p",
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
