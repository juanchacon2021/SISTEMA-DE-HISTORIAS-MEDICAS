<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<?php  // Verificar permisos
    if (!isset($permisos)) {
        // Si no existe $permisos, redirigir a login
        header("Location: ?pagina=login");
        exit();
    } elseif (!in_array('Pacientes crónicos', $permisos)) {
        // Si existe $permisos pero no tiene acceso al módulo, mostrar error 403
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    } ?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Pacientes cronicos
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
		    <div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar pacientes cronicos
			</div>

			<div class="col-md-2 recortar">	
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
                    <th style="display:none;">Cod. de emergencia</th> 
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Patologia Cronica</th>
                    <th>Nombre del Paciente</th>
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
        <h5 class="modal-title">Formulario de pacientes cronicos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
		<div class="container">
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	

<br>
			<div class="row mb-3">

				<label class="texto-inicio font-medium" for="cedula_h">Cedula del Paciente</label>
						<div class="col-md-8 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_h" name="cedula_h" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia" name="cedula_historia" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
						</div>
						<span id="scedula_h"></span>
					</div>
					<div class="row">
						<div class="col-md-12" id="datosdelpacientes">
						
						</div>
					</div>

			</div>

			<div class="row mb-3 ms-2">


			<div class="col-md-12" style="display: none;">
					   <label class="texto-inicio font-medium" for="cod_cronico">Cod. de pacientes cronicos</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_cronico" />
					   <span id="scod_cronico"></span>
					</div>
					
					<div class="col-md-12">
					<label class="texto-inicio font-medium" for="patologia_cronica">Patología Crónica</label>
					<input
						class="form-control bg-gray-200 rounded-lg border-white"
						type="text"
						id="patologia_cronica"
						name="patologia_cronica"
						readonly
					/>



			</div>
				
				<div class="row mb-3 ms-2">
					
					<div id="patologia_options" class="mb-2">
					<div class="row" id="patologia_options">
						<div class="col-md-6">
						<label><input type="checkbox" value="Cardiopatía" onchange="actualizarCheckboxes()"> Cardiopatía</label><br>
						<label><input type="checkbox" value="Hipertensión" onchange="actualizarCheckboxes()"> Hipertensión</label><br>
						<label><input type="checkbox" value="Endocrinometabólico" onchange="actualizarCheckboxes()"> Endocrinometabólico</label>
						</div>
						<div class="col-md-6">
						<label><input type="checkbox" value="Asmático" onchange="actualizarCheckboxes()"> Asmático</label><br>
						<label><input type="checkbox" value="Renal" onchange="actualizarCheckboxes()"> Renal</label><br>
						<label><input type="checkbox" value="Mental" onchange="actualizarCheckboxes()"> Mental</label>
						</div>
					</div>
					</div>

					
					<span id="spatologia_cronica"></span>
					</div>

                    <div class="col-md-12">
					   <label class="texto-inicio font-medium" for="Tratamiento">Tratamiento</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="Tratamiento" />
					   <span id="Tratamiento"></span>
					</div>
				</div>

				<br>

                <div class="row mb-3 ms-2">
				    <div class="col-md-12" >
					   <label class="texto-inicio font-medium" for="admistracion_t">Admistracion de Tratamiento</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="admistracion_t"></textarea>
					   <span id="sadmistracion_t"></span>
					</div>
                    
				</div>
				<div class="row mb-3">
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
<script type="text/javascript" src="js/p_cronicos.js"></script> 

</body>
</html>