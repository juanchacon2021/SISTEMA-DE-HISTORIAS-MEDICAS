<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>

<body>
<?php
    if ($nivel == 'Doctor' || $nivel == 'Enfermera') {
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
  Planificacion
</div>
<div class="container espacio">
    <div class="container">
		  <div class="row mt-3 botones">
        <a href="#" class="btn-flotante" style="cursor: pointer;" onclick='pone(this,3)'>
            <img src="img/lapiz.svg" alt="">
        </a>
					
        <div class="col-md-2 recortar">	
          <a href="?pagina=principal" class="boton">Volver</a>
        </div>
      </div>
    </div>

    <!-- codigo scroll -->
    <div class="scroll">
      <?php require_once 'comunes/publicaciones.php'; ?>
          </div>

            <script>
            //Simple codigo para hacer la paginacion scroll
            $(document).ready(function() {
              $('.scroll').jscroll({
                loadingHtml: '<img src="images/invisible.png" alt="Loading" />'
            });
            });
    </script>
          <!-- codigo scroll -->
</div>


<!-- SECCION MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <center><div class="modal-content mt-12" style="width: 35rem;">
      <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="container">
      <form method="POST" action="controlador/planificacion.php" id="formPlanificacion" enctype="multipart/form-data" autocomplete="off" style="margin: 0em 2em 2em 2em;">
        <input type="hidden" name="accion" id="accion" value="guardarPublicacion">
        <div class="row mb-6">
            <h1 class="text-2xl font-bold mb-2">Crear publicación</h1>
        </div>
        <div class="col-md-12 position-relative">
            <textarea class="form-control bg-white rounded-lg border-white p-3" 
                      placeholder="¿Qué estás pensando?" 
                      style="font-size: 25px;" 
                      id="contenido"
                      name="contenido"></textarea>
        </div>
        <div class="borde flex justify-between align-items-center border-3 border-zinc-500 p-2 mt-4">
            <div class="font-medium text-xl opacity-50">
                <span>Agregar a tu publicación</span>
            </div>
            <div class="subir">
                <label for="imagen" class="d-flex align-items-center cursor-pointer">
                    <img src="img/camera.svg" alt="" style="width:30px;">
                </label>
                <input type="file" id="imagen" name="imagen" class="d-none" accept="image/*">
            </div>
        </div>
        <div class="mt-3 flex justify-between">
            <div class="col-md-12 publicar">
                <button type="submit" id="guardarPublicacionBtn">Publicar</button>
            </div>
        </div>
      </form>
      <div id="mensaje" class="mt-3"></div>
		  </div>
    </div></center>
  </div>
</div>

<?php require_once("comunes/modal.php"); ?>
  <script src="js/planificacion.js"></script>
<?php
    }					
?>
</body>
</html>