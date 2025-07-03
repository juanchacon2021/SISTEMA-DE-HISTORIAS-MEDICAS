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
                                    <button class="btn botonrojo" onclick="abrirModalSeleccionPDF('${p.cedula_paciente}')">
                                        <img src="img/descarga.svg" style="width: 20px;"> PDF Personalizado
                                    </button>
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

// Función para abrir el modal de selección de PDF
function abrirModalSeleccionPDF(cedula) {
    $('#pdf_cedula_paciente').val(cedula);
    // Por defecto selecciona datos personales
    $('#formSeleccionPDF input[type=checkbox]').prop('checked', false);
    $('#checkDatosPersonales').prop('checked', true);
    $('#modalSeleccionPDF').modal('show');
}

// Evento para generar el PDF personalizado
$(document).on('click', '#btnGenerarPDF', function() {
    const cedula = $('#pdf_cedula_paciente').val();
    const secciones = [];
    $('#formSeleccionPDF input[type=checkbox]:checked').each(function() {
        secciones.push($(this).val());
    });
    if (secciones.length === 0) {
        alert('Seleccione al menos una sección');
        return;
    }
    window.open('vista/fpdf/historia_medica.php?cedula_paciente=' + cedula + '&secciones=' + secciones.join(','), '_blank');
    $('#modalSeleccionPDF').modal('hide');
});

// Inicialización
$(document).ready(function() {
    consultar();
    setInterval(consultar, 60000); // Actualiza cada minuto
});