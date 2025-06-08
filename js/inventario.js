// Variable global para guardar el código a eliminar
let codMedicamentoAEliminar = null;
let modoEntradaSalida = null;
let lotesTemporales = [];
let salidasTemporales = [];

function consultarMedicamentos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_medicamentos');
    enviaAjax(datos);
}

function consultarLotesMedicamento(cod_medicamento) {
    var datos = new FormData();
    datos.append('accion', 'consultar_lotes');
    datos.append('cod_medicamento', cod_medicamento);
    enviaAjax(datos);
}

function consultarMovimientos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_movimientos');
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

function destruyeDTMovimientos() {
    if ($.fn.DataTable.isDataTable("#tablaMovimientos")) {
        $("#tablaMovimientos").DataTable().destroy();
    }
}

function crearDTMovimientos() {
    if (!$.fn.DataTable.isDataTable("#tablaMovimientos")) {
        $("#tablaMovimientos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron movimientos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay movimientos registrados",
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

function mostrarFormularioEntradaSalida(cod_medicamento, modo) {
    modoEntradaSalida = modo;
    limpiaFormularioEntradaSalida();
    
    var datos = new FormData();
    datos.append('accion', 'obtener_medicamento');
    datos.append('cod_medicamento', cod_medicamento);
    
    $.ajax({
        url: '', 
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            try {
                var lee = res;
                if (lee.resultado == "obtener_medicamento") {
                    $("#cod_medicamento_es").val(lee.datos.cod_medicamento);
                    $("#nombre_medicamento_es").val(lee.datos.nombre);
                    
                    if(modo == 'entrada') {
                        $("#modalEntradaSalidaLabel").text("Registrar Entrada de Medicamento");
                        $("#btnProcesarEntradaSalida").text("Registrar Entrada").removeClass("btn-danger").addClass("btn-success");
                        $("#camposEntrada").show();
                    } else {
                        $("#modalEntradaSalidaLabel").text("Registrar Salida de Medicamento");
                        $("#btnProcesarEntradaSalida").text("Registrar Salida").removeClass("btn-success").addClass("btn-danger");
                        $("#camposEntrada").hide();
                        
                        // Mostrar lotes disponibles
                        if(lee.lotes && lee.lotes.length > 0) {
                            var html = '<div class="mb-3"><label class="form-label">Lotes Disponibles</label><div class="list-group">';
                            lee.lotes.forEach(function(lote) {
                                html += `<div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Lote ${lote.cod_lote} - Vence: ${new Date(lote.fecha_vencimiento).toLocaleDateString()}</span>
                                        <span class="badge bg-primary">${lote.cantidad} ${lee.datos.unidad_medida}</span>
                                    </div>
                                    <small class="text-muted">Proveedor: ${lote.proveedor}</small>
                                </div>`;
                            });
                            html += '</div></div>';
                            $("#infoLotes").html(html);
                        } else {
                            $("#infoLotes").html('<div class="alert alert-warning">No hay lotes disponibles</div>');
                        }
                    }
                    
                    $("#modalEntradaSalida").modal("show");
                }
            } catch (e) {
                muestraMensaje("Error al procesar los datos del medicamento");
            }
        },
        error: function(xhr, status, error) {
            muestraMensaje("Error en la petición AJAX");
        }
    });
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

function procesarEntradaSalida() {
    if(!validarFormularioEntradaSalida()) return;
    
    var datos = new FormData($("#formEntradaSalida")[0]);
    
    if(modoEntradaSalida == 'entrada') {
        datos.append('accion', 'registrar_entrada');
    } else {
        datos.append('accion', 'registrar_salida');
    }
    
    enviaAjax(datos);
}

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
    $("#unidad_medida").val("");
}

function limpiaFormularioEntradaSalida() {
    $("#cod_medicamento_es").val("");
    $("#nombre_medicamento_es").val("");
    $("#cantidad").val("");
    $("#fecha_vencimiento").val("");
    $("#proveedor").val("");
    $("#infoLotes").html("");
}

function validarFormulario() {
    if($("#nombre").val().trim() == "") {
        muestraMensaje("El nombre del medicamento es requerido");
        return false;
    }
    if($("#unidad_medida").val() == "") {
        muestraMensaje("Debe seleccionar una unidad de medida");
        return false;
    }
    return true;
}

function validarFormularioEntradaSalida() {
    if($("#cantidad").val() <= 0) {
        muestraMensaje("La cantidad debe ser mayor a cero");
        return false;
    }
    if(modoEntradaSalida == 'entrada' && $("#fecha_vencimiento").val() == "") {
        muestraMensaje("La fecha de vencimiento es requerida para entradas");
        return false;
    }
    if(modoEntradaSalida == 'entrada' && $("#proveedor").val().trim() == "") {
        muestraMensaje("El proveedor es requerido para entradas");
        return false;
    }
    return true;
}

$(document).ready(function() {
    consultarMedicamentos();
    consultarMovimientos();

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
    
    $("#btnProcesarEntradaSalida").on("click", function() {
        // Si hay lotes temporales, procesar todos juntos
        if (lotesTemporales.length > 0) {
            var datos = new FormData();
            datos.append('accion', 'registrar_entrada_multiple');
            datos.append('lotes', JSON.stringify(lotesTemporales));
            enviaAjax(datos);
            lotesTemporales = [];
            actualizarListaLotesTemporales();
            $("#modalEntradaSalida").modal("hide");
        } else {
            // Si no hay lotes temporales, validar el formulario individual
            if (!validarFormularioEntradaSalida()) return;
            var datos = new FormData($("#formEntradaSalida")[0]);
            datos.append('accion', 'registrar_entrada');
            enviaAjax(datos);
            $("#modalEntradaSalida").modal("hide");
        }
    });
    
    $("#selectMedicamentoSalida").on("change", function() {
        let cod = $(this).val();
        if (cod) {
            // Cargar lotes disponibles para ese medicamento
            var datos = new FormData();
            datos.append('accion', 'consultar_lotes');
            datos.append('cod_medicamento', cod);
            $.ajax({
                url: '',
                type: 'POST',
                data: datos,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    try {
                        var lee = res;
                        if (lee.resultado == "consultar_lotes") {
                            let html = '';
                            lee.datos.forEach(function(lote) {
                                html += `<div>
                                    <input type="checkbox" class="chkLoteSalida" value="${lote.cod_lote}" data-max="${lote.cantidad}">
                                    Lote ${lote.cod_lote} - Vence: ${new Date(lote.fecha_vencimiento).toLocaleDateString()} - Stock: ${lote.cantidad}
                                    <input type="number" min="1" max="${lote.cantidad}" class="inputCantidadLoteSalida" data-cod="${lote.cod_lote}" placeholder="Cantidad a retirar" style="width:120px;">
                                </div>`;
                            });
                            $("#lotesDisponiblesSalida").html(html);
                        }
                    } catch (e) {
                        muestraMensaje("Error al cargar lotes");
                    }
                }
            });
        } else {
            $("#lotesDisponiblesSalida").html("");
        }
    });
    
    $("#btnProcesarSalidaGlobal").on("click", function() {
        if (salidasTemporales.length === 0) {
            muestraMensaje("Debe agregar al menos un lote a la salida");
            return;
        }
        var datos = new FormData();
        datos.append('accion', 'registrar_salida_multiple');
        datos.append('salidas', JSON.stringify(salidasTemporales));
        enviaAjax(datos);
        $("#modalSalidaGlobal").modal("hide");
    });
    
    $("#btnAgregarLoteSalida").on("click", function() {
        // Recorre los checkboxes de lotes seleccionados
        $(".chkLoteSalida:checked").each(function() {
            let cod_lote = $(this).val();
            let cantidad = $(`.inputCantidadLoteSalida[data-cod='${cod_lote}']`).val();
            let max = parseInt($(this).data("max"));
            cantidad = parseInt(cantidad);

            if (!cantidad || cantidad <= 0 || cantidad > max) {
                muestraMensaje("Cantidad inválida para el lote " + cod_lote);
                return;
            }

            // Evita duplicados
            if (salidasTemporales.some(s => s.cod_lote == cod_lote)) {
                muestraMensaje("Ya agregaste este lote a la salida");
                return;
            }

            salidasTemporales.push({
                cod_lote: cod_lote,
                cantidad: cantidad
            });
        });

        // Actualiza la lista visual de lotes agregados a la salida
        actualizarListaSalidasTemporales();
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
        dataType: 'json',
        success: function(res) {
            console.log("Respuesta cruda:", res);
            procesarRespuesta(res);  
        },
        error: function(xhr, status, error) {
            console.log('Error en la petición AJAX: ', error);
        }
    });
}

function procesarRespuesta(respuesta) {
    try {
        var lee = respuesta;

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
                            <a type="button" class="btn btn-primary" onclick="mostrarFormularioEntrada(${fila.cod_medicamento}, '${fila.nombre.replace(/'/g, "\\'")}')">
                                <img src="img/mas.svg" style="width: 20px">
                            </a>
                            <a type="button" class="btn btn-danger" onclick="confirmarEliminacion(${fila.cod_medicamento})">
                                <img src="img/basura.svg" style="width: 20px">
                            </a>
                        </div>
                    </td>
                    <td>${fila.cod_medicamento}</td>
                    <td>${fila.nombre}</td>
                    <td>${fila.descripcion}</td>
                    <td>${fila.stock_total || 0}</td>
                    <td>${fila.unidad_medida}</td>
                </tr>`;
            });
            $("#resultadoMedicamentos").html(html);
            crearDT();
        }
        else if (lee.resultado == "consultar_movimientos") {
            destruyeDTMovimientos();
            var html = '';
            lee.datos.forEach(function(fila) {
                var tipo = '';
                var tipoLower = fila.tipo.toLowerCase();
                
                if (tipoLower.includes('entrada')) {
                    tipo = '<span class="badge bg-success">Entrada</span>';
                } else if (tipoLower.includes('salida')) {
                    tipo = '<span class="badge bg-danger">Salida</span>';
                } else {
                    tipo = '<span class="badge bg-secondary">' + fila.tipo + '</span>';
                }
                
                var detalles = '';
                if(fila.proveedor) {
                    detalles += `Proveedor: ${fila.proveedor}<br>`;
                }
                if(fila.fecha_vencimiento) {
                    detalles += `Vence: ${new Date(fila.fecha_vencimiento).toLocaleDateString()}`;
                }
                
                html += `<tr>
                    <td>${fila.codigo}</td>
                    <td>${new Date(fila.fecha).toLocaleDateString()}</td>
                    <td>${fila.hora}</td>
                    <td>${tipo}</td>
                    <td>${fila.medicamento}</td>
                    <td>${fila.cantidad}</td>
                    <td>${fila.nombre_personal} ${fila.apellido_personal}</td>
                    <td>${detalles}</td>
                </tr>`;
            });
            $("#resultadoMovimientos").html(html);
            crearDTMovimientos();
        }
        else if (lee.resultado == "obtener_medicamento") {
            $("#cod_medicamento").val(lee.datos.cod_medicamento);
            $("#nombre").val(lee.datos.nombre);
            $("#descripcion").val(lee.datos.descripcion);
            $("#unidad_medida").val(lee.datos.unidad_medida);

            $("#procesoMedicamento").text("MODIFICAR");
            $("#modalMedicamentoLabel").text("Editar Medicamento");
            $("#modalMedicamento").modal("show");
        }
        else if (
            lee.resultado == "incluir_medicamento" ||
            lee.resultado == "modificar_medicamento" ||
            lee.resultado == "registrar_entrada" ||
            lee.resultado == "registrar_salida" ||
            lee.resultado == "eliminar_medicamento"
        ) {
            muestraMensaje(lee.mensaje);
            $("#modalMedicamento").modal("hide");
            $("#modalEntradaSalida").modal("hide");
            $('#modalConfirmacion').modal("hide");
            consultarMedicamentos();
            consultarMovimientos();
        }
        else if (lee.resultado == "error") {
            muestraMensaje(lee.mensaje);
        }
    } catch (e) {
        muestraMensaje("Error al procesar la respuesta del servidor");
    }
}

function mostrarFormularioSalidaGlobal() {
    salidasTemporales = [];
    limpiaFormularioEntradaSalida();
    $("#salidasMultiples").html("");
    $("#cod_medicamento_es").val("");
    $("#nombre_medicamento_es").val("");
    $("#modalEntradaSalidaLabel").text("Registrar Entrada/Salida de Medicamentos");
    $("#btnProcesarEntradaSalida").text("Registrar").removeClass("btn-danger btn-success").addClass("btn-primary");
    $("#camposEntrada").show();
    $("#infoLotes").html('<div class="alert alert-info">Puede registrar varias entradas o salidas de lotes.</div>');
    cargarMedicamentosEnSelect(); // <-- Agrega esta línea
    $("#modalSalidaGlobal").modal("show");
}

function mostrarFormularioEntrada(cod_medicamento, nombre_medicamento) {
    $("#formEntradaSalida")[0].reset();
    $("#cod_medicamento_es").val(cod_medicamento);
    $("#accion_es").val("registrar_entrada");
    $("#nombre_medicamento_es").val(nombre_medicamento); // Aquí se muestra el nombre
    $("#modalEntradaSalidaLabel").text("Registrar Entrada de Lote");
    $("#modalEntradaSalida").modal("show");
}

function agregarLoteTemporal() {
    // Leer los valores del formulario
    const cod_medicamento = $("#cod_medicamento_es").val();
    const nombre = $("#nombre_medicamento_es").val();
    const cantidad = $("#cantidad").val();
    const fecha_vencimiento = $("#fecha_vencimiento").val();
    const proveedor = $("#proveedor").val();

    // Validación básica
    if (!cantidad || cantidad <= 0) {
        muestraMensaje("Ingrese una cantidad válida para el lote");
        return;
    }

    // Agregar lote temporal
    lotesTemporales.push({
        cod_medicamento,
        nombre,
        cantidad,
        fecha_vencimiento,
        proveedor
    });

    actualizarListaLotesTemporales();

    // Limpiar campos para el siguiente lote
    $("#cantidad").val('');
    $("#fecha_vencimiento").val('');
    $("#proveedor").val('');
}

function agregarLoteSalidaTemporal() {
    $("#btnAgregarLoteSalida").click();
}

function actualizarListaLotesTemporales() {
    let html = "";
    if (lotesTemporales.length === 0) {
        html = '<div class="alert alert-info">No hay lotes agregados</div>';
    } else {
        html = '<ul class="list-group">';
        lotesTemporales.forEach((lote, idx) => {
            html += `<li class="list-group-item">
                Lote ${idx + 1}: ${lote.nombre} - Cantidad: ${lote.cantidad} - Vence: ${lote.fecha_vencimiento || 'N/A'} - Proveedor: ${lote.proveedor || 'N/A'}
                <button type="button" class="btn btn-danger btn-sm float-end" onclick="eliminarLoteTemporal(${idx})">Eliminar</button>
            </li>`;
        });
        html += '</ul>';
    }
    $("#lotesMultiples").html(html);
}

function eliminarLoteTemporal(idx) {
    lotesTemporales.splice(idx, 1);
    actualizarListaLotesTemporales();
}

function cargarMedicamentosEnSelect() {
    var datos = new FormData();
    datos.append('accion', 'consultar_medicamentos');
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res.resultado === 'consultar_medicamentos') {
                let html = '<option value="">Seleccione...</option>';
                res.datos.forEach(function(med) {
                    html += `<option value="${med.cod_medicamento}">${med.nombre}</option>`;
                });
                $("#selectMedicamentoSalida").html(html);
            } else {
                $("#selectMedicamentoSalida").html('<option value="">No hay medicamentos</option>');
            }
        }
    });
}

function actualizarListaSalidasTemporales() {
    let html = "";
    if (salidasTemporales.length === 0) {
        html = '<div class="alert alert-info">No hay lotes agregados a la salida</div>';
    } else {
        html = '<ul class="list-group">';
        salidasTemporales.forEach((salida, idx) => {
            html += `<li class="list-group-item">
                Lote ${salida.cod_lote} - Cantidad: ${salida.cantidad}
                <button type="button" class="btn btn-danger btn-sm float-end" onclick="eliminarSalidaTemporal(${idx})">Eliminar</button>
            </li>`;
        });
        html += '</ul>';
    }
    $("#salidasMultiples").html(html);
}
