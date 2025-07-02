<html> 
<?php 
	require_once("comunes/encabezado.php"); 
    require_once("modelo/datos.php"); 
    require_once("comunes/sidebar.php");
    require_once("comunes/notificaciones.php");

    $nombre = "Usuario no encontrado"; 
    $foto_perfil = "img/user.png";
    $cedula = "";
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

    if (isset($usuario)) {
        try {
            $conexion = (new datos())->conecta2(); 
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conexion->prepare("SELECT nombre, foto_perfil, cedula_personal FROM usuario WHERE cedula_personal = :cedula");
            $stmt->bindParam(':cedula', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                $nombre = $resultado['nombre'];
                $cedula = $resultado['cedula_personal'];
                if (!empty($resultado['foto_perfil'])) {
                    $foto_perfil = "img/perfiles/" . $resultado['foto_perfil'];
                }
            }
        } catch (Exception $e) {
            $nombre = "Error al obtener el nombre";
        }
    }
?>
<head>
<title>Panel Usuario</title>

    <style>
        body {
            position: relative;
            z-index: 0;
        }


        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('img/fondo.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; 
            opacity: 0.1; 
            z-index: -1; 
        }
    </style>
</head>
<body class="bg-stone-100">

    <article class="inicio bg-white px-4" style="padding: 70px 0px 20px 0px">
        
    </article>

    <div class="fondo">
        <img src="img/logo.png" alt="">
    </div>

    

    <!-- <button id="helpButton">Ayuda</button> -->

    <!-- Contenedor del calendario flotante en la parte derecha -->
    <div class="calendario"
         style="
            position: fixed;
            top: 120px;
            right: 40px;
            z-index: 1002;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 8px #eee;
            padding: 1.5rem 2rem;
            min-width: 340px;
            margin-bottom: 0;">
        <div style="font-weight: bold; color: rgb(220,38,38); font-size: 1.1rem;">Calendario</div>
        <div id="calendar"></div>
    </div>
    <!-- Contenedor de tarjetas -->
    <div style="display: flex; justify-content: flex-end; margin-top: 40px;">
        <div class="panel-container" style="gap: 25px;">
            <!-- Tarjeta Pacientes -->
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px #eee; padding: 2rem 2.5rem; text-align: center; min-width: 220px;">
                <div style="font-size: 2.2rem; color: rgb(220,38,38); font-weight: bold;" id="pacientesCount"></div>
                <div style="color: #444;">Pacientes registrados</div>
            </div>
            <!-- Tarjeta Personal -->
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px #eee; padding: 2rem 2.5rem; text-align: center; min-width: 220px;">
                <div style="font-size: 2.2rem; color: rgb(220,38,38); font-weight: bold;" id="personalCount"></div>
                <div style="color: #444;">Personal activo</div>
            </div>
        </div>
    </div>

    <!-- Frase motivacional -->
    <div id="frase-motivacional" style="
        position: fixed;
        left: 350px;
        bottom: 30px;
        background: rgba(255,255,255,0.95);
        color: #a11212;
        font-size: 1.2rem;
        font-family: 'Sora', sans-serif;
        border-radius: 1rem;
        box-shadow: 0 2px 8px #eee;
        padding: 1.2rem 2rem;
        min-width: 260px;
        max-width: 350px;
        z-index: 1003;
        text-align: left;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-style: italic;
    ">
    </div>

    <script>
    // Frases motivacionales modernas
    const frases = [
        "¡Hoy es un gran día para ayudar y sanar!",
        "Cada paciente es una oportunidad para marcar la diferencia.",
        "La salud es el regalo más grande, cuídala.",
        "Tu vocación transforma vidas. ¡Sigue adelante!",
        "La empatía y la dedicación salvan más que cualquier medicina.",
        "Pequeños actos, grandes cambios.",
        "El esfuerzo de hoy es el bienestar de mañana.",
        "¡Gracias por ser parte de este equipo humano!",
        "La sonrisa es el mejor remedio.",
        "Juntos construimos salud y esperanza."
    ];
    // Selecciona una frase aleatoria
    document.getElementById('frase-motivacional').textContent =
        frases[Math.floor(Math.random() * frases.length)];
    </script>
    <script src="js/principal.js"></script>
</body>
</html>