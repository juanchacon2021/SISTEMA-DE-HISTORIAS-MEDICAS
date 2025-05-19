// Variable global para guardar el código a eliminar
let codMedicamentoAEliminar = null;

function consultarMedicamentos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_medicamentos');
    $.ajax({
        url: '', 
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar_medicamentos") {
                    destruyeDT();
                    var html = '';
                    lee.datos.forEach(function(fila) {
                        html += `<tr>
                            <td>
                                <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                                    <a type="button" class="btn btn-success" onclick="editarMedicamento(${fila.cod_medicamento})">
                                        <img src="img/lapiz.svg" style="width: 20px">
                                    </a>
                                    <a type="button" class="btn btn-danger" onclick="confirmarEliminacion(${fila.cod_medicamento})">
                                        <img src="img/basura.svg" style="width: 20px">
                                    </a>
                                </div>
                            </td>
                            <td>${fila.cod_medicamento}</td>
                            <td>${fila.nombre}</td>
                            <td>${fila.descripcion}</td>
                            <td>${fila.cantidad}</td>
                            <td>${fila.unidad_medida}</td>
                            <td>${fila.fecha_vencimiento ? new Date(fila.fecha_vencimiento).toLocaleDateString() : 'N/A'}</td>
                            <td>${fila.lote}</td>
                            <td>${fila.proveedor}</td>
                        </tr>`;
                    });
                    $("#resultadoMedicamentos").html(html);
                    crearDT();
                }
            } catch (e) {
                muestraMensaje("Error al actualizar la tabla de medicamentos");
            }
        },
        error: function(xhr, status, error) {
            muestraMensaje("Error en la petición AJAX al actualizar la tabla");
        }
    });
}

function consultarTransacciones() {
    var datos = new FormData();
    datos.append('accion', 'consultar_transacciones');
    enviaAjax(datos);
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablaMedicamentos")) {
        $("#tablaMedicamentos").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablaMedicamentos")) {
        $("#tablaMedicamentos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron medicamentos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay medicamentos registrados",
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

function destruyeDTTransacciones() {
    if ($.fn.DataTable.isDataTable("#tablaTransacciones")) {
        $("#tablaTransacciones").DataTable().destroy();
    }
}

function crearDTTransacciones() {
    if (!$.fn.DataTable.isDataTable("#tablaTransacciones")) {
        $("#tablaTransacciones").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron transacciones",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay transacciones registradas",
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
            order: [[1, "desc"]],
        });
    }
}

function mostrarFormularioMedicamento() {
    limpiaFormulario();
    $("#procesoMedicamento").text("INCLUIR");
    $("#modalMedicamentoLabel").text("Agregar Nuevo Medicamento");
    $("#modalMedicamento").modal("show");
}

function editarMedicamento(cod_medicamento) {
    var datos = new FormData();
    datos.append('accion', 'obtener_medicamento');
    datos.append('cod_medicamento', cod_medicamento);
    enviaAjax(datos);
}

function confirmarEliminacion(cod_medicamento) {
    codMedicamentoAEliminar = cod_medicamento;
    $('#modalConfirmacion').modal('show');
}

// Al hacer clic en el botón "Eliminar" del modal
$('#btnConfirmarEliminar').off('click').on('click', function() {
    if (codMedicamentoAEliminar) {
        var datos = new FormData();
        datos.append('accion', 'eliminar_medicamento');
        datos.append('cod_medicamento', codMedicamentoAEliminar);
        enviaAjax(datos);
        codMedicamentoAEliminar = null;
        $('#modalConfirmacion').modal('hide');
    }
});

function limpiaFormulario() {
    $("#cod_medicamento").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#cantidad").val("");
    $("#unidad_medida").val("");
    $("#fecha_vencimiento").val("");
    $("#lote").val("");
    $("#proveedor").val("");
}

function validarFormulario() {
    // Implementa tus validaciones aquí
    return true;
}

$(document).ready(function() {
    consultarMedicamentos();
    consultarTransacciones();

    $("#incluirMedicamento").on("click", function() {
        mostrarFormularioMedicamento();
    });

    $("#procesoMedicamento").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            if (validarFormulario()) {
                var datos = new FormData($("#formMedicamento")[0]);
                datos.append('accion', 'incluir_medicamento');
                enviaAjax(datos);
            }
        } else if ($(this).text() == "MODIFICAR") {
            if (validarFormulario()) {
                var datos = new FormData($("#formMedicamento")[0]);
                datos.append('accion', 'modificar_medicamento');
                enviaAjax(datos);
            }
        }
    });
});

