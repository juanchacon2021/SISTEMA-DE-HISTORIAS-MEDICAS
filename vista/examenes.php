<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
	require_once("comunes/notificaciones.php");
?>
<body >
	<?php  // Verificar permisos
    if (!isset($permisos)) {
        // Si no existe $permisos, redirigir a login
        header("Location: ?pagina=login");
        exit();
    } elseif (!in_array('Examenes', $permisos)) {
        // Si existe $permisos pero no tiene acceso al módulo, mostrar error 403
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    } ?>

<?php if(in_array('Examenes', $permisos)): ?>
<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Examenes
</div>

<div class="container espacio"> <!-- todo el contenido ira dentro de esta etiqueta-->
	<div class="container">
		<div class="row mt-3 botones">
			<hr class="mb-12 text-gray-600">
				<div style="color: white;" class="botonverde" onclick='pone(this,3)' style="cursor: pointer;" >
					Registrar Tipo de Examen
				</div>
					
			<div class="col-md-2 recortar">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>

			<hr class="mt-12 text-gray-600">
		</div>
	</div>

	<div class="container">
		<div class="row mt-3 botonesdos">
			<div style="color: white;" class="botonverde" onclick="pone(this,4)" style="cursor: pointer;" >
				Registrar Examen a Paciente
			</div>
		</div>
	</div>

	<div class="container">
	   <div class="table-responsive">
		<table class="table table-striped table-hover" id="tablapersonal">
			<thead>
			  <tr>
				<th>Acciones</th>
				<th style="display:none;">Codigo del Registro</th>
				<th>Fecha</th>				
				<th>Observaciones</th>
				<th>Cedula del Paciente</th>
				<th>Nombre de Examen</th>
				<th style="display:none;">Tipo de Examen</th>
			  </tr>
			</thead>
			<tbody id="resultadoconsulta">
			  
			  
			</tbody>
	   </table>
	  </div>
  </div>
</div> <!-- fin de container -->

<!-- seccion del modal historias -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalpacientes" style="z-index:1500">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Listado de Pacientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-content">
		<table class="table table-striped table-hover" id="tablahistorias">
		<thead>
		  <tr>
		    <th style="display:none">Id</th>
			<th>Cedula</th>
			<th>Nombre</th>
			<th>Apellido</th>
		  </tr>
		</thead>
		<tbody id="listadopacientes">
		 
		</tbody>
		</table>
    </div>
	<div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
<!--fin de seccion modal-->

<!-- seccion del modal examenes -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalexamenes" style="z-index:1500">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Listado de Examenes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-content">
		<table class="table table-striped table-hover" id="tabladeexamenes">
		<thead>
		  <tr>
		    <th style="display:none">cod</th>
		    <th >cod_examenes</th>
			<th>Tipos de examen</th>
			<th>Descripcion</th>
		  </tr>
		</thead>
		<tbody id="listadoexamenes">
		 
		</tbody>
		</table>
    </div>
	<div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
<!--fin de seccion modal-->
 

<!-- seccion del modal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
		   <form method="post" id="f1" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container mt-2">	
				<div class="row mb-3">

					<div class="col-md-4" style="display: NONE;">
					   <label for="cod_examenes" >COD Examen</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_examenes" />
					   <span id="scod_examenes"></span>
					</div>

					<div class="col-md-5">
					   <label class="texto-inicio font-medium" for="nombre_examen">Nombre del Examen</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre_examen" />
					   <span id="snombre_examen"></span>
					</div>

					<div class="col-md-7">
					   <label class="texto-inicio font-medium" for="descripcion_examen">Descripción</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="descripcion_examen"  />
					   <span id="sdescripcion_examen"></span>
					</div>
                    
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				
                <div class="row mt-3 justify-content-center">
                    <div class="col-md-2">
                        <button style="color: white;" type="button" class="btn botonverde" id="proceso">INCLUIR</button>
                    </div>
                </div>
			</div>	
			</form>
		</div> <!-- fin de container -->
		<!--
		
		-->
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Formulario de Registro de Examenes</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         
    </div>
    <div class="modal-content">
		<div class="container"> <!-- contenido -->
		   <form method="post" id="f" autocomplete="off" enctype='multipart/form-data'>
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				
			<div class="col-md-4" style="display: none;" >
					   <label class="texto-inicio font-medium" for="cod_registro">Cod. de Registro</label >
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_registro" />
					   <span id="scod_registro"></span>
					</div>	
					
					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="fecha_r">Fecha</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fecha_r" />
					   <span id="sfecha_r"></span>
					</div>	

				</div>

				<div class="row mb-3">

				<label class="texto-inicio font-medium" for="cod_examenes1">Tipo de Examen</label>
					<div class="col-md-6 input-group">
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_examenes1" name="cod_examenes1"  />				  
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="codigo_examenes" name="codigo_examenes" style="display:none"/>
						<button type="button" class="btn btn-primary" id="listadodeexamenes" name="listadodeexamenes">LISTADO DE EXAMENES</button>
						<span id="scod_examenes1"></span>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12" id="datosdeexamen">
						
						</div>
					</div>
					<br>

					<label class="texto-inicio font-medium" for="cedula_h">Cedula del Paciente</label>
						<div class="col-md-6 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_h" name="cedula_h" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia" name="cedula_historia" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
							<span id="scedula_h"></span>
						</div>
				    
					<br>
					<div class="row">
						<div class="col-md-12" id="datosdelpacientes">
						
						</div>
					</div>


				</div>

				<div class="row mb-3">

					<div class="col-md-12">
					   <label class="texto-inicio font-medium" for="observacion_examen">Observación</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="observacion_examen"></textarea>
					   <span id="sobservacion_examen"></span>
					</div>
					
				</div>
				<div class="col-md-12">
					<hr/>
					<center>
						<label for="archivo"  style="cursor:pointer">
						   
							<img src="img/logo.png" id="imagen" 
							 class="img-fluid w-25 mt-3 mb-3 centered"
							 style="object-fit:scale-down; border-radius: 20px;">
							Subir foto del Examen
					    </label>
						<input id="archivo"  
						type="file" 
						style="display:none" 
						accept=".png,.jpg,.jpeg"
						name="imagenarchivo"/>
					</center>
					</div>						
				<div class="row mt-3 justify-content-center">
                    <div class="col-md-2">
                        <button style="color: white;" type="button" class="btn botonverde" id="proceso1"></button>
                    </div>
                </div>
			</div>	
			</form>
		</div> <!-- fin de container -->
	 
    </div>
  </div>
</div>

<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/examenes.js"></script> 
<<<<<<< HEAD

=======
<?php endif; ?>
>>>>>>> 1013b25ca68067cea044e63171e152da3e69d0bd
</body>
</html>