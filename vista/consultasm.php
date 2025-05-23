<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >
<?php if(in_array('Consultas', $permisos)): ?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Consultas
</div>
<div class="container espacio">
	
   
	<div class="container">
		<div class="row mt-3 botones">
		    <div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Consultas
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
			    <th style="display:none;">Cod. de Consulta</th> 
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Cedula del Paciente</th>
				<th>Fecha de Consulta</th>				
				<th>Nombre del Personal</th>
				<th>Apellido del Personal</th>
				<th>Cedula del Personal</th>


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

					<div class="col-md-2" style="margin-right:20px">
						<label class="texto-inicio font-medium" for="fechaconsulta">Fecha de Consulta</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fechaconsulta" />
						<span id="sfechaconsulta"></span>
					</div>

					<div class="col-md-3">
						<label class="texto-inicio font-medium" for="cedula_p">Cedula del Personal</label>
						<div class="boton-ced">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_p" name="cedula_p" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" style="display:none"/>
							<button type="button" class="btn btn-primary boton-lista" id="listadodepersonal" name="listadodepersonal">LISTADO DE PERSONAL</button>
							
						</div>			
						<span id="scedula_p"></span>		
						<div class="row" >
							<div class="col-md-12" id="datosdelpersonal">
							</div>
						</div>
					</div>
					
					

					
						<label class="texto-inicio font-medium" for="cedula_h">Cedula del Paciente</label>
						<div class="boton-ced">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_h" name="cedula_h" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia" name="cedula_historia" style="display:none"/>
							<button type="button" class="btn btn-primary boton-lista" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
						</div>
						<span id="scedula_h"></span>
						<div class="row">
							<div class="col-md-12" id="datosdelpacientes">
							
							</div>
						</div>
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
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

<!-- sssssssssssssssssssssss -->
			
				<div class="ml-2 mb-4">
                    <div class="texto-inicio font-medium">Examenes de Sistema</div>
                </div>
               

				<div class="row mb-4">
					<div class="col-md-6 mb-2">
						<label for="cabeza_craneo" class="texto-inicio font-medium">Cabeza Craneo</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cabeza_craneo"
								name="cabeza_craneo"/>
						<span id="scabeza_craneo"></span>
					</div>
					<div class="col-md-6 mb-2">
						<label for="cardiovascular" class="texto-inicio font-medium">Cardiovascular</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cardiovascular"
								name="cardiovascular"/>
						<span id="scardiovascular"></span>
					</div>
					<div class="col-md-6 mb-2">
						<label for="respiratorio" class="texto-inicio font-medium">Respiratorio</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="respiratorio"
								name="respiratorio"/>
						<span id="srespiratorio"></span>
					</div>
					<div class="col-md-6 mb-2">
						<label for="abdomen" class="texto-inicio font-medium">Abdomen</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="abdomen"
								name="abdomen"/>
						<span id="sabdomen"></span>
					</div>
					<div class="col-md-6 mb-2">
						<label for="neurologicos" class="texto-inicio font-medium">Neurologico</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="neurologicos"
								name="neurologicos"/>
						<span id="sneurologicos"></span>
					</div>
					<div class="col-md-6 mb-2">
						<label for="extremidades_s" class="texto-inicio font-medium">Extremidades del Sistema</label>
						<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="extremidades_s"
								name="extremidades_s"/>
						<span id="sextremidades_s"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>


				<div class="texto-inicio font-medium">Examenes Regional</div>
            
			
				<br>

				<div class="row mb-3">
					<div class="col-md-4 mb-2">
                        <label for="oidos" class="texto-inicio font-medium">Oidos</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="oidos"
                                name="oidos"/>
                        <span id="soidos"></span>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="ojos" class="texto-inicio font-medium">Ojos</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ojos"
                                name="ojos"/>
                        <span id="sojos"></span>
                    </div>

					<div class="col-md-4 mb-2">
                        <label for="nariz" class="texto-inicio font-medium">Nariz</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nariz"
                                name="nariz"/>
                        <span id="snariz"></span>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="boca_abierta" class="texto-inicio font-medium">Boca Abierta</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_abierta"
                                name="boca_abierta"/>
                        <span id="sboca_abierta"></span>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="boca_cerrada" class="texto-inicio font-medium">Boca Cerrada</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_cerrada"
                                name="boca_cerrada"/>
                        <span id="sboca_cerrada"></span>
                    </div>
                                                                                         
                    <div class="col-md-4 mb-2">
                        <label for="extremidades_r" class="texto-inicio font-medium">Extremidades Regionales</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="extremidades_r"
                                name="extremidades_r"/>
                        <span id="sextremidades_r"></span>
                    </div>

				</div>

				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="texto-inicio font-medium mb-4">Examen Físico</div>
				

				<div class="row mb-6">
					<div class="col-md-12">
						<label for="general" class="texto-inicio font-medium">Examen Fisico General</label>
						<textarea class="form-control bg-gray-200 rounded-lg border-white"   id="general" name="general"></textarea>
						<span id="sgeneral"></span>
					</div>
				</div>
				<br>

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
			<?php if ($datosPersonal['resultado'] == 'listadopersonal'): ?>
				<?php foreach ($datosPersonal['datos'] as $fila): ?>
					<tr onclick="colocapersonal(this)">
						<td style="display:none;"><?= htmlspecialchars($fila['cedula_personal']) ?></td>
						<td><?= htmlspecialchars($fila['cedula_personal']) ?></td>
						<td><?= htmlspecialchars($fila['nombre']) ?></td>
						<td><?= htmlspecialchars($fila['apellido']) ?></td>
						<td><?= htmlspecialchars($fila['cargo']) ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		 
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
			<?php if ($datosPacientes['resultado'] == 'listadopacientes'): ?>
				<?php foreach ($datosPacientes['datos'] as $fila): ?>
					<tr onclick="colocapacientes(this)">
						<td style="display:none;"><?= htmlspecialchars($fila['cedula_historia']) ?></td>
						<td><?= htmlspecialchars($fila['cedula_historia']) ?></td>
						<td><?= htmlspecialchars($fila['nombre']) ?></td>
						<td><?= htmlspecialchars($fila['apellido']) ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		</table>
    </div>
  </div>
</div>
<!--fin de seccion modal-->



<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/consultasm.js"></script> 
<?php endif; ?>
</body>
</html>