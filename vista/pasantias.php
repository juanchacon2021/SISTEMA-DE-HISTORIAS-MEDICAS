<?php
require_once("comunes/encabezado.php");
require_once("comunes/sidebar.php");
require_once("comunes/notificaciones.php");
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Pasantías', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
    ?>

    <div class="wrapper">
        <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
            Pasantías
        </div>
        <div class="container espacio">
            <section class="content">

                <div class="container-fluid d-flex">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="pasantiasTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button" role="tab">
                                Estudiantes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="areas-tab" data-bs-toggle="tab" data-bs-target="#areas" type="button" role="tab">
                                <i class="fas fa-map-marked-alt mr-1"></i> Áreas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="asistencia-tab" data-bs-toggle="tab" data-bs-target="#asistencia" type="button" role="tab">
                                <i class="fas fa-calendar-check mr-1"></i> Asistencia
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="pasantiasTabContent">
                    <!-- Tab de Estudiantes -->
                    <div class="tab-pane fade show active" id="estudiantes" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn botonverde" onclick="mostrarModalEstudiante('incluir')">
                                            <i class="fas fa-plus me-2"></i>Nuevo Estudiante
                                        </button>
                                        <button type="button" class="btn botonreporte" onclick="mostrarModalReportes()">
                                             Generar Reporte
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaEstudiantes" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Apellidos</th>
                                                <th>Nombres</th>
                                                <th>Institución</th>
                                                <th>Teléfono</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoEstudiantes"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab de Áreas -->
                    <div class="tab-pane fade" id="areas" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn botonverde" onclick="mostrarModalArea('incluir')">
                                            Nueva Área
                                        </button>
                                        <button type="button" class="btn botonreporte" onclick="mostrarModalReportes()">
                                             Generar Reporte
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaAreas" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Área</th>
                                                <th>Descripción</th>
                                                <th>Doctor Responsable</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoAreas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab de Asistencia -->
                    <div class="tab-pane fade" id="asistencia" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn botonverde" onclick="mostrarModalAsistencia()">
                                            <i class="fas fa-plus mr-1"></i> Registrar Asistencia
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="filtroAreaAsistencia">Filtrar por Área:</label>
                                        <select class="form-control" id="filtroAreaAsistencia">
                                            <option value="">Todas las áreas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtroFechaAsistencia">Filtrar por Fecha:</label>
                                        <input type="date" class="form-control" id="filtroFechaAsistencia">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtroEstadoAsistencia">Filtrar por Estado:</label>
                                        <select class="form-control" id="filtroEstadoAsistencia">
                                            <option value="">Todos</option>
                                            <option value="1">Activos</option>
                                            <option value="0">Inactivos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaAsistencia" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Estudiante</th>
                                                <th>Área</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoAsistencia"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Modal para Estudiantes -->
        <div class="modal fade" id="modalEstudiante" tabindex="-1" aria-labelledby="modalEstudianteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                    <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEstudiante" autocomplete="off">
                            <input type="hidden" id="accionEstudiante" name="accion" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula_estudiante">Cédula</label>
                                        <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cedula_estudiante" name="cedula_estudiante" required>
                                        <small id="scedula_estudiante" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombres</label>
                                        <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="nombre" name="nombre" required>
                                        <small id="snombre" class="form-text text-danger"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellidos</label>
                                        <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="apellido" name="apellido" required>
                                        <small id="sapellido" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="institucion">Institución</label>
                                        <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="institucion" name="institucion" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="telefono" name="telefono">
                                        <small id="stelefono" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cod_area">Área de Pasantía</label>
                                        <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cod_area" name="cod_area" required>
                                            <option value="">Seleccione un área</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn botonverde" id="btnGuardarEstudiante">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Áreas -->
        <div class="modal fade" id="modalArea" tabindex="-1" aria-labelledby="modalAreaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                    <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formArea" autocomplete="off">
                            <input type="hidden" id="accionArea" name="accion" value="">
                            <input type="hidden" id="id_area" name="cod_area" value="">
                            <div class="form-group">
                                <label for="nombre_area">Nombre del Área</label>
                                <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="nombre_area" name="nombre_area" required>
                                <small id="snombre_area" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="responsable_id">Doctor Responsable</label>
                                <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="responsable_id" name="responsable_id" required>
                                    <option value="">Seleccione un doctor</option>
                                </select>
                                <small id="sresponsable_id" class="form-text text-danger"></small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn botonverde" id="btnGuardarArea">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Asistencia -->
        <div class="modal fade" id="modalAsistencia" tabindex="-1" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                    <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAsistencia" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asistenciaEstudiante">Estudiante</label>
                                        <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="asistenciaEstudiante" required>
                                            <option value="">Seleccione un estudiante</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asistenciaArea">Área</label>
                                        <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="asistenciaArea" required>
                                            <option value="">Seleccione un área</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asistenciaFechaInicio">Fecha de Inicio</label>
                                        <input type="date" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="asistenciaFechaInicio" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asistenciaFechaFin">Fecha de Finalización (opcional)</label>
                                        <input type="date" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="asistenciaFechaFin">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="asistenciaActivo" checked>
                                <label class="form-check-label" for="asistenciaActivo">Activo</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn botonverde" id="btnGuardarAsistencia">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Reportes -->
        <div class="modal fade" id="modalReportes" tabindex="-1" aria-labelledby="modalReportesLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                    <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div style="margin: 0em 2em 0em 2em;">
                        <h1 class="text 2x1 font-bold mb-2" id="modalReportesLabel">
                           Generar Reporte
                        </h1>
                        <div>
                            <div class="col-md-2 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);"></div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-success text-start" onclick="generarReporte('estudiantes')">
                                <i class="fas fa-users me-2"></i> Listado de Estudiantes
                            </button>
                            <button type="button" class="btn btn-success text-start" onclick="generarReporte('areas')">
                                <i class="fas fa-map-marked-alt me-2"></i> Asistencia por Áreas
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="mensajeConfirmacion">
                        ¿Está seguro de eliminar este registro?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn botonrojo" id="btnConfirmarEliminar">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once("comunes/modal.php"); ?>
        <?php require_once("comunes/footer.php"); ?>
    </div>

    <script src="js/pasantias.js"></script>
</body>

</html>