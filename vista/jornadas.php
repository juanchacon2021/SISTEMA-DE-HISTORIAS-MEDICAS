<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="container espacio">
        <!-- Main content -->
        <section class="content">
            <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
                Jornadas Médicas
            </div>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">Listado de Jornadas Médicas</h3>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-success" onclick="mostrarModalJornada('incluir')">
                                     Nueva Jornada
                                </button>
                                <a href="vista/fpdf/jornadas.php" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-file-pdf mr-1"></i> Generar Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="tablaJornadas" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Fecha</th>
                                        <th>Ubicación</th>
                                        <th>Total Pacientes</th>
                                        <th>Hombres</th>
                                        <th>Mujeres</th>
                                        <th>Embarazadas</th>
                                        <th>Responsable</th>
                                    </tr>
                                </thead>
                                <tbody id="resultadoJornadas"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal para Jornadas -->
    <div class="modal fade" id="modalJornada" tabindex="-1" role="dialog" aria-labelledby="modalJornadaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalJornadaLabel">Registrar Jornada Médica</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formJornada" autocomplete="off">
                        <input type="hidden" id="accionJornada" name="accion" value="">
                        <input type="hidden" id="cod_jornada" name="cod_jornada" value="">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_jornada">Fecha de la Jornada</label>
                                    <input type="date" class="form-control" id="fecha_jornada" name="fecha_jornada" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cedula_responsable">Responsable</label>
                                    <select class="form-control" id="cedula_responsable" name="cedula_responsable" required>
                                        <option value="">Seleccione un responsable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="ubicacion">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_pacientes">Total Pacientes</label>
                                    <input type="number" class="form-control" id="total_pacientes" name="total_pacientes" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pacientes_masculinos">Pacientes Masculinos</label>
                                    <input type="number" class="form-control" id="pacientes_masculinos" name="pacientes_masculinos" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pacientes_femeninos">Pacientes Femeninos</label>
                                    <input type="number" class="form-control" id="pacientes_femeninos" name="pacientes_femeninos" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pacientes_embarazadas">Pacientes Embarazadas</label>
                                    <input type="number" class="form-control" id="pacientes_embarazadas" name="pacientes_embarazadas" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Participantes</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <select class="form-control" id="participante">
                                            <option value="">Seleccione un participante</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-block" onclick="agregarParticipante()">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered" id="tablaParticipantes">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listaParticipantes"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarJornada">Guardar</button>
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
                    ¿Está seguro de eliminar esta jornada médica?
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

<script src="js/jornadas.js"></script>
</body>
</html>