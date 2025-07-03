<?php
 require_once("comunes/encabezado.php");
 require_once("comunes/sidebar.php");
 require_once("comunes/notificaciones.php");
?>


<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
    Historias Médicas
</div>
<div class="container espacio">
    <div class="container">
        <div class="row mt-3 botones">
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
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="resultadoHistorias"></tbody>
        </table>
       </div>
    </div>
</div>

<!-- Modal para seleccionar secciones del PDF -->
<div class="modal fade" id="modalSeleccionPDF" tabindex="-1" aria-labelledby="modalSeleccionPDFLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 1rem;">
        <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <h5 class="modal-title" id="modalSeleccionPDFLabel" style="font-size: 1.2rem; font-weight: bold; margin: 20px;">Seleccionar secciones para el PDF</h5>
      <div class="modal-body px-4 py-4">
        <form id="formSeleccionPDF" autocomplete="off">
          <input type="hidden" id="pdf_cedula_paciente" name="cedula_paciente">
          <div class="mb-4">
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="datos_personales" id="checkDatosPersonales" checked>
              <label class="form-check-label texto-inicio font-medium" for="checkDatosPersonales" style="font-size: 1.1rem;">Datos personales</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="emergencias" id="checkEmergencias">
              <label class="form-check-label texto-inicio font-medium" for="checkEmergencias" style="font-size: 1.1rem;">Emergencias</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="consultas" id="checkConsultas">
              <label class="form-check-label texto-inicio font-medium" for="checkConsultas" style="font-size: 1.1rem;">Consultas</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="examenes" id="checkExamenes">
              <label class="form-check-label texto-inicio font-medium" for="checkExamenes" style="font-size: 1.1rem;">Exámenes</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="antecedentes" id="checkAntecedentes">
              <label class="form-check-label texto-inicio font-medium" for="checkAntecedentes" style="font-size: 1.1rem;">Antecedentes familiares</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-between bg-light" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn botonverde" id="btnGenerarPDF">Generar PDF</button>
      </div>
    </div>
  </div>
</div>

<style>
  .modal-backdrop.show {
    z-index: 1079 !important;
  }
  #modalSeleccionPDF {
    z-index: 1080 !important;
  }
</style>
<script src="js/historias.js"></script>
<script>
// Acción del botón en la tabla
function abrirModalSeleccionPDF(cedula) {
    $('#pdf_cedula_paciente').val(cedula);
    $('#modalSeleccionPDF').modal('show');
}
$('#btnGenerarPDF').on('click', function() {
    const cedula = $('#pdf_cedula_paciente').val();
    const secciones = [];
    $('#formSeleccionPDF input[type=checkbox]:checked').each(function() {
        secciones.push($(this).val());
    });
    if (secciones.length === 0) {
        alert('Seleccione al menos una sección');
        return;
    }
    // Redirige al FPDF con parámetros GET
    window.open('vista/fpdf/historia_medica.php?cedula_paciente=' + cedula + '&secciones=' + secciones.join(','), '_blank');
    $('#modalSeleccionPDF').modal('hide');
});
</script>
</body>
</html>