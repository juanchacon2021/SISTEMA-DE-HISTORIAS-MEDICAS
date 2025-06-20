<!DOCTYPE html>
<html lang="es">
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Consultas', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>
<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Consultas
</div>
<div class="container espacio">


			<div class="container">
				<div class="row mt-3 botones">
					<div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
						Registrar Consultas
					</div>

					<div class="col-md-2">
						<button type="button" class="btn botonverde" data-bs-toggle="modal" data-bs-target="#modalobservacion">
							Observaciones
						</button>
					</div>

						
					<div class="col-md-2 recortar">	
						<a href="?pagina=principal" class="boton">Volver</a>
					</div>
			</div>

			<div class="container">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tablapersonal">
						<thead>

						<tr>
							
							<th style="display:none;">Cod. de Consulta</th> 
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Cedula del Paciente</th>
							<th>Fecha de Consulta</th>
							<th>Hora de Consulta</th>
							<th>Nombre del Personal</th>
							<th>Apellido del Personal</th>
							<th>Cedula del Personal</th>
							<th>Acciones</th>

						</tr>

						</thead>
						<tbody id="resultadoconsulta">
						
						
						</tbody>

				</table>
				</div>
			</div>


	
	
</div> 

<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
		<div class="modal-header text-light bg-info gradiente">
			<h5 class="modal-title">Formulario de Consultas</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="container">
			<form method="post" id="f" autocomplete="off">
				<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
				<div class="container">	
					<div class="primerafila">
						<div class="row">

							<div class="col-md-3" style="display: none;">
								<label class="texto-inicio font-medium" for="cod_consulta">Cod. de Consulta</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_consulta" />
								<span id="scod_consulta"></span>
							</div>
					
						</div>	

						<div class="row mb-3">

							<div class="col-md-7" style="width: 555px;">

								<label class="texto-inicio font-medium" for="cedula_paciente">Cedula del Paciente</label>
								<div class="boton-ced">
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_paciente" name="cedula_paciente" />				
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_pacienteistoria" name="cedula_pacienteistoria" style="display:none"/>
									<button type="button" class="btn btn-primary boton-lista" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
								</div>
								<span id="scedula_paciente"></span>
								<div class="row">
									<div class="col-md-12" id="datosdelpacientes"></div>
								</div>

							</div>

							<div class="col-md-7" style="width: 555px;">

								<label class="texto-inicio font-medium" for="cedula_personal">Cedula del Personal</label>
								<div class="boton-ced">
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" />				
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" style="display:none"/>
									<button type="button" class="btn btn-primary boton-lista" id="listadodepersonal" name="listadodepersonal">LISTADO DE PERSONAL</button>
									
								</div>			
								<span id="scedula_personal"></span>		
								<div class="row" >
									<div class="col-md-12" id="datosdelpersonal"></div>
								</div>
								
							</div>

						</div>

						<div class="row mb-3">

							<div class="col-md-6">

								<label class="texto-inicio font-medium" for="fechaconsulta">Fecha de Consulta</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fechaconsulta" />
								<span id="sfechaconsulta"></span>
								
							</div>

							<div class="col-md-6">

								<label class="texto-inicio font-medium" for="Horaconsulta">Hora de Consulta</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="time" id="Horaconsulta" />
								<span id="sHoraconsulta"></span>

							</div>						
							
						</div>

						<div class="row mb-3">

							<div class="col-md-4" >
								<label class="texto-inicio font-medium" class="mt-[-20px]" for="consulta">Consulta</label>
								<textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="consulta"></textarea>
								<span id="sconsulta"></span>
							</div>  

							<div class="col-md-4">
								<label class="texto-inicio font-medium" for="diagnostico">Diagnostico</label>
								<textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="diagnostico"></textarea>
								<span id="sdiagnostico"></span>
							</div>

							<div class="col-md-4">
								<label class="texto-inicio font-medium" for="tratamientos">Tratamientos</label>
								<textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="tratamientos"></textarea>
								<span id="stratamientos"></span>
							</div>

						</div>	

						<div class="row mb-3">
							<div id="bloque_agregar_observacion">

								<select id="select_observacion" class="form-control">
								<option value="" disabled selected>Seleccione una observación</option>
								<!-- Las opciones se llenarán por JS -->
								</select>
								<button type="button" class="btn btn-secondary mt-2" id="agregar_observacion">Agregar</button>

							</div>

							
							<div id="observaciones_container"></div>

						</div>


						

						<div class="row">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>
					
						<div class="row mt-3 justify-content-center mb-4">
							<div class="col-md-2">
								<button style="color: white;" type="button" class="btn botonverde" 
								id="proceso" ></button>
							</div>
						</div>
					</div>
				</div>	
			</form>
		</div> 	
    </div>
  </div>
</div> 

<!-- seccion del modal personal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalpersonal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
          <tbody id="listadopersonal"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--fin de seccion modal-->


<!-- seccion del modal historias -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalpacientes">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Pacientes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-hover" id="tablahistorias">
          <thead>
            <tr>
              <th style="display:none">Id</th>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
            </tr>
          </thead>
          <tbody id="listadopacientes"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--fin de seccion modal-->

<!--modal de observaciones-->
<div class="modal fade" tabindex="-1" id="modalobservacion">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header text-light bg-info gradiente">
				<h5 class="modal-title">Listado de Observaciones</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-hover" id="tabla_Observaciones">
				<thead>
					<tr>
					<th style="display:none">Id</th>
					<th class="text-center">Nombre</th>
					<th class="text-center">Acción</th>
					</tr>
				</thead>
				<tbody id="listado_observaciones">
				</tbody>
				</table>

				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="row mt-3 justify-content-center mb-4">
					<div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,4)' >
						Registrar observación
					</div>			
				</div>
				

			</div>
		</div>
	</div>
</div>
<!--modal de observaciones-->

<!--modal de new observaciones-->

<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header text-light bg-info gradiente">
			<h5 class="modal-title">Observación</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="container">
			<form method="post" id="f" autocomplete="off">
				<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
				<div class="container">	
					<div class="primerafila">
						<div class="row">

							<div class="col-md-3" style="display: none;">
								<label class="texto-inicio font-medium" for="cod_observacion">cod_observacion</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_observacion" />
								<span id="scod_observacion"></span>
							</div>
					
						</div>	

						

						<div class="row mb-3">

							<div class="col-md-12">

								<label class="texto-inicio font-medium" for="nom_observaciones">Nombre de la Observación</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nom_observaciones" />
								<span id="snom_observaciones"></span>
								
							</div>			
							
						</div>
	

						<div class="row">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>
					
						<div class="row mt-3 justify-content-center mb-4">
							<div class="col-md-2">
								<button style="color: white;" type="button" class="btn botonverde" 
								id="proceso2" ></button>
							</div>
						</div>
					</div>
				</div>	
			</form>
		</div> 	
    </div>
  </div>
</div> 


<!--modal de new observaciones-->



<?php require_once("comunes/modal.php"); ?>

<script type="text/javascript" src="js/consultasm.js"></script> 

</body>

</html>