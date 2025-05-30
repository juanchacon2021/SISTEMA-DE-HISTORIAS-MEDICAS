<!DOCTYPE html>
<?php 
    require_once("comunes/encabezado.php"); 
    $captcha_code = substr(md5(uniqid(rand(), true)), 0, 6);
    $_SESSION['captcha_code'] = $captcha_code;
?>
<?php require_once("comunes/modal.php"); ?>
<div id="mensajes" style="display:none">
<?php
    if(!empty($mensaje)){
        echo $mensaje;
    }
?>    
</div>
<head>
    <style>
        .captcha-container {
            margin: 50px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .captcha-code {
            background: #f0f0f0;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
            letter-spacing: 2px;
            user-select: none;
            flex-grow: 1;
            text-align: center;
        }
    </style>
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
            
            <!-- CAPTCHA -->
            <div class="input-div">
                <div class="captcha-container">
                    <div class="captcha-code" id="captcha-code"><?php echo $captcha_code; ?></div>
                </div>
                <div class="div">
                    <input type="text" id="captcha" class="input form-control" name="captcha" placeholder="Ingrese el código CAPTCHA">
                    <span id="scaptcha"></span>
                </div>
            </div>

            <input id="entrar" name="btningresar" class="btn botoncito" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>
   <script type="text/javascript" src="js/login.js"></script>
</body>
</html>