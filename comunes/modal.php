<div class="container">	  
   <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content ">
         <div class="modal-header">
            <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="modalcerrar">&times;</button>-->
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalcerrar"></button>
               <div id="cabezerademodal">
            </div>
         </div>
         <div class="modal-body">
               <span class=" d-flex justify-content-center">
                  <img src="img/logo.png" alt="" style="width: 120px;">
               </span>
               <div class=" d-flex justify-content-center font-semibold py-4" id="contenidodemodal">
            </div>    
         </div>
      </div>
      </div>
   </div>
</div>
<script>
function mostrarModalCorrecto(mensaje) {
    document.getElementById('icono-correcto').style.display = 'flex';
    document.getElementById('icono-error').style.display = 'none';
    document.getElementById('contenidodemodal').innerText = mensaje;
    var modal = new bootstrap.Modal(document.getElementById('mostrarmodal'));
    modal.show();
}

function mostrarModalError(mensaje) {
    document.getElementById('icono-correcto').style.display = 'none';
    document.getElementById('icono-error').style.display = 'flex';
    document.getElementById('contenidodemodal').innerText = mensaje;
    var modal = new bootstrap.Modal(document.getElementById('mostrarmodal'));
    modal.show();
}
</script>


