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
			<div class="col-md-2 botonverde" style="cursor: pointer;" >
				<a href="?pagina=historia" style="color: white;">Registrar Paciente</a>
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
				<th>Cedula</th>
				<th>Apellido</th>
				<th>Nombre</th>
				<th>Fecha de Nacimiento</th>
				<th>Edad</th>
				<th>Telefono</th>
			  </tr>
			</thead>
			<tbody id="resultadoconsulta">
			  
			  
			</tbody>
	   </table>
	  </div>
  </div>
</div> <!-- FIN DEL CONTAINER -->


<!-- SECCION MODAL -->
<!-- <div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-header text-light gradiente flex justify-content-between">
			<div class="flex justify-content-end w-[50%]">
				<h5 class="modal-title font-semibold text-xl">Paciente</h5>
			</div>
			<div class="w-[50%] flex justify-content-end">
				<button type="button" class="close text-[30px]"  style="cursor: pointer" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true" style="cursor: pointer">&times;</span>
				</button>
			</div>
			</button>
		</div>
		<div class="modal-content">
			<div class="container mt-4"> <!-- todo el contenido ira dentro de esta etiqueta-->
				<!-- <form method="post" id="f" autocomplete="off">
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
								<label for="estadocivi" class="texto-inicio font-medium">Estado Civil</label>
								<select class="form-control bg-gray-200 rounded-lg border-white" id="estadocivi" 
										name = "estadocivi">
										<option value="" selected>-- Seleccione --</option>
										<option value="SOLTERO">Soltero</option>
										<option value="CASADO">Casado</option>
										<option value="DIVORCIADO">Divorciado</option>
										<option value="VIUDO">Viudo</option>
								</select>
							</div>
							
							<div class="col-md-3">
								<label for="direccion" class="texto-inicio font-medium">Direccion</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="direccion"
										name="direccion"/>
								<span id="sdireccion"></span>
							</div>
							
							<div class="col-md-3">
								<label for="telefono" class="texto-inicio font-medium">Telefono</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="telefono"
										name="telefono"/>
								<span id="stelefono"></span>
							</div>
						</div>

						<div class="row py-4">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="ocupacion" class="texto-inicio font-medium">Ocupacion</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ocupacion"
										name="ocupacion"/>
								<span id="socupacion"></span>
							</div>

							<div class="col-md-6">
								<label for="hda" class="texto-inicio font-medium">H.D.A</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="hda"
										name="hda"/>
								<span id="shda"></span>
							</div>

							<div class="col-md-3">
								<label for="habtoxico" class="texto-inicio font-medium">Habito Toxico</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="habtoxico"
										name="habtoxico"/>
								<span id="shabtoxico"></span>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="alergias" class="texto-inicio font-medium">Alergias</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias"
										name="alergias"/>
								<span id="salergias"></span>
							</div>

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
								<label for="boca_abierta" class="texto-inicio font-medium">Boca Abierta</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_abierta"
										name="boca_abierta"/>
								<span id="sboca_abierta"></span>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="boca_cerrada" class="texto-inicio font-medium">Boca Cerrada</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_cerrada"
										name="boca_cerrada"/>
								<span id="sboca_cerrada"></span>
							</div>

							<div class="col-md-3">
								<label for="oidos" class="texto-inicio font-medium">Oidos</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="oidos"
										name="oidos"/>
								<span id="soidos"></span>
							</div>
							
							<div class="col-md-3">
								<label for="cabeza_craneo" class="texto-inicio font-medium">Cabeza Craneo</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cabeza_craneo"
										name="cabeza_craneo"/>
								<span id="scabeza_craneo"></span>
							</div>
							
							<div class="col-md-3">
								<label for="ojos" class="texto-inicio font-medium">Ojos</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ojos"
										name="ojos"/>
								<span id="sojos"></span>
							</div>
						</div>

						<div class="row py-4">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-3">
								<label for="nariz" class="texto-inicio font-medium">Nariz</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nariz"
										name="nariz"/>
								<span id="snariz"></span>
							</div>

							<div class="col-md-3">
								<label for="tiroides" class="texto-inicio font-medium">Tiroides</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="tiroides"
										name="tiroides"/>
								<span id="stiroides"></span>
							</div>
							
							<div class="col-md-3">
								<label for="cardiovascular" class="texto-inicio font-medium">Cardiovascular</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cardiovascular"
										name="cardiovascular"/>
								<span id="scardiovascular"></span>
							</div>
							
							<div class="col-md-3">
								<label for="respiratorio" class="texto-inicio font-medium">Respiratorio</label>
								<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="respiratorio"
										name="respiratorio"/>
								<span id="srespiratorio"></span>
							</div>
						</div>

							<div class="row mb-3">
								<div class="col-md-3">
									<label for="abdomen" class="texto-inicio font-medium">Abdomen</label>
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="abdomen"
											name="abdomen"/>
									<span id="sabdomen"></span>
								</div>

								<div class="col-md-3">
									<label for="extremidades" class="texto-inicio font-medium">Extremidades</label>
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="extremidades"
											name="extremidades"/>
									<span id="sextremidades"></span>
								</div>
								
								<div class="col-md-3">
									<label for="neurologico" class="texto-inicio font-medium">Neurologico</label>
									<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="neurologico"
											name="neurologico"/>
									<span id="sneurologico"></span>
								</div>
							</div>
						</div>

						<div class="row py-4">
							<div class="col-md-12">
								<hr/>
							</div>
						</div>

						<div class="row mb-3 mx-1">
							<div class="col-md-6">
								<label for="nariz" class="texto-inicio font-medium">Antecedentes Personales</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="anteecpersonal" id=""></textarea>
								<span id="santecpersonal"></span>
							</div>

							<div class="col-md-6">
								<label for="tiroides" class="texto-inicio font-medium">Antecedentes Maternos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antecmadre" id=""></textarea>
								<span id="santecmadre"></span>
							</div>
							
							<div class="col-md-6">
								<label for="cardiovascular" class="texto-inicio font-medium">Antecedentes Paternos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antecpadre" id=""></textarea>
								<span id="santecpadre"></span>
							</div>
							
							<div class="col-md-6">
								<label for="respiratorio" class="texto-inicio font-medium">Antecedentes Hermanos</label>
								<textarea class="form-control bg-gray-200 rounded-lg border-white" name="antechermano" id=""></textarea>
								<span id="santechermano"></span>
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
</div> --> 

<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/pacientes.js"></script>
<?php
					  }
					?>
</body>
</html>