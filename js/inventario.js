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

function editarMedicamento(cod_medicamento) {
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
            if (res.resultado === "obtener_medicamento" && res.datos) {
                // Precargar los datos en el formulario de medicamento
                $("#cod_medicamento").val(res.datos.cod_medicamento);
                $("#nombre").val(res.datos.nombre);
                $("#descripcion").val(res.datos.descripcion);
                $("#unidad_medida").val(res.datos.unidad_medida);
                $("#stock_min").val(res.datos.stock_min);

                // Cambiar el texto del botón y del modal
                $("#procesoMedicamento").text("MODIFICAR");
                $("#modalMedicamentoLabel").text("Editar Medicamento");

                // Mostrar el modal de medicamento
                $("#modalMedicamento").modal("show");
            } else {
                muestraMensaje("No se pudo obtener la información del medicamento", "error");
            }
        },
        error: function() {
            muestraMensaje("Error al consultar el medicamento", "error");
        }
    });
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
    let $btn = $(this);
    if (codMedicamentoAEliminar) {
        var datos = new FormData();
        datos.append('accion', 'eliminar_medicamento');
        datos.append('cod_medicamento', codMedicamentoAEliminar);
        enviaAjax(datos, $btn, "Procesando...", "Eliminar");
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

function limpiaFormularioSalida() {
    // Limpia campos del formulario de salida
    $("#selectMedicamentoSalida").val("").trigger("change");
    $("#lotesDisponiblesSalida").html('<div class="alert alert-info">Seleccione un medicamento para ver los lotes disponibles</div>');
    $("#salidasMultiples").html('<div class="alert alert-info">No hay lotes agregados a la salida</div>');
    $("#cantidad").val("");
    $("#cantidad").removeAttr("data-stock-actual").removeAttr("data-stock-min");
    $("#infoStockAdvertencia").remove();
    $("#infoStockMaximo").remove();
    salidasTemporales = [];
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
    // Validación de stock mínimo
    const stockMin = $("#stock_min").val();
    if (stockMin === "" || isNaN(stockMin) || parseInt(stockMin) < 0) {
        muestraMensaje("El stock mínimo debe ser un número positivo");
        return false;
    }
    return true;
}

// Al abrir el formulario de entrada, consulta el stock actual y ajusta el máximo permitido
function mostrarFormularioEntrada(cod_medicamento, nombre_medicamento) {
    modoEntradaSalida = 'entrada'; // <-- IMPORTANTE: define el modo correctamente
    $("#formEntradaSalida")[0].reset();
    $("#cod_medicamento_es").val(cod_medicamento);
    $("#accion_es").val("registrar_entrada");
    $("#nombre_medicamento_es").val(nombre_medicamento);
    $("#modalEntradaSalidaLabel").text("Registrar Entrada de Lote");
    $("#btnProcesarEntradaSalida").text("Registrar Entrada").removeClass("btn-danger").addClass("btn-success");
    $("#camposEntrada").show();

    // Consultar stock actual y stock mínimo
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'obtener_medicamento', cod_medicamento: cod_medicamento },
        dataType: 'json',
        success: function(res) {
            if (res.resultado === 'obtener_medicamento') {
                let stock_actual = 0;
                if (res.lotes && Array.isArray(res.lotes)) {
                    stock_actual = res.lotes.reduce((sum, lote) => sum + parseInt(lote.cantidad), 0);
                }
                let maxPermitido = 250 - stock_actual;
                if (maxPermitido < 0) maxPermitido = 0;
                let stockMin = parseInt(res.datos.stock_min || 0);

                // Limita el input cantidad
                $("#cantidad")
                    .attr("max", maxPermitido)
                    .removeAttr("data-stock-actual")
                    .removeAttr("data-stock-min");
                $("#cantidad").val(""); // Limpia el campo

                // Muestra el stock actual, máximo permitido y el stock mínimo informativo
                $("#infoStockMaximo").remove();
                $("#cantidad").after(`<div id="infoStockMaximo" class="text-muted small mt-1">
                    Stock actual: ${stock_actual}. Puedes ingresar hasta <b>${maxPermitido}</b> unidades.<br>
                    <b>El stock mínimo recomendado para este medicamento es: ${stockMin}</b>.
                </div>`);
            }
        }
    });

    $("#modalEntradaSalida").modal("show");
}

