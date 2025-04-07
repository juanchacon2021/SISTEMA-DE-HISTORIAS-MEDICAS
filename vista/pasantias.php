<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 290px;"> <!-- Ajusta el margen izquierdo -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gestión de Pasantías</h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="pasantiasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button" role="tab">
                            <i class="fas fa-users mr-1"></i> Estudiantes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="areas-tab" data-bs-toggle="tab" data-bs-target="#areas" type="button" role="tab">
                            <i class="fas fa-map-marked-alt mr-1"></i> Áreas
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pasantiasTabContent">
                    <!-- Tab de Estudiantes -->
                    <div class="tab-pane fade show active" id="estudiantes" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="card-title">Listado de Estudiantes en Pasantía</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success" onclick="mostrarModalEstudiante('incluir')">
                                            <i class="fas fa-plus-circle mr-1"></i> Nuevo Estudiante
                                        </button>
                                        <a href="vista/fpdf/pasantias.php" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-file-pdf mr-1"></i> Generar Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaEstudiantes" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Acciones</th>
                                                <th>Cédula</th>
                                                <th>Apellidos</th>
                                                <th>Nombres</th>
                                                <th>Institución</th>
                                                <th>Área</th>
                                                <th>Doctor</th>
                                                <th>Estado</th>
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
                        <div class="card mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="card-title">Listado de Áreas de Pasantía</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success" onclick="mostrarModalArea('incluir')">
                                            <i class="fas fa-plus-circle mr-1"></i> Nueva Área
                                        </button>
                                        <a href="vista/fpdf/areas_pasantia.php" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-file-pdf mr-1"></i> Generar Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaAreas" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Acciones</th>
                                                <th>Área</th>
                                                <th>Descripción</th>
                                                <th>Doctor Responsable</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoAreas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal para Estudiantes -->
    <div class="modal fade" id="modalEstudiante" tabindex="-1" role="dialog" aria-labelledby="modalEstudianteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEstudianteLabel">Registrar Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEstudiante" autocomplete="off">
                        <input type="hidden" id="accionEstudiante" name="accion" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cedula_estudiante">Cédula</label>
                                    <input type="text" class="form-control" id="cedula_estudiante" name="cedula_estudiante" required>
                                    <small id="scedula_estudiante" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombres</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    <small id="snombre" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido">Apellidos</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                    <small id="sapellido" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="institucion">Institución</label>
                                    <input type="text" class="form-control" id="institucion" name="institucion" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                    <small id="stelefono" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cod_area">Área de Pasantía</label>
                                    <select class="form-control" id="cod_area" name="cod_area" required>
                                        <option value="">Seleccione un área</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Finalización</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" checked>
                            <label class="form-check-label" for="activo">Estudiante Activo</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarEstudiante">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Áreas -->
    <div class="modal fade" id="modalArea" tabindex="-1" role="dialog" aria-labelledby="modalAreaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalAreaLabel">Registrar Área</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formArea" autocomplete="off">
                        <input type="hidden" id="accionArea" name="accion" value="">
                        <input type="hidden" id="cod_area" name="cod_area" value="">
                        <div class="form-group">
                            <label for="nombre_area">Nombre del Área</label>
                            <input type="text" class="form-control" id="nombre_area" name="nombre_area" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="responsable_id">Doctor Responsable</label>
                            <select class="form-control" id="responsable_id" name="responsable_id" required>
                                <option value="">Seleccione un doctor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarArea">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mensajeConfirmacion">
                    ¿Está seguro de eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
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