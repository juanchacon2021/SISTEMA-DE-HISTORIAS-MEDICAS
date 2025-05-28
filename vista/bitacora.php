<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php  // Verificar permisos
    if (!isset($permisos)) {
        // Si no existe $permisos, redirigir a login
        header("Location: ?pagina=login");
        exit();
    } elseif (!in_array('Bitácora', $permisos)) {
        // Si existe $permisos pero no tiene acceso al módulo, mostrar error 403
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    } ?>

<div class="wrapper">
    
    <div class="container espacio">
        <section class="content">
            <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
                Bitácora del Sistema
            </div>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title mb-0">Registros de Actividad</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="filtroBitacora" placeholder="Filtrar...">
                                    <button class="btn btn-light" type="button" id="btnFiltrar">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-light ml-2" type="button" id="btnRecargar">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="tablaBitacora">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>Usuario</th>
                                        <th>Módulo</th>
                                        <th>Acción</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <?php require_once("comunes/footer.php"); ?>
</div>

<script src="js/bitacora.js"></script>
</body>
</html>