<nav class="navbar navbar-expand-lg navbar-dark position-fixed top-0 w-100">
    <div class="container-fluid">
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Notificaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificacionesDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="img/bell.svg" alt="" style="width: 25px;">
                        <span class="badge bg-danger d-none" id="contadorNotificaciones">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="listaNotificaciones">
                        <li><h6 class="dropdown-header">Notificaciones recientes</h6></li>
                        <li><div class="dropdown-item text-center py-3">Cargando...</div></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="js/notificaciones.js"></script>
