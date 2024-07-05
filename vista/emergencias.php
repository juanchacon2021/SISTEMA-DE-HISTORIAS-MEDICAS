<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800">
Emergencias
</div>
<div class="container pl-64"> <!-- todo el contenido ira dentro de esta etiqueta-->
	<div class="container">
		<div class="row mt-3 justify-content-between">
		    <div class="col-md-2">
				<a href="?pagina=crearpersonal" class="boton">Registrar Personal</a>
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
				<th>Hora de ingreso</th>
				<th>Fecha ingreso</th>
				<th>Motivo de ingreso</th>
				<th>Diagnostico_e</th>
				<th>Tratamientos</th>
				<th>Cedula_p</th>
                <th>Cedula_h</th>
			  </tr>
			</thead>
			<tbody id="resultadoconsulta">
			  
			  
			</tbody>
	   </table>
	  </div>
  </div>
</div> <!-- fin de container -->

<!-- seccion del modal consultar -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

     <div>holaaaaa</div>
              
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- fin de modal consultar -->


<!-- seccion del modal modificar -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    
    <div class="modal-content">
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Formulario de Personal</h5>
          <span aria-hidden="true"></span>
        
    </div>
		<div class="container"> <!-- todo el contenido ira dentro de esta etiqueta-->
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
							<option value="doctor">Doctor</option>
							<option value="enfermera">Enfermera</option>
							
					   </select>
					</div>
				</div>
				
				
				
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="row mt-3 justify-content-center">
					
				</div>
			</div>	
			</form>
		</div> <!-- fin de container -->
		<div class="modal-footer bg-light justify-content-center">
        <div class="col-md-2 ">                          
			 <button type="button" class="btn btn-primary" 
			 id="proceso" ></button>                
		</div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
		
	
    </div>
	
  </div>
</div>
<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/emergencias.js"></script>

</body>
</html>