// Validación antes de enviar
function validarFormularioEntradaSalida() {
    const cantidad = parseInt($("#cantidad").val(), 10);
    const max = parseInt($("#cantidad").attr("max"), 10);
    const stockActual = parseInt($("#cantidad").data("stock-actual") || 0, 10);
    const stockMin = parseInt($("#cantidad").data("stock-min") || 0, 10);

    if (cantidad <= 0) {
        muestraMensaje("La cantidad debe ser mayor a cero");
        return false;
    }
    if (!isNaN(max) && cantidad > max) {
        muestraMensaje("El stock máximo es de 250. Solo puedes agregar " + max + " unidades.");
        return false;
    }
    // Validación de stock mínimo para salida
    if (modoEntradaSalida == 'salida') {
        if ((stockActual - cantidad) < stockMin) {
            muestraMensaje(`No puedes retirar esa cantidad. El stock mínimo permitido es ${stockMin} y el stock actual es ${stockActual}.`);
            return false;
        }
    }
    if (modoEntradaSalida == 'entrada' && $("#fecha_vencimiento").val() == "") {
        muestraMensaje("La fecha de vencimiento es requerida para entradas");
        return false;
    }
    if (modoEntradaSalida == 'entrada' && $("#proveedor").val().trim() == "") {
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
        let $btn = $(this);
        if ($btn.text() == "INCLUIR") {
            if (validarFormulario()) {
                var datos = new FormData($("#formMedicamento")[0]);
                datos.append('accion', 'incluir_medicamento');
                enviaAjax(datos, $btn, "Procesando...", "INCLUIR");
            }
        } else if ($btn.text() == "MODIFICAR") {
            if (validarFormulario()) {
                var datos = new FormData($("#formMedicamento")[0]);
                datos.append('accion', 'modificar_medicamento');
                enviaAjax(datos, $btn, "Procesando...", "MODIFICAR");
            }
        }
    });
    
    $("#btnProcesarEntradaSalida").on("click", function() {
        let $btn = $(this);
        if (lotesTemporales.length > 0) {
            var datos = new FormData();
            datos.append('accion', 'registrar_entrada_multiple');
            datos.append('lotes', JSON.stringify(lotesTemporales));
            enviaAjax(datos, $btn, "Procesando...", "Procesar");
            lotesTemporales = [];
            actualizarListaLotesTemporales();
            $("#modalEntradaSalida").modal("hide");
        } else {
            if (!validarFormularioEntradaSalida()) return;
            var datos = new FormData($("#formEntradaSalida")[0]);
            datos.append('accion', 'registrar_entrada');
            enviaAjax(datos, $btn, "Procesando...", "Procesar");
            $("#modalEntradaSalida").modal("hide");
        }
    });
    
    $("#btnProcesarSalida").on("click", function() {
        let $btn = $(this);
        if (salidasTemporales.length === 0) {
            muestraMensaje("Debe agregar al menos un lote a la salida");
            return;
        }
        var datos = new FormData();
        datos.append('accion', 'registrar_salida_multiple');
        datos.append('salidas', JSON.stringify(salidasTemporales));
        enviaAjax(datos, $btn, "Procesando...", "Registrar Salida");
        $("#modalSalida").modal("hide");
    });
    
    $("#btnAgregarLoteSalida").on("click", function() {
        let $btn = $(this);
        setBotonProcesando($btn, "Procesando...", "Agregar Lote", true);
        setTimeout(() => {
            // Obtener stock actual y stock mínimo del medicamento seleccionado
            const cod_medicamento = $("#selectMedicamentoSalida").val();
            let stockActual = 0;
            let stockMin = 0;
            // Busca el stock actual y mínimo en los atributos del select o consulta AJAX si lo necesitas
            // Aquí asumimos que ya tienes los datos cargados en el select o puedes guardarlos en variables globales

            // Puedes guardar los datos en variables globales al cargar los lotes
            stockActual = parseInt($("#selectMedicamentoSalida option:selected").data("stock-actual") || 0, 10);
            stockMin = parseInt($("#selectMedicamentoSalida option:selected").data("stock-min") || 0, 10);

            // Si no tienes los datos en el select, puedes calcular el stock sumando los lotes disponibles
            // Aquí un ejemplo usando los lotes mostrados en la tabla:
            let totalStockDisponible = 0;
            $(".chkLoteSalida").each(function() {
                totalStockDisponible += parseInt($(this).data("max") || 0, 10);
            });
            stockActual = totalStockDisponible;

            // Sumar las cantidades ya agregadas a la salida
            let totalARetirar = 0;
            salidasTemporales.forEach(s => {
                totalARetirar += parseInt(s.cantidad, 10);
            });

            // Recorre los checkboxes de lotes seleccionados
            $(".chkLoteSalida:checked").each(function() {
                let cod_lote = $(this).val();
                let cantidad = $(`.inputCantidadLoteSalida[data-cod='${cod_lote}']`).val();
                let max = parseInt($(this).data("max"));
                cantidad = parseInt(cantidad);

                if (!cantidad || cantidad <= 0 || cantidad > max) {
                    muestraMensaje("Cantidad inválida para el lote " + cod_lote);
                    setBotonProcesando($btn, "Procesando...", "Agregar Lote", false);
                    return false;
                }

                // Evita duplicados
                if (salidasTemporales.some(s => s.cod_lote == cod_lote)) {
                    muestraMensaje("Ya agregaste este lote a la salida");
                    setBotonProcesando($btn, "Procesando...", "Agregar Lote", false);
                    return false;
                }

                // Validar stock mínimo global
                let totalARetirarNuevo = totalARetirar + cantidad;
                if ((stockActual - totalARetirarNuevo) < stockMin) {
                    muestraMensaje(`No puedes retirar esa cantidad. El stock mínimo permitido es ${stockMin} y el stock quedaría en ${stockActual - totalARetirarNuevo}.`);
                    setBotonProcesando($btn, "Procesando...", "Agregar Lote", false);
                    return false;
                }

                salidasTemporales.push({
                    cod_lote: cod_lote,
                    cantidad: cantidad
                });
                totalARetirar += cantidad;
            });

            // Actualiza la lista visual de lotes agregados a la salida
            actualizarListaSalidasTemporales();
            setBotonProcesando($btn, "Procesando...", "Agregar Lote", false);
        }, 500); // Simula un pequeño retardo visual
    });

    // Evento para mostrar lotes al seleccionar un medicamento en salida global
    $("#selectMedicamentoSalida").on("change", function() {
        const cod_medicamento = $(this).val();
        if (!cod_medicamento) {
            $("#lotesDisponiblesSalida").html('<div class="alert alert-info">Seleccione un medicamento para ver los lotes disponibles</div>');
            return;
        }
        // Consulta los lotes disponibles para ese medicamento
        $.ajax({
            url: '',
            type: 'POST',
            data: { accion: 'consultar_lotes', cod_medicamento: cod_medicamento },
            dataType: 'json',
            success: function(res) {
                if (res.resultado === 'consultar_lotes' && res.datos.length > 0) {
                    let html = '<div class="mb-3"><label class="form-label">Lotes Disponibles</label><div class="list-group">';
                    res.datos.forEach(function(lote) {
                        html += `<div class="d-flex justify-between">
                            <div>
                                <input type="checkbox" class="form-check-input chkLoteSalida" value="${lote.cod_lote}" data-max="${lote.cantidad}">
                                <span>Lote ${lote.cod_lote} - Vence: ${new Date(lote.fecha_vencimiento).toLocaleDateString()}
                            </div>
                            <div>
                                <b>${lote.cantidad}</b> unidades disponibles</span>
                                <input type="number" class="form-control inputCantidadLoteSalida d-inline-block ms-2" style="width:200px" min="1" max="${lote.cantidad}" data-cod="${lote.cod_lote}" placeholder="Cantidad">
                            </div>
                        </div>`;
                    });
                    html += '</div></div>';
                    $("#lotesDisponiblesSalida").html(html);
                } else {
                    $("#lotesDisponiblesSalida").html('<div class="alert alert-warning">No hay lotes disponibles para este medicamento</div>');
                }
            }
        });
    });

    // Evento para advertir si la cantidad dejaría el stock por debajo del mínimo
    $("#cantidad").on("input", function() {
        // Solo mostrar advertencia en modo salida
        if (modoEntradaSalida !== 'salida') {
            $("#infoStockAdvertencia").remove();
            return;
        }
        const cantidad = parseInt($(this).val(), 10) || 0;
        const stockActual = parseInt($(this).data("stock-actual") || 0, 10);
        const stockMin = parseInt($(this).data("stock-min") || 0, 10);

        $("#infoStockAdvertencia").remove();

        if ((stockActual - cantidad) < stockMin) {
            $(this).after(`<div id="infoStockAdvertencia" class="text-danger small mt-1">
                <b>Advertencia:</b> Esta cantidad dejaría el stock por debajo del mínimo permitido (${stockMin}).
            </div>`);
        }
    });


