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
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Reportes Parametrizados', $_SESSION['permisos']['modulos'])) {
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
            Reportes Personalizados
        </div>
        <div class="container espacio">
            <section class="content">
                <div class="cardd">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn botonverde" onclick="generarReporte()">
                                    <i class="fas fa-file-pdf me-2"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tabla</label>
                                    <select class="form-control" id="tabla">
                                        <option value="">Seleccione una tabla</option>
                                        <option value="paciente">Pacientes</option>
                                        <option value="consulta">Consultas</option>
                                        <option value="emergencia">Emergencias</option>
                                        <option value="examen">Exámenes</option>
                                        <option value="medicamentos">Medicamentos</option>
                                        <option value="personal">Personal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" id="fecha_fin">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3" id="campos_filtro">
                            <!-- Campos de filtro dinámicos se cargarán aquí -->
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Campos a mostrar</label>
                                    <div id="campos_seleccion" class="row">
                                        <!-- Checkboxes de campos se cargarán aquí -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Vista previa</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tablaResultados">
                                            <thead id="tablaResultadosHead"></thead>
                                            <tbody id="tablaResultadosBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php require_once("comunes/modal.php"); ?>
        <?php require_once("comunes/footer.php"); ?>
    </div>

    <script src="js/reportes_p.js"></script>
</body>
</html>