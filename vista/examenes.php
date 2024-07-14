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
		    <div class="col-md-2 botonverde">
			<div class="col-md-2 botonverde" onclick='pone(this,3)' style="cursor: pointer;" >
				Registrar Examen Medico
			</div>
			</div>
					
			<div class="col-md-2">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>
		</div>
	</div>
	<div class="container">
	   <div class="table-responsive">
		<table class="table table-striped table-hover" id="tablapersonal">
			<thead>
			  <tr>
				<th>Acciones</th>
				<th>Codigo Examenes</th>
				<th>Tipo</th>
				<th>Observacion</th>
				<th>Cedula H</th>
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
        <h5 class="modal-title">Formulario de Examenes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				<div class="row mb-3">

					<div class="col-md-4">
					   <label for="cod_examenes" style="display: NONE;">COD Examen</label>
					   <input class="form-control" type="text" id="cod_examenes" />
					   <span id="scod_examenes"></span>
					</div>

                    <div class="col-md-4">
					   <label for="cedula_h">Cedula</label>
					   <input class="form-control" type="text" id="cedula_h" />
					   <span id="scedula_h"></span>
					</div>

					<div class="col-md-4">
					   <label for="tipo_examen">Tipo de Examen</label>
					   <input class="form-control" type="text" id="tipo_examen" />
					   <span id="stipo_examen"></span>
					</div>

				</div>
				
				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="observacion_medica">Observaciones</label>
					   <input class="form-control" type="text" id="observacion_medica"  />
					   <span id="sobservacion_medica"></span>
					</div>
                    
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="row mt-3 justify-content-center">
					<div class="col-md-2">
						   <button type="button" class="btn btn-primary" 
						   id="proceso" ></button>
					</div>
				</div>
			</div>	
			</form>
		</div> <!-- fin de container -->
		<!--
		
		-->
    </div>
	<div class="modal-footer bg-light">
        <button type="button" class="btn botonverde" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/examenes.js"></script> 

</body>
</html>