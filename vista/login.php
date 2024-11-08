<!DOCTYPE html>
<?php 
        require_once("comunes/encabezado.php"); 
?>
<head>
</head>

<body id="fond">
    <img class="wave" src="img/wave.png">
    <div class="container-login">
        <div class="img">
        <img src="img/bg.svg">
    </div>
    <div class="login-content">
        <form method="POST" action="" class="formulario-login">
            <center><img src="img/logo.png" alt="logo"></center>
            <h2 class="title">BIENVENIDO</h2>

            <div class="input-div one">
                <div class="i">
                    <img src="img/user.svg" alt="">
                </div>
                <div class="div">
                    <input id="usuario" type="text" class="input border-0" name="usuario" placeholder="Usuario">
                </div>
            </div>
            <div class="input-div pass">
                <div class="i">
                    <img src="img/lock.svg" alt="">
                </div>
                <div class="div">
                    <input type="password" id="input" class="input" name="clave" placeholder="Contraseña">
                </div>
            </div>

            <div class="text-center">
                <a class="enlace font-italic isai5" href="?pagina=principal">saltar</a>
            </div>
            <input name="btningresar" class="btn botoncito" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>

</body>

</html>