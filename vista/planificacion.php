<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>

<body>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
  Planificación
</div>

<div class="container espacio">
    <div class="container">
        <div class="row mt-3 botones">
            <a href="#" class="btn-flotante" style="cursor: pointer;" onclick="mostrarFormularioPublicacion()">
                <img src="img/lapiz.svg" alt="Nueva Publicación">
            </a>
                    
            <div class="col-md-2 recortar">    
                <a href="?pagina=principal" class="boton">Volver</a>
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
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="ocultarFormularioPublicacion()">Cancelar</button>
                        <button type="button" id="procesoPublicacion" class="btn btn-primary">Publicar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Listado de publicaciones -->
    <div class="container" id="listadoPublicaciones">
        <!-- Las publicaciones se cargarán aquí via AJAX -->
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once("comunes/modal.php"); ?>
<script src="js/planificacion.js"></script>

</body>
</html>