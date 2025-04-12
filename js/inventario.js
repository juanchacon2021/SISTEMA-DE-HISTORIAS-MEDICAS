$(document).ready(function() {
    // Cargar datos iniciales
    cargarMedicamentos();
    actualizarResumen();
    
    // Mostrar modal para nuevo medicamento
    $('#btnNuevoMedicamento').click(function() {
        resetForm();
        $('#tituloModal').text('Nuevo Medicamento');
        $('#accion').val('agregar');
        $('#modalMedicamento').modal('show');
    });
    
    // Guardar medicamento
    $('#btnGuardar').click(function() {
        const formData = $('#formMedicamento').serialize();
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    
                    if(res.resultado === 'success') {
                        $('#modalMedicamento').modal('hide');
                        mostrarModal('success', 'Registro Incluido Correctamente');
                        cargarMedicamentos();
                        actualizarResumen();
                    } else {
                        mostrarModal('error', res.mensaje || 'Error al realizar la operación');
                    }
                } catch(e) {
                    mostrarModal('error', 'Error al procesar la respuesta');
                }
            },
            error: function() {
                mostrarModal('error', 'Error de conexión');
            }
        });
    });
    
    // Editar medicamento
    $(document).on('click', '.btn-editar', function() {
        const codMedicamento = $(this).data('id');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'consultar'
            },
            success: function(response) {
                try {
                    const medicamentos = JSON.parse(response);
                    const medicamento = medicamentos.find(m => m.cod_medicamento == codMedicamento);
                    
                    if(medicamento) {
                        $('#tituloModal').text('Editar Medicamento');
                        $('#accion').val('modificar');
                        $('#cod_medicamento').val(medicamento.cod_medicamento);
                        $('#nombre').val(medicamento.nombre);
                        $('#descripcion').val(medicamento.descripcion);
                        $('#cantidad').val(medicamento.cantidad);
                        $('#unidad_medida').val(medicamento.unidad_medida);
                        $('#fecha_vencimiento').val(medicamento.fecha_vencimiento);
                        $('#lote').val(medicamento.lote);
                        $('#proveedor').val(medicamento.proveedor);
                        
                        $('#modalMedicamento').modal('show');
                    }
                } catch(e) {
                    mostrarModal('error', 'Error al cargar el medicamento');
                }
            }
        });
    });
    
    // Eliminar medicamento
    $(document).on('click', '.btn-eliminar', function() {
        const codMedicamento = $(this).data('id');
        
        if(confirm('¿Está seguro de eliminar este medicamento?')) {
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    accion: 'eliminar',
                    cod_medicamento: codMedicamento
                },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        
                        if(res.resultado === 'success') {
                            mostrarModal('success', 'Medicamento eliminado correctamente');
                            cargarMedicamentos();
                            actualizarResumen();
                        } else {
                            mostrarModal('error', res.mensaje || 'Error al eliminar el medicamento');
                        }
                    } catch(e) {
                        mostrarModal('error', 'Error al procesar la respuesta');
                    }
                }
            });
        }
    });
    
    // Función para cargar medicamentos
    function cargarMedicamentos() {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'consultar'
            },
            success: function(response) {
                try {
                    const medicamentos = JSON.parse(response);
                    let html = '';
                    
                    medicamentos.forEach(med => {
                        html += `
                            <tr style="margin-bottom: 100px;"> <!-- Más margen entre filas -->
                                <td>${med.nombre}</td>
                                <td>${med.descripcion || '-'}</td>
                                <td>${med.cantidad}</td>
                                <td>${med.unidad_medida}</td>
                                <td>${med.fecha_vencimiento || '-'}</td>
                                <td>${med.lote || '-'}</td>
                                <td>${med.proveedor || '-'}</td>
                                <td>${med.nombre_personal} ${med.apellido}</td>
                                <td>${new Date(med.fecha_registro).toLocaleString()}</td>
                                <td>
                                    <button class="btn btn-success btn-editar me-2" data-id="${med.cod_medicamento}">
                                        <img src="img/lapiz.svg" alt="Editar" width="20">
                                    </button>
                                    <button class="btn btn-danger btn-eliminar" data-id="${med.cod_medicamento}">
                                        <img src="img/basura.svg" alt="Borrar" width="20">
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#tablaMedicamentos tbody').html(html);
                } catch(e) {
                    console.error('Error al cargar medicamentos:', e);
                }
            }
        });
    }
    
    // Función para actualizar resumen
    function actualizarResumen() {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'total_medicamentos'
            },
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    $('#totalMedicamentos').text(res.total);
                } catch(e) {
                    console.error('Error al cargar total de medicamentos:', e);
                }
            }
        });
        
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                accion: 'total_stock'
            },
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    $('#totalStock').text(res.total || '0');
                } catch(e) {
                    console.error('Error al cargar total de stock:', e);
                }
            }
        });
    }
    
    // Función para resetear formulario
    function resetForm() {
        $('#formMedicamento')[0].reset();
        $('#cod_medicamento').val('');
        $('#unidad_medida').val('');
    }
    
    // Función para mostrar alertas
    function mostrarModal(tipo, mensaje) {
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('body').append(alert);
        
        // Ocultar después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 2000);
    }

    // Función para mostrar el modal con un mensaje
    function mostrarModal(tipo, mensaje) {
        const modal = $('#mostrarmodal');
        const contenido = $('#contenidodemodal');
        const iconoCorrecto = $('#icono-correcto');
        const iconoError = $('#icono-error');

        // Configurar el contenido del modal
        contenido.text(mensaje);

        if (tipo === 'success') {
            iconoCorrecto.show();
            iconoError.hide();
        } else {
            iconoCorrecto.hide();
            iconoError.show();
        }

        // Mostrar el modal
        modal.modal('show');

        // Ocultar automáticamente después de 5 segundos
        setTimeout(function () {
            modal.modal('hide');
        }, 2000);
    }
});