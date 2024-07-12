<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800">
Emergencias
</div>
<!--nuevo-->
<div class="container">	
				<div class="row mb-3">
				
				<div class="col-md-4" style="display: none;">
					   <label for="cod_emergencia">cod_emergencia</label>
					   <input class="form-control" type="text" id="cod_emergencia" />
					   <span id="scod_emergencia"></span>
					</div>
					
					<div class="col-md-4">
					   <label for="horaingreso">Hora de Ingreso</label>
					   <input class="form-control" type="text" id="horaingreso" />
					   <span id="shoraingreso"></span>
					</div>

                    <div class="col-md-4">
					   <label for="fechaingreso">Fecha de Ingreso</label>
					   <input class="form-control" type="date" id="fechaingreso" />
					   <span id="fechaingreso"></span>
					</div>

					
				</div>
				
				<div class="col-md-4">
					   <label for="motingreso">Motivo de Ingrso</label>
					   <input class="form-control" type="text" id="motingreso" />
					   <span id="smotingreso"></span>
					</div>

				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="diagnostico_e">Diagnostico</label>
					   <input class="form-control" type="text" id="diagnostico_e"  />
					   <span id="sdiagnostico_e"></span>
					</div>
                    
				</div>
				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="tratamientos">Tratamientos</label>
					   <input class="form-control" type="text" id="tratamientos"/>
					   <span id="stratamientos"></span>
					</div>
                    
				</div>


				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="cedula_p">Cedula_p</label>
					   <input class="form-control" type="text" id="cedula_p"  />
					   <span id="scedula_p"></span>
					</div>
					<div class="col-md-12">
					   <label for="cedula_h">Cedula_h</label>
					   <input class="form-control" type="text" id="cedula_h"  />
					   <span id="scedula_h"></span>
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
						   id="proceso" style="background: #FF0000"></button>
					</div>
				</div>
			</div>

<!--nuevo-->
<div class="container pl-64">
	<div class="container">
		<div class="row mt-3 justify-content-between">
		    <div class="col-md-2 boton" onclick='pone(this,3)' >
				Registrar Emergencias
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
				<th>cod_emergencia</th>
				<th>Hora de Ingreso</th>
				<th>Fecha de Ingreso</th>
				<th>Motivo de Ingrso</th>				
				<th>Diagnostico</th>
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
</div> 


<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<div class="modal-header text-light bg-info" style="background: -webkit-linear-gradient(90deg, #ff0000,#ff8080);/* Chrome 10-25, Safari 5.1-6 */ background: linear-gradient(90deg, #ff0000,#ff8080);/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
        <h5 class="modal-title">Formulario de Emergencias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
		<div class="container">
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				<div class="row mb-3">
				
				<div class="col-md-4" style="display: none;">
					   <label for="cod_emergencia">cod_emergencia</label>
					   <input class="form-control" type="text" id="cod_emergencia" />
					   <span id="scod_emergencia"></span>
					</div>
					
					<div class="col-md-4">
					   <label for="horaingreso">Hora de Ingreso</label>
					   <input class="form-control" type="text" id="horaingreso" />
					   <span id="shoraingreso"></span>
					</div>

                    <div class="col-md-4">
					   <label for="fechaingreso">Fecha de Ingreso</label>
					   <input class="form-control" type="date" id="fechaingreso" />
					   <span id="fechaingreso"></span>
					</div>

					
				</div>
				
				<div class="col-md-4">
					   <label for="motingreso">Motivo de Ingrso</label>
					   <input class="form-control" type="text" id="motingreso" />
					   <span id="smotingreso"></span>
					</div>

				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="diagnostico_e">Diagnostico</label>
					   <input class="form-control" type="text" id="diagnostico_e"  />
					   <span id="sdiagnostico_e"></span>
					</div>
                    
				</div>
				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="tratamientos">Tratamientos</label>
					   <input class="form-control" type="text" id="tratamientos"/>
					   <span id="stratamientos"></span>
					</div>
                    
				</div>


				<div class="row mb-3">

					<div class="col-md-12">
					   <label for="cedula_p">Cedula_p</label>
					   <input class="form-control" type="text" id="cedula_p"  />
					   <span id="scedula_p"></span>
					</div>
					<div class="col-md-12">
					   <label for="cedula_h">Cedula_h</label>
					   <input class="form-control" type="text" id="cedula_h"  />
					   <span id="scedula_h"></span>
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
						   id="proceso" style="background: #FF0000"></button>
					</div>
				</div>
			</div>	
			</form>
		</div> 
		
    </div>
	
  </div>
</div>

<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/emergencias.js"></script> 

</body>
</html>