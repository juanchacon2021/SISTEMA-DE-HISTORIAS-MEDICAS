<?php
if(!is_file("modelo/".$pagina.".php")){
    echo "Falta el modelo";
    exit;
}

require_once("modelo/".$pagina.".php"); 

if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        $o = new entrada();
        if($_POST['accion']=='entrar'){
            // Iniciar sesión para validar CAPTCHA
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $mensaje = "";
            $captcha_valido = false;
            
            // Validar que exista el CAPTCHA en sesión y en el formulario
            if(isset($_SESSION['captcha_code']) && isset($_POST['captcha'])){
                $captcha_valido = (strtolower(trim($_POST['captcha'])) === strtolower(trim($_SESSION['captcha_code'])));
            }
            
            if(!$captcha_valido){
                $mensaje = "El código CAPTCHA es incorrecto o ha expirado";
                // Regenerar CAPTCHA después de un intento fallido
                $_SESSION['captcha_code'] = substr(md5(uniqid(rand(), true)), 0, 6);
            } else {
                $o->set_email($_POST['email']);
                $o->set_clave($_POST['clave']);  
                $m = $o->existe();
                
                if($m['resultado']=='existe'){
                    // Limpiar CAPTCHA de la sesión
                    unset($_SESSION['captcha_code']);
                    
                    // Regenerar ID de sesión por seguridad
                    session_regenerate_id(true);
                    
                    $_SESSION['nivel'] = $m['mensaje'];
                    $_SESSION['usuario'] = $m['usuario'];
                    $_SESSION['nombre'] = $m['nombre'];
                    $_SESSION['permisos'] = $m['permisos'];
                    
                    // Registrar inicio de sesión en bitácora
                    if(is_file("modelo/bitacora.php")) {
                        require_once("modelo/bitacora.php");
                        bitacora::registrar('Inicio de sesión', 'Usuario inició sesión');
                    }
                    
                    header('Location: . ');
                    exit();
                } else {
                    $mensaje = $m['mensaje'];
                    // Regenerar CAPTCHA después de un intento fallido
                    $_SESSION['captcha_code'] = substr(md5(uniqid(rand(), true)), 0, 6);
                }
            }
        }
    }
    
    require_once("vista/".$pagina.".php"); 
} else {
    echo "Falta la vista";
}
?>