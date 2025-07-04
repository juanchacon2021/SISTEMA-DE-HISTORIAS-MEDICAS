<html> 
<?php 
    require_once("comunes/encabezado.php"); 
    require_once("comunes/sidebar.php");
    require_once("comunes/notificaciones.php");
?>

<body>
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Pacientes', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
    Pacientes
</div>
<div class="container espacio">
    <div class="container">
        <div class="row mt-3 botones">
            <div style="color: white;" class="btn botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
                Registrar Paciente
            </div>
            <div class="btn botonrojo">    
                <a href="?pagina=principal">Volver</a>
            </div>
        </div>
    </div>
    <div class="container">
       <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
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
                </tr>
            </thead>
            <tbody id="resultadoconsulta">
                
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- MODAL PRINCIPAL DE PACIENTES -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="width: 60rem;">
      <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="container">
        <form method="post" id="f" autocomplete="off" style="margin: 0em 2em 2em 2em;">
          <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">

          <!-- Paso 1: Información General -->
          <div class="container step" id="step-1">
            <div class="row mb-6">
                <h1 class="text-2xl font-bold mb-2">Información General</h1>

                <div>
                    <div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                    </div>
                </div>

              <div class="col-md-6">
                <label for="cedula_paciente" class="texto-inicio font-medium mb-2">Cedula *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Cedula" type="text" id="cedula_paciente" name="cedula_paciente" />
                <span id="scedula_paciente"></span>
              </div>
              <div class="col-md-6">
                <label for="nombre" class="texto-inicio font-medium mb-2">Nombres *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Nombres" type="text" id="nombre" name="nombre" />
                <span id="snombre"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="apellido" class="texto-inicio font-medium mb-2">Apellidos *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Apellidos" type="text" id="apellido" name="apellido" />
                <span id="sapellido"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="fecha_nac" class="texto-inicio font-medium mb-2">Fecha de Nacimiento *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Fecha de Nacimiento" type="date" id="fecha_nac" name="fecha_nac" />
                <span id="sfecha_nac"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="edad" class="texto-inicio font-medium mb-2">Edad *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Edad" type="number" id="edad" name="edad" />
                <span id="sedad"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="estadocivil" class="texto-inicio font-medium mb-2">Estado Civil *</label>
                <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Estado Civil" id="estadocivil" name="estadocivil">
                  <option value="" selected>-- Seleccione --</option>
                  <option value="SOLTERO">Soltero</option>
                  <option value="CASADO">Casado</option>
                  <option value="DIVORCIADO">Divorciado</option>
                  <option value="VIUDO">Viudo</option>
                </select>
              </div>
              <div class="col-md-6 mt-4">
                <label for="telefono" class="texto-inicio font-medium mb-2">Telefono *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Telefono" type="text" id="telefono" name="telefono" />
                <span id="stelefono"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="direccion" class="texto-inicio font-medium mb-2">Direccion *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Direccion" type="text" id="direccion" name="direccion" />
                <span id="sdireccion"></span>
              </div>
            </div>
          </div>

          <!-- Paso 2: Historia Médica -->
          <div class="container step d-none" id="step-2">
            <div class="row mb-3">

                <h1 class="text-2xl font-bold mb-2">Historia Médica</h1>

                <div>
                    <div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                    </div>
                </div>

                <div class="col-md-6 mt-4">
                    <label for="ocupacion" class="texto-inicio font-medium mb-2">Ocupacion *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Ocupacion" type="text" id="ocupacion" name="ocupacion" />
                    <span id="socupacion"></span>
                </div>

                <div class="col-md-6 mb-2 mt-4">
                    <label for="alergias" class="texto-inicio font-medium mb-2">Alergias *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Alergias" type="text" id="alergias"
                        name="alergias"/>
                    <span id="salergias"></span>
                </div>
                <div class="col-md-6 mt-4">
                    <label for="alergias_med" class="texto-inicio font-medium mb-2">Alergias Médicas *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Alergias Medicas" type="text" id="alergias_med"
                            name="alergias_med"/>
                    <span id="salergias_med"></span>
                </div>
                <div class="col-md-6 mt-4">
                    <label for="habtoxico" class="texto-inicio font-medium mb-2">Habito Toxico *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Habito Toxico" type="text" id="habtoxico"
                            name="habtoxico"/>
                    <span id="shabtoxico"></span>
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-md-4 mt-4">
                    <label for="quirurgico" class="texto-inicio font-medium mb-2">Quirurgico *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Quirurgico" type="text" id="quirurgico"
                            name="quirurgico"/>
                    <span id="squirurgico"></span>
                </div>
                
                <div class="col-md-4 mt-4">
                    <label for="transsanguineo" class="texto-inicio font-medium mb-2">Trans Sanguineos *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Trans Sanguineos" type="text" id="transsanguineo"
                            name="transsanguineo"/>
                    <span id="stranssanguineo"></span>
                </div>

                <div class="col-md-4 mt-4">
                    <label for="hda" class="texto-inicio font-medium mb-2">HDA *</label>
                    <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="HDA" type="text" id="hda"
                            name="hda"/>
                    <span id="shda"></span>
                </div>
              </div>
            </div>
        

          <!-- Paso 3: Antecedentes Familiares -->
          <div class="container step d-none" id="step-3">
            <div class="row mb-3">
                
                <h1 class="text-2xl font-bold mb-2">Antecedentes Familiares</h1>

                <div>
                    <div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                    </div>
                </div>

                
                <div class="col-md-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="texto-inicio font-medium mb-2">Familiares</h3>
                        <button type="button" class="btn btn-secondary" id="btnAgregarFamiliar">
                            + Agregar Familiar
                        </button>
                    </div>
                    
                    <div id="listaFamiliares" class="space-y-3">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-gray-500">No se han agregado familiares</p>
                        </div>
                    </div>
                </div>
               
                <div class="col-md-9 mt-4">
                    <label for="psicosocial" class="texto-inicio font-medium mb-2">Psicosocial *</label>
                    <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Psicosocial" name="psicosocial" id="psicosocial"></textarea>
                    <span id="spsicosocial"></span>
                </div>
            </div>
          </div>

          <!-- Paso 4: Patologías Crónicas -->
