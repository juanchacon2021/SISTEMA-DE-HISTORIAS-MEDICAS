<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800">
Examenes
</div>

<div class="container pl-64"> <!-- todo el contenido ira dentro de esta etiqueta-->
	<div class="container">
		<div class="row mt-3 justify-content-between">
			
			<hr class="mb-12 text-gray-600">
		    <div class="botonverde">
				<div class="botonverde" onclick='pone(this,3)' style="cursor: pointer;" >
					Registrar Tipo de Examen
				</div>
			</div>
					
			<div class="col-md-2 mt-3">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>
		</div>
	</div>

	<hr class="mt-12 text-gray-600">

	<div class="botonverde w-[16.5rem] mt-12">
		<div class="botonverde" style="cursor: pointer;" >
			Registrar Examen a Paciente
		</div>
	</div>

	<div class="container">
	   <div class="table-responsive">
		<table class="table table-striped table-hover" id="tablapersonal">
			<thead>
			  <tr>
				<th>Acciones</th>
				<th>Cedula</th>
				<th>Apellido</th>
				<th>Nombre</th>
				<th>Tipo de Examen</th>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
		   <form method="post" id="f" autocomplete="off">
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
                        <button type="button" class="btn botonverde" id="proceso">INCLUIR</button>
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
<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/examenes.js"></script> 

</body>
</html>