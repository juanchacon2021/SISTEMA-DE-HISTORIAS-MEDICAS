<html> 
    <?php 
        require_once("comunes/encabezado.php"); 
        require_once("comunes/sidebar.php");
        
        $cedula_historia = $_GET['cedula_historia'];

// Crear una instancia del modelo y obtener los datos del paciente
        $model = new PacienteModel();
        $paciente = $model->getPacienteByCedula(cedula_historia:$cedula_historia);
?>
    <body >


    <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
    Pacientes
    </div>

    <div class="container espacio">
        <div class="ml-8 mb-8">
            <a href="?pagina=pacientes" class="boton px-4">Volver</a>
        </div>

        <form method="post" id="f">
            <div class="container">	
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="cedula_historia" class="texto-inicio font-medium">Cedula</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia"
                        name="cedula_historia" value="<?php echo $paciente['cedula_historia']; ?>" required/>
                        <span id="scedula_historia"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="nombre" class="texto-inicio font-medium">Nombres</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nombre"
                                name="nombre" value="<?php echo $paciente['nombre']; ?>"/>
                        <span id="snombre"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="texto-inicio font-medium">Apellidos</label>
                            <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="apellido"
                                name="apellido" value="<?php echo $paciente['apellido']; ?>" required/>
                            <span id="sapellido"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_nac" class="texto-inicio font-medium">Fecha de Nacimiento</label>
                            <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fecha_nac"
                                    name="fecha_nac" value="<?php echo $paciente['fecha_nac']; ?>" required/>
                            <span id="sfecha_nac"></span>
                        </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="edad" class="texto-inicio font-medium">Edad</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="number" id="edad"
                                name="edad" value="<?php echo $paciente['edad']; ?>" required/>
                        <span id="sedad"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="estadocivil" class="texto-inicio font-medium">Estado Civil</label>
                        <select class="form-control bg-gray-200 rounded-lg border-white" id="estadocivil" 
                                name = "estadocivil" value="" required>
                            <option value="" selected><?php echo $paciente['estadocivil']; ?></option>
                            <option value="SOLTERO">Soltero</option>
                            <option value="CASADO">Casado</option>
                            <option value="DIVORCIADO">Divorciado</option>
                            <option value="VIUDO">Viudo</option>
                        </select>
                    </div>
                            
                    <div class="col-md-3">
                        <label for="direccion" class="texto-inicio font-medium">Direccion</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="direccion"
                                name="direccion" value="<?php echo $paciente['direccion']; ?>" required/>
                        <span id="sdireccion"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="telefono" class="texto-inicio font-medium">Telefono</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="telefono"
                                name="telefono" value="<?php echo $paciente['telefono']; ?>" required/>
                        <span id="stelefono"></span>
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="ocupacion" class="texto-inicio font-medium">Ocupacion</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ocupacion"
                                name="ocupacion" value="<?php echo $paciente['ocupacion']; ?>" required/>
                        <span id="socupacion"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="hda" class="texto-inicio font-medium">H.D.A</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="hda"
                                    name="hda" value="<?php echo $paciente['hda']; ?>" required/>
                            <span id="shda"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="habtoxico" class="texto-inicio font-medium">Habito Toxico</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="habtoxico"
                                name="habtoxico" value="<?php echo $paciente['habtoxico']; ?>" required/>
                        <span id="shabtoxico"></span>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="alergias" class="texto-inicio font-medium">Alergias</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias"
                                name="alergias" value="<?php echo $paciente['alergias']; ?>" required/>
                        <span id="salergias"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="quirurgico" class="texto-inicio font-medium">Quirurgico</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="quirurgico"
                                name="quirurgico" value="<?php echo $paciente['quirurgico']; ?>" required/>
                        <span id="squirurgico"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="transsanguineo" class="texto-inicio font-medium">Transfuciones Sanguineas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="transsanguineo"
                                name="transsanguineo" value="<?php echo $paciente['transsanguineo']; ?>" required/>
                        <span id="stranssanguineo"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="alergias_med" class="texto-inicio font-medium">Alergias Medicas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias_med"
                                name="alergias_med" value="<?php echo $paciente['alergias_med']; ?>" required/>
                        <span id="salergias_med"></span>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="psicosocial" class="texto-inicio font-medium">Psicosocial</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="psicosocial"
                                name="psicosocial" value="<?php echo $paciente['psicosocial']; ?>" required/>
                        <span id="spsicosocial"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="oidos" class="texto-inicio font-medium">Oidos</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="oidos"
                                name="oidos" value="<?php echo $paciente['oidos']; ?>" required/>
                        <span id="soidos"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="cabeza_craneo" class="texto-inicio font-medium">Cabeza Craneo</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cabeza_craneo"
                                name="cabeza_craneo" value="<?php echo $paciente['cabeza_craneo']; ?>" required/>
                        <span id="scabeza_craneo"></span>
                    </div>
                        
                    <div class="col-md-3">
                        <label for="ojos" class="texto-inicio font-medium">Ojos</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="ojos"
                                name="ojos" value="<?php echo $paciente['ojos']; ?>" required/>
                        <span id="sojos"></span>
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="nariz" class="texto-inicio font-medium">Nariz</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="nariz"
                                name="nariz" value="<?php echo $paciente['nariz']; ?>" required/>
                        <span id="snariz"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="tiroides" class="texto-inicio font-medium">Tiroides</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="tiroides"
                                name="tiroides" value="<?php echo $paciente['tiroides']; ?>" required/>
                        <span id="stiroides"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="boca_abierta" class="texto-inicio font-medium">Boca Abierta</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_abierta"
                                name="boca_abierta" value="<?php echo $paciente['boca_abierta']; ?>" required/>
                        <span id="sboca_abierta"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="boca_cerrada" class="texto-inicio font-medium">Boca Cerrada</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="boca_cerrada"
                                name="boca_cerrada" value="<?php echo $paciente['boca_cerrada']; ?>" required/>
                        <span id="sboca_cerrada"></span>
                    </div>
                         
                     <div class="col-md-3">
                        <label for="cardiovascular" class="texto-inicio font-medium">Cardiovascular</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cardiovascular"
                                name="cardiovascular" value="<?php echo $paciente['cardiovascular']; ?>" required/>
                        <span id="scardiovascular"></span>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="respiratorio" class="texto-inicio font-medium">Respiratorio</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="respiratorio"
                                name="respiratorio" value="<?php echo $paciente['respiratorio']; ?>" required/>
                        <span id="srespiratorio"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="abdomen" class="texto-inicio font-medium">Abdomen</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="abdomen"
                                name="abdomen" value="<?php echo $paciente['abdomen']; ?>" required/>
                        <span id="sabdomen"></span>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="extremidades" class="texto-inicio font-medium">Extremidades</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="extremidades"
                                name="extremidades" value="<?php echo $paciente['extremidades']; ?>" required/>
                        <span id="sextremidades"></span>
                    </div>
                     
                    <div class="col-md-3">
                        <label for="neurologicos" class="texto-inicio font-medium">Neurologico</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="neurologicos"
                                name="neurologicos" value="<?php echo $paciente['neurologicos']; ?>" required/>
                        <span id="sneurologicos"></span>
                    </div>  
                </div>
                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>
                <div class="row mb-3 mx-1">

                    <div class="col-md-6">
                        <label for="antec_madre" class="texto-inicio font-medium">Antecedentes Maternos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antec_madre" name="antec_madre"><?php echo $paciente['antec_madre']; ?></textarea>
                        <span id="santec_madre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antec_padre" class="texto-inicio font-medium">Antecedentes Paternos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antec_padre" name="antec_padre"><?php echo $paciente['antec_padre']; ?></textarea>
                        <span id="santec_padre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antec_hermano" class="texto-inicio font-medium">Antecedentes Hermanos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antec_hermano" name="antec_hermano"><?php echo $paciente['antec_hermano']; ?></textarea>
                        <span id="santec_hermano"></span>
                    </div>
                </div>

                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row mb-3 mx-1">

                    <div class="col-md-6">
                        <label for="general" class="texto-inicio font-medium">Examen Fisico General</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white"   id="general" name="general"><?php echo $paciente['general']; ?></textarea>
                        <span id="sgeneral"></span>
                    </div>

                </div>

                <div class="row mt-3 justify-content-center">
                    <div class="col-md-2">
                        <button type="button" class="btn botonverde" id="proceso">MODIFICAR</button>
                    </div>
                </div>
            </div>	
        </form>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script type="text/javascript" src="js/modificarhistoria.js"></script>

    </body>
</html>