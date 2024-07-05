<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Modificar Paciente
</div>

<!-- seccion del modal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Formulario de Personas</h5>
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
					<div class="col-md-4">
					   <label for="cedula">Cedula</label>
					   <input class="form-control" type="text" id="cedula" />
					   <span id="scedula"></span>
					</div>
					<div class="col-md-8">
					   <label for="apellido">apellido</label>
					   <input class="form-control" type="text" id="apellido" />
					   <span id="sapellido"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-8">
					   <label for="nombre">nombre</label>
					   <input class="form-control" type="text" id="nombre"  />
					   <span id="snombre"></span>
					</div>
					<div class="col-md-4">
					   <label for="fecha_nac">Fecha de Nacimiento</label>
					   <input class="form-control" type="date" id="fecha_nac" name="fecha_nac" />
					   <span id="sfecha_nac"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-3">
						<label  for="masculino">
						   Masculino	
						   <input class="form-control" type="radio" value="M" id="masculino" name="sexo" />
						</label>
						<label  for="femenino">
						   Femenino	
						   <input class="form-control" type="radio" value="F" id="femenino" name="sexo" />
						</label>
					</div>
					<div class="col-md-9">
					   <label for="gradodeinstruccion">Grado de Instruccion</label>
					   <select class="form-control" id="gradodeinstruccion">
							<option value="PRIMARIA">Primaria</option>
							<option value="BACHILLER">Bachiller</option>
							<option value="TSU">TSU</option>
							<option value="GRADO">Grado Superior</option>
							<option value="POSTGRADO">Post Grado</option>
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
<script type="text/javascript" src="js/pacientes.js"></script>

</body>
</html>