<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: /SISTEMA-DE-HISTORIAS-MEDICAS/login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Pacientes crónicos', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Pacientes cronicos
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
		    <div style="color: white;" class="btn botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Paciente Crónico
			</div>

			<div class="col-md-2">
				<button type="button" class="btn botonverde" data-bs-toggle="modal" data-bs-target="#modalopatologias">
					Patologías
				</button>
			</div>

			<div class="btn botonrojo">    
                <a href="/SISTEMA-DE-HISTORIAS-MEDICAS/principal">Volver</a>
            </div>
		</div>
	</div>
	<div class="container">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead class="table-dark">
                <tr>
                    <th style="text-align: center;">Cedula Pacientes</th>
                    <th style="text-align: center;">Nombre</th>
                    <th style="text-align: center;">Apellido</th>
					<th style="text-align: center;">Patologías Crónica</th>
					<th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody id="resultadoconsulta">
                
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="width: 60rem;">
      <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
			<div class="container">
				<form method="post" id="f" autocomplete="off">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
					<div class="container">	
						<h1 class="text-2xl font-bold mb-2">Formulario de Pacientes Crónicos</h1>

						<div>
							<div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
							</div>
						</div>

						<br>
						<div class="row mb-3">

							<div class="col-md-12">

								<label for="edad" class="texto-inicio font-medium mb-2">Cédula del Paciente</label>
								<div class="boton-ced">
									<input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Cedula" type="text" id="cedula_paciente" name="cedula_paciente" />				
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_pacienteistoria" name="cedula_pacienteistoria" style="display:none"/>
									<button type="button" class="btn botonrojo boton-lista" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
								</div>
								<span id="scedula_paciente"></span>
								<div class="row">
									<div class="col-md-12" id="datosdelpacientes"></div>
								</div>

							</div>
						
						</div>	
				
						
						


						<div class="row mb-3">
							<div id="bloque_agregar_patologia">

								<select id="select_patologia" class="form-control">
								<option value="" disabled selected>Seleccione una patología</option>
								<!-- Las opciones se llenarán por JS -->
								</select>
								<button type="button" class="btn btn-secondary mt-2" id="agregar_patologia">Agregar</button>

							</div>

							
							<div id="patologias_container"></div>

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
  <div class="modal-dialog modal-lg" role="document" style="box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);">
    <div class="modal-header text-light bg-white d-flex justify-between" style="padding: 25px 25px">
		<div>
			<h1 class="text-2xl font-bold mb-2 text-black">Listado de Pacientes</h1>

			<div>
				<div class="col-md-3 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
				</div>
			</div>
		</div>
        <div class="text-light text-end" style="margin: 20px 20px 0px 0px; cursor: pointer;">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
    </div>
    <div class="modal-content" style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);">
		<table class="table table-striped table-hover" id="tablahistorias">
		<thead>
		  <tr>
		    <th style="display:none">Id</th>
			<th style="text-align: center;">Cedula</th>
			<th style="text-align: center;">Nombre</th>
			<th style="text-align: center;">Apellido</th>
		  </tr>
		</thead>
		<tbody id="listadopacientes">
		
		</tbody>
		</table>
    </div>
  </div>
</div>
<!--fin de seccion modal-->




<!--modal de patologias-->
<div class="modal fade" tabindex="-1" id="modalopatologias">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header text-light d-flex" style="padding: 25px 25px">
				<div>
					<h1 class="text-2xl font-bold mb-2 text-black">Listado de Pacientes</h1>
					<div>
						<div class="col-md-3 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
						</div>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-hover" id="tabla_Patologias">
				<thead>
					<tr>
					<th style="display:none">Id</th>
					<th class="text-center">Nombre</th>
					<th class="text-center">Acción</th>
					</tr>
				</thead>
				<tbody id="listado_patologias">
				</tbody>
				</table>

				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="row mt-3 justify-content-center mb-4">
					<div style="color: white;" class="btn botonverde" style="cursor: pointer;" onclick='pone(this,4)' >
						Registrar Patologia
					</div>			
				</div>
				

			</div>
		</div>
	</div>
</div>
<!--modal de patologias-->


<!--modal de new patologias-->

<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header text-light bg-info gradiente">
			<h5 class="modal-title">Patologia</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="container">
			<form method="post" id="f" autocomplete="off">
				<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
				<div class="container">	
					<div class="primerafila">
						<div class="row">

							<div class="col-md-3" style="display: none;">
								<label class="texto-inicio font-medium" for="cod_patologia">cod_patologia</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_patologia" />
								<span id="scod_patologia"></span>
							</div>
					
						</div>	

						

						<div class="row mb-3">

							<div class="col-md-12">

								<label class="texto-inicio font-medium" for="nombre_patologia">Nombre de la Patologia</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre_patologia" />
								<span id="snombre_patologia"></span>
								
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


<!--modal de new patologias-->


<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/p_cronicos.js"></script> 

</body>
</html>