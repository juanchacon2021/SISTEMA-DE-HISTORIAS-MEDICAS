<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<head>
<title>Botón de Ayuda</title> 
<style> #helpButton { 
    
    position: fixed; 
    top: 10px; right: 
    10px; padding: 
    10px 20px; 
    background-color: #007BFF; 
    color: white; border: none; 
    border-radius: 5px; 
    cursor: pointer; } 
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
<a style="background-color:rgb(220 38 38);" href="MANUAL DE USUARIO.pdf" id="helpButton">Ayuda</a>

    <article class="inicio bg-white py-8 px-4">
        <!-- <h1 class="texto-inicio">Hola, Bienvenido</h1> -->
    </article>

    <div class="fondo">
        <img src="img/logo.png" alt="">
    </div>
</body>
</html>