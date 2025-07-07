function destruyeDT(){
	if ($.fn.DataTable.isDataTable("#tablarespuesta")) {
            $("#tablarespuesta").DataTable().destroy();
    }
}
function crearDT(){
    if (!$.fn.DataTable.isDataTable("#tablarespuesta")) {
            $("#tablarespuesta").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontró ninguna Emergencia",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay emergencias registradas",
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

function insertarCabecera(modulo) {
    let cabecera = '';
    switch(modulo) {
        case 'pacientes':
            cabecera = `
                <th class="text-center">Cédula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Fecha Nac.</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Dirección</th>
            `;
            break;
        case 'personal':
            cabecera = `
                <th class="text-center">Cédula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Cargo</th>
                <th class="text-center">Correo</th>
            `;
            break;
        case 'emergencias':
            cabecera = `
                <th class="text-center">Hora ingreso</th>
                <th class="text-center">Fecha ingreso</th>
                <th class="text-center">Motivo de ingreso</th>
                <th class="text-center">Diagnóstico</th>
                <th class="text-center">Tratamientos</th>
                <th class="text-center">Procedimientos</th>
                <th class="text-center">Cédula paciente</th>
                <th class="text-center">Cédula personal</th>
            `;
            break;
        // Agrega más módulos según necesites...
        default:
            cabecera = `<th class="text-center">Resultados</th>`;
    }
    $("#cabecerastable").html(cabecera);
}


$(document).ready(function() {
    // ...otros listeners...

    $("#busqueda").on("click", function() {
        // Obtén los valores de los campos
        var modulo = $("#modulo").val();
        var texto = $("#selectParametro").val();
        var mes = $("#selectMes").val();
        var ano = $("#selectAnio").val();
        insertarCabecera($("#modulo").val());

         switch(modulo) {
        case "emergencias":
            console.log("holaa");
            break;
        default:
            console.log("default case");
            break;
        }

        // Llama a tu función de búsqueda (ajusta el nombre si es diferente)
        buscarReporte(modulo, texto, mes, ano);
        console.log(modulo, texto, mes, ano);
    });

    $("#modulo").on("change", function() {
        insertarCabecera($(this).val());
    });

    // O al hacer click en el botón de búsqueda:
   
});

// Ejemplo de función de búsqueda
function buscarReporte(modulo, texto, mes, ano) {
    var datos = new FormData();
    datos.append('modulo', modulo);
    datos.append('texto', texto);
    datos.append('mes', mes);
    datos.append('ano', ano);

    // Aquí tu AJAX o llamada a enviaAjax(datos)
    enviaAjax(datos);
}

function enviaAjax(datos) {
    $.ajax({
        url: "", // o la URL de tu backend
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(respuesta) {
            try{
                if (respuesta.resultado == "buscar_emergencias") {
                    console.log("Resultados encontrados");
                   
                    var html = '';
                    respuesta.datos.forEach(function(fila) {
                        html += `<tr>
                            <td>${fila.horaingreso}</td>
                            <td>${fila.fechaingreso}</td>
                            <td>${fila.motingreso}</td>
                            <td>${fila.diagnostico_e}</td>
                            <td>${fila.tratamientos}</td>
                            <td>${fila.procedimiento}</td>
                            <td>${fila.cedula_paciente}</td>
                            <td>${fila.cedula_personal}</td>
                        </tr>`;
                    });
                    $("#resultadoconsulta").html(html);
                    // Aquí puedes agregar el código para mostrar los resultados
                    
                }
                else {
                    console.log("No se encontraron resultados ", respuesta);
                }
            }catch (e){
                alert("Error en JSON " + e.name);
            }
        },
       error: function (request, status, err) {
      
        if (status == "timeout") {
            
            muestraMensaje("Servidor ocupado, intente de nuevo");
        } else {
            
            muestraMensaje("ERROR: <br/>" + request + status + err);
        }
        },
        complete: function () {},
    });
}


	
















/* $(document).ready(function() {
    // Inicializar DataTable
    var dataTable = $('#tablaResultados').DataTable({
        dom: '<"top"lf>rt<"bottom"ip>',
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar en resultados:",
            paginate: {
                first: "Primera",
                last: "Última",
                next: "Siguiente",
                previous: "Anterior"
            },
            loadingRecords: "Cargando...",
            processing: "Procesando..."
        },
        responsive: true,
        autoWidth: false,
        pagingType: "full_numbers"
    });

    // Evento para el botón de búsqueda
    $('#btnBuscar').on('click', function() {
        buscar();
    });

    // Buscar al presionar Enter en el campo de texto
    $('#texto').on('keypress', function(e) {
        if(e.which == 13) {
            buscar();
            e.preventDefault();
        }
    });

    // Evento para el botón de exportar a Excel
    $('#btnExportExcel').on('click', function() {
        exportarExcel();
    });

    // Evento para el botón de exportar a PDF
    $('#btnExportPDF').on('click', function() {
        exportarPDF();
    });

    // Evento para limpiar la búsqueda
    $('button[type="reset"]').on('click', function() {
        setTimeout(function() {
            dataTable.clear().draw();
            $('#cabeceraResultados').html('<th class="text-center">Seleccione un módulo y realice una búsqueda</th>');
            $('#resultadosBusqueda').html('<tr><td colspan="1" class="text-center text-muted">No hay resultados para mostrar</td></tr>');
            $('#exportButtons').hide();
        }, 100);
    });
});

function buscar() {
    var modulo = $('#modulo').val();
    var texto = $('#texto').val();
    var mes = $('#mes').val();
    var ano = $('#ano').val();
    
    if(!modulo) {
        muestraMensaje("Debe seleccionar un módulo para buscar");
        return;
    }
    
    if(!texto && (!mes || !ano)) {
        muestraMensaje("Debe ingresar un texto de búsqueda o seleccionar mes y año");
        return;
    }
    
    // Mostrar carga
    $('#resultadosBusqueda').html('<tr><td colspan="10" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>');
    
    var datos = new FormData();
    datos.append('accion', 'buscar');
    datos.append('modulo', modulo);
    datos.append('texto', texto);
    datos.append('mes', mes);
    datos.append('ano', ano);
    
    enviaAjax(datos);
}

function enviaAjax(datos) {
    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json', // <-- Esto hace que respuesta ya sea un objeto
        success: function(respuesta) {
            // respuesta ya es un objeto, úsalo directamente
            if (respuesta.resultado == "buscar") {
                // Destruir la tabla existente
                if ($.fn.DataTable.isDataTable("#tablaResultados")) {
                    $('#tablaResultados').DataTable().destroy();
                }
                
                // Generar cabecera según el módulo
                var cabecera = generarCabecera(respuesta.datos, respuesta.modulo);
                $('#cabeceraResultados').html(cabecera);
                
                // Generar filas de resultados
                var html = '';
                if(respuesta.datos.length > 0) {
                    respuesta.datos.forEach(function(fila) {
                        html += generarFila(fila, respuesta.modulo);
                    });
                    $('#exportButtons').show();
                } else {
                    // Calcula el número de columnas según la cabecera
                    var colCount = $('#cabeceraResultados th').length || 1;
                    html = `<tr><td colspan="${colCount}" class="text-center text-muted">No se encontraron resultados</td></tr>`;
                    $('#exportButtons').hide();
                }
                $('#resultadosBusqueda').html(html);
                
                // Recrear DataTable
                $('#tablaResultados').DataTable({
                    dom: '<"top"lf>rt<"bottom"ip>',
                    language: {
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        zeroRecords: "No se encontraron resultados",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        search: "Buscar en resultados:",
                        paginate: {
                            first: "Primera",
                            last: "Última",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    responsive: true,
                    autoWidth: false,
                    pagingType: "full_numbers"
                });
            } else if (respuesta.resultado == "error") {
                muestraMensaje(respuesta.mensaje);
                $('#resultadosBusqueda').html('<tr><td colspan="10" class="text-center text-danger">Error al cargar los datos</td></tr>');
                $('#exportButtons').hide();
            }
        },
        error: function(xhr, status, error) {
            muestraMensaje("Error en la solicitud: " + error);
        },
        complete: function() {
            $('#btnBuscar').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Buscar');
        }
    });
}

function generarCabecera(datos, modulo) {
    var cabecera = '';
    
    switch(modulo) {
        case 'pacientes':
            cabecera = `
                <th class="text-center">Cédula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Fecha Nac.</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Dirección</th>
            `;
            break;
            
        case 'personal':
            cabecera = `
                <th class="text-center">Cédula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Cargo</th>
                <th class="text-center">Correo</th>
            `;
            break;
            
        case 'emergencias':
            cabecera = `
                <th class="text-center">Fecha</th>
                <th class="text-center">Hora</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Personal</th>
                <th class="text-center">Motivo</th>
                <th class="text-center">Diagnóstico</th>
                <th class="text-center">Tratamiento</th>
            `;
            break;
            
        case 'consultas':
            cabecera = `
                <th class="text-center">Código</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Hora</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Personal</th>
                <th class="text-center">Consulta</th>
                <th class="text-center">Diagnóstico</th>
                <th class="text-center">Tratamiento</th>
            `;
            break;
            
        case 'inventario':
            cabecera = `
                <th class="text-center">Fecha</th>
                <th class="text-center">Hora</th>
                <th class="text-center">Medicamento</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Vencimiento</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Personal</th>
            `;
            break;
            
        case 'pasantias':
            cabecera = `
                <th class="text-center">Área</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Responsable</th>
            `;
            break;
            
        case 'jornadas':
            cabecera = `
                <th class="text-center">Fecha</th>
                <th class="text-center">Ubicación</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Total Pacientes</th>
            `;
            break;
            
        case 'examenes':
            cabecera = `
                <th class="text-center">Fecha</th>
                <th class="text-center">Hora</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Examen</th>
                <th class="text-center">Observación</th>
            `;
            break;
            
        case 'patologias':
            cabecera = `
                <th class="text-center">Paciente</th>
                <th class="text-center">Patología</th>
                <th class="text-center">Tratamiento</th>
                <th class="text-center">Administración</th>
            `;
            break;
            
        default:
            if(datos.length > 0) {
                var keys = Object.keys(datos[0]);
                keys = keys.filter(key => !['id', 'old_'].some(prefix => key.startsWith(prefix)));
                keys.forEach(function(key) {
                    cabecera += '<th class="text-center">' + key.replace(/_/g, ' ').toUpperCase() + '</th>';
                });
            } else {
                cabecera = '<th class="text-center">Resultados</th>';
            }
    }
    
    return cabecera;
}

function generarFila(fila, modulo) {
    var html = '<tr>';
    
    switch(modulo) {
        case 'pacientes':
            html += `
                <td class="text-center">${fila.cedula_paciente}</td>
                <td>${fila.nombre}</td>
                <td>${fila.apellido}</td>
                <td class="text-center">${formatearFecha(fila.fecha_nac)}</td>
                <td class="text-center">${fila.telefono || 'N/A'}</td>
                <td>${fila.direccion || 'N/A'}</td>
            `;
            break;
            
        case 'personal':
            html += `
                <td class="text-center">${fila.cedula_personal}</td>
                <td>${fila.nombre}</td>
                <td>${fila.apellido}</td>
                <td class="text-center">${fila.cargo || 'N/A'}</td>
                <td>${fila.correo || 'N/A'}</td>
            `;
            break;
            
        case 'emergencias':
            html += `
                <td class="text-center">${formatearFecha(fila.fechaingreso)}</td>
                <td class="text-center">${fila.horaingreso}</td>
                <td>${fila.nombre_paciente} ${fila.apellido_paciente}</td>
                <td>${fila.nombre_personal} ${fila.apellido_personal}</td>
                <td>${fila.motingreso || 'N/A'}</td>
                <td>${fila.diagnostico_e || 'N/A'}</td>
                <td>${fila.tratamientos || 'N/A'}</td>
            `;
            break;
            
        case 'consultas':
            html += `
                <td class="text-center">${fila.cod_consulta}</td>
                <td class="text-center">${formatearFecha(fila.fechaconsulta)}</td>
                <td class="text-center">${fila.Horaconsulta}</td>
                <td>${fila.nombre_paciente} ${fila.apellido_paciente}</td>
                <td>${fila.nombre_personal} ${fila.apellido_personal}</td>
                <td>${fila.consulta || 'N/A'}</td>
                <td>${fila.diagnostico || 'N/A'}</td>
                <td>${fila.tratamientos || 'N/A'}</td>
            `;
            break;
            
        case 'inventario':
            html += `
                <td class="text-center">${formatearFecha(fila.fecha)}</td>
                <td class="text-center">${fila.hora}</td>
                <td>${fila.medicamento}</td>
                <td class="text-center">${fila.cantidad_salida}</td>
                <td class="text-center">${formatearFecha(fila.fecha_vencimiento)}</td>
                <td>${fila.proveedor}</td>
                <td>${fila.nombre_personal} ${fila.apellido_personal}</td>
            `;
            break;
            
        case 'pasantias':
            html += `
                <td>${fila.nombre_area}</td>
                <td>${fila.descripcion || 'N/A'}</td>
                <td>${fila.nombre_responsable} ${fila.apellido_responsable}</td>
            `;
            break;
            
        case 'jornadas':
            html += `
                <td class="text-center">${formatearFecha(fila.fecha_jornada)}</td>
                <td>${fila.ubicacion}</td>
                <td>${fila.descripcion || 'N/A'}</td>
                <td class="text-center">${fila.total_pacientes}</td>
            `;
            break;
            
        case 'examenes':
            html += `
                <td class="text-center">${formatearFecha(fila.fecha_e)}</td>
                <td class="text-center">${fila.hora_e}</td>
                <td>${fila.nombre_paciente} ${fila.apellido_paciente}</td>
                <td>${fila.nombre_examen}</td>
                <td>${fila.observacion_examen || 'N/A'}</td>
            `;
            break;
            
        case 'patologias':
            html += `
                <td>${fila.nombre_paciente} ${fila.apellido_paciente}</td>
                <td>${fila.nombre_patologia}</td>
                <td>${fila.tratamiento || 'N/A'}</td>
                <td>${fila.administracion_t || 'N/A'}</td>
            `;
            break;
            
        default:
            if(Object.keys(fila).length > 0) {
                var keys = Object.keys(fila).filter(key => !['id', 'old_'].some(prefix => key.startsWith(prefix)));
                keys.forEach(function(key) {
                    if(key.toLowerCase().includes('fecha')) {
                        html += '<td class="text-center">' + formatearFecha(fila[key]) + '</td>';
                    } else {
                        html += '<td>' + (fila[key] || 'N/A') + '</td>';
                    }
                });
            } else {
                html = '<td colspan="10" class="text-center text-muted">No se encontraron resultados</td>';
            }
    }
    
    html += '</tr>';
    return html;
}

function formatearFecha(fechaString) {
    if(!fechaString) return 'N/A';
    
    try {
        const fecha = new Date(fechaString);
        return fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    } catch(e) {
        return fechaString;
    }
}

function exportarExcel() {
    // Obtener los datos de la tabla
    var table = document.getElementById("tablaResultados");
    var workbook = XLSX.utils.table_to_book(table, {sheet: "Reporte"});
    
    // Generar nombre de archivo con fecha
    var fecha = new Date();
    var nombreArchivo = 'Reporte_' + fecha.getFullYear() + 
                       ('0' + (fecha.getMonth()+1)).slice(-2) + 
                       ('0' + fecha.getDate()).slice(-2) + '_' + 
                       ('0' + fecha.getHours()).slice(-2) + 
                       ('0' + fecha.getMinutes()).slice(-2) + '.xlsx';
    
    // Descargar el archivo
    XLSX.writeFile(workbook, nombreArchivo);
}

function exportarPDF() {
    // Configurar el documento PDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'landscape'
    });
    
    // Obtener datos de la tabla
    var headers = [];
    var rows = [];
    
    // Obtener encabezados
    $('#cabeceraResultados th').each(function() {
        headers.push($(this).text().trim());
    });
    
    // Obtener filas de datos
    $('#tablaResultados tbody tr').each(function() {
        var row = [];
        $(this).find('td').each(function() {
            row.push($(this).text().trim());
        });
        rows.push(row);
    });
    
    // Configurar opciones
    var options = {
        head: [headers],
        body: rows,
        margin: { top: 20 },
        startY: 20,
        styles: {
            fontSize: 8,
            cellPadding: 1,
            overflow: 'linebreak'
        },
        columnStyles: {
            0: { cellWidth: 'auto' },
            1: { cellWidth: 'auto' },
            // Ajustar anchos de columna según necesidad
        },
        didDrawPage: function(data) {
            // Título del reporte
            doc.setFontSize(12);
            doc.setTextColor(40);
            doc.text('Reporte Parametrizado', data.settings.margin.left, 10);
            
            // Fecha y hora
            var fecha = new Date();
            var fechaStr = fecha.toLocaleDateString('es-ES') + ' ' + fecha.toLocaleTimeString('es-ES');
            doc.setFontSize(8);
            doc.text('Generado: ' + fechaStr, doc.internal.pageSize.width - 20, 10, { align: 'right' });
        }
    };
    
    // Generar PDF
    doc.autoTable(options);
    
    // Guardar PDF
    var nombreArchivo = 'Reporte_' + new Date().getTime() + '.pdf';
    doc.save(nombreArchivo);
}

function muestraMensaje(mensaje) {
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    }, 5000);
} */