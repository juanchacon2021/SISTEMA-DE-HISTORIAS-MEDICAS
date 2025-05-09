<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>

<body>
<?php
    if ($nivel == 'Doctor' || $nivel == 'Enfermera') {
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
  Inventario de Medicamentos
</div>

<div class="container espacio">
    <div class="container">
        <div class="row mt-3 botones">
            <a href="#" class="btn-flotante" style="cursor: pointer;" onclick="mostrarFormularioMedicamento()">
                <img src="img/lapiz.svg" alt="Agregar Medicamento">
            </a>
                    
            <div class="col-md-2 recortar">    
                <a href="?pagina=principal" class="boton">Volver</a>
            </div>
        </div>
    </div>

    <!-- Tabla de Medicamentos -->
    <div class="container mt-4">
        <h3 class="mb-3">Listado de Medicamentos</h3>
        <div class="table-responsive">
            <table id="tablaMedicamentos" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Acciones</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Vencimiento</th>
                        <th>Lote</th>
                        <th>Proveedor</th>
                    </tr>
                </thead>
                <tbody id="resultadoMedicamentos">
                    <!-- Los datos se cargarán via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Transacciones -->
    <div class="container mt-5">
        <h3 class="mb-3">Historial de Transacciones</h3>
        <div class="table-responsive">
            <table id="tablaTransacciones" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Codigo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Medicamento</th>
                        <th>Cantidad</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody id="resultadoTransacciones">
                    <!-- Los datos se cargarán via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para formulario de medicamentos -->
<div class="modal fade" id="modalMedicamento" tabindex="-1" aria-labelledby="modalMedicamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalMedicamentoLabel">Gestión de Medicamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formMedicamento" autocomplete="off">
                    <input type="hidden" id="cod_medicamento" name="cod_medicamento">
                    <input type="hidden" id="accion" name="accion" value="incluir_medicamento">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Medicamento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                            <select class="form-select" id="unidad_medida" name="unidad_medida" required>
                                <option value="">Seleccione...</option>
                                <option value="mg">mg</option>
                                <option value="ml">ml</option>
                                <option value="unidades">unidades</option>
                                <option value="frascos">frascos</option>
                                <option value="ampollas">ampollas</option>
                                <option value="comprimidos">comprimidos</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lote" class="form-label">Lote</label>
                            <input type="text" class="form-control" id="lote" name="lote">
                        </div>
                        <div class="col-md-6">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="procesoMedicamento" class="btn btn-primary">INCLUIR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once("comunes/modal.php"); ?>
<script src="js/inventario.js"></script>

<?php
    }                    
?>
</body>
</html>