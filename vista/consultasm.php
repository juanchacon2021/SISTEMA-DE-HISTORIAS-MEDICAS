<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800">
Consultas Medicas
</div>
<div class="container pl-64"> <!-- todo el contenido ira dentro de esta etiqueta-->
	<div class="container">
		<div class="row mt-3 justify-content-between">
		    <div class="col-md-2 boton" onclick='pone(this,3)' >
				Registrar Consultas Medicas
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
				<th>Codigo de Consulta</th>
				<th>Fecha de Consulta</th>
				<th>Diagnostico</th>				
				<th>Tratamiento</th>
				<th>Cedula del Paciente</th>
				<th>Cedula del Doctor</th>
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
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Formulario de Consultas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-content">
		<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				<div class="row mb-3">
				
				<div class="col-md-4" style="display: none;">
					   <label for="cod_consulta">cod_consulta</label>
					   <input class="form-control" type="text" id="cod_consulta" />
					   <span id="scod_consulta"></span>
					</div>
					
					<div class="col-md-4">
					   <label for="fechaconsulta">Fecha de la Consulta</label>
					   <input class="form-control" type="date" id="fechaconsulta" />
					   <span id="sfechaconsulta"></span>
					</div>

                    <div class="col-md-4">
					   <label for="cedula_h">Cedula del Paciente</label>
					   <input class="form-control" type="text" id="cedula_h" />
					   <span id="scedula_h"></span>
					</div>

					
				</div>
				
				<div class="col-md-4">
					   <label for="diagnostico">Diagnostico</label>
					   <input class="form-control" type="text" id="diagnostico" />
					   <span id="sdiagnostico"></span>
					</div>

				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="tratamientos">Tratamiento</label>
					   <input class="form-control" type="text" id="tratamientos"  />
					   <span id="stratamientos"></span>
					</div>
                    
				</div>
				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="cedula_p">Cedula del Doctor</label>
					   <input class="form-control" type="text" id="cedula_p"  />
					   <span id="scedula_p"></span>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>

<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/consultasm.js"></script> 

</body>
</html>