<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800">
Personal
</div>
<div class="container pl-64"> <!-- container-->
	<div class="container">
		<div class="row mt-3 justify-content-between">
		    <div class="col-md-2 botonverde" onclick='pone(this,3)' style="cursor: pointer;" >
				Registrar Personal
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
				<th>Cedula</th>
				<th>Apellidos</th>
				<th>Nombres</th>
				<th>Correo</th>
				<th>Telefono</th>
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
				<div class="row mb-3">
					
				<div class="col-md-4">
					   <label for="cedula_personal">Cedula</label>
					   <input class="form-control" type="text" id="cedula_personal" />
					   <span id="scedula_personal"></span>
					</div>
					<div class="col-md-8">
					   <label for="apellido">Apellidos</label>
					   <input class="form-control" type="text" id="apellido" />
					   <span id="sapellido"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-8">
					   <label for="nombre">Nombres</label>
					   <input class="form-control" type="text" id="nombre"  />
					   <span id="snombre"></span>
					</div>
					<div class="col-md-4">
					   <label for="correo">Correo</label>
					   <input class="form-control" type="mail" id="correo" name="correo" />
					   <span id="scorreo"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-8">
					   <label for="telefono">Telefono</label>
					   <input class="form-control" type="text" id="telefono"  />
					   <span id="stelefono"></span>
					</div>

					<div class="col-md-9">
					   <label for="cargo">Cargo</label>
					   <select class="form-control" id="cargo">
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
						   <button type="button" class="btn botonverde" 
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
<script type="text/javascript" src="js/personal.js"></script>

</body>
</html>