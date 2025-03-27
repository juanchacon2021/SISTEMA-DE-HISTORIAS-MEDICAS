<html> 
    <?php 
        require_once("comunes/encabezado.php"); 
        require_once("comunes/sidebar.php");	
    ?>
    <body >
    <?php
		if($nivel=='Doctor'){
	?>

    <?php if (isset($mensaje)): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
    Pacientes
    </div>

    <div class="container espacio">
        <div class="ml-8 mb-8">
            <a href="?pagina=pacientes" class="boton px-4">Volver</a>
        </div>

        <form method="POST" id="f">
        <input type="hidden" name="accion" value="<?php echo isset($datos) ? 'modificar' : 'guardar'; ?>">
            <div class="container">	
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="cedula_historia" class="texto-inicio font-medium">Cedula</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white"
                        type="text"
                        id="cedula_historia"
                        name="cedula_historia"
                        value="<?php echo $datos['cedula_historia'] ?? ''; ?>" required/>
                        <span id="scedula_historia"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="nombre" class="texto-inicio font-medium">Nombres</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre"
                                name="nombre" value="<?php echo $datos['nombre'] ?? ''; ?>" required/>
                        <span id="snombre"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="texto-inicio font-medium">Apellidos</label>
                            <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="apellido"
                                name="apellido" value="<?php echo $datos['apellido'] ?? ''; ?>" required/>
                            <span id="sapellido"></span>
                        </div>
                    <div class="col-md-3">
                        <label for="fecha_nac" class="texto-inicio font-medium">Fecha Nacimiento</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fecha_nac" name="fecha_nac"
                        value="<?php echo $datos['fecha_nac'] ?? ''; ?>" required/>
                        <span id="sfecha_nac"></span>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="edad" class="texto-inicio font-medium">Edad</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="number" id="edad"
                                name="edad" value="<?php echo $datos['edad'] ?? ''; ?>" required readonly/>
                        <span id="sedad"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="estadocivil" class="texto-inicio font-medium">Estado Civil</label>
                        <select class="form-control bg-gray-200 rounded-lg border-white" id="estadocivil" 
                                name = "estadocivil" value="<?php echo $datos['estadocivil'] ?? ''; ?>" required>
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
                                name="direccion" value="<?php echo $datos['direccion'] ?? ''; ?>" required/>
                        <span id="sdireccion"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="telefono" class="texto-inicio font-medium">Telefono</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="telefono"
                                name="telefono" value="<?php echo $datos['telefono'] ?? ''; ?>" required/>
                        <span id="stelefono"></span>
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>
                <div class="row mb-3 mx-1 gap-y-2">
                    <div class="col-md-3">
                        <label for="ocupacion" class="texto-inicio font-medium">Ocupacion</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ocupacion"
                                name="ocupacion" value="<?php echo $datos['ocupacion'] ?? ''; ?>" required/>
                        <span id="socupacion"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="hda" class="texto-inicio font-medium">H.D.A</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="hda"
                                    name="hda" value="<?php echo $datos['hda'] ?? ''; ?>" required/>
                            <span id="shda"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="habtoxico" class="texto-inicio font-medium">Habito Toxico</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="habtoxico"
                                name="habtoxico" value="<?php echo $datos['habtoxico'] ?? ''; ?>" required/>
                        <span id="shabtoxico"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="alergias" class="texto-inicio font-medium">Alergias</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias"
                                name="alergias" value="<?php echo $datos['alergias'] ?? ''; ?>" required/>
                        <span id="salergias"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="quirurgico" class="texto-inicio font-medium">Quirurgico</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="quirurgico"
                                name="quirurgico" value="<?php echo $datos['quirurgico'] ?? ''; ?>" required/>
                        <span id="squirurgico"></span>
                    </div>

                    <div class="col-md-3">
                        <label for="psicosocial" class="texto-inicio font-medium">Psicosocial</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="psicosocial"
                                name="psicosocial" value="<?php echo $datos['psicosocial'] ?? ''; ?>" required/>
                        <span id="spsicosocial"></span>
                    </div>
                        
                    <div class="col-md-6">
                        <label for="transsanguineo" class="texto-inicio font-medium">Transfuciones Sanguineas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="transsanguineo"
                                name="transsanguineo" value="<?php echo $datos['transsanguineo'] ?? ''; ?>" required/>
                        <span id="stranssanguineo"></span>
                    </div>
                        
                    <div class="col-md-6">
                        <label for="alergias_med" class="texto-inicio font-medium">Alergias Medicas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias_med"
                                name="alergias_med" value="<?php echo $datos['alergias_med'] ?? ''; ?>" required/>
                        <span id="salergias_med"></span>
                    </div>

                    
                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>
                <div class="row mb-3 mx-1 gap-y-2">

                    <div class="col-md-6">
                        <label for="antc_madre" class="texto-inicio font-medium">Antecedentes Maternos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_madre" name="antc_madre"
                        ><?php echo $datos['antc_madre'] ?? ''; ?></textarea>
                        <span id="santc_madre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antc_padre" class="texto-inicio font-medium">Antecedentes Paternos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_padre" name="antc_padre"
                        ><?php echo $datos['antc_padre'] ?? ''; ?></textarea>
                        <span id="santc_padre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antc_hermano" class="texto-inicio font-medium">Antecedentes Hermanos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_hermano" name="antc_hermano"
                        ><?php echo $datos['antc_hermano'] ?? ''; ?></textarea>
                        <span id="santc_hermano"></span>
                    </div>
                </div>

                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row mt-3 justify-content-center">
                    <button type="submit" class="boton px-4" id="proceso" name="accion" value="<?php echo !empty($datos) ? 'modificar' : 'incluir'; ?>">
                        <?php echo !empty($datos) ? 'Modificar' : 'Guardar'; ?>
                    </button>
                </div>
            </div>	
        </form>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/historia.js"></script>
    <?php
}
    ?>
    </body>
</html>