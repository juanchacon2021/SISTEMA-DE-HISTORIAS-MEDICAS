<!DOCTYPE html>
<?php 
    require_once("comunes/encabezado.php"); 
?>
<?php require_once("comunes/modal.php"); ?>
<!--Div oculta para colocar el mensaje a mostrar-->
<div id="mensajes" style="display:none">
<?php
    if(!empty($mensaje)){
        echo $mensaje;
    }
?>    
</div>
<head>
</head>

<body id="fond">
    <img class="wave" src="img/wave.png">
    <div class="container-login">
        <div class="img">
            <img src="img/bg.svg">
        </div>
    <div class="login-content">
        <form method="POST" action="" id="f" class="formulario-login">
        <input type="text" name="accion" id="accion" style="display:none"/>
        <center><img src="img/logo.png" alt="logo"></center>
            <h2 class="title">BIENVENIDO</h2>

            <div class="input-div one">
                <div class="i">
                    <img src="img/user.svg" alt="">
                </div>
                <div class="div">
                    <input id="email" type="email" class="input border-0 form-control" name="email" placeholder="Email">
                    <span id="semail"></span>
                </div>
            </div>
            <div class="input-div pass">
                <div class="i">
                    <img src="img/lock.svg" alt="">
                </div>
                <div class="div">
                    <input type="password" id="clave" class="input form-control" name="clave" placeholder="Contraseña">
                    <span id="sclave"></span>
                </div>
            </div>

            
            <input id="entrar" name="btningresar" class="btn botoncito" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>
   <script type="text/javascript" src="js/login.js"></script>
</body>
</html>