// INTRO.JS

    introJs().setOptions({
        steps: [
                {
                    element: document.querySelector('.botonverde'),
                    intro: 'Haz clic aquí para registrar un nuevo medicamento en el inventario.'
                },
                {
                    element: document.querySelector('.botonrojo'),
                    intro: 'Haz clic aquí para registrar una salida de medicamentos.'
                },
                {
                    element: document.querySelector('.btn.botonrojo a[href="?pagina=principal"]'),
                    intro: 'Haz clic aquí para volver al panel principal.'
                },
                {
                    element: document.querySelector('#tablaMedicamentos'),
                    intro: 'Aquí puedes ver el listado de medicamentos registrados.'
                },
                {
                    element: document.querySelector('.modificar'),
                    intro: 'Haz click aqui para modificar el medicamento'
                },
                {
                    element: document.querySelector('.agregarlote'),
                    intro: 'Haz click aqui para agregar un lote de medicamento'
                },
                {
                    element: document.querySelector('.eliminar'),
                    intro: 'Haz click aqui para eliminar un medicamento'
                },
                {
                    element: document.querySelector('#tablaMovimientos'),
                    intro: 'Aquí puedes ver el historial de movimientos de entrada y salida de medicamentos.'
                }
            ],
            showProgress: true,
            exitOnOverlayClick: true,
            showBullets: false,
            nextLabel: 'Siguiente',
            prevLabel: 'Anterior',
            skipLabel: 'X',
            doneLabel: 'Finalizar'
    })

    $("#btnTutorial").on("click", function() {
        introJs().setOptions({
            steps: [
                {
                    element: document.querySelector('.botonverde'),
                    intro: 'Haz clic aquí para registrar un nuevo medicamento en el inventario.'
                },
                {
                    element: document.querySelector('.botonrojo'),
                    intro: 'Haz clic aquí para registrar una salida de medicamentos.'
                },
                {
                    element: document.querySelector('.btn.botonrojo a[href="?pagina=principal"]'),
                    intro: 'Haz clic aquí para volver al panel principal.'
                },
                {
                    element: document.querySelector('#tablaMedicamentos'),
                    intro: 'Aquí puedes ver el listado de medicamentos registrados.'
                },
                {
                    element: document.querySelector('.modificar'),
                    intro: 'Haz click aqui para modificar el medicamento'
                },
                {
                    element: document.querySelector('.agregarlote'),
                    intro: 'Haz click aqui para agregar un lote de medicamento'
                },
                {
                    element: document.querySelector('.eliminar'),
                    intro: 'Haz click aqui para eliminar un medicamento'
                },
                {
                    element: document.querySelector('#tablaMovimientos'),
                    intro: 'Aquí puedes ver el historial de movimientos de entrada y salida de medicamentos.'
                }
            ],
            showProgress: true,
            exitOnOverlayClick: true,
            showBullets: false,
            nextLabel: 'Siguiente',
            prevLabel: 'Anterior',
            skipLabel: 'X',
            doneLabel: 'Finalizar'
        }).oncomplete(function() {
            localStorage.setItem('tutorialPacientesVisto', 'true');
        }).onexit(function() {
            localStorage.setItem('tutorialPacientesVisto', 'true');
        }).start();
    });
});

