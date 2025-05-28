<div class="notificaciones-container position-fixed top-0 end-0 m-3">
    <button class="btn-notificacion" id="btnMostrarNotificaciones">
        <i class="fas fa-bell"></i>
        <span id="notificacionCounter" class="badge bg-danger" style="display:none"></span>
    </button>
    <div id="notificacionesDropdown" class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <div class="dropdown-header">Notificaciones recientes</div>
        <div id="listaNotificaciones"></div>
        <div class="dropdown-footer text-center">
            <a href="?pagina=bitacora">Ver todas</a>
        </div>
    </div>
</div>
<script src="js/notificaciones.js"></script>