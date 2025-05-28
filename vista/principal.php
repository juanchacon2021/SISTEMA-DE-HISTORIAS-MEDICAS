<html> 
<?php 
	require_once("comunes/encabezado.php"); 
    require_once("modelo/datos.php"); 
    require_once("comunes/notificaciones.php");
    require_once("comunes/sidebar.php");

    $nombre = "Usuario no encontrado"; 

    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

    if (isset($usuario)) {
        try {
            $conexion = (new datos())->conecta2(); // Conexión a la base de datos
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para obtener el nombre y apellido del personal
            $stmt = $conexion->prepare("SELECT nombre FROM usuario WHERE id = :id");
            $stmt->bindParam(':id', $usuario, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                $nombre = $resultado['nombre'];
            }
        } catch (Exception $e) {
            $nombre = "Error al obtener el nombre";
        }
    }
?>
<head>
<title>Botón de Ayuda</title> 
<style> 
    #helpButton { 
    
    position: fixed; 
    top: 10px; right: 
    10px; padding: 
    10px 20px; 
    color: rgb(220 38 38); } 

    #helpButton:hover { 
        color: rgb(153, 27, 27);
        text-decoration: underline;
        } 
    </style>

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
            background-image: url('img/fondo.jpg'); /* Cambia esto por la ruta a tu imagen */
            background-size: cover; /* Asegura que la imagen cubra toda la pantalla */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            opacity: 0.1; /* Ajusta la opacidad aquí */
            z-index: -1; /* Asegúrate de que la imagen esté detrás del contenido */
        }
    </style>
</head>
<body class="bg-stone-100">

    <article class="inicio bg-white py-8 px-4">
        <h1 class="texto-inicio">Hola, Bienvenido <?php echo htmlspecialchars($nombre); ?></h1>
    </article>

    <div class="fondo">
        <img src="img/logo.png" alt="">
    </div>
</body>
</html>