function muestraMensaje(mensaje) {
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
    }, 2000);
}

function setBotonProcesando($btn, textoProcesando, textoNormal, estadoProcesando) {
    if (estadoProcesando) {
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> ' + textoProcesando);
    } else {
        $btn.prop('disabled', false).text(textoNormal);
    }
}

// Modifica enviaAjax para aceptar el botón y textos
function enviaAjax(datos, $btn = null, textoProcesando = "Procesando...", textoNormal = null) {
    if ($btn) setBotonProcesando($btn, textoProcesando, textoNormal || $btn.text(), true);
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
        },
        complete: function() {
            if ($btn) setBotonProcesando($btn, textoProcesando, textoNormal || $btn.text(), false);
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
                    <td>${fila.cod_medicamento}</td>
                    <td>${fila.nombre}</td>
                    <td>${fila.descripcion}</td>
                    <td>${fila.stock_total || 0}</td>
                    <td>${fila.unidad_medida}</td>
                    <td>
                        <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                            <a type="button" class="btn btn-success modificar" onclick="editarMedicamento('${fila.cod_medicamento}')">
                                <img src="img/lapiz.svg" style="width: 20px">
                            </a>
                            <a type="button" class="btn btn-primary agregarlote" onclick="mostrarFormularioEntrada('${fila.cod_medicamento}', '${fila.nombre.replace(/'/g, "\\'")}')">
                                <img src="img/mas.svg" style="width: 20px">
                            </a>
                            <a type="button" class="btn btn-danger eliminar" onclick="confirmarEliminacion('${fila.cod_medicamento}')">
                                <img src="img/basura.svg" style="width: 20px">
                            </a>
                        </div>
                    </td>
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
            $("#cod_medicamento_es").val(lee.datos.cod_medicamento);
            $("#nombre_medicamento_es").val(lee.datos.nombre);

            if(modoEntradaSalida == 'entrada') {
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

                // Calcular stock actual sumando los lotes
                let stockActual = 0;
                if (lee.lotes && Array.isArray(lee.lotes)) {
                    stockActual = lee.lotes.reduce((sum, lote) => sum + parseInt(lote.cantidad), 0);
                }
                // Obtener el stock mínimo del medicamento
                let stockMin = parseInt(lee.datos.stock_min || 0);

                $("#cantidad")
                    .attr("data-stock-actual", stockActual)
                    .attr("data-stock-min", stockMin);

                // Mostrar info visual al usuario
                $("#infoStockMaximo").remove();
                $("#cantidad").after(`<div id="infoStockMaximo" class="text-muted small mt-1">
                    Stock actual: ${stockActual}. <b>El stock mínimo permitido es: ${stockMin}</b>.<br>
                    No puedes retirar una cantidad que deje el stock por debajo de este valor.
                </div>`);
            }
            $("#modalEntradaSalida").modal("show");
        }
        else if (
            lee.resultado == "incluir_medicamento" ||
            lee.resultado == "modificar_medicamento" ||
            lee.resultado == "registrar_entrada" ||
            lee.resultado == "registrar_salida" ||
            lee.resultado == "eliminar_medicamento"
        ) {
            muestraMensaje(lee.mensaje);
            limpiarTodosLosFormularios();
            $("#modalMedicamento").modal("hide");
            $("#modalEntradaSalida").modal("hide");
            $('#modalConfirmacion').modal("hide");
            consultarMedicamentos();
            consultarMovimientos();
        }
        else if (lee.resultado === "error") {
            muestraMensaje(lee.mensaje, "error");
            return;
        }
    } catch (e) {
        muestraMensaje("Error procesando respuesta del servidor: " + e.message, "error");
    }
}

