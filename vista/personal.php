<html> 
<?php 
    require_once("comunes/encabezado.php"); 
    require_once("comunes/sidebar.php");    
    require_once("comunes/notificaciones.php")
?>
<body >
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Personal', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Personal
</div>
<script type="text/javascript" src="js/personal.js"></script>
<div class="container espacio"> <!-- container-->
    <div class="container">
        <div class="row mt-3 botones">
            <div style="color: white;" class="btn botonverde" onclick='pone(this,3)' style="cursor: pointer;" >
                Registrar Personal
            </div>
            <div class="col-md-2 botonreporte">
                <a style="color: white;" class='btn' href='vista/fpdf/personal.php' target='_blank'>Generar Reporte</a>
            </div>
            <div class="btn botonrojo">    
                <a href="?pagina=principal">Volver</a>
            </div>
        </div>
    </div>
    <div class="container">
       <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Cedula</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th style="text-align: center;">Correo</th>
                <th>Teléfonos</th>
                <th>Cargo</th>
                
              </tr>
            </thead>
            <tbody id="resultadoconsulta">
            
            </tbody>
       </table>
       
      </div>
  </div>
</div> <!-- fin de container -->


<!-- seccion del modal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Formulario de Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         
    </div>
    <div class="modal-content">
        <div class="container"> <!-- contenido -->
           <form method="post" id="f" autocomplete="off">
            <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
            <div class="container">    
                <div class="row mb-3 pt-4">
                    <div class="col-md-6">
                       <label class="texto-inicio font-medium" for="cedula_personal">Cedula</label>
                       <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" />
                       <span id="scedula_personal"></span>
                    </div>
                    <div class="col-md-6">
                       <label class="texto-inicio font-medium" for="apellido">Apellidos</label>
                       <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="apellido" />
                       <span id="sapellido"></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                       <label class="texto-inicio font-medium" for="nombre">Nombres</label>
                       <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre"  />
                       <span id="snombre"></span>
                    </div>
                    <div class="col-md-6">
                       <label class="texto-inicio font-medium" for="correo">Correo</label>
                       <input class="form-control bg-gray-200 rounded-lg border-white" type="mail" id="correo" name="correo" />
                       <span id="scorreo"></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                       <label class="texto-inicio font-medium">Teléfonos</label>
                       <div id="telefonos-container">
                           <div class="input-group mb-2">
                               <input class="form-control bg-gray-200 rounded-lg border-white telefono-input" type="text" />
                               <button type="button" class="btn btn-danger btn-remove-phone">-</button>
                           </div>
                       </div>
                       <button type="button" class="btn btn-sm btn-success" id="btn-add-phone">+ Agregar Teléfono</button>
                       <span id="stelefono"></span>
                    </div>

                    <div class="col-md-6">
                       <label class="texto-inicio font-medium" for="cargo">Cargo</label>
                       <select class="form-control bg-gray-200 rounded-lg border-white" id="cargo">
                            <option value="Doctor">Doctor</option>
                            <option value="Enfermera">Enfermera</option>
                            
                       </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row mt-3 justify-content-center">
                    <div class="col-md-2">
                           <button style="color: white;" type="button" class="btn botonverde" 
                           id="proceso"></button>
                    </div>
                </div>
            </div>    
            </form>
        </div> <!-- fin de container -->
     
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>


</body>
</html>