function muestraMensaje(mensaje) {
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    }, 2000);
}

function enviaAjax(datos) {
    $.ajax({
        url: '', 
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            procesarRespuesta(respuesta);  
        },
        error: function(xhr, status, error) {
            console.log('Error en la petición AJAX: ', error);
        }
    });
}

function procesarRespuesta(respuesta) {
    try {
        var lee = JSON.parse(respuesta);

        if (lee.resultado == "consultar_medicamentos") {
            destruyeDT();
            var html = '';
            lee.datos.forEach(function(fila) {
                html += `<tr>
                    <td>
                        <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                            <a type="button" class="btn btn-success" onclick="editarMedicamento(${fila.cod_medicamento})">
                                <img src="img/lapiz.svg" style="width: 20px">
                            </a>
                            <a type="button" class="btn btn-danger" onclick="confirmarEliminacion(${fila.cod_medicamento})">
                                <img src="img/basura.svg" style="width: 20px">
                            </a>
                        </div>
                    </td>
                    <td>${fila.cod_medicamento}</td>
                    <td>${fila.nombre}</td>
                    <td>${fila.descripcion}</td>
                    <td>${fila.cantidad}</td>
                    <td>${fila.unidad_medida}</td>
                    <td>${fila.fecha_vencimiento ? new Date(fila.fecha_vencimiento).toLocaleDateString() : 'N/A'}</td>
                    <td>${fila.lote}</td>
                    <td>${fila.proveedor}</td>
                </tr>`;
            });
            $("#resultadoMedicamentos").html(html);
            crearDT();
        }
        else if (lee.resultado == "consultar_transacciones") {
            destruyeDTTransacciones();
            var html = '';
            lee.datos.forEach(function(fila) {
                var tipo = '';
                switch(fila.tipo_transaccion) {
                    case 'entrada': tipo = '<span class="badge bg-success">Entrada</span>'; break;
                    case 'salida': tipo = '<span class="badge bg-danger">Salida</span>'; break;
                    case 'ajuste_positivo': tipo = '<span class="badge bg-primary">Ajuste +</span>'; break;
                    case 'ajuste_negativo': tipo = '<span class="badge bg-warning text-dark">Ajuste -</span>'; break;
                    default: tipo = fila.tipo_transaccion;
                }
                html += `<tr>
                    <td>${fila.cod_transaccion}</td>
                    <td>${new Date(fila.fecha).toLocaleDateString()}</td>
                    <td>${fila.hora}</td>
                    <td>${tipo}</td>
                    <td>${fila.medicamento}</td>
                    <td>${fila.cantidad}</td>
                    <td>${fila.nombre} ${fila.apellido}</td>
                </tr>`;
            });
            $("#resultadoTransacciones").html(html);
            crearDTTransacciones();
        }
        else if (lee.resultado == "obtener_medicamento") {
            $("#cod_medicamento").val(lee.datos.cod_medicamento);
            $("#nombre").val(lee.datos.nombre);
            $("#descripcion").val(lee.datos.descripcion);
            $("#cantidad").val(lee.datos.cantidad);
            $("#unidad_medida").val(lee.datos.unidad_medida);
            $("#fecha_vencimiento").val(lee.datos.fecha_vencimiento);
            $("#lote").val(lee.datos.lote);
            $("#proveedor").val(lee.datos.proveedor);

            $("#procesoMedicamento").text("MODIFICAR");
            $("#modalMedicamentoLabel").text("Editar Medicamento");
            $("#modalMedicamento").modal("show");
        }
        else if (
            lee.resultado == "incluir_medicamento" ||
            lee.resultado == "modificar_medicamento" ||
            lee.resultado == "eliminar_medicamento"
        ) {
            muestraMensaje(lee.mensaje);
            $("#modalMedicamento").modal("hide");
            $('#modalConfirmacion').modal("hide");
            consultarMedicamentos();
            consultarTransacciones();
        }
        else if (lee.resultado == "error") {
            muestraMensaje(lee.mensaje);
        }
    } catch (e) {
        muestraMensaje("Error al procesar la respuesta del servidor");
    }
}