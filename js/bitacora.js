$(document).ready(function () {
  var tabla = $("#tablaBitacora").DataTable({
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
    processing: true,
    serverSide: false,
    ajax: {
      url: "?pagina=bitacora",
      type: "POST",
      data: function (d) {
        return {
          accion: "consultar",
          filtro: $("#filtroBitacora").val(),
        };
      },
      dataSrc: "data", // Cambiado de 'datos' a 'data'
    },
    columns: [
      {
        data: "fecha_hora",
        render: function (data) {
          return new Date(data).toLocaleString();
        },
      },
      { data: "usuario_nombre" },
      { data: "usuario_rol" },
      { data: "modulo_nombre" },
      { data: "accion" },
      { data: "descripcion" },
    ],
    order: [[0, "desc"]],
    responsive: true,
    autoWidth: false,
  });

  $("#btnFiltrar").click(function () {
    tabla.ajax.reload();
  });

  $("#btnRecargar").click(function () {
    $("#filtroBitacora").val("");
    tabla.ajax.reload();
  });

  $("#filtroBitacora").keypress(function (e) {
    if (e.which == 13) {
      tabla.ajax.reload();
    }
  });
});
