<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");   
require_once("comunes/notificaciones.php"); 
?>

<body>
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Inventario', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
  Inventario de Medicamentos
</div>

<div class="container espacio">
    <div class="container">
        <div class="row mt-9 botones">
            <a href="#" class="btn botonverde d-flex" style="cursor: pointer;" onclick="mostrarFormularioMedicamento()">
                Registrar Medicamento
            </a>

            <button type="button" class="btn botonrojo d-flex" onclick="mostrarFormularioSalida()">
                Registrar Salida
            </button>
            
            <div class="btn botonrojo">    
                <a href="?pagina=principal">Volver</a>
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
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Unidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="resultadoMedicamentos">
                    <!-- Los datos se cargaran via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="container mt-5">
        <h3 class="mb-3">Historial de Movimientos</h3>
        <div class="table-responsive">
            <table id="tablaMovimientos" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Medicamento</th>
                        <th>Cantidad</th>
                        <th>Responsable</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody id="resultadoMovimientos">
                    <!-- Los datos se cargarán via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para formulario de medicamentos -->
<div class="modal fade" id="modalMedicamento" tabindex="-1" aria-labelledby="modalMedicamentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 25px 25px 0px 25px;">
            <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formMedicamento" autocomplete="off">
                    <input type="hidden" id="cod_medicamento" name="cod_medicamento">
                    <input type="hidden" id="accion" name="accion" value="incluir_medicamento">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nombre" class="texto-inicio font-medium mb-2">Nombre del Medicamento</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="nombre" name="nombre" placeholder="Nombre del Medicamento" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="unidad_medida" class="texto-inicio font-medium mb-2">Unidad de Medida</label>
                            <select class="form-select bg-gray-200 rounded-lg border-white p-3 text" id="unidad_medida" name="unidad_medida" required>
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
                            <label for="stock_min" class="texto-inicio font-medium mb-2">Stock Mínimo</label>
                            <input type="number" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="stock_min" name="stock_min" min="0" required>
                            <div id="infoStockMin" class="text-muted small mt-1">
                                Este es el mínimo de unidades que debe mantenerse en stock para este medicamento.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="texto-inicio font-medium mb-2">Descripción</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="procesoMedicamento" class="btn botonverde">Incluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para entrada de medicamentos -->
<div class="modal fade" id="modalEntradaSalida" tabindex="-1" aria-labelledby="modalEntradaSalidaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 25px 25px 0px 25px;">
            <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEntradaSalida" autocomplete="off">
                    <input type="hidden" id="cod_medicamento_es" name="cod_medicamento">
                    <input type="hidden" id="accion_es" name="accion">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_medicamento_es" class="texto-inicio font-medium mb-2">Medicamento</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="nombre_medicamento_es" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidad" class="texto-inicio font-medium mb-2">Cantidad</label>
                            <input type="number" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cantidad" name="cantidad" min="1" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="camposEntrada">
                        <div class="col-md-6">
                            <label for="fecha_vencimiento" class="texto-inicio font-medium mb-2">Fecha de Vencimiento</label>
                            <input type="date" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="fecha_vencimiento" name="fecha_vencimiento">
                        </div>
                        <div class="col-md-6">
                            <label for="proveedor" class="texto-inicio font-medium mb-2">Proveedor</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="proveedor" name="proveedor">
                        </div>
                    </div>
                    
                    <div id="lotesMultiples"></div>
                    <button type="button" class="btn btn-secondary mb-2" onclick="agregarLoteTemporal()">Agregar Lote</button>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnProcesarEntradaSalida" class="btn botonverde">Procesar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para salida global de medicamentos -->
<div class="modal fade" id="modalSalida" tabindex="-1" aria-labelledby="modalSalidaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 25px 25px 0px 25px;">
            <div class="modal-body">
                <form id="formSalida" autocomplete="off">
                    <div class="mb-3">
                        <label for="selectMedicamentoSalida" class="form-label">Medicamento</label>
                        <select class="form-select" id="selectMedicamentoSalida">
                            <!-- Opciones cargadas por JS -->
                        </select>
                    </div>
                    <div id="lotesDisponiblesSalida"></div>
                    <button type="button" class="btn btn-secondary mb-2" id="btnAgregarLoteSalida">Agregar Lote a Salida</button>
                    <div id="salidasMultiples"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnProcesarSalida" class="btn botonverde">Registrar Salida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirmacion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este medicamento?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn botonrojo" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once("comunes/modal.php"); ?>
<script src="js/inventario.js"></script>

</body>
</html>