function mostrarFormularioSalida() {
    salidasTemporales = [];
    limpiaFormularioEntradaSalida();
    $("#salidasMultiples").html("");
    $("#cod_medicamento_es").val("");
    $("#nombre_medicamento_es").val("");
    $("#modalEntradaSalidaLabel").text("Registrar Salida de Medicamentos");
    $("#btnProcesarEntradaSalida").text("Registrar").removeClass("btn-danger btn-success").addClass("btn-primary");
    $("#camposEntrada").show();
    $("#infoLotes").html('<div class="alert alert-info">Puede registrar varias entradas o salidas de lotes.</div>');
    cargarMedicamentosEnSelect();
    $("#modalSalida").modal("show");
    $("#modalSalida").on("hidden.bs.modal", function() {
        limpiaFormularioSalida();
    });
}

function mostrarFormularioEntrada(cod_medicamento, nombre_medicamento) {
    modoEntradaSalida = 'entrada'; // <-- IMPORTANTE: define el modo correctamente
    $("#formEntradaSalida")[0].reset();
    $("#cod_medicamento_es").val(cod_medicamento);
    $("#accion_es").val("registrar_entrada");
    $("#nombre_medicamento_es").val(nombre_medicamento);
    $("#modalEntradaSalidaLabel").text("Registrar Entrada de Lote");
    $("#btnProcesarEntradaSalida").text("Registrar Entrada").removeClass("btn-danger").addClass("btn-success");
    $("#camposEntrada").show();

    // Consultar stock actual y stock mínimo
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'obtener_medicamento', cod_medicamento: cod_medicamento },
        dataType: 'json',
        success: function(res) {
            if (res.resultado === 'obtener_medicamento') {
                let stock_actual = 0;
                if (res.lotes && Array.isArray(res.lotes)) {
                    stock_actual = res.lotes.reduce((sum, lote) => sum + parseInt(lote.cantidad), 0);
                }
                let maxPermitido = 250 - stock_actual;
                if (maxPermitido < 0) maxPermitido = 0;
                let stockMin = parseInt(res.datos.stock_min || 0);

                // Limita el input cantidad
                $("#cantidad")
                    .attr("max", maxPermitido)
                    .removeAttr("data-stock-actual")
                    .removeAttr("data-stock-min");
                $("#cantidad").val(""); // Limpia el campo

                // Muestra el stock actual, máximo permitido y el stock mínimo informativo
                $("#infoStockMaximo").remove();
                $("#cantidad").after(`<div id="infoStockMaximo" class="text-muted small mt-1">
                    Stock actual: ${stock_actual}. Puedes ingresar hasta <b>${maxPermitido}</b> unidades.<br>
                    <b>El stock mínimo recomendado para este medicamento es: ${stockMin}</b>.
                </div>`);
            }
        }
    });

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

