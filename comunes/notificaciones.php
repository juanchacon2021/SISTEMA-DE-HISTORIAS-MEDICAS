<div class="notificaciones-container position-fixed top-0 end-0 m-3" style="z-index: 9999;">
    <button class="btn-notificacion" id="btnMostrarNotificaciones">
        <img src="img/bell.svg" alt="Campana" style="width:24px;height:24px;">
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