<div class="container step d-none" id="step-4">
    <div class="row mb-3">
        <h1 class="text-2xl font-bold mb-2">Patologías Crónicas</h1>

        <div>
            <div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="texto-inicio font-medium mb-2">Patologías del Paciente</h3>
                <button type="button" class="btn btn-info" id="btnRegistrarPatologia">
                    Registrar Nueva Patología
                </button>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="select_patologia" class="form-label">Patología</label>
                    <select class="form-control" id="select_patologia">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tratamiento_patologia" class="form-label">Tratamiento</label>
                    <input type="text" class="form-control" id="tratamiento_patologia" />
                </div>
                <div class="col-md-3">
                    <label for="administracion_patologia" class="form-label">Administración</label>
                    <input type="text" class="form-control" id="administracion_patologia" />
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" id="btnAgregarPatologiaPaciente">Agregar Patología</button>
            <div id="listaPatologiasPaciente" class="mt-3">
                <div class="alert alert-info">No se han agregado patologías</div>
            </div>
        </div>
    </div>
</div>
<!-- Botones de Navegación -->
          <div class="row mt-5 mb-5 justify-content-between">
            <div class="col-md-3 abajo">
              <button type="button" class="" id="prev-btn" style="display: none;">&lt; Anterior</button>
            </div>
            <div class="col-md-3 text-end abajo"">
              <button type="button" id="next-btn">Siguiente &gt;</button>
              <button type="button" class="btn botonverde" id="proceso" style="display: none;">INCLUIR</button>
            </div>
          </div>


        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA ANTECEDENTES FAMILIARES -->
<div class="modal fade" id="modalFamiliar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFamiliarTitle">Agregar Familiar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formFamiliar">
          <input type="hidden" id="id_familiar" name="id_familiar">
          <input type="hidden" id="familiar_cedula_paciente" name="cedula_paciente">
          
          <div class="mb-3">
            <label for="nom_familiar" class="form-label">Nombre *</label>
            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3" id="nom_familiar" name="nom_familiar" required>
          </div>
          
          <div class="mb-3">
            <label for="ape_familiar" class="form-label">Apellido *</label>
            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3" id="ape_familiar" name="ape_familiar" required>
          </div>
          
          <div class="mb-3">
            <label for="relacion_familiar" class="form-label">Relación Familiar *</label>
            <select class="form-control bg-gray-200 rounded-lg border-white p-3" id="relacion_familiar" name="relacion_familiar" required>
              <option value="">Seleccione...</option>
              <option value="Padre">Padre</option>
              <option value="Madre">Madre</option>
              <option value="Hermano/a">Hermano/a</option>
              <option value="Abuelo/a">Abuelo/a</option>
              <option value="Tío/a">Tío/a</option>
              <option value="Primo/a">Primo/a</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" id="observaciones" name="observaciones" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarFamiliar">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA REGISTRAR NUEVA PATOLOGÍA -->
<div class="modal fade" id="modalRegistrarPatologia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar Nueva Patología</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formRegistrarPatologia">
          <div class="mb-3">
            <label for="nombre_nueva_patologia" class="form-label">Nombre de la Patología *</label>
            <input type="text" class="form-control" id="nombre_nueva_patologia" name="nombre_patologia" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarNuevaPatologia">Registrar</button>
      </div>
    </div>
  </div>
</div>



<!-- Botones de Navegación (SOLO UNO, dentro del form y antes de </form>) -->
<div class="row mt-5 mb-5 justify-content-between">
    <div class="col-md-3 abajo">
        <button type="button" class="" id="prev-btn" style="display: none;">&lt; Anterior</button>
    </div>
    <div class="col-md-3 text-end abajo">
        <button type="button" id="next-btn">Siguiente &gt;</button>
        <button type="button" class="btn botonverde" id="proceso" style="display: none;">INCLUIR</button>
    </div>
</div>
</form>
</div>
<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/pacientes.js"></script>

</body>
</html>