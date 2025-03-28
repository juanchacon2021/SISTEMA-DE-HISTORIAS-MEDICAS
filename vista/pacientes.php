<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>

<body>
<?php
	if($nivel=='Doctor'){
?>
<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
Pacientes
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
			<div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Paciente
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
					<th>Cédula</th>
					<th>Apellido</th>
					<th>Nombre</th>
					<th>Fecha de Nacimiento</th>
					<th>Edad</th>
					<th>Teléfono</th>
					<th style="display: none;">Estado Civil</th>
					<th style="display: none;">Dirección</th>
					<th style="display: none;">Ocupación</th>
					<th style="display: none;">HDA</th>
					<th style="display: none;">Hábito Tóxico</th>
					<th style="display: none;">Alergias</th>
					<th style="display: none;">Alergias Medicas</th>
					<th style="display: none;">Quirurgico</th>
					<th style="display: none;">Psicosocial</th>
					<th style="display: none;">Transsanguineo</th>
					<th style="display: none;">Antecedentes Padre</th>
					<th style="display: none;">Antecedentes Madre</th>
					<th style="display: none;">Antecedentes Hermano</th>
				</tr>
			</thead>
			<tbody id="resultadoconsulta">
                
            </tbody>
		</table>
	</div>
</div>
</div>

<!-- SECCION MODAL -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
		<div class="modal-header text-light bg-info gradiente">
			<h5 class="modal-title">Formulario de Pacientes</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
			<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
				<form method="post" id="f" autocomplete="off">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
					<div class="container">	
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="cedula_historia" class="texto-inicio font-medium">Cedula</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia"
								name="cedula_historia"/>
								<span id="scedula_historia"></span>
							</div>

							<div class="col-md-3">
								<label for="nombre" class="texto-inicio font-medium">Nombres</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre"
										name="nombre"/>
								<span id="snombre"></span>
							</div>

							<div class="col-md-3">
								<label for="apellido" class="texto-inicio font-medium">Apellidos</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="apellido"
									name="apellido"/>
								<span id="sapellido"></span>
							</div>

							<div class="col-md-3">
								<label for="fecha_nac" class="texto-inicio font-medium">Fecha Nacimiento</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fecha_nac" name="fecha_nac" 
								/>
								<span id="sfecha_nac"></span>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="edad" class="texto-inicio font-medium">Edad</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="number" id="edad"
									   name="edad"/>
								<span id="sedad"></span>
							</div>

							<div class="col-md-3">
								<label for="estadocivil" class="texto-inicio font-medium">Estado Civil</label>
								<select class="form-control bg-gray-200 rounded-lg border-white" id="estadocivil" 
										name = "estadocivil">
										<option value="" selected>-- Seleccione --</option>
										<option value="SOLTERO">Soltero</option>
										<option value="CASADO">Casado</option>
										<option value="DIVORCIADO">Divorciado</option>
										<option value="VIUDO">Viudo</option>
								</select>
							</div>
							
							<div class="col-md-3">
								<label for="ocupacion" class="texto-inicio font-medium">Ocupacion</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ocupacion"
										name="ocupacion"/>
								<span id="socupacion"></span>
							</div>

							<div class="col-md-3">
								<label for="direccion" class="texto-inicio font-medium">Direccion</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="direccion"
										name="direccion"/>
								<span id="sdireccion"></span>
							</div>
							
							
						</div>

						<div class="row py-4">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="telefono" class="texto-inicio font-medium">Telefono</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="telefono"
										name="telefono"/>
								<span id="stelefono"></span>
							</div>

							<div class="col-md-6">
								<label for="hda" class="texto-inicio font-medium">H.D.A</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="hda"
										name="hda"/>
								<span id="shda"></span>
							</div>

							<div class="col-md-3">
								<label for="alergias" class="texto-inicio font-medium">Alergias</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias"
										name="alergias"/>
								<span id="salergias"></span>
							</div>

							<div class="col-md-3">
								<label for="alergias_med" class="texto-inicio font-medium">Alergias Médicas</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias_med"
										name="alergias_med"/>
								<span id="salergias_med"></span>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="quirurgico" class="texto-inicio font-medium">Quirurgico</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="quirurgico"
										name="quirurgico"/>
								<span id="squirurgico"></span>
							</div>
							
							<div class="col-md-3">
								<label for="transsanguineo" class="texto-inicio font-medium">Trans Sanguineos</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="transsanguineo"
										name="transsanguineo"/>
								<span id="stranssanguineo"></span>
							</div>

							<div class="col-md-3">
								<label for="habtoxico" class="texto-inicio font-medium">Habito Toxico</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="habtoxico"
										name="habtoxico"/>
								<span id="shabtoxico"></span>
							</div>

							<div class="col-md-3">
								<label for="psicosocial" class="texto-inicio font-medium">Psicosocial</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="psicosocial"
										name="psicosocial"/>
								<span id="spsicosocial"></span>
							</div>
						</div>

						<div class="row py-4">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row mb-3 mx-1">
							<div class="col-md-6">
								<label for="antc_madre" class="texto-inicio font-medium">Antecedentes Maternos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antcmadre" id="antc_madre"></textarea>
								<span id="santcmadre"></span>
							</div>
							
							<div class="col-md-6">
								<label for="antc_padre" class="texto-inicio font-medium">Antecedentes Paternos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antcpadre" id="antc_padre"></textarea>
								<span id="santcpadre"></span>
							</div>
							
							<div class="col-md-6">
								<label for="antc_hermano" class="texto-inicio font-medium">Antecedentes Hermanos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antchermano" id="antc_hermano"></textarea>
								<span id="santchermano"></span>
							</div>
						</div>

						<div class="row mt-3 justify-content-center">
							<div class="col-md-2">
								<button type="button" class="btn botonverde" id="proceso"></button>
							</div>
						</div>
					</div>	
				</form>
			</div>
		</div>
    </div>
</div>


<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/pacientes.js"></script>
<?php
	}
?>
</body>
</html>