// Limpiar formulario y variables al cerrar el modal de salida
$("#modalSalida").on("hidden.bs.modal", function() {
    limpiaFormularioEntradaSalida();
    salidasTemporales = [];
    actualizarListaSalidasTemporales();
});

function eliminarSalidaTemporal(idx) {
    salidasTemporales.splice(idx, 1);
    actualizarListaSalidasTemporales();
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

// NOTIFICACIONES
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al WebSocket');
};

ws.onclose = function() {
    console.log('WebSocket cerrado');
};

ws.onerror = function(error) {
    console.error('WebSocket error:', error);
};

// Ejemplo: enviar un mensaje
function enviarNotificacion(msg) {
    ws.send(msg);
}

function limpiarTodosLosFormularios() {
    // Formulario principal de medicamentos
    $("#formMedicamento")[0]?.reset();
    // Formulario de entrada/salida de lotes
    $("#formEntradaSalida")[0]?.reset();
    // Limpia selects y campos auxiliares
    $("#cod_medicamento").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#unidad_medida").val("");
    $("#stock_min").val("");
    $("#cod_medicamento_es").val("");
    $("#nombre_medicamento_es").val("");
    $("#cantidad").val("");
    $("#fecha_vencimiento").val("");
    $("#proveedor").val("");
    $("#infoLotes").html("");
    $("#infoStockMaximo").remove();
    $("#lotesMultiples").html("");
    $("#salidasMultiples").html("");
}