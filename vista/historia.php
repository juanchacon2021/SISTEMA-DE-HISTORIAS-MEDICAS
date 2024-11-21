<html> 
    <?php 
        require_once("comunes/encabezado.php"); 
        require_once("comunes/sidebar.php");	
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
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fecha_nac" name="fecha_nac"/>
                        <span id="sfecha_nac"></span>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <div class="col-md-3">
                        <label for="edad" class="texto-inicio font-medium">Edad</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="number" id="edad"
                                name="edad" readonly/>
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
                <div class="row mb-3 mx-1 gap-y-2">
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
                    <div class="col-md-6">
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
                        <label for="psicosocial" class="texto-inicio font-medium">Psicosocial</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="psicosocial"
                                name="psicosocial"/>
                        <span id="spsicosocial"></span>
                    </div>
                        
                    <div class="col-md-6">
                        <label for="transsanguineo" class="texto-inicio font-medium">Transfuciones Sanguineas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="transsanguineo"
                                name="transsanguineo"/>
                        <span id="stranssanguineo"></span>
                    </div>
                        
                    <div class="col-md-6">
                        <label for="alergias_med" class="texto-inicio font-medium">Alergias Medicas</label>
                        <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="alergias_med"
                                name="alergias_med"/>
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
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_madre" name="antc_madre"></textarea>
                        <span id="santc_madre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antc_padre" class="texto-inicio font-medium">Antecedentes Paternos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_padre" name="antc_padre"></textarea>
                        <span id="santc_padre"></span>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="antc_hermano" class="texto-inicio font-medium">Antecedentes Hermanos</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white" id="antc_hermano" name="antc_hermano"></textarea>
                        <span id="santc_hermano"></span>
                    </div>
                </div>

                <div class="row py-4">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <!-- <div class="row mb-3 mx-1">

                    <div class="col-md-6">
                        <label for="general" class="texto-inicio font-medium">Examen Fisico General</label>
                        <textarea class="form-control bg-gray-200 rounded-lg border-white"   id="general" name="general"></textarea>
                        <span id="sgeneral"></span>
                    </div>

                </div> -->

                <div class="row mt-3 justify-content-center">
                    <div class="col-md-2">
                        <center><button type="button" class="btn botonverde" id="proceso" style="color: white;">INCLUIR</button></center>
                    </div>
                </div>
            </div>	
        </form>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script type="text/javascript" src="js/historia.js"></script>

    </body>
</html>