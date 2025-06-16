<?php
require_once("comunes/encabezado.php");
require_once("comunes/sidebar.php");
	require_once("comunes/notificaciones.php");
?>
<html>
<body >
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Emergencias', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>
<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Emergencias
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
		    <div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Emergencias
			</div>

			<div class="col-md-2 recortar">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>
		</div>
	</div>
	<div class="container">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead class="table-dark">
                <tr>
					<th class="text-center">Nombre</th>
					<th class="text-center">Apellido</th>
					<th class="text-center">Hora de Ingreso</th>
					<th class="text-center">Fecha de Ingreso</th>               
					<th class="text-center">Cédula del Paciente</th>
					<th class="text-center">Nombre del Personal</th>
					<th class="text-center">Apellido del Personal</th>
					<th class="text-center">Cédula del Personal</th>
					<th class="text-center">Acciones</th>
				</tr>
            </thead>
            <tbody id="resultadoconsulta">
                
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Formulario de Emergencias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
		<div class="container">
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				<div class="row mb-3">
					<input type="hidden" id="old_cedula_paciente">
					<input type="hidden" id="old_cedula_personal">
					<input type="hidden" id="old_fechaingreso">
					<input type="hidden" id="old_horaingreso">
					
					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="horaingreso">Hora de Ingreso</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="time" id="horaingreso" />
					   <span id="shoraingreso"></span>
					</div>

					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="fechaingreso">Fecha de Ingreso</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fechaingreso" />
					   <span id="sfechaingreso"></span>
					</div>
				</div>

				<br>

                <div class="row mb-3">
				    <div class="col-md-6" >
					   <label class="texto-inicio font-medium" for="motingreso">Motivo de Ingreso</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="motingreso"></textarea>
					   <span id="smotingreso"></span>
					</div>

					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="diagnostico_e">Diagnostico</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="diagnostico_e"></textarea>
					   <span id="sdiagnostico_e"></span>
					</div>
                    
				</div>
				<div class="row mb-3">

					<div class="col-md-12">
					   <label class="texto-inicio font-medium" for="tratamientos">Tratamientos</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="tratamientos"></textarea>
					   <span id="stratamientos"></span>
					</div>
				</div>

					<div class="col-md-12">
						<label class="texto-inicio font-medium" for="cedula_personal">Cedula del Personal</label>
						<div class="col-md-8 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepersonal" name="listadodepersonal">LISTADO DE PERSONAL</button>
						</div>
						<span id="scedula_personal"></span>
					</div>
					<div class="row">
						<div class="col-md-12" id="datosdelpersonal">
						
						</div>
					</div>
					<br>

					<label class="texto-inicio font-medium" for="cedula_paciente">Cedula del Paciente</label>
						<div class="col-md-8 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_paciente" name="cedula_paciente" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_paciente" name="cedula_paciente" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
						</div>
						<span id="scedula_paciente"></span>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12" id="datosdelpacientes">
						
						</div>
					</div>
					<br>
					
				                  
				</div>

				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>


				<div class="row mt-3 justify-content-center mb-4">
					<div class="col-md-2">
						   <button style="color: white;" type="button" class="btn botonverde" id="proceso"></button>
					</div>
				</div>
				
			</div>	
			</form>
		</div> 
		
    </div>
	
  </div>
</div>

<!-- seccion del modal personal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalpersonal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Personal</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<table class="table table-striped table-hover" id="tabladelpersonal">
		<thead>
		  <tr>
		    <th style="display:none">Id</th>
			<th>Cedula</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Cargo</th>
		  </tr>
		</thead>
		<tbody id="listadopersonal">

		 
		</tbody>
		</table>
    </div>
  </div>
</div>
<!--fin de seccion modal-->


<!-- seccion del modal historias -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalpacientes">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Pacientes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
  </div>
</div>
<!--fin de seccion modal-->



<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/emergencias.js"></script> 
</body>
</html>