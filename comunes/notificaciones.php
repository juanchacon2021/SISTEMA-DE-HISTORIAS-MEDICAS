<body>
    <!-- Icono de notificación -->
    <div id="notificacionIcono" style="position:fixed;top:20px;right:30px;z-index:9999;cursor:pointer;">
        <img src="img/bell.svg" alt="Notificaciones" style="width:32px;">
        <span id="notificacionBadge" style="display:none;position:absolute;top:0;right:0;background:red;color:white;border-radius:50%;padding:2px 6px;font-size:12px;">0</span>
    </div>

    <!-- Panel de notificaciones -->
    <div id="panelNotificaciones" style="display:none;position:fixed;top:60px;right:30px;width:300px;max-height:400px;overflow-y:auto;background:white;border:1px solid #ccc;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.2);z-index:10000;">
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
    <?php if(isset($_SESSION['notificaciones'])): ?>
    <script>
        window.notificacionesPersistentes = <?php echo json_encode($_SESSION['notificaciones']); ?>;
    </script>
    <?php endif; ?>
</body>