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
            <a href="#" class="btn-flotante" id="btnNuevoMedicamento">
                <img src="img/mas.svg" alt="Nuevo medicamento">
            </a>
                    
            <div class="col-md-2 recortar my-3">	
                <a href="?pagina=principal" class="boton">Volver</a>
            </div>
        </div>
    </div>

    <!-- Resumen del inventario -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Medicamentos</h5>
                    <p class="card-text display-4" id="totalMedicamentos">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total en Stock</h5>
                    <p class="card-text display-4" id="totalStock">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de medicamentos -->
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablaMedicamentos">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Vencimiento</th>
                    <th>Lote</th>
                    <th>Proveedor</th>
                    <th>Registrado por</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se cargarán via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar/editar medicamento -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalMedicamento">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="padding: 20px;">
            <div class="modal-header">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Nuevo Medicamento</h1>

                    <div class="col-md-6 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formMedicamento" autocomplete="off">
                    <input type="hidden" name="accion" id="accion" value="agregar">
                    <input type="hidden" name="cod_medicamento" id="cod_medicamento" value="">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="texto-inicio font-medium mb-2">Nombre</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3" id="nombre" name="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidad" class="texto-inicio font-medium mb-2">Cantidad</label>
                            <input type="number" class="form-control bg-gray-200 rounded-lg border-white p-3" id="cantidad" name="cantidad" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="unidad_medida" class="texto-inicio font-medium mb-2">Unidad de Medida</label>
                            <select class="form-control bg-gray-200 rounded-lg border-white p-3" id="unidad_medida" name="unidad_medida" required>
                                <option value="">Seleccione...</option>
                                <option value="mg">mg</option>
                                <option value="g">g</option>
                                <option value="ml">ml</option>
                                <option value="L">L</option>
                                <option value="unidades">unidades</option>
                                <option value="cápsulas">cápsulas</option>
                                <option value="comprimidos">comprimidos</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="texto-inicio font-medium mb-2">Fecha de Vencimiento</label>
                            <input type="date" class="form-control bg-gray-200 rounded-lg border-white p-3" id="fecha_vencimiento" name="fecha_vencimiento">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="lote" class="texto-inicio font-medium mb-2">Lote</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3" id="lote" name="lote">
                        </div>
                        <div class="col-md-6">
                            <label for="proveedor" class="texto-inicio font-medium mb-2">Proveedor</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3" id="proveedor" name="proveedor">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="texto-inicio font-medium mb-2">Descripción</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex justify-between">
                <button type="button" style="padding: 6px 60px;" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" style="padding: 6px 60px;" class="btn btn-success" id="btnGuardar">Guardar</button>
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