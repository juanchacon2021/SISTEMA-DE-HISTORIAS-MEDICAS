<?php
// comunes/notificaciones.php
use Shm\Shm\modelo\principal;
?>
<body>
    <?php
    if (!isset($datosUsuario)) {
        // Eduin si no pasa la informacion hice esto para obtenerla 
        require_once(__DIR__ . '/../src/modelo/principal.php');
        $datosUsuario = ['nombre' => 'Usuario no encontrado', 'foto_perfil' => 'img/user.png', 'cedula_personal' => ''];
        if (isset($_SESSION['usuario'])) {
            $datosUsuario = principal::obtenerDatosUsuario($_SESSION['usuario']);
        }
    }
    ?>
    <!-- Panel de usuario y campana de notificaciones -->
    <div style="position:fixed;
                top:20px;
                right:30px;
                z-index:1005;
                display:flex;
                align-items:center;
                gap:35px;">
        <!-- Campana -->
        <div id="notificacionIcono" style="
            cursor:pointer;
            position:relative;
            width:58px;
            height:58px;
            background:#e8e8e8;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            transition: background 0.2s;
        ">
            <img src="img/bell.svg" alt="Notificaciones" id="campanaImg" style="width:28px;transition:filter 0.2s;">
            <span id="notificacionBadge" style="display:none;
                position:absolute;
                top:2px;
                right:2px;
                background:red;
                color:white;
                border-radius:50%;
                padding:2px 7px;
                font-size:12px;">0</span>
        </div>
        <!-- Panel usuario -->
        <div class="user-panel" id="userPanel" style="position:relative;
            display:flex;   
            align-items:center; 
            min-width:180px;">

            <img src="<?php echo htmlspecialchars($datosUsuario['foto_perfil']); ?>" class="user-img" alt="Foto de perfil" style="width:44px;
            height:44px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid rgb(220,38,38);
            margin-right:12px;
            background:#f8f9fa;">
            
            <span class="user-name" style="font-weight:600;
            color:rgb(220,38,38);
            font-size:1.1rem;
            margin-right:10px;
            white-space:nowrap;
            cursor:pointer;">Hola, <?php echo htmlspecialchars($datosUsuario['nombre']); ?></span>
            
            <button class="dropdown-toggle" id="userDropdownBtn" title="Opciones" style="background:none;
            border:none;
            color:rgb(220,38,38);
            font-size:1.3rem;
            cursor:pointer;
            outline:none;
            margin-left:2px;
            padding:0rem 0.8rem;"></button>
            
            <div class="user-dropdown-menu" id="userDropdownMenu" style="min-width:300px; 
            right:0; 
            left:auto; 
            border-radius:1rem; 
            border:1px solid #eee; 
            box-shadow:0 2px 12px rgba(220,38,38,0.10); 
            background:#fff; 
            padding:1rem 1.2rem; 
            position:absolute; 
            top:60px;
            display:none;">
                <div class="user-cedula" style="font-size:1rem;color:#444; margin-bottom:0.7rem; font-weight:500;">Cédula: <?php echo htmlspecialchars($datosUsuario['cedula_personal']); ?></div>
                <form action="?pagina=salida" method="post" style="margin:0;">
                    <button type="submit" class="logout-btn" style="background:rgb(220,38,38); 
                    color:#fff; 
                    border:none; 
                    border-radius:0.5rem; 
                    padding:0.5rem 1.2rem; 
                    font-weight:600;
                    width:100%; 
                    transition:background 0.2s; 
                    margin-top:0.5rem;">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Panel de notificaciones -->
    <div id="panelNotificaciones" style="display:none;
        position:fixed;
        top:80px;
        right:30px;
        width:400px;
        max-height:500px;
        overflow-y:auto;
        background:white;
        border:1px solid #ccc;
        border-radius:8px;
        box-shadow:0 2px 8px rgba(0,0,0,0.2);
        z-index:10000;
        padding:20px;">
        <div style="padding:10px;font-weight:bold;border-bottom:1px solid #eee;">Notificaciones</div>
        <ul id="listaNotificaciones" style="list-style:none;padding:0;margin:0;"></ul>
    </div>

    <!-- Toast flotante -->
    <div id="toastNotificacion" style="
        display:none;
        position:fixed;
        bottom:30px;
        right:30px;
        background:#323232;
        color:white;
        padding:12px 24px;
        border-radius:6px;
        box-shadow:0 2px 8px rgba(0,0,0,0.2);
        z-index:10001;
        font-size:16px;
    "></div>

    <script src="js/notificaciones.js"></script>

    <script>
    document.getElementById('notificacionIcono').onclick = function(e) {
        this.classList.toggle('campana-activa');
        // Mostrar/ocultar panel de notificaciones si lo tienes
        const panel = document.getElementById('panelNotificaciones');
        if (panel) {
            panel.style.display = (panel.style.display === 'block') ? 'none' : 'block';
        }
    };
    // Opcional: si quieres que al hacer click fuera se quite el efecto activo
    document.addEventListener('click', function(e) {
        const icono = document.getElementById('notificacionIcono');
        const panel = document.getElementById('panelNotificaciones');
        if (!icono.contains(e.target) && (!panel || !panel.contains(e.target))) {
            icono.classList.remove('campana-activa');
            if (panel) panel.style.display = 'none';
        }
    });

    // Función para alternar el menú de usuario
    function toggleUserDropdown(e) {
        e.stopPropagation();
        document.getElementById('userDropdownMenu').classList.toggle('show');
    }

    // Asigna el evento tanto al nombre como al botón flecha
    document.getElementById('userDropdownBtn').onclick = toggleUserDropdown;
    document.querySelector('.user-name').onclick = toggleUserDropdown;

    // Opcional: también puedes agregarlo a la imagen si lo deseas
    // document.querySelector('.user-img').onclick = toggleUserDropdown;

    // Cerrar el menú al hacer click fuera
    document.addEventListener('click', function(e) {
        var menu = document.getElementById('userDropdownMenu');
        if(menu.classList.contains('show') && !e.target.closest('#userPanel')) {
            menu.classList.remove('show');
        }
    });
    </script>

    <style>
        .user-dropdown-menu.show { display: block !important; animation: fadeIn 0.2s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px);} to { opacity: 1; transform: translateY(0);} }
        .notificacion-animada img {
            transition: transform 0.3s cubic-bezier(.4,2,.6,1);
        }
        .notificacion-animada:hover img {
            transform: rotate(-20deg) scale(1.1);
        }
        .notificacion-animada:active img {
            animation: campana-rebote 0.3s;
        }
        @keyframes campana-rebote {
            0%   { transform: scale(1) rotate(0deg);}
            30%  { transform: scale(1.2) rotate(10deg);}
            60%  { transform: scale(0.95) rotate(-10deg);}
            100% { transform: scale(1) rotate(0deg);}
        }
        #notificacionIcono.campana-activa {
            background: #a11212 !important;
            border-color: #a11212 !important;
        }
        #notificacionIcono.campana-activa img {
            filter: brightness(0) invert(1);
        }
        #notificacionIcono:hover,
        #notificacionIcono.campana-activa {
            background: #a11212 !important;
        }
        #notificacionIcono:hover img,
        #notificacionIcono.campana-activa img {
            filter: brightness(0) invert(1);
        }
    </style>
</body>