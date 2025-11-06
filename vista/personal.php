<?php
require_once("comunes/encabezado.php");
require_once("comunes/sidebar.php");    
require_once("comunes/notificaciones.php");
?>

<body>
<?php  
if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
    header("Location: /SISTEMA-DE-HISTORIAS-MEDICAS/login");
    exit();
} elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Personal', $_SESSION['permisos']['modulos'])) {
    http_response_code(403);
    die('<div class="container text-center py-5">
            <h1 class="text-danger">403 - Acceso prohibido</h1>
            <p class="lead">No tienes permiso para acceder a este módulo</p>
            <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="btn btn-primary">Volver al inicio</a>
         </div>');
}
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
    Personal
</div>
    <div class="container espacio">
        <section class="content">
            <div class="cardd mt-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 botones">
                            <button type="button" class="btn botonverde" onclick="mostrarModalPersonal('incluir')">
                                <i class="fas fa-plus me-2"></i>Nuevo Personal
                            </button>
                            <button type="button" class="btn botonreporte">
                                <a style="color: white;" href='vista/fpdf/personal.php' target='_blank'>Generar Reporte</a>
                            </button>
                            
                            <button id="btnTutorial" class="botonverde">Comenzar Tutorial</button>

                            <div class="btn botonrojo">
                                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tablaPersonal" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Correo</th>
                                    <th>Teléfonos</th>
                                    <th>Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoPersonal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal para Personal -->
    <div class="modal fade" id="modalPersonal" tabindex="-1" aria-labelledby="modalPersonalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="padding: 25px 25px 0px 25px;">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPersonal" autocomplete="off">
                        <input type="hidden" id="accionPersonal" name="accion" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cedula_personal">Cédula</label>
                                    <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cedula_personal" name="cedula_personal" required>
                                    <small id="scedula_personal" class="form-text text-danger"></small>
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
                                    <label for="correo">Correo</label>
                                    <input type="email" class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="correo" name="correo" required>
                                    <small id="scorreo" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teléfonos</label>
                                    <div id="telefonos-container">
                                        <div class="input-group mb-2">
                                            <input class="form-control bg-gray-200 rounded-lg border-white telefono-input" type="text">
                                            <button type="button" class="btn btn-danger btn-remove-phone">-</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success" id="btn-add-phone">+ Agregar Teléfono</button>
                                    <small id="stelefono" class="form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cargo" name="cargo" required>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Enfermera">Enfermera</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn boton" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn botonverde" id="btnGuardarPersonal">Guardar</button>
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

<script src="js/personal.js"></script>
</body>
</html>