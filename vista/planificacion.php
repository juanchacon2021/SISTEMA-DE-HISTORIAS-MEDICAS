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
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Planificacion', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
    ?>

    <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
        Planificación
    </div>

    <div class="container espacio">
        <div class="container">
            <div class="row mt-3 botones">
                <a href="#" id= "registrar" class="btn-flotante" style="cursor: pointer;" onclick="mostrarFormularioPublicacion()">
                    <img src="img/lapiz.svg" alt="Nueva Publicación">
                </a>

                <div class="col-md-2 recortar">
                    <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="boton">Volver</a>
                </div>
            </div>
        </div>

        <!-- Formulario para nueva publicación (oculto inicialmente) -->
        <div class="container mt-4 mb-5" id="formPublicacion" style="display: none;">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <form id="formPublicacionData" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" id="cod_pub" name="cod_pub">
                        <input type="hidden" id="accion" name="accion" value="incluir_publicacion">

                        <div class="mb-3">
                            <textarea class="form-control bg-gray-200 rounded-lg border-white p-3 text"
                                id="contenido" name="contenido"
                                rows="3" placeholder="¿Qué estás pensando?"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen (opcional)</label>
                            <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                            <img id="imagenVistaPrevia" src="" style="display:none; max-width: 100%; max-height: 200px;">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn botonrojo" onclick="ocultarFormularioPublicacion()">Cancelar</button>
                            <button type="button" id="procesoPublicacion" class="btn botonverde">Publicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de publicaciones -->
        <div class="container" id="listadoPublicaciones">
            <!-- Las publicaciones se cargarán aquí via AJAX -->
            <table id="tablaPublicaciones" class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Contenido</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyPublicaciones"></tbody>
            </table>
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
                    <p>¿Estás seguro de que deseas eliminar esta publicación?</p>
                </div>
                <div class="modal-footer botones">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn botonrojo" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para imagen ampliada -->
    <div class="modal fade" id="modalImagenPublicacion" tabindex="-1" aria-labelledby="modalImagenPublicacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center p-0">
                    <img id="imagenAmpliadaPublicacion" src="" class="img-fluid rounded" style="max-width: 90vw; max-height: 80vh;">
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script src="js/planificacion.js"></script>
    <script>
        const cedulaUsuarioLogueado = "<?php echo $_SESSION['usuario']; ?>";
    </script>

</body>

</html>