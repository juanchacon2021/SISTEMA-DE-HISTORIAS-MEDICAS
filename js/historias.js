
// Función para consultar pacientes
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

// Función para destruir DataTable si existe
function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablahistorias")) {
        $("#tablahistorias").DataTable().destroy();
    }
}

// Función para crear DataTable
function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablahistorias")) {
        $("#tablahistorias").DataTable({
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

// Función para enviar datos por AJAX
function enviaAjax(datos) {
    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function(res) {
            console.log("Respuesta AJAX:", res); // <-- Agrega esto
            try {
                let data = JSON.parse(res);
                if (data.resultado === 'consultar') {
                    destruyeDT();
                    let html = '';
                    data.datos.forEach(function(p) {
                        html += `
                            <tr>
                                <td>${p.cedula_paciente}</td>
                                <td>${p.nombre}</td>
                                <td>${p.apellido}</td>
                                <td>${p.fecha_nac}</td>
                                <td>${p.edad}</td>
                                <td>${p.telefono}</td>
                                <td>
                                    <a class="btn btn-danger" href="vista/fpdf/historia_medica.php?cedula_paciente=${p.cedula_paciente}" target="_blank">
                                        <img src="img/descarga.svg" style="width: 20px;">
                                    </a>
                                </td>
                            </tr>`;
                    });
                    $("#resultadoHistorias").html(html);
                    crearDT();
                }
            } catch (e) {
                console.error("Error en JSON:", res);
            }
        },
        error: function(request, status, err) {
            console.error("Error AJAX:", err);
        }
    });
}

// Inicialización
$(document).ready(function() {
    consultar();
    setInterval(consultar, 60000); // Actualiza cada minuto
});