<?php
require_once("comunes/encabezado.php");
require_once("comunes/sidebar.php");
require_once("comunes/notificaciones.php");

if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
    header("Location: /SISTEMA-DE-HISTORIAS-MEDICAS/login");
    exit();
} elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Examenes', $_SESSION['permisos']['modulos'])) {
    http_response_code(403);
    die('<div class="container text-center py-5">
            <h1 class="text-danger">403 - Acceso prohibido</h1>
            <p class="lead">No tienes permiso para acceder a este módulo</p>
            <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="btn btn-primary">Volver al inicio</a>
         </div>');
}
?>

<body>
    <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
        Exámenes Médicos
    </div>

    <div class="container espacio">
        <div class="container">
            <div class="row mt-9 botones">
                <button type="button" class="btn botonverde d-flex" onclick="mostrarModalRegistroExamen()">
                    <i class="fas fa-plus mr-1"></i> Registrar Examen
                </button>
                <button type="button" class="btn botonazul d-flex" onclick="mostrarModalTipoExamen('incluir')">
                    <i class="fas fa-plus mr-1"></i> Nuevo Tipo
                </button>
                <div class="btn botonrojo">    
                    <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal">Volver</a>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-4" id="examenesTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="registros-tab" data-bs-toggle="tab" data-bs-target="#registros" type="button" role="tab">
                    Registros de Exámenes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tipos-tab" data-bs-toggle="tab" data-bs-target="#tipos" type="button" role="tab">
                    Tipos de Examen
                </button>
            </li>
        </ul>

        <div class="tab-content" id="examenesTabContent">
            <!-- Tab de Registros de Examen -->
            <div class="tab-pane fade show active" id="registros" role="tabpanel">
                <div class="container mt-4">
                    <div class="table-responsive">
                        <table id="tablaRegistrosExamen" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Tipo de Examen</th>
                                    <th>Fecha</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoRegistrosExamen"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab de Tipos de Examen -->
            <div class="tab-pane fade" id="tipos" role="tabpanel">
                <div class="container mt-4">
                    <div class="table-responsive">
                        <table id="tablaTiposExamen" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoTiposExamen"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Registros de Examen -->
    <div class="modal fade" id="modalRegistroExamen" tabindex="-1" aria-labelledby="modalRegistroExamenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistroExamen" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" id="accionRegistroExamen" name="accion" value="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pacienteExamen" class="form-label">Paciente</label>
                                <select class="form-control bg-gray-200 rounded-lg border-white p-2" id="pacienteExamen" name="cedula_paciente" required>
                                    <option value="">Seleccione un paciente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipoExamen" class="form-label">Tipo de Examen</label>
                                <select class="form-control bg-gray-200 rounded-lg border-white p-2" id="tipoExamen" name="cod_examen" required>
                                    <option value="">Seleccione un tipo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaExamen" class="form-label">Fecha</label>
                                <input type="date" class="form-control bg-gray-200 rounded-lg border-white p-2" id="fechaExamen" name="fecha_e" required>
                            </div>
                            <div class="col-md-6">
                                <label for="horaExamen" class="form-label">Hora</label>
                                <input type="time" class="form-control bg-gray-200 rounded-lg border-white p-2" id="horaExamen" name="hora_e" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="observacionExamen" class="form-label">Observaciones</label>
                            <textarea class="form-control bg-gray-200 rounded-lg border-white p-2" id="observacionExamen" name="observacion_examen" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="archivoExamen" class="form-label">Resultado del Examen (Imagen)</label>
                            <input type="file" class="form-control bg-gray-200 rounded-lg border-white p-2" id="archivoExamen" name="imagenarchivo" accept=".png,.jpg,.jpeg">
                            <div class="mt-2 text-center">
                                <img src="img/logo.png" id="imagenExamen" class="img-fluid" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn botonverde" id="btnGuardarRegistroExamen">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Tipos de Examen -->
    <div class="modal fade" id="modalTipoExamen" tabindex="-1" aria-labelledby="modalTipoExamenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTipoExamen" autocomplete="off">
                        <input type="hidden" id="accionTipoExamen" name="accion" value="">
                        <input type="hidden" id="cod_examen" name="cod_examen" value="">
                        <div class="mb-3">
                            <label for="nombre_examen" class="form-label">Nombre del Examen</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-2" id="nombre_examen" name="nombre_examen" required>
                            <small id="snombre_examen" class="form-text text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_examen" class="form-label">Descripción</label>
                            <textarea class="form-control bg-gray-200 rounded-lg border-white p-2" id="descripcion_examen" name="descripcion_examen" rows="3"></textarea>
                            <small id="sdescripcion_examen" class="form-text text-danger"></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn botonverde" id="btnGuardarTipoExamen">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Visualizar Imagen -->
    <div class="modal fade" id="modalImagenExamen" tabindex="-1" aria-labelledby="modalImagenExamenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imagenGrandeExamen" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn boton" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mensajeConfirmacion">
                    ¿Está seguro de eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script src="js/examenes.js"></script>
